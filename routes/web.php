<?php

use App\Helpers\ReuseableCode;
use App\Http\Controllers\SalesController;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();

Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});
Route::get('login', function () {

    if (Auth::check()) {

        return Redirect::to('dClient');
    } else {
        return view('auth/login');
    }
});

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    echo 'cache clear successfully';
});
Route::get('migrate', function () {
    echo Artisan::call('migrate');
    echo 'All migration run successfully';
});


Route::get('/abc', function () {
    $nexmo = app('Nexmo\Client');
    $nexmo->message()->send([
        'to' => '923368980737',
        'from' => '923368980737',
        'text' => 'Using the instance to send a message.'
    ]);
});
Route::get('/', function () {

    if (Auth::check()) {


        return Redirect::to('dClient');
    } else {

        return view('Visitor.visitorDashboard');
    }
});
Route::get('login', array(
    'as' => 'login',
    function () {
        return view('Visitor.visitorDashboard');
    }
));
/* Visitor Module Starts Here*/

Route::group(['prefix' => 'visitor', 'before' => 'csrf'], function () {
    Route::get('/d', 'VisitorController@index');
    Route::get('/careers/{id?}', 'VisitorController@careers');
    Route::get('/ViewandApplyDetail/{id}/{company_id}', 'VisitorController@ViewandApplyDetail');
    Route::get('/ThankyouForApply', 'VisitorController@ThankyouForApply');
    Route::get('/quizTest', 'VisitorController@quizTest');

    Route::get('/viewQuizTestResult/{id}', 'VisitorController@viewQuizTestResult');
    Route::get('/viewEmployeeIQTestList', 'VisitorController@viewEmployeeIQTestList');
});


Route::group(['prefix' => 'vad', 'before' => 'csrf'], function () {
    Route::post('/addVisitorApplyDetail', 'VisitorAddDetailController@addVisitorApplyDetail');

});

/* Visitor Module Ends Here*/
Route::get('/dClient', 'ClientController@index')->name('dClient');
Route::group(['middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/dMaster', 'MasterController@index');
    Route::get('/fClient', 'ClientController@financeDashboard')->name('fClient');
    Route::get('/get_customer_sales_info', 'ClientController@get_customer_sales_info');
    Route::get('/mydesk', 'ClientController@mydesk')->name('mydesk');
    Route::get('/alert', 'ClientController@alert')->name('alert');
    // Route::get('/financeDashboardAjax', 'ClientController@financeDashboardAjax');
    Route::get('/BusinessFlowChartAjax', 'ClientController@BusinessFlowChartAjax');
    Route::get('/trtpAjax', 'ClientController@trtpAjax');
    Route::get('/salesAgingAjax', 'ClientController@salesAgingAjax');
    Route::get('/vendorAgingAjax', 'ClientController@vendorAgingAjax');
    Route::get('/production_dashboard', 'ClientController@production_dashboard')->name('production_dashboard');
    Route::get('/dCompany', 'CompanyController@index');
    Route::get('/d', 'HomeController@index');

});


Route::get('/deleteMasterTableReceord', 'DeleteMasterTableRecordController@deleteMasterTableReceord');

//Start Company Database Record Delete

Route::group(['prefix' => 'fd', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/deleteCompanyFinanceTwoTableRecords', 'FinanceDeleteController@deleteCompanyFinanceTwoTableRecords');

    Route::get('/repostCompanyFinanceTwoTableRecords', 'FinanceDeleteController@repostCompanyFinanceTwoTableRecords');

    Route::get('/approveCompanyFinanceTwoTableRecords', 'FinanceDeleteController@approveCompanyFinanceTwoTableRecords');
    //amir finance delete
    Route::get('/deletechartofaccount', 'FinanceDeleteController@deletechartofaccount');

    Route::get('/deleteCompanyFinanceThreeTableRecords', 'FinanceDeleteController@deleteCompanyFinanceThreeTableRecords');

    Route::get('/repostCompanyFinanceThreeTableRecords', 'FinanceDeleteController@repostCompanyFinanceThreeTableRecords');

    Route::get('/deleteEmployeeTax', 'FinanceDeleteController@deleteEmployeeTax');

    Route::get('/deleteEmployeeEOBI', 'FinanceDeleteController@deleteEmployeeEOBI');

    Route::get('/deletecostcenter', 'FinanceDeleteController@deletecostcenter');


});

Route::group(['prefix' => 'pd', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::post('/updateMaterialRequestandApprove', 'PurchaseDeleteController@updateMaterialRequestandApprove');
    Route::get('/deleteCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@deleteCompanyPurchaseTwoTableRecords');

    Route::get('/repostCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@repostCompanyPurchaseTwoTableRecords');

    Route::get('/approveCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@approveCompanyPurchaseTwoTableRecords');



    Route::get('/deleteCompanyPurchaseThreeTableRecords', 'PurchaseDeleteController@deleteCompanyPurchaseThreeTableRecords');

    Route::get('/repostCompanyPurchaseThreeTableRecords', 'PurchaseDeleteController@repostCompanyPurchaseThreeTableRecords');

    Route::get('/approveCompanyPurchaseGoodsReceiptNote', 'PurchaseDeleteController@approveCompanyPurchaseGoodsReceiptNote');

    Route::get('/delete_records', 'PurchaseDeleteController@delete_records');
    Route::get('/DeleteAgainForPO', 'PurchaseDeleteController@DeleteAgainForPO');
    Route::get('/reject_po', 'PurchaseDeleteController@reject_po');




});

Route::group(['prefix' => 'std', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/deleteCompanyStoreThreeTableRecords', 'StoreDeleteController@deleteCompanyStoreThreeTableRecords');
    Route::get('/repostCompanyStoreThreeTableRecords', 'StoreDeleteController@repostCompanyStoreThreeTableRecords');
    Route::get('/approvePurchaseRequest', 'StoreDeleteController@approvePurchaseRequest');
    Route::get('/approvePurchaseRequestSale', 'StoreDeleteController@approvePurchaseRequestSale');


});
//End Company Database Record Delete


//Start Companies
Route::group(['prefix' => 'companies', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/c', 'ClientCompaniesController@toDayActivity');
    Route::post('/addCompanyDetail', 'ClientCompaniesController@addCompanyDetail');
});
Route::get('/check_status', 'UserController@check_status');
Route::group(['prefix' => 'ccd', 'before' => 'csrf'], function () {
    $companiesList = DB::table('company')->select(['name', 'id', 'dbName'])->where('status', '=', '1')->get();
    foreach ($companiesList as $routeRow1) {
        Route::get('/' . $routeRow1->dbName . '', 'ClientController@clientCompanyMenu');
    }
});


Route::group(['prefix' => 'users', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/add_notifications', 'UserController@add_notifications');
    Route::get('/get_behavior', 'UserController@get_behavior');
    Route::get('/get_notification_data', 'UserController@get_notification_data');
    Route::get('/notifications_list', 'UserController@notifications_list');
    Route::post('/insert_notifications', 'UserController@insert_notifications');
    Route::get('/menu_delete/{id}', 'UserController@menu_delete')->name('menu.menu_delete');
    Route::get('/submenu_delete/{id}', 'UserController@submenu_delete')->name('menu.submenu_delete');

});
//End Companies

//Start Finance
Route::group(['prefix' => 'finance', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::resource('bank', 'BankController');
    // Route::get('/viewBankEditForm', 'BankController@viewBankEditForm');
    Route::resource('bankFacility', 'BankFacilityController');
    Route::resource('facilityRequest', 'FacilityRequestController');
    Route::get('/paymentVoucherReturnList', 'FinanceController@paymentVoucherReturnList');
    Route::get('/flow_statement_page', 'FinanceController@flow_statement_page');
    Route::get('/f', 'FinanceController@toDayActivity');
    Route::get('/ccoa', 'FinanceController@ccoa');
    Route::get('/createAccountForm', 'FinanceController@createAccountForm');
    Route::get('/viewChartofAccountList', 'FinanceController@viewChartofAccountList');
    Route::get('/viewChartofAccountListTwo', 'FinanceController@viewChartofAccountListTwo');
    Route::get('/create_new_pv', 'FinanceController@create_new_pv');
    Route::get('/edit_new_pv/{id?}', 'FinanceController@edit_new_pv');
    Route::get('/new_pv_list', 'FinanceController@new_pv_list');
    Route::get('/view_new_pv_detail', 'FinanceController@view_new_pv_detail');
    Route::get('/usersList', 'FinanceController@usersList');
    Route::get('/filter_user_list', 'FinanceController@filter_user_list');
    Route::get('/update_user_password', 'FinanceController@update_user_password');
    Route::get('/commission', 'FinanceController@commission');
    Route::get('/get_commision_data', 'FinanceController@get_commision_data');
    Route::get('/set_opening', 'FinanceController@set_opening');
    Route::get('/set_opening_stock', 'FinanceController@set_opening_stock');
    Route::get('/set_remining_stp', 'FinanceController@set_remining_stp');
    Route::get('/add_pi', 'FinanceController@add_pi');

    Route::get('/sales_on_finance', 'FinanceController@sales_on_finance');
    Route::get('/trial_balance_other_format', 'FinanceController@trial_balance_other_format');

    Route::get('/activeInActiveUser', 'FinanceController@activeInActiveUser');



    Route::get('/viewTaxSectionList', 'FinanceController@viewTaxSectionList');
    Route::get('/viewJvsAllocation', 'FinanceController@viewJvsAllocation');
    Route::get('/viewTrialBalance', 'FinanceController@viewTrialBalance');
    Route::get('/viewCashFlow', 'FinanceController@viewCashFlow');
    Route::get('/viewCashFlowReport', 'FinanceController@viewCashFlowReport');
    Route::post('/addCashFlowHeadInTransaction', 'FinanceController@addCashFlowHeadInTransaction')->name('finance.addCashFlowHeadInTransaction');
    Route::get('/bankReconciliationForm', 'FinanceController@bankReconciliationForm');
    Route::get('/getLastDateByAccount', 'FinanceController@getLastDateByAccount');
    Route::get('/bankReconciliationView', 'FinanceController@bankReconciliationView');
    Route::get('/bankReconciliationViewData', 'FinanceController@bankReconciliationViewData')->name('bankReconciliationViewData');
    Route::post('/saveBankReconciliationForm', 'FinanceController@saveBankReconciliationForm')->name('saveBankReconciliationForm');
    Route::get('/viewBalanceSheet', 'FinanceController@viewBalanceSheet');
    Route::get('/viewBalanceSheet_old', 'FinanceController@viewBalanceSheet_old');
    Route::get('/viewBalanceSheetCopy', 'FinanceController@viewBalanceSheetCopy');
    Route::get('/trialBalanceReportPage', 'FinanceController@trialBalanceReportPage');


    Route::get('/viewIncomeStatement', 'FinanceController@viewIncomeStatement');
    Route::get('/supplierSummaryReport', 'FinanceController@supplierSummaryReport');
    Route::get('/receivableSummaryReport', 'FinanceController@receivableSummaryReport');
    Route::get('/employeeSummaryReport', 'FinanceController@employeeSummaryReport');
    Route::get('/general_general', 'FinanceController@general_general');




    Route::get('/addTaxSectionForm', 'FinanceController@addTaxSectionForm');
    Route::get('/pv_detail_show', 'FinanceController@pv_detail_show');

    //amir

    Route::get('/createDepartmentForm', 'FinanceController@createDepartmentForm');
    Route::get('/viewDepartmentList', 'FinanceController@viewDepartmentList');
    Route::get('/createCostCenterForm', 'FinanceController@createCostCenterForm');
    Route::get('/viewCostCenterList', 'FinanceController@viewCostCenterList');
    //end amir
    Route::post('/ccoa_detail', 'FinanceController@ccoa_detail');


    Route::get('/createJournalVoucherForm', 'FinanceController@createJournalVoucherForm');
    Route::get('/createJournalVoucherNew', 'FinanceController@createJournalVoucherNew');
    Route::get('/UploadJournalVoucherView', 'FinanceController@UploadJournalVoucherView');

    Route::get('/viewGeneralJournalVouchers', 'FinanceController@viewGeneralJournalVouchers');

    Route::get('/viewJournalVoucherList', 'FinanceController@viewJournalVoucherList');
    Route::get('/viewJournalVoucherNew', 'FinanceController@viewJournalVoucherNew');
    Route::get('/PurchaseVoucherList', 'FinanceController@PurchaseVoucherList');
    // Route::get('/PurchaseVoucherList','FinanceController@PurchaseVoucherList');

    Route::get('/purchaseVoucherListt', 'FinanceController@purchaseVoucherListt');

    Route::get('/editJournalVoucherForm', 'FinanceController@editJournalVoucherForm');

    Route::get('/createCashPaymentVoucherForm', 'FinanceController@createCashPaymentVoucherForm');
    Route::get('/viewCashPaymentVoucherList', 'FinanceController@viewCashPaymentVoucherList');
    Route::get('/PaymentVoucherList', 'FinanceController@PaymentVoucherList');
    Route::get('/WithHoldingTax', 'FinanceController@WithHoldingTax');
    Route::get('/PaymentVoucherCheque', 'FinanceController@PaymentVoucherCheque')->name('PaymentVoucherCheque');
    Route::get('/paymentVoucherListImport', 'FinanceController@paymentVoucherListImport');

    Route::get('/editCashPaymentVoucherForm', 'FinanceController@editCashPaymentVoucherForm');


    Route::get('/createBankPaymentVoucherForm', 'FinanceController@createBankPaymentVoucherForm');
    Route::get('/createContraVoucher', 'FinanceController@createContraVoucher');

    //amir
    Route::post('/createPaymentForOutstanding/{id?}', 'FinanceController@createPaymentForOutstanding');
    Route::post('/CreatePayment_through_jvs/{id?}', 'FinanceController@CreatePayment_through_jvs');

    //end amir
    Route::get('/viewBankPaymentVoucherList', 'FinanceController@viewBankPaymentVoucherList');
    Route::get('/viewBankPaymentNewVoucherList', 'FinanceController@viewBankPaymentNewVoucherList');

    Route::get('/editBankPaymentVoucherForm', 'FinanceController@editBankPaymentVoucherForm');
    Route::get('/editContraVoucher/{id?}', 'FinanceController@editContraVoucher');
    Route::get('/viewContraVoucherList', 'FinanceController@viewContraVoucherList');

    Route::get('/createCashReceiptVoucherForm', 'FinanceController@createCashReceiptVoucherForm');
    Route::get('/viewCashReceiptVoucherList', 'FinanceController@viewCashReceiptVoucherList');
    Route::get('/editCashReceiptVoucherForm', 'FinanceController@editCashReceiptVoucherForm');
    //amir
    Route::get('/editCashPaymentVoucherForm/{id?}', 'FinanceController@editCashPaymentVoucherForm');
    Route::get('/editCashPVForm/{id?}', 'FinanceController@editCashPVForm');
    Route::get('/editBankPaymentNew/{id?}', 'FinanceController@editBankPaymentNew');
    Route::get('/editPurchaseVoucherFormNew/{id?}', 'FinanceController@editPurchaseVoucherFormNew');
    Route::get('/editDirectPurchaseVoucherForm/{id?}', 'FinanceController@editDirectPurchaseVoucherForm');
    Route::get('/editJournalVoucherForm/{id?}', 'FinanceController@editJournalVoucherForm');
    Route::get('/editBankRv/{id?}', 'FinanceController@editBankRv');
    Route::get('/editCashRv/{id?}', 'FinanceController@editCashRv');
    Route::get('/editJv/{id?}', 'FinanceController@editJv');


    //ABDUL
    //Route::get('/editBankPaymentVoucherForm','FinanceController@editBankPaymentVoucherForm');
    Route::get('/editBankPaymentVoucherForm/{id?}', 'FinanceController@editBankPaymentVoucherForm');

    Route::get('/createBankReceiptVoucherForm', 'FinanceController@createBankReceiptVoucherForm');
    Route::get('/createBankRvNew', 'FinanceController@createBankRvNew');
    Route::get('/viewBankRvNew', 'FinanceController@viewBankRvNew');
    Route::get('/createCashRvNew', 'FinanceController@createCashRvNew');
    Route::get('/viewCashRvNew', 'FinanceController@viewCashRvNew');
    Route::get('/paidToExpenseReport', 'FinanceController@paidToExpenseReport');
    Route::get('/auditTrialReport', 'FinanceController@auditTrialReport');



    Route::get('/createBankPaymentNew', 'FinanceController@createBankPaymentNew');
    Route::get('/viewBankReceiptVoucherList', 'FinanceController@viewBankReceiptVoucherList');
    Route::get('/editBankReceiptVoucherForm/{id}', 'FinanceController@editBankReceiptVoucherForm');



    Route::get('/viewLedgerReport', 'FinanceController@viewLedgerReport');
    Route::get('/viewTrialBalanceReportAnotherPage', 'FinanceController@viewTrialBalanceReportAnotherPage');

    Route::get('/createPurchaseCashPaymentVoucherForm', 'FinanceController@createPurchaseCashPaymentVoucherForm');
    Route::get('/viewPurchaseCashPaymentVoucherList', 'FinanceController@viewPurchaseCashPaymentVoucherList');

    Route::get('/createPurchaseBankPaymentVoucherForm', 'FinanceController@createPurchaseBankPaymentVoucherForm');
    Route::get('/viewPurchaseBankPaymentVoucherList', 'FinanceController@viewPurchaseBankPaymentVoucherList');

    Route::get('/createSaleCashReceiptVoucherForm', 'FinanceController@createSaleCashReceiptVoucherForm');
    Route::get('/viewSaleCashReceiptVoucherList', 'FinanceController@viewSaleCashReceiptVoucherList');

    Route::get('/createSaleBankReceiptVoucherForm', 'FinanceController@createSaleBankReceiptVoucherForm');
    Route::get('/viewSaleBankReceiptVoucherList', 'FinanceController@viewSaleBankReceiptVoucherList');

    Route::get('/viewPurchaseJournalVoucherList', 'FinanceController@viewPurchaseJournalVoucherList');
    Route::get('/viewSaleJournalVoucherList', 'FinanceController@viewSaleJournalVoucherList');


    Route::get('/createEmployeeTaxForm', 'FinanceController@createEmployeeTaxForm');
    Route::get('/viewEmployeeTaxList', 'FinanceController@viewEmployeeTaxList');
    Route::get('/editEmployeeTaxDetailForm', 'FinanceController@editEmployeeTaxDetailForm');


    Route::get('/createEmployeeEOBIForm', 'FinanceController@createEmployeeEOBIForm');
    Route::get('/viewEmployeeEOBIList', 'FinanceController@viewEmployeeEOBIList');
    Route::get('/editEmployeeEOBIDetailForm', 'FinanceController@editEmployeeEOBIDetailForm');

    //amir
    Route::get('/viewPurchaseVoucherList', 'FinanceController@viewPurchaseVoucherList');
    Route::get('/payable_reports', 'FinanceController@payable_reports');

    Route::get('/viewOutstanding_bills_through_jvs', 'FinanceController@viewOutstanding_bills_through_jvs');
    //for sales receipt voucher
    Route::post('/CreateReceiptVoucherForSales/{id?}', 'FinanceController@CreateReceiptVoucherForSales');

    Route::get('/viewBookDay', 'FinanceController@viewBookDay');
    //end amir
    Route::get('/createPurchaseVoucherForm', 'FinanceController@createPurchaseVoucherForm');
    Route::get('/paidToCreateAndView', 'FinanceController@paidToCreateAndView');
    Route::get('/getDatabase', 'FinanceController@getDatabase');
    Route::get('/expenseVoucherForm', 'FinanceController@expenseVoucherForm');
    Route::get('/expenseVoucherList', 'FinanceController@expenseVoucherList');
    Route::get('/createOpeningPage', 'FinanceController@createOpeningPage');
    Route::get('/addPaymentVoucherAgainstPO', 'FinanceController@addPaymentVoucherAgainstPO');

});

