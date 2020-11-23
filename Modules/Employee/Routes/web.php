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
Route::group(['middleware' => 'auth'], function() {
    Route::prefix('hr')->group(function() {
        Route::get('/', 'EmployeeController@dashboard')->name('hr.dashboard');
        Route::resource('employees', 'EmployeeController');
        
        Route::get('employees/{employee}/custodies', 'EmployeeController@custodies')->name('employees.custodies');
        // Route::get('/show/{id}', 'EmployeeController@show')->name('employees.show');
        // Route::post('/', 'EmployeeController@store')->name('employees.store');
        // Route::put('/edit/{id}', 'EmployeeController@update')->name('employees.update');
                
        Route::resources([
            'transactions' => 'TransactionController',
            'departments' => 'DepartmentController',
            'positions' => 'PositionController',
            'salaries' => 'SalaryController',
            'custodies' => 'CustodyController',
            'vacations' => 'VacationController',
        ]);
        
        //departments route
        // Route::get('/departments', 'DepartmentController@index')->name('departments.index');;
        // Route::get('/departments/show/{id}', 'DepartmentController@show')->name('departments.show');;
        // Route::post('/departments', 'DepartmentController@store')->name('departments.store');
        // Route::put('/departments/edit/{id}', 'DepartmentController@update')->name('departments.update');
        
        //positions route
        // Route::get('/positions', 'PositionController@index')->name('positions.index');;
        // Route::get('/positions/show/{id}', 'PositionController@show')->name('positions.show');;
        // Route::post('/positions', 'PositionController@store')->name('positions.store');
        // Route::put('/positions/edit/{id}', 'PositionController@update')->name('positions.update');
        
        //salaries route
        // Route::get('/salaries/{id}', 'SalaryController@index')->name('salaries.index');
        // Route::post('/salaries/create', 'SalaryController@create')->name('salaries.create');
        // Route::post('/salaries', 'SalaryController@store')->name('salaries.store');
        // Route::put('/salaries/edit/{id}', 'SalaryController@update')->name('salaries.update');
        // Route::delete('/salaries/delete/{id}', 'SalaryController@destroy')->name('salaries.destroy');
        
        //attendance route
        Route::get('/attendance', 'AttendanceController@index')->name('attendance.index');
        Route::post('/attendance/create', 'AttendanceController@create')->name('attendance.create');
        Route::post('/attendance', 'AttendanceController@store')->name('attendance.store');
        Route::put('/attendance/edit/{id}', 'AttendanceController@update')->name('attendance.update');
        Route::delete('/attendance/delete/{id}', 'AttendanceController@destroy')->name('attendance.destroy');
        
        //vacations route
        // Route::get('/vacations', 'VacationController@index')->name('vacations.index');
        // Route::post('/vacations/create', 'VacationController@create')->name('vacations.create');
        // Route::post('/vacations', 'VacationController@store')->name('vacations.store');
        // Route::put('/vacations/edit/{id}', 'VacationController@update')->name('vacations.update');
        // Route::delete('/vacations/delete/{id}', 'VacationController@destroy')->name('vacations.destroy');
        
        
        //report route
        Route::get('/report/salaries/{id}', 'SalaryController@report')->name('report.salaries');
        
        
        
    });
});
