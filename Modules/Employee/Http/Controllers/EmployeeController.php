<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Accounting\Models\Account;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Custody;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employees-create')->only(['create', 'store']);
        $this->middleware('permission:employees-read')->only(['index', 'show']);
        $this->middleware('permission:employees-update')->only(['edit', 'update']);
        $this->middleware('permission:employees-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    
    public function dashboard()
    {
        $employeesCount = Employee::count();
        $positionsCount = Position::count();
        $departmentsCount = Department::count();
        
        return view('employee::dashboard', compact('employeesCount', 'positionsCount', 'departmentsCount'));
    }
    
    
    public function index()
    {
        $employees      = Employee::all();
        $positions      = Position::all();
        $departments    = Department::all();
        // $telephoneBook = \Modules\Employee\Models\Employee::whereNotNull('line')->orderBy('department_id')->get();
        // dd($telephoneBook);
        return view('employee::index', compact('employees', 'positions', 'departments'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $positions      = Position::all();
        // $position = $positions->count() ? $positions->first() : null;
        $position_id = isset($request->position_id) ? $request->position_id : null;
        
        $departments    = Department::all();
        // $department = $departments->count() ? $departments->first() : null;
        $department_id = isset($request->department_id) ? $request->department_id : null;
        // dd($department);
        return view('employee::create', compact('positions', 'departments', 'position_id', 'department_id'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'name'          => 'required',
        'name'          => 'unique:employees',
        'line'          => 'unique:employees',
        'salary'        => 'required',
        'position_id'   => 'required',
        'department_id' => 'required',
        'started_at'    => 'required',
        ]);
        
        $department = Department::firstOrCreate(['title' => $request->department_id]);
        $position = Position::firstOrCreate(['title' => $request->position_id]);
        
        $data = $request->except(['_token', '_method']);
        $data['department_id'] = $department->id;
        $data['position_id'] = $position->id;
        $employee = Employee::create($data);
        if ($employee) {
            $employee->attach();
        }
        return redirect()->route('employees.show', $employee)->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Employee $employee)
    {
        $positions      = Position::all();
        $departments    = Department::all();
        $year = isset($request->year) ? $request->year : date('Y');
        $month = isset($request->month) ? $request->month < 10 ? '0' . $request->month : $request->month : date('m');
        $fullMonth = $year . '-' . $month;
        $transactions = $employee->monthlyTransactions($fullMonth);
        $totalDebts = [
        'later' => 0,
        'safe' => 0,
        'bank' => 0
        ];
        $totalDeducations = [
        'later' => 0,
        'safe' => 0,
        'bank' => 0
        ];
        $totalCredits = [
        'later' => 0,
        'safe' => 0,
        'bank' => 0
        ];
        // dd($transactions);
        // dd($transactions->first()->delete());
        foreach ($transactions as $transaction) {
            if(isset($transaction->entry)){
                foreach ($transaction->entry->accounts as $account) {
                    if($account->isCashSafe()){
                        if($transaction->type == Transaction::TYPE_DEBT) $totalDebts['safe'] += $account->pivot->amount;
                        else if($transaction->type == Transaction::TYPE_DEDUCATION) $totalDeducations['safe'] += $account->pivot->amount;
                        else if($transaction->type == Transaction::TYPE_BONUS) $totalCredits['safe'] += $account->pivot->amount;
                    }
                    else if($account->isBankSafe()){
                        if($transaction->type == Transaction::TYPE_DEBT) $totalDebts['bank'] += $account->pivot->amount;
                        else if($transaction->type == Transaction::TYPE_DEDUCATION) $totalDeducations['bank'] += $account->pivot->amount;
                        else if($transaction->type == Transaction::TYPE_BONUS) $totalCredits['bank'] += $account->pivot->amount;
                    }
                    
                }
            }else{
                if($transaction->type == Transaction::TYPE_DEBT) $totalDebts['later'] += $transaction->amount;
                else if($transaction->type == Transaction::TYPE_DEDUCATION) $totalDeducations['later'] += $transaction->amount;
                else if($transaction->type == Transaction::TYPE_BONUS) $totalCredits['later'] += $transaction->amount;
            }
        }
        return view('employee::show',  compact('employee', 'departments','positions','transactions', 'totalDebts', 'totalCredits', 'totalDeducations', 'year', 'fullMonth', 'month'));
    }
    
    /**
    * Show the employee custodies
    * @param Employee $employee
    * @return void
    */
    public function custodies(Request $request, Employee $employee)
    {
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $status = $request->has('status') ? $request->status : 'all';
        $statuses = Custody::STATUSES;
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $custodies = Custody::where('employee_id', $employee->id)->orderBy('created_at', 'DESC');
        $custodies = $custodies->whereBetween('created_at', [$from_date_time, $to_date_time]);
        $custodies = $custodies->get();
        if ($status != 'all') {
            $custodies = $custodies->filter(function($custody) use($status){
                return $custody->checkStatus($status);
            });
        }
        // dd($custodies->modelKeys());
        return view('employee::custodies', compact('employee', 'custodies', 'statuses', 'status', 'from_date', 'to_date'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit(Employee $employee)
    {
        $positions      = Position::all();
        $departments    = Department::all();
        return view('employee::edit', compact('employee', 'positions', 'departments'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, Employee $employee)
    {
        
        $request->validate([
        'name'          => 'required',
        'salary'        => 'required',
        'position_id'   => 'required',
        'department_id' => 'required',
        'started_at'    => 'required',
        ]);
        
        $department = Department::firstOrCreate(['title' => $request->department_id]);
        $position = Position::firstOrCreate(['title' => $request->position_id]);
        
        $data = $request->except(['_token', '_method']);
        $data['department_id'] = $department->id;
        $data['public_line'] = $request->public_line ?? 0;
        $data['position_id'] = $position->id;
        
        $employee->update($data);
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
}