<?php

Route::group(['prefix' => 'import/LcAndLg', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'Import\LcAndLgController@index');
    Route::get('/create', 'Import\LcAndLgController@create');
    Route::post('/store', 'Import\LcAndLgController@store')->name('LcAndLg.store');
    Route::get('/edit/{id}', 'Import\LcAndLgController@edit')->name('LcAndLg.edit');
    Route::get('/show/{id}', 'Import\LcAndLgController@show');
    Route::post('/update/{id}', 'Import\LcAndLgController@update')->name('LcAndLg.update');
    
    Route::get('/delete/{id}', 'Import\LcAndLgController@deleteLc')->name('LcAndLg.delete');
    
    Route::get('/reportLcLg', 'Import\LcAndLgController@reportLcLg');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('import/LcAndLg/'));

    })->name('LcAndLg.cancel');

});


