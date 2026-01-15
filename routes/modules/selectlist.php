<?php

Route::group(['prefix' => 'slal','before' => 'csrf'], function () {
    Route::get('/departmentDependentDesignation', 'SelectListLoadAjaxController@departmentDependentDesignation');
    Route::get('/locationDependentRegion', 'SelectListLoadAjaxController@locationDependentRegion');
    Route::get('/getEmployeeRegionList', 'SelectListLoadAjaxController@getEmployeeRegionList');

    Route::get('/stateLoadDependentCountryId', 'SelectListLoadAjaxController@stateLoadDependentCountryId');
    Route::get('/cityLoadDependentStateId', 'SelectListLoadAjaxController@cityLoadDependentStateId');
	Route::get('/employeeLoadDependentDepartmentID', 'SelectListLoadAjaxController@employeeLoadDependentDepartmentID');
    Route::get('/MachineEmployeeListDeptWise', 'SelectListLoadAjaxController@MachineEmployeeListDeptWise');
    Route::get('/MachineAllEmployeeList', 'SelectListLoadAjaxController@MachineAllEmployeeList');
    Route::get('/getEmployeeProjectList', 'SelectListLoadAjaxController@getEmployeeProjectList');
    Route::get('/getEmployeeCategoriesList', 'SelectListLoadAjaxController@getEmployeeCategoriesList');
    Route::get('/getBuildingsList', 'SelectListLoadAjaxController@getBuildingsList');


});