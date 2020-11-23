<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Models\{Account, Year};
use Modules\Employee\Models\{Employee, Salary, Transaction};
use App\Traits\Statusable;
class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:accounts-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:accounts-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:transactions-read', ['only' => ['transactions', 'transaction']]);
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        // dd(Account::assets());
        $entriesCount = year() ? year()->entries()->count() : 0;
        $vouchersCount = year() ? year()->vouchers()->count() : 0;
        $expensesCount = year() ? year()->expenses()->count() : 0;
        // dd(view('accounting::index', compact('entriesCount', 'vouchersCount', 'expensesCount')));
        return view('accounting::index', compact('entriesCount', 'vouchersCount', 'expensesCount'));
    }
    public function reports()
    {
        $years = Year::all();
        $accounts = accounts(true, true);
        return view('accounting::reports', compact('years', 'accounts'));
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function tree(Request $request)
    {
        $roots = Account::roots(true);
        $account = isset($request->account_id) ? Account::find($request->account_id) : $roots->first();
        $account = (isset($request->account_id) && !$account) ? $roots->first() : $account;
        $crumbs = [
        [route('accounts.index'), __('accounting::accounting.tree')]
        ];
        foreach ($account->parents(true) as $parent) {
            $crumbs[] = [route('accounts.show', $parent), $parent->name];
        }
        $crumbs[] = ['#', $account->name];
        // dd(finalAccount());
        return view('accounting::tree', compact('account', 'roots', 'crumbs'));
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function transactions(Request $request)
    {
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');

        $types = Transaction::TYPES;
        $type = $request->has('type') ? $request->type : 'all';

        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $transactions = Transaction::orderBy('created_at', 'DESC')
        ->where('status', Statusable::$STATUS_APPROVED);
        if($type != 'all'){
            $transactions = $transactions->where('type', $type);
        }else{
            // $transactions = $transactions->where('type', Transaction::TYPE_BONUS)
            //                             ->orwhere('type', Transaction::TYPE_DEBT);
        }
        $transactions = $transactions->whereBetween('created_at', [$from_date_time, $to_date_time]);
        if ($type != 'all') {
            $transactions = $transactions->where('type', $type);
        }
        $transactions = $transactions->get()->filter(function($transaction){
            return is_null($transaction->entry);
        });
        // dd($transactions->last()->delete());
        return view('accounting::transactions.index', compact('transactions', 'from_date', 'to_date', 'types', 'type'));
    }


    /**
    * Display the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function transaction(Transaction $transaction)
    {
        return view('accounting::transactions.show', compact('transaction'));
    }


}
