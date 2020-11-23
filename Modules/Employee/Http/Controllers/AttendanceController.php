<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Attendance;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance-create')->only(['create', 'store']);
        $this->middleware('permission:attendance-read')->only(['index', 'show']);
        $this->middleware('permission:attendance-update')->only(['edit', 'update']);
        $this->middleware('permission:attendance-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $date = date('Y-m-d');
        $attendance = Attendance::where('attend_date', $date)->where('time_out', null)->get();
        $employees = Employee::all();
        return view('employee::attendance.index', compact('attendance', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'time_in'       => 'required',
            'employee_id'   => 'required',
        ]);
        $data = $request->all();
        $data['attend_date'] = date('Y-m-d');
        Attendance::create($data);

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('employee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        $request->validate([
            'time_in'       => 'required',
            'time_out'      => 'required',
            'employee_id'   => 'required',
        ]);


        $attendance->update($request->all());

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
}
