<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Employee\Models\{Employee};
use Modules\Accounting\Models\{Custody, Account, Entry};
use Illuminate\Routing\Controller;

class CustodyController extends Controller
{
    public function __construct() {
        $this->middleware('year.activated')->only(['create','store']);
        $this->middleware('year.opened')->only(['create','store']);
        
        $this->middleware('permission:custodies-create')->only(['create', 'store']);
        $this->middleware('permission:custodies-read')->only(['index', 'show']);
        $this->middleware('permission:custodies-update')->only(['edit', 'update']);
        $this->middleware('permission:custodies-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $first = Custody::first();
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        if (!$request->has('from_date') && !is_null($first)) {
            $from_date = $first->created_at->format('Y-m-d');
        }
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $status = $request->has('status') ? $request->status : 'open';
        $account_id = $request->has('account_id') ? $request->account_id : 'all';
        $accounts = Account::secondaryAccounts();
        $statuses = Custody::STATUSES;
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $custodies = Custody::orderBy('created_at', 'DESC');
        $custodies = $custodies->whereBetween('created_at', [$from_date_time, $to_date_time]);
        $custodies = $custodies->get();
        if ($account_id != 'all') {
            $custodies = $custodies->filter(function($custody) use($account_id){
                $account = $custody->account;
                if (!is_null($account)) {
                    return $account->id == $account_id;
                }

                return false;
            });
        }
        if ($status != 'all') {
            $custodies = $custodies->filter(function($custody) use($status){
                return $custody->checkStatus($status);
            });
        }
        // dd($custodies->modelKeys());
        return view('accounting::custodies.index', compact('custodies', 'statuses', 'status', 'accounts', 'account_id', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        // $employees = Employee::all();
        // $employee = $request->has('employee_id') ? Employee::findOrFail($request->employee_id) : null;
        $primary_accounts = Account::primaryAccounts();
        $secondary_accounts = Account::secondaryAccounts();
        return view('accounting::custodies.create', compact('primary_accounts', 'secondary_accounts'));
    }
    
    /**
    * Custody a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // dd($request->all());
        if(\Hash::check($request->password, auth()->user()->password)){
            $rules = [
                'amount' => 'required|numeric',
                'debt_account' => 'required',
                'credit_account' => 'required|numeric',
                'details' => 'string|nullable',
            ];
            if ($request->account_name) {
                $rules['main_account'] = 'required|numeric';
                $rules['account_name'] = 'required|string';
            }
            $request->validate($rules);
            
            $data = $request->except('_token');
            $custody = Custody::create($data);
            if ($custody) {
                $custody->attach();
                if ($request->account_name) {
                    $debt_account = Account::create([
                        'name' => $request->account_name,
                        'main_account' => $request->main_account,
                    ]);
                    
                    if ($debt_account) {
                        $request['debt_account'] = $debt_account->id;
                    }
                }
                $custody->register($request->amount, $request->debt_account, $request->credit_account, $request->details);
            }
            
            return redirect()->route('accounting.custodies.show', $custody)->with('success', __('accounting::custodies.create_success'));
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Custody  $custody
    * @return \Illuminate\Http\Response
    */
    public function show(Custody $custody)
    {
        // dd($custody->voucher->created_at, $custody->voucher->entry);
        $debt_account = $custody->debt_account;
        $credit_account = null; //$custody->credit_account;
        return view('accounting::custodies.show', compact('custody', 'debt_account', 'credit_account'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Custody  $custody
    * @return \Illuminate\Http\Response
    */
    public function edit(Custody $custody)
    {
        $primary_accounts = Account::primaryAccounts();
        $secondary_accounts = Account::secondaryAccounts();
        $voucher = $custody->voucher;
        $entry = $custody->entry;
        $debt_account = $custody->debt_account;
        $credit_account = $custody->credit_account;
        // dd($entry->id);
        return view('accounting::custodies.edit', compact('custody', 'primary_accounts', 'secondary_accounts', 'voucher', 'entry', 'debt_account', 'credit_account'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Custody  $custody
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Custody $custody)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $voucher = $custody->voucher;
            $entry = $custody->entry;
            $rules = [
            'amount' => 'required|numeric',
            'debt_account' => 'required',
            'credit_account' => 'required|numeric',
            'details' => 'string|nullable',
            ];
            if ($request->account_name) {
                $rules['main_account'] = 'required|numeric';
                $rules['account_name'] = 'required|string';
            }
            
            $request->validate($rules);
            $old_amount = $custody->amount;
            $data = $request->except('_token');
            $result = $custody->update($data);
            
            if ($result) {
                // $custody->attach();
                if ($request->account_name) {
                    $debt_account = Account::create([
                    'name' => $request->account_name,
                    'main_account' => $request->main_account,
                    ]);
                    
                    if ($debt_account) {
                        $request['debt_account'] = $debt_account->id;
                    }
                }
                
                if (is_null($entry)) {
                    $custody->register($request->amount, $request->debt_account, $request->credit_account, $request->details);
                }else{
                    $voucher->update($request->only(['amount', 'details']));
                    $entry->update($request->only(['amount', 'details']));
                    $entry->accounts()->detach();
                    $entry->accounts()->attach($request->debt_account, [
                    'amount' => $request->amount,
                    'side' => Entry::SIDE_DEBTS,
                    ]);
                    $entry->accounts()->attach($request->credit_account, [
                    'amount' => $request->amount,
                    'side' => Entry::SIDE_CREDITS,
                    ]);
                }
            }
            
            
            return back()->withSuccess(__('accounting::custodies.update_success'));
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }

    public function pay(Request $request, Custody $custody)
    {
        // dd($request->all());
        for ($i=0; $i < count($request->vouchers); $i++) {
            $voucher_number = $request->vouchers[$i];
            $debts_accounts = 'debts_accounts' . $voucher_number;
            $debts_amounts = 'debts_amounts' . $voucher_number;
            $credits_accounts = 'credits_accounts' . $voucher_number;
            $credits_amounts = 'credits_amounts' . $voucher_number;

            $attachment_name = $request->attachments_names[$i];
            $attachment_file = 'attachments_file' . $voucher_number;
            
            if(!(is_null($request->$debts_amounts) || is_null($request->$debts_accounts) || is_null($request->$credits_amounts) || is_null($request->$credits_accounts))){
                if(count($request->$debts_amounts) == count($request->$debts_accounts) && count($request->$credits_amounts) == count($request->$credits_accounts)){
                    $details = $request->details[$i];
                    $debt_amount = $request->$debts_amounts[0];
                    $debt_account = $request->$debts_accounts[0];
                    $voucher = $custody->consum($debt_amount, $debt_account, $details);  
                    if ($voucher) {
                        $voucher->attaching($request->$attachment_name, $request->file($attachment_file));
                    }              
                }
            }
        }

        return back()->withSuccess('تمت عملية التخليص بنجاح.');
    }


    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Custody  $custody
    * @return \Illuminate\Http\Response
    */
    public function destroy(Custody $custody)
    {
        $previous_url = url()->previous();
        $show_url = route('accounting.custodies.show', $custody);
        $custody->delete();
        if($previous_url == $show_url){
            return redirect()->route('accounting.custodies.index')->with('success', __('accounting::custodies.delete_success'));
        }
        return back()->with('success', __('accounting::custodies.delete_success'));
    }
}