Route::group(['prefix' => 'fad', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::post('/addPurchasePaymentVoucherDetail', 'FinanceAddDetailControler@addPurchasePaymentVoucherDetail');

    Route::post('/uploadAccountDetail', 'FinanceAddDetailControler@uploadAccountDetail');
    Route::post('/addAccountDetail', 'FinanceAddDetailControler@addAccountDetail');
    Route::post('/pos_payment', 'FinanceAddDetailControler@pos_payment');
    Route::post('/commision_form', 'FinanceAddDetailControler@commision_form');



    Route::post('/addTaxSectionDetail', 'FinanceAddDetailControler@addTaxSectionDetail');
    Route::post('/addPaidTo', 'FinanceAddDetailControler@addPaidTo');
    Route::post('/add_role', 'FinanceAddDetailControler@add_role');
    Route::post('/addSalesReceipt', 'FinanceAddDetailControler@addSalesReceipt');
    Route::post('/addJournalVoucherDetail', 'FinanceAddDetailControler@addJournalVoucherDetail');
    Route::post('/updateJournalVoucherDetail', 'FinanceEditDetailControler@updateJournalVoucherDetail');
    //amir
    Route::post('/addDepartmentForm', 'FinanceAddDetailControler@addDepartmentForm');
    Route::post('/addCostCenterForm', 'FinanceAddDetailControler@addCostCenterForm');
    Route::post('/editAccountDetail/{id?}', 'FinanceEditDetailControler@editAccountDetail');
    Route::post('/editCostCenterForm/{id?}', 'FinanceEditDetailControler@editCostCenterForm');

    //end amir


    Route::post('/editJournalPendingVoucherDetail', 'FinanceEditDetailControler@editJournalPendingVoucherDetail');
    Route::post('/editJournalApproveVoucherDetail', 'FinanceEditDetailControler@editJournalApproveVoucherDetail');

    Route::post('/addCashPaymentVoucherDetail', 'FinanceAddDetailControler@addCashPaymentVoucherDetail');
    Route::post('/editCashPaymentPendingVoucherDetail', 'FinanceEditDetailControler@editCashPaymentPendingVoucherDetail');
    Route::post('/editCashPaymentApproveVoucherDetail', 'FinanceEditDetailControler@editCashPaymentApproveVoucherDetail');
    Route::post('/editCashPaymentVoucherDetail', 'FinanceEditDetailControler@editCashPaymentVoucherDetail');

    Route::post('/addBankPaymentVoucherDetail', 'FinanceAddDetailControler@addBankPaymentVoucherDetail');
    Route::post('/updateBankPaymentVoucherDetail', 'FinanceEditDetailControler@updateBankPaymentVoucherDetail');
    Route::post('/addBankPaymentVoucherDetail_through_jvs', 'FinanceAddDetailControler@addBankPaymentVoucherDetail_through_jvs');
    Route::post('/editBankPaymentPendingVoucherDetail', 'FinanceEditDetailControler@editBankPaymentPendingVoucherDetail');
    Route::post('/editBankPaymentApproveVoucherDetail', 'FinanceEditDetailControler@editBankPaymentApproveVoucherDetail');


    Route::post('/addCashReceiptVoucherDetail', 'FinanceAddDetailControler@addCashReceiptVoucherDetail');
    Route::post('/editCashReceiptPendingVoucherDetail', 'FinanceEditDetailControler@editCashReceiptPendingVoucherDetail');
    Route::post('/editCashReceiptApproveVoucherDetail', 'FinanceEditDetailControler@editCashReceiptApproveVoucherDetail');

    Route::post('/addBankReceiptVoucherDetail', 'FinanceAddDetailControler@addBankReceiptVoucherDetail');
    Route::post('/addBankReceiptVoucherDetail_against_sales', 'FinanceAddDetailControler@addBankReceiptVoucherDetail_against_sales');
    Route::post('/updateBankReceiptVoucherDetail_against_sales', 'FinanceAddDetailControler@updateBankReceiptVoucherDetail_against_sales');
    Route::post('/addContraVoucherDetail', 'FinanceAddDetailControler@addContraVoucherDetail');
    Route::post('/updateContraVoucherDetail', 'FinanceEditDetailControler@updateContraVoucherDetail');

    Route::post('/editBankReceiptVoucherForm', 'FinanceEditDetailControler@editBankReceiptVoucherForm');
    Route::post('/editBankReceiptPendingVoucherDetail', 'FinanceEditDetailControler@editBankReceiptPendingVoucherDetail');
    Route::post('/editBankReceiptApproveVoucherDetail', 'FinanceEditDetailControler@editBankReceiptApproveVoucherDetail');

    Route::post('/addPurchaseCashPaymentVoucherDetail', 'FinanceAddDetailControler@addPurchaseCashPaymentVoucherDetail');
    Route::post('/addPurchaseBankPaymentVoucherDetail', 'FinanceAddDetailControler@addPurchaseBankPaymentVoucherDetail');

    Route::post('/addSaleCashReceiptVoucherDetail', 'FinanceAddDetailControler@addSaleCashReceiptVoucherDetail');
    Route::post('/addSaleBankReceiptVoucherDetail', 'FinanceAddDetailControler@addSaleBankReceiptVoucherDetail');

    Route::post('/addEmployeeTaxDetail', 'FinanceAddDetailControler@addEmployeeTaxDetail');

    Route::post('/addEmployeeEOBIDetail', 'FinanceAddDetailControler@addEmployeeEOBIDetail');
    Route::post('/addPaymentVoucherDetail', 'FinanceAddDetailControler@addPaymentVoucherDetail');
    Route::post('/updatePurchaseVoucher', 'FinanceAddDetailControler@updatePurchaseVoucher');
    Route::post('/addExpenseVoucherDetail', 'FinanceAddDetailControler@addExpenseVoucherDetail');

});


Route::group(['prefix' => 'fmfal', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/makeFormJournalVoucher', 'FinanceMakeFormAjaxLoadController@makeFormJournalVoucher');
    Route::get('/addMoreJournalDetailRows', 'FinanceMakeFormAjaxLoadController@addMoreJournalDetailRows');
    Route::get('/addJournalVoucherDetailRows_costing', 'FinanceMakeFormAjaxLoadController@addJournalVoucherDetailRows_costing');
    Route::get('/get_current_amount', 'FinanceMakeFormAjaxLoadController@get_current_amount');

    Route::get('/makeFormCashPaymentVoucher', 'FinanceMakeFormAjaxLoadController@makeFormCashPaymentVoucher');
    Route::get('/addMoreCashPvsDetailRows', 'FinanceMakeFormAjaxLoadController@addMoreCashPvsDetailRows');

    Route::get('/makeFormBankPaymentVoucher', 'FinanceMakeFormAjaxLoadController@makeFormBankPaymentVoucher');
    Route::get('/addMoreBankPvsDetailRows', 'FinanceMakeFormAjaxLoadController@addMoreBankPvsDetailRows');
    Route::get('/addMoreBankPvsDetailRows_costing', 'FinanceMakeFormAjaxLoadController@addMoreBankPvsDetailRows_costing');

    Route::get('/makeFormCashReceiptVoucher', 'FinanceMakeFormAjaxLoadController@makeFormCashReceiptVoucher');
    Route::get('/addMoreCashRvsDetailRows', 'FinanceMakeFormAjaxLoadController@addMoreCashRvsDetailRows');

    Route::get('/makeFormBankReceiptVoucher', 'FinanceMakeFormAjaxLoadController@makeFormBankReceiptVoucher');
    Route::get('/addMoreBankRvsDetailRows', 'FinanceMakeFormAjaxLoadController@addMoreBankRvsDetailRows');

    Route::get('/loadPurchaseCashPaymentVoucherDetailByGRNNo', 'FinanceMakeFormAjaxLoadController@loadPurchaseCashPaymentVoucherDetailByGRNNo');
    Route::get('/loadPurchaseBankPaymentVoucherDetailByGRNNo', 'FinanceMakeFormAjaxLoadController@loadPurchaseBankPaymentVoucherDetailByGRNNo');

    Route::get('/loadSaleCashReceiptVoucherDetailByInvoiceNo', 'FinanceMakeFormAjaxLoadController@loadSaleCashReceiptVoucherDetailByInvoiceNo');
    Route::get('/loadSaleBankReceiptVoucherDetailByInvoiceNo', 'FinanceMakeFormAjaxLoadController@loadSaleBankReceiptVoucherDetailByInvoiceNo');
    Route::get('/getBranchClientWise', 'FinanceMakeFormAjaxLoadController@getBranchClientWise');
    Route::get('/getRegionClusterWise', 'FinanceMakeFormAjaxLoadController@getRegionClusterWise');

    Route::get('/getBranchClientWiseSingle', 'FinanceMakeFormAjaxLoadController@getBranchClientWiseSingle');
    Route::get('/getBranchClientWiseLedger', 'FinanceMakeFormAjaxLoadController@getBranchClientWiseLedger');
    Route::get('/getAccount', 'FinanceMakeFormAjaxLoadController@getAccount');
    Route::get('/getEmpOrPaidToData', 'FinanceMakeFormAjaxLoadController@getEmpOrPaidToData');
    Route::get('/loadPurchaseBankPaymentVoucherDetailByPONo', 'FinanceMakeFormAjaxLoadController@loadPurchaseBankPaymentVoucherDetailByPONo');
    Route::get('/addMorePurchaseBankPvsDetailRows', 'FinanceMakeFormAjaxLoadController@addMorePurchaseBankPvsDetailRows');



});
Route::group(['prefix' => 'fdc', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/viewJournalVoucherDetail', 'FinanceDataCallController@viewJournalVoucherDetail');
    Route::get('/viewJournalVoucherDetailPrint', 'FinanceDataCallController@viewJournalVoucherDetailPrint');
    Route::get('/viewBankRvDetailNew', 'FinanceDataCallController@viewBankRvDetailNew');
    Route::get('/viewBankRvDetailNewPrint', 'FinanceDataCallController@viewBankRvDetailNewPrint');

    Route::get('/viewCashRvDetailNew', 'FinanceDataCallController@viewCashRvDetailNew');
    Route::get('/viewCashRvDetailNewPrint', 'FinanceDataCallController@viewCashRvDetailNewPrint');

    Route::get('/vendor_summery', 'FinanceDataCallController@vendor_summery');
    Route::get('/vendor_summery_two', 'FinanceDataCallController@vendor_summery_two');
    Route::get('/getPayAble', 'FinanceDataCallController@getPayAble');
    Route::get('/get_rights', 'FinanceDataCallController@get_rights');
    Route::get('/com_delete', 'FinanceDataCallController@com_delete');
    Route::get('/trial_balance_other_format', 'FinanceDataCallController@trial_balance_other_format');

    Route::get('/receivablSummaryReport', 'FinanceDataCallController@receivablSummaryReport');
    Route::get('/employeeSummaryReport', 'FinanceDataCallController@employeeSummaryReport');


    Route::get('/viewPurchaseVoucherDetail', 'FinanceDataCallController@viewPurchaseVoucherDetail');
    Route::get('/viewDirectPurchaseVoucherDetail', 'FinanceDataCallController@viewDirectPurchaseVoucherDetail');
    Route::get('/viewCashPaymentVoucherDetail', 'FinanceDataCallController@viewCashPaymentVoucherDetail');
    Route::get('/viewBankPaymentVoucherDetail', 'FinanceDataCallController@viewBankPaymentVoucherDetail');
    Route::get('/viewBankPaymentVoucherDetailPrint', 'FinanceDataCallController@viewBankPaymentVoucherDetailPrint');

    Route::get('/viewExpenseVoucherDetail', 'FinanceDataCallController@viewExpenseVoucherDetail');
    Route::get('/viewBankPaymentVoucherDetailInDetail', 'FinanceDataCallController@viewBankPaymentVoucherDetailInDetail');
    Route::get('/viewBankPaymentVoucherDetailInDetailImport', 'FinanceDataCallController@viewBankPaymentVoucherDetailInDetailImport');

    Route::get('/viewBankPaymentVoucherDetailInDetailPrint', 'FinanceDataCallController@viewBankPaymentVoucherDetailInDetailPrint');

    Route::get('/viewBankPaymentVoucherDetailInDetailDirect', 'FinanceDataCallController@viewBankPaymentVoucherDetailInDetailDirect');





    Route::get('/viewCashReceiptVoucherDetail', 'FinanceDataCallController@viewCashReceiptVoucherDetail');
    Route::get('/viewBankReceiptVoucherDetail', 'FinanceDataCallController@viewBankReceiptVoucherDetail');
    Route::get('/viewContraVoucherDetail', 'FinanceDataCallController@viewContraVoucherDetail');
    Route::get('/get_new_pvs_list_ajax', 'FinanceDataCallController@get_new_pvs_list_ajax');

    //amir fdc
    Route::get('/createAccountFormAjax/{id?}/{PageName?}', 'FinanceDataCallController@createAccountFormAjax');
    Route::get('/editChartOfAccountForm/{id?}', 'FinanceDataCallController@editChartOfAccountForm');
    Route::post('/addChartOfAccount', 'FinanceDataCallController@addChartOfAccount');
    Route::get('/editCostCenterForm/{id?}', 'FinanceDataCallController@editCostCenterForm');

    Route::get('/filterJournalVoucherList', 'FinanceDataCallController@filterJournalVoucherList');
    Route::get('/tax_calculation', 'FinanceDataCallController@tax_calculation');
    Route::get('/fbr_tax_calculation', 'FinanceDataCallController@fbr_tax_calculation');
    Route::get('/pra_tax_calculation', 'FinanceDataCallController@pra_tax_calculation');
    Route::get('/srb_tax_calculation', 'FinanceDataCallController@srb_tax_calculation');
    Route::get('/income_tax_calculation', 'FinanceDataCallController@income_tax_calculation');

    //Show All Taxes Route
    Route::get('/showIncomeTaxWithholding', 'FinanceDataCallController@showIncomeTaxWithholding');
    Route::get('/showFbrSalesTaxWithholding', 'FinanceDataCallController@showFbrSalesTaxWithholding');
    Route::get('/showSrbSindhRevenue', 'FinanceDataCallController@showSrbSindhRevenue');
    Route::get('/showPunjabSalesTaxWithholding', 'FinanceDataCallController@showPunjabSalesTaxWithholding');
    Route::get('/showTaxesData', 'FinanceDataCallController@showTaxesData');
    Route::get('/ShowDetailData', 'FinanceDataCallController@ShowDetailData');


    Route::get('/filterCashPaymentVoucherList', 'FinanceDataCallController@filterCashPaymentVoucherList');
    Route::get('/filterBankPaymentVoucherList', 'FinanceDataCallController@filterBankPaymentVoucherList');
    Route::get('/filterCashReceiptVoucherList', 'FinanceDataCallController@filterCashReceiptVoucherList');
    Route::get('/filterBankReceiptVoucherList', 'FinanceDataCallController@filterBankReceiptVoucherList');
    Route::get('/loadFilterLedgerReport', 'FinanceDataCallController@loadFilterLedgerReport');
    Route::get('/paidToExpenseReport', 'FinanceDataCallController@paidToExpenseReport');
    Route::get('/AuditTrialReport', 'FinanceDataCallController@AuditTrialReport');


    Route::get('/filterContraVoucherList', 'FinanceDataCallController@filterContraVoucherList');
    Route::get('/filterPurchaseCashPaymentVoucherList', 'FinanceDataCallController@filterPurchaseCashPaymentVoucherList');
    Route::get('/filterPurchaseBankPaymentVoucherList', 'FinanceDataCallController@filterPurchaseBankPaymentVoucherList');
    Route::get('/filterBookDayList', 'FinanceDataCallController@filterBookDayList');


    Route::get('/filterSaleCashReceiptVoucherList', 'FinanceDataCallController@filterSaleCashReceiptVoucherList');
    Route::get('/filterSaleBankReceiptVoucherList', 'FinanceDataCallController@filterSaleBankReceiptVoucherList');

    Route::get('/filterPurchaseJournalVoucherList', 'FinanceDataCallController@filterPurchaseJournalVoucherList');
    Route::get('/filterSaleJournalVoucherList', 'FinanceDataCallController@filterSaleJournalVoucherList');
    Route::get('/getJvsDateAndAccontWise', 'FinanceDataCallController@getJvsDateAndAccontWise');
    Route::get('/getGJVDateAndAccontWise', 'FinanceDataCallController@getGJVDateAndAccontWise');
    //    For Sales Start
    Route::get('/getRvsDateAndAccontWiseForSales', 'FinanceDataCallController@getRvsDateAndAccontWiseForSales');
    //    For Sales End
    Route::get('/getBpvsDateAndAccontWise', 'FinanceDataCallController@getBpvsDateAndAccontWise');
    Route::get('/getCpvsDateAndAccontWise', 'FinanceDataCallController@getCpvsDateAndAccontWise');
    Route::get('/getCrvsDateAndAccontWise', 'FinanceDataCallController@getCrvsDateAndAccontWise');
    Route::get('/getBrvsDateAndAccontWise', 'FinanceDataCallController@getBrvsDateAndAccontWise');


    Route::get('/getOutstandingpvsDateAndAccontWise', 'FinanceDataCallController@getOutstandingpvsDateAndAccontWise');
    Route::get('/getOutstandingpvsDateAndAccontWiseImport', 'FinanceDataCallController@getOutstandingpvsDateAndAccontWiseImport');

    Route::get('/getprvsDateAndAccontWise', 'FinanceDataCallController@getprvsDateAndAccontWise');
    Route::get('/insertOpeningBalance', 'FinanceDataCallController@insertOpeningBalance');





    Route::get('/DeleteJvActivity', 'FinanceDataCallController@DeleteJvActivity');
    Route::get('/DeletePvActivity', 'FinanceDataCallController@DeletePvActivity');
    Route::get('/DeleteRvActivity', 'FinanceDataCallController@DeleteRvActivity');
    Route::get('/DeleteCvActivity', 'FinanceDataCallController@DeleteCvActivity');
    Route::get('/approvePurchaseVoucherDetail', 'FinanceDataCallController@approvePurchaseVoucherDetail');

    Route::get('/trialBalanceData', 'FinanceDataCallController@trialBalanceData');
    Route::get('/trialBalanceSheet', 'FinanceDataCallController@trialBalanceSheet');
    Route::get('/trialBalanceSheet_old', 'FinanceDataCallController@trialBalanceSheet_old');
    Route::get('/IncomeStatement', 'FinanceDataCallController@IncomeStatement');
    Route::get('/flow_statement_ajax', 'FinanceDataCallController@flow_statement_ajax');

    Route::get('/getDataSupplierWise', 'FinanceDataCallController@getDataSupplierWise');
    Route::get('/getSummaryLedgerDetail', 'FinanceDataCallController@getSummaryLedgerDetail');
    Route::get('/getTrialBalanceDataAjax', 'FinanceDataCallController@getTrialBalanceDataAjax');
    Route::get('/deleteNewPv', 'FinanceDataCallController@deleteNewPv');







});
//End Finance


