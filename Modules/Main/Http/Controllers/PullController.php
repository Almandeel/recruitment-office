<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Main\Models\Office;
use Modules\Accounting\Models\Voucher;
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Advance, Pull};
class PullController extends Controller
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
        $pulls = $pulls->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        if($office_id != 'all'){
            $pulls = $pulls->filter(function($pull) use ($office_id){
                return $pull->office->id == $office_id;
            });
        }
        
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
        
        
        return view('main::pulls.index', compact('offices', 'office_id', 'type', 'pulls', 'status', 'gender', 'from_date', 'to_date'));
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Pull $pull)
    {
        $advance = $pull->advance;
        return view('main::pulls.show', compact('pull', 'advance'));
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