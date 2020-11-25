<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Models\Entry;
use Modules\Accounting\Models\Voucher;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Safe;
use Modules\Accounting\Models\Custody;
use Modules\ExternalOffice\Models\{Cv, Bill, BillCv, Advance};
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Statusable;
class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('year.activated')->only(['index', 'create','store']);
        $this->middleware('year.opened')->only(['create','store']);
        
        $this->middleware('permission:vouchers-create')->only(['create','store']);
        $this->middleware('permission:vouchers-read')->only(['index', 'show']);
        $this->middleware('permission:vouchers-update')->only(['edit', 'update']);
        $this->middleware('permission:vouchers-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $types = Voucher::TYPES;
        
        $from_date = $request->from_date ? $request->from_date : date("Y-m-d");
        $to_date = $request->to_date ? $request->to_date : date("Y-m-d");
        $status = $request->status ? $request->status : 'confirming';
        $type = $request->type ? $request->type : 'all';
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $vouchers = Voucher::whereBetween('created_at',[$from_date_time, $to_date_time]);
        if($type != 'all'){
            $vouchers = $vouchers->where('type', $type);
        }
        $vouchers = $vouchers->orderBy('created_at', 'DESC')->get();
        
        if($status == 'confirmed'){
            $vouchers = $vouchers->filter(function($voucher){ return isset($voucher->entry); });
        }
        else if($status == 'confirming'){
            $vouchers = $vouchers->filter(function($voucher){ return !isset($voucher->entry); });
        }
        // dd($vouchers->first()->status, \App\Traits\Statusable::$STATUS_CHECKED);
        
        return view('accounting::vouchers.index', compact('vouchers', 'types', 'type', 'status', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('accounting::vouchers.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('vouchers-update')) {
            $request['status'] = Statusable::$STATUS_APPROVED;
        }
        // dd($request->only(['debts_accounts', 'credits_accounts', 'debts_amounts', 'credits_amounts']));
        if (!$request->has('type') && ($request->has('contract_id') || $request->has('tafweed_id')) && $request->has('marketer_id')) {
            // $contract = Contract::findOrFail($request->contract_id);
            // if ($request->amount != $contract->marketing_ratio) {
            //     $contract->update(['marketing_ratio' => $request->amount]);
            // }
            $msg = 'سند صرف للمسوق رقم: ' . $request->marketer_id;
            if ($request->has('contract_id')) {
                $msg .= ' عن العقد رقم: ' . $request->contract_id;
            }
            elseif ($request->has('tafweed_id')) {
                $msg .= ' عن الوكالة رقم: ' . $request->tafweed_id;
            }
            
            $data = array_merge($request->except(['_token']),
            [
            'amount' => $request->amount,
            'type' => Voucher::TYPE_PAYMENT,
            'details' =>  $msg,
            'voucher_date' => date('Y-m-d'),
            ]);
            $voucher = Voucher::create($data);
            if ($voucher) {
                return back()->with('success', __('accounting::vouchers.create_success'));
            }else{
                return back()->with('error', __('accounting::vouchers.create_fail'));
            }
        }
        if ($request->voucherable_type == Custody::class) {
            $request->validate([
            'voucherable_id' => 'required|numeric',
            'voucherable_type' => 'required',
            'amount' => 'required|numeric',
            'debts_accounts' => 'required',
            'debts_amounts' => 'required',
            'details' => 'string|nullable',
            ]);
            // dd([$request->debts_accounts, $request->debts_amounts]);
            $custody_class = $request->voucherable_type;
            $custody = $custody_class::findOrFail($request->voucherable_id);
            $voucher = $custody->consum($request->amount, [$request->debts_accounts, $request->debts_amounts], $request->details);
            $voucher->attach();
            return back()->with('success', __('accounting::global.operation_success'));
        }
        else if ($request->voucherable_type == Bill::class && $request->has('cv_id')) {
            $bill_cv_builder = \DB::table('bill_cv')
            ->where('cv_id', $request->cv_id)
            ->where('bill_id', $request->voucherable_id);
            $bill_cv = $bill_cv_builder->first();
            
            $cv = Cv::findOrFail($request->cv_id);
            $bill = Bill::findOrFail($request->voucherable_id);
            $bill->update(['status' => Statusable::$STATUS_CHECKING]);
            $contract = $cv->contract;
            
            $data = array_merge($request->except(['_token', 'amount_in_riyal']),
            [
            'amount' => $bill_cv->amount,
            'type' => Voucher::TYPE_PAYMENT,
            'details' => 'سند صرف للعاملة رقم: ' . $request->cv_id,
            'voucher_date' => date('Y-m-d'),
            ]);
            if($contract) $data['contract_id'] = $contract->id;
            
            $voucher = Voucher::create($data);
            if ($voucher) {
                $bill_cv_builder->update(['amount_in_riyal' => $request->amount_in_riyal]);
                return back()->with('success', __('accounting::vouchers.create_success'));
            }else{
                return back()->with('error', __('accounting::vouchers.create_fail'));
            }
        }else{
            $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required',
            'type' => 'required|numeric',
            'entry_date' => 'date',
            'debts_accounts' => 'array',
            'credits_accounts' => 'array',
            'debts_amounts' => 'array',
            'credits_amounts' => 'array',
            ]);
            if($request->has('active_tab')){
                session()->flash('active_tab', $request->active_tab);
            }
            if ($request->has('advance_id') && $request->voucherable_type !== Bill::class) {
                $advance = Advance::findOrFail($request->advance_id);
                $request['type'] = Voucher::TYPE_PAYMENT;
                if(is_null($request->details)) $request['details'] = 'عبارة عن سند صرف لسلفية رقم: ' . $request->advance_id;
            }
            
            if ($request->has('advance_id') && $request->voucherable_type == Bill::class) {
                $request['type'] = Voucher::TYPE_RECEIPT;
                if(is_null($request->details)) $request['details'] = 'عبارة عن سند قبض من سلفية رقم: ' . $request->advance_id . ' للفاتورة رقم: ' . $request->voucherable_id;
            }
            
            if (!$request->has('advance_id') && $request->voucherable_type == Bill::class) {
                $request['type'] = Voucher::TYPE_PAYMENT;
                if(is_null($request->details)) $request['details'] = 'عبارة عن سند صرف للفاتورة رقم: ' . $request->voucherable_id;
            }
            
            $voucher = Voucher::create($request->except(['_token']));
            if ($request->has('advance_id') && $request->voucherable_type == Bill::class) {
                $advance = Advance::findOrFail($request->advance_id);
                $advance->update(['voucher_id' => $voucher->id]);
                return back()->with('success', __('accounting::vouchers.create_success'));
            }
            
            // if($request->has(['voucherable_id', 'voucherable_type']) || $request->has('advance_id')){
            // $voucherable_type = $request->voucherable_type;
            // $voucherable_id = $request->voucherable_id;
            // $voucherable = $voucherable_type::findOrFail($voucherable_id);
            // $voucherable->vouchers()->save($voucher);
            // return back()->with('success', __('accounting::vouchers.create_success'));
            // }
            
            if ($voucher) {
                if ($request->has(['debts_accounts', 'credits_accounts', 'debts_amounts', 'credits_amounts'])) {
                    $entry = $voucher->register($request->only(['debts_accounts', 'credits_accounts', 'debts_amounts', 'credits_amounts']));
                }
                $voucher->attach();
            }
            if (url()->previous() == route('vouchers.create')) {
                return redirect()->route('vouchers.show', $voucher)->with('success', __('accounting::vouchers.create_success'));
            }
            return back()->with('success', __('accounting::vouchers.create_success'));
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Response
    */
    public function show(Voucher $voucher)
    {
        return view('accounting::vouchers.show', compact('voucher'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, Voucher $voucher)
    {
        if($request->has('check') && $voucher->isChecked()){
            return back()->withError('accounting::vouchers.invalid_checked');
        }
        $title = $request->has('check') ? 'تأكيد' : 'تعديل';
        $title .= ' ' . $voucher->displayType();
        $title .= ': ' . $voucher->id;
        $entry = $voucher->entry;
        $entry_amount = $entry ? $entry->amount : $voucher->amount;
        $compact = compact('voucher', 'title', 'entry', 'entry_amount');
        if($request->has('check')){
            $compact['accounts'] = Account::secondaryAccounts();
            $entry_amount = 0;
            $cv = $voucher->cv;
            $marketer = $voucher->marketer;
            $contract = $voucher->contract;
            $bill = $voucher->bill;
            $advance = $voucher->advance;
            
            $compact['cv'] = $cv;
            $compact['marketer'] = $marketer;
            $compact['contract'] = $contract;
            $compact['bill'] = $bill;
            $compact['advance'] = $advance;
            
            if ($cv && $bill) {
                $bill_cv = $cv->billPivot($bill->id);
                $entry_amount = $bill_cv ? $bill_cv->getAttributes()['amount_in_riyal'] : $entry_amount;
                // dd($bill_cv->getAttributes()['amount_in_riyal']);
                $cv_voucher = $cv->voucher;
                if ($cv_voucher) {
                    $entry = $cv_voucher->entry;
                }
                $compact['entry'] = $entry;
                $compact['entry_amount'] = $entry_amount;
            }
            
        }
        
        return view('accounting::vouchers.edit', $compact);
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Voucher $voucher)
    {
        
        if($request->has('check')){
            $request->validate([
            'entry_amount' => 'required|numeric',
            // 'entry_details' => 'string',
            'credit_id' => 'required|numeric',
            'debt_id' => 'required|numeric',
            ]);
            
            // if($request->details && !$request->entry_details){
            //     $request['entry_details'] = $request->details;
            // }
            // if($request->entry_details && !$request->details){
            //     $request['details'] = $request->entry_details;
            // }
            
            $voucher->update($request->except(['_token', '_method']));
            $entry_data = [
            'amount' => $request->entry_amount,
            'details' => $request->entry_details,
            'entry_date' => $request->entry_date,
            ];
            if ($voucher->voucherable_type == Custody::class) {
                $entry_data['type'] = Entry::TYPE_ADJUST;
            }
            $entry = Entry::create($entry_data);
            
            if($voucher->isReceipt()){
                $from = $request->credit_id;
                $to = $request->debt_id;
            }else{
                $from = $request->debt_id;
                $to = $request->credit_id;
            }
            $entry->accounts()->attach($from, [
            'amount' => $entry->amount,
            'side' => Entry::SIDE_DEBTS,
            ]);
            $entry->accounts()->attach($to, [
            'amount' => $entry->amount,
            'side' => Entry::SIDE_CREDITS,
            ]);
            $voucher->setSafe($request->credit_id);
            $voucher->entry()->save($entry);
            $advance = $voucher->advance;
            if($advance){
                if($advance->voucher()->id == $voucher->id){
                    $advance->update(['status' => Advance::STATUS_PAYED]);
                }
            }
            
            $bill = $voucher->bill;
            if ($bill) {
                $bill->update(['status' => Statusable::$STATUS_CHECKED]);
            }
            return redirect()->route('vouchers.show', ['voucher' => $voucher, 'layout' => 'print'])->with('success', 'تم تأكيد السند بنجاح');
        }
        if(!$request->has('no_validation')){
            $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required',
            'type' => 'required|numeric',
            'entry_date' => 'date',
            ]);
        }
        $voucher->update($request->except(['_token', '_method']));
        return back()->with('success', __('accounting::vouchers.update_success'));
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Response
    */
    public function destroy(Voucher $voucher)
    {
        $previous_url = url()->previous();
        $show_url = route('vouchers.show', $voucher);
        $voucher->delete();
        if(startsWith($previous_url, $show_url)){
            return redirect()->route('vouchers.index')->with('success', __('accounting::vouchers.delete_success'));
        }
        return back()->with('success', __('accounting::vouchers.delete_success'));
    }
}