//Start Purchase
Route::group(['prefix' => 'purchase', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    //amirmurshad
    Route::get('/edit_sub_ca', 'PusherController@edit_sub_ca');
    Route::get('/inventory_page', 'PurchaseController@inventory_page');
    Route::get('/purchase_page', 'PurchaseController@purchase_page');
    Route::get('/testReportPage', 'PurchaseController@testReportPage');

    Route::get('/sales_page', 'PurchaseController@sales_page');
    Route::get('/item_master_list', 'PusherController@item_master_list');


    Route::get('/editItemMaster/{id?}', 'PurchaseController@editItemMaster');

    Route::get('/p', 'PurchaseController@toDayActivity');
    Route::get('/getPoReportByPoNo', 'PurchaseController@getPoReportByPoNo');
    Route::get('/purchase_request_form', 'PurchaseController@purchase_request_form');
    Route::get('/purchaseDetailReportPage', 'PurchaseController@purchaseDetailReportPage');
    Route::get('/detailReportPage', 'PurchaseController@detailReportPage');
    Route::get('/purchaseInvoiceReportPage', 'PurchaseController@purchaseInvoiceReportPage');
    Route::get('/aqmsStockReportPage', 'PurchaseController@aqmsStockReportPage');
    Route::get('/vendor_outstanding', 'PurchaseController@vendor_outstanding');
    Route::get('/poTrackingPage', 'PurchaseController@poTrackingPage');
    Route::get('/deleteItemMaster', 'PurchaseController@deleteItemMaster');
    Route::get('/vendor_balance_page', 'PurchaseController@vendor_balance_page');
    Route::get('/add_another_data_page', 'PurchaseController@add_another_data_page');







    Route::get('/viewAgingReportPage', 'PurchaseController@viewAgingReportPage');

    Route::get('/in_stock_recon', 'PurchaseController@in_stock_recon');



    Route::get('/vendor_opening_list', 'PurchaseController@vendor_opening_list');
    Route::get('/vendor_opening', 'PurchaseController@vendor_opening');
    Route::get('/customer_opening', 'PurchaseController@customer_opening');
    Route::get('/vendor_report', 'PurchaseController@vendor_report');


    Route::get('/viewSupplierDetail', 'PurchaseController@viewSupplierDetail');

    Route::get('/deleteSupplierRecord', 'PurchaseDeleteController@deleteSupplierRecord');
    Route::get('/repostSupplierRecord', 'PurchaseDeleteController@repostSupplierRecord');

    //saquib
    //Supplier
    Route::get('/createSupplierForm', 'PurchaseController@createSupplierForm');
    Route::get('/viewSupplierList', 'PurchaseController@viewSupplierList');
    Route::get('/editSupplierForm/{id?}', 'PurchaseController@editSupplierForm');
    Route::get('/viewSupplierFormlist/{id}', 'PurchaseController@viewSupplierFormlist');

    // amir
    Route::get('/createPurchaseVoucherForm', 'PurchaseController@createPurchaseVoucherForm');
    Route::get('/createJobOrder', 'PurchaseController@createJobOrder');
    Route::get('/editJobOrder/{id?}/{duplicate?}', 'PurchaseController@editJobOrder');
    Route::get('/createProduct', 'PurchaseController@createProduct');
    Route::get('/addSurveyForm', 'PurchaseController@addSurveyForm');

    Route::get('/editSurvey/{id?}', 'PurchaseController@editSurvey');
    Route::get('/editGoodIssuance/{id?}', 'PurchaseController@editGoodIssuance');
    Route::get('/editStockReturn/{id?}', 'PurchaseController@editStockReturn');


    // opening stock
    Route::get('/opening_stock_report', 'PurchaseController@opening_stock_report');
    Route::get('/ItemWiseReport', 'PurchaseController@ItemWiseReport');

    // opening stock eend

    Route::post('/createPurchaseVoucherFormThroughGrn', 'PurchaseController@createPurchaseVoucherFormThroughGrn');
    Route::get('/editPurchaseVoucherForm/{id?}', 'PurchaseController@editPurchaseVoucherForm');

    Route::get('/createCategoryForm', 'PurchaseController@createCategoryForm');
    Route::get('/viewCategoryList', 'PurchaseController@viewCategoryList');
    Route::get('/add_item_master', 'PurchaseController@add_item_master');
    Route::get('/editItemMaster', 'PurchaseController@editItemMaster');

    Route::get('/viewItemMasterList', 'PurchaseController@viewItemMasterList');


    //Abdul
    Route::get('/createSubCategoryForm', 'PurchaseController@createSubCategoryForm');
    Route::get('/viewSubCategoryList', 'PurchaseController@viewSubCategoryList');
    //Abdul
    Route::get('/addRegionForm', 'PurchaseController@addRegionForm');
    Route::get('/regionList', 'PurchaseController@regionList');
    Route::get('/addCluster', 'PurchaseController@addCluster');
    Route::get('/clusterList', 'PurchaseController@clusterList');


    Route::get('/viewCategoryDetail', 'PurchaseController@viewCategoryDetail');
    Route::get('/editCategoryForm', 'PurchaseController@editCategoryForm');
    Route::get('/deleteCategoryRecord', 'PurchaseDeleteController@deleteCategoryRecord');
    Route::get('/repostCategoryRecord', 'PurchaseDeleteController@repostCategoryRecord');

    Route::get('/createSubItemForm', 'PurchaseController@createSubItemForm');
    Route::get('/viewSubItemList', 'PurchaseController@viewSubItemList');
    Route::get('/viewSubItemDetail', 'PurchaseController@viewSubItemDetail');
    Route::get('/editSubItemForm', 'PurchaseController@editSubItemForm');
    Route::get('/deleteSubItemRecord', 'PurchaseDeleteController@deleteSubItemRecord');
    Route::get('/repostSubItemRecord', 'PurchaseDeleteController@repostSubItemRecord');
    Route::resource('brands', 'BrandController');

    //amir
    Route::get('/createDemandTypeForm', 'PurchaseController@createDemandTypeForm');
    Route::get('/createWarehouseForm', 'PurchaseController@createWarehouseForm');
    //end

    Route::get('/createUOMForm', 'PurchaseController@createUOMForm');
    Route::get('/viewUOMList', 'PurchaseController@viewUOMList');

    Route::get('/createDemandForm', 'PurchaseController@createDemandForm');
    Route::get('/createDemandFormAgainstMr', 'PurchaseController@createDemandFormAgainstMr');

    Route::get('/viewDemandList', 'PurchaseController@viewDemandList');
    Route::get('/stockreturnlist', 'PurchaseController@stockreturnlist');

    //amir
    Route::get('/viewPurchaseVoucherList', 'PurchaseController@viewPurchaseVoucherList');
    Route::get('/viewJobOrder', 'PurchaseController@viewJobOrder');
    Route::get('/viewJobOrderTwo', 'PurchaseController@viewJobOrderTwo');

    Route::get('/viewProduct', 'PurchaseController@viewProduct');

    Route::get('/viewPurchaseVoucherListThroughGrn', 'PurchaseController@viewPurchaseVoucherListThroughGrn');
    Route::get('/viewPurchaseVoucherListThroughWithoutGrn', 'PurchaseController@viewPurchaseVoucherListThroughWithoutGrn');
    Route::get('/viewDemandTypeList', 'PurchaseController@viewDemandTypeList');
    Route::get('/viewWarehouseList', 'PurchaseController@viewWarehouseList');
    Route::get('/viewGrnListForPurchaseVoucher', 'PurchaseController@viewGrnListForPurchaseVoucher');

    //end amir
    Route::get('/editDemandVoucherForm/{id}', 'PurchaseController@editDemandVoucherForm');

    Route::get('/createGoodsReceiptNoteForm', 'PurchaseController@createGoodsReceiptNoteForm');
    Route::get('/viewGoodsReceiptNoteList', 'PurchaseController@viewGoodsReceiptNoteList');
    Route::get('/editGoodsReceiptNoteVoucherForm/{id}/{GrnNo}', 'PurchaseController@editGoodsReceiptNoteVoucherForm');
    Route::get('/editPurchaseReturnForm/{id}/{PrNo}', 'PurchaseController@editPurchaseReturnForm');



    Route::get('/editGoodsReceiptNoteWithoutPOForm/{id}', 'PurchaseController@editGoodsReceiptNoteWithoutPOForm');
    Route::get('/createGoodReceiptNoteForWithoutPO', 'PurchaseController@createGoodReceiptNoteForWithoutPO');



    Route::get('/createGoodsForwardForm', 'PurchaseController@createGoodsForwardForm');
    Route::get('/viewGoodsForwardList', 'PurchaseController@viewGoodsForwardList');
    Route::get('/editGoodsForwardForm', 'PurchaseController@editGoodsForwardForm');

    Route::get('/createGoodsForwardOrderForm', 'PurchaseController@createGoodsForwardOrderForm');
    Route::get('/viewGoodsForwardOrderList', 'PurchaseController@viewGoodsForwardOrderList');
    Route::get('/editGoodsForwardOrderForm', 'PurchaseController@editGoodsForwardOrderForm');
    Route::get('/purchaseReturnForm', 'PurchaseController@purchaseReturnForm');
    Route::get('/purchaseReturnList', 'PurchaseController@purchaseReturnList');
    Route::get('/createstockreturn', 'PurchaseController@createstockreturn');

    // estimate
    Route::get('/job_order_next_step', 'PurchaseController@job_order_next_step');
    Route::get('/job_order_next_step_edit', 'PurchaseController@job_order_next_step_edit');
    Route::get('/ShowAllImages/{id}', 'PurchaseController@ShowAllImages');
    Route::get('/editJobOrder/{id?}/{duplicate?}', 'PurchaseController@editJobOrder');
    Route::get('/directPurchaseOrderForm', 'PurchaseController@directPurchaseOrderForm');
    Route::get('/purchase_order_status', 'PurchaseController@purchase_order_status');
    Route::get('/poReportPage', 'PurchaseController@poReportPage');
    Route::get('/directPurchaseInvoice', 'PurchaseController@directPurchaseInvoice');
    Route::get('/addOpeningAgainstVendorForm', 'PurchaseController@addOpeningAgainstVendorForm');

    // QC for GRN Set Standards
    Route::get('/grnAddQcValues', 'PurchaseController@grnAddQcValues');
    Route::get('/grnQcValuesList', 'PurchaseController@grnQcValuesList')->name('grnQcValuesList');
    Route::get('/grnViewQcValuesDetail', 'PurchaseController@grnViewQcValuesDetail');
    Route::get('/grnEditQcValues/{id}', 'PurchaseController@grnEditQcValues')->name('purchase.grnEditQcValues');
    Route::get('/grnGetQcValueForm', 'GrnQaController@grnGetQcValueForm');
    Route::post('/grnStoreQcValues', 'PurchaseController@grnStoreQcValues')->name('grnStoreQcValues');
    Route::post('/grnUpdateQcValues', 'PurchaseController@grnUpdateQcValues')->name('grnUpdateQcValues');
    Route::post('/grnDeleteQcValue', 'PurchaseController@grnDeleteQcValue')->name('grnDeleteQcValue');


    // QC for GRN
    Route::get('QaGrn/', 'GrnQaController@index');
    Route::get('QaGrn/create', 'GrnQaController@create');
    Route::post('QaGrn/store', 'GrnQaController@store')->name('QaGrn.store');
    Route::post('QaGrn/storeTestResult', 'GrnQaController@storeTestResult')->name('QaGrn.storeTestResult');
    Route::get('QaGrn/edit/{id}', 'GrnQaController@edit')->name('QaGrn.edit');
    Route::get('QaGrn/show/{id}', 'GrnQaController@show')->name('QaGrn.show');
    Route::post('QaGrn/update/{id}', 'GrnQaController@update')->name('QaGrn.update');

    Route::get('QaGrn/delete', 'GrnQaController@grnDeleteQaPacking')->name('QaGrn.delete');
    Route::get('QaGrn/productionPlanAndCustomerAgainstSo', 'GrnQaController@productionPlanAndCustomerAgainstSo');
    Route::get('QaGrn/getGrnListNo', 'GrnQaController@getGrnListNo');
    Route::get('QaGrn/testingOnReceiveItem/{id}', 'GrnQaController@testingOnReceiveItem')->name('QaGrn.testingOnReceiveItem');
    Route::get('QaGrn/testResultOnReceiveItem/{id}', 'GrnQaController@testResultOnReceiveItem')->name('QaGrn.testResultOnReceiveItem');
    Route::get('QaGrn/testResultOnReceiveItemAjax/{id}', 'GrnQaController@testResultOnReceiveItemAjax')->name('QaGrn.testResultOnReceiveItemAjax');

    Route::get('QaGrn/getPackingListNoForDispatch', 'GrnQaController@getPackingListNoForDispatch');
    Route::get('QaGrn/grnViewQaGrnDetail', 'GrnQaController@grnViewQaGrnDetail');
    Route::get('QaGrn/getGrnDataItems', 'GrnQaController@getGrnDataItems');



});




Route::group(['prefix' => 'quotation', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('create_quotation', 'QuotationController@create_quotation');
    Route::get('create_quotation', 'QuotationController@create_quotation');
    Route::get('create_quotation_ajax', 'QuotationController@create_quotation_ajax');
    Route::get('edit_quotation/{pr_id}/{q_id}', 'QuotationController@edit_quotation');
    Route::get('quotation_form/{id}', 'QuotationController@quotation_form');
    Route::post('insert_quotation', 'QuotationController@insert_quotation');
    Route::get('quotation_list', 'QuotationController@quotation_list');
    Route::get('quotation_list_ajax', 'QuotationController@quotation_list_ajax');
    Route::get('view_quotation', 'QuotationController@view_quotation');
    Route::get('qutation_summary', 'QuotationController@qutation_summary');
    Route::get('approve', 'QuotationController@approve');
    Route::get('approved_quotation_summary', 'QuotationController@approved_quotation_summary');
    Route::post('update_quotation/{id}', 'QuotationController@update_quotation');
    Route::get('delete_quotation', 'QuotationController@delete_quotation');
});


