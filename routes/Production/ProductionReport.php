<?php

Route::group(['prefix' => 'Production/Report'], function () {
    
    Route::get('hdpePipeLineReport','Production\ProductionReportController@hdpePipeLineReport')->name('hdpePipeLineReport');
    Route::get('remainingOrderDetail','Production\ProductionReportController@remainingOrderDetail')->name('remainingOrderDetail');
    Route::get('incomingInspection','Production\ProductionReportController@incomingInspection')->name('incomingInspection');
    Route::get('rawmaterialrequisition','Production\ProductionReportController@rawmaterialrequisition')->name('rawmaterialrequisition');
    Route::get('rawmaterialrequirementsreport','Production\ProductionReportController@rawmaterialrequirementsreport')->name('rawmaterialrequirementsreport');
    Route::get('rawmaterialconsumptionreport','Production\ProductionReportController@rawmaterialconsumptionreport')->name('rawmaterialconsumptionreport');
    Route::get('copperworkorderstatusreport','Production\ProductionReportController@copperworkorderstatusreport')->name('copperworkorderstatusreport');
    Route::get('stockflowfrom','Production\ProductionReportController@stockflowfrom')->name('stockflowfrom');
    Route::get('stockreport','Production\ProductionReportController@stockreport')->name('stockreport');


});

