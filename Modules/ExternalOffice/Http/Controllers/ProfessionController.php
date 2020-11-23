<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Profession;

class ProfessionController extends BaseController
{
	public function __construct()
	{
		$this->middleware('permission:professions-create')->only(['create', 'store']);
		$this->middleware('permission:professions-read')->only(['index', 'show']);
		$this->middleware('permission:professions-update')->only(['edit', 'update']);
		$this->middleware('permission:professions-delete')->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$professions = Profession::all();
		return view('externaloffice::professions.index', compact('professions'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('externaloffice::professions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		Profession::create($request->validate([
			'name' => 'required | string | max:40',
			'name_en' => 'required | string | max:40'
		]));

		return redirect()->route('professions.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Show the specified resource.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function show(Profession $profession)
	{
		return view('externaloffice::professions.show', compact('profession'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function edit(Profession $profession)
	{
		return view('externaloffice::professions.edit', compact('profession'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function update(Request $request, Profession $profession)
	{
		$profession->update($request->validate([
			'name' => 'required | string | max:40',
			'name_en' => 'required | string | max:40'
		]));

		return redirect()->route('professions.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function destroy(Profession $profession)
	{
		$profession->delete();

		return redirect()->route('professions.index')->with('success', __('global.operation_success'));
	}
}
