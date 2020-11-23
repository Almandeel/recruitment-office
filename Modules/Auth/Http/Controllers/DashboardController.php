<?php

namespace Modules\Auth\Http\Controllers;

use App\User;
use App\Role;
use App\Permission;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $usersCount = User::where('id', '!=', 1)->count();
        $rolesCount = Role::count();
        $permissionsCount = Permission::count();

        return view('auth::users.dashboard', compact('usersCount', 'rolesCount', 'permissionsCount'));
    }
}
