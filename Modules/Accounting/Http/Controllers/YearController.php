<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Models\Branch;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountEntry;
use Modules\Accounting\Models\Currency;
use Modules\Accounting\Models\Entry;
use Modules\Accounting\Models\Balance;
use Modules\Accounting\Models\Year;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class YearController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:create-year')->only(['create','store']);
        // $this->middleware('permission:read-year')->only(['index', 'show']);
        // $this->middleware('permission:update-year')->only(['edit', 'update']);
        // $this->middleware('permission:delete-year')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $years = Year::all();
        $classes = [
        Year::STATUS_OPENED => 'success',
        Year::STATUS_CLOSED => 'danger',
        Year::STATUS_ARCHIVED => 'warning',
        ];
        // dd(year());
        // dd(Account::with('entries')->get()->toJson());
        return view('accounting::years.index', compact('years', 'classes'));
    }
    
    public function closing(Request $request, Year $year)
    {
        // $entries = [];
        // switch ($request->step) {
        //     case 'use_entries':
        //         $entries = $year->entries->where('type', Entry::TYPE_USE);
        //         break;
        //     case 'close_entries':
        //         $entries = $year->entries->where('type', Entry::TYPE_CLOSE);
        //         break;
        
        //     default:
        //         break;
        // }
        // // dd($year->entries);
        // $compact = compact('year');
        // $compact['entries'] = $entries;
        return view('accounting::years.close', compact('year'));
    }
    
    public function close(Request $request, Year $year)
    {
        // $close_entries = $year->entries->where('type', Entry::TYPE_CLOSE);
        // if($close_entries->count()){
        //     // dd($close_entries);
        //     foreach ($close_entries as $entry) {
        //         $entry->delete();
        //     }
        // }
        
        // if($request->operation == 'close'){
        //     $data = $request->except(['_token', 'operation']);
        //     return redirect()->route('years.index', $branch)->with('success', __('accounting::years.closed'));
        // }else{
        //     $income_summary = Account::find($request->income_summary);
        //     $capital = Account::find($request->capital);
        //     $debts_accounts = [];
        //     $credits_accounts = [];
        //     $debts_total = 0;
        //     $credits_total = 0;
        //     for ($i=0; $i < count($request->debts_accounts); $i++) {
        //         $debts_accounts[$i] = Account::find($request->debts_accounts[$i]);
        //         $credits_accounts[$i] = Account::find($request->credits_accounts[$i]);
        //         $debts_total += $debts_accounts[$i]->balance()->getAmount();
        //         $credits_total += $credits_accounts[$i]->balance()->getAmount();
        //     }
        //     $caps_debt = [];
        //     $caps_credit = [];
        //     for ($i=0; $i < count($request->caps_debt); $i++) {
        //         $caps_debt[$i] = Account::find($request->caps_debt[$i]);
        //         $caps_credit[$i] = Account::find($request->caps_credit[$i]);
        
        //     }
        //     // dd($caps_debt[0]->name());
        //     return view('accounting::years.close', compact('year', 'income_summary', 'capital', 'debts_accounts', 'credits_accounts', 'debts_total', 'credits_total', 'caps_debt', 'caps_credit'));
        // }
        
        for ($i=0; $i < count($request->entries); $i++) {
            $entry_number = $request->entries[$i];
            $debts_accounts = 'debts_accounts' . $entry_number;
            $debts_amounts = 'debts_amounts' . $entry_number;
            $credits_accounts = 'credits_accounts' . $entry_number;
            $credits_amounts = 'credits_amounts' . $entry_number;
            
            if(!(is_null($request->$debts_amounts) || is_null($request->$debts_accounts) || is_null($request->$credits_amounts) || is_null($request->$credits_accounts))){
                if(count($request->$debts_amounts) == count($request->$debts_accounts) && count($request->$credits_amounts) == count($request->$credits_accounts)){
                    $details = $request->details[$i];
                    $amount = array_sum($request->$debts_amounts);
                    $type = Entry::TYPE_CLOSE;
                    $entry = Entry::create([
                        'amount' => $amount,
                        'details' => $details,
                        'type' => $type,
                    ]);
                    
                    for ($d=0; $d < count($request->$debts_amounts); $d++) {
                        $amount = $request->$debts_amounts[$d];
                        $account = $request->$debts_accounts[$d];
                        $side = Entry::SIDE_DEBTS;
                        $entry->accounts()->attach($account, [
                        'side' => $side,
                        'amount' => $amount,
                        ]);
                    }
                    
                    for ($c=0; $c < count($request->$credits_amounts); $c++) {
                        $amount = $request->$credits_amounts[$c];
                        $account = $request->$credits_accounts[$c];
                        $side = Entry::SIDE_CREDITS;
                        $entry->accounts()->attach($account, [
                        'side' => $side,
                        'amount' => $amount,
                        ]);
                    }
                }
            }
        }
        $year->update([
        'closed_at' => $request->closed_at,
        'status' => Year::STATUS_CLOSED,
        ]);
        return redirect()->route('years.index')->withSuccess('تم إقفال السنة المالية بنجاح');
        
        return back()->withError('القيود غير متساوية يرجى مراجعة القيود');
    }
    public function incomeStatement(Request $request, Year $year){
        $revenues_account = revenuesAccount();
        $expenses_account = expensesAccount();
        $revenues_amount = $revenues_account->balances(false, $year->id);
        $expenses_amount = $expenses_account->balances(false, $year->id);
        // dd($revenues_account->balances(true));
        return view('accounting::years.income_statement', compact('year', 'revenues_account', 'expenses_account', 'revenues_amount', 'expenses_amount'));
    }
    public function trialBalance(Request $request, Year $year){
        $by = $request->by ? $request->by : 'totals';
        $compact =  compact('year', 'by');
        // if($by == 'balances'){
        //     $debts = Account::where('type', Account::TYPE_SECONDARY)->where('side', Account::SIDE_DEBT)->get();
        //     $credits = Account::where('type', Account::TYPE_SECONDARY)->where('side', Account::SIDE_CREDIT)->get();
        //     $compact['debts'] = $debts;
        //     $compact['credits'] = $credits;
        // }
        // dd($debts, $credits);
        return view('accounting::years.trial_balance',$compact);
    }
    public function balanceSheet(Request $request, Year $year){
        $type = $request->type ? $request->type : 'accounts';
        // $cash = Account::find(12122);
        // dd($cash->debts()->pluck('pivot')->toArray());
        // dd(Entry::where('id', 'like', $year->id . '%')->get());
        $accounts = Account::allToJson(true, $year->id);
        return view('accounting::years.balance_sheet', compact('year', 'type', 'accounts'));
    }
    
    public function create(Request $request)
    {
        $years = Year::all();
        $last_year = $years->count() ? $years->last() : null;
        if($request->has('last_year')){
            $last_year = Year::findOrFail($request->last_year);
        }
        // dd($last_year->accounts());
        return view('accounting::years.create', compact('years','last_year'));
    }
    
    public function show(Year $year)
    {
        
        return view('accounting::years.show', compact('year'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // dd($request);
        request()->validate([
        'id' => 'unique:years',
        'opened_at' => 'required',
        ]);
        $data = $request->except(['_token', 'next']);
        // $ym = date('Ym', strtotime($request->opened_at . '00:00:00'));
        // $id = $ym;
        // $data['id'] = $id;
        $date = \Carbon\Carbon::parse($request->opened_at);
        $id = $date->format('Ym');
        $data['id'] = $id;
        if(!Year::find($id)){
            $year = Year::create($data);
            if($request->move_accounts_balances && $request->last_year && $request->accounts){
                for ($i=0; $i < count($request->accounts); $i++) {
                    
                    $account = $request->accounts[$i];
                    $balance_field = 'balance_' . $account;
                    $balance = $request->$balance_field;
                    $transfer_field = 'transfer_' . $account;
                    $transfer = $request->$transfer_field;
                    if($transfer){
                        $account_details = Account::findOrFail($account);
                        $entry = Entry::create([
                        'amount' => $balance,
                        'details' => 'عبارة عن قيد افتتاحي لترحيل الحساب رقم: ' . $account . ' من الحساب رقم: ' . $transfer . ' من السنة المالية: ' . $request->last_year,
                        'type' => Entry::TYPE_OPEN,
                        ]);
                        $debt = $account_details->isDebt() ? $account : $transfer;
                        $credit = $account_details->isCredit() ? $account : $transfer;
                        $entry->accounts()->attach($debt, [
                        'side' => Entry::SIDE_DEBTS,
                        'amount' => $balance,
                        ]);
                        $entry->accounts()->attach($credit, [
                        'side' => Entry::SIDE_CREDITS,
                        'amount' => $balance,
                        ]);
                    }
                }
            }
            if ($year) {
                $request->session()->put('year', \Modules\Accounting\Models\Year::active());
            }
            if($request->next == 'save_new'){
                return redirect()->route('years.create')->with('success', __('accounting::global.create_success'));
            }
            else if($request->next == 'save_list'){
                return redirect()->route('years.index')->with('success', __('accounting::global.create_success'));
            }
            else if($request->next == 'save_show'){
                return redirect()->route('years.show', $year)->with('success', __('accounting::global.create_success'));
            }
            return back()->with('error', __('accounting::global.create_fail'));
        }else{
            return back()->with('error', __('accounting::years.errors.id_exists'));
        }
        
    }
    
    public function setEntries($group, $entry, $year, $last_year){
        foreach($group->accounts as $account){
            if($account->balance(false, $last_year->id)->getAmount()){
                if($account->balance(false, $last_year->id)->getType() == Balance::TYPE_DEBT){
                    AccountEntry::create([
                    'type' => AccountEntry::TYPE_DEBT,
                    'amount' => $account->balance(false, $last_year->id)->getAmount(),
                    'account_id' => $account->id,
                    'year_id' => $year->id,
                    'entry_id' => $entry->id,
                    'currency_id' => $year->currency->id,
                    ]);
                }
                elseif($account->balance(false, $last_year->id)->getType() == Balance::TYPE_CREDIT){
                    AccountEntry::create([
                    'type' => AccountEntry::TYPE_CREDIT,
                    'amount' => $account->balance(false, $last_year->id)->getAmount(),
                    'account_id' => $account->id,
                    'year_id' => $year->id,
                    'entry_id' => $entry->id,
                    'currency_id' => $year->currency->id,
                    ]);
                }
            }
        }
        foreach($group->groups as $group){
            $this->setEntries($group, $entry, $year, $last_year);
        }
    }
    public function edit(Year $year)
    {
        
        $years = Year::all();
        return view('accounting::years/edit', compact('years', 'year'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Year  $year
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Year $year)
    {
        $request->validate([
        // 'opened_at' => 'required | date',
        ]);
        $data = $request->except(['_token', '_method', 'next', 'opened_at', 'last_year']);
        $year->update($data);
        if ($request->has('status')) {
            if ($request->status == Year::STATUS_OPENED) {
                $request->session()->put('year', $year);
            }
        }
        if($request->next == 'save_edit'){
            return redirect()->route('years.edit', $year)->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_list'){
            return redirect()->route('years.index')->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_show'){
            return redirect()->route('years.show', $year)->with('success', __('accounting::global.update_success'));
        }
        return back()->with('success', __('accounting::global.operation_success'));
        
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Year  $year
    * @return \Illuminate\Http\Response
    */
    public function destroy(Year $year)
    {
        $year->delete();
        
        return redirect()->route('years.index')->with('success', __('accounting::global.delete_success'));
    }
}