<?php
//new code

Route::group(['middleware' => 'mysql2','before' => 'csrf'], function () {
    Route::get('create-assets', 'AssetsController@createAssetsForm')->name('create-assets');
    Route::get('assets-list', 'AssetsController@viewAssetsList')->name('assets-list');
    Route::post('add-assets', 'AssetsController@addAssets')->name('add-assets');
    Route::post('edit-assets', 'AssetsController@editAssets')->name('edit-assets');
    Route::get('create-category', 'AssetsController@createCategoryForm')->name('create-category');
    Route::get('create-sub-category', 'AssetsController@createSubCategoryForm')->name('create-sub-category');
    Route::get('assetsFilter', 'AssetsController@assetsFilter')->name('assetsFilter');
    Route::get('assetsEditColumnsList', 'AssetsController@assetsEditColumnsList')->name('assetsEditColumnsList');
    Route::get('editAssetsForm', 'AssetsController@editAssetsForm')->name('editAssetsForm');
    Route::get('upload-assets', 'AssetsController@uploadAssetsForm')->name('upload-assets');
    Route::post('upload-assets-file', 'AssetsController@uploadAssetsFile')->name('upload-assets-file');
    Route::get('viewAssetsDetail', 'AssetsController@viewAssetsDetail')->name('viewAssetsDetail');
    Route::get('assets-report', 'AssetsController@viewAssetsReport')->name('assets-report');
    
    Route::get('getPremiseData', 'AssetsController@getPremiseData')->name('getPremiseData');
    Route::get('getSubCategoryData', 'AssetsController@getSubCategoryData')->name('getSubCategoryData');

    Route::post('add-category-on-assets', 'AssetsController@addCategory')->name('add-category-on-assets');
    Route::post('add-sub-category-on-assets', 'AssetsController@addSubCategory')->name('add-sub-category-on-assets');

    Route::get('create-work-order', 'WorkOrderController@createWorkOrderForm')->name('create-work-order');
    Route::get('work-order-list', 'WorkOrderController@viewWorkOrderList')->name('work-order-list');
    Route::get('editWorkOrderForm', 'WorkOrderController@editWorkOrderForm')->name('editWorkOrderForm');
    Route::post('add-work-order', 'WorkOrderController@addWorkOrder')->name('add-work-order');
    Route::post('edit-work-order', 'WorkOrderController@editWorkOrder')->name('edit-work-order');
    Route::get('getProjectAssets', 'WorkOrderController@getProjectAssets')->name('getProjectAssets');
    Route::get('getAssetData', 'WorkOrderController@getAssetData')->name('getAssetData');
    Route::get('workOrderEditColumnList', 'WorkOrderController@workOrderEditColumnList')->name('workOrderEditColumnList');
    Route::get('workOrderFilter', 'WorkOrderController@workOrderFilter')->name('workOrderFilter');
    Route::get('workOrderStatusForm', 'WorkOrderController@workOrderStatusForm')->name('workOrderStatusForm');
    Route::get('openStatusForm', 'WorkOrderController@openStatusForm')->name('openStatusForm');
    Route::post('add-work-order-status', 'WorkOrderController@addWorkOrderStatus')->name('add-work-order-status');
    Route::post('add-work-order-status', 'WorkOrderController@addWorkOrderStatus')->name('add-work-order-status');
    Route::get('getEmployees', 'WorkOrderController@getEmployees')->name('getEmployees');
    Route::get('viewPreventiveWorkOrderDetail', 'WorkOrderController@viewPreventiveWorkOrderDetail')->name('viewPreventiveWorkOrderDetail');
    Route::get('viewCorrectiveWorkOrderDetail', 'WorkOrderController@viewCorrectiveWorkOrderDetail')->name('viewCorrectiveWorkOrderDetail');

    Route::get('create-tickets', 'TicketsController@createTicketForm')->name('create-tickets');
    Route::get('tickets-list', 'TicketsController@viewTicketsList')->name('tickets-list');

    Route::get('createUsersForm', 'TicketsController@createUsersForm')->name('createUsersForm');
    Route::get('viewUsersList', 'TicketsController@viewUsersList')->name('viewUsersList');

    Route::get('create-master-items', 'MasterController@createMasterForm')->name('create-master-items');
    Route::get('master-items-list', 'MasterController@viewMasterList')->name('master-items-list');
    Route::get('masterItemsList', 'MasterController@masterItemsList')->name('masterItemsList');
    Route::post('getMasterItemData', 'MasterController@getMasterItemData')->name('getMasterItemData');

    Route::post('add-category', 'MasterController@addCategory')->name('add-category');
    Route::post('edit-category', 'MasterController@editCategory')->name('edit-category');
    Route::get('editCategoryForm', 'MasterController@editCategoryForm')->name('editCategoryForm');

    Route::post('add-sub-category', 'MasterController@addSubCategory')->name('add-sub-category');
    Route::post('edit-sub-category', 'MasterController@editSubCategory')->name('edit-sub-category');
    Route::get('editSubCategoryForm', 'MasterController@editSubCategoryForm')->name('editSubCategoryForm');

    Route::post('add-floor', 'MasterController@addFloor')->name('add-floor');
    Route::post('edit-floor', 'MasterController@editFloor')->name('edit-floor');
    Route::get('editFloorsForm', 'MasterController@editFloorsForm')->name('editFloorsForm');

    Route::post('add-manufacturer', 'MasterController@addManufacturer')->name('add-manufacturer');
    Route::post('edit-manufacturer', 'MasterController@editManufacturer')->name('edit-manufacturer');
    Route::get('editManufacturerForm', 'MasterController@editManufacturerForm')->name('editManufacturerForm');

    Route::post('add-useful-life', 'MasterController@addUseFulLife')->name('add-useful-life');
    Route::post('edit-useful-life', 'MasterController@editUseFulLife')->name('edit-useful-life');
    Route::get('editUseFulLifeForm', 'MasterController@editUseFulLifeForm')->name('editUseFulLifeForm');

    Route::post('add-premises', 'MasterController@addPremises')->name('add-premises');
    Route::post('edit-premises', 'MasterController@editPremises')->name('edit-premises');
    Route::get('editPremisesForm', 'MasterController@editPremisesForm')->name('editPremisesForm');

    Route::post('add-frequency', 'MasterController@addFrequency')->name('add-frequency');
    Route::post('edit-frequency', 'MasterController@editFrequency')->name('edit-frequency');
    Route::get('editFrequencyForm', 'MasterController@editFrequencyForm')->name('editFrequencyForm');

    Route::post('deleteTableRecord', 'MasterController@deleteTableRecord')->name('deleteTableRecord');

});


?>
