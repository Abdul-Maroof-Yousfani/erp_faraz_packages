<?php
//Start Users
Route::group(['prefix' => 'users','before' => 'csrf'], function () {
    Route::get('/u', 'UsersController@toDayActivity');
    Route::get('/createMainMenuTitleForm','UsersController@createMainMenuTitleForm');
    Route::get('/createSubMenuForm','UsersController@createSubMenuForm');
    Route::get('/createUsersForm', 'UsersController@createUsersForm');
    Route::get('/createRoleForm','UsersController@createRoleForm');
    Route::get('/viewRoleList','UsersController@viewRoleList');
    Route::get('/viewEmployeePrivileges/{id}','UsersController@viewEmployeePrivileges');
    Route::get('/editUserProfile','UsersController@editUserProfile');

    Route::get('/createNewUser','UsersAddDetailController@createNewUser');
    Route::post('/storeNewUser','UsersAddDetailController@storeNewUser');
    
    Route::get('/userList','UsersController@userList');
    Route::get('/userEditForm/{id}','UsersAddDetailController@userEditForm')->name('userEditForm');
    Route::post('/editUser','UsersAddDetailController@editUser');

});

Route::group(['prefix' => 'udc','before' => 'csrf'], function () {
    Route::get('/viewMainMenuTitleList','UsersDataCallController@viewMainMenuTitleList');
    Route::get('/viewSubMenuList','UsersDataCallController@viewSubMenuList');
});

Route::group(['prefix' => 'uad','before' => 'csrf'], function () {
    Route::post('/addMainMenuTitleDetail','UsersAddDetailController@addMainMenuTitleDetail');
    Route::post('/addSubMenuDetail','UsersAddDetailController@addSubMenuDetail');
    Route::post('/addRoleDetail','UsersAddDetailController@addRoleDetail');

    /*Edit Routes*/
    Route::post('/editUserPasswordDetail','UsersEditDetailController@editUserPasswordDetail');
    Route::post('/editUserRoleDetail','UsersEditDetailController@editUserRoleDetail');
    Route::post('/editApprovalCodeDetail','UsersEditDetailController@editApprovalCodeDetail');
    
    
   


});
//End Users
