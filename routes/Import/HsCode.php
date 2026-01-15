<?php

Route::group(['prefix' => 'import/HsCode', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'Import\HsCodeController@index');
    Route::get('/create', 'Import\HsCodeController@create');
    Route::post('/store', 'Import\HsCodeController@store')->name('HsCode.store');
    Route::get('/edit/{id}', 'Import\HsCodeController@edit')->name('HsCode.edit');
    Route::get('/show/{id}', 'Import\HsCodeController@show');
    Route::post('/update/{id}', 'Import\HsCodeController@update')->name('HsCode.update');
    
    Route::get('/delete/{id}', 'Import\HsCodeController@deleteHsCode')->name('HsCode.delete');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('import/HsCode/'));

    })->name('HsCode.cancel');

});


