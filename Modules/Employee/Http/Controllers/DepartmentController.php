<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Position;
use Modules\Tutorial\Models\Category;
use Modules\Employee\Models\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:departments-create')->only(['create', 'store']);
        $this->middleware('permission:departments-read')->only(['index', 'show']);
        $this->middleware('permission:departments-update')->only(['edit', 'update']);
        $this->middleware('permission:departments-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $departments = Department::all();
        return view('employee::departments.index', compact('departments'));
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
            'title' => 'required'
        ]);

        Department::create($request->all());

        Category::create([
            'name' => $request->title
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
        $department = Department::find($id);
        $departments = Department::all();
        $positions = Position::all();
        return view('employee::departments.show', compact('department', 'departments', 'positions'));
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
        $request->validate([
            'title' => 'required'
        ]);

        $department = Department::find($id);

        // update category tutrial
        $category = Category::where('name', 'like', '%' . $department->title . '%')->first();
        $category->update([
            'name' => $request->title
        ]);

        $department->update($request->all());
        
        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Department $department)
    {

        // foreach($department->employees as $employee) {
        //     $employee->delete();
        // }

        $department->delete();

        return back()->with('success', 'تمت العملية بنجاح');

    }
}
