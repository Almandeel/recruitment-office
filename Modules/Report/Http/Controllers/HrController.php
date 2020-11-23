<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;

class HrController extends Controller
{
    public function index() {
        return view('report::hr.index');
    }

    public function employees(Request $request) {
        if($request->type == 'department') {
            $departments = Department::where('title', 'like', '%' . $request->department . '%')->first();
            $employees = $departments ? Employee::where('department_id', $departments->id)->get() : [];
        }

        if($request->type == 'position') {
            $position = Position::where('title', 'like', '%' . $request->position . '%')->first();
            $employees = $position ? Employee::where('position_id', $position->id)->get() : [];
        }

        if($request->type == 'start') {
            $employees =  Employee::whereBetween('started_at', [$request->from, $request->to])->get();
        }

        if($request->type == '*' || !$request->type) {
            $employees = Employee::all();
        }
        return view('report::hr.employees', compact('employees'));
    }
}
