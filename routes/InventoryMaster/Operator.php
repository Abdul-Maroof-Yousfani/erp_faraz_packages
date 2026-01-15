<?php

Route::group(['prefix' => 'InventoryMaster/Operator', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'InventoryMaster\OperatorController@index');
    Route::get('/create', 'InventoryMaster\OperatorController@create');
    Route::post('/store', 'InventoryMaster\OperatorController@store')->name('Operator.store');
    Route::get('/edit/{id}', 'InventoryMaster\OperatorController@edit')->name('Operator.edit');
    Route::post('/update/{id}', 'InventoryMaster\OperatorController@update')->name('Operator.update');
    
    Route::get('/delete/{id}', 'InventoryMaster\OperatorController@deleteOperator')->name('Operator.delete');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('InventoryMaster/Operator/'));
        // Replace 'Operator.index' with your actual index route
    })->name('Operator.cancel');

});