Route::group(['prefix' => 'pad', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::post('/uploadSupplier', 'PurchaseAddDetailControler@uploadSupplier');
    Route::post('/uploadSubItems', 'PurchaseAddDetailControler@uploadSubItems');

    Route::post('/import_vendor_opening', 'PurchaseAddDetailControler@import_vendor_opening');
    Route::post('/addSupplierDetail', 'PurchaseAddDetailControler@addSupplierDetail');

    Route::post('/edit_sub', 'PurchaseAddDetailControler@edit_sub');

    Route::post('/editSupplierDetail', 'PurchaseEditDetailControler@editSupplierDetail');
    Route::post('/inser_item_master', 'PurchaseAddDetailControler@inser_item_master');
    Route::post('/update_item_master', 'PurchaseAddDetailControler@update_item_master');



    Route::post('/addCategoryDetail', 'PurchaseAddDetailControler@addCategoryDetail');
    Route::post('/editCategoryDetail', 'PurchaseEditDetailControler@editCategoryDetail');

    //ABdul
    Route::post('/addSubCategoryDetail', 'PurchaseAddDetailControler@addSubCategoryDetail');
    //ABdul

    Route::post('/addRegionDetail', 'PurchaseAddDetailControler@addRegionDetail');
    Route::post('/insertCluster', 'PurchaseAddDetailControler@insertCluster');

    Route::post('/addSubItemDetail', 'PurchaseAddDetailControler@addSubItemDetail');
    Route::post('/editSubItemDetail', 'PurchaseEditDetailControler@editSubItemDetail');

    Route::post('/addUOMDetail', 'PurchaseAddDetailControler@addUOMDetail');
    Route::post('/editUOMDetail', 'PurchaseEditDetailControler@editUOMDetail');

    Route::post('/addDemandDetail', 'PurchaseAddDetailControler@addDemandDetail');
    Route::post('/updateDemandDetail', 'PurchaseAddDetailControler@updateDemandDetail');

    Route::post('/addIssuanceDetail', 'PurchaseAddDetailControler@addIssuanceDetail');
    Route::post('/updateIssuanceDetail', 'PurchaseAddDetailControler@updateIssuanceDetail');
    Route::post('/updateRegionDetail', 'PurchaseAddDetailControler@updateRegionDetail');

    Route::post('/UpdateStockReturnDetail', 'PurchaseAddDetailControler@UpdateStockReturnDetail');
    Route::post('/addStockReturnDetail', 'PurchaseAddDetailControler@addStockReturnDetail');
    //amir


    Route::post('/addJobOrder', 'PurchaseAddDetailControler@addJobOrder');
    Route::post('/updateJobOrderDetail', 'PurchaseAddDetailControler@updateJobOrderDetail');



    Route::post('/addJobOrderNextStep', 'PurchaseAddDetailControler@addJobOrderNextStep');
    Route::post('/addJobOrderNextStepUpdate', 'PurchaseAddDetailControler@addJobOrderNextStepUpdate');

    Route::post('/addProduct', 'PurchaseAddDetailControler@addProduct');
    Route::post('/addSurveyDetail', 'PurchaseAddDetailControler@addSurveyDetail');
    Route::post('/updateSurveyDetail', 'PurchaseAddDetailControler@updateSurveyDetail');

    Route::post('/addJobTrackingDetails', 'PurchaseAddDetailControler@addJobTrackingDetails');

    Route::post('/createPurchaseVoucher', 'PurchaseAddDetailControler@createPurchaseVoucher');
    Route::post('/addPurchaseVoucherThorughGrn', 'PurchaseAddDetailControler@addPurchaseVoucherThorughGrn');
    Route::post('/editPurchaseVoucher/{id}', 'PurchaseEditDetailControler@editPurchaseVoucher');
    Route::post('/addDemandTypeDetail', 'PurchaseAddDetailControler@addDemandTypeDetail');
    Route::post('/addDirectGrnForm', 'PurchaseAddDetailControler@addDirectGrnForm');
    Route::post('/UpdateDirectGrnForm', 'PurchaseAddDetailControler@UpdateDirectGrnForm');
    Route::post('/addWareHouseDetail', 'PurchaseAddDetailControler@addWareHouseDetail');
    // end amir
    Route::post('/editDemandVoucherDetail', 'PurchaseEditDetailControler@editDemandVoucherDetail');
    Route::post('/updateDemandDetailandApprove', 'PurchaseEditDetailControler@updateDemandDetailandApprove');

    Route::post('/addGoodsReceiptNoteDetail', 'PurchaseAddDetailControler@addGoodsReceiptNoteDetail');
    Route::post('/addPurchaseReturnDetail', 'PurchaseAddDetailControler@addPurchaseReturnDetail');

    Route::post('/editGoodsReceiptNoteDetail', 'PurchaseEditDetailControler@editGoodsReceiptNoteDetail');
    Route::post('/createStoreChallanandApproveGoodsReceiptNote', 'PurchaseAddDetailControler@createStoreChallanandApproveGoodsReceiptNote');

    Route::post('/addGoodsForwardDetail', 'PurchaseAddDetailControler@addGoodsForwardDetail');

    Route::post('/addGoodsForwardOrderDetail', 'PurchaseAddDetailControler@addGoodsForwardOrderDetail');
    Route::post('/createGoodsForwardOrderDetailForm', 'PurchaseDataCallController@createGoodsForwardOrderDetailForm');




    Route::post('/editPurchaseRequestVoucherDetail', 'PurchaseEditDetailControler@editPurchaseRequestVoucherDetail');
    Route::post('/addStockTransfer', 'PurchaseAddDetailControler@addStockTransfer');
    Route::post('/updateStockTransfer', 'PurchaseAddDetailControler@updateStockTransfer');
    Route::post('/updatePurchaseReturnDetail', 'PurchaseAddDetailControler@updatePurchaseReturnDetail');


    Route::post('/add_internal_consum', 'PurchaseAddDetailControler@add_internal_consum');

    Route::post('/insertDirectPurchaseInvoice', 'PurchaseAddDetailControler@insertDirectPurchaseInvoice');
    Route::post('/updateDirectPurchaseInvoice', 'PurchaseAddDetailControler@updateDirectPurchaseInvoice');
    Route::get('/reverseDirectPurchaseInvoice', 'PurchaseDeleteController@reverseDirectPurchaseInvoice');



});
Route::get('/set_user_db_id', 'PurchaseDataCallController@set_user_db_id');
Route::group(['prefix' => 'pdc', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/purchase_order/{id}', 'PurchaseDataCallController@purchase_order');

    Route::get('/viewCustomerList', 'PurchaseDataCallController@viewCustomerList');
    Route::get('/fetchDataOfMr', 'PurchaseDataCallController@fetchDataOfMr');
    Route::get('/get_stock_location_wise', 'PurchaseDataCallController@get_stock_location_wise');
    Route::get('/get_stock_location_wise2', 'PurchaseDataCallController@get_stock_location_wise2');
    Route::get('/getDupicate', 'PurchaseDataCallController@getDupicate');

    Route::get('/delete_cate', 'PurchaseDataCallController@delete_cate');
    Route::get('/delete_sub_cate', 'PurchaseDataCallController@delete_sub_cate');


    Route::get('/viewSupplierList', 'PurchaseDataCallController@viewSupplierList');
    Route::get('/getPurchaseDetailReportAjax', 'PurchaseDataCallController@getPurchaseDetailReportAjax');
    Route::get('/getAgingReportDataAjax', 'PurchaseDataCallController@getAgingReportDataAjax');
    Route::get('/get_dashboard_info', 'PurchaseDataCallController@get_dashboard_info');
    Route::get('/getOnlineUserAjax', 'PurchaseDataCallController@getOnlineUserAjax');

    Route::get('/getPendingApporvedMultiList', 'PurchaseDataCallController@getPendingApporvedMultiList');
    Route::get('/getPendingApporvedMultiListForSales', 'PurchaseDataCallController@getPendingApporvedMultiListForSales');
    Route::get('/getPendingApporvedMultiListForFinance', 'PurchaseDataCallController@getPendingApporvedMultiListForFinance');
    Route::get('/vendor_outstanding_data', 'PurchaseDataCallController@vendor_outstanding_data');
    Route::get('/vendor_balance_ajax_data', 'PurchaseDataCallController@vendor_balance_ajax_data');

    Route::get('/getDetailReportAjax', 'PurchaseDataCallController@getDetailReportAjax');

    Route::get('/delete_supp', 'PurchaseDataCallController@delete_supp');



    Route::get('/createSupplierAccount', 'PurchaseDataCallController@createSupplierAccount');
    Route::get('/get_sub_category', 'PurchaseDataCallController@get_sub_category');
    Route::get('/getSubCategoryList', 'PurchaseDataCallController@getSubCategoryList');
    Route::get('/getItemBySubCategory', 'PurchaseDataCallController@getItemBySubCategory');

    Route::get('/get_item_master', 'PurchaseDataCallController@get_item_master');
    Route::get('/get_sub_category_by_id', 'PurchaseDataCallController@get_sub_category_by_id');
    Route::get('/get_sub_item_code', 'PurchaseDataCallController@get_sub_item_code');
    Route::get('/get_data', 'PurchaseDataCallController@get_data');
    Route::get('/get_batch_code', 'PurchaseDataCallController@get_batch_code');
    Route::get('/get_grn_history', 'PurchaseDataCallController@get_grn_history');
    Route::get('/search/{categoryId?}/{subCategory_id?}', 'PurchaseDataCallController@search');

    Route::get('/viewCategoryList', 'PurchaseDataCallController@viewCategoryList');
    Route::get('/viewRegionList', 'PurchaseDataCallController@viewRegionList');
    Route::get('/viewSubItemList', 'PurchaseDataCallController@viewSubItemList');
    Route::get('/viewSubItemListAjax', 'PurchaseDataCallController@viewSubItemListAjax');

    Route::get('/viewUOMList', 'PurchaseDataCallController@viewUOMList');
    Route::get('/filterDemandVoucherList', 'PurchaseDataCallController@filterDemandVoucherList');
    Route::get('/viewDemandVoucherDetail', 'PurchaseDataCallController@viewDemandVoucherDetail');
    Route::get('/viewJobOrderDetail', 'PurchaseDataCallController@viewJobOrderDetail');
    Route::get('/viewEstimateDetail', 'PurchaseDataCallController@viewEstimateDetail');
    Route::get('/viewSurveyImage', 'PurchaseDataCallController@viewSurveyImage');

    Route::get('/viewPurchaseReturnDetail', 'PurchaseDataCallController@viewPurchaseReturnDetail');
    Route::get('/viewStockReturnDetail', 'PurchaseDataCallController@viewStockReturnDetail');

    Route::get('/get_stock_region_wise', 'PurchaseDataCallController@get_stock_region_wise');
    Route::get('/getReportMultiItems', 'PurchaseDataCallController@getReportMultiItems');

    Route::get('/get_stock_region_wise_batch_wise', 'PurchaseDataCallController@get_stock_region_wise_batch_wise');

    Route::get('/get_stock', 'PurchaseDataCallController@get_stock');
    Route::get('/get_uom', 'PurchaseDataCallController@get_uom');
    Route::get('/get_uom_name_by_item_id', 'PurchaseDataCallController@get_uom_name_by_item_id');

    //amir

    Route::get('/viewPurchaseVoucherDetail/{id?}', 'PurchaseDataCallController@viewPurchaseVoucherDetail');
    Route::get('/services', 'PurchaseDataCallController@services');
    Route::get('/viewPurchaseVoucherDetailThroughGrn/{id?}', 'PurchaseDataCallController@viewPurchaseVoucherDetailThroughGrn');
    Route::get('/viewPurchaseVoucherDetailAfterSubmit/{id?}', 'PurchaseDataCallController@viewPurchaseVoucherDetailAfterSubmit');
    Route::get('/purchase_voucher_list_ajax', 'PurchaseDataCallController@purchase_voucher_list_ajax');
    Route::get('/get_data_debit_note_ajax', 'PurchaseDataCallController@get_data_debit_note_ajax');
    Route::get('/filterByClientAndRegionJobOrder', 'PurchaseDataCallController@filterByClientAndRegionJobOrder');
    Route::get('/filterByClientAndRegionJobOrderTwo', 'PurchaseDataCallController@filterByClientAndRegionJobOrderTwo');
    Route::get('/filterByCategoryAndRegionWiseStockOpening', 'PurchaseDataCallController@filterByCategoryAndRegionWiseStockOpening');
    Route::get('/ItemWiseReportAjax', 'PurchaseDataCallController@ItemWiseReportAjax');

    Route::get('/issuanceDataFilter', 'PurchaseDataCallController@issuanceDataFilter');
    Route::get('/stockReturnDataFilter', 'PurchaseDataCallController@stockReturnDataFilter');
    Route::get('/approve_grn', 'PurchaseDataCallController@approve_grn');
    Route::get('/getDataAjaxSupplierWise', 'PurchaseDataCallController@getDataAjaxSupplierWise');

    Route::get('/get_ledger_refrence_wise', 'PurchaseDataCallController@get_ledger_refrence_wise');
    Route::get('/deletePurchaseReturn', 'PurchaseDataCallController@deletePurchaseReturn');
    Route::get('/DeleteStockReturn', 'PurchaseDataCallController@DeleteStockReturn');
    Route::get('/deleteStockTransfer', 'PurchaseDataCallController@deleteStockTransfer');

    Route::get('/DeleteGrn', 'PurchaseDataCallController@DeleteGrn');
    Route::get('/MasterDeleteGrn', 'PurchaseDataCallController@MasterDeleteGrn');
    Route::get('/UpdateBranchId', 'PurchaseDataCallController@UpdateBranchId');
    Route::get('/getStockDataWithItemWise', 'PurchaseDataCallController@getStockDataWithItemWise');
    Route::get('/insertOrUpdateOpeningStock', 'PurchaseDataCallController@insertOrUpdateOpeningStock');
    Route::get('/editRegionDetail', 'PurchaseDataCallController@editRegionDetail');
    Route::get('/checkDuplicateSubCategory', 'PurchaseDataCallController@checkDuplicateSubCategory');






    Route::get('/get_job_order', 'PurchaseDataCallController@get_job_order');
    Route::get('/get_po_status_data', 'PurchaseDataCallController@get_po_status_data');
    Route::get('/getPoDetailDateWise', 'PurchaseDataCallController@getPoDetailDateWise');


    //end amir

    // for  supplier  ajax
    //  Route::get('/viewPurchaseVoucherDetail/{id?}','PurchaseDataCallController@viewPurchaseVoucherDetail');
    Route::get('/createSupplierFormAjax/{PageName?}', 'PurchaseDataCallController@createSupplierFormAjax');
    Route::post('/addSupplierDetail', 'PurchaseDataCallController@addSupplierDetail');


    // for  purchase Type ajax

    Route::get('/createPurchaseTypeForm', 'PurchaseDataCallController@createPurchaseTypeForm');
    Route::get('/approve_purchase', 'PurchaseDataCallController@approve_purchase');

    Route::post('/addPurchaseType', 'PurchaseDataCallController@addPurchaseType');


    // for opening
    Route::get('/get_data_opening', 'PurchaseDataCallController@get_data_opening');

    // for  currency ajax
    Route::get('/createCurrencyTypeForm', 'PurchaseDataCallController@createCurrencyTypeForm');
    Route::Get('/addCurrency', 'PurchaseDataCallController@addCurrency');
    Route::Post('/addCurrencyForm', 'PurchaseDataCallController@addCurrencyForm');


    // for sub item ajax
    Route::get('/createSubItemFormAjax/{id?}', 'PurchaseDataCallController@createSubItemFormAjax');
    Route::Post('/addSubItemDetailAjax', 'PurchaseDataCallController@addSubItemDetailAjax');
    Route::get('/viewHistoryOfItem', 'PurchaseDataCallController@viewHistoryOfItem');
    Route::get('/viewHistoryOfItem_directPo', 'PurchaseDataCallController@viewHistoryOfItem_directPo');
    Route::get('/viewPaymentDetail', 'PurchaseDataCallController@viewPaymentDetail');











    // for category
    Route::get('/createCategoryFormAjax/{id?}', 'PurchaseDataCallController@createCategoryFormAjax');
    Route::Post('/addCategoryDetailAjax', 'PurchaseDataCallController@addCategoryDetailAjax');

    //end

    // for Department ajax
    Route::get('/createDepartmentFormAjax/{id?}', 'PurchaseDataCallController@createDepartmentFormAjax');
    Route::Post('/addDepartmentFormAjax', 'PurchaseDataCallController@addDepartmentFormAjax');


    // for Cost Center ajax
    Route::get('/createCostCenterFormAjax/{id?}', 'PurchaseDataCallController@createCostCenterFormAjax');
    Route::Post('/addCostCenterFormajax', 'PurchaseDataCallController@addCostCenterFormajax');


    // for delete purchase voucher
    Route::get('/deletepurchasevoucher', 'PurchaseDataCallController@deletepurchasevoucher');
    Route::get('/UpdateDpdn', 'PurchaseDataCallController@UpdateDpdn');

    //end amir

    //sandeep
    Route::get('/deleteProductDetail', 'PurchaseDataCallController@deleteProductDetail');
    Route::get('/deleteCondition', 'PurchaseDataCallController@deleteCondition');
    Route::get('/deleteTypeList', 'PurchaseDataCallController@deleteTypeList');
    Route::get('/deleteClientList', 'PurchaseDataCallController@deleteClientList');
    Route::get('/deleteProductTypeList', 'PurchaseDataCallController@deleteProductTypeList');
    Route::get('/deleteResourceAssignedList', 'PurchaseDataCallController@deleteResourceAssignedList');

    // for ware house ajax
    Route::get('/createWarehouseFormAjax/{id?}', 'PurchaseDataCallController@createWarehouseFormAjax');
    Route::Post('/addWarehouseDetailAjax', 'PurchaseDataCallController@addWarehouseDetailAjax');

    Route::get('/filterGoodsReceiptNoteList', 'PurchaseDataCallController@filterGoodsReceiptNoteList');
    Route::get('/viewGoodsReceiptNoteDetail', 'PurchaseDataCallController@viewGoodsReceiptNoteDetail');
    Route::get('/qc', 'PurchaseDataCallController@qc');
    Route::post('/qc_submit', 'PurchaseDataCallController@qc_submit');
    Route::get('/viewGoodsReceiptNoteDetail', 'PurchaseDataCallController@viewGoodsReceiptNoteDetail');
    Route::get('/viewGoodsReceiptNoteDetailNewTab', 'PurchaseDataCallController@viewGoodsReceiptNoteDetailNewTab');

    Route::get('/viewGoodsReceiptNoteWPODetail', 'PurchaseDataCallController@viewGoodsReceiptNoteWPODetail');

    Route::get('/filterGoodsForwardOrderVoucherList', 'PurchaseDataCallController@filterGoodsForwardOrderVoucherList');
    Route::get('/viewGoodsForwardOrderVoucherDetail', 'PurchaseDataCallController@viewGoodsForwardOrderVoucherDetail');
    Route::get('/filterApproveDemandListandCreateGoodsForwardOrder', 'PurchaseDataCallController@filterApproveDemandListandCreateGoodsForwardOrder');

    Route::get('/ApprovedGoodIssuance', 'PurchaseDataCallController@ApprovedGoodIssuance');
    Route::get('/ApprovedStockReturn', 'PurchaseDataCallController@ApprovedStockReturn');
    Route::get('/Recieved', 'PurchaseDataCallController@Recieved');


});
// Start Recipe

Route::group(['prefix' => 'recipe', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/addRecipe', 'RecipeController@addRecipe');
    Route::post('/insertRecipe', 'RecipeController@insertRecipe');
    Route::get('/viewRecipeInfo', 'RecipeController@viewRecipeInfo');
    Route::get('/recipeList', 'RecipeController@recipeList');
    Route::get('/recipeDelete', 'RecipeController@recipeDelete');
    Route::get('/recipeDataItemDelete', 'RecipeController@recipeDataItemDelete');
    Route::get('/recipeEdit', 'RecipeController@recipeEdit');
    Route::get('/changeFormulationStatus', 'RecipeController@changeFormulationStatus');
    Route::post('/UpdateRecipe', 'RecipeController@UpdateRecipe');
    Route::post('/addChangeFormulationStatusDetail', 'RecipeController@addChangeFormulationStatusDetail');
});



//End Recipe

// Start Make Product
Route::group(['prefix' => 'makeProduct', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/createMakeProductForm', 'MakeProductController@createMakeProductForm');
    Route::get('/getRecipeData/{id}', 'MakeProductController@getRecipeData');
    Route::post('/addMakeProductDetail', 'MakeProductController@addMakeProductDetail');

    Route::get('/productlist', 'MakeProductController@productlist');
    Route::get('/viewProductList', 'MakeProductController@viewProductList');
    Route::get('/deleteList', 'MakeProductController@deleteList');
});
//End Make Product


Route::group(['prefix' => 'pmfal', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/subItemListLoadDepandentCategoryId', 'PurchaseMakeFormAjaxLoadController@subItemListLoadDepandentCategoryId');
    Route::get('/get_category_wise_sub_category', 'PurchaseMakeFormAjaxLoadController@get_category_wise_sub_category');
    Route::get('/get_sub_item_all_ajax', 'PurchaseMakeFormAjaxLoadController@get_sub_item_all_ajax');


    Route::get('/addMoreDemandsDetailRows', 'PurchaseMakeFormAjaxLoadController@addMoreDemandsDetailRows');
    Route::get('/addDirectgrn', 'PurchaseMakeFormAjaxLoadController@addDirectgrn');
    //amir
    Route::get('/addMorPurchaseVoucherRow', 'PurchaseMakeFormAjaxLoadController@addMorPurchaseVoucherRow');
    Route::get('/get_detail_purchase_voucher', 'PurchaseMakeFormAjaxLoadController@get_detail_purchase_voucher');
    Route::get('/get_po', 'PurchaseMakeFormAjaxLoadController@get_po');
    Route::get('/getGrnNoBySupplier', 'PurchaseMakeFormAjaxLoadController@getGrnNoBySupplier');

    Route::get('/get_refer', 'PurchaseMakeFormAjaxLoadController@get_refer');
    Route::get('/get_ledger_refrence_wise', 'PurchaseMakeFormAjaxLoadController@get_ledger_refrence_wise');
    Route::get('/new_refrence', 'PurchaseMakeFormAjaxLoadController@new_refrence');
    Route::get('/ClientInfo', 'PurchaseMakeFormAjaxLoadController@ClientInfo');
    Route::get('/GetBranch', 'PurchaseMakeFormAjaxLoadController@GetBranch');


    //amir end

    Route::get('/addMoreIssuanceDetailRows', 'PurchaseMakeFormAjaxLoadController@addMoreIssuanceDetailRows');
    Route::get('/makeFormDemandVoucher', 'PurchaseMakeFormAjaxLoadController@makeFormDemandVoucher');
    Route::get('/makeFormGoodsReceiptNoteDetailByPRNo', 'PurchaseMakeFormAjaxLoadController@makeFormGoodsReceiptNoteDetailByPRNo');
    Route::get('/makeFormGoodsReceiptNoteDetailByPRNoManual', 'PurchaseMakeFormAjaxLoadController@makeFormGoodsReceiptNoteDetailByPRNoManual');

    Route::get('/makeFormGoodsReceiptNoteDetailByGrnNo', 'PurchaseMakeFormAjaxLoadController@makeFormGoodsReceiptNoteDetailByGrnNo');

    Route::get('/addMorJobOrderRow', 'PurchaseMakeFormAjaxLoadController@addMorJobOrderRow');

    Route::get('/get_stock', 'PurchaseMakeFormAjaxLoadController@get_stock');

    Route::get('/deleteJobOrderData', 'PurchaseMakeFormAjaxLoadController@deleteJobOrderData');
    Route::get('/deleteJobOrderAndEstimate', 'PurchaseMakeFormAjaxLoadController@deleteJobOrderAndEstimate');
});

//End Purchase

