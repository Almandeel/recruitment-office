<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Advance;

class AdvanceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$advances = Advance::all();
		return view('main::advances.index', compact('advances'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Advance  $advance
	 * @return Response
	 */
	public function update(Request $request, Advance $advance)
	{
		$request->validate([
			'status' => 'required | numeric',
		]);

		$advance->update($request->all());

		return redirect()->route('mainadvances.index')->with('success', __('global.operation_success'));
	}

	public function show(Advance $advance)
	{
		return view('main::advances.show', compact('advance'));
	}

	public function destroy(Advance $advance)
	{
		$previous_url = url()->previous();
        $show_url = route('offices.advances.show', $advance);
        $advance->delete();
        if($previous_url == $show_url){
            return redirect()->route('offices.advances.index')->with('success', __('global.delete_success'));
		}
		return back()->with('success', __('global.delete_success'));
	}
}
