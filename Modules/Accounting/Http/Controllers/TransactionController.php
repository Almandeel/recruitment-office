<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\{Transaction, Account, Safe};
use Modules\Employee\Models\{Employee};
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{
    public function __construct() {
        $this->middleware('year.activated')->only(['create','store']);
        $this->middleware('year.opened')->only(['create','store']);
        
        $this->middleware('permission:transactions-create')->only(['create', 'store']);
        $this->middleware('permission:transactions-read')->only(['index', 'show']);
        $this->middleware('permission:transactions-update')->only(['edit', 'update']);
        $this->middleware('permission:transactions-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $status = $request->has('status') ? $request->status : 'waiting';
        $year = $request->has('year') ? $request->year : date('Y');
        $month = $request->has('month') ? $request->month : date('m');
        
        $statuses = \App\Traits\Statusable::$STATUSES;
        $types = Transaction::TYPES;
        $type = $request->has('type') ? $request->type : 'all';
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $transactions = Transaction::orderBy('created_at', 'DESC');
        $transactions = $transactions->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($type != 'all') {
            $transactions = $transactions->where('type', $type);
        }
        // if ($status != 'all') {
        //     $transactions = $transactions->where('status', $status);
        // }
        $transactions = $transactions->get();
        // dd($transactions->first()->getStatus());
        return view('accounting::transactions.index', compact('transactions', 'statuses', 'status', 'types', 'type', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        $employees = Employee::all();
        $employee = $request->has('employee_id') ? Employee::findOrFail($request->employee_id) : null;
        $accounts = Account::secondaryAccounts();
        $safes = Safe::all();
        return view('accounting::transactions.create', compact('employee', 'employees', 'accounts', 'safes'));
    }
    
    /**
    * Transaction a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
                'amount'      => 'required | numeric',
                // 'safe_id'      => 'required | numeric',
                // 'account_id'      => 'numeric',
            ]);
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            $transaction = Transaction::create($request->except(['_token']));
            if ($transaction) {
                if (!$transaction->isDeducation()) {
                    $transaction->confirm($request->safe_id, $request->account_id, $request->amount, $request->details);
                    $transaction->attach();
                }
            }
            return redirect()->route('accounting.transactions.show', $transaction)->with('success', 'تمت المعاملة بنجاح');
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function show(Transaction $transaction)
    {
        return view('accounting::transactions.show', compact('transaction'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function edit(Transaction $transaction)
    {
        $employees = Employee::all();
        $employee = $transaction->employee;
        $year_month = explode('-', $transaction->month);
        $year = $year_month[0];
        $month = $year_month[0];
        return view('accounting::transactions.edit', compact('transaction', 'employees', 'employee', 'year', 'month'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Transaction $transaction)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'amount'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            $transaction->update($request->all());
            
            
            return back()->withSuccess('تم تعديل المعاملة بنجاح');
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function destroy(Transaction $transaction)
    {
        $previous_url = url()->previous();
        $show_url = route('accounting.transactions.show', $transaction);
        $transaction->delete();
        if($previous_url == $show_url){
            return redirect()->route('accounting.transactions.index')->with('success', __('transactions.delete_success'));
        }
        return back()->with('success', __('transactions.delete_success'));
    }
}