<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Main\Models\Office;
use App\Http\Controllers\Controller;
use Modules\Services\Models\Tafweed;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Customer;
use Modules\Accounting\Models\Voucher;
use Illuminate\Support\Facades\Session;
use Modules\ExternalOffice\Models\Country;
use Modules\ExternalOffice\Models\Profession;
use Modules\Services\Models\Marketer;

class TafweedController extends Controller
{

    public function index(Request $request)
    {
        $tafweeds = Tafweed::orderBy('created_at')->get();
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        $cvs = Cv::all();


        $first_tafweed = Tafweed::first();
        $from_date = is_null($request->from_date) ? (is_null($first_tafweed) ? date('Y-m-d') : $first_tafweed->created_at->format('Y-m-d')) : $request->from_date;
        $to_date = is_null($request->to_date) ? date('Y-m-d') : $request->to_date;
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $tafweeds = $tafweeds->whereBetween('created_at', [$from_date_time, $to_date_time]);

        $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
        if ($country_id != 'all') {

            $cvs = Cv::where('country_id', $country_id)->get();

            foreach ($cvs as $cv) {
                $cid = $cv->contract_id;
                $tafweeds =  $tafweeds->where('contract_id', $cid);
            }
        }


        $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
        if ($profession_id != 'all') {

            $cvs = Cv::where('profession_id', $profession_id)->get();

            foreach ($cvs as $cv) {
                $cid = $cv->contract_id;
                $tafweeds =  $tafweeds->where('contract_id', $cid);
            }
        }



        $gender = !is_null($request->gender) ? $request->gender : 'all';
        if ($gender != 'all') {

            $cvs = Cv::where('gender', $gender)->get('contract_id');

            foreach ($cvs as $cvs) {
                $cid = $cvs->contract_id;
                $kcid =  $tafweeds->contract_id;
                if ($cid = $kcid) {
                $tafweeds =  $tafweeds->where('contract_id', $cid);
                }
            }
        }

        return view('tafweeds.index', compact('tafweeds', 'from_date', 'to_date', 'gender', 'country_id', 'profession_id', 'countries',  'professions', 'cvs'));
    }

    public function print()
    {
        $tafweeds = Tafweed::orderBy('id', 'desc')->paginate(10);
        return view('tafweeds.print')->withtafweeds($tafweeds);
    }

    public function create()
    {
        $tafweed = Tafweed::all();
        $customers = Customer::all();
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        return view('tafweeds.create')->withtafweed($tafweed)->withcustomers($customers)->withcountries($countries)->withoffices($offices)->withprofessions($professions);
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            // 'identification_num' => 'required|max:255'
        ));

        $tafweed = new Tafweed;

        $tafweed->customer_id = $request->customer_id;
        $i = $request->customer_id;

        $customer = Customer::find($i);

        $tafweed->identification_num = $customer->id_number;
        $tafweed->addr = $customer->address;
        $tafweed->phone = $customer->phones;

        $tafweed->contract_id = $request->contract_id;


        $tafweed->visa = $request->visa;
        $tafweed->gender = $request->gender;
        $tafweed->salary = $request->salary;
        $tafweed->marketer = $request->marketer;
        $tafweed->comm = $request->comm;


        $tafweed->country_id = $request->country_id;
        $tafweed->office = $request->office;
        $tafweed->recruitment_cv_name = $request->recruitment_cv_name;
        $tafweed->recruitment_cv_passport = $request->recruitment_cv_passport;
        $tafweed->injaz_num = $request->injaz_num;
        $tafweed->injaz_cost = $request->injaz_cost;
        $tafweed->contract_num = $request->contract_num;
        $tafweed->attach = $request->attach;
        $tafweed->notes = $request->notes;

        $tafweed->save();

        $id =   $tafweed->id;

        if ($tafweed) {
            $tafweed->attach();
        }

        Session::flash('success', 'تم الحفظ  ');

        return redirect()->route('tafweed.index');
    }


    public function show($id)
    {
        $tafweed = Tafweed::find($id);
        $vid = $tafweed->customer_id;
        $voucher = Voucher::where('voucherable_id', $vid)->get();
        return view('tafweeds.show')->withtafweed($tafweed)->withvoucher($voucher);
    }

    public function edit($id)
    {
        $tafweed = Tafweed::find($id);
        $customers = Customer::all();
        $countries = Country::all();
        $offices = Office::all();
        $professions = Profession::all();
        $marketers = Marketer::all();
        return view('tafweeds.edit')->withtafweed($tafweed)->withcustomers($customers)->withcountries($countries)->withoffices($offices)->withprofessions($professions)->withmarketers($marketers);
    }

    public function update(Request $request, $id)
    {
        $tafweed = Tafweed::find($id);

        if ($request->input('contract_date') == $tafweed->contract_date) {
            $this->validate($request, array(
                //  'contract_date'         => 'required|max:255'
            ));
        } else {
            $this->validate($request, array(
                // 'contract_date'         => 'required|max:255'
            ));
        }

        // Save the data to the database
        $tafweed = Tafweed::find($id);

        $tafweed->contract_id = $request->input('contract_id');
        $tafweed->customer_id = $request->input('customer_id');

        $i = $request->input('customer_id');
        $customer = Customer::find($i);
        $tafweed->identification_num = $customer->id_number;
        $tafweed->addr = $customer->address;
        $tafweed->phone = $customer->phones;


        $tafweed->visa = $request->input('visa');
        $tafweed->phone = $request->input('phone');
        $tafweed->gender = $request->input('gender');
        $tafweed->addr = $request->input('addr');
        $tafweed->salary = $request->input('salary');
        $tafweed->marketer = $request->input('marketer');
        $tafweed->comm = $request->input('comm');
        $tafweed->identification_num = $request->input('identification_num');
        $tafweed->country_id = $request->input('country_id');
        $tafweed->office = $request->input('office');
        $tafweed->recruitment_cv_name = $request->input('recruitment_cv_name');
        $tafweed->recruitment_cv_passport = $request->input('recruitment_cv_passport');
        $tafweed->injaz_num = $request->input('injaz_num');
        $tafweed->injaz_cost = $request->input('injaz_cost');
        $tafweed->contract_num = $request->input('contract_num');
        $tafweed->attach = $request->input('attach');
        $tafweed->notes = $request->input('notes');

        $tafweed->save();

        if ($tafweed) {
            $tafweed->attach();
        }
        Session::flash('success', 'تم التعديل ');

        return redirect()->route('tafweed.index');
    }

    public function destroy($id)
    {

        $tafweed = Tafweed::find($id);

        $tafweed->delete();

        Session::flash('success', 'تم الحذف');

        return redirect()->route('tafweed.index');
    }
}