//Start Store
Route::group(['prefix' => 'store', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/addMaterialRequestForm', 'StoreController@addMaterialRequestForm');
    Route::get('/editMaterialRequestForm/{id}', 'StoreController@editMaterialRequestForm')->name('store.editMaterialRequestForm');
    Route::get('/viewMaterialRequestList', 'StoreController@viewMaterialRequestList');
    Route::get('/addStoreChallanForm', 'StoreController@addStoreChallanForm');
    Route::get('/editStoreChallanForm/{material_request_no}/{material_request_date}/{store_challan_no}', 'StoreController@editStoreChallanForm')->name('store.editStoreChallanForm');
    Route::get('/viewStoreChallanList', 'StoreController@viewStoreChallanList');
    Route::get('/addMoreMaterialRequestsDetailRows', 'StoreController@addMoreMaterialRequestsDetailRows');
    Route::get('/st', 'StoreController@toDayActivity');
    Route::get('/average_cost', 'StoreController@average_cost');
    Route::get('/inventoryActivityPage', 'StoreController@inventoryActivityPage');
    Route::get('/inventoryActivityAjax', 'StoreController@inventoryActivityAjax');
    Route::get('/scReportPage', 'StoreController@scReportPage');
    Route::get('/getDataScReportAjax', 'StoreController@getDataScReportAjax');
    Route::get('/issuence_against_product', 'StoreController@issuence_against_product');
    Route::get('/add_internal_consumtion', 'StoreController@add_internal_consumtion');
    Route::get('/add_bom', 'StoreController@add_bom');
    Route::get('/add_operation_data', 'StoreController@add_operation_data');
    Route::get('/Create_routing', 'StoreController@Create_routing');
    Route::post('/add_finish', 'StoreController@add_finish');


    Route::get('/getBatchCodes', 'StoreController@getBatchCodes');
    Route::get('/getStockBatchWise', 'StoreController@getStockBatchWise');



    Route::get('/add_opening', 'StoreController@add_opening');
    Route::get('/createIssuanceForm', 'StoreController@createIssuanceForm');
    Route::get('/editIssuanceForm', 'StoreController@editIssuanceForm');

    Route::get('/issuanceList', 'StoreController@issuanceList');


    Route::get('/viewDemandList', 'StoreController@viewDemandList');
    Route::get('/itemCostClassification', 'StoreController@itemCostClassification');

    Route::get('/item_detaild_supplier_wise', 'StoreController@item_detaild_supplier_wise');

    Route::get('/createStoreChallanForm', 'StoreController@createStoreChallanForm');
    Route::get('/viewStoreChallanList', 'StoreController@viewStoreChallanList');
    Route::get('/editStoreChallanVoucherForm', 'StoreController@editStoreChallanVoucherForm');


    Route::get('/createPurchaseRequestForm', 'StoreController@createPurchaseRequestForm');
    Route::get('/viewPurchaseRequestList', 'StoreController@viewPurchaseRequestList');
    Route::get('/editPurchaseRequestVoucherForm', 'StoreController@editPurchaseRequestVoucherForm');

    Route::get('/createPurchaseRequestSaleForm', 'StoreController@createPurchaseRequestSaleForm');
    Route::get('/viewPurchaseRequestSaleList', 'StoreController@viewPurchaseRequestSaleList');
    Route::get('/editPurchaseRequestSaleVoucherForm', 'StoreController@editPurchaseRequestSaleVoucherForm');

    Route::get('/createStoreChallanReturnForm', 'StoreController@createStoreChallanReturnForm');
    Route::get('/viewStoreChallanReturnList', 'StoreController@viewStoreChallanReturnList');
    Route::get('/editStoreChallanReturnForm', 'StoreController@editStoreChallanReturnForm');

    Route::get('/viewDateWiseStockInventoryReport', 'StoreController@viewDateWiseStockInventoryReport');

    Route::get('/editPurchaseRequestVoucherForm/{id}', 'StoreController@editPurchaseRequestVoucherForm');
    Route::get('/editDirectPurchaseRequestVoucherForm/{id}', 'StoreController@editDirectPurchaseRequestVoucherForm');
    Route::get('/stockReportView', 'StoreController@stockReportView');
    Route::get('/stockReportBatchWiseView', 'StoreController@stockReportBatchWiseView');

    Route::get('/fullstockReportView', 'StoreController@fullstockReportView');
    Route::get('/fullstockReportViewBatch', 'StoreController@fullstockReportViewBatch');
    Route::get('/stockDetailReport', 'StoreController@stockDetailReport');
    Route::get('/StockOpeningValuesUpdate', 'StoreController@StockOpeningValuesUpdate');


    Route::get('/InventoryStockReport', 'StoreController@InventoryStockReport');
    Route::get('/InventoryStockReportAjax', 'StoreController@InventoryStockReportAjax');
    Route::get('/rateAndAmountupdate', 'StoreController@rateAndAmountupdate');
    Route::get('/rateAndAmountupdateAjax', 'StoreController@rateAndAmountupdateAjax');
    Route::get('/UpdateRateAmount', 'StoreController@UpdateRateAmount');
    Route::get('/UpdateRateAmountGrn', 'StoreController@UpdateRateAmountGrn');
    Route::get('/UpdateRateAmountReturn', 'StoreController@UpdateRateAmountReturn');
    Route::get('/stockReportItemWisePage', 'StoreController@stockReportItemWisePage');
    Route::get('/stockReportItemWiseAjax', 'StoreController@stockReportItemWiseAjax');
    Route::get('/checkPurchasingPage', 'StoreController@checkPurchasingPage');
    Route::get('/getCheckPurchasingDataAjax', 'StoreController@getCheckPurchasingDataAjax');
    Route::get('/stock_transfer_form', 'StoreController@stock_transfer_form');
    Route::get('/stock_transfer_list', 'StoreController@stock_transfer_list');
    Route::get('/editStockTransferForm/{id}/{TrNo}', 'StoreController@editStockTransferForm');
    Route::get('/itemWiseOpening', 'StoreController@itemWiseOpening');
    Route::get('/inventory_movement', 'StoreController@inventory_movement');
    Route::get('/inventory_movement_test', 'StoreController@inventory_movement_test');
    Route::get('/inventory_movement_fi', 'StoreController@inventory_movement_fi');
    Route::get('/stock_movemnet', 'StoreController@stock_movemnet');
    Route::get('/stock_movemnet_test', 'StoreController@stock_movemnet_test');
    Route::get('/stock_movemnetAjaxMoreData', 'StoreController@stock_movemnetAjaxMoreData');

    Route::get('/internal_consumtion_list', 'StoreController@internal_consumtion_list');

    Route::get('/makeFormStoreChallanDetailByMRNo', 'StoreController@makeFormStoreChallanDetailByMRNo');
    Route::get('/makeEditFormStoreChallanDetailByMRNo', 'StoreController@makeEditFormStoreChallanDetailByMRNo');

});

Route::group(['prefix' => 'stad', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::post('/createStoreChallanDetailForm', 'StoreDataCallController@createStoreChallanDetailForm');
    Route::post('/addStoreChallanDetail', 'StoreAddDetailControler@addStoreChallanDetail');
    Route::post('/editStoreChallanDetail', 'StoreAddDetailControler@editStoreChallanDetail');
    Route::post('/add_issuence', 'StoreAddDetailControler@add_issuence');
    Route::post('/editStoreChallanVoucherDetail', 'StoreEditDetailControler@editStoreChallanVoucherDetail');
    Route::post('/addIssuanceDetail', 'StoreAddDetailControler@addIssuanceDetail');
    Route::post('/updateIssuanceDetail', 'StoreAddDetailControler@updateIssuanceDetail');
    Route::get('deleteStoreChallanDetail', 'StoreAddDetailControler@deleteStoreChallanDetail')->name('stad.StoreChallanDetail.deleteStoreChallanDetail');

    Route::post('/issuence_return', 'StoreAddDetailControler@issuence_return');
    Route::post('/add_production', 'StoreAddDetailControler@add_production');

    Route::post('/insertDirectPurchaseOrder', 'StoreAddDetailControler@insertDirectPurchaseOrder');

    Route::post('/insert_opening_data', 'StoreAddDetailControler@insert_opening_data');
    Route::post('/addPurchaseRequestDetail', 'StoreAddDetailControler@addPurchaseRequestDetail');
    Route::post('/editPurchaseRequestVoucherDetail', 'StoreEditDetailControler@editPurchaseRequestVoucherDetail');

    Route::post('/createPurchaseRequestSaleDetailForm', 'StoreDataCallController@createPurchaseRequestSaleDetailForm');
    Route::post('/addPurchaseRequestSaleDetail', 'StoreAddDetailControler@addPurchaseRequestSaleDetail');
    Route::post('/editPurchaseRequestSaleVoucherDetail', 'StoreEditDetailControler@editPurchaseRequestSaleVoucherDetail');

    Route::post('/createStoreChallanReturnDetailForm', 'StoreDataCallController@createStoreChallanReturnDetailForm');
    Route::post('/addStoreChallanReturnDetail', 'StoreAddDetailControler@addStoreChallanReturnDetail');
    Route::post('/editStoreChallanReturnDetail', 'StoreEditDetailControler@editStoreChallanReturnDetail');

    Route::get('/Email_Sent', 'StoreAddDetailControler@Email_Sent');
    Route::get('/p_detail_report', 'StoreDataCallController@p_detail_report');
    Route::get('/stockDetailReport', 'StoreDataCallController@stockDetailReport');
    Route::get('/UpdateTableDataSubitem', 'StoreAddDetailControler@UpdateTableDataSubitem');

    Route::post('/addConvertGrnData', 'StoreAddDetailControler@addConvertGrnData');

    Route::post('/addMaterialRequestDetail', 'StoreAddDetailControler@addMaterialRequestDetail');
    Route::put('/editMaterialRequestDetail', 'StoreAddDetailControler@editMaterialRequestDetail');

    Route::get('/deleteMaterialRequestDetail', 'StoreAddDetailControler@deleteMaterialRequestDetail')->name('stad.deleteMaterialRequestDetail');


});

Route::group(['prefix' => 'stdc', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/createPurchaseRequestDetailForm', 'StoreDataCallController@createPurchaseRequestDetailForm');

    Route::get('/getPendingPurchaseRequest', 'StoreDataCallController@getPendingPurchaseRequest');
    Route::get('/viewMaterialRequestDetail/{id}', 'StoreDataCallController@viewMaterialRequestDetail');
    Route::get('/filterDemandVoucherList', 'StoreDataCallController@filterDemandVoucherList');
    Route::get('/get_work_order_data', 'StoreDataCallController@get_work_order_data');
    Route::get('/approve_transfer', 'StoreDataCallController@approve_transfer');
    Route::get('/approveIssuance', 'StoreDataCallController@approveIssuance');
    Route::get('/getStockTransferDataAjax', 'StoreDataCallController@getStockTransferDataAjax');
    Route::get('/delete_issue', 'StoreDataCallController@delete_issue');
    Route::get('/delete_internal_cons', 'StoreDataCallController@delete_internal_cons');


    Route::get('/viewIssuanceDetail', 'StoreDataCallController@viewIssuanceDetail');


    Route::get('/filterApproveDemandListandCreateStoreChallan', 'StoreDataCallController@filterApproveDemandListandCreateStoreChallan');
    Route::get('/filterStoreChallanVoucherList', 'StoreDataCallController@filterStoreChallanVoucherList');
    Route::get('/viewStoreChallanVoucherDetail', 'StoreDataCallController@viewStoreChallanVoucherDetail');

    Route::get('/filterApproveDemandListandCreatePurchaseRequest', 'StoreDataCallController@filterApproveDemandListandCreatePurchaseRequest');
    Route::get('/filterPurchaseRequestVoucherList', 'StoreDataCallController@filterPurchaseRequestVoucherList');
    Route::get('/viewPurchaseRequestVoucherDetail', 'StoreDataCallController@viewPurchaseRequestVoucherDetail');


    Route::get('/filterApproveDemandListandCreatePurchaseRequestSale', 'StoreDataCallController@filterApproveDemandListandCreatePurchaseRequestSale');
    Route::get('/filterPurchaseRequestSaleVoucherList', 'StoreDataCallController@filterPurchaseRequestSaleVoucherList');
    Route::get('/viewPurchaseRequestSaleVoucherDetail', 'StoreDataCallController@viewPurchaseRequestSaleVoucherDetail');

    Route::get('/filterApproveStoreChallanandCreateStoreChallanReturn', 'StoreDataCallController@filterApproveStoreChallanandCreateStoreChallanReturn');
    Route::get('/filterStoreChallanReturnList', 'StoreDataCallController@filterStoreChallanReturnList');
    Route::get('/viewStoreChallanReturnDetail', 'StoreDataCallController@viewStoreChallanReturnDetail');

    Route::get('/filterViewDateWiseStockInventoryReport', 'StoreDataCallController@filterViewDateWiseStockInventoryReport');
    Route::get('/viewStockInventorySummaryDetail', 'StoreDataCallController@viewStockInventorySummaryDetail');
    Route::get('/viewStockTransferDetail', 'StoreDataCallController@viewStockTransferDetail');
    Route::get('/getBuyerWiseOpeningData', 'StoreDataCallController@getBuyerWiseOpeningData');
    Route::get('/getVendorWiseOpeningData', 'StoreDataCallController@getVendorWiseOpeningData');
    Route::get('/UpdateBuyerOpening', 'StoreDataCallController@UpdateBuyerOpening');
    Route::get('/UpdateVendorOpening', 'StoreDataCallController@UpdateVendorOpening');
    Route::get('/getPoDataPoNoWise', 'StoreDataCallController@getPoDataPoNoWise');
    Route::get('/view_internal_consumtion_detail', 'StoreDataCallController@view_internal_consumtion_detail');
    Route::get('/internal_cosum', 'StoreDataCallController@internal_cosum');

});




//End Store

//Start Sales
Route::group(['prefix' => 'sales', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::resource('salesorder', 'SalesOrderController');

    Route::post('/uploadCreditCustomer', 'SalesController@uploadCreditCustomer');

    Route::get('/s', 'SalesController@toDayActivity');

    Route::get('/editDirectSalesTaxInvoice/{id}', 'SalesController@editDirectSalesTaxInvoice')->name('editDirectSalesTaxInvoice');

    Route::get('/addOpeningAgainstCustomerForm', 'SalesController@addOpeningAgainstCustomerForm');

    Route::get('/addReceiptVoucherAgainstSOForm', 'SalesController@addReceiptVoucherAgainstSOForm');
    Route::get('/topFiveSalesReportPage', 'SalesController@topFiveSalesReportPage');

    Route::get('/debtor_payment_detail', 'SalesController@debtor_payment_detail');


    Route::get('/debtor_balance_page', 'SalesController@debtor_balance_page');
    Route::get('/commission_report_page', 'SalesController@commission_report_page');


    Route::get('/freight_collection_page', 'SalesController@freight_collection_page');

    Route::get('/soTrackingQtyPage', 'SalesController@soTrackingQtyPage');

    Route::get('/outstandingReportPage', 'SalesController@outstandingReportPage');
    Route::get('/salesTaxInvoiceReportPage', 'SalesController@salesTaxInvoiceReportPage');

    Route::get('/salesActivityPage', 'SalesController@salesActivityPage');
    Route::get('/salesAgingReport', 'SalesController@salesAgingReport');
    Route::get('/getAgingReportDataAjaxSales', 'SalesController@getAgingReportDataAjaxSales');

    Route::get('/createCustomerOpeningBalance', 'SalesController@createCustomerOpeningBalance');
    Route::get('/creatVendorOpeningBalance', 'SalesController@creatVendorOpeningBalance');
    Route::get('/logActivity', 'SalesController@logActivity');
    Route::get('/creditCustomerAddNView', 'SalesController@creditCustomerAddNView');
    Route::get('/cashCustomerAddNView', 'SalesController@cashCustomerAddNView');
    Route::get('/salesActivityAjax', 'SalesController@salesActivityAjax');

    Route::get('/createCashCustomerForm', 'SalesController@createCashCustomerForm');
    Route::get('/viewCashCustomerList', 'SalesController@viewCashCustomerList');

    Route::get('/createCreditCustomerForm', 'SalesController@createCreditCustomerForm');
    Route::get('/editCustomerForm/{id?}', 'SalesController@editCustomerForm');
    Route::get('/viewCreditCustomerList', 'SalesController@viewCreditCustomerList');
    Route::get('/add_agent_list', 'SalesController@add_agent_list');
    Route::get('/viewCustomer/{id}', 'SalesController@viewCustomer')->name('viewCustomer');
    Route::get('/customerOrderTracking/{id}', 'SalesController@customerOrderTracking')->name('customerOrderTracking');



    Route::get('/createCreditSaleVoucherForm', 'SalesController@createCreditSaleVoucherForm');
    Route::get('/viewCreditSaleVouchersList', 'SalesController@viewCreditSaleVouchersList');

    Route::get('/createCashSaleVoucherForm', 'SalesController@createCashSaleVoucherForm');
    Route::get('/viewCashSaleVouchersList', 'SalesController@viewCashSaleVouchersList');
    Route::get('/CreateSalesOrder', 'SalesController@CreateSalesOrder');
    Route::get('/EditSalesOrder/{id}', 'SalesController@EditSalesOrder');


    Route::get('/viewSalesOrderList', 'SalesController@viewSalesOrderList');
    Route::get('/viewSalesOrderDetail/{id?}', 'SalesController@viewSalesOrderDetail');



    // For Delivery Note
    Route::get('/CreateDeliveryNoteList', 'SalesController@CreateDeliveryNoteList');
    Route::get('/CreateDeliveryChallanList', 'SalesController@CreateDeliveryChallanList');
    Route::get('/CreateDispatchList', 'SalesController@CreateDispatchList');
    Route::get('/CreateDeliveryNote/{id?}', 'SalesController@CreateDeliveryNote');
    Route::get('/CreateDeliveryChallan/{id?}', 'SalesController@CreateDeliveryChallan');
    Route::get('/CreateDispatch/{id?}', 'SalesController@CreateDispatch');
    Route::get('/EditDeliveryNote/{id?}', 'SalesController@EditDeliveryNote');
    Route::get('/EditDispatch/{id?}', 'SalesController@EditDispatch');
    Route::get('/editSalesReturn/{id?}', 'SalesController@editSalesReturn');
    Route::get('/viewDeliveryNoteList', 'SalesController@viewDeliveryNoteList');
    Route::get('/viewDeliveryChallanList', 'SalesController@viewDeliveryChallanList');
    Route::get('/viewDispatchList', 'SalesController@viewDispatchList');
    Route::get('/viewDeliveryNoteListOther', 'SalesController@viewDeliveryNoteListOther');
    Route::get('/viewDeliveryNoteDetail/{id?}', 'SalesController@viewDeliveryNoteDetail');
    Route::get('/viewDeliveryChallanDetail/{id?}', 'SalesController@viewDeliveryChallanDetail');
    Route::get('/viewDispatchDetail/{id?}', 'SalesController@viewDispatchDetail');
    Route::get('/viewDeliveryNoteDetailTwo/{id?}', 'SalesController@viewDeliveryNoteDetailTwo');
    Route::get('/ViewMultipleDeliveryNotesDetail', 'SalesController@ViewMultipleDeliveryNotesDetail');
    Route::get('/ViewMultipleSalesTaxInvoices', 'SalesController@ViewMultipleSalesTaxInvoices');
    Route::get('/ViewMultipleCreditNoteDetail', 'SalesController@ViewMultipleCreditNoteDetail');
    Route::get('/editImportDocument/{id?}', 'SalesController@editImportDocument');


    Route::get('/approve_so', 'SalesController@approve_so');
    Route::get('/si_approve', 'SalesController@si_approve');



    Route::get('/CreateSalesTaxInvoiceBySO/{id?}', 'SalesController@CreateSalesTaxInvoiceBySO');

    // For Sales Tax Invoice

    Route::get('/undertaking/{id?}', 'SalesController@undertaking');
    // For Sales Receipt Voucher
    Route::get('/CreateReceiptVoucherList', 'SalesController@CreateReceiptVoucherList');
    Route::get('/receiptVoucherList', 'SalesController@receiptVoucherList');
    Route::get('/editVoucherList{id?}', 'SalesController@editVoucherList');
    Route::get('/CreateSalesTaxInvoiceList', 'SalesController@CreateSalesTaxInvoiceList');
    Route::post('/CreateSalesTaxInvoice', 'SalesController@CreateSalesTaxInvoice');
    Route::get('/CreateDirectSalesTaxInvoice', 'SalesController@CreateDirectSalesTaxInvoice');

    Route::get('/edit-sales-tax-invoice/{id}', 'SalesController@EditSalesTaxInvoice')
        ->name('EditSalesTaxInvoice');
    Route::get('/viewSalesTaxInvoiceList', 'SalesController@viewSalesTaxInvoiceList');
    Route::get('/viewSalesTaxInvoiceDetailList', 'SalesController@viewSalesTaxInvoiceDetailList');
    Route::get('/viewSalesTaxInvoiceDetailListAjax', 'SalesController@viewSalesTaxInvoiceDetailListAjax');
    Route::get('/viewSalesTaxInvoiceDetail/{id?}', 'SalesController@viewSalesTaxInvoiceDetail');
    Route::get('/viewReceivedAllVoucher/{id?}', 'SalesController@viewReceivedAllVoucher');
    Route::get('/PrintSalesTaxInvoice/{id?}', 'SalesController@PrintSalesTaxInvoice');
    Route::get('/PrintSalesTaxInvoiceDirect/{id?}', 'SalesController@PrintSalesTaxInvoiceDirect');

    // for credit no
    //te
    Route::get('/CreateCustomerCreditNote', 'SalesController@CreateCustomerCreditNote');

    // credit Not form
    Route::post('/addCustomerCredit_no', 'SalesController@addCustomerCredit_no');
    Route::get('/editCustomerCredit_no/{id}', 'SalesController@editCustomerCredit_no')->name('sales.salereturn.edit');


    // credit note list
    Route::get('/viewCustomerCreditNoteList', 'SalesController@viewCustomerCreditNoteList');

    // for credit note detail

    Route::get('/viewCreditNoteDetail/{id?}', 'SalesController@viewCreditNoteDetail');

    Route::get('/createType', 'SalesController@createType');
    Route::get('/createConditions', 'SalesController@createConditions');

    Route::get('/typeList', 'SalesController@typeList');
    Route::get('/conditionList', 'SalesController@conditionList');
    Route::get('/clientJobList', 'SalesController@clientJobList');

    Route::get('/createSurveyBy', 'SalesController@createSurveyBy');
    Route::get('/branchList', 'SalesController@branchList');

    Route::get('/jobTrackingSheet', 'SalesController@jobTrackingSheet');
    Route::get('/jobTrackingSheetCopy', 'SalesController@jobTrackingSheetCopy');
    Route::get('/surveylist', 'SalesController@surveylist');
    Route::get('/jobtrackinglist', 'SalesController@jobtrackinglist');

    Route::get('/ShowAllImages/{id}', 'SalesController@ShowAllImages');
    Route::get('/ShowAllImagesComplaint/{id}', 'SalesController@ShowAllImagesComplaint');

    Route::get('/addClient', 'SalesController@addClient');
    Route::get('/createBranch', 'SalesController@createBranch');

    Route::get('/addDesc', 'SalesController@addDesc');

    Route::get('/addClientJob', 'SalesController@addClientJob');
    Route::get('/addClientJobAjax', 'SalesController@addClientJobAjax');
    Route::get('/addBranchAjax', 'SalesController@addBranchAjax');
    Route::get('/clientList', 'SalesController@clientList');
    Route::get('/clientBranchList', 'SalesController@clientBranchList');

    Route::get('/invoiceDescList', 'SalesController@invoiceDescList');


    Route::get('/createProductType', 'SalesController@createProductType');
    Route::get('/createResourceAssigned', 'SalesController@createResourceAssigned');
    Route::get('/producttypeList', 'SalesController@producttypeList');
    Route::get('/resourceAssignedList', 'SalesController@resourceAssignedList');
    Route::get('/addquotationForm', 'SalesController@addquotationForm');
    Route::get('/quotationList', 'SalesController@quotationList');

    // Amir new modification 22-sep-2020
    Route::post('/createInvoiceForm', 'SalesController@createInvoiceForm');
    //end

    Route::get('/createInvoiceFormseprate{/id?}', 'SalesController@createInvoiceFormseprate');

    Route::get('/editInvoice/{id?}', 'SalesController@editInvoice');
    Route::get('/editQuotation/{id?}', 'SalesController@editQuotation');

    Route::get('/editClientBranchForm/{id?}', 'SalesController@editClientBranchForm');

    Route::get('/invoiceList', 'SalesController@invoiceList');
    Route::get('/createInvoice', 'SalesController@createInvoice');
    Route::get('/addComplaint', 'SalesController@addComplaint');
    Route::get('/complaintList', 'SalesController@complaintList');
    Route::get('/ViewMultipleDeliveryNotesDetail', 'SalesController@ViewMultipleDeliveryNotesDetail');
    Route::Post('/CreateMultipleSalesTaxInvoices', 'SalesController@CreateMultipleSalesTaxInvoices');
    Route::get('/createTestForm', 'SalesController@createTestForm');
    Route::get('/import_payment_process', 'SalesController@import_payment_process');

    Route::get('/importDocumentList', 'SalesController@importDocumentList');
    Route::get('/view_convert_grn', 'SalesController@view_convert_grn');
    Route::get('/soTrackingPage', 'SalesController@soTrackingPage');
    Route::get('/customer_opening_list', 'SalesController@customer_opening_list');
    Route::get('/soReportPage', 'SalesController@soReportPage');
    Route::get('/dnReportPage', 'SalesController@dnReportPage');
    Route::get('/dn_without_Sales', 'SalesController@dn_without_Sales');
    Route::get('/cogs_si', 'SalesController@cogs_si');
    Route::get('/add_point_of_sale', 'SalesController@add_point_of_sale');
    Route::get('/pos_list', 'SalesController@pos_list');
    Route::get('/po_detail', 'SalesController@po_detail');


});

