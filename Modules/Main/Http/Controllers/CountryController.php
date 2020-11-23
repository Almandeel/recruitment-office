<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Country;

class CountryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$countries = Country::all();
		return view('main::countries.index', compact('countries'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('main::countries.create');
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
		return view('main::countries.show', compact('profession'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Country  $country
	 * @return Response
	 */
	public function edit(Country $country)
	{
		return view('main::countries.edit', compact('profession'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Country  $country
	 * @return Response
	 */
	public function update(Request $request, $country)
	{
		$country = Country::find($country);
		$country->update($request->validate([
			'name' => 'required | string | max:40',
		]));

		return redirect()->route('maincountries.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Country  $country
	 * @return Response
	 */
	public function destroy(Country $country)
	{
		$country->delete();

		return redirect()->route('maincountries.index')->with('success', __('global.operation_success'));
	}
}
