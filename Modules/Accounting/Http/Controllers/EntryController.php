<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Models\Currency;
use Modules\Accounting\Models\Year;
use Modules\Accounting\Models\Entry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountEntry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('year.activated')->only(['index', 'create','store']);
        $this->middleware('year.opened')->only(['create','store']);
        
        $this->middleware('permission:entries-create')->only(['create','store']);
        $this->middleware('permission:entries-read')->only(['index', 'show']);
        $this->middleware('permission:entries-update')->only(['edit', 'update']);
        $this->middleware('permission:entries-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $accounts = Account::getAll();
        $accounts_ids = $accounts->pluck('id')->toArray();
        
        $types = Entry::TYPES;
        
        $entries = year()->entries;
        $entries = Entry::where('year_id', year()->id);
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        
        $entries = $entries->whereBetween('created_at', [$fromDate, $toDate]);
        $type = $request->type ? $request->type : "all";
        $account_id = $request->account_id ? $request->account_id : "all";
        if($type !== 'all'){
            $entries->where('type', $type);
        }
        $entries = $entries->orderBy('created_at', 'DESC')->get();
        if($account_id != 'all'){
            $entries = $entries->filter(function($entry) use ($account_id){
                return $entry->accounts->where('id', $account_id)->count();
            });
        }
        $entries = $entries->unique('id');
        // $rows_per_page = 10;
        // $pages = ceil($entries->count() / $rows_per_page);
        return view('accounting::entries.index', compact('entries', 'types', 'type', 'accounts', 'account_id', 'from_date', 'to_date'));
    }
    
    public function confirm(Request $request){
        if($request->has('id') && $request->has('type')){
            $id = $request->id;
            $type = $request->type;
            $entryable = $type::findOrFail($id);
            $succeeded = false;
            $entry = $entryable->entryConfirm($request);
            $succeeded = !is_null($entry);
            if($succeeded){
                return back()->withSuccess(__('global.confirm_success'));
            }
            
            return back()->withError(__('global.confirm_fail'));
        }
    }
    
    public function create()
    {
        $types = Entry::TYPES;
        $roots = roots(true);
        $roots = Account::getAll();
        // $entry =
        // dd(year()->generateEntryId());
        return view('accounting::entries.create', compact('roots', 'types'));
    }
    
    public function show(Entry $entry)
    {
        // dd($entry);
        return view('accounting::entries/show', compact('entry'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->operation){
            if($request->has('ajax')){
                if($request->operation == 'adverse' && $request->has('entry_id')){
                    $details = str_replace('__entry_id__', $request->entry_id, __('accounting::entries.details_adverse'));
                    $msg = str_replace('__entry_id__', $request->entry_id, __('accounting::entries.adverse_success'));
                    session()->flash('success', $msg);
                    $entry = Entry::findOrFail($request->entry_id);
                    $adverse = $entry->adverse(true);
                    return response()->json(['status'=> 201], 200);
                }
            }
            elseif($request->operation == 'safeable'){
                request()->validate([
                'safe_id' => 'required|numeric',
                'account_id' => 'required|numeric',
                'amount' => 'required|numeric|min:1',
                ]);
                $safeableType = $request->safeable_type;
                $safeableId = $request->safeable_id;
                $safeable = $safeableType::find($safeableId);
                $msg = __("accounting::safes.safeable_confirmed");
                $msg = str_replace('__safeable__', __('accounting::safes.safeables.' . $safeableType), $msg);
                $attributes = [];
                $attributes['entryable_type'] = $request->safeable_type;
                $attributes['entryable_id'] = $request->safeable_id;
                $attributes['amount'] = $request->amount;
                $attributes['details'] = $request->details;
                $entry = Entry::create($attributes);
                $safeable->entry()->save($entry);
                $from = null;
                $to = null;
                $amount = $request->amount;
                if($safeableType == 'Modules\Accounting\Models\Voucher'){
                    if($safeable->isReceipt()){
                        $from = $request->safe_id;
                        $to = $request->account_id;
                    }else{
                        $from = $request->account_id;
                        $to = $request->safe_id;
                    }
                }
                else if($safeableType == 'Modules\Accounting\Models\Expense'){
                    $from = $request->account_id;
                    $to = $request->safe_id;
                }
                
                $entry->accounts()->attach($from, [
                'amount' => $amount,
                'side' => Entry::SIDE_DEBTS,
                
                ]);
                $entry->accounts()->attach($to, [
                'amount' => $amount,
                'side' => Entry::SIDE_CREDITS,
                
                ]);
                
                return back()->withSuccess($msg);
            }
        }
        request()->validate([
        'entry_date' => 'required|date',
        'amount' => 'required|numeric|min:1',
        'details' => 'required|string',
        ]);
        $total_debts = array_sum($request->debts_amounts);
        $total_credits = array_sum($request->credits_amounts);
        $data = $request->except(['_token']);
        // dd($data);
        if($request->total_debts == 0){
            
            return back()->with('error', __("entries.amount_invalid"));
        }
        else{
            if($request->total_debts == $request->total_credits){
                if($request->total_debts == $request->amount){
                    $entry = Entry::create($data);
                    if($request->debts_accounts && $request->debts_amounts){
                        if(count($request->debts_accounts) == count($request->debts_amounts)){
                            for ($i=0; $i < count($request->debts_accounts); $i++) {
                                $account = $request->debts_accounts[$i];
                                $amount = $request->debts_amounts[$i];
                                $entry->accounts()->attach($account, [
                                'amount' => $amount,
                                'side' => Entry::SIDE_DEBTS,
                                
                                ]);
                            }
                        }
                    }
                    if($request->credits_accounts && $request->credits_amounts){
                        if(count($request->credits_accounts) == count($request->credits_amounts)){
                            for ($i=0; $i < count($request->credits_accounts); $i++) {
                                $account = $request->credits_accounts[$i];
                                $amount = $request->credits_amounts[$i];
                                $entry->accounts()->attach($account, [
                                'amount' => $amount,
                                'side' => Entry::SIDE_CREDITS,
                                
                                ]);
                            }
                        }
                    }
                    
                    if($request->next == 'save_new'){
                        return redirect()->route('entries.create')->with('success', __('accounting::entries.create_success'));
                    }
                    else if($request->next == 'save_list'){
                        return redirect()->route('entries.index')->with('success', __('accounting::entries.create_success'));
                    }
                    else if($request->next == 'save_show'){
                        return redirect()->route('entries.show', $entry)->with('success', __('accounting::entries.create_success'));
                    }
                }else{
                    
                    return back()->with('error', __("entries.debt_credit_mismatch"));
                }
            }
            else{
                
                return back()->with('error', __("entries.amount_mismatch"));
            }
        }
        return back()->with('error', __('accounting::entries.create_fail'));
    }
    
    public function edit(Entry $entry)
    {
        
        // $entry = Entry::find($entry_id);
        $currencies = Currency::all();
        return view('accounting::entries/edit', compact('currencies', 'entry'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Entry $entry)
    {
        request()->validate([
        // 'name' => 'required | string | min:5 | max:40',
        ]);
        $data = $request->except(['_token', '_method', 'next']);
        // dd($data, $entry);
        $entry->update($data);
        if($request->next == 'save_edit'){
            return redirect()->route('entries.edit', $entry)->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_list'){
            return redirect()->route('entries.index')->with('success', __('accounting::global.update_success'));
        }
        else if($request->next == 'save_show'){
            return redirect()->route('entries.show', $entry)->with('success', __('accounting::global.update_success'));
        }
        
        return back()->with('error', __('accounting::global.update_fail'));
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function destroy(Entry $entry)
    {
        $entry->delete();
        
        return redirect()->route('entries.index')->with('success', __('accounting::global.delete_success'));
    }
}