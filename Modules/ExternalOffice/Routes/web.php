<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:office'], 'prefix' => 'office'], function () {
	Route::get('/', 'ExternalOfficeController')->name('office.dashboard');
	Route::get('not-active', function () {
		return view('externaloffice::not-active');
	})->name('offices.users.not-active');

	Route::post('attachments', 'AttachmentController@store')->name('office.attachments.store');
	Route::put('attachments/{attachment}', 'AttachmentController@update')->name('office.attachments.update');
	Route::get('attachments/{attachment}', 'AttachmentController@show')->name('office.attachments.show');
	Route::delete('attachments/{attachment}', 'AttachmentController@destroy')->name('office.attachments.destroy');

	Route::post('cvs/{cv}/pull', 'CvController@pull')->name('cvs.pull');

	Route::prefix('cvs')->name('cvs.')->group(function () {
		Route::resource('bills', 'BillController');
	});

	Route::resources([
		'cvs' => 'CvController',
		'professions' => 'ProfessionController',
		// 'contracts' => 'ContractController',
		'countries' => 'CountryController',
		'marketers' => 'MarketerController',
		'advances' => 'AdvanceController',
	]);

	Route::name('office.')->group(function () {
		Route::get('profile', 'UserController@profile')->name('users.profile');
		Route::put('profile', 'UserController@profileUpdate')->name('users.profile.update');
		Route::resource('users', 'UserController');
		Route::resource('roles', 'RoleController');
	});

    Route::resource('returns', 'ReturnController', ['as' => 'office'])->only(['index', 'show']);
    Route::resource('pulls', 'PullController', ['as' => 'office'])->only(['index', 'show']);
    Route::get('flights/{flight}/passengers/{passenger}', 'FlightPassengerController@show')->name('office.flights.passengers.show');
    Route::put('flights/{flight}/passengers/{passenger}', 'FlightPassengerController@update')->name('office.flights.passengers.update');
    Route::as('office')->resource('flights', 'FlightController');
});
