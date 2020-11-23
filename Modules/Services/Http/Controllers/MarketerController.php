<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Cv;
use Illuminate\Routing\Controller;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;

class MarketerController extends Controller
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
		$cvs = Cv::all();
		$customers = Customer::all();
		return view('services::marketers.index', compact('marketers', 'cvs', 'customers'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('services::marketers.create');
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
			'phone' => 'numeric'
		]));

		return back()->with('success', 'تمت العملية بنجاح');
	}

	/**
	 * Show the specified resource.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function show($marketer)
	{
		$marketer = Marketer::find($marketer);
		return view('services::marketers.show', compact('marketer'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function edit(Marketer $marketer)
	{
		return view('services::marketers.edit', compact('marketer'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function update(Request $request, $marketer)
	{
		$request->validate([
			'name' => 'required | string | max:40',
			'phone' => 'required | numeric'
		]);
		$marketer = Marketer::find($marketer);
		$marketer->update($request->except(['_token', '_method']));

		return redirect()->route('servicesmarketers.index')->with('success', 'تمت العملية بنجاح');
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Marketer  $marketer
	 * @return Response
	 */
	public function destroy(Marketer $marketer)
	{
		$marketer->delete();

		return redirect()->route('marketers.index')->with('success', 'تمت العملية بنجاح');
	}

	public function credit(Request $request)
	{
		$marketer = Marketer::find($request->marketer_id);

		$marketer->update([
			'debt' => $marketer->debt - $request->credit,
			'credit' => $marketer->credit + $request->credit
		]);

		return back()->with('success', 'تمت العملية بنجاح');
	}
}
