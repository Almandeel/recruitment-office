<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function change(Request $request){
        if($request->has('id') && $request->has('type')){
            $id = $request->id;
            $type = $request->type;
            $statusable = $type::findOrFail($id);
            if ($request->has('status')) {
                $succeeded = false;
                $msg = __('global.operation_success');
                if($request->status == 'approve'){
                    $msg = __('global.approve_success');
                    $succeeded = $statusable->approve();
                }
                else if($request->status == 'reject'){
                    $msg = __('global.reject_success');
                    $succeeded = $statusable->reject();
                }
                
                if($succeeded){
                    return back()->withSuccess($msg);
                }
                
                return back()->withError('فشلت العملية');
            }
            
            return back()->withError('لا يمكنك تغيير الحالة');
        }
    }
}