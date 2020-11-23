<?php

namespace Modules\ExternalOffice\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\{Cv, Profession};

class CvController extends BaseController
{
	public function __construct()
	{
		$this->middleware('permission:cv-create')->only(['create', 'store']);
		$this->middleware('permission:cv-read')->only(['index', 'show']);
		$this->middleware('permission:cv-update')->only(['edit', 'update']);
		$this->middleware('permission:cv-delete')->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index(Request $request)
	{
		$cvs = Cv::orderBy('created_at', 'DESC');
		$from_date = $request->has('from_date') ? $request->from_date : ($cvs->get()->isEmpty() ? now()->subDay() : $cvs->get()->first()->created_at);

		$to_date = $request->has('to_date') ? $request->to_date : now();

		$cvs = $cvs->whereBetween('created_at', [
			Carbon::parse($from_date)->startOfDay(),
			Carbon::parse($to_date)->endOfDay()
		]);

		$status = $request->has('status') ? $request->status : 'all';
		if ($status != 'all') {		
			$cvs = $cvs->where('status', array_search($status, Cv::STATUSES));
		}

		$gender = $request->has('gender') ? $request->gender : 'all';
		if ($gender != 'all') {
			$cvs = $cvs->where('gender', array_search($gender, Cv::GENDERS));
		}
		$cvs = $cvs->get();
		return view('externaloffice::cvs.index', compact('cvs', 'status', 'gender', 'from_date', 'to_date'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		$professions = Profession::all();
		return view('externaloffice::cvs.create', compact('professions'));
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$request_data = $request->validate([
			'name' => 'required | string | max:140',
			'passport' => 'required | string | max:140',
			'amount' => 'required | numeric',
			'profession_id' => 'required | numeric',
			'gender' => 'required | numeric',
			'birth_date' => 'required | date',
			'procedure' => 'required | string | max:200',
			'children' => 'numeric', 'phone',

			'reference_number' => 'nullable',
			'religion' => 'nullable|string',
			'birthplace' => 'nullable|string',
			'marital_status' => 'nullable|string',
			'phone' => 'nullable',
			'qualification' => 'nullable | string',
			'english_speaking_level' => 'nullable | string',
			'experince' => 'nullable | string',
			'weight' => 'nullable | numeric',
			'height' => 'nullable | numeric',
			'sewing' => 'nullable | boolean',
			'decor' => 'nullable | boolean',
			'cleaning' => 'nullable | boolean',
			'washing' => 'nullable | boolean',
			'cooking' => 'nullable | boolean',
			'babysitting' => 'nullable | boolean',
			'passport_place_of_issue' => 'nullable | string',
			'passport_issuing_date' => 'nullable | date',
			'passport_expiration_date' => 'nullable | date',
			'contract_period' => 'nullable | string',
			'contract_salary' => 'nullable | numeric',
			'bio' => 'nullable | string',
			'photo' => 'nullable | file',
			'passport_photo' => 'nullable | file',
		]);

		$request_data['office_id'] = auth()->guard('office')->user()->office_id;
		$request_data['country_id'] = auth()->guard('office')->user()->office->country_id;
		$request_data['user_id'] = auth()->guard('office')->user()->id;

		// if ($request->has('photo')) {
		// 	$file = $request->file('photo');
		// 	$extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
		// 	$newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
		// 	$file->move(public_path("/cvs_data"), $newFileName);

		// 	array_merge($request_data, ['photo' => $newFileName]);
		// }

		if ($request->has('photo')) {
			$fileName = time().'.'.$request->photo->extension();
			
			$request->photo->move(public_path('cvs_data'), $fileName);
			$request_data['photo'] = $fileName;
		}

		// if ($request->has('passport_photo')) {
		// 	$file = $request->file('passport_photo');
		// 	$extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
		// 	$newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
		// 	$file->move(public_path("/cvs_data"), $newFileName);

		// 	array_merge($request_data, ['passport_photo' => $newFileName]);
		// }

		if ($request->has('passport_photo')) {
			$fileName = time().'.'.$request->passport_photo->extension();
			
			$request->passport_photo->move(public_path('cvs_data'), $fileName);
			$request_data['passport_photo'] = $fileName;
		}

		$cv = Cv::create($request_data);
		if ($cv) {
			$cv->attach();
		}
		return redirect()->route('cvs.show', $cv)->with('success', __('global.operation_success'));
	}

	/**
	 * Show the specified resource.
	 * @param  Cv  $cv
	 * @return Response
	 */
	public function show(Cv $cv)
	{
		return view('externaloffice::cvs.show', compact('cv'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  Cv  $cv
	 * @return Response
	 */
	public function edit(Cv $cv)
	{
		$professions = Profession::all();
		return view('externaloffice::cvs.edit', compact('cv', 'professions'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param  Request  $request
	 * @param  Cv  $cv
	 * @return Response
	 */
	public function update(Request $request, Cv $cv)
	{
		$cv->update($request->validate([
			'name' => 'required | string | max:140',
			'passport' => 'required | string | max:140',
			'amount' => 'required | numeric',
			'profession_id' => 'required | numeric',
			'gender' => 'required | numeric',
			'birth_date' => 'required | date',
			'procedure' => 'required | string | max:200',
			'children' => 'numeric', 'phone',

			'reference_number' => 'nullable',
			'religion' => 'nullable|string',
			'birthplace' => 'nullable|string',
			'marital_status' => 'nullable|string',
			'phone' => 'nullable',
			'qualification' => 'nullable | string',
			'english_speaking_level' => 'nullable | string',
			'experince' => 'nullable | string',
			'weight' => 'nullable | numeric',
			'height' => 'nullable | numeric',
			'sewing' => 'nullable | boolean',
			'decor' => 'nullable | boolean',
			'cleaning' => 'nullable | boolean',
			'washing' => 'nullable | boolean',
			'cooking' => 'nullable | boolean',
			'babysitting' => 'nullable | boolean',
			'passport_place_of_issue' => 'nullable | string',
			'passport_issuing_date' => 'nullable | date',
			'passport_expiration_date' => 'nullable | date',
			'contract_period' => 'nullable | string',
			'contract_salary' => 'nullable | numeric',
			'bio' => 'nullable | string',
		]));

		if ($request->has('photo')) {
			$file = $request->file('photo');
			$extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
			$newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
			$file->move(public_path("/cvs_data"), $newFileName);

			$cv->update(['photo' => $newFileName]);
		}

		if ($request->has('passport_photo')) {
			$file = $request->file('passport_photo');
			$extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
			$newFileName = Str::random(16) . date('Ymdhis') . '.' . $extension;
			$file->move(public_path("cvs_data"), $newFileName);

			$cv->update(['passport_photo' => $newFileName]);
		}

		return redirect()->route('cvs.index')->with('success', __('global.operation_success'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param  Cv  $cv
	 * @return Response
	 */
	public function destroy(Cv $cv)
	{
		$cv->delete();

		return redirect()->route('cvs.index')->with('success', __('global.operation_success'));
	}

	public function pull(Request $request, Cv $cv)
	{
		$request->validate([
			'damages' => 'string',
			'cause' => 'string',
			'notes' => 'nullable|string'
		]);

		if (!$cv->isAccepted()) {
			return back()->with('error', 'error');
		}
		
		$pull = $cv->pulling();
		$pull->attach();

		return back()->with('success', __('global.operation_success'));
	}
}
