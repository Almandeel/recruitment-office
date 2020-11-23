<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers-create')->only(['create', 'store']);
        $this->middleware('permission:customers-read')->only(['index', 'show']);
        $this->middleware('permission:customers-update')->only(['edit', 'update']);
        $this->middleware('permission:customers-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $cvs = Cv::all();

        $customers = Customer::all();

        $newCustomers = Customer::doesntHave('contracts')->get();

        $contractedCustomers = Customer::has('contracts')->get();
        return view('services::customers.index', compact('customers', 'newCustomers', 'contractedCustomers', 'cvs'));
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
        $request->validate([
            'name'        => 'required | string',
            'phones'      => 'required | string',
            'address'     => 'required | string',
            'id_number'   => 'string | unique:customers',
        ]);

        $customer = Customer::create($request->all());

        $customer->update([
            'user_id' => $request->user()->id
        ]);

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $cvs = Cv::all();
        $customer = Customer::find($id);
        $customers = Customer::all();
        return view('services::customers.show', compact('cvs', 'customer', 'customers'));
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
        // dd($request->phones);
        $customer = Customer::find($id);

        $request->validate([
            'name'        => 'required | string',
            'phones'      => 'required',
            'address'     => 'required | string',
            'id_number'   => ['nullable', Rule::unique('customers', 'id_number')->ignore($customer)]
        ]);
        
        $customer->update($request->all());
        
        return back()->with('success', 'تمت العملية بنجاح');
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

    public function addContract($customer) {
        $customer = Customer::find($customer);
        if($customer) {
            $customers = Customer::all();
            $cvs = Cv::all();
            $marketers = Marketer::all();
            return view('services::customers.contract', compact('cvs', 'customer', 'customers', 'marketers'));
        }else {
            return back()->with('error', 'المعرف غير صحيح');
        }
        
    }
}
