<?php

namespace Modules\Main\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Modules\Main\Models\Office;
use Modules\ExternalOffice\Models\{Country, Profession, Advance, Bill};
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Accounting\Models\{Voucher};

class OfficeController extends Controller
{
    public function __construct()
    {
    }
    
    public function dashboard()
    {
        return view('main::offices.dashboard');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $offices = Office::all();
        return view('main::offices.index', compact('offices'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $countries = Country::all();
        return view('main::offices.create', compact('countries'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param  Request  $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        "office_name" => "required | string | max:45",
        "office_email" => "required | email | max:45 | unique:offices,email",
        "office_phone" => "required | numeric",
        "office_status" => "required | numeric | max:2",
        "office_country_id" => "required | numeric | exists:countries,id",
        "name" => "required | string | max:45",
        "username" => "required | string | max:45 | unique:office_users,username",
        "password" => "required | confirmed | string",
        "phone" => "required | numeric",
        "status" => "required | numeric | max:2",
        ]);
        
        $office = Office::create([
        'name' => $request->office_name,
        'status' => $request->office_status,
        'country_id' => $request->office_country_id,
        'email' => $request->office_email,
        'phone' => $request->office_phone,
        ]);
        
        $user = $office->admin()->create([
        'name' => $request->name,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'status' => $request->status,
        'office_id' => $office->id,
        ]);
        
        $user->attachRole(Role::where('name', 'super')->first()->id);
        
        $office->update(['admin_id' => $user->id]);
        
        return redirect()->route('offices.index')->with('success', __('global.operation_success'));
    }
    
    /**
    * Show the specified resource.
    * @param  Office  $office
    * @return Response
    */
    public function show(Request $request, Office $office)
    {
        $active_tab = $request->has('active_tab') ? $request->active_tab : 'details';
        $from_date = $request->from_date ? $request->from_date : date("Y-m-d");
        $to_date = $request->to_date ? $request->to_date : date("Y-m-d");
        // dd($office->receipts()->toArray(), $office->payments()->toArray());
        $tabs = [
            'details' => 'البيانات',
            'contracts' => 'العقود',
            'cvs' => 'السير الذاتيه',
            'complaints' => 'الشكاوي',
            'bills' => 'الفواتير',
            'advances' => 'السلفيات',
            'returns' => 'المرتجعات',
            'pulls' => 'المسحوبات',
            'vouchers' => 'السندات',
        ];
        
        $title = 'مكتب : ' . $office->name;
        $title .= ' /' . $tabs[$active_tab];
        $compact = compact('office', 'active_tab', 'from_date', 'to_date', 'title');
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        if ($active_tab == 'details') {
            $compact['title'] = $title;
        } elseif ($active_tab == 'contracts') {
            $professions = Profession::all();
            $profession_id = $request->profession_id ? $request->profession_id : 'all';
            $contracts = $office->contracts;
            $contracts = $contracts->whereBetween('created_at', [$from_date_time, $to_date_time]);
            if ($profession_id != 'all') {
                $contracts = $contracts->filter(function ($contract) use ($profession_id) {
                    return $contract->cv()->profession_id == $profession_id;
                });
            }
            $compact['contracts'] = $contracts;
            $compact['professions'] = $professions;
            $compact['profession_id'] = $profession_id;
        } elseif ($active_tab == 'cvs') {
            $professions = Profession::all();
            $profession_id = $request->profession_id ? $request->profession_id : 'all';
            $type = $request->type ? $request->type : 'all';
            $cvs = $office->cvs;
            $cvs = $cvs->whereBetween('created_at', [$from_date_time, $to_date_time]);
            
            if ($profession_id != 'all') {
                $cvs = $cvs->where('profession_id', $profession_id);
            }
            
            if ($type == 'waiting') {
                $cvs = $cvs->whereNull('contract_id');
            } elseif ($type == 'contracted') {
                $cvs = $cvs->whereNotNull('contract_id');
            }
            
            $compact['cvs'] = $cvs;
            $compact['professions'] = $professions;
            $compact['profession_id'] = $profession_id;
            $compact['type'] = $type;
        } elseif ($active_tab == 'advances') {
            $advances = $office->advances;
            $advances = $advances->whereBetween('created_at', [$from_date_time, $to_date_time]);
            $compact['advances'] = $advances;
        } elseif ($active_tab == 'bills') {
            $bills = $office->bills;
            $bills = $bills->whereBetween('created_at', [$from_date_time, $to_date_time]);
            $status = $request->has('status') ? $request->status : 'waiting';
            if($status != 'all'){
                $bills = $bills->filter(function($bill) use ($status){
                    return $bill->statusIs($status);
                });
            }
            
            $compact['status'] = $status;
            $compact['bills'] = $bills;
        } elseif ($active_tab == 'returns') {
            $returns = $office->returns;
            $returns = $returns->whereBetween('created_at', [$from_date_time, $to_date_time]);
            $compact['returns'] = $returns;
        } elseif ($active_tab == 'pulls') {
            $pulls = $office->cvs()->with('pull');
            $pulls = $pulls->whereBetween('created_at', [$from_date_time, $to_date_time]);
            $compact['pulls'] = $pulls;
        }
        
        return view('main::offices.show', $compact);
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param  Office  $office
    * @return Response
    */
    public function edit(Office $office)
    {
        $countries = Country::all();
        return view('main::offices.edit', compact('countries', 'office'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param  Request  $request
    * @param  Office  $office
    * @return Response
    */
    public function update(Request $request, Office $office)
    {
        $request->validate([
        "office_name" => "required | string | max:45",
        "office_email" => ["required",  "email", "max:45", Rule::unique('offices', 'email')->ignore($office->id)],
        "office_phone" => "required | numeric",
        "office_status" => "required | numeric | max:2",
        "office_country_id" => "required | numeric | exists:countries,id",
        'office_admin_id' => 'nullable | numeric | exists:office_users,id',
        "name" => "nullable | string | max:45",
        "username" => "nullable | string | max:45 | unique:office_users,username, {$office->admin->id}",
        "password" => "nullable | confirmed | string",
        "phone" => "nullable | numeric",
        "status" => "nullable | numeric | max:2",
        ]);
        
        $office->update([
        'name' => $request->office_name,
        'status' => $request->office_status,
        'country_id' => $request->office_country_id,
        'email' => $request->office_email,
        'phone' => $request->office_phone,
        'admin_id' => $request->office_admin_id ?? $office->admin->id
        ]);

        if($request->password) {
            $user = $office->admin()->update([
                'name'      => $request->name,
                'username'  => $request->username,
                'phone'     => $request->phone,
                'password' => Hash::make($request->password),
            ]);
        }else {
            $user = $office->admin()->update([
                'name'      => $request->name,
                'username'  => $request->username,
                'phone'     => $request->phone,
            ]);
        }
        
        // if ($request->filled(['name', 'username', 'password', 'phone'])) {
        //     $user = $office->admin()->create([
        //     'name' => $request->name,
        //     'username' => $request->username,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone,
        //     'status' => $request->status,
        //     'office_id' => $office->id,
        //     ]);
            
        //     $office->update(['admin_id' => $user->id]);
        // }
        
        return redirect()->route('offices.index')->with('success', __('global.operation_success'));
    }
    
    /**
    * Disable/Activate the specified resource from storage.
    * @param  Office  $office
    * @return Response
    */
    public function destroy(Office $office)
    {
        $office->update(['status' => !$office->status]);
        
        return back()->with('success', 'تمت العملية بنجاح');
        return redirect()->route('offices.index')->with('success', __('global.operation_success'));
    }
    
    public function showBill($id)
    {
        $bill = Bill::find($id);
        $advances = Advance::where('office_id', $bill->office_id)->whereNotNull('voucher_id')->get();
        $advances = $advances->filter(function ($advance) {
            if(!is_null($advance->voucher())){
                return $advance->voucher()->isChecked() && !$advance->isPayed();
            }
            return false;
        });
        // dd($advances);
        return view('main::offices.bill', compact('bill', 'advances'));
    }
    
    public function showAdvance(Advance $advance)
    {
        // dd($advance->attachments);
        $voucher = $advance->voucher();
        $vouchers = $advance->vouchers->filter(function($v) use($voucher) { return $voucher->id != $v->id; });
        return view('main::offices.advance', compact('advance', 'vouchers', 'voucher'));
    }
    
    public function billVouchers(Request $request)
    {
        $bill = Bill::find($request->bill_id);
        $bill->addVoucher(Voucher::TYPE_PAYMENT, $request->amount);
        return back()->with('success', 'تمت العملية بنجاح');;
    }
}