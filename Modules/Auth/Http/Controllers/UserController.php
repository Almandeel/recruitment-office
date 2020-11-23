<?php

namespace Modules\Auth\Http\Controllers;

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;

class UserController extends Controller
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
    public function dashboard()
    {
        $users = User::where('id', '!=', 1)->where('id', '!=', auth()->user()->getKey())->get();
        return view('auth::users.dashboard',compact('users'));
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $users = User::where('id', '!=', 1)->where('id', '!=', auth()->user()->getKey())->get();
        return view('auth::users.index',compact('users'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $employees = Employee::all()->filter(function($employee){ return $employee->user == null; });
        $permissions = Permission::enternal();
        $roles = Role::mainOfficeRoles()->get();
        $positions      = Position::all();
        $departments    = Department::all();
        $modules = [
            'employee'      => ['attendance', 'vacations', 'departments', 'employees', 'lines', 'phones', 'vacations', 'positions', 'custodies'],
            'accounting'    => ['accounts', 'centers', 'entries', 'expenses', 'salaries', 'transfers', 'vouchers', 'years', 'transactions', 'safes', 'statements'],
            'user'         => ['logs', 'notes', 'permissions', 'roles', 'suggestions', 'tasks', 'users', 'sms'],
            'service'      => ['complaints', 'contracts', 'customers', 'cv', 'marketers', 'services', 'flights'],
            'warehouse'     => ['warehouses', 'warehousecvs'],
            'tutorial'      => ['categories', 'tutorials'],
            'mails'          => ['letters', 'mail'],
            'office'       => ['countries', 'offices', 'professions'],
        ];
        return view('auth::users.create',compact('employees', 'positions', 'departments', 'permissions', 'roles', 'modules'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        request()->validate([
        // 'username'      => 'required|string|max:100|min:3 |regex:/^[A-Za-z]+$/| unique:users',
        'username'      => 'required|string|unique:users',
        'password'      => 'required|string|min:6',
        'employee_id'   => 'nullable|numeric',
        ]);
        
        $request_data = $request->except('password');
        $request_data['password'] = bcrypt($request->password);
        $user = User::create($request_data);
        
        
        $user->roles()->attach($request->roles);
        
        $user->permissions()->attach($request->permissions);
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        
        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('users.index');
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function show(User $user)
    {
        return view('auth::users.show', compact('user'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {
        $employees = Employee::all();//->filter(function($employee) use ($user){ return $employee->user == null || $employee->id == $user->id; })
        $permissions = Permission::enternal();
        $roles = Role::mainOfficeRoles()->get();
        $positions      = Position::all();
        $departments    = Department::all();
        $modules = [
            'employee'      => ['attendance', 'vacations', 'departments', 'employees', 'lines', 'phones', 'vacations', 'positions', 'custodies'],
            'accounting'    => ['accounts', 'centers', 'entries', 'expenses', 'salaries', 'transfers', 'vouchers', 'years', 'transactions', 'safes', 'statements'],
            'user'         => ['logs', 'notes', 'permissions', 'roles', 'suggestions', 'tasks', 'users', 'sms'],
            'service'      => ['complaints', 'contracts', 'customers', 'cv', 'marketers', 'services', 'flights'],
            'warehouse'     => ['warehouses', 'warehousecvs'],
            'tutorial'      => ['categories', 'tutorials'],
            'mails'          => ['letters', 'mail'],
            'office'       => ['countries', 'offices', 'professions'],
        ];
        return view('auth::users.edit', compact('user', 'employees', 'positions', 'departments', 'permissions', 'roles', 'modules'));
        
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
        request()->validate([
        'username'      => 'required|string',
        'password'      => 'nullable|string|min:6',
        'employee_id'   => ['nullable', Rule::unique('users', 'employee_id')->ignore($user)]
        ]);
        
        $request_data = $request->except('password');
        
        if($request->password) {
            $request_data['password'] = bcrypt($request->password);
        }
        
        $user->update($request_data);
        
        $user->roles()->sync($request->roles);
        
        $user->permissions()->sync($request->permissions);
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        
        if($request->next == 'back') {
            return back();
        }else {
            return redirect()->route('users.index');
        }
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
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        
        return redirect()->route('users.index');
        
    }
    
    
    public function profile(Request $request) {
        return view('auth::users.profile');
    }
    
    public function profileUpdate(Request $request) {
        
        $user = User::find(auth()->user()->getKey());
        
        request()->validate([
        'username'      => 'required|string|max:100|min:3|regex:/^[A-Za-z]+$/',
        'password'      => 'nullable|string|min:6  |confirmed',
        ]);
        
        $request_data = $request->except('password');
        
        if($request->password) {
            $request_data['password'] = bcrypt($request->password);
        }
        
        if(Hash::check($request->old_password, $user->password) && $request->old_password != $request->password  ) {
            $user->update($request_data);
            session()->flash('success', 'تمت العملية بنجاح');
        }else {
            if($request->old_password == $request->password) {
                session()->flash('error', 'يجب ادخال كلمة مرور جديدة');
            }else {
                session()->flash('error', 'كلمة المرور الحالية عير صحيحة');
            }
        }
        
        
        return back();
    }
}