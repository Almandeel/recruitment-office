<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Main\Models\Office;
use Modules\Accounting\Models\{Account, Entry, Voucher};
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Advance, Bill};
use App\Traits\Statusable;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $offices = Office::all();
        $office_id = $request->has('office_id') ? $request->office_id : 'all';
        $from_date = $request->from_date ? $request->from_date : date("Y-m-d");
        $to_date = $request->to_date ? $request->to_date : date("Y-m-d");
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $status = $request->has('status') ? $request->status : 'checking';

        $bills = Bill::orderBy('created_at', 'DESC');
        if ($office_id != 'all') {
            $bills = $bills->where('office_id', $office_id);
        }
        $bills = $bills->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        if($status != 'all'){
            $bills = $bills->filter(function($bill) use ($status){
                return $bill->statusIs($status);
            });
        }
        
        return view('accounting::bills.index', compact('offices', 'office_id', 'status', 'bills', 'from_date', 'to_date'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('accounting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Bill $bill)
    {
        $advances = Advance::where('office_id', $bill->office_id)->whereNotNull('voucher_id')->get();
        $advances = $advances->filter(function ($advance) {
            if(!is_null($advance->voucher())){
                return $advance->voucher()->isChecked() && !$advance->isPayed();
            }
            return false;
        });
        // dd($advances);
        $secondary_accounts = Account::secondaryAccounts();
        return view('accounting::bills.show', compact('bill', 'advances', 'secondary_accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Bill $bill)
    {
        return redirect()->route('accounting.bills.show',$bill);
        // $advances = Advance::where('office_id', $bill->office_id)->whereNotNull('voucher_id')->get();
        // $advances = $advances->filter(function ($advance) {
        //     if(!is_null($advance->voucher())){
        //         return $advance->voucher()->isChecked() && !$advance->isPayed();
        //     }
        //     return false;
        // });

        // return view('accounting::bills.edit', compact('bill', 'advances'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Bill $bill)
    {
        // dd($request->all());
        if ($bill->statusIs('checking') && $request->has(['cvs_ids', 'cvs_amounts'])) {
            $request['status'] = Statusable::$STATUS_CHECKED;
        }
        $result = $bill->update($request->except(['_token', '_method']));
        if ($request->has(['cvs_ids', 'cvs_amounts'])) {
            for ($i=0; $i < count($request->cvs_ids); $i++) { 
                $cv_id = $request->cvs_ids[$i];
                $credit_account = $request->credit_accounts[$i];
                $cv = Cv::findOrFail($cv_id);
                $contract = $cv->contract;
                $voucher = $cv->voucher;
                $entry = $voucher ? $voucher->entry : null;
                $cv_amount = $request->cvs_amounts[$i];
                if ($cv_amount && $credit_account) {
                    $bill_cv_builder = \DB::table('bill_cv')
                        ->where('cv_id', $cv_id)
                        ->where('bill_id', $bill->id);
                    $bill_cv = $bill_cv_builder->first();
                    // dd($credit_account);
                    $bill_cv_builder->update(['amount_in_riyal' => $cv_amount]);
    
                    if (is_null($entry)) {
                        $entry = Entry::create([
                            'amount' => $cv_amount,
                            'details' => 'عبارة عن قيد لسند صرف العاملة رقم: ' . $cv_id,
                        ]);
                        $entry->accounts()->attach($cv->office->account->id, [
                            'amount' => $cv_amount,
                            'side' => Entry::SIDE_DEBTS,
                        ]);
                        $entry->accounts()->attach($credit_account, [
                            'amount' => $cv_amount,
                            'side' => Entry::SIDE_CREDITS,
                        ]);
                    }else{
                        $credit_data = [];
                        if ($cv_amount != $entry->amount) {
                            $entry->update(['amount' => $cv_amount]);
                            $credit_data = array('amount' => $cv_amount);
                        }

                        $credit = $entry->credits()->first();
                        if ($credit->id != $credit_account) {
                            $credit_data['account_id'] = $credit_account;
                        }
                        if (count($credit_data)) {
                            $entry->accounts()->updateExistingPivot($credit, $credit_data, false);
                        }
                        $debt_data = [];
                        $debt = $entry->debts()->last();
                        if ($debt->pivot->amount != $cv_amount) {
                            $debt_data['amount'] = $cv_amount;
                        }
                        if (count($debt_data)) {
                            $entry->accounts()->updateExistingPivot($debt, $debt_data, false);
                        }
                    }
                    
                    if (is_null($voucher)) {
                        $voucher = Voucher::create([
                            'voucherable_type' => get_class($bill),
                            'voucherable_id' => $bill->id,
                            'cv_id' => $cv_id,
                            'contract_id' => $contract ? $contract->id : null,
                            'amount' => $bill_cv->amount,
                            'type' => Voucher::TYPE_PAYMENT,
                            'details' => 'سند صرف للعاملة رقم: ' . $cv_id,
                        ]);
    
                        $voucher->entry()->save($entry);
                    }
                }
            }
        }

        if ($result) {
            return back()->withSuccess('تمت العملية بنجاح');
        }
        return back()->withSuccess('فشلت العملية');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
