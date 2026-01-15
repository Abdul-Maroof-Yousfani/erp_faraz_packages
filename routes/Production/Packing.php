<?php

Route::group(['prefix' => 'Production/Packing'], function () {
    
    Route::get('/', 'Production\PackingController@index');
    Route::get('/create', 'Production\PackingController@create');
    Route::post('/store', 'Production\PackingController@store')->name('Packing.store');
    Route::get('/edit/{id}', 'Production\PackingController@edit')->name('Packing.edit');
    Route::get('/show/{id}', 'Production\PackingController@show')->name('Packing.show');
    Route::post('/update', 'Production\PackingController@update')->name('Packing.update');
    Route::get('/createPackingFormAjax', 'Production\PackingController@createPackingFormAjax')->name('createPackingFormAjax');
    Route::get('/createFinalPackingForm', 'Production\PackingController@createFinalPackingForm')->name('createFinalPackingForm');

    Route::get('/delete/{id}', 'Production\PackingController@deletePacking')->name('Packing.delete');
    Route::get('/productionPlanAndCustomerAgainstSo', 'Production\PackingController@productionPlanAndCustomerAgainstSo');
    Route::get('/getMachineProcessDataByMr', 'Production\PackingController@getMachineProcessDataByMr');
    Route::get('/getPurchaseRequestData', 'Production\PackingController@getPurchaseRequestData');
    Route::get('/checkPackagingType', 'Production\PackingController@checkPackagingType');
    
    
    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('Production/Packing/'));
        // Replace 'Packing.index' with your actual index route
    })->name('Packing.cancel');

});

