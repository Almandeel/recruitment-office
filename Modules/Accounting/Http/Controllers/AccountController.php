<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Modules\Services\Models\Customer;
use Carbon\Carbon;
class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:accounts-create')->only(['create','store']);
        $this->middleware('permission:accounts-read')->only(['index', 'show']);
        $this->middleware('permission:accounts-update')->only(['edit', 'update']);
        $this->middleware('permission:accounts-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        // $roots = Account::roots(true);
        // $account = isset($request->account_id) ? Account::find($request->account_id) : $roots->first();
        // $account = (isset($request->account_id) && !$account) ? $roots->first() : $account;
        // $crumbs = [
        // [route('accounts.index'), __('accounting::accounting.tree')]
        // ];
        // foreach ($account->parents(true) as $parent) {
        //     $crumbs[] = [route('accounts.show', $parent), $parent->name];
        // }
        // $crumbs[] = ['#', $account->name];
        // // dd(finalAccount());
        // return view('accounting::accounts.index-old', compact('account', 'roots', 'crumbs'));
        $accounts = Account::all();
        // dd($accounts);
        return view('accounting::accounts.index', compact('accounts'));
    }
    
    public function create()
    {
        return view('accounting::accounts.create');
    }
    
    public function show(Request $request, Account $account)
    {
        if($request->has('account_id')){
            $params = array_merge(['account' => $request->account_id], $request->except('_token', 'account_id'));
            return redirect()->route('accounts.show', $params);
        }
        $layout = $request->layout == 'print' ? 'layouts.print' : 'accounting::layouts.master';
        $view = $request->has('view') ? $request->view : 'show';
        $crumbs[] = [route('accounts.index'), __('accounting::global.accounts')];
        foreach ($account->parents(true) as $parent) {
            $crumbs[] = [route('accounts.show', $parent), $parent->name];
        }
        
        // dd(__('accounting::global.delete_success'));
        $title = $view == 'statement' ? 'كشف حساب: ' . $account->name : $account->name;
        
        if($view == 'statement'){
            $crumbs[] = [route('accounts.show', $account), $account->name];
            $crumbs[] = ['#', __('accounting::accounts.statement')];
            $accounts = Account::getEntryables();
            $debts = $account->debts();
            $credits = $account->credits();
            $opening_balance = ['debt', 0, null];
            
            // $from_date = !$request->has('from_date') &&  ? $request->from_date : date('Y-m-d');
            $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
            $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
            
            
            if(count($debts) || count($credits)){
                $first_date = $account->currentEntries()->first()->entry_date;
                $last_date = date('Y-m-d', strtotime(Carbon::parse($first_date)->subDays(1)->toDateTimeString()));
                $last_debts = $debts->whereBetween('entry_date', [$first_date, $last_date]);
                $last_credits = $credits->whereBetween('entry_date', [$first_date, $last_date]);
                
                $debtsAmount = $last_debts->sum('pivot.amount');
                $creditsAmount = $last_credits->sum('pivot.amount');
                
                if($debtsAmount > $creditsAmount){
                    $opening_balance[0] = 'debt';
                    $opening_balance[1] = $debtsAmount - $creditsAmount;
                }
                if($creditsAmount > $debtsAmount){
                    $opening_balance[0] = 'credit';
                    $opening_balance[1] = $creditsAmount - $debtsAmount;
                }
                
                if(count($last_debts)){
                    if(count($last_credits)){
                        if($last_debts->last()->getDate('Ymd') > $last_credits->last()->getDate('Ymd')){
                            $opening_balance[2] = $last_debts->last()->getDate();
                        }
                        else{
                            $opening_balance[2] = $last_credits->last()->getDate();
                        }
                    }else{
                        $opening_balance[2] = $last_debts->last()->getDate();
                    }
                }
                else if(count($last_credits)){
                    if(count($last_debts)){
                        if($last_credits->last()->getDate('Ymd') > $last_debts->last()->getDate('Ymd')){
                            $opening_balance[2] = $last_credits->last()->getDate();
                        }
                        else{
                            $opening_balance[2] = $last_debts->last()->getDate();
                        }
                    }else{
                        $opening_balance[2] = $last_credits->last()->getDate();
                    }
                }
                
            }
            // $debts_from_date
            $debts = $debts->whereBetween('entry_date', [$from_date, $to_date])->unique();
            $credits = $credits->whereBetween('entry_date', [$from_date, $to_date])->unique();
            // dd($debts->first()->delete());
            $options = [
            'title' => __('accounting::accounts.statement') . ': ' . $account->id . '-' . $account->name,
            'datatable' => true,
            'accounting_modals' => ['account'],
            ];
            if($layout == 'layouts.print'){
                $options = [
                'title' => $title,
                'heading' => __('accounting::accounts.statement') . '<br>' . $account->display(),
                ];
            }
            $compact = compact('account', 'accounts', 'options', 'debts', 'credits', 'opening_balance', 'from_date', 'to_date');
        }else{
            $crumbs[] = ['#', $account->name];
            
            $debts_from_date = $request->debts_from_date ? $request->debts_from_date : date('Y-m-d');
            $debts_to_date = $request->debts_to_date ? $request->debts_to_date : date('Y-m-d');
            
            $credits_from_date = $request->credits_from_date ? $request->credits_from_date : date('Y-m-d');
            $credits_to_date = $request->credits_to_date ? $request->credits_to_date : date('Y-m-d');
            
            $debts = $account->debts();
            $credits = $account->credits();
            
            $costs = $account->costsCenters();
            $profits = $account->profitsCenters();
            $debts = $debts->whereBetween('entry_date', [$debts_from_date, $debts_to_date])->sortByDesc('entry_date');
            $credits = $credits->whereBetween('entry_date', [$credits_from_date, $credits_to_date])->sortByDesc('entry_date');
            // dd($profits);
            $options = [
            'title' => $title,
            'datatable' => true,
            // 'accounting_modals' => ['account'],
            'crumbs' => $crumbs,
            ];
            if($layout == 'layouts.print'){
                $options = [
                'title' => $title,
                'heading' => $title,
                ];
            }
            $compact = compact('account', 'options', 'debts_from_date', 'debts_to_date', 'credits_from_date', 'credits_to_date', 'debts', 'credits', 'costs', 'profits');
        }
        $compact = array_merge(compact('layout', 'title', 'view', 'crumbs'), $compact);
        // dd($compact, $crumbs);
        return view('accounting::accounts.' . $view, $compact);
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|min:3|max:40',
        ]);
        $data = $request->except(['_token']);
        // dd($data);
        if(array_key_exists('final_account', $data)){
            if(!is_numeric($data['final_account'])){
                unset($data['final_account']);
            }
        }
        $account = Account::create($data);
        if($account->main_account == Account::ACCOUNT_CUSTOMERS){
            $customer = Customer::create([
            'id' => $account->id,
            'name' => $account->name,
            'address' => $request->address,
            'phones' => $request->phones,
            ]);
            $customer->account()->save($account);
        }
        if($request->next == 'save_new'){
            return redirect()->route('accounts.create')->with('success', __('accounting::global.create_success'));
        }
        else if($request->next == 'save_list'){
            return redirect()->route('accounts.index')->with('success', __('accounting::global.create_success'));
        }
        else if($request->next == 'save_show'){
            return redirect()->route('accounts.show', $account)->with('success', __('accounting::global.create_success'));
        }else{
            return back()->with('success', __('accounting::global.create_success'));
        }
        
        return back()->with('error', __('accounting::global.create_fail'));
    }
    
    public function edit(Account $account)
    {
        $crumbs[] = [route('accounts.index'), __('accounting::global.accounts')];
        foreach ($account->parents(true) as $parent) {
            $crumbs[] = [route('accounts.show', $parent), $parent->name];
        }
        $crumbs[] = [route('accounts.show', $account), $account->name];
        $crumbs[] = ['#', __('accounting::global.edit')];
        return view('accounting::accounts/edit', compact('account', 'crumbs'));
        // if(!$account->isRoot()){
        // }else{
        //     return back()->with('error', 'لا يمكن تعديل حساب جزري');
        // }
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Account  $account
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Account $account)
    {
        $request->validate([
        'name' => 'required | string | min:5 | max:40',
        ]);
        
        if($account->isRoot()){
            $data = $request->only(['name']);
        }else{
            $data = $request->except(['_token', '_method']);
        }
        if(array_key_exists('final_account', $data)){
            if(!is_numeric($data['final_account'])){
                unset($data['final_account']);
            }
        }
        
        // dd($data);
        
        $updated = $account->update($data);
        
        if($account->main_account == Account::ACCOUNT_CUSTOMERS && $account->accountable){
            $account->accountable->update([
            'name' => $account->name,
            'address' => $request->address,
            'phones' => $request->phones,
            ]);
        }
        
        if ($updated) {
            if($request->next == 'save_edit'){
                return redirect()->route('accounts.edit', $account)->with('success', __('accounting::global.update_success'));
            }
            else if($request->next == 'save_list'){
                return redirect()->route('accounts.index')->with('success', __('accounting::global.update_success'));
            }
            else if($request->next == 'save_show'){
                return redirect()->route('accounts.show', $account)->with('success', __('accounting::global.update_success'));
            }else{
                return back()->with('success', __('accounting::global.update_success'));
            }
        }
        
        return back()->with('error', __('accounting::global.update_fail'));
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Account  $account
    * @return \Illuminate\Http\Response
    */
    public function destroy(Account $account)
    {
        // dd($account);
        if(!$account->isDefault()){
            $previous_url = url()->previous();
            $show_url = route('accounts.show', $account);
            $account->delete();
            if($previous_url == $show_url){
                return redirect()->route('accounts.index')->with('success', __('accounting::accounts.delete_success'));
            }
            return back()->with('success', __('accounting::accounts.delete_success'));

            // $prev_url = route('accounts.show', $account);
            // $account->delete();
            
            
            // return back()->with('success', __('accounting::global.delete_success'));
            // return redirect()->route('accounts.index')->with('success', __('accounting::global.delete_success'));
        }else{
            return back()->with('error', 'لا يمكن حذف حساب جزري');
        }
    }
}