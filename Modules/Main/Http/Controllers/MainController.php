<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Services\Models\Office;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\ExternalOffice\Models\Country;
use Modules\ExternalOffice\Models\Marketer;
use Modules\ExternalOffice\Models\Profession;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        $statuses = Contract::STATUSES;
        if(request()->profession_id == 'all' & request()->country_id == 'all') {
            $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->get()->map(function($cv) {
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
        }elseif(request()->profession_id != 'all' & request()->country_id == 'all'){
            $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->where('profession_id', request()->profession_id)->get()->map(function($cv) {
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
        }elseif(request()->profession_id == 'all' & request()->country_id != 'all') {
            $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->where('country_id', request()->country_id)->get()->map(function($cv) {
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
        }elseif(request()->profession_id != 'all' & request()->country_id != 'all') {
            $cvs = Cv::where('status', Cv::STATUS_ACCEPTED)->where('country_id', request()->country_id)->where('profession_id', request()->profession_id)->get()->map(function($cv) {
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
        };
        $office_id = $request->office_id ?? 'all';
        $profession_id = $request->profession_id ?? 'all';
        $country_id = $request->country_id ?? 'all';
        return view('main::index', compact('cvs','countries', 'offices', 'professions', 'country_id','office_id', 'profession_id', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('main::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('main::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('main::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
