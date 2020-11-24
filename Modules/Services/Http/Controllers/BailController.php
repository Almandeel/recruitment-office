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
        
        $bails = Bail::with('contract')->orderBy('created_at');
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
            $bails = $bails->where('contract.country_id', $country_id);
        }
        
        $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
        if ($profession_id != 'all') {
            $bails = $bails->where('contract.profession_id', $profession_id);
        }
        
        // dd($bails->get());
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
        $title = 'إضافة كفالة';
        $crumbs = [
        'title' => $title,
        'datatable' => true,
        'crumbs' => [
        [route('bails.index'), 'الكفالات'],
        ['#', $title],
        ]
        ];
        
        $statuses = Bail::STATUSES;
        $x_contract = Contract::findOrFail($request->contract_id);
        $cv = $x_contract->cv;
        $x_customer = $x_contract->customer;
        $customers = Customer::all();
        $marketers = Marketer::all();
        // dd(\Modules\Main\Models\Office::create(['id' => $cv->office_id, 'name' => 'Office', 'country_id' => 1]));
        return view('services::bails.create', compact('crumbs', 'marketers', 'customers', 'x_customer', 'x_contract', 'cv', 'statuses'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param  Request  $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
            'phones' => 'unique:customers',
            'visa' => 'nullable|numeric',
            'details' => 'nullable|string',
            'cv_id' => 'required|numeric',
            'x_contract_id' => 'required',
            'x_customer_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'marketer_id' => 'nullable',
            'marketing_ratio' => 'nullable|numeric',
            'id_number'   => 'string|nullable| unique:customers',
        ]);
        $data = $request->except(['_token']);
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
        }else if($request->marketer_id) {
            $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
            $data['marketer_id'] = $marketer->id;
        }
        
        $data['user_id'] = auth()->user()->id;
        // dd($data);
        $contract_data = $data;
        $contract_data['status'] = Contract::STATUS_TRAIL;
        $contract = Contract::create($contract_data);
        $data['contract_id'] = $contract->id;
        if ($contract) {
            $bail = Bail::create($data);
            $contract->attach();
        }
        return redirect()->route('bails.show', $bail->id)->with('success', __('global.operation_success'));
        
    }
    
    /**
    * Show the specified resource.
    * @param  Bail  $bail
    * @return Response
    */
    public function show(Bail $bail)
    {
        $cv = $bail->cv;
        $contract = $bail->contract;
        $x_contract = $bail->x_contract;
        $customer = $bail->customer;
        $x_customer = $bail->x_customer;
        return view('services::bails.show', compact('bail', 'cv', 'contract', 'x_contract', 'customer', 'x_customer'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param  Bail  $bail
    * @return Response
    */
    public function edit(Request $request, Bail $bail)
    {
        $cv = $bail->cv;
        $contract = $bail->contract;
        $x_contract = $bail->x_contract;
        $customer = $bail->customer;
        $x_customer = $bail->x_customer;
        $cv = $x_contract->cv;
        $customers = Customer::all();
        $marketers = Marketer::all();

        return view('services::bails.edit', compact('bail', 'cv', 'contract', 'x_contract', 'customer', 'x_customer', 'customers', 'marketers'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param  Request  $request
    * @param  Bail  $bail
    * @return Response
    */
    public function update(Request $request, Bail $bail)
    {
        $request->validate([
        'visa' => 'nullable|numeric',
        'notes' => 'nullable|string',
        'amount' => 'numeric|min:0',
        ]);
        
        $data = $request->except(['_token', '_method', 'marketer_id']);
        $contract_data = $request->except(['_token', '_method', 'status']);
        
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
                    $contract_data['customer_id'] = $customer->id;
                }
            }
        }
        
        
        if ($request->marketer_id) {
            $marketer = Marketer::firstOrCreate(['name' => $request->marketer_id]);
            $contract_data['marketer_id'] = $marketer->id;
            $debt = $request->marketing_ratio;
        }
        if ($request->status == 'confirmed') {
            $contract_data['status'] = Contract::STATUS_WORKING;
        }
        else if ($request->status == 'canceled') {
            $contract_data['status'] = Contract::STATUS_CANCELED;
        }
        
        $bail->contract->update($contract_data);
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