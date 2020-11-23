<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Complaint;

class ComplaintController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:complaints-create')->only(['create', 'store']);
        $this->middleware('permission:complaints-read')->only(['index', 'show']);
        $this->middleware('permission:complaints-update')->only(['edit', 'update']);
        $this->middleware('permission:complaints-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index(Request $request)
    {
        $cvs = Cv::all();
        $customers = Customer::all();
        $complaints = Complaint::get();
        return view('services::complaints.index', compact('complaints', 'cvs', 'customers'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $customers = Customer::all();
        $cvs = Cv::all();
        return view('services::complaints.create', compact('customers', 'cvs'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'cause'     => 'required | string',
        ]);
        
        $request_data = $request->all();
        $request_data['user_id'] = auth()->user()->getKey();
        
        $complaint = Complaint::create($request_data);
        if ($complaint) {
            $complaint->attach();
        }
        return redirect()->route('complaints.show', $complaint)->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show($id)
    {
        $complaint = Complaint::find($id);
        return view('services::complaints.show', compact('complaint'));
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
        $complaint = Complaint::find($id);
        
        $complaint->update([
        'status' => 1
        ]);
        
        return redirect()->route('complaints.index')->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        $complaint = Complaint::find($id);
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'تمت العملية بنجاح');
    }
}