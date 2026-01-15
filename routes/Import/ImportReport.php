<?php

Route::group(['prefix' => 'import/Report', 'middleware' => 'mysql2','before' => 'csrf'], function () {
    
    Route::get('/underClearance', 'Import\ImportReportController@underClearance');//->name('ImportReport.underClearance');
    Route::get('/maturitySheet', 'Import\ImportReportController@maturitySheet');//->name('ImportReport.maturitySheet');
    Route::get('/bankWisePADSummary', 'Import\ImportReportController@bankWisePADSummary');//->name('ImportReport.bankWisePADSummary');
    Route::get('/lcandLgBankLimit', 'Import\ImportReportController@lcandLgBankLimit');//->name('ImportReport.lcandLgBankLimit');
    Route::get('/exchangeAndGainLossReport', 'Import\ImportReportController@exchangeAndGainLossReport');//->name('ImportReport.exchangeAndGainLossReport');
    Route::get('/clearingAgent', 'Import\ImportReportController@clearingAgent');//->name('ImportReport.clearingAgent');
    Route::get('/InsuranceDetail', 'Import\ImportReportController@InsuranceDetail');//->name('ImportReport.clearingAgent');
    Route::get('/securityDepositReport', 'Import\ImportReportController@securityDepositReport');//->name('ImportReport.clearingAgent');
    Route::get('/rawMaterialDutySheet', 'Import\ImportReportController@rawMaterialDutySheet');//->name('ImportReport.clearingAgent');
    
    // Route::get('/underClearanceAjax', 'Import\ImportReportController@underClearanceAjax');//->name('ImportReport.underClearance');

});

