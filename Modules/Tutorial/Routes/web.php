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

Route::middleware('auth')->prefix('tutorial')->group(function() {
    Route::get('/', 'TutorialController@dashboard')->name('tutorials.dashboard');
    Route::resource('/tutorials', 'TutorialController');
    Route::resource('/categories', 'CategoryController');
});
