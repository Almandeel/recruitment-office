<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use Modules\Accounting\Models\Safe;
use Modules\Accounting\Models\Account;

class SafeController extends Controller
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
    * @return Response
    */
    public function index()
    {
        $banks = Safe::banks();
        $cashes = Safe::cashes();
        // dd($cashes->first()->account->balance());
        return view('accounting::safes.index', compact('banks', 'cashes'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $accounts = roots();
        return view('accounting::safes.create', compact('accounts'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        
        $request->validate([
        'name' => 'required|string|max:45'
        ]);
        $safe = Safe::create($request->except('_token'));
        $tab = !isset($request->active_tab) ? ($safe->isBank() ? 'banks' : 'cashes') : $request->active_tab;
        
        return back()->with('success', __('accounting::safes.create_success'))->with('tab', $tab);
        
        
        // if($request->next == 'save_new'){
        //     return redirect()->route('safes.create')->with('success', __('accounting::safes.create_success'));
        // }
        // else if($request->next == 'save_list'){
        //     return redirect()->route('safes.index')->with('success', __('accounting::safes.create_success'));
        // }
        // else if($request->next == 'save_show'){
        //     return redirect()->route('safes.show', $safe)->with('success', __('accounting::safes.create_success'));
        // }else{
        //     return back()->with('success', __('accounting::safes.create_success'))->with('tab', $tab);
        // }
    }
    
    /**
    * Show the specified resource.
    * @param Safe $safe
    * @return Response
    */
    public function show(Request $request, Safe $safe)
    {
        
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        // $accounts = Account::secondaryAccounts();
        $active_tab = isset($request->active_tab) ? $request->active_tab : 'default';
            $transfers = $safe->transfers;
            $expenses = $safe->expenses;
            $transfer_accounts = Account::getAll(true);
            $debts = $safe->debts();
            $credits = $safe->credits();
            // dd($debts, $credits);
            // dd($safe->balance());
            return view('accounting::safes.show', compact('safe', 'expenses', 'debts', 'credits', 'active_tab', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param Safe $safe
    * @return Response
    */
    public function edit(Safe $safe)
    {
        // $accounts = roots();
        // return view('accounting::safes.edit', compact('accounts', 'safe'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param Safe $safe
    * @return Response
    */
    public function update(Request $request, Safe $safe)
    {
        if(isset($request->account_id)){
            if($request->operation == 'add'){
                if(!$safe->accounts->contains($request->account_id)){
                    $safe->accounts()->attach($request->account_id);
                }
                return back()->with('success', __('accounting::safes.add_account_success'));
            }
            else if($request->operation == 'remove'){
                if($safe->accounts->contains($request->account_id)){
                    $safe->accounts()->detach($request->account_id);
                }
                return back()->with('success', __('accounting::safes.remove_account_success'));
            }
        }
        $request->validate([
        'name' => 'required|string|max:45'
        ]);
        $tab = !isset($request->active_tab) ? ($safe->isBank() ? 'banks' : 'cashes') : $request->active_tab;
        
        $safe->update($request->except('_token', '_method'));
        
        return back()->with('success', __('accounting::safes.update_success'))->with('tab', $tab);
        
        // if($request->next == 'save_edit'){
        //     return redirect()->route('accounts.edit', $account)->with('success', __('accounting::safes.update_success'));
        // }
        // else if($request->next == 'save_list'){
        //     return redirect()->route('accounts.index')->with('success', __('accounting::safes.update_success'));
        // }
        // else if($request->next == 'save_show'){
        //     return redirect()->route('accounts.show', $account)->with('success', __('accounting::safes.update_success'));
        // }else{
        //     return back()->with('success', __('accounting::safes.create_success'))->with('tab', $tab);
        // }
    }
    
    public function confirm(Request $request){
        $request->validate([
        'safeable_type' => 'required',
        'safeable_id' => 'required',
        ]);
        $safeableType = $request->safeable_type;
        $safeable = $safeableType::findOrFail($request->safeable_id);
        // dd($request->all());
        $safeable->confirm();
        return back()->withSuccess(__('accounting::global.confirm_success'));
    }
    
    public function entryConfirm(Request $request){
        if($request->has('id') && $request->has('type')){
            $id = $request->id;
            $type = $request->type;
            $safeable = $type::findOrFail($id);
            $succeeded = false;
            $msg = __('global.confirm_success');
            $entry = $safeable->entryConfirm($request);
            $succeeded = !is_null($entry);
            if($succeeded){
                return back()->withSuccess($msg);
            }
            
            return back()->withError(__('global.confirm_fail'));
        }
    }
    
    /**
    * Remove the specified resource from storage.
    * @param Safe $safe
    * @return Response
    */
    public function destroy(Safe $safe)
    {
        $tab = !isset($request->active_tab) ? ($safe->isBank() ? 'banks' : 'cashes') : $request->active_tab;
        
        $previous_url = url()->previous();
        $show_url = route('safes.show', $safe);
        $safe->delete();
        if($previous_url == $show_url){
            return redirect()->route('safes.index')->with('success', __('accounting::safes.delete_success'))->with('tab', $tab);
        }
        return back()->with('success', __('accounting::safes.delete_success'))->with('tab', $tab);
        
    }
}