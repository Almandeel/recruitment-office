<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Main\Models\Office;
use Modules\ExternalOffice\Models\{Country, Profession, Advance, Bill};

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
        $status = $request->has('status') ? $request->status : 'waiting';

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
        
        return view('main::bills.index', compact('offices', 'office_id', 'status', 'bills', 'from_date', 'to_date'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('main::create');
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
        return view('main::bills.show', compact('bill', 'advances'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('main::edit');
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
        $result = $bill->update($request->except(['_token', '_method']));
        if ($request->has(['cvs_ids', 'cvs_amounts'])) {
            for ($i=0; $i < count($request->cvs_ids); $i++) { 
                $cv_id = $request->cvs_ids[$i];
                $cv_amount = $request->cvs_amounts[$i];
                \DB::table('bill_cv')
                    ->where('cv_id', $cv_id)
                    ->where('bill_id', $bill->id)
                    ->update(['amount_in_riyal' => $cv_amount]);
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
