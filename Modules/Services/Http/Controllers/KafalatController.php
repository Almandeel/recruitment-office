<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Main\Models\Office;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Services\Models\Kafalat;
use Modules\ExternalOffice\Models\Cv;
use Modules\Services\Models\Contract;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Marketer;
use Modules\Accounting\Models\Voucher;
use Illuminate\Support\Facades\Session;
use Modules\ExternalOffice\Models\Country;
use Modules\ExternalOffice\Models\Profession;


class KafalatController extends Controller
{

    public function index(Request $request)
    {
        $kafalats = Kafalat::orderBy('id', 'desc')->get();
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        $cvs = Cv::all();


        $first_kafalat = Kafalat::first();
        $from_date = is_null($request->from_date) ? (is_null($first_kafalat) ? date('Y-m-d') : $first_kafalat->created_at->format('Y-m-d')) : $request->from_date;
        $to_date = is_null($request->to_date) ? date('Y-m-d') : $request->to_date;
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $kafalats = $kafalats->whereBetween('created_at', [$from_date_time, $to_date_time]);

        $country_id = !is_null($request->country_id) ? $request->country_id : 'all';
        if ($country_id != 'all') {

            $cvs = Cv::where('country_id', $country_id)->get();

            foreach ($cvs as $cv) {
                $cid = $cv->contract_id;
                $kafalats =  $kafalats->where('contract_id', $cid);
            }
        }


        $profession_id = !is_null($request->profession_id) ? $request->profession_id : 'all';
        if ($profession_id != 'all') {

            $cvs = Cv::where('profession_id', $profession_id)->get();

            foreach ($cvs as $cv) {
                $cid = $cv->contract_id;
                $kafalats =  $kafalats->where('contract_id', $cid);
            }
        }


        $office_id = !is_null($request->office_id) ? $request->office_id : 'all';
        if ($office_id != 'all') {
            $kafalats =  $kafalats->where('office_id', $office_id);
        }

        $gender = !is_null($request->gender) ? $request->gender : 'all';
        if ($gender != 'all') {

            $cvs = Cv::where('gender', $gender)->get('contract_id');

            foreach ($cvs as $cvs) {
                $cid = $cvs->contract_id;
                //$kcid =  $Kafalats->contract_id;
                //if ($cid = $kcid) {
                $kafalats =  $kafalats->where('contract_id', $cid);
                // }
            }
        }


        return view('services::kafalats.index', compact('kafalats', 'from_date', 'to_date', 'gender', 'office_id', 'country_id', 'profession_id', 'countries', 'offices', 'professions', 'cvs'));
    }

    public function create()
    {
        $kafalat = Kafalat::all();
        $customers = Customer::all();
        $countries = Country::all();
        $contracts = Contract::all();
        $professions = Profession::all();
        $offices = Office::all();
        $marketers = Marketer::all();

        //$users = DB::table('contracts')->select('id','customer_id')->groupBy('customer_id')->get();

        $users =  Contract::distinct()->get(['customer_id']);

        return view('services::kafalats.create')->withkafalat($kafalat)->withcustomers($customers)->withcountries($countries)->withcontracts($contracts)->withprofessions($professions)->withoffices($offices)->withmarketers($marketers)->withusers($users);
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            // 'trial_date' => 'required|max:255' ,
        ));


        $kafalat = new Kafalat;

        if ($request->s_customer_id === 'create') { {
                $request->s_customer_id = 1;
                $customer = Customer::firstOrCreate([
                    'name' => $request->name,
                    'id_number' => $request->id_number,
                    'phones' => $request->phone,
                    'address' => $request->addr,
                ]);
            }

            $request->s_customer_id  = $customer->id;

            $kafalat->s_customer_id = $request->s_customer_id;
        }

        $kafalat->s_customer_id = $request->s_customer_id;
        $i = $request->s_customer_id;

        $customer = Customer::find($i);

        if (!empty($customer)) {
            $kafalat->id_number = $customer->id_number;
            $kafalat->addr = $customer->address;
            $kafalat->phone = $customer->phones;
        }


        $cid = $kafalat->contract_id  = $request->contract_id;
        $contract = Contract::find($cid);
        if (!empty($contract)) {
            $kafalat->f_customer_id = $contract->customer_id;
        }



        $c = DB::table('contract_cv')->where('contract_id', $cid)->first();
        $cv_id = $c->cv_id;
        $cv = Cv::find($cv_id);
        if (!empty($contract)) {
            $kafalat->recruitment_cv_name = $cv->name;
            $kafalat->recruitment_cv_passport = $cv->passport;
            $kafalat->office_id = $cv->office_id;
            $kafalat->job = $cv->profession_id;
        } else {
            $kafalat->recruitment_cv_name = null;
            $kafalat->recruitment_cv_passport = null;
            $kafalat->office_id = null;
            $kafalat->job = null;
        }

        $kafalat->trial_date = $request->trial_date;
        $kafalat->rem_trial = $request->rem_trial;

        $kafalat->marketer = $request->marketer;
        $kafalat->comm = $request->comm;
        $kafalat->transfer_date = $request->transfer_date;
        $kafalat->status = 1;
        $kafalat->notes = $request->notes;
        $kafalat->attach = $request->attach;


        $kafalat->save();

        if ($kafalat) {
            $kafalat->attach();
        }

        $id =   $kafalat->id;
        $contract_id =   $kafalat->contract_id;


        DB::update('update recruitment_contracts set   status = ? where id = ?', [5, $contract_id]);

        Session::flash('success', 'تم الحفظ  ');

        return redirect()->route('kafalat.index');
    }


    public function kcontract($id)
    {
        $contract = Contract::find($id);
        $kafalat = Kafalat::all();
        $customers = Customer::all();
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        $marketers = Marketer::all();
        return view('services::kafalats.kcontract')->withkafalat($kafalat)->withcontract($contract)->withcustomers($customers)->withcountries($countries)->withprofessions($professions)->withoffices($offices)->withmarketers($marketers);
    }

    public function show($id)
    {
        $kafalat = Kafalat::find($id);
        $cid = $kafalat->contract_id;
        $vid = $kafalat->f_customer_id;
        $contract = Contract::find($cid);
        $c = DB::table('contract_cv')->where('contract_id', $cid)->first();
        $cv_id = $c->cv_id;
        $cv = Cv::find($cv_id);
        $voucher = Voucher::where('voucherable_id', $vid)->get();
        return view('services::kafalats.show')->withkafalat($kafalat)->withcontract($contract)->withcv($cv)->withvoucher($voucher);
    }

    public function edit($id)
    {
        $kafalat = Kafalat::find($id);
        $cid = $kafalat->contract_id;
        $contract = Contract::find($cid);
        $customers = Customer::all();
        $countries = Country::all();
        $professions = Profession::all();
        $offices = Office::all();
        return view('services::kafalats.edit')->withkafalat($kafalat)->withcustomers($customers)->withcountries($countries)->withcontract($contract)->withprofessions($professions)->withoffices($offices);
    }

    public function update(Request $request, $id)
    {
        $kafalat = Kafalat::find($id);

        if ($request->input('trial_date') == $kafalat->name) {
            $this->validate($request, array(
                // 'trial_date'         => 'required|max:255'
            ));
        } else {
            $this->validate($request, array(
                //   'trial_date'         => 'required|max:255'
            ));
        }

        // Save the data to the database
        $kafalat = Kafalat::find($id);

        $kafalat->trial_date = $request->input('trial_date');
        $kafalat->f_customer_id = $request->input('f_customer_id');


        $kafalat->s_customer_id = $request->input('s_customer_id');
        $i = $request->input('s_customer_id');
        $customer = Customer::find($i);
        $kafalat->id_number = $customer->id_number;
        $kafalat->addr = $customer->address;
        $kafalat->phone = $customer->phones;

        $cid = $kafalat->contract_id  = $request->input('contract_id');
        $contract = Contract::find($cid);
        $kafalat->f_customer_id = $contract->customer_id;


        $cvid = $contract->id;
        $cv = Cv::where('contract_id', $cid)->first();
        $kafalat->recruitment_cv_name = $cv->name;
        $kafalat->recruitment_cv_passport = $cv->passport;
        $kafalat->office_id = $cv->office_id;
        $kafalat->job = $cv->profession_id;

        $kafalat->rem_trial = $request->input('rem_trial');
        $kafalat->name = $request->input('name');
        $kafalat->comm = $request->input('comm');
        $kafalat->transfer_date = $request->input('transfer_date');
        $kafalat->status = 1;
        $kafalat->notes = $request->input('notes');
        $kafalat->attach = $request->input('attach');


        $kafalat->save();

        $id =   $kafalat->id;
        $contract_id =   $kafalat->contract_id;

        if ($kafalat) {
            $kafalat->attach();
        }


        DB::update('update recruitment_contracts set   status = ? where id = ?', [5, $contract_id]);

        Session::flash('success', 'تم التعديل ');

        return redirect()->route('kafalat.index');
    }

    public function destroy($id)
    {

        $kafalat = Kafalat::find($id);

        $kafalat->delete();

        Session::flash('success', 'تم الحذف');

        return redirect()->route('kafalat.index');
    }


    public function approve(Request $request)
    {
        // find the New in the database and save as a var
        $kafalat = Kafalat::find($request->id);
        $approveVal  = $request->status;

        if ($approveVal == 'on') {
            $approveVal = 2;
        } else {
            $approveVal = 1;
        }

        $kafalat->status = $approveVal;
        $kafalat->save();

        return back();
    }
}