Route::group(['prefix' => 'sad', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::post('/import_customer_opening', 'SalesAddDetailControler@import_customer_opening');
    Route::any('/createbuldles', 'SalesAddDetailControler@createbuldles');
    Route::post('/pos_return', 'SalesAddDetailControler@pos_return');
    Route::get('/update_cost', 'SalesAddDetailControler@update_cost');
    Route::get('/set_cost', 'SalesAddDetailControler@set_cost');


    Route::post('/addCashCustomerDetail', 'SalesAddDetailControler@addCashCustomerDetail');
    Route::post('/addCreditCustomerDetail', 'SalesAddDetailControler@addCreditCustomerDetail');
    Route::post('/updateCreditCustomerDetail', 'SalesAddDetailControler@updateCreditCustomerDetail');

    Route::post('/addCreditSaleVoucherDetail', 'SalesAddDetailControler@addCreditSaleVoucherDetail');
    Route::post('/addCashSaleVoucherDetail', 'SalesAddDetailControler@addCashSaleVoucherDetail');
    Route::post('/createSalesOrder', 'SalesAddDetailControler@createSalesOrder');
    Route::post('/updateSalesOrder', 'SalesAddDetailControler@updateSalesOrder');


    Route::post('/addeDeliveryNote', 'SalesAddDetailControler@addeDeliveryNote');
    Route::post('/addeDeliveryChallan', 'SalesAddDetailControler@addeDeliveryChallan');
    Route::post('/addeDispatch', 'SalesAddDetailControler@addeDispatch');
    Route::post('/updateDeliveryNote', 'SalesEditDetailController@updateDeliveryNote');
    Route::post('/updateDispatch', 'SalesEditDetailController@updateDispatch');

    Route::post('/addeSalesTaxInvoice', 'SalesAddDetailControler@addeSalesTaxInvoice');

    Route::post('/addeDirectSalesTaxInvoice', 'SalesAddDetailControler@addeDirectSalesTaxInvoice');
    Route::post('/updateDirectSalesTaxInvoice/{id}', 'SalesAddDetailControler@updateDirectSalesTaxInvoice')->name('updateDirectSalesTaxInvoice');
    Route::post('/updateSalesTaxInvoice', 'SalesAddDetailControler@updateSalesTaxInvoice');
    Route::post('/addCreditNote', 'SalesAddDetailControler@addCreditNote');
    Route::post('/updateCreditNote', 'SalesAddDetailControler@updateCreditNote');

    Route::get('/sales_tax_delete', 'SalesAddDetailControler@sales_tax_delete');
    Route::get('/delivery_note_delete', 'SalesAddDetailControler@delivery_note_delete');
    Route::get('/sale_order_delete', 'SalesAddDetailControler@sale_order_delete');
    Route::get('/delivery_not_delete', 'SalesAddDetailControler@delivery_not_delete');
    Route::get('/dispatch_delete', 'SalesAddDetailControler@dispatch_delete');


    Route::post('/addType', 'SalesAddDetailControler@addType');
    Route::post('/addCondition', 'SalesAddDetailControler@addCondition');
    Route::post('/updateCondition', 'SalesAddDetailControler@updateCondition');
    Route::post('/updateProductForm', 'SalesAddDetailControler@updateProductForm');
    Route::post('/updateClientForm', 'SalesAddDetailControler@updateClientForm');
    Route::post('/updateClientBranchForm', 'SalesAddDetailControler@updateClientBranchForm');

    Route::post('/updateProductType', 'SalesAddDetailControler@updateProductType');
    Route::post('/updateResourceAssigned', 'SalesAddDetailControler@updateResourceAssigned');
    Route::post('/updateSurveyByForm', 'SalesAddDetailControler@updateSurveyByForm');
    Route::post('/updateTypeList', 'SalesAddDetailControler@updateTypeList');

    Route::post('/addBranch', 'SalesAddDetailControler@addBranch');
    Route::post('/addClientDetail', 'SalesAddDetailControler@addClientDetail');
    Route::post('/addBranchDetail', 'SalesAddDetailControler@addBranchDetail');

    Route::post('/insertInvoiceDesc', 'SalesAddDetailControler@insertInvoiceDesc');

    Route::post('/addClientJob', 'SalesAddDetailControler@addClientJob');
    Route::get('/addClientJobGET', 'SalesAddDetailControler@addClientJobGET');
    Route::get('/insertBranchAjax', 'SalesAddDetailControler@insertBranchAjax');

    Route::post('/addProductType', 'SalesAddDetailControler@addProductType');
    Route::post('/addResourceAssign', 'SalesAddDetailControler@addResourceAssign');
    Route::post('/addQuotation', 'SalesAddDetailControler@addQuotation');
    Route::post('/updateQuotation', 'SalesAddDetailControler@updateQuotation');
    Route::post('/addInvoiceDetail', 'SalesAddDetailControler@addInvoiceDetail');
    Route::post('/addComplaintDetail', 'SalesAddDetailControler@addComplaintDetail');
    Route::post('/updateInvoiceDetail', 'SalesAddDetailControler@updateInvoiceDetail');
    Route::post('/addTestForm', 'SalesAddDetailControler@addTestForm');
    Route::post('/addConvertGrnData', 'SalesAddDetailControler@addConvertGrnData');
    Route::post('/updateImportDocument', 'SalesAddDetailControler@updateImportDocument');


    Route::post('/add_import_po', 'SalesAddDetailControler@add_import_po');
    Route::post('/update_import_po', 'SalesAddDetailControler@update_import_po');
    Route::post('/update_import_exp', 'SalesAddDetailControler@update_import_exp');

    Route::post('/addCustomerOpeningBalance', 'SalesAddDetailControler@addCustomerOpeningBalance');
    Route::post('/addVendorOpeningBalance', 'SalesAddDetailControler@addVendorOpeningBalance');


    Route::get('/add_pos', 'SalesAddDetailControler@add_pos');
    Route::any('/pos_data', 'SalesAddDetailControler@pos_data');

    Route::post('/addSaleReceiptVoucherDetailAgainstSQ', 'SalesAddDetailControler@addSaleReceiptVoucherDetailAgainstSQ');

});
Route::group(['prefix' => 'sdc', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/getTopFiveSalesReport', 'SalesDataCallController@getTopFiveSalesReport');
    Route::get('/get_bundels_data', 'SalesDataCallController@get_bundels_data');
    Route::get('/getCommissionColumn', 'SalesDataCallController@getCommissionColumn');
    Route::get('/get_commission_data_ajax', 'SalesDataCallController@get_commission_data_ajax');

    Route::get('/getFreightCollectionReport', 'SalesDataCallController@getFreightCollectionReport');
    Route::get('/updateScNo', 'SalesDataCallController@updateScNo');
    Route::get('/update_agent_in_customer', 'SalesDataCallController@update_agent_in_customer');
    Route::get('/import_payment_delete', 'SalesDataCallController@import_payment_delete');
    Route::get('/import_delete', 'SalesDataCallController@import_delete');

    Route::get('/viewPaymentDetail', 'SalesDataCallController@viewPaymentDetail');
    Route::get('/getSoTrackingQtyAjax', 'SalesDataCallController@getSoTrackingQtyAjax');
    Route::get('/getSobyCustomer', 'SalesDataCallController@getSobyCustomer');

    Route::get('/viewCashCustomerList', 'SalesDataCallController@viewCashCustomerList');
    Route::get('/getSalesTaxInvoiceReportData', 'SalesDataCallController@getSalesTaxInvoiceReportData');
    Route::get('/getDnWithoutSalesTax', 'SalesDataCallController@getDnWithoutSalesTax');
    Route::get('/update_cost', 'SalesDataCallController@update_cost');


    Route::get('/viewCreditCustomerList', 'SalesDataCallController@viewCreditCustomerList');
    Route::get('/filterCreditSaleVoucherList', 'SalesDataCallController@filterCreditSaleVoucherList');
    Route::get('/filterCashSaleVoucherList', 'SalesDataCallController@filterCashSaleVoucherList');
    Route::get('/viewCreditSaleVoucherDetail', 'SalesDataCallController@viewCreditSaleVoucherDetail');
    Route::get('/viewCashSaleVoucherDetail', 'SalesDataCallController@viewCashSaleVoucherDetail');
    Route::get('/viewQuotationDetail', 'SalesDataCallController@viewQuotationDetail');
    Route::get('/viewInvoiceDetail', 'SalesDataCallController@viewInvoiceDetail');
    Route::get('/viewComplaintDetail', 'SalesDataCallController@viewComplaintDetail');
    Route::get('/viewQuotationDetailTwo', 'SalesDataCallController@viewQuotationDetailTwo');
    Route::get('/filterByClientAndRegionSurvey', 'SalesDataCallController@filterByClientAndRegionSurvey');
    Route::get('/filterByClientAndRegionQuotation', 'SalesDataCallController@filterByClientAndRegionQuotation');
    Route::get('/filterByClientAndRegionComplaint', 'SalesDataCallController@filterByClientAndRegionComplaint');
    Route::get('/cogs_si', 'SalesDataCallController@cogs_si');

    Route::get('/addData', 'SalesDataCallController@addData');
    Route::get('/approve_invoice/{id?}', 'SalesDataCallController@approve_invoice');
    Route::get('/approve_dispatch/{id?}', 'SalesDataCallController@approve_dispatch');
    Route::get('/viewReceiptVoucher', 'SalesDataCallController@viewReceiptVoucher');
    Route::get('/viewReceiptVoucherPrint', 'SalesDataCallController@viewReceiptVoucherPrint');

    Route::get('/viewReceiptVoucherDirect', 'SalesDataCallController@viewReceiptVoucherDirect');

    Route::get('/check_item_master_code', 'SalesDataCallController@check_item_master_code');
    Route::get('/customer_delete', 'SalesDataCallController@customer_delete');


    Route::get('/get_import_data', 'SalesDataCallController@get_import_data');
    Route::get('/get_import_docs', 'SalesDataCallController@get_import_docs');

    Route::get('/get_pay_form', 'SalesDataCallController@get_pay_form');
    Route::get('/getSalesOrderDateWise', 'SalesDataCallController@getSalesOrderDateWise');
    Route::get('/getSalesOrderDateWiseForDeliveryNote', 'SalesDataCallController@getSalesOrderDateWiseForDeliveryNote');
    Route::get('/getSalesOrderDateWiseForDeliveryChallan', 'SalesDataCallController@getSalesOrderDateWiseForDeliveryChallan');
    Route::get('/getSalesOrderDateWiseForDispatch', 'SalesDataCallController@getSalesOrderDateWiseForDispatch');


    Route::get('/get_batch_details', 'SalesDataCallController@get_batch_details');
    Route::get('/addSalesOrder', 'SalesDataCallController@addSalesOrder');
    // for credit not
    Route::get('/getSalesTaxInvoice', 'SalesDataCallController@getSalesTaxInvoice');
    Route::get('/viewSurveyListDetail', 'SalesDataCallController@viewSurveyListDetail');


    Route::get('/viewJobTrackingListDetail', 'SalesDataCallController@viewJobTrackingListDetail');

    Route::post('/AddJobTrackingDetail', 'SalesDataCallController@AddJobTrackingDetail');
    Route::get('/getTrackingSheet', 'SalesDataCallController@getTrackingSheet');
    Route::get('/getQuatationForm', 'SalesDataCallController@getQuatationForm');
    Route::get('/editConditionForm', 'SalesDataCallController@editConditionForm');
    Route::get('/editProductForm', 'SalesDataCallController@editProductForm');
    Route::get('/editClientForm', 'SalesDataCallController@editClientForm');


    Route::get('/editSurveyByForm', 'SalesDataCallController@editSurveyByForm');
    Route::get('/editProductTypeForm', 'SalesDataCallController@editProductTypeForm');
    Route::get('/editResourceAssignedForm', 'SalesDataCallController@editResourceAssignedForm');
    Route::get('/editTypeList', 'SalesDataCallController@editTypeList');
    Route::get('/ApprovedSurvey', 'SalesDataCallController@ApprovedSurvey');
    Route::get('/ApprovedJobOrder', 'SalesDataCallController@ApprovedJobOrder');
    Route::get('/ApprovedQuotation', 'SalesDataCallController@ApprovedQuotation');
    Route::get('/Activity_log_list_ajax', 'SalesDataCallController@Activity_log_list_ajax');
    Route::get('/getDataClientWise', 'SalesDataCallController@getDataClientWise');
    Route::get('/getRecieptDataClientWise', 'SalesDataCallController@getRecieptDataClientWise');
    Route::get('/getOutstandingReportAjax', 'SalesDataCallController@getOutstandingReportAjax');
    Route::get('/get_debtor_balance_ajax', 'SalesDataCallController@get_debtor_balance_ajax');


    Route::get('/TrackingDelete', 'SalesDataCallController@TrackingDelete');

    Route::get('/invoice_list', 'SalesDataCallController@invoice_list');
    Route::get('/viewImportPoDetail', 'SalesDataCallController@viewImportPoDetail');

    Route::get('/sals_history', 'SalesDataCallController@sals_history');
    Route::get('/delete_sales_return', 'SalesDataCallController@delete_sales_return');
    Route::get('/createCustomerAccount', 'SalesDataCallController@createCustomerAccount');
    Route::get('/getSoReportBySoNo', 'SalesDataCallController@getSoReportBySoNo');
    Route::get('/getSoDetailDateWise', 'SalesDataCallController@getSoDetailDateWise');
    Route::get('/getDnDetailDateWise', 'SalesDataCallController@getDnDetailDateWise');
    Route::get('/getDeliveryNoteFilterWise', 'SalesDataCallController@getDeliveryNoteFilterWise');
    Route::get('/getDeliveryChallanFilterWise', 'SalesDataCallController@getDeliveryChallanFilterWise');
    Route::get('/getSalesTaxInvoiceeFilterWise', 'SalesDataCallController@getSalesTaxInvoiceeFilterWise');
    Route::get('/getCustomerCreditNoteData', 'SalesDataCallController@getCustomerCreditNoteData');
    Route::get('/pos_delete', 'SalesDataCallController@pos_delete');


    //
    Route::get('/pos_list_flter_wise', 'SalesDataCallController@pos_list_flter_wise');
    Route::get('/delete_pos', 'SalesDataCallController@delete_pos');





});

Route::group(['prefix' => 'smfal', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/subItemListLoadDepandentCategoryId', 'SaleMakeFormAjaxLoadController@subItemListLoadDepandentCategoryId');
    Route::get('/addMoreCreditSaleDetailRows', 'SaleMakeFormAjaxLoadController@addMoreCreditSaleDetailRows');
    Route::get('/addMoreCashSaleDetailRows', 'SaleMakeFormAjaxLoadController@addMoreCashSaleDetailRows');
    Route::get('/deleteSurvey', 'SaleMakeFormAjaxLoadController@deleteSurvey');
    Route::get('/jobOrderDelete', 'SaleMakeFormAjaxLoadController@jobOrderDelete');
    Route::get('/job_Order_Jvc_Submitted', 'SaleMakeFormAjaxLoadController@job_Order_Jvc_Submitted');
    Route::get('/QuotationDelete', 'SaleMakeFormAjaxLoadController@QuotationDelete');
    Route::get('/invoiceDelete', 'SaleMakeFormAjaxLoadController@invoiceDelete');
    Route::get('/loadSaleReceiptVoucherDetailBySQNo', 'SaleMakeFormAjaxLoadController@loadSaleReceiptVoucherDetailBySQNo');
    Route::get('/loadSaleReceiptVoucherDetailBySONo', 'SaleMakeFormAjaxLoadController@loadSaleReceiptVoucherDetailBySONo');
    Route::get('/addMoreSalesRvsDetailRows', 'SaleMakeFormAjaxLoadController@addMoreSalesRvsDetailRows');

});

Route::group(['prefix' => 'sa', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/creditSaleVoucherApprove', 'SaleApprovalDataCallController@creditSaleVoucherApprove');
    Route::get('/checkQuantityinStock', 'SaleApprovalDataCallController@checkQuantityinStock');
    Route::get('/cashSaleVoucherDelete', 'SaleApprovalDataCallController@cashSaleVoucherDelete');
    Route::get('/cashSaleVoucherRepost', 'SaleApprovalDataCallController@cashSaleVoucherRepost');
    Route::get('/get_batch_detail', 'SalesDataCallController@get_batch_detail');




});


//End Sales


//Start Report
Route::group(['prefix' => 'reports', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/viewBankDepositSummary', 'ReportsController@viewBankDepositSummary');
    Route::get('/viewBranchPerformanceReports', 'ReportsController@viewBranchPerformanceReports');
    Route::get('/viewBranchExpenseSummaryReports', 'ReportsController@viewBranchExpenseSummaryReports');
    Route::get('/viewBranchExpenseSummaryDetailReports', 'ReportsController@viewBranchExpenseSummaryDetailReports');

    Route::get('/viewInventoryPerformanceDetailReports', 'ReportsController@viewInventoryPerformanceDetailReports');
    Route::get('/p_detail_report', 'ReportsController@p_detail_report');
    Route::get('/create_daily_activity', 'ReportsController@create_daily_activity');
    Route::get('/edit_daily_activity', 'ReportsController@edit_daily_activity');
    Route::post('/insertDailyTask', 'ReportsController@insertDailyTask');
    Route::post('/updateDailyTask', 'ReportsController@updateDailyTask');
    Route::get('/UpdateRemarks', 'ReportsController@UpdateRemarks');
    Route::get('/daily_activity_list', 'ReportsController@daily_activity_list');
    Route::get('/full_daily_activity_list', 'ReportsController@full_daily_activity_list');
    Route::get('/full_daily_activity_list_ajax', 'ReportsController@full_daily_activity_list_ajax');
    Route::get('/get_daily_task', 'ReportsController@get_daily_task');
    Route::get('/get_remarks', 'ReportsController@get_remarks');
    Route::get('/job_Done', 'ReportsController@job_Done');
    Route::get('/job_Delay', 'ReportsController@job_Delay');
    Route::get('/job_Hold', 'ReportsController@job_Hold');

});

Route::group(['prefix' => 'production', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/createProductionRequestForm', 'ProductionController@createProductionRequestForm');
    Route::get('/viewProductionRequestList', 'ProductionController@viewProductionRequestList');
    Route::get('/viewProductionRequestListDetail', 'ProductionController@viewProductionRequestListDetail');
    Route::get('/viewProductionRequestDetail', 'ProductionController@viewProductionRequestDetail');
    Route::get('/editProductionRequestForm/{id}', 'ProductionController@editProductionRequestForm');

    Route::get('/get_machine_data_by_finish_good', 'ProductionController@get_machine_data_by_finish_good');
    Route::get('/get_machine_data_by_finish_good_for_operation', 'ProductionController@get_machine_data_by_finish_good_for_operation');
    Route::get('/production_dashboard', 'ProductionController@production_dashboard');
    Route::get('/create_factory_over_head', 'ProductionController@create_factory_over_head');
    Route::get('/edit_factory_over_head', 'ProductionController@edit_factory_over_head');
    Route::get('/create_labours_working', 'ProductionController@create_labours_working');
    Route::get('/edit_labours_working', 'ProductionController@edit_labours_working');
    Route::get('/labour_working_list', 'ProductionController@labour_working_list');
    Route::get('/create_production_plane', 'ProductionController@create_production_plane');
    Route::get('/edit_production_plane', 'ProductionController@edit_production_plane');
    Route::get('/ppc_issue_item', 'ProductionController@ppc_issue_item');
    Route::get('/create_make_product_issue_items', 'ProductionController@create_make_product_issue_items');
    Route::post('/insert_make_product_issue_items', 'ProductionController@insert_make_product_issue_items')->name('production.make.product');
    Route::get('/ppc_issue_item_filtered', 'ProductionController@ppc_issue_item_filtered');
    Route::get('/production_plan_list', 'ProductionController@production_plan_list');
    Route::get('/get_production_plan_list', 'ProductionController@get_production_plan_list');
    Route::get('/get_route', 'ProductionController@get_route');
    Route::get('/save_issue_material', 'ProductionController@save_issue_material');
    Route::get('/view_plan', 'ProductionController@view_plan');
    Route::get('/material_return', 'ProductionController@material_return');
    Route::get('/return_material', 'ProductionController@return_material');
    Route::get('/conversion', 'ProductionController@conversion');
    Route::get('/get_ledger_data', 'ProductionController@get_ledger_data');
    Route::get('/consumption_edit', 'ProductionController@consumption_edit');
    Route::get('/make_product_issue_items', 'ProductionController@make_product_issue_items');
    Route::get('/conversion_cost', 'ProductionController@conversion_cost');
    Route::get('/get_cost_data', 'ProductionController@get_cost_data');
    Route::get('/view_issuence', 'ProductionController@view_issuence');


    Route::get('/view_cost', 'ProductionController@view_cost');
    Route::get('/production_activity_page', 'ProductionController@production_activity_page');
    Route::get('/production_activity_ajax', 'ProductionController@production_activity_ajax');


    Route::post('/cost_insert', 'ProductionController@cost_insert');



    Route::get('/createDaiForm', 'ProductionController@createDaiForm');
    Route::get('/editDaiForm', 'ProductionController@editDaiForm');

    Route::get('/getCheckingDuplicate', 'ProductionController@getCheckingDuplicate');

    Route::get('/getCharges', 'ProductionController@getCharges');

    Route::get('/get_machine_by_finish_good', 'ProductionController@get_machine_by_finish_good');
    Route::get('/create_die_detail', 'ProductionController@create_die_detail');
    Route::get('/factory_over_head_cateogory_list', 'ProductionController@factory_over_head_cateogory_list');

    Route::get('/create_bom_detail', 'ProductionController@create_bom_detail');

    Route::get('/createMoldForm', 'ProductionController@createMoldForm');
    Route::get('/editMoldForm', 'ProductionController@editMoldForm');
    Route::get('/createMachineForm', 'ProductionController@createMachineForm');
    Route::get('/editMachineForm', 'ProductionController@editMachineForm');
    Route::get('/insert_dai', 'ProductionController@insert_dai');
    Route::get('/update_dai', 'ProductionController@update_dai');

    Route::get('/insert_mold', 'ProductionController@insert_mold');
    Route::get('/update_mold', 'ProductionController@update_mold');

    Route::get('/moldList', 'ProductionController@moldList');
    Route::get('/daiList', 'ProductionController@daiList');
    Route::post('/insert_machine', 'ProductionController@insert_machine');
    Route::post('/update_machine', 'ProductionController@update_machine');
    Route::get('/machineCodeCheck', 'ProductionController@machineCodeCheck');
    Route::get('/machine_list', 'ProductionController@machine_list');
    Route::get('/create_routing', 'ProductionController@create_routing');
    Route::get('/edit_routing', 'ProductionController@edit_routing');
    Route::get('/viewMachineDetail', 'ProductionController@viewMachineDetail');
    Route::get('/viewDieDetail', 'ProductionController@viewDieDetail');
    Route::get('/viewMoldDetail', 'ProductionController@viewMoldDetail');

    Route::get('/create_bill_of_material', 'ProductionController@create_bill_of_material');
    Route::get('/edit_bill_of_material', 'ProductionController@edit_bill_of_material');
    Route::post('/insert_bom', 'ProductionController@insert_bom');
    Route::post('/update_bom', 'ProductionController@update_bom');
    Route::get('/bom_list', 'ProductionController@bom_list');
    Route::get('/viewBomDetail', 'ProductionController@viewBomDetail');
    Route::get('/viewLabourWorkingDetail', 'ProductionController@viewLabourWorkingDetail');

    Route::get('/viewRoutingDetail', 'ProductionController@viewRoutingDetail');

    Route::get('/create_operation', 'ProductionController@create_operation');
    Route::get('/edit_operation', 'ProductionController@edit_operation');
    Route::get('/operation_list', 'ProductionController@operation_list');
    Route::get('/insert_operation', 'ProductionController@insert_operation');
    Route::get('/add_mould_detail', 'ProductionController@add_mould_detail');
    Route::post('/insert_mould_detail', 'ProductionController@insert_mould_detail');
    Route::get('/create_labour_category', 'ProductionController@create_labour_category');
    Route::get('/labour_category_list', 'ProductionController@labour_category_list');
    Route::get('/viewOperationDetail', 'ProductionController@viewOperationDetail');
    Route::get('/get_operation_data', 'ProductionController@get_operation_data');
    Route::get('/factory_overhead_list', 'ProductionController@factory_overhead_list');
    Route::get('/view_factory_overhead_detail', 'ProductionController@view_factory_overhead_detail');
    Route::get('/decline_cost', 'ProductionController@decline_cost');
    Route::get('/approve_plan', 'ProductionController@approve_plan');
    Route::get('/production_detail_report', 'ProductionController@production_detail_report');
    Route::get('/get_production_detail_report', 'ProductionController@get_production_detail_report');



    Route::get('/routing_list', 'ProductionController@routing_list');
    Route::get('/delete_machine_data', 'ProductionController@delete_machine_data');


    Route::get('/costing_finish_goods', 'ProductionController@costing_finish_goods');
    Route::get('/get_finish_goods_data', 'ProductionController@get_finish_goods_data');


    Route::get('/scarp_report', 'ProductionController@scarp_report');
    Route::get('/get_scarp_report', 'ProductionController@get_scarp_report');


    Route::get('/die_usage_report', 'ProductionController@die_usage_report');
    Route::get('/die_mould_usage_report', 'ProductionController@die_mould_usage_report');
    Route::get('/die_usage', 'ProductionController@die_usage');


    Route::get('/mould_usage_report', 'ProductionController@mould_usage_report');
    Route::get('/get_mould_usage_report', 'ProductionController@get_mould_usage_report');
    Route::get('/die_usage', 'ProductionController@die_usage');

    Route::get('/machine_usage_report', 'ProductionController@machine_usage_report');
    Route::get('/get_machine_usage_data', 'ProductionController@get_machine_usage_data');


    Route::get('/costing_finish_goods_estimator', 'ProductionController@costing_finish_goods_estimator');
    Route::get('/get_data', 'ProductionController@get_data');


    Route::get('/finish_good_cost_history', 'ProductionController@finish_good_cost_history');
    Route::get('/get_finish_goods_history', 'ProductionController@get_finish_goods_history');
    Route::any('/add_estimatore', 'ProductionController@add_estimatore');

    Route::get('/mould_usage', 'ProductionController@mould_usage');

});

Route::group(['prefix' => 'prad', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::post('/inser_over_head_category', 'ProductionAddDetailController@inser_over_head_category');
    Route::post('/insert_dai_detail', 'ProductionAddDetailController@insert_dai_detail');
    Route::post('/insert_bom_detail', 'ProductionAddDetailController@insert_bom_detail');
    Route::get('/insert_labour_category', 'ProductionAddDetailController@insert_labour_category');
    Route::post('/insert_operation_detail', 'ProductionAddDetailController@insert_operation_detail');
    Route::post('/update_operation_detail', 'ProductionAddDetailController@update_operation_detail');
    Route::post('/add_route', 'ProductionAddDetailController@add_route');
    Route::post('/update_route', 'ProductionAddDetailController@update_route');
    Route::post('/add_factory_over_head', 'ProductionAddDetailController@add_factory_over_head');
    Route::post('/update_factory_over_head', 'ProductionAddDetailController@update_factory_over_head');
    Route::post('/insert_labours_working', 'ProductionAddDetailController@insert_labours_working');
    Route::post('/update_labours_working', 'ProductionAddDetailController@update_labours_working');
    Route::any('/insert_ppc', 'ProductionAddDetailController@insert_ppc');
    Route::post('/update_ppc', 'ProductionAddDetailController@update_ppc');
    Route::post('/update_internal_consum', 'ProductionAddDetailController@update_internal_consum');

    Route::any('/insert_conversion', 'ProductionAddDetailController@insert_conversion');
    Route::post('/addProductionReuestDetail', 'ProductionAddDetailController@addProductionReuestDetail');
    Route::post('/editProductionReuestDetail', 'ProductionAddDetailController@editProductionReuestDetail');
    Route::post('/approveAndRejectProductionRequest', 'ProductionAddDetailController@approveAndRejectProductionRequest');
    Route::get('/deleteProductionRequest', 'ProductionAddDetailController@deleteProductionRequest');

});

Route::group(['prefix' => 'prd', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/delete_die', 'ProductionDeleteController@delete_die');
    Route::get('/delete_mould', 'ProductionDeleteController@delete_mould');
    Route::get('/delete_machine', 'ProductionDeleteController@delete_machine');
    Route::get('/delete_bom', 'ProductionDeleteController@delete_bom');
    Route::get('/delete_operation', 'ProductionDeleteController@delete_operation');
    Route::get('/delete_route', 'ProductionDeleteController@delete_route');
    Route::get('/delete_factory_over_head', 'ProductionDeleteController@delete_factory_over_head');
    Route::get('/delete_production_plan', 'ProductionDeleteController@delete_production_plan');
});



Route::group(['prefix' => 'rdc', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/inventorySelectList', 'ReportsDataCallController@showBranchInventoryList');
});

Route::get('/testcsvFrom', 'Test_controller@testcsvFrom');
Route::get('/orm_test', 'Test_controller@orm_test');
Route::post('/addcsvDetail', 'Test_controller@addcsvDetail');
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});


Route::group(['prefix' => '', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::post('/insertBankPayment', 'PaymentVoucherDetails@insertBankPayment');
    Route::post('/insertCashPayment', 'PaymentVoucherDetails@insertCashPayment');
    Route::post('/insertJournalVoucher', 'PaymentVoucherDetails@insertJournalVoucher');
    Route::post('/uploadJournalVoucher', 'PaymentVoucherDetails@uploadJournalVoucher');
    Route::post('/insertBankRv', 'PaymentVoucherDetails@insertBankRv');
    Route::post('/insertCashRv', 'PaymentVoucherDetails@insertCashRv');
    Route::post('/insert_new_pv', 'PaymentVoucherDetails@insert_new_pv');
    Route::post('/update_new_pv', 'PaymentVoucherDetails@update_new_pv');



    Route::post('/approvedPaymentVoucher', 'PaymentVoucherDetails@approvedPaymentVoucher');
    Route::post('/updateCashPayment', 'PaymentVoucherDetails@updateCashPayment');
    Route::post('/updateBankRv', 'PaymentVoucherDetails@updateBankRv');
    Route::post('/updateCashRv', 'PaymentVoucherDetails@updateCashRv');
    Route::post('/UpdateJv', 'PaymentVoucherDetails@UpdateJv');

    Route::post('/updateBankPaymentNew', 'PaymentVoucherDetails@updateBankPaymentNew');
    Route::get('/approve_voucher', 'PaymentVoucherDetails@approve_voucher');
    Route::get('/verify_voucher', 'PaymentVoucherDetails@verify_voucher');

    Route::get('/DeletePVoucherActivity', 'PaymentVoucherDetails@DeletePVoucherActivity');
    Route::get('/payment_return', 'PaymentVoucherDetails@payment_return');

    Route::get('/DeletePurchaseVoucher', 'PaymentVoucherDetails@DeletePurchaseVoucher');
    Route::get('/DeleteJVoucherActivity', 'PaymentVoucherDetails@DeleteJVoucherActivity');
    Route::get('/DeleteRVoucherActivity', 'PaymentVoucherDetails@DeleteRVoucherActivity');


    Route::Post('/PaymentPurchaseVoucher', 'PaymentVoucherDetails@PaymentPurchaseVoucher');
    Route::Post('/AddPaymentPurchaseVoucher', 'PaymentVoucherDetails@AddPaymentPurchaseVoucher');
    Route::get('/editPaymentPurchaseVoucher/{id?}', 'PaymentVoucherDetails@editPaymentPurchaseVoucher');
    Route::Post('/updatePaymentPurchaseVoucher', 'PaymentVoucherDetails@updatePaymentPurchaseVoucher');
    Route::get('/DataSortBySupplier', 'PaymentVoucherDetails@DataSortBySupplier');
    Route::get('/getVoucherDetailDataByVoucherNo', 'PaymentVoucherDetails@getVoucherDetailDataByVoucherNo');

    Route::get('/get_advance_amount', 'PaymentVoucherDetails@get_advance_amount');
    Route::get('/adjust_amount/{id?}/{supplier_id}', 'PaymentVoucherDetails@adjust_amount');
    Route::post('/adjust_amount_entry', 'PaymentVoucherDetails@adjust_amount_entry');
    Route::get('/hit_vouchers', 'PaymentVoucherDetails@hit_vouchers');

    Route::get('/CreateInvoiceOpening', 'PaymentVoucherDetails@CreateInvoiceOpening');
    Route::get('/CreateInvoiceOpeningUpdate', 'PaymentVoucherDetails@CreateInvoiceOpeningUpdate');

    Route::post('/update_sales_order', 'PaymentVoucherDetails@update_sales_order');
    Route::get('/company', 'PaymentVoucherDetails@company');
    Route::get('/set_company/{id?}', 'PaymentVoucherDetails@set_company');
    Route::get('/abc', 'PaymentVoucherDetails@abc');
    Route::get('/approve_new_pv', 'PaymentVoucherDetails@approve_new_pv');
});


Route::group(['prefix' => 'ajax', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('get_data', 'AjaxController@get_data');
});


Route::group(['prefix' => 'Finance/CashFlowHead', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {

    Route::get('/', 'Finance\CashFlowHeadController@index');
    Route::get('/create', 'Finance\CashFlowHeadController@create');
    Route::post('/store', 'Finance\CashFlowHeadController@store')->name('CashFlowHead.store');
    Route::get('/edit/{id}', 'Finance\CashFlowHeadController@edit')->name('CashFlowHead.edit');
    Route::post('/update/{id}', 'Finance\CashFlowHeadController@update')->name('CashFlowHead.update');

    Route::get('/delete/{id}', 'Finance\CashFlowHeadController@deleteCashFlowHead')->name('CashFlowHead.delete');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('Finance/CashFlowHead/'));
        // Replace 'CashFlowHead.index' with your actual index route
    })->name('CashFlowHead.cancel');

});

Route::group(['prefix' => 'Finance/CashFlowSubHead', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    Route::get('/', 'Finance\CashFlowHeadController@CashFlowSubHead');
    Route::get('/create', 'Finance\CashFlowHeadController@CashFlowSubHeadcreate');
    Route::post('/store', 'Finance\CashFlowHeadController@CashFlowSubHeadstore')->name('CashFlowSubHead.store');
    Route::get('/edit/{id}', 'Finance\CashFlowHeadController@CashFlowSubHeadedit')->name('CashFlowSubHead.edit');
    Route::post('/update/{id}', 'Finance\CashFlowHeadController@CashFlowSubHeadupdate')->name('CashFlowSubHead.update');

    Route::get('/delete/{id}', 'Finance\CashFlowHeadController@deleteCashFlowSubHead')->name('CashFlowSubHead.delete');

});

Route::get('finance/createPaymentVoucherForm', 'AllInOnePaymentVoucherController@createAllPaymentVoucherForm');
Route::get('finance/viewAllPaymentNewVoucherList', 'AllInOnePaymentVoucherController@viewAllPaymentNewVoucherList');
Route::get('finance/editAllPaymentNew/{id?}', 'AllInOnePaymentVoucherController@editAllPaymentNew');
Route::get('fdc/viewAllPaymentVoucherDetailPrint', 'AllInOnePaymentVoucherController@viewAllPaymentVoucherDetailPrint');
Route::get('fdc/getAllpvsDateAccontWiseAndTypeWise', 'AllInOnePaymentVoucherController@getAllpvsDateAccontWiseAndTypeWise');
Route::post('/insertAllPayment', 'AllInOnePaymentVoucherController@insertAllPayment');
Route::get('/get_pv_merge_chunk', 'AllInOnePaymentVoucherController@get_pv_merge_chunk');
Route::get('/pv_acount_head_po_pi_chunk', 'AllInOnePaymentVoucherController@pv_acount_head_po_pi_chunk');


Route::group(['prefix' => 'far_production', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    // production order
    Route::get('/createProductionOrderForm', 'FarazProductionController@createProductionOrderForm');
    Route::get('/viewProductionOrderList', 'FarazProductionController@viewProductionOrderList');
    Route::get('/viewProductionOrderListDetail', 'FarazProductionController@viewProductionOrderListDetail');
    Route::get('/viewProductionOrderDetail', 'FarazProductionController@viewProductionOrderDetail');
    Route::get('/editProductionOrderForm/{id}', 'FarazProductionController@editProductionOrderForm');

    // production mixture
    Route::get('/addProductionMixture', 'FarazProductionController@addProductionMixture');
    Route::get('/viewProductionMixingList', 'FarazProductionController@viewProductionMixingList');
    Route::get('/viewMixingInfo', 'FarazProductionController@viewMixingInfo');
    Route::get('/mixtureEdit', 'FarazProductionController@mixtureEdit');

    Route::get('/mixtureRolling', 'FarazProductionController@mixtureRolling');
    Route::get('/viewProductionRollingList', 'FarazProductionController@viewProductionRollingList');
    Route::get('/viewProductionRollPrintingList', 'FarazProductionController@viewProductionRollPrintingList');

    Route::get('/rollPrinting', 'FarazProductionController@rollPrinting');


});

Route::group(['prefix' => 'far_prod', 'middleware' => 'mysql2', 'before' => 'csrf'], function () {
    // production order ajax
    Route::post('/addProductionOrderDetail', 'FarazProductionAddDetailController@addProductionOrderDetail');
    Route::get('/deleteProductionOrder', 'FarazProductionAddDetailController@deleteProductionOrder');
    Route::post('/approveAndRejectProductionOrder', 'FarazProductionAddDetailController@approveAndRejectProductionOrder');
    Route::post('/editProductionOrderDetail', 'FarazProductionAddDetailController@editProductionOrderDetail');

    // production mixing ajax
    Route::post('/addProductionMixingDetail', 'FarazProductionAddDetailController@addProductionMixingDetail');

    // production rolling ajax
    Route::post('/addProductionRollingDetail', 'FarazProductionAddDetailController@addProductionRollingDetail')->name('FarProduction.Rolling');

    // production roll printing ajax
    Route::post('/addProductionRollPrintingDetail', 'FarazProductionAddDetailController@addProductionRollPrintingDetail')->name('FarProduction.RollPrint');

});

Route::get('store-items-data', function () {
    DB::Connection('mysql2')->beginTransaction();
    try {
        // Get account IDs for RAW MATERIAL and FINISH GOOD
        $raw_material_acc_id = ReuseableCode::get_acc_id_by_code('1-2-3-1'); // RAW MATERIAL account code
        $finish_good_acc_id = ReuseableCode::get_acc_id_by_code('1-2-3-2'); // FINISH GOOD account code

        // Category IDs (7 = Raw Material, 8 = Finish Good)
        $raw_material_category_id = 7;
        $finish_good_category_id = 8;

        $output = "<h2>Storing Items Data</h2><ul>";

        // Raw Materials Data
        $raw_materials = [
            ['description' => 'Lottrene 474/374/4024', 'opening' => 1676, 'purchase' => 0, 'closing' => 1676, 'consume' => 0],
            ['description' => 'Lottrene Fresh', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'Lottrene Recycle', 'opening' => 524, 'purchase' => 0, 'closing' => 498, 'consume' => 26],
            ['description' => 'LLD 3000', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD 7080', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD 1018', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD Wire Milky # Rangeen', 'opening' => 39, 'purchase' => 0, 'closing' => 39, 'consume' => 0],
            ['description' => 'LLD 1801', 'opening' => 150, 'purchase' => 100, 'closing' => 86, 'consume' => 164],
            ['description' => 'LLD 119', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD Non Prime', 'opening' => 220, 'purchase' => 0, 'closing' => 192, 'consume' => 28],
            ['description' => 'LLD 209 Recycle', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD Wire #2', 'opening' => 60, 'purchase' => 0, 'closing' => 48, 'consume' => 12],
            ['description' => 'LLD Blue #01', 'opening' => 68, 'purchase' => 0, 'closing' => 67, 'consume' => 1],
            ['description' => 'LLD Milky', 'opening' => 300, 'purchase' => 0, 'closing' => 300, 'consume' => 0],
            ['description' => 'LLD Wire #Green', 'opening' => 1, 'purchase' => 0, 'closing' => 1, 'consume' => 0],
            ['description' => 'LLD Wire #Red', 'opening' => 1, 'purchase' => 0, 'closing' => 1, 'consume' => 0],
            ['description' => 'LLD Wire # Blue', 'opening' => 47, 'purchase' => 0, 'closing' => 47, 'consume' => 0],
            ['description' => 'LLD Wire #Yellow', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'LLD Wire #Super', 'opening' => 50, 'purchase' => 0, 'closing' => 44, 'consume' => 6],
            ['description' => 'LLD Wire #Super (Yellow Shade)', 'opening' => 23, 'purchase' => 0, 'closing' => 23, 'consume' => 0],
            ['description' => 'LLD Wire #01', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'HD Recycle', 'opening' => 384, 'purchase' => 0, 'closing' => 357, 'consume' => 27],
            ['description' => 'HD 029', 'opening' => 38, 'purchase' => 0, 'closing' => 0, 'consume' => 38],
            ['description' => 'HD BL-3', 'opening' => 212, 'purchase' => 0, 'closing' => 212, 'consume' => 0],
            ['description' => 'HD F5211', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'HD 2474', 'opening' => 37, 'purchase' => 0, 'closing' => 34, 'consume' => 3],
            ['description' => 'HD 046', 'opening' => 87, 'purchase' => 0, 'closing' => 84, 'consume' => 3],
            ['description' => 'HD TR-144', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'HD F4811', 'opening' => 0, 'purchase' => 150, 'closing' => 0, 'consume' => 150],
            ['description' => 'HD 2640', 'opening' => 3, 'purchase' => 0, 'closing' => 0, 'consume' => 3],
            ['description' => 'HD 720', 'opening' => 0, 'purchase' => 78, 'closing' => 18, 'consume' => 60],
            ['description' => 'HD Wire Rangeen', 'opening' => 50, 'purchase' => 0, 'closing' => 50, 'consume' => 0],
            ['description' => 'Calpet 0099', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'Calpet P50', 'opening' => 273, 'purchase' => 0, 'closing' => 225, 'consume' => 48],
            ['description' => 'PP Film 4128', 'opening' => 40, 'purchase' => 50, 'closing' => 50, 'consume' => 40],
            ['description' => 'PP Film 631', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'PP Tape 550 (China)', 'opening' => 0, 'purchase' => 100, 'closing' => 100, 'consume' => 0],
            ['description' => 'PP Tape 1102', 'opening' => 0, 'purchase' => 0, 'closing' => 0, 'consume' => 0],
            ['description' => 'PP Tape 030', 'opening' => 18, 'purchase' => 0, 'closing' => 0, 'consume' => 18],
        ];

        // Function to extract sub_category prefix from raw material description
        function getRawMaterialSubCategory($description)
        {
            // Check for PP Film or PP Tape first (before PP)
            if (stripos($description, 'PP Film') === 0) {
                return 'PP Film';
            }
            if (stripos($description, 'PP Tape') === 0) {
                return 'PP Tape';
            }
            // Check for other prefixes
            if (stripos($description, 'Lottrene') === 0) {
                return 'Lottrene';
            }
            if (stripos($description, 'LLD') === 0) {
                return 'LLD';
            }
            if (stripos($description, 'HD') === 0) {
                return 'HD';
            }
            if (stripos($description, 'Calpet') === 0) {
                return 'Calpet';
            }
            // Default: return first word
            $words = explode(' ', $description);
            return $words[0];
        }

        // Process Raw Materials
        foreach ($raw_materials as $rawMaterial) {
            $description = $rawMaterial['description'];
            $subCategoryName = getRawMaterialSubCategory($description);

            // Create or get sub_category for raw material group
            $subCategory = DB::Connection('mysql2')->table('sub_category')
                ->where('category_id', $raw_material_category_id)
                ->where('sub_category_name', $subCategoryName)
                ->where('status', 1)
                ->first();

            if (!$subCategory) {
                $subCategoryId = DB::Connection('mysql2')->table('sub_category')->insertGetId([
                    'category_id' => $raw_material_category_id,
                    'sub_category_name' => $subCategoryName,
                    'acc_id' => $raw_material_acc_id ?? 0,
                    'status' => 1,
                    'username' => Auth::user()->name ?? 'System',
                    'created_date' => date('Y-m-d')
                ]);
                $output .= "<li>Created sub_category: $subCategoryName</li>";
            } else {
                if (empty($subCategory->acc_id) && $raw_material_acc_id) {
                    DB::Connection('mysql2')->table('sub_category')
                        ->where('id', $subCategory->id)
                        ->update(['acc_id' => $raw_material_acc_id]);
                }
                $subCategoryId = $subCategory->id;
            }

            // Create subitem if it doesn't exist
            $existingItem = DB::Connection('mysql2')->table('subitem')
                ->where('sub_ic', $description)
                ->where('main_ic_id', $raw_material_category_id)
                ->where('sub_category_id', $subCategoryId)
                ->where('status', 1)
                ->first();

            if (!$existingItem) {
                $itemCode = strtoupper(str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', $description)));
                DB::Connection('mysql2')->table('subitem')->insert([
                    'sub_ic' => $description,
                    'item_code' => $itemCode,
                    'main_ic_id' => $raw_material_category_id,
                    'sub_category_id' => $subCategoryId,
                    'acc_id' => $raw_material_acc_id ?? 0,
                    'status' => 1,
                    'username' => Auth::user()->name ?? 'System',
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'action' => 'create'
                ]);
                $output .= "<li>Created raw material item: $description (Sub-category: $subCategoryName)</li>";
            } else {
                $output .= "<li>Raw material item already exists: $description</li>";
            }
        }

        // Finish Goods Data
        $finish_goods = [
            ['category' => 'Suffyan Shoppers', 'item' => '7×9', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '8×11', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '10×14', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '12x16', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '14x18', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '17x23', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers', 'item' => '18×26', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Blue', 'item' => '7x9 Blue', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Blue', 'item' => '8x11 Blue', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Blue', 'item' => '10x14 Blue', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Blue', 'item' => '12x16 Blue', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Blue', 'item' => '14x18 Blue', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Red', 'item' => '7x9 Red', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Red', 'item' => '8x11 Red', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Red', 'item' => '10x14 Red', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Red', 'item' => '12x16 Red', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan Shoppers Red', 'item' => '14x18 Red', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan shoppers Green', 'item' => '7x9 Green', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan shoppers Green', 'item' => '8x11 Green', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan shoppers Green', 'item' => '10x14 Green', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan shoppers Green', 'item' => '12x16 Green', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan shoppers Green', 'item' => '14x18 Green', 'uom' => '25 KG Per Bag'],
            ['category' => 'My Karachi', 'item' => '14x18 My Karachi', 'uom' => '25 KG Per Bag'],
            ['category' => 'My Karachi', 'item' => '17x23 My Karachi', 'uom' => '25 KG Per Bag'],
            ['category' => 'My Karachi', 'item' => '18x26 My Karachi', 'uom' => '25 KG Per Bag'],
            ['category' => 'My Karachi', 'item' => '20x30 My Karachi', 'uom' => '10 KG Per Bundle'],
            ['category' => 'My Karachi', 'item' => '24x My Karachi', 'uom' => '15 KG Per Bundle'],
            ['category' => 'My Karachi', 'item' => '30x40 My Karachi', 'uom' => '20 KG Per Bundle'],
            ['category' => 'My Karachi', 'item' => '30x50 My Karachi', 'uom' => '20 KG Per Bundle'],
            ['category' => 'Thank You Shoppers', 'item' => '10×14 Thank You', 'uom' => '25 KG Per Bag'],
            ['category' => 'Thank You Shoppers', 'item' => '12×16 Thank You', 'uom' => '25 KG Per Bag'],
            ['category' => 'Thank You Shoppers', 'item' => '14×18 Thank You', 'uom' => '25 KG Per Bag'],
            ['category' => 'Thank You Shoppers', 'item' => '17×23 Thank You', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '3×4 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '4×5 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '5×6 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '5×7 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '6×7 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '6×8 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '6×9 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '7×9 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '7×10 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '8×10 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '8×12 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '10×12 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '10×14 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '10×16 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '10×18 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '12×16 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '12×18 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '14x18 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '14×20 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '14×22 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '14x28 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '16×22 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '18×24 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '20×30 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '24×36 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '30×40 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'HD', 'item' => '30×50 HD', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '2x3 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '2½x3 special', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '2½×3½ special', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '3x4 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '3x5 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '4x4 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '4x5 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '4x6 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x6 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x7 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x8 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x10 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '5x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '6x7 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '6x8 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '6x9 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '6x10 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '6x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '7x8 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '7x9 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '7x10 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '7x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '7x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '8x10 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '8x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '8x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x11 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x13 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x16 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '9x18 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '10x12 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '10x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '10x16 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '10x18 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '10x20 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x14 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x16 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x18 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x20 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x22 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '12x24 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '14x16 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '14x18 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '14x20 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '16x18 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '16x22 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '16x24 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x20 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x22 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x24 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x26 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x28 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '18x30 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '20x24 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '20x26 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '20x30 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '24x36 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '24x48 special pp', 'uom' => '25 KG Per Bag'],
            ['category' => 'Suffyan P.P', 'item' => '30x50 special pp', 'uom' => '25 KG Per Bag'],
        ];

        // Process Finish Goods
        foreach ($finish_goods as $finishGood) {
            $categoryName = $finishGood['category'];
            $itemName = $finishGood['item'];
            $unitOfMeasurement = $finishGood['uom'];

            // Create or get sub_category for finish good category
            $subCategory = DB::Connection('mysql2')->table('sub_category')
                ->where('category_id', $finish_good_category_id)
                ->where('sub_category_name', $categoryName)
                ->where('status', 1)
                ->first();

            if (!$subCategory) {
                $subCategoryId = DB::Connection('mysql2')->table('sub_category')->insertGetId([
                    'category_id' => $finish_good_category_id,
                    'sub_category_name' => $categoryName,
                    'acc_id' => $finish_good_acc_id ?? 0,
                    'status' => 1,
                    'username' => Auth::user()->name ?? 'System',
                    'created_date' => date('Y-m-d')
                ]);
                $output .= "<li>Created sub_category: $categoryName</li>";
            } else {
                if (empty($subCategory->acc_id) && $finish_good_acc_id) {
                    DB::Connection('mysql2')->table('sub_category')
                        ->where('id', $subCategory->id)
                        ->update(['acc_id' => $finish_good_acc_id]);
                }
                $subCategoryId = $subCategory->id;
            }

            // Use just the item name for subitem
            $itemNameForSubitem = $itemName;

            // Create subitem if it doesn't exist
            $existingItem = DB::Connection('mysql2')->table('subitem')
                ->where('sub_ic', $itemNameForSubitem)
                ->where('main_ic_id', $finish_good_category_id)
                ->where('sub_category_id', $subCategoryId)
                ->where('status', 1)
                ->first();

            if (!$existingItem) {
                $itemCode = strtoupper(str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9\s]/', '', $itemNameForSubitem)));
                DB::Connection('mysql2')->table('subitem')->insert([
                    'sub_ic' => $itemNameForSubitem,
                    'item_code' => $itemCode,
                    'main_ic_id' => $finish_good_category_id,
                    'sub_category_id' => $subCategoryId,
                    'acc_id' => $finish_good_acc_id ?? 0,
                    'status' => 1,
                    'username' => Auth::user()->name ?? 'System',
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'action' => 'create'
                ]);
                $output .= "<li>Created finish good item: $itemNameForSubitem (Sub-category: $categoryName)</li>";
            } else {
                $output .= "<li>Finish good item already exists: $itemNameForSubitem</li>";
            }
        }

        DB::Connection('mysql2')->commit();
        $output .= "</ul><h3>All items stored successfully!</h3>";
        return $output;

    } catch (\Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return "<h2>Error</h2><p>Error storing items: " . $e->getMessage() . "</p>";
    }
});

require('Production/Production.php');
require('InventoryMaster/InventoryMaster.php');
require('Import/importMainRoute.php');
require('modules/selling.php');
require('modules/hr.php');
require('modules/selectlist.php');
require('modules/users.php');
require('modules/shah.php');
require('modules/assets.php');