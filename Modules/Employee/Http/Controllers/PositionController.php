<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:positions-create')->only(['create', 'store']);
        $this->middleware('permission:positions-read')->only(['index', 'show']);
        $this->middleware('permission:positions-update')->only(['edit', 'update']);
        $this->middleware('permission:positions-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $positions = Position::all();
        return view('employee::positions.index', compact('positions'));
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

        Position::create($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $position = Position::find($id);
        $positions = Position::all();
        $departments = Department::all();
        return view('employee::positions.show', compact('position', 'positions', 'departments'));
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

        $position = Position::find($id);

        $position->update($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($position)
    {
        $position = Position::find($position);

        foreach($position->employees as $position) {
            $position->delete();
        }

        $position->delete();

        return back()->with('success', 'تمت العملية بنجاح');
    }
}
