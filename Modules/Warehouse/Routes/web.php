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

Route::prefix('warehouses')->group(function() {
    Route::get('/', 'WarehouseController@dashboard')->name('warehouses.dashboard');
    Route::post('/warehouseuser', 'WarehouseController@storeUser')->name('warehouseuser.storeuser');
    Route::delete('/warehouseuser/{id}', 'WarehouseController@destroyUser')->name('warehouseuser.distroyuser');
    Route::resource('warehouses', 'WarehouseController');

    Route::resource('warehousecv', 'WarehouseCvController');
});
