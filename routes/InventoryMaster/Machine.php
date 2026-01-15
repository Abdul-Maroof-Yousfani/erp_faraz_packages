<?php

Route::group(['prefix' => 'InventoryMaster/Machine', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'InventoryMaster\MachineController@index');
    Route::get('/create', 'InventoryMaster\MachineController@create');
    Route::post('/store', 'InventoryMaster\MachineController@store')->name('Machine.store');
    Route::get('/edit/{id}', 'InventoryMaster\MachineController@edit')->name('Machine.edit');
    Route::post('/update/{id}', 'InventoryMaster\MachineController@update')->name('Machine.update');
    
    Route::get('/delete/{id}', 'InventoryMaster\MachineController@deleteMachine')->name('Machine.delete');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('InventoryMaster/Machine/'));
        // Replace 'Machine.index' with your actual index route
    })->name('Machine.cancel');

});

