<?php

namespace Modules\ExternalOffice\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\ExternalOffice\Models\User;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:users-create')->only(['create', 'store']);
        $this->middleware('permission:users-read')->only(['index', 'show']);
        $this->middleware('permission:users-update')->only(['edit', 'update']);
        $this->middleware('permission:users-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::inSameOffice()->get()->except(auth()->user()->getKey());

        return view('externaloffice::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::officeRoles()->get();

        return view('externaloffice::users.create', compact('roles'));
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
            'name' => 'required | string | max:100 | min:3 | unique:users',
            'username' => 'required | string | max:100 | min:3 |regex:/^[A-Za-z]+$/| unique:users',
            'password' => 'required | confirmed | string | min:6',
            'phone' => 'required | numeric',
            'status' => 'required | numeric',
        ]);

        $request_data = $request->except('password');
        $request_data['password'] = Hash::make($request->password);

        if (auth('office')->check()) {
            $request_data['office_id'] = auth('office')->user()->office_id;
        }

        $user = User::create($request_data);

        $user->roles()->attach($request->roles);

        return redirect()->route('office.users.index')->with('success', __('global.operation_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('externaloffice::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::officeRoles()->get();

        return view('externaloffice::users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required | string | max:100 | min:3 | unique:users',
            'phone' => 'required | numeric',
            'status' => 'required | numeric',
            'username' => 'required | string | max:100 | min:3|regex:/^[A-Za-z]+$/',
            'password' => 'nullable | confirmed | string | min:6',
        ]);

        $request_data = $request->except('password');

        if ($request->filled(['password'])) {
            $request_data['password'] = Hash::make($request->password);
        }

        $user->update($request_data);

        $user->roles()->sync($request->roles);

        return redirect()->route('office.users.index')->with('success', __('global.operation_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->roles()->detach();

        $user->permissions()->detach();

        $user->delete();

        return redirect()->route('office.users.index')->with('success', __('global.operation_success'));
    }

    public function profile(Request $request)
    {
        $user = User::find(auth()->user()->getKey());

        request()->validate([
            'username' => 'required | string | max:100 | min:3|regex:/^[A-Za-z]+$/',
            'password' => 'sometimes | confirmed | string | min:6',
        ]);

        $request_data = $request->except('password');

        if ($request->filled(['password'])) {
            $request_data['password'] = bcrypt($request->password);
        }

        if (Hash::check($request->old_password, $user->password) && $request->old_password != $request->password) {
            $user->update($request_data);
            session()->flash('success', 'تمت العملية بنجاح');
        } else {
            if ($request->old_password == $request->password) {
                session()->flash('error', 'يجب ادخال كلمة مرور جديدة');
            } else {
                session()->flash('error', 'كلمة المرور الحالية عير صحيحة');
            }
        }


        return back();
    }
}
