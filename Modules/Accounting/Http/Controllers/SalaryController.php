<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;
use Modules\Employee\Models\{Employee};
use Modules\Accounting\Models\{Account, Safe, Salary, Transaction};
use App\Traits\Statusable;

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
        $status = $request->has('status') ? (int) $request->status : 'waiting';
        
        $year = $request->has('year') ? $request->year : date('Y');
        $month = $request->has('month') ? $request->month : date('m');
        $fullMonth = $year . '-' . $month;
        
        $statuses = \App\Traits\Statusable::$STATUSES;
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        if(auth()->user()->hasPermission('salaries-delete')) {
            $builder = Salary::orderBy('created_at', 'DESC');
        }else {
            $builder = Salary::where('employee_id', auth()->user()->employee_id)->orderBy('created_at', 'DESC');
        }
        
        if ($employee_id != 'all') {
            $builder = $builder->where('employee_id', $employee_id);
        }
        
        $salaries = $builder->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        // dd($status);
        // if ($status != 'all') {
        //     // $salaries = $salaries->where('status', $status);
        //     $salaries = $builder->get()->filter(function($salary) use($status){
        //         return $salary->statusIs($status);
        //     });
        // }
        // dd($salaries->first()->getStatus());
        return view('accounting::salaries.index', compact('employees', 'employee', 'salaries', 'statuses', 'status', 'from_date', 'to_date'));
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
                return back()->withError('هذا المرتب موجود');
            }
        }
        $employees = Employee::all();
        $transactions = new Collection();
        
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
            $transactions = Transaction::where('employee_id', $employee->id)->where('month', $fullMonth)->orderBy('type')->get();
            // dd($transactions->groupBy('type'));
            // $transactions = $employee->monthlyTransactions($fullMonth)->sortBy('type');
            $debts = $transactions->where('type', Transaction::TYPE_DEBT);
            $bonuses = $transactions->where('type', Transaction::TYPE_BONUS);
            $deducations = $transactions->where('type', Transaction::TYPE_DEDUCATION);
            // foreach ($transactions as $transaction) {
            //     if (is_null($transaction->safe) || is_null($transaction->account)) {
            //         dd($transaction);
            //         // $transaction->delete();
            //     }
            // }
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
        $details = '';
        $accounts = [];
        $safes = [];
        if ($employee) {
            $details = __('accounting::salaries.entry_details');
            $details = str_replace('__month__', $month, $details);
            $details = str_replace('__employee_id__', $employee->name, $details);
            
            $accounts = Account::secondaryAccounts();
            $safes = Safe::all();
        }
        
        return view('accounting::salaries.create', compact('employees', 'employee', 'transactions', 'filtered_transactions', 'payed_transactions', 'remain_transactions', 'total', 'net', 'totalDebts', 'totalBonuses', 'totalDeducations', 'year', 'fullMonth', 'month', 'details', 'safes', 'accounts'));
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
                $details = __('accounting::salaries.entry_details');
                $details = str_replace('__month__', $date, $details);
                $details = str_replace('__employee_id__', $salary->employee->name, $details);
                $salary->confirm($request->safe_id, $request->account_id, $request->net, $details);
                $salary->attach();
            }
            return redirect()->route('accounting.salaries.show', $salary)->with('success', 'تمت اضافة المرتب بنجاح');
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
        return view('accounting::salaries.show', compact('salary'));
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
        $show_url = route('accounting.salaries.show', $salary);
        $salary->delete();
        if($previous_url == $show_url){
            return redirect()->route('accounting.salaries.index')->with('success', __('salaries.delete_success'));
        }
        return back()->with('success', __('salaries.delete_success'));
    }
    /**
    * Display the specified resource.
    *
    * @param  Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function confirm(Salary $salary)
    {
        $remain_transactions = $salary->transactions('remain')->sortBy('type');
        $payed_debts = $salary->transactions('payed')->where('type', Transaction::TYPE_DEBT);
        $payed_deducations = $salary->transactions('payed')->where('type', Transaction::TYPE_DEDUCATION);
        $payed_bonuses = $salary->transactions('payed')->where('type', Transaction::TYPE_BONUS);
        $accounts = Account::secondaryAccounts();
        $safes = Safe::all();
        return view('accounting::salaries.confirm', compact('salary', 'accounts', 'safes', 'remain_transactions', 'payed_debts', 'payed_deducations', 'payed_bonuses'));
    }
    
}