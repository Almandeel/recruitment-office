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
    Route::prefix('accounting')->group(function() {
        Route::resources([
        'accounts' => 'AccountController',
        'years' => 'YearController',
        'entries' => 'EntryController',
        'safes' => 'SafeController',
        'payments' => 'PaymentController',
        'transfers' => 'TransferController',
        'cheques' => 'ChequeController',
        'centers' => 'CenterController',
        'expenses' => 'ExpenseController',
        'vouchers' => 'VoucherController',
        ]);
        
        Route::get('{year}/closing', 'YearController@closing')->name('years.closing');
        Route::post('{year}/close', 'YearController@close')->name('years.close');
        Route::get('{year}/income_statement', 'YearController@incomeStatement')->name('years.income_statement');
        Route::get('{year}/trial_balance', 'YearController@trialBalance')->name('years.trial_balance');
        Route::get('{year}/balance_sheet', 'YearController@balanceSheet')->name('years.balance_sheet');
        
        
        Route::post('safes/confirm', 'SafeController@confirm')->name('safes.confirm');
        Route::post('entries/confirm', 'EntryController@confirm')->name('entries.confirm');
        
        Route::post('custodies/{custody}/pay', 'CustodyController@pay')->name('accounting.custodies.pay');
        // Route::get('transactions', 'AccountingController@transactions')->name('accounting.transactions');
        // Route::get('transactions/{transaction}', 'AccountingController@transaction')->name('accounting.transaction');
        
        Route::resource('salaries', 'SalaryController', ['as' => 'accounting']);
        Route::resource('transactions', 'TransactionController', ['as' => 'accounting']);
        // Route::get('salaries', 'SalaryController@index')->name('accounting.salaries');
        // Route::get('salaries/{salary}', 'SalaryController@show')->name('accounting.salary');
        // Route::post('salaries/confirming/{salary}', 'SalaryController@confirming')->name('accounting.salaries.confirming');
        // Route::get('salaries/confirm/{salary}', 'SalaryController@confirm')->name('accounting.salaries.confirm');
        
        Route::resource('custodies', 'CustodyController', ['as' => 'accounting']);
        Route::resource('bills', 'BillController', ['as' => 'accounting']);
        
        Route::get('/', 'AccountingController@index')->name('accounting.dashboard');
        Route::get('/tree', 'AccountingController@tree')->name('accounting.tree');
        Route::prefix('reports')->group(function() {
            Route::get('/', 'ReportController@index')->name('accounting.reports');
            Route::get('/safes', 'ReportController@safes')->name('accounting.reports.safes');
            Route::get('/safes/{safe}', 'ReportController@safe')->name('accounting.reports.safe');
            Route::get('/centers', 'ReportController@centers')->name('accounting.reports.centers');
            Route::get('/centers/{center}', 'ReportController@center')->name('accounting.reports.center');
            Route::get('/vouchers', 'ReportController@vouchers')->name('accounting.reports.vouchers');
            Route::get('/expenses', 'ReportController@expenses')->name('accounting.reports.expenses');
            Route::get('/transfers', 'ReportController@transfers')->name('accounting.reports.transfers');
            Route::get('/transactions', 'ReportController@transactions')->name('accounting.reports.transactions');
            Route::get('/salaries', 'ReportController@salaries')->name('accounting.reports.salaries');
        });
        
    });
    Route::get('/accounts/statement', 'AccountController@statement')->name('accounts.statement');
    
    $domain = '{accounting}.' . parse_url(env('APP_URL'), PHP_URL_HOST);
    Route::domain($domain)->group(function($router) {
        Route::get('/', 'AccountingController@index');
    });
});