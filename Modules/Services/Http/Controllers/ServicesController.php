<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;
use Modules\Services\Models\Complaint;
use Modules\Services\Models\ContractCustomer;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $cvs = Cv::all();
        $customers = Customer::all();
        $contracts = Contract::all();
        $complaints = Complaint::all();
        $marketers = Marketer::all();
        return view('services::index', compact('cvs', 'customers', 'contracts', 'complaints', 'marketers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('services::create');
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
    public function show($id)
    {
        return view('services::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('services::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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



    public function cvs($country, $profession) {
        if($country == 'null' && $profession == 'null') {
            $cvs = Cv::where('accepted', 1)->where('contract_id', null)->get();
        }else if($country == 'null' && $profession != 'null') {
            $cvs = Cv::where('accepted', 1)->where('contract_id', null)->where('profession_id', $profession)->get();
        }else if($country != 'null' && $profession == 'null') {
            $cvs = Cv::where('accepted', 1)->where('contract_id', null)->where('country_id', $country)->get();
        }else {
            $cvs = Cv::where('accepted', 1)->where('contract_id', null)->where('country_id', $country)->where('profession_id', $profession)->get();
        }
        
        return response()->json($cvs);
    }

    public function getCv($id) {
        $cv = Cv::find($id);
        $country = $cv->country;
        $profession = $cv->profession;
        return response()->json([
            'cv' => $cv,
            'country' => $country,
            'profession' => $profession,
        ]);
    }

    public function customerCvs($customer) {
        $cvs = Contract::where('customer_id', $customer)
            ->get()
            ->map(function ($c)
            {
                return $c->cv();
            });
        return response()->json($cvs->toArray());
    }

    public function business(Request $request) {
        $customer = Customer::where('id_number', $request->id_number)->first();
        $cv = Cv::find($request->cv_id);
        if(isset($customer)) {
            $contract = Contract::create([
                'visa' =>$request->visa,
                'country_id' =>$cv->country_id,
                'profession_id' =>$cv->profession_id,
                'customer_id' =>$customer->id,
            ]);
            $cv->contracting($contract->id, Contract::STATUS_INITIAL);
            return back()->with('success', __('global.operation_success'));
        }else {
            $customer = Customer::create([
                'name' => $request->name,
                'address' => $request->address,
                'phones' => $request->phones,
                'id_number' => $request->id_number,
            ]);

            $contract = Contract::create([
                'visa' =>$request->visa,
                'country_id' =>$cv->country_id,
                'profession_id' =>$cv->profession_id,
                'customer_id' =>$customer->id,
            ]);
            $cv->contracting($contract->id, Contract::STATUS_INITIAL);
            return back()->with('success', __('global.operation_success'));
        }
    }
}
