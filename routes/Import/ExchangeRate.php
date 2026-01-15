<?php

Route::group(['prefix' => 'import/ExchangeRate' , 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/', 'Import\ExchangeRateController@index');
    Route::get('/create', 'Import\ExchangeRateController@create');
    Route::post('/store', 'Import\ExchangeRateController@store')->name('ExchangeRate.store');
    Route::get('/edit/{id}', 'Import\ExchangeRateController@edit')->name('ExchangeRate.edit');
    Route::post('/update/{id}', 'Import\ExchangeRateController@update')->name('ExchangeRate.update');
    
    Route::get('/delete/{id}', 'Import\ExchangeRateController@deleteExchangeRate')->name('ExchangeRate.delete');

    Route::get('/report', 'Import\ExchangeRateController@report');

    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('import/ExchangeRate/'));
        // Replace 'ExchangeRate.index' with your actual index route
    })->name('ExchangeRate.cancel');

});

Route::group(['prefix' => 'import/Currency', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    Route::get('/', 'Import\ExchangeRateController@currency');
    Route::get('/create', 'Import\ExchangeRateController@create_currency');
    Route::post('/store/{currency?}', 'Import\ExchangeRateController@store_update_currency')->name('Currency.store_update');
    Route::get('/edit/{id}', 'Import\ExchangeRateController@edit_currency')->name('Currency.edit');


}); 

Route::group(['prefix' => 'import/Agent', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    Route::get('/', 'Import\ClearingAgentMemberController@agent');
    Route::get('/create', 'Import\ClearingAgentMemberController@create_agent');
    Route::post('/store/{agent?}', 'Import\ClearingAgentMemberController@store_update_agent')->name('Agent.store_update');
    Route::get('/edit/{id}', 'Import\ClearingAgentMemberController@edit_agent')->name('Agent.edit');


}); 

