<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::resource('users', 'UserController');
        Route::get('/', 'DashboardController')->name('users.dashboard');
        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
    });

    Route::get('profile', 'UserController@profile')->name('users.profile');
    Route::put('profile', 'UserController@profileUpdate')->name('users.profile.update');
});
