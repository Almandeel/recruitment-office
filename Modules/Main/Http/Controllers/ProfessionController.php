<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Profession;

class ProfessionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$professions = Profession::all();
		return view('main::professions.index', compact('professions'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('main::professions.create');
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

		return back()->with('success', 'تمت العملية بنجاح');
	}

	/**
	 * Show the specified resource.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function show(Profession $profession)
	{
		return view('main::professions.show', compact('profession'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function edit(Profession $profession)
	{
		return view('main::professions.edit', compact('profession'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function update(Request $request, $profession)
	{
		$profession = Profession::find($profession);
		$profession->update($request->validate([
			'name' => 'required | string | max:40',
			'name_en' => 'required | string | max:40'
		]));

		return redirect()->route('mainprofessions.index')->with('success', 'تمت العملية بنجاح');
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Profession  $profession
	 * @return Response
	 */
	public function destroy(Profession $profession)
	{
		$profession->delete();

		return redirect()->route('mainprofessions.index')->with('success', 'تمت العملية بنجاح');
	}
}
