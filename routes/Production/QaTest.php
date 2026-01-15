<?php

Route::group(['prefix' => 'Production/QaTest'], function () {
    
    Route::get('/', 'Production\QaTestController@index');
    Route::get('/create', 'Production\QaTestController@create');
    Route::post('/store', 'Production\QaTestController@store')->name('QaTest.store');
    Route::get('/edit/{id}', 'Production\QaTestController@edit')->name('QaTest.edit');
    Route::get('/show/{id}', 'Production\QaTestController@show')->name('QaTest.show');
    Route::post('/update/{id}', 'Production\QaTestController@update')->name('QaTest.update');
    
    Route::get('/delete/{id}', 'Production\QaTestController@deleteQaTest')->name('QaTest.delete');
    Route::get('/productionPlanAndCustomerAgainstSo', 'Production\QaTestController@productionPlanAndCustomerAgainstSo');
    Route::get('/getMachineProcessDataByMr', 'Production\QaTestController@getMachineProcessDataByMr');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('Production/QaTest/'));
        // Replace 'QaTest.index' with your actual index route
    })->name('QaTest.cancel');

});

