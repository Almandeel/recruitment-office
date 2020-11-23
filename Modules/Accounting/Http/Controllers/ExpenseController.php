<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Expense;

class ExpenseController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $expenses = Expense::orderBy('created_at', 'DESC')->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        return view('accounting::expenses.index', compact('expenses', 'from_date', 'to_date'));
    }
    
    public function create()
    {
        return view('accounting::expenses.create');
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
        'amount' => 'required | numeric',
        'safe_id' => 'required | numeric',
        'account_id' => 'required | numeric',
        'details' => 'string|nullable',
        ]);
        Expense::create($request->except(['_token']));
        
        return redirect()->back()->with('success', __('accounting::expenses.create_success'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Expense  $expense
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
        'amount' => 'required | numeric',
        'safe_id' => 'required | numeric',
        'account_id' => 'required | numeric',
        'details' => 'string|nullable',
        ]);
        
        $expense->update($request->except(['_token', '_method']));
        
        return redirect()->back()->with('success', __('accounting::expenses.update_success'));
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Expense  $expense
    * @return \Illuminate\Http\Response
    */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        
        return back()->with('success', __('accounting::expenses.delete_success'));
        return redirect()->route('expenses.index')->with('success', __('accounting::expenses.delete_success'));
    }
}