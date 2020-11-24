<?php

namespace Modules\Services\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Services\Models\{Contract, Bail};
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;
use Modules\ExternalOffice\Models\Cv;
use Modules\Main\Models\Office;
use Modules\ExternalOffice\Models\{Country, Profession, Bill};

class BailController extends Controller
{
    public function __construct()
    {
        // $super = \App\Role::where('name', 'super')->first();
        // foreach (['create', 'update', 'delete', 'read'] as $permission) {
        //     $p = \App\Permission::firstOrCreate(['name' => 'bails-'. $permission]);
        //     $super->permissions()->sync($p);
        // }
        $this->middleware('permission:bails-create')->only(['create', 'store']);
        $this->middleware('permission:bails-read')->only(['index', 'show']);
        $this->middleware('permission:bails-update')->only(['edit', 'update']);
        $this->middleware('permission:bails-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        
        $bails = Bail::orderBy('created_at');
        $first_bail = Bail::first();
        $from_date = is_null($request->from_date) ? (is_null($first_bail) ? date('Y-m-d') : $first_bail->created_at->format('Y-m-d')) : $request->from_date;
        $to_date = is_null($request->to_date) ? date('Y-m-d') : $request->to_date;
        $status = !is_null($request->status) ? $request->status : 'trail';
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $bails = $bails->whereBetween('created_at', [$from_date_time, $to_date_time]);
        // $bails = $bails->whereBetween('created_at', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        // $bails = $bails->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
        if ($country_id != 'all') {
            $bails = $bails->where('country_id', $country_id);
        }
        
        $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
        if ($profession_id != 'all') {
            $bails = $bails->where('profession_id', $profession_id);
        }
        
        $bails = $bails->get();
        
        $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
        if ($office_id != 'all') {
            $bails = $bails->filter(function($bail) use($office_id){
                return $bail->cv->office_id == $office_id;
            });
        }
        $gender = !is_null($request->gender) ? $request->gender : 'all';
        if ($gender != 'all') {
            $bails = $bails->filter(function($bail) use($gender){
                return $bail->cv->gender == array_search($gender, Cv::GENDERS);
            });
        }
        
        if ($status != 'all') {
            $bails = $bails->filter(function($bail) use($status){
                return $bail->checkStatus($status);
            });
        }
        
        return view('services::bails.index', compact('bails', 'from_date', 'to_date', 'status', 'gender', 'office_id', 'country_id', 'profession_id', 'countries', 'offices', 'professions'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $view = !is_null($request->view) ? $request->view : 'create';
        $layout = 'master';
        $title = 'إضافة عقد';
        $crumbs = [
        'title' => $title,
        'datatable' => true,
        'modals' => ['customer', 'marketer'],
        'crumbs' => [
        [route('bails.index'), 'العقود'],
        ['#', $title],
        ]
        ];
        
        $countries = Country::all();
        $professions = Profession::all();
        $statuses = Bail::STATUSES;
        $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv){
            $data = $cv->getAttributes();
            return array_merge($data, [
            'gender' => $cv->displayGender(),
            'age' => $cv->age(),
            'payed' => $cv->payed(),
            'country_name' => $cv->country->name,
            'office_name' => $cv->office->name ?? '',
            'profession_name' => $cv->profession->name,
            ]);
        });
        // dd($cvs);
        $cv = Cv::find($request->cv_id);
        if(is_null($cv)){
            $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
            $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
            $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        else{
            $country_id = $cv->country_id;
            $office_id = $cv->office_id;
            $profession_id = $cv->profession_id;
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        
        if ($view == 'initial') {
            $bail = Bail::find($request->bail_id);
            $title = is_null($bail) ? 'إنشاء عقد مبدئي' : 'عقد مبدئي';
            $layout = is_null($bail) ? 'base' : 'print';
            $crumbs = [
            'title' => $title,
            'datatable' => true,
            'modals' => ['customer', 'marketer'],
            'crumbs' => [
            [route('bails.index'), 'العقود'],
            ['#', $title],
            ]
            ];
            if (!is_null($bail)) {
                $crumbs = [
                'title' => $title,
                'heading' => 'عقد مبدئي',
                ];
            }
            
            $compact = is_null($bail) ? compact('crumbs', 'cvs', 'cv', 'layout', 'countries', 'professions', 'country_id', 'profession_id', 'statuses', 'title', 'bail') : compact('crumbs', 'title', 'bail', 'layout');
            return view('services::bails.' . $view, $compact);
        }
        
        $customers = Customer::all();
        $offices = Office::all();
        $marketers = Marketer::all();
        return view('services::bails.' . $view, compact('crumbs', 'marketers', 'customers', 'cvs', 'cv', 'layout', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id', 'statuses'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param  Request  $request
    * @return Response
    */
    public function store(Request $request)
    {
        if ($request->type == 'initial') {
            $request->validate([
            'customer_name' => 'required|string',
            'customer_phones' => 'required|string',
            'customer_id_number' => 'numeric',
            'visa' => 'nullable|numeric',
            ]);
            $data = $request->only(['cv_id', 'visa']);
            $cv = Cv::findOrFail($request->cv_id);
            $data['country_id'] = $cv->country_id;
            $data['office_id'] = $cv->office_id;
            $data['profession_id'] = $cv->profession_id;
            $data['amount'] = 0;
            
            if ($request->customer_id_number) {
                $customer = Customer::where('id_number', $request->customer_id_number)->first();
            }
            if (!!is_null($request->customer_id_number) && !is_null($request->customer_phones)) {
                $customer = Customer::where('phones', $request->customer_phones)->first();
            }
            if(!isset($customer)){
                $customer = Customer::firstOrCreate([
                'name' => $request->customer_name,
                'phones' => $request->customer_phones,
                ]);
            }
            $data['customer_id'] = $customer->id;
            $data['user_id'] = auth()->user()->id;
            $data['status'] = Bail::STATUS_INITIAL;
            // dd($data);
            $bail = Bail::create($data);
            $cv->bailing($bail->id, Bail::STATUS_INITIAL);
            if ($bail) {
                return redirect()->route('bails.create', ['view' => 'initial', 'bail_id' => $bail->id])->withSuccess('تم إنشاء العقد بنجاح');
            }
            return back()->withError('حدث خطأ اثناء إنشاء العقد');
        }else {
            $request->validate([
            'phones' => 'unique:customers',
            'visa' => 'nullable|numeric',
            'details' => 'nullable|string',
            'cv_id' => 'required|numeric',
            'profession_id' => 'required',
            'country_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'marketer_id' => 'nullable',
            'marketing_ratio' => 'nullable|numeric',
            ]);
            $data = $request->except(['_token', 'marketer_id']);
            $cv = Cv::findOrFail($request->cv_id);
            if ($request->country_id == 'all' || $request->country_id != $cv->country_id) {
                $data['country_id'] = $cv->country_id;
            }
            if ($request->profession_id == 'all' || $request->profession_id != $cv->profession_id) {
                $data['profession_id'] = $cv->profession_id;
            }
            
            if (is_null($request->customer_id)) {
                $customer_data = [];
                if (!is_null($request->customer_name)) {
                    $customer_data['name'] = $request->customer_name;
                }
                if (!is_null($request->customer_id_number)) {
                    $customer_data['id_number'] = $request->customer_id_number;
                }
                if (count($customer_data)) {
                    $customer = Customer::create($customer_data);
                    if ($customer) {
                        $data['customer_id'] = $customer->id;
                    }
                }
            }
            
            if ($request->marketer_id && $request->marketing_ratio) {
                $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
                $data['marketer_id'] = $marketer->id;

                $debt = $request->marketing_ratio;

                $marketer->update([
                    'debt' => ($marketer->debt +  $debt)
                ]);
            }else if($request->marketer_id) {
                $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
                $data['marketer_id'] = $marketer->id;
            }
            
            $data['user_id'] = auth()->user()->id;

            $bail = Bail::create($data);
            
            
            $cv->bailing($bail->id, $request->status);
            
            if ($bail) {
                $bail->attach();
            }
            return redirect()->route('bails.show', $bail->id)->with('success', __('global.operation_success'));
        }
        
    }
    
    /**
    * Show the specified resource.
    * @param  Bail  $bail
    * @return Response
    */
    public function show(Bail $bail)
    {
        // dd($bail->all_vouchers, $bail->cvs_vouchers);
        return view('services::bails.show', compact('bail'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param  Bail  $bail
    * @return Response
    */
    public function edit(Request $request, Bail $bail)
    {
        $marketers = Marketer::all();
        // factory(Customer::class, 10)->create();
        $customers = Customer::all();
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        $statuses = Bail::STATUSES;
        $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv){
            return [
            'id' => $cv->id,
            'name' => $cv->name,
            'gender' => $cv->displayGender(),
            'age' => $cv->age(),
            'passport' => $cv->passport,
            'payed' => $cv->payed(),
            'passport' => $cv->passport,
            'country_id' => $cv->country_id,
            'office_id' => $cv->office_id,
            'profession_id' => $cv->profession_id,
            'country_name' => $cv->country->name,
            'office_name' => $cv->office->name ?? '',
            'profession_name' => $cv->profession->name,
            ];
        });
        $cv = $bail->cv;
        if(is_null($cv)){
            $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
            $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
            $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        else{
            $country_id = $cv->country_id;
            $office_id = $cv->office_id;
            $profession_id = $cv->profession_id;
            $cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
        }
        
        
        return view('services::bails.edit', compact('bail', 'marketers', 'customers', 'cvs', 'cv', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id', 'statuses'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param  Request  $request
    * @param  Bail  $bail
    * @return Response
    */
    public function update(Request $request, Bail $bail)
    {
        // dd($bail->cvs->where('pivot.cv_id', $request->cv_id)->first()->pivot->status);
        $request->validate([
        'visa' => 'nullable|numeric',
        // 'details' => 'nullable|string',
        'profession_id' => 'required',
        'country_id' => 'required',
        'amount' => 'required|numeric|min:0',
        ]);
        
        $data = $request->except(['_token', '_method', 'marketer_id']);
        $cv = Cv::findOrFail($request->cv_id);
        if ($request->country_id == 'all' || $request->country_id != $cv->country_id) {
            $data['country_id'] = $cv->country_id;
        }
        if ($request->profession_id == 'all' || $request->profession_id != $cv->profession_id) {
            $data['profession_id'] = $cv->profession_id;
        }
        
        if (is_null($request->customer_id)) {
            $customer_data = [];
            if (!is_null($request->customer_name)) {
                $customer_data['name'] = $request->customer_name;
            }
            if (!is_null($request->customer_id_number)) {
                $customer_data['id_number'] = $request->customer_id_number;
            }
            if (count($customer_data)) {
                $customer = Customer::firstOrCreate($customer_data);
                if ($customer) {
                    $data['customer_id'] = $customer->id;
                }
            }
        }
        
        if ($bail->cvs->count()) {
            if (!$bail->cvs->contains($request->cv_id)) {
                foreach($bail->cvs->where('pivot.status', Bail::STATUS_WORKING) as $c){
                    $bail->cvs()->updateExistingPivot($c, array('status' => Bail::STATUS_CANCELED), false);
                    $c->update(['status' => Cv::STATUS_ACCEPTED]);
                }
                
                
                $cv->bailing($bail->id, $request->status);
            }else{
                $bail_cv = $bail->cvs->where('pivot.cv_id', $request->cv_id)->last();
                if ($bail_cv->pivot->status != $request->status) {
                    $bail->cvs()->updateExistingPivot($cv, array('status' => $request->status), false);
                }
            }
        }else{
            $cv->bailing($bail->id, $request->status);
        }
        
        if ($request->marketer_id) {
            $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
            $data['marketer_id'] = $marketer->id;
            $debt = $request->marketing_ratio;
            if ($bail->marketer_id) {
                $bail->marketer->update([
                'debt' => ($bail->marketer->debt -  $bail->marketing_ratio)
                ]);
            }
            
            $marketer->update([
            'debt' => ($marketer->debt +  $debt)
            ]);
        }
        
        $bail->update($data);
        
        return back()->with('success', __('global.operation_success'));
    }
    
    /**
    * Remove the specified resource from storage.
    * @param  Bail  $bail
    * @return Response
    */
    public function destroy(Request $request, Bail $bail)
    {
        if ($request->operation == 'cancel') {
            $bail->cancel();
            return back()->with('success', __('global.operation_success'));
        }
        $previous_url = url()->previous();
        $show_url = route('bails.show', $bail);
        $bail->delete();
        if($previous_url == $show_url){
            return redirect()->route('bails.index')->with('success', __('bails.cancel_success'));
        }
        return back()->with('success', __('global.delete_success'));
    }
}