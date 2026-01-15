<?php

Route::group(['prefix' => 'import/LcAndLgAgainstPo', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'Import\LcAndLgAgainstPoController@index');
    Route::get('/create', 'Import\LcAndLgAgainstPoController@create');
    Route::post('/store', 'Import\LcAndLgAgainstPoController@store')->name('LcAndLgAgainstPo.store');
    Route::get('/edit/{id}', 'Import\LcAndLgAgainstPoController@edit')->name('LcAndLgAgainstPo.edit');
    Route::get('/show/{id}', 'Import\LcAndLgAgainstPoController@show');
    Route::post('/update/{id}', 'Import\LcAndLgAgainstPoController@update')->name('LcAndLgAgainstPo.update');
    
    Route::get('/delete/{id}', 'Import\LcAndLgAgainstPoController@deleteLcAndLgAgainstPo')->name('LcAndLgAgainstPo.delete');
    
    Route::get('/reportLcLg', 'Import\LcAndLgAgainstPoController@reportLcLg');


    Route::get('/import_ppi','Import\LcAndLgAgainstPoController@import_ppi');
    
    Route::get('/getPodata','Import\LcAndLgAgainstPoController@getPodata');

    Route::get('/viewSecurityDepostiLetter','Import\LcAndLgAgainstPoController@viewSecurityDepostiLetter');
    Route::get('/createPayOrderForm','Import\LcAndLgAgainstPoController@createPayOrderForm');
    
    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('import/LcAndLgAgainstPo/'));

    })->name('LcAndLgAgainstPo.cancel');

});


