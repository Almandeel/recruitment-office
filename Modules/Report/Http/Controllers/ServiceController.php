<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ExternalOffice\Models\Cv;
use Illuminate\Routing\Controller;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\ExternalOffice\Models\Country;
use Modules\ExternalOffice\Models\Profession;

class ServiceController extends Controller
{
    public function index()
    {
        return view('report::services.index');
    }

    public function contracts(Request $request)
    {

        if ($request->type == 'profession') {
            $profession = Profession::where('name', 'like', '%' . $request->profession . '%')->first();
            $contracts = $profession ? Contract::where('profession_id', $profession->id)->get() : [];
        }

        if ($request->type == 'country') {
            $country = Country::where('name', 'like', '%' . $request->country . '%')->first();
            $contracts = $country ? Contract::where('country_id', $country->id)->get() : [];
        }

        if ($request->type == '*' || !$request->type) {
            $contracts = Contract::all();
        }

        return view('report::services.contract', compact('contracts'));
    }

    public function cvs(Request $request)
    {
        if ($request->type == 'profession') {
            $profession = Profession::where('name', 'like', '%' . $request->profession . '%')->first();
            $cvs = $profession ? Cv::where('profession_id', $profession->id)->get() : [];
        }

        if ($request->type == 'country') {
            $country = Country::where('name', 'like', '%' . $request->country . '%')->first();
            $cvs = $country ? Cv::where('country_id', $country->id)->get() : [];
        }

        if ($request->type == '*' || !$request->type) {
            $cvs = Cv::all();
        }
        return view('report::services.cv', compact('cvs'));
    }

    public function customers()
    {
        $customers = Customer::all();
        return view('report::services.customer', compact('customers'));
    }
}
