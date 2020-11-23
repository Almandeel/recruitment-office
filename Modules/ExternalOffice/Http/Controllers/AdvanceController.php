<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Advance;

class AdvanceController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:advances-create')->only(['create', 'store']);
        $this->middleware('permission:advances-read')->only(['index', 'show']);
        $this->middleware('permission:advances-update')->only(['edit', 'update']);
        $this->middleware('permission:advances-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $advances = Advance::all();
        return view('externaloffice::advances.index', compact('advances'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required | numeric',
        ]);

        $advance = Advance::create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
            'office_id' => auth()->guard('office')->user()->office_id,
        ]);
        if ($advance) {
            $advance->attach();
        }
        return back()->with('success', __('global.operation_success'));
    }

    /**
     * Show the specified resource.
     * @param  Advance  $advances
     * @return Response
     */
    public function show(Advance $advance)
    {
        return view('externaloffice::advances.show', compact('advance'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  Advance  $advances
     * @return Response
     */
    public function edit(Advance $advances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  Request  $request
     * @param  Advance  $advances
     * @return Response
     */
    public function update(Request $request, Advance $advances)
    {
        // $request->validate([
        //     'amount' => 'numeric',
        // ]);
        $advances->update($request->except(['_token', '_method']));

        return back()->with('success', __('global.operation_success'));
    }

    /**
     * Remove the specified resource from storage.
     * @param  Advance  $advances
     * @return Response
     */
    public function destroy(Advance $advances)
    {
        //
    }
}
