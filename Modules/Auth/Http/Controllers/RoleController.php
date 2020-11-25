<?php

namespace Modules\Auth\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::mainOfficeRoles()->get();
        return view('auth::roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::enternal();
        $modules = [
            'employee'      => ['attendance', 'vacations', 'departments', 'employees', 'lines', 'phones', 'vacations', 'positions', 'custodies'],
            'accounting'    => ['accounts', 'centers', 'entries', 'expenses', 'salaries', 'transfers', 'vouchers', 'years', 'transactions', 'safes', 'statements'],
            'user'         => ['logs', 'notes', 'permissions', 'roles', 'suggestions', 'tasks', 'users', 'sms'],
            'service'      => ['complaints', 'contracts', 'customers', 'cv', 'marketers', 'services', 'flights', 'delegations', 'bails'],
            'warehouse'     => ['warehouses', 'warehousecvs'],
            'tutorial'      => ['categories', 'tutorials'],
            'mails'          => ['letters', 'mail'],
            'office'       => ['countries', 'offices', 'professions', 'advances', 'bills', 'returns', 'pulls'],
        ];
        return view('auth::roles.create', compact('permissions', 'modules'));
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
            'name' => 'required | unique:roles,name'
        ]);
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->attachPermissions($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');


        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('roles.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('auth::roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if($role->id == 1){
            return back()->with('error', 'لا يمكنك تعديل الدور الرئيسي');
        }
        $permissions = Permission::enternal();
        $modules = [
            'employee'      => ['attendance', 'vacations', 'departments', 'employees', 'lines', 'phones', 'vacations', 'positions', 'custodies'],
            'accounting'    => ['accounts', 'centers', 'entries', 'expenses', 'salaries', 'transfers', 'vouchers', 'years', 'transactions', 'safes', 'statements'],
            'user'         => ['logs', 'notes', 'permissions', 'roles', 'suggestions', 'tasks', 'users', 'sms'],
            'service'      => ['complaints', 'contracts', 'customers', 'cv', 'marketers', 'services', 'flights', 'delegations', 'bails'],
            'warehouse'     => ['warehouses', 'warehousecvs'],
            'tutorial'      => ['categories', 'tutorials'],
            'mails'          => ['letters', 'mail'],
            'office'       => ['countries', 'offices', 'professions', 'advances', 'bills', 'returns', 'pulls'],
        ];
        return view('auth::roles.edit', compact('permissions', 'role', 'modules'));
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
        $request->validate([
            'name' => ['required', Rule::unique('roles', 'name')->ignore($role)]
        ]);

        if($role->id == 1){
            return back()->with('error', 'لا يمكنك تعديل الدور الرئيسي');
        }
        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        session()->flash('success', 'تمت العملية بنجاح');

        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('roles.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->id == 1){
            return back()->with('error', 'لا يمكنك حذف الدور الرئيسي');
        }
        $role->delete();

        session()->flash('success', 'تمت العملية بنجاح');

        return redirect()->route('roles.index');

    }
}
