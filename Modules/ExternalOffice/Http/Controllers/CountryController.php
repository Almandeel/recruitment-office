<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Country;

class CountryController extends BaseController
{
	public function __construct()
	{
		$this->middleware('permission:countries-create')->only(['create', 'store']);
		$this->middleware('permission:countries-read')->only(['index', 'show']);
		$this->middleware('permission:countries-update')->only(['edit', 'update']);
		$this->middleware('permission:countries-delete')->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$countries = Country::all();
		return view('externaloffice::countries.index', compact('countries'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('externaloffice::countries.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		Country::create($request->validate([
			'name' => 'required | string | max:40',
		]));

		return back()->with('success', __('global.operation_success'));
	}

	/**
	 * Show the specified resource.
	 * @param  Country  $country
	 * @return Response
	 */
	public function show(Country $country)
	{
		return view('externaloffice::countries.show', compact('profession'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Country  $country
	 * @return Response
	 */
	public function edit(Country $country)
	{
		return view('externaloffice::countries.edit', compact('profession'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Country  $country
	 * @return Response
	 */
	public function update(Request $request, Country $country)
	{
		$country->update($request->validate([
			'name' => 'required | string | max:40',
		]));

		return redirect()->route('countries.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Country  $country
	 * @return Response
	 */
	public function destroy(Country $country)
	{
		$country->delete();

		return redirect()->route('countries.index')->with('success', __('global.operation_success'));
	}
}
