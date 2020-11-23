<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Marketer;

class MarketerController extends BaseController
{
	public function __construct()
	{
		$this->middleware('permission:marketers-create')->only(['create', 'store']);
		$this->middleware('permission:marketers-read')->only(['index', 'show']);
		$this->middleware('permission:marketers-update')->only(['edit', 'update']);
		$this->middleware('permission:marketers-delete')->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$marketers = Marketer::all();
		return view('externaloffice::marketers.index', compact('marketers'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('externaloffice::marketers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		Marketer::create($request->validate([
			'name' => 'required | string | max:40',
			'phone' => 'required | numeric'
		]));

		return back()->with('success', __('global.operation_success'));
	}

	/**
	 * Show the specified resource.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function show(Marketer $marketer)
	{
		return view('externaloffice::marketers.show', compact('marketer'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function edit(Marketer $marketer)
	{
		return view('externaloffice::marketers.edit', compact('marketer'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function update(Request $request, Marketer $marketer)
	{
		$marketer->update($request->validate([
			'name' => 'required | string | max:40',
			'phone' => 'required | numeric'
		]));

		return redirect()->route('marketers.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function destroy(Marketer $marketer)
	{
		$marketer->delete();

		return redirect()->route('marketers.index')->with('success', __('global.operation_success'));
	}
}
