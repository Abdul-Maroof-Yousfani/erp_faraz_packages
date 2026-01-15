<?php 


Route::group(['prefix' => 'store', 'middleware' => 'mysql2','before' => 'csrf'], function ()  {
    Route::get('BudgetForm', 'BudgetController@BudgetForm')->name('BudgetForm');;
    Route::post('Budgetdata', 'BudgetController@Budgetdata')->name('Budgetdata');
    Route::get('BudgetList', 'BudgetController@Budgetlist')->name('BudgetList');
});


Route::group(['prefix' => 'purchase', 'middleware' => 'mysql2','before' => 'csrf'], function () { 
    Route::get('/AddGoodsquality', 'PurchaseController@AddGoodsquality');
});