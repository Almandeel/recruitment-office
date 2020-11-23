<?php

namespace Modules\Employee\Http\Controllers;

use Modules\Employee\Models\{Employee};
use Modules\Accounting\Models\{Salary, Transaction, Account};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;

class SalaryController extends Controller
{
    public function __construct() {
        $this->middleware('year.activated')->only(['create','store']);
        $this->middleware('year.opened')->only(['create','store']);
        
        $this->middleware('permission:salaries-create')->only(['create', 'store']);
        $this->middleware('permission:salaries-read')->only(['index', 'show']);
        $this->middleware('permission:salaries-update')->only(['edit', 'update']);
        $this->middleware('permission:salaries-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $employees = Employee::all();
        $employee_id = $request->has('employee_id') ? $request->employee_id : 'all';
        $employee = ($employee_id != 'all') ? Employee::findOrFail($employee_id) : null;
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        $status = $request->has('status') ? $request->status : 'waiting';
        
        $year = $request->has('year') ? $request->year : date('Y');
        $month = $request->has('month') ? $request->month : date('m');
        $fullMonth = $year . '-' . $month;
        
        $statuses = \App\Traits\Statusable::$STATUSES;
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        if(auth()->user()->hasPermission('salaries-delete')) {
            $salaries = Salary::orderBy('created_at', 'DESC');
        }else {
            $salaries = Salary::where('employee_id', auth()->user()->employee_id)->orderBy('created_at', 'DESC');
        }
        
        if ($employee) {
            $salaries = $salaries->where('employee_id', $employee_id);
        }
        
        $salaries = $salaries->whereBetween('created_at', [$from_date_time, $to_date_time]);
        
        // if ($status != 'all') {
        //     $salaries = $salaries->where('status', $status);
        // }
        
        $salaries = $salaries->get();
        return view('employee::salaries.index', compact('employees', 'employee', 'salaries', 'statuses', 'status', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Request
    */
    public function create(Request $request)
    {
        $employee = $request->has('employee_id') ? Employee::findOrFail($request->employee_id) : null;
        $year = isset($request->year) ? $request->year : date('Y');
        $month = isset($request->month) ? $request->month < 10 ? '0' . $request->month : $request->month : date('m');
        $fullMonth = $year . '-' . $month;
        if($employee){
            $salary = Salary::where('employee_id', $employee->id)->where('month', $fullMonth)->get()->first();
            if(!is_null($salary)){
                return redirect()->route('salaries.edit', $salary);
            }
        }
        $employees = Employee::all();
        
        $filtered_transactions = [
        'debts' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        'deducations' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        'bonuses' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        ];
        
        $totalDebts = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalDeducations = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalBonuses = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $total = 0;
        $net = 0;
        if(is_null($employee)){
            $transactions = new Collection();
            $debts = new Collection();
            $bonuses = new Collection();
            $deducations = new Collection();
        }else{
            $transactions = $employee->monthlyTransactions($fullMonth)->sortBy('type');
            $debts = $transactions->where('type', Transaction::TYPE_DEBT);
            $bonuses = $transactions->where('type', Transaction::TYPE_BONUS);
            $deducations = $transactions->where('type', Transaction::TYPE_DEDUCATION);
            
            $total = $employee->salary;
            $net = $employee->salary;
            foreach ($bonuses as $bonus) {
                if($bonus->safe()){
                    $totalBonuses['payed'] += $bonus->amount;
                    $filtered_transactions['bonuses']['payed']->push($bonus);
                }else{
                    $totalBonuses['remain'] += $bonus->amount;
                    $filtered_transactions['bonuses']['remain']->push($bonus);
                }
                $net += $bonus->amount;
                $totalBonuses['total'] += $bonus->amount;
                $filtered_transactions['bonuses']['total']->push($bonus);
            }
            foreach ($debts as $debt) {
                if($debt->safe()){
                    $totalDebts['payed'] += $debt->amount;
                    $filtered_transactions['debts']['payed']->push($debt);
                }else{
                    $totalDebts['remain'] += $debt->amount;
                    $filtered_transactions['debts']['remain']->push($debt);
                }
                $net -= $debt->amount;
                $totalDebts['total'] += $debt->amount;
                $filtered_transactions['debts']['total']->push($debt);
            }
            foreach ($deducations as $deducation) {
                if($deducation->safe()){
                    $totalDeducations['payed'] += $deducation->amount;
                    $filtered_transactions['deducations']['payed']->push($deducation);
                }else{
                    $totalDeducations['remain'] += $deducation->amount;
                    $filtered_transactions['deducations']['remain']->push($deducation);
                }
                $net -= $deducation->amount;
                $totalDeducations['total'] += $deducation->amount;
                $filtered_transactions['deducations']['total']->push($deducation);
            }
            $total += $totalBonuses['total'];
            // $total['debts'] = ['payed' => $totalDebts['payed'], 'remain' => $totalDebts['remain'], 'total' => $totalDebts['total']];
            // $total['deducations'] = ['payed' => $totalDeducations['payed'], 'remain' => $totalDeducations['remain'], 'total' => $totalDeducations['total']];
            // $total['bonuses'] = ['payed' => $totalBonuses['payed'], 'remain' => $totalBonuses['remain'], 'total' => $totalBonuses['total']];
        }
        
        $payed_transactions = $filtered_transactions['debts']['payed'];
        $payed_transactions = $payed_transactions->merge($filtered_transactions['deducations']['payed']);
        $payed_transactions = $payed_transactions->merge($filtered_transactions['bonuses']['payed']);
        
        $remain_transactions = $filtered_transactions['debts']['remain'];
        $remain_transactions = $remain_transactions->merge($filtered_transactions['deducations']['remain']);
        $remain_transactions = $remain_transactions->merge($filtered_transactions['bonuses']['remain']);
        // dd(Account::where('id', 'like', '4%')->where('type', Account::TYPE_SECONDARY)->get()->toArray());
        // dd($payed_transactions->toArray());
        return view('employee::salaries.create', compact('employees', 'employee', 'transactions', 'filtered_transactions', 'payed_transactions', 'remain_transactions', 'total', 'net', 'totalDebts', 'totalBonuses', 'totalDeducations', 'year', 'fullMonth', 'month'));
    }
    
    /**
    * Salary a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'net'      => 'required | numeric',
            'total'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= (($request->month < 10) && (strlen($request->month) < 2)) ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            $salary = Salary::create($request->all());
            if ($salary) {
                $salary->attach();
            }
            return redirect()->route('salaries.show', $salary)->with('success', 'تمت اضافة المرتب بنجاح');
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function show(Salary $salary)
    {
        return view('employee::salaries.show', compact('salary'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, Salary $salary)
    {
        $employee = $request->has('employee_id') ? Employee::findOrFail($request->employee_id) : $salary->employee;
        $year = isset($request->year) ? $request->year : $salary->split_month(0);
        $month = isset($request->month) ? $request->month < 10 ? '0' . $request->month : $request->month : $salary->split_month(1);
        $fullMonth = ($request->has('year') && $request->has('month')) ? $year . '-' . $month : $salary->month;
        $employees = Employee::all();
        
        $filtered_transactions = [
        'debts' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        'deducations' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        'bonuses' => ['payed' => new Collection(), 'remain' => new Collection(), 'total' => new Collection()],
        ];
        
        $totalDebts = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalDeducations = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalBonuses = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $total = 0;
        $net = 0;
        if(is_null($employee)){
            $transactions = new Collection();
            $debts = new Collection();
            $bonuses = new Collection();
            $deducations = new Collection();
        }else{
            $transactions = $employee->monthlyTransactions($fullMonth)->sortBy('type');
            $debts = $transactions->where('type', Transaction::TYPE_DEBT);
            $bonuses = $transactions->where('type', Transaction::TYPE_BONUS);
            $deducations = $transactions->where('type', Transaction::TYPE_DEDUCATION);
            
            $total = $employee->salary;
            $net = $employee->salary;
            foreach ($bonuses as $bonus) {
                if($bonus->safe()){
                    $totalBonuses['payed'] += $bonus->amount;
                    $filtered_transactions['bonuses']['payed']->push($bonus);
                }else{
                    $totalBonuses['remain'] += $bonus->amount;
                    $filtered_transactions['bonuses']['remain']->push($bonus);
                }
                $net += $bonus->amount;
                $totalBonuses['total'] += $bonus->amount;
                $filtered_transactions['bonuses']['total']->push($bonus);
            }
            foreach ($debts as $debt) {
                if($debt->safe()){
                    $totalDebts['payed'] += $debt->amount;
                    $filtered_transactions['debts']['payed']->push($debt);
                }else{
                    $totalDebts['remain'] += $debt->amount;
                    $filtered_transactions['debts']['remain']->push($debt);
                }
                $net -= $debt->amount;
                $totalDebts['total'] += $debt->amount;
                $filtered_transactions['debts']['total']->push($debt);
            }
            foreach ($deducations as $deducation) {
                if($deducation->safe()){
                    $totalDeducations['payed'] += $deducation->amount;
                    $filtered_transactions['deducations']['payed']->push($deducation);
                }else{
                    $totalDeducations['remain'] += $deducation->amount;
                    $filtered_transactions['deducations']['remain']->push($deducation);
                }
                $net -= $deducation->amount;
                $totalDeducations['total'] += $deducation->amount;
                $filtered_transactions['deducations']['total']->push($deducation);
            }
            $total += $totalBonuses['total'];
        }
        
        $payed_transactions = $filtered_transactions['debts']['payed'];
        $payed_transactions = $payed_transactions->merge($filtered_transactions['deducations']['payed']);
        $payed_transactions = $payed_transactions->merge($filtered_transactions['bonuses']['payed']);
        
        $remain_transactions = $filtered_transactions['debts']['remain'];
        $remain_transactions = $remain_transactions->merge($filtered_transactions['deducations']['remain']);
        $remain_transactions = $remain_transactions->merge($filtered_transactions['bonuses']['remain']);
        return view('employee::salaries.edit', compact('salary', 'employees', 'employee', 'transactions', 'filtered_transactions', 'payed_transactions', 'remain_transactions', 'total', 'net', 'totalDebts', 'totalBonuses', 'totalDeducations', 'year', 'fullMonth', 'month'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Employee $employee, Salary $salary)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'net'      => 'required | numeric',
            'total'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= (($request->month < 10) && (strlen($request->month) < 2)) ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            $salary->update($request->all());
            
            session()->flash('success', 'تم تعديل المرتب بنجاح');
            
            return back();
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function destroy(Salary $salary)
    {
        $previous_url = url()->previous();
        $show_url = route('salaries.show', $salary);
        $salary->delete();
        if($previous_url == $show_url){
            return redirect()->route('salaries.index')->with('success', __('salaries.delete_success'));
        }
        return back()->with('success', __('salaries.delete_success'));
    }
}