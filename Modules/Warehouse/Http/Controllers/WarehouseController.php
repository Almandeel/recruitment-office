<?php

namespace Modules\Warehouse\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ExternalOffice\Models\Cv;
use Modules\Warehouse\Models\Warehouse;
use Modules\Warehouse\Models\WarehouseCv;
use Modules\Warehouse\Models\WarehouseUser;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:warehouses-create')->only(['create', 'store']);
        $this->middleware('permission:warehouses-read')->only(['index', 'show']);
        $this->middleware('permission:warehouses-update')->only(['edit', 'update']);
        $this->middleware('permission:warehouses-delete')->only('destroy');
    }
    public function dashboard()
    {
        $warehousesCount = Warehouse::count();
        $warehouseCvCount = WarehouseCv::count();
        $warehouseUserCount = WarehouseUser::count();
        
        return view('warehouse::dashboard', compact('warehousesCount', 'warehouseCvCount', 'warehouseUserCount'));
    }
    /**
    * Display a listing of the resource.
    * @return Response
    */
    public function index()
    {
        $warehouses = auth()->user()->warehouses;
        return view('warehouse::index', compact('warehouses'));
    }
    
    /**
    * Show the form for creating a new resource.
    * @return Response
    */
    public function create()
    {
        
        return view('warehouse::create');
    }
    
    /**
    * Store a newly created resource in storage.
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $request->validate([
        'name'      => 'required | string',
        'phone'      => 'required | string',
        'address'      => 'required | string',
        ]);
        // dd(auth()->user(), auth()->user()->getKey());
        $warehouse = Warehouse::create($request->all());
        
        $warehouse->users()->attach([auth()->user()->getKey()]);
        // $users = WarehouseUser::create(
        //     [
        //         'warehouse_id' => $warehouse->id,
        //         'user_id' => auth()->user()->getKey(),
        //     ]
        // );
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Show the specified resource.
    * @param int $id
    * @return Response
    */
    public function show(Warehouse $warehouse)
    {
        $warehouses_cvs = WarehouseCv::get()->pluck('cv_id')->toArray();
        
        $cvs = Cv::whereIn('status', [Cv::STATUS_ACCEPTED, Cv::STATUS_CONTRACTED])->get()->filter(function ($cv) use ($warehouses_cvs) {
            return in_array($cv->id, $warehouses_cvs) == false;
        });
        
        $users = User::all()->filter(function($user) use($warehouse){
            return !in_array($user->id, $warehouse->users->pluck('id')->toArray());
        });
        
        return view('warehouse::show', compact('warehouse', 'users', 'cvs'));
    }
    
    /**
    * Show the form for editing the specified resource.
    * @param int $id
    * @return Response
    */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouse::edit');
    }
    
    /**
    * Update the specified resource in storage.
    * @param Request $request
    * @param int $id
    * @return Response
    */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
        'name'      => 'required | string',
        'phone'      => 'required | string',
        'address'      => 'required | string',
        ]);
        
        $warehouse->update($request->all());
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    * @param int $id
    * @return Response
    */
    public function destroy(Warehouse $warehouse)
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        $warehouse->delete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    public function storeUser(Request $request)
    {
        
        $request->validate([
        'user_id' => 'required',
        'warehouse_id' => 'required',
        ]);
        
        WarehouseUser::create($request->all());
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    public function destroyUser(Request $request, $id)
    {
        $user = WarehouseUser::find($id);
        
        if ($user) {
            $user->delete();
            return back()->with('success', 'تمت العملية بنجاح');
        } else {
            return back()->with('error', 'المستخدم غير موجود');
        }
    }
}