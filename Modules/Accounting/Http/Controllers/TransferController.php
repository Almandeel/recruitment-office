<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Models\{Account, Entry, Transfer};
use Illuminate\Http\Request;
use Carbon\Carbon;
class TransferController extends Controller
{
    public function __construct() {
        // $this->middleware('permission:transfers-create')->only(['create', 'store']);
        // $this->middleware('permission:transfers-read')->only(['index', 'show']);
        // $this->middleware('permission:transfers-update')->only(['edit', 'update']);
        // $this->middleware('permission:transfers-delete')->only('destroy');
    }
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
        $transfers = Transfer::orderBy('created_at', 'DESC')->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        return view('accounting::transfers.index', compact('transfers', 'from_date', 'to_date'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('accounting::transfers.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $rules = [
        'amount' => 'required | numeric',
        'from_id' => 'required | numeric',
        'to_id' => 'required | numeric',
        ];
        if($request->from_id == $request->to_id){
            return back()->with('error', __('accounting::transfers.error_matching_accounts'));
        }

        if($request->max){
            $rules['amount'] = 'required | numeric | max:' . $request->max;
        }else{
            $rules['amount'] = 'required | numeric';
        }

        $request->validate($rules);

        $transfer = Transfer::create($request->except(['_token', '_method']));

        session()->flash('success', 'تمت العملية بنجاح');
        return back();
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function show(transfer $transfer)
    {
        return view('accounting::dashboard.transfers.show', compact('transfer'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function edit(transfer $transfer)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, transfer $transfer)
    {
        $transfer->update($request->validate([
        'amount' => 'required | numeric', // | max:' . ($transfer->to->balance() + $transfer->amount),
        'from_id' => 'required | numeric',
        'to_id' => 'required | numeric',
        'details' => 'string',
        ]));
        $transfer->entry->update($request->all());

        session()->flash('success', 'تمت العملية بنجاح');

        return back();
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function destroy(transfer $transfer)
    {
        $transfer->delete();
        session()->flash('success', 'تمت العملية بنجاح');

        return back();

    }
}
