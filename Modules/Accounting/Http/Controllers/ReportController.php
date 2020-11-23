<?php

namespace Modules\Accounting\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Models\Center;
use Modules\Accounting\Models\Entry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Safe;
use Modules\Accounting\Models\Transfer;
use Modules\Accounting\Models\Voucher;
use Modules\Accounting\Models\Year;
use Modules\Employee\Models\Employee;
use Modules\Accounting\Models\Salary;
use Modules\Accounting\Models\Transaction;

class ReportController extends Controller
{
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $years = Year::all();
        $users = User::all();
        $employees = Employee::all();
        $centers = Center::orderBy('type')->get();
        $safes = Safe::all();
        $accounts = Account::secondaryAccounts();
        return view('accounting::reports.index' , compact('users', 'employees', 'years', 'accounts', 'safes', 'centers'));
    }
    
    public function safes(Request $request){
        // $safes = Safe::all();
        $users = User::all();
        $user_id = $request->user_id ? $request->user_id : auth()->user()->getKey();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $accounts = Account::safes()->children(true)->where('type', Account::TYPE_SECONDARY);
        $safes = [];
        foreach ($accounts as $account) {
            $debts = $account->debts()->where('year_id', $request->year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
            if($user_id == 'all'){
                $debts = $debts->filter(function($entry) use($users){
                    if (is_null($entry->auth())) {
                        return true;
                    }
                    return in_array($entry->auth()->id, $users->pluck('id')->toArray());
                });
            }else{
                $debts = $debts->filter(function($entry) use($user_id){
                    return $entry->auth()->id == $user_id;
                });
            }
            $safes[$account->name]['debts'] = $debts;

            $credits = $account->credits()->where('year_id', $request->year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
            if($user_id == 'all'){
                $credits = $credits->filter(function($entry) use($users){
                    if (is_null($entry->auth())) {
                        return true;
                    }
                    return in_array($entry->auth()->id, $users->pluck('id')->toArray());
                });
            }else{
                $credits = $credits->filter(function($entry) use($user_id){
                    return $entry->auth()->id == $user_id;
                });
            }
            $safes[$account->name]['credits'] = $credits;
        }
        // dd($safes, $accounts->pluck('id')->toArray());
        // $entries = Entry::where('year_id', $request->year_id)->whereBetween('created_at', [$from_date_time, $to_date_time])->orderBy('created_at', 'DESC')->get();
        //        $safe_id = $request->safe_id ? $request->safe_id : "all";
        //        if($safe_id == 'all'){
        //            $entries = $entries->filter(function($entry) use($safes){
        //                return $entry->accounts->whereIn('pivot.account_id', $safes->pluck('id')->toArray())->count();
        //            });
        //        }else{
        //            $entries = $entries->filter(function($entry) use($safe_id){
        //                return $entry->accounts->where('pivot.account_id', $safe_id)->count();
        //            });
        //        }
        // if($user_id == 'all'){
        //     $entries = $entries->filter(function($entry) use($users){
        //         if (is_null($entry->auth())) {
        //             return true;
        //         }
        //         return in_array($entry->auth()->id, $users->pluck('id')->toArray());
        //     });
        // }else{
        //     $entries = $entries->filter(function($entry) use($user_id){
        //         return $entry->auth()->id == $user_id;
        //     });
        // }
        //        $entries = $entries->groupBy('pivot.account_id')->map(function($entry){
        //            return [$entry->first()->accounts->first()->id => $entry];
        //        });
        //        dd($entries->first());
        $side_debts = Entry::SIDE_DEBTS;
        $side_credits = Entry::SIDE_CREDITS;
        return view('accounting::reports.safes', compact('users', 'safes', 'side_debts', 'side_credits', 'from_date', 'to_date'));
        
    }
    
    public function safe(Request $request, Safe $safe){
        $entries = $safe->account->entries($request->year_id);
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $opening_balance = 0;
        
        if(count($entries)){
            $first_date = $entries->first()->created_at->toDateTimeString();
            $before_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->subDays(1)->toDateTimeString())) . ' 23:59:59';
            $beforeDebts = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($safe) { return $entry->from_id == $safe->id; });
            $beforeCredits = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($safe) { return $entry->to_id == $safe->id; });
            $opening_balance = ($beforeDebts->sum('amount') - $beforeCredits->sum('amount'));
        }
        
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        $debts = $safe->account->debts()->whereBetween('created_at', [$from_date_time, $to_date_time])->sortByDesc('created_at');
        $credits = $safe->account->credits()->whereBetween('created_at', [$from_date_time, $to_date_time])->sortByDesc('created_at');
        //         dd($debts->pluck('year_id'), $credits->pluck('year_id'), yearId());
        return view('accounting::reports.safe', compact('debts', 'credits', 'opening_balance', 'safe', 'from_date', 'to_date'));
        
    }
    
    public function center(Request $request, Center $center){
        $entries = $center->entries($request->year_id);
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $opening_balance = 0;
        if(count($entries)){
            $first_date = $entries->first()->created_at->toDateTimeString();
            $before_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->subDays(1)->toDateTimeString())) . ' 23:59:59';
            $beforeDebts = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($center) { return $entry->from_id == $center->id; });
            $beforeCredits = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($center) { return $entry->to_id == $center->id; });
            $opening_balance = ($beforeDebts->sum('amount') - $beforeCredits->sum('amount'));
        }
        
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        $entries = $entries->whereBetween('created_at', [$from_date_time, $to_date_time])->unique();//->sortByDesc('created_at');
        return view('accounting::reports.center', compact('entries','opening_balance', 'center', 'from_date', 'to_date'));
        
    }
    
    public function vouchers(Request $request){
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $year_id = $request->has('year_id') ? $request->year_id : (!is_null(year()) ? yearId() : Year::last()->id);
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $type = $request->has('type') ? $request->type : 'all';
        $status = $request->has('status') ? $request->status : 'all';
        $vouchers = Voucher::where('year_id', $year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($type != 'all'){
            $vouchers = $vouchers->where('type',$type);
        }
        $vouchers = $vouchers->orderBy('created_at', 'DESC')->get();
        if ($status != 'all'){
            $vouchers = $vouchers->filter(function($voucher) use ($status){
                return $voucher->statusEquals($status);
            });
        }
        return view('accounting::reports.vouchers' , compact('vouchers', 'from_date', 'to_date'));
    }
    
    public function expenses(Request $request){
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $year_id = $request->has('year_id') ? $request->year_id : (!is_null(year()) ? yearId() : Year::last()->id);
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $type = $request->has('type') ? $request->type : 'all';
        $status = $request->has('status') ? $request->status : 'all';
        $user_id = $request->has('user_id') ? $request->user_id : 'all';
        $safe_id = $request->has('safe_id') ? $request->safe_id : 'all';
        $account_id = $request->has('account_id') ? $request->account_id : 'all';
        $expenses = Expense::where('year_id', $year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($type != 'all'){
            $expenses = $expenses->where('type',$type);
        }
        if ($status != 'all'){
            $expenses = $expenses->where('status',$status);
        }
        if ($safe_id != 'all'){
            $expenses = $expenses->where('safe_id',$safe_id);
        }
        if ($account_id != 'all'){
            $expenses = $expenses->where('account_id',$account_id);
        }
        $expenses = $expenses->orderBy('created_at', 'DESC')->get();
        if ($user_id != 'all'){
            $expenses = $expenses->filter(function($expense) use ($user_id){
                return $expense->auth() == $user_id;
            });
        }
        return view('accounting::reports.expenses' , compact('expenses', 'from_date', 'to_date'));
    }
    
    public function transfers(Request $request){
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $year_id = $request->has('year_id') ? $request->year_id : (!is_null(year()) ? yearId() : Year::last()->id);
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $user_id = $request->has('user_id') ? $request->user_id : 'all';
        $from_account_id = $request->has('from_account_id') ? $request->from_account_id : 'all';
        $to_account_id = $request->has('to_account_id') ? $request->to_account_id : 'all';
        $transfers = Transfer::where('year_id', $year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
        
        if ($from_account_id != 'all'){
            $transfers = $transfers->where('from_id',$from_account_id);
        }
        if ($to_account_id != 'all'){
            $transfers = $transfers->where('to_id',$to_account_id);
        }
        $transfers = $transfers->orderBy('created_at', 'DESC')->get();
        if ($user_id != 'all'){
            $transfers = $transfers->filter(function($transfer) use ($user_id){
                return $transfer->auth() == $user_id;
            });
        }
        return view('accounting::reports.transfers' , compact('transfers', 'from_date', 'to_date'));
    }
    
    public function transactions(Request $request){
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $year_id = $request->has('year_id') ? $request->year_id : (!is_null(year()) ? yearId() : Year::last()->id);
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $type = $request->has('type') ? $request->type : 'all';
        $user_id = $request->has('user_id') ? $request->user_id : 'all';
        $employee_id = $request->has('employee_id') ? $request->employee_id : 'all';
        $status = $request->has('status') ? $request->status : 'all';
        $transactions = Transaction::where('year_id', $year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($type != 'all'){
            $transactions = $transactions->where('type',$type);
        }
        if ($status != 'all'){
            $transactions = $transactions->where('status',$status);
        }
        if ($employee_id != 'all'){
            $transactions = $transactions->where('employee_id',$employee_id);
        }
        $transactions = $transactions->orderBy('created_at', 'DESC')->get();
        if ($user_id != 'all'){
            $transactions = $transactions->filter(function($transaction) use ($user_id){
                return $transaction->auth() == $user_id;
            });
        }
        return view('accounting::reports.transactions' , compact('transactions', 'from_date', 'to_date'));
    }
    
    public function salaries(Request $request){
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $year_id = $request->has('year_id') ? $request->year_id : (!is_null(year()) ? yearId() : Year::last()->id);
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $type = $request->has('type') ? $request->type : 'all';
        $user_id = $request->has('user_id') ? $request->user_id : 'all';
        $employee_id = $request->has('employee_id') ? $request->employee_id : 'all';
        $status = $request->has('status') ? $request->status : 'all';
        $salaries = Salary::where('year_id', $year_id)->whereBetween('created_at', [$from_date_time, $to_date_time]);
        
        if ($status != 'all'){
            $salaries = $salaries->where('status',$status);
        }
        if ($employee_id != 'all'){
            $salaries = $salaries->where('employee_id',$employee_id);
        }
        $salaries = $salaries->orderBy('created_at', 'DESC')->get();
        if ($user_id != 'all'){
            $salaries = $salaries->filter(function($salary) use ($user_id){
                return $salary->auth() == $user_id;
            });
        }
        return view('accounting::reports.salaries' , compact('salaries', 'from_date', 'to_date'));
    }
}