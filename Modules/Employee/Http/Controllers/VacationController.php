<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Vacation;

class VacationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vacations-create')->only(['create', 'store']);
        $this->middleware('permission:vacations-read')->only(['index', 'show']);
        $this->middleware('permission:vacations-update')->only(['edit', 'update']);
        $this->middleware('permission:vacations-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $vacations = Vacation::where('accepted', 0)->get();
        $employees = Employee::all();
        return view('employee::vacations.index', compact('vacations', 'employees'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create(Request $request)
    {
        $employees = Employee::all();
        $employee = $employees->count() ? $employees->first() : null;
        $employee = isset($request->employee_id) ? Employee::findOrFail($request->employee_id) : $employee;
        // dd($employee->name);
        return view('employee::vacations.create', compact('employees', 'employee'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
        'title'         => 'required',
        'details'       => 'required',
        'payed'         => 'required',
        'started_at'    => 'required',
        'employee_id'   => 'required'
        ]);
        
        $vacation = Vacation::create($request->all());
        if ($vacation) {
            $vacation->attach();
        }
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Vacation $vacation)
    {
        return view('employee::vacations.show', compact('vacation'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit(Vacation $vacation)
    {
        $employees = Employee::all();
        $employee = $vacation->employee;
        return view('employee::vacations.edit', compact('employees', 'employee', 'vacation'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, Vacation $vacation)
    {
        
        $request->validate([
        'title'         => 'required',
        'details'       => 'required',
        'payed'         => 'required',
        'started_at'    => 'required',
        'employee_id'   => 'required'
        ]);
        
        $data = $request->all();
        
        $data['accepted'] = 1;
        
        $vacation->update($data);
        
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy(Vacation $vacation)
    {
        $previous_url = url()->previous();
        $show_url = route('vacations.show', $vacation);
        $vacation->delete();
        if($previous_url == $show_url){
            return redirect()->route('vacations.index')->with('success', __('vacations.delete_success'));
        }
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
}