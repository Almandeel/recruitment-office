<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Main\Models\Office;
use Illuminate\Routing\Controller;
use Modules\Accounting\Models\Voucher;
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Advance, Pull};

class PullController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pulls-create')->only(['create', 'store']);
        $this->middleware('permission:pulls-read')->only(['index', 'show']);
        $this->middleware('permission:pulls-update')->only(['edit', 'update']);
        $this->middleware('permission:pulls-delete')->only('destroy');
    }

    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $from_date = $request->from_date ? $request->from_date : now();
        $to_date = $request->to_date ? $request->to_date : now();

        $type = $request->has('type') ? $request->type : 'all';
        
        $pulls = Pull::orderBy('created_at', 'DESC');
        if($type != 'all'){
            if($type == 'payed'){
                $pulls = $pulls->whereNotNull('advance_id');
            }
            else if($type == 'free'){
                $pulls = $pulls->whereNull('advance_id');
            }
        }
        $pulls = $pulls->whereBetween('created_at', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])->get();
        $status = $request->has('status') ? $request->status : 'all';
        if ($status != 'all') {
            $pulls = $pulls->filter(function($pull) use ($status){
                return $pull->status == array_search($status, Pull::STATUSES);
            });
        }
        
        $gender = $request->has('gender') ? $request->gender : 'all';
        if ($gender != 'all') {
            $pulls = $pulls->filter(function($pull) use ($gender){
                return $pull->cv->gender == array_search($gender, Cv::GENDERS);
            });
        }
        
        
        return view('externaloffice::pulls.index', compact('type', 'pulls', 'status', 'gender', 'from_date', 'to_date'));
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Pull $pull)
    {
        $advance = $pull->advance;
        return view('externaloffice::pulls.show', compact('pull', 'advance'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, Pull $pull)
    {
        if ($request->operation == 'confirm') {
            $confirmed = $pull->confirm($request);
            if ($confirmed) {
                return back()->withSuccess('تم تأكيد عملية السحب');
            }else {
                return back()->withError('حدث خطأ ما في عملية تأكيد السحب');
            }
        }
        elseif ($request->operation == 'cancel') {
            $canceled = $pull->cancel($request);
            if ($canceled) {
                return back()->withSuccess('تم إلغاء عملية السحب');
            }else {
                return back()->withError('حدث خطأ ما في عملية إلغاء السحب');
            }
        }
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy(Pull $pull)
    {
        $previous_url = url()->previous();
        $show_url = route('offices.pulls.show', $pull);
        $pull->delete();
        if($previous_url == $show_url){
            return redirect()->route('offices.pulls.index')->with('success', __('global.delete_success'));
        }
        return back()->with('success', __('global.delete_success'));
        
    }
}