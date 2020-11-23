<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Voucher;
class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        return view('home');
    }
    
    
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Voucher  $voucher
    * @return \Illuminate\Http\Request
    */
    public function voucher(Voucher $voucher)
    {
        return view('vouchers.show', compact('voucher'));
    }
    
    public function lines()
    {
        if(auth()->user()->hasPermission('phones-read')){
            $telephoneBook = \Modules\Employee\Models\Employee::whereNotNull('line')->orderBy('department_id')->get();
        }else{
            $telephoneBook = \Modules\Employee\Models\Employee::where('public_line', 1)->whereNotNull('line')->orderBy('department_id')->get();
        }
        return view('lines', compact('telephoneBook'));
    }
    
    // Notifcation Component 
    public function notification(Request $request) {
        $user = $request->user();
        
        $total = $user->unreadNotifications->count();

        return compact('total');
    }
}
