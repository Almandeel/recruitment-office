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

Route::prefix('report')->group(function() {
    Route::get('/', 'ReportController@index')->name('report.dashboard');

    // HR Reports Links
    Route::get('/hr', 'HrController@index')->name('report.hr');
    Route::get('/hr/employees', 'HrController@employees')->name('report.employees');

    // Services Reports Links
    Route::get('/services', 'ServiceController@index')->name('report.services');
    Route::get('/services/contracts', 'ServiceController@contracts')->name('report.contracts');
    Route::get('/services/cvs', 'ServiceController@cvs')->name('report.cvs');
    Route::get('/services/customers', 'ServiceController@customers')->name('report.customers');
    // Offices Report Link
    // Route::get('/offices', 'ServiceController@index')->name('report.offices');
});
