<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Employee\Models\{Custody, Account, Employee};
use Modules\Accounting\Models\Entry;
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
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $from_date = $request->has('from_date') ? $request->from_date : $from_date;
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $status = $request->has('status') ? $request->status : 'open';
        $employee_id = $request->has('employee_id') ? $request->employee_id : 'all';
        $employees = Employee::all();
        $statuses = Custody::STATUSES;
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';

        if(auth()->user()->hasPermission('custodies-delete')) {
            $custodies = Custody::orderBy('created_at', 'DESC');
        }else {
            $custodies = Custody::where('employee_id', auth()->user()->employee_id)->orderBy('created_at', 'DESC');
        }

        $custodies = $custodies->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($employee_id != 'all') {
            $custodies = $custodies->where('employee_id', $employee_id);
        }
        $custodies = $custodies->get();
        if ($status != 'all') {
            $custodies = $custodies->filter(function($custody) use($status){
                return $custody->checkStatus($status);
            });
        }
        // dd($custodies->modelKeys());
        return view('employee::custodies.index', compact('custodies', 'statuses', 'status', 'employees', 'employee_id', 'from_date', 'to_date'));
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
        return view('employee::custodies.create', compact('employee', 'employees'));
    }
    
    /**
    * Custody a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'amount'      => 'required|numeric',
            'currency'      => 'required|string',
            'details'      => 'string|nullable',
            ]);
            $request['amount'] = [$request->amount, $request->currency];
            $data = $request->except('_token');
            // dd($data);
            $custody = Custody::create($data);
            if ($custody) {
                $custody->attach();
            }
            if ($request->has(['entry_date', 'entry_amount', 'debt_account', 'credit_account'])) {
                if (!(is_null($request->entry_amount) || is_null($request->debt_account) || is_null($request->credit_account))) {
                    $custody->voucher->register([
                    'entry' => [
                    'amount'=> $request->entry_amount,
                    'entry_date'=> $request->entry_date,
                    'details'=> $request->details,
                    ],
                    'debts' => [
                    ['account' => $request->debt_account,
                    'amount' => $request->entry_amount,]
                    ],
                    'credits' => [
                    ['account' => $request->credit_account,
                    'amount' => $request->entry_amount,]
                    ],
                    ]);
                }
            }
            return redirect()->route('custodies.show', $custody)->with('success', __('custodies.create_success'));
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
        return view('employee::custodies.show', compact('custody'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Custody  $custody
    * @return \Illuminate\Http\Response
    */
    public function edit(Custody $custody)
    {
        $employees = Employee::all();
        $employee = $custody->employee;
        // dd($custody->voucher->amount);
        return view('employee::custodies.edit', compact('custody', 'employees', 'employee'));
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
            $request->validate([
            'amount'      => 'required|numeric',
            'currency'      => 'required|string',
            'details'      => 'string|nullable',
            ]);
            $data = $request->except('_token');
            // $data['amount'] = [$request->amount, $request->currency];
            $data['amount'] = json_encode(['value' => $request->amount, 'currency' => $request->currency]);
            // dd($data);
            $custody->update($data);
            
            $entry = $custody->voucher->entry;
            if (is_null($entry)) {
                if ($request->has(['entry_date', 'entry_amount', 'debt_account', 'credit_account'])) {
                    $custody->voucher->register([
                    'entry' => [
                    'amount'=> $request->entry_amount,
                    'entry_date'=> $request->entry_date,
                    'details'=> $request->details,
                    ],
                    'debts' => [
                    ['account' => $request->debt_account,
                    'amount' => $request->entry_amount,]
                    ],
                    'credits' => [
                    ['account' => $request->credit_account,
                    'amount' => $request->entry_amount,]
                    ],
                    ]);
                }
            }else{
                $entry_data = [];
                if ($request->details != $entry->details) {
                    $entry_data['entry']['details'] = $request->details;
                }
                if ($request->entry_date != $entry->entry_date) {
                    $entry_data['entry']['entry_date'] = $request->entry_date;
                }
                
                if ($request->entry_amount != $entry->amount) {
                    $entry_data['entry']['amount'] = $request->entry_amount;
                }
                $debt = $entry->debts()->first();
                $debt_id = is_null($debt) ? 0 : $debt->id;
                if ($debt_id != $request->debt_account || $request->entry_amount != $entry->amount) {
                    $entry_data['debts'] = [
                    ['account' => $request->debt_account,
                    'amount' => $request->entry_amount,]
                    ];
                }
                $credit = $entry->credits()->first();
                $credit_id = is_null($credit) ? 0 : $credit->id;
                if ($credit_id != $request->credit_account || $request->entry_amount != $entry->amount) {
                    $entry_data['credits'] = [
                    ['account' => $request->credit_account,
                    'amount' => $request->entry_amount,]
                    ];
                }
                if (array_key_exists('entry', $entry_data)) {
                    $entry->update($entry_data['entry']);
                }
                if (array_key_exists('debts', $entry_data)) {
                    $entry->accounts()->detach($entry->debts()->modelKeys());
                    foreach ($entry_data['debts'] as $debt) {
                        $entry->accounts()->attach($debt['account'], [
                        'amount' => $debt['amount'],
                        'side' => Entry::SIDE_DEBTS,
                        ]);
                    }
                }
                if (array_key_exists('credits', $entry_data)) {
                    $entry->accounts()->detach($entry->credits()->modelKeys());
                    foreach ($entry_data['credits'] as $credit) {
                        $entry->accounts()->attach($credit['account'], [
                        'amount' => $credit['amount'],
                        'side' => Entry::SIDE_CREDITS,
                        ]);
                    }
                }
            }
            
            return back()->withSuccess(__('custodies.update_success'));
        }
        return back()->with('error', 'كلمة المرور خاطئة');
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
        $show_url = route('custodies.show', $custody);
        $custody->delete();
        if($previous_url == $show_url){
            return redirect()->route('custodies.index')->with('success', __('custodies.delete_success'));
        }
        return back()->with('success', __('custodies.delete_success'));
    }
}