<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\{Bill, BillCv, Cv};

class BillController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:bills-create')->only(['create', 'store']);
        $this->middleware('permission:bills-read')->only(['index', 'show']);
        $this->middleware('permission:bills-update')->only(['edit', 'update']);
        $this->middleware('permission:bills-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $bills = Bill::officeBills()->has('cvBill')->get();
        return view('externaloffice::bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // $cvs = Cv::notBilled()->get()->filter(function ($cv) {
        // 	return $cv->payed() == 0;
        // });
        $cvs = Cv::where('office_id', auth()->user()->office_id)->get()->filter(function ($cv) {
            return ! $cv->billed && $cv->status == $cv::STATUS_CONTRACTED;
        });

        return view('externaloffice::bills.create', compact('cvs'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|array',
            'amount.*.*' => 'required|numeric',
            'cv_id' => 'required|array',
            'cv_id.*.*' => 'required|exists:cvs',
            'notes' => 'nullable|string',
        ], [], ['cv_id' => 'cv', 'amount' => 'amount']);

        $bill = Bill::create([
            'office_id' => auth()->guard('office')->user()->office_id,
            'amount' => array_sum($request->amount),
            'user_id' => $request->user()->id,
            'notes' => $request->notes,
        ]);

        for ($i = 0; $i < count($request->cv_id); $i++) {
            $bill->cvBill()->create([
                'bill_id' => $bill->id,
                'cv_id' => $request->cv_id[$i],
                'amount' => $request->amount[$i],
            ]);

            Cv::find($request->cv_id[$i])->update(['billed' => true]);
        }
        if ($bill) {
            $bill->attach();
        }
        return redirect()->route('cvs.bills.index')->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param  Bill  $bill
     * @return Response
     */
    public function show(Bill $bill)
    {
        return view('externaloffice::bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  Bill  $bill
     * @return Response
     */
    public function edit(Bill $bill)
    {
        $cvs = Cv::notBilled()->get()->filter(function ($cv) {
            return $cv->payed() == 0;
        });

        return view('externaloffice::bills.edit', compact('bill', 'cvs'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request  $request
     * @param  Bill  $bill
     * @return Response
     */
    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'amount' => 'required|array',
            'amount.*.*' => 'required|numeric',
            'cv_id' => 'required|array',
            'cv_id.*.*' => 'required|exists:cvs',
            'notes' => 'nullable|string',
        ]);

        $bill->update([
            'amount' => array_sum($request->amount),
            'notes' => $request->notes,
        ]);

        $bill->cvBill()->delete();

        for ($i = 0; $i < count($request->cv_id); $i++) {
            $bill->cvBill()->create([
                'bill_id' => $bill->id,
                'cv_id' => $request->cv_id[$i],
                'amount' => $request->amount[$i],
            ]);
        }

        return redirect()->route('cvs.bills.index')->with('success', __('global.operation_success'));
    }

    /**
     * Remove the specified resource from storage.
     * @param  Bill  $bill
     * @return Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
