<?php

Route::group(['prefix' => 'Production/QaPacking'], function () {

    Route::get('/', 'Production\QaPackingController@index');
    Route::get('/create', 'Production\QaPackingController@create');
    Route::post('/store', 'Production\QaPackingController@store')->name('QaPacking.store');
    Route::post('/storeTestResult', 'Production\QaPackingController@storeTestResult')->name('QaPacking.storeTestResult');
    Route::get('/edit/{id}', 'Production\QaPackingController@edit')->name('QaPacking.edit');
    Route::get('/show/{id}', 'Production\QaPackingController@show')->name('QaPacking.show');
    Route::post('/update/{id}', 'Production\QaPackingController@update')->name('QaPacking.update');

    Route::get('/delete', 'Production\QaPackingController@deleteQaPacking')->name('QaPacking.delete');
    Route::get('/productionPlanAndCustomerAgainstSo', 'Production\QaPackingController@productionPlanAndCustomerAgainstSo');
    Route::get('/getPackingListNo', 'Production\QaPackingController@getPackingListNo');
    Route::get('/testingOnReceiveItem/{id}', 'Production\QaPackingController@testingOnReceiveItem')->name('QaPacking.testingOnReceiveItem');
    Route::get('/testResultOnReceiveItem/{id}', 'Production\QaPackingController@testResultOnReceiveItem')->name('QaPacking.testResultOnReceiveItem');
    Route::get('/testResultOnReceiveItemAjax/{id}', 'Production\QaPackingController@testResultOnReceiveItemAjax')->name('QaPacking.testResultOnReceiveItemAjax');

    Route::get('/getPackingListNoForDispatch', 'Production\QaPackingController@getPackingListNoForDispatch');
    Route::get('/viewQaPackingDetail', 'Production\QaPackingController@viewQaPackingDetail');

    Route::get('/addQcValues', 'Production\QaPackingController@addQcValues');
    Route::get('/qcValuesList', 'Production\QaPackingController@qcValuesList')->name('qcValuesList');
    Route::get('/viewQcValuesDetail', 'Production\QaPackingController@viewQcValuesDetail');
    Route::get('/editQcValues/{id}', 'Production\QaPackingController@editQcValues')->name('Production.QaPacking.editQcValues');

    Route::get('/getQcValueForm', 'Production\QaPackingController@getQcValueForm');

    
    Route::post('/storeQcValues', 'Production\QaPackingController@storeQcValues')->name('QaPacking.storeQcValues');
    Route::post('/updateQcValues', 'Production\QaPackingController@updateQcValues')->name('QaPacking.updateQcValues');
    Route::post('/deleteQcValue', 'Production\QaPackingController@deleteQcValue')->name('deleteQcValue');

    
    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('Production/QaPacking/'));
        // Replace 'QaPacking.index' with your actual index route
    })->name('QaPacking.cancel');

});

