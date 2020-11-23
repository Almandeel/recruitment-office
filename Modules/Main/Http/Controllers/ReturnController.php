<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Main\Models\Office;
use App\Traits\Statusable;
use Modules\Accounting\Models\Voucher;
use Modules\ExternalOffice\Models\{Country, Profession, Cv, Advance, Returned};
class ReturnController extends Controller
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
        
        $returns = Returned::orderBy('created_at', 'DESC');
        if($type != 'all'){
            if($type == 'payed'){
                $returns = $returns->whereNotNull('advance_id');
            }
            else if($type == 'free'){
                $returns = $returns->whereNull('advance_id');
            }
        }
        $returns = $returns->whereBetween('created_at', [$from_date_time, $to_date_time])->get();
        if($office_id != 'all'){
            $returns = $returns->filter(function($return) use ($office_id){
                return $return->office->id == $office_id;
            });
        }
        
        
        return view('main::returns.index', compact('offices', 'office_id', 'type', 'returns', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv){
            return [
            'id' => $cv->id,
            'name' => $cv->name,
            'payed' => $cv->payed(),
            'passport' => $cv->passport,
            'country_id' => $cv->country_id,
            'office_id' => $cv->office_id,
            'profession_id' => $cv->profession_id,
            'country_name' => $cv->country->name,
            'office_name' => $cv->office->name,
            'profession_name' => $cv->profession->name,
            ];
        });
        $cv = Cv::find($request->cv_id);
        // dd($cvs);
        if(is_null($cv)){
            $country_id = $request->has('country_id') ? $request->country_id : 'all';
            $office_id = $request->has('office_id') ? $request->office_id : 'all';
            $profession_id = $request->has('profession_id') ? $request->profession_id : 'all';
            $cv_id = $request->has('cv_id') ? $request->cv_id : 'all';
        }
        else{
            $country_id = $cv->country_id;
            $office_id = $cv->office_id;
            $profession_id = $cv->profession_id;
            $cv_id = $request->has('cv_id') ? $request->cv_id : 'all';
        }
        return view('main::returns.create', compact('cvs', 'cv', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'cv_id' => 'required|numeric', //|exists:cvs'
        'payed' => 'numeric',
        'damages' => 'numeric',
        ]);
        $cv = Cv::findOrFail($request->cv_id);
        // dd($request->all());
        $amount = 0;
        $amount += $request->payed;
        $amount += $request->damages;
        if($amount){
            $advance = Advance::create([
            'office_id' => $cv->office_id,
            'amount' => $amount,
            'details' => 'Converting returned CV: ' . $request->cv_id . ' payments to advance',
            'status' => Advance::STATUS_PAYED,
            'user_id' => $cv->office->admin_id,
            ]);
            
            $voucher = Voucher::create([
            'amount' => $amount,
            'advance_id' => $advance->id,
            'type' => Voucher::TYPE_PAYMENT,
            'details' => 'عبارة عن سند صرف لإرجاع cv رقم: ' . $request->cv_id . ' بإعتبار إجمالي المدفوع والاضرار كسلفة للمكتب',
            'currency' => 'دولار',
            'status' => Statusable::$STATUS_CHECKED,
            'voucher_date' => date('Y-m-d'),
            ]);
            $request['advance_id'] = $advance->id;
        }
        
        
        $request['user_id'] = auth()->user()->getKey();
        $return = Returned::create($request->except(['_token']));
        
        if($return){
            $cv->update(['status' => Cv::STATUS_RETURNED]);
            if ($return) {
                $return->attach();
            }
            return redirect()->route('offices.returns.show', $return)->withSuccess('تم إرجاع الCV بنجاح');
            // return redirect()->route('offices.returns.show', $return)->withSuccess('تم إرجاع الCV بنجاح');
        }
        return back()->withError('حدث خطأ اثناء محاولة إرجاع Cv رقم: ' . $request->cv_id);
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Returned $return)
    {
        $crumbs = [
        [route('offices.index'), 'المكاتب الخارجية'],
        [route('offices.returns.index'), 'المرتجعات'],
        ['#', 'إرجاع cv: ' . $return->cv->id]
        ];
        $advance = $return->advance;
        return view('main::returns.show', compact('return', 'advance', 'crumbs'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit(Returned $return)
    {
        return view('main::edit');
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, Returned $return)
    {
        //
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy(Returned $return)
    {
        $previous_url = url()->previous();
        $show_url = route('offices.returns.show', $return);
        $return->delete();
        if($previous_url == $show_url){
            return redirect()->route('offices.returns.index')->with('success', __('global.delete_success'));
        }
        return back()->with('success', __('global.delete_success'));
    }
}