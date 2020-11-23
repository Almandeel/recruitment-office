<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Accounting\Models\Center;
use Modules\Accounting\Models\Account;
use Carbon\Carbon;

class CenterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:centers-create')->only(['create','store']);
        $this->middleware('permission:centers-read')->only(['index', 'show']);
        $this->middleware('permission:centers-update')->only(['edit', 'update']);
        $this->middleware('permission:centers-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $costs = Center::costs();
        $profits = Center::profits();
        // dd($costs);
        return view('accounting::centers.index', compact('costs', 'profits'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        $accounts = roots();
        return view('accounting::centers.create', compact('accounts'));
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        
        $request->validate([
        'name' => 'required|string|max:45'
        ]);
        $center = Center::create($request->except('_token'));
        $tab = !isset($request->active_tab) ? ($center->isCost() ? 'costs' : 'profits') : $request->active_tab;
        
        return back()->with('success', __('accounting::centers.create_success'))->with('tab', $tab);
        
        
        // if($request->next == 'save_new'){
        //     return redirect()->route('centers.create')->with('success', __('accounting::centers.create_success'));
        // }
        // else if($request->next == 'save_list'){
        //     return redirect()->route('centers.index')->with('success', __('accounting::centers.create_success'));
        // }
        // else if($request->next == 'save_show'){
        //     return redirect()->route('centers.show', $center)->with('success', __('accounting::centers.create_success'));
        // }else{
        //     return back()->with('success', __('accounting::centers.create_success'))->with('tab', $tab);
        // }
    }
    
    /**
    * Show the specified resource.
    * @param Center $center
    * @return Response
    */
    public function show(Request $request, Center $center)
    {
        $accounts = Account::secondaryAccounts();
        $entries = $center->entries();
        $opening_balance[0] = $center->isCost() ? 'credit' : 'debt';
        $opening_balance[1] = 0;
        $opening_balance[2] = null;
        
        // if(count($entries)){
        //     $from_date = $request->from_date ? $request->from_date : $entries->first()->entry_date;
        //     $to_date = $request->to_date ? $request->to_date : $entries->last()->entry_date;
        
        //     $first_date = $entries->first()->entry_date;
        //     $last_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->subDays(1)->toDateTimeString()));
        //     $last_entries = $entries->whereBetween('entry_date', [$first_date, $last_date]);
        
        //     $entriesAmount = $last_entries->sum('pivot.amount');
        //     $opening_balance[1] = $entriesAmount;
        //     if(count($last_entries)) $opening_balance[2] = $last_entries->last()->entry_date;
        
        // }else{
        //     $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        //     $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        // }
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        
        $entries = $entries->whereBetween('entry_date', [$from_date, $to_date])->sortByDesc('entry_date');
        // dd($entries);
        return view('accounting::centers.show', compact('center', 'accounts', 'entries', 'from_date', 'to_date'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param Center $center
    * @return Response
    */
    public function edit(Center $center)
    {
        // $accounts = roots();
        // return view('accounting::centers.edit', compact('accounts', 'center'));
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param Center $center
    * @return Response
    */
    public function update(Request $request, Center $center)
    {
        if(isset($request->account_id)){
            if($request->operation == 'add'){
                if(!$center->accounts->contains($request->account_id)){
                    $center->accounts()->attach($request->account_id);
                }
                return back()->with('success', __('accounting::centers.add_account_success'));
            }
            else if($request->operation == 'remove'){
                if($center->accounts->contains($request->account_id)){
                    $center->accounts()->detach($request->account_id);
                }
                return back()->with('success', __('accounting::centers.remove_account_success'));
            }
        }
        $request->validate([
        'name' => 'required|string|max:45'
        ]);
        $tab = !isset($request->active_tab) ? ($center->isCost() ? 'costs' : 'profits') : $request->active_tab;
        
        $center->update($request->except('_token', '_method'));
        
        return back()->with('success', __('accounting::centers.update_success'))->with('tab', $tab);
        
        // if($request->next == 'save_edit'){
        //     return redirect()->route('accounts.edit', $account)->with('success', __('accounting::centers.update_success'));
        // }
        // else if($request->next == 'save_list'){
        //     return redirect()->route('accounts.index')->with('success', __('accounting::centers.update_success'));
        // }
        // else if($request->next == 'save_show'){
        //     return redirect()->route('accounts.show', $account)->with('success', __('accounting::centers.update_success'));
        // }else{
        //     return back()->with('success', __('accounting::centers.create_success'))->with('tab', $tab);
        // }
    }
    
    /**
    * Remove the specified resource from storage.
    * @param Center $center
    * @return Response
    */
    public function destroy(Center $center)
    {
        $tab = !isset($request->active_tab) ? ($center->isCost() ? 'costs' : 'profits') : $request->active_tab;
        
        $center->delete();
        
        return back()->with('success', __('accounting::centers.delete_success'))->with('tab', $tab);
        
    }
}