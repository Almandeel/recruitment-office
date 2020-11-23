<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Main\Models\Office;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;
use Modules\ExternalOffice\Models\Country;
use Modules\ExternalOffice\Models\Profession;

class ContractSearchController extends Controller
{

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create()
	{
		return view('main::contracts.search');
	}

	/**
	 * Show the specified resource.
	 * @param  Country  $country
	 * @return Response
	 */
	public function show(Request $request)
	{
		$request->validate([
			'customer_id_number' => 'required|numeric|exists:customers,id_number',
			'visa' => 'required|numeric|exists:contracts,visa',
		]);

		$customer = Customer::where('id_number', $request->customer_id_number)->firstOrFail();
		$contract = $customer->contracts()->whereVisa($request->visa)->whereStatus(-1)->firstOrFail();

		$marketers = Marketer::all();
		$customers = Customer::all();
		$countries = Country::all();
		$offices = Office::all();
		$professions = Profession::all();
		$statuses = Contract::STATUSES;
		$cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function ($cv) {
			return [
				'id' => $cv->id,
				'name' => $cv->name,
				'gender' => $cv->displayGender(),
				'age' => $cv->age(),
				'passport' => $cv->passport,
				'payed' => $cv->payed(),
				'passport' => $cv->passport,
				'country_id' => $cv->country_id,
				'office_id' => $cv->office_id,
				'profession_id' => $cv->profession_id,
				'country_name' => $cv->country->name,
				'office_name' => $cv->office->name ?? '',
				'profession_name' => $cv->profession->name,
			];
		});
		$cv = $contract->cv();
		if (is_null($cv)) {
			$country_id = !is_null($request->country_id) ? $request->country_id : 'all';
			$office_id = !is_null($request->office_id) ? $request->office_id : 'all';
			$profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
			$cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
		} else {
			$country_id = $cv->country_id;
			$office_id = $cv->office_id;
			$profession_id = $cv->profession_id;
			$cv_id = !is_null($request->cv_id) ? $request->cv_id : 'all';
		}


		return view('services::contracts.edit', compact('contract', 'marketers', 'customers', 'cvs', 'cv', 'countries', 'offices', 'professions', 'country_id', 'office_id', 'profession_id', 'statuses'));
	}
}
