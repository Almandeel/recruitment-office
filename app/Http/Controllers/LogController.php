<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\{User, Log};
class LogController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        // dd(\Carbon\Carbon::now());
        $users = User::all();
        $users_ids = $users->pluck('id')->toArray();
        $oprs = Log::OPERATIONS;
        $from_date = $request->has('from_date') ? $request->from_date : date('Y-m-d');
        $to_date = $request->has('to_date') ? $request->to_date : date('Y-m-d');
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        $logs = Log::whereBetween('created_at', [$from_date_time, $to_date_time]);
        
        $operation = $request->has('operation') ? $request->operation : 'all';
        if($operation !== 'all'){
            $logs = $logs->where('operation', $request->operation);
        }
        
        $user_id = $request->has('user_id') ? $request->user_id : 'all';
        $user = $user_id != 'all' ? User::find($user_id) : null;
        if($user_id !== 'all'){
            $logs = $logs->where('user_id', $user_id);
        }
        $logs = $logs->get();
        $logs = ($operation == 'all' ? $logs->groupBy('operation') : $logs)->sortByDesc('created_at');
        $tabs = Log::OPERATIONS;
        return view('logs.index', compact('logs', 'from_date', 'to_date', 'users', 'user', 'user_id', 'operation', 'oprs', 'tabs'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->has('reset')){
            foreach (Log::all() as $log) {
                $log->delete();
            }
            
            return back()->with('success', __('global.reset_success'));
        }
        $request->validate([
        'name' => 'string|required',
        ]);
        $data = $request->except(['_token']);
        $log = Log::create($data);
        
        return back()->with('error', __('global.create_fail'));
    }
    
    /**
    * Display the specified resource.
    *
    * @param  Log $log
    * @return \Illuminate\Http\Response
    */
    public function show(Log $log)
    {
        return view('logs.show', compact('log'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  Log $log
    * @return \Illuminate\Http\Response
    */
    public function edit(Log $log)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  Log $log
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Log $log)
    {
        if($request->has('restore')){
            $log->restore();
            return back()->with('success', __('global.restore_success'));
        }
        $request->validate([
        'name' => 'string|required',
        ]);
        $data = $request->except(['_token']);
        $log->update($data);
        return back()->with('success', __('global.update_success'));
        
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  Log $log
    * @return \Illuminate\Http\Response
    */
    public function destroy(Log $log)
    {
        $log->delete();
        return back()->with('success', __('logs.delete_success'));
    }
}