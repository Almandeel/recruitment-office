<?php

namespace Modules\ExternalOffice\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:roles-create')->only(['create', 'store']);
        $this->middleware('permission:roles-read')->only(['index', 'show']);
        $this->middleware('permission:roles-update')->only(['edit', 'update']);
        $this->middleware('permission:roles-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::officeRoles()->get();
        return view('externaloffice::roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::external();
        return view('externaloffice::roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string',
            'permissions' => 'nullable',
            'permissions.*.*' => 'nullable | numeric',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'office_id' => auth()->guard('office')->user()->office_id
        ]);

        $role->attachPermissions($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');

        return redirect()->route('office.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('externaloffice::roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if ($role->id == 1) {
            return back()->with('error', 'لا يمكنك تعديل الدور الرئيسي');
        }

        $permissions = Permission::external();

        return view('externaloffice::roles.edit', compact('permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if ($role->id == 1) {
            return back()->with('error', 'لا يمكنك تعديل الدور الرئيسي');
        }

        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');

        return redirect()->route('office.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->id == 1) {
            return back()->with('error', 'لا يمكنك حذف الدور الرئيسي');
        }

        $role->delete();

        session()->flash('success', 'تمت العملية بنجاح');

        return redirect()->route('office.roles.index');
    }
}
