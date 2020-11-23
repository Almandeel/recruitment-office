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

Route::middleware('auth')->group(function () {
    Route::get('/', 'MainController@index')->name('home');
    Route::resource('/tasks', 'TaskController');
    Route::resource('/suggestions', 'SuggestionController');
});

Route::prefix('offices')->middleware('auth')->group(function () {
    Route::get('/', 'OfficeController@dashboard')->name('offices.dashboard');

    Route::get('mainadvances', 'AdvanceController@index')->name('mainadvances.index');
    Route::get('mainadvances/{advance}', 'AdvanceController@show')->name('mainadvances.show');
    Route::put('mainadvances/{advance}', 'AdvanceController@update')->name('mainadvances.update');

    Route::resource('offices', 'OfficeController');
    Route::get('offices/bill/{id}', 'OfficeController@showBill')->name('show.bill');
    Route::get('offices/advance/{advance}', 'OfficeController@showAdvance')->name('show.advance');
    Route::post('offices/bill/voucher', 'OfficeController@billVouchers')->name('bill.voucher');
    Route::resource('bills', 'BillController', ['as' => 'offices']);
    Route::resource('returns', 'ReturnController', ['as' => 'offices']);
    Route::resource('advances', 'AdvanceController', ['as' => 'offices']);
    Route::resource('pulls', 'PullController', ['as' => 'offices']);
    Route::get('search/contracts', 'ContractSearchController@create')->name('contracts.search');
    Route::post('search/contracts', 'ContractSearchController@show')->name('contracts.search.show');
    Route::resources([
    'mainprofessions' => 'ProfessionController',
    'maincountries' => 'CountryController',
    'mainflights' => 'FlightController'
    ]);
});
