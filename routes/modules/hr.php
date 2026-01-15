<?php

//Start HR
Route::group(['prefix' => 'hr','before' => 'csrf'], function () {

    //new code starts
    Route::get('/h', 'HrController@toDayActivity');
    Route::get('/departmentAddNView', 'HrController@departmentAddNView');

    Route::get('/createEmployeeExitInterviewForm', 'HrController@createEmployeeExitInterviewForm');
    Route::get('/createDepartmentForm', 'HrController@createDepartmentForm');
    Route::get('/viewDepartmentList','HrController@viewDepartmentList');
    Route::get('/editDepartmentForm','HrController@editDepartmentForm');

    Route::get('/createSubDepartmentForm', 'HrController@createSubDepartmentForm');
    Route::get('/viewSubDepartmentList','HrController@viewSubDepartmentList');
    Route::get('/editSubDepartmentForm','HrController@editSubDepartmentForm');

    Route::get('/createDesignationForm', 'HrController@createDesignationForm');
    Route::get('/viewDesignationList','HrController@viewDesignationList');
    Route::get('/editDesignationForm','HrController@editDesignationForm');

    Route::get('/createHealthInsuranceForm', 'HrController@createHealthInsuranceForm');
    Route::get('/viewHealthInsuranceList','HrController@viewHealthInsuranceList');
    Route::get('/editHealthInsuranceForm', 'HrController@editHealthInsuranceForm');

    Route::get('/createQualificationForm', 'HrController@createQualificationForm');
    Route::get('/viewQualificationList','HrController@viewQualificationList');
    Route::get('/editQualificationForm', 'HrController@editQualificationForm');

    Route::get('/createLeaveTypeForm', 'HrController@createLeaveTypeForm');
    Route::get('/viewLeaveTypeList','HrController@viewLeaveTypeList');
    Route::get('/editLeaveTypeForm', 'HrController@editLeaveTypeForm');

    Route::get('/createLoanTypeForm', 'HrController@createLoanTypeForm');
    Route::get('/viewLoanTypeList','HrController@viewLoanTypeList');
    Route::get('/editLoanTypeForm', 'HrController@editLoanTypeForm');

    Route::get('/createAdvanceTypeForm', 'HrController@createAdvanceTypeForm');
    Route::get('/viewAdvanceTypeList','HrController@viewAdvanceTypeList');
    Route::get('/editAdvanceTypeForm', 'HrController@editAdvanceTypeForm');

    Route::get('/createJobTypeForm', 'HrController@createJobTypeForm');
    Route::get('/viewJobTypeList','HrController@viewJobTypeList');
    Route::get('/editJobTypeForm', 'HrController@editJobTypeForm');

    Route::get('/createEmployeeForm', 'HrController@createEmployeeForm');
    Route::get('/viewEmployeeList','HrController@viewEmployeeList');
    Route::get('/editEmployeeDetailForm/{id}/{m}', 'HrController@editEmployeeDetailForm');
    Route::get('/uploadEmployeeFileForm', 'HrController@uploadEmployeeFileForm');

    Route::get('/createManageAttendanceForm', 'HrController@createManageAttendanceForm');
    Route::get('/viewEmployeeAttendanceList','HrController@viewEmployeeAttendanceList');
    Route::get('/editEmployeeAttendanceDetailForm','HrController@editEmployeeAttendanceDetailForm');
    Route::get('/ViewAttendanceProgress','HrController@ViewAttendanceProgress');

    Route::get('/createPayrollForm','HrController@createPayrollForm');
    Route::get('/viewPayrollList','HrController@viewPayrollList');
    Route::get('/viewPayrollReport','HrController@viewPayrollReport');
    Route::get('/companyWisePayrollReport','HrController@companyWisePayrollReport');
    Route::get('/viewPayrollReceivingReport','HrController@viewPayrollReceivingReport');

    Route::get('/createMaritalStatusForm', 'HrController@createMaritalStatusForm');
    Route::get('/viewMaritalStatuslist','HrController@viewMaritalStatuslist');
    Route::get('/editMaritalStatusForm', 'HrController@editMaritalStatusForm');

    Route::get('/createAllowanceForm', 'HrController@createAllowanceForm');
    Route::get('/viewAllowanceList','HrController@viewAllowanceList');
    Route::get('/editAllowanceDetailForm', 'HrController@editAllowanceDetailForm');

    Route::get('/createDeductionForm', 'HrController@createDeductionForm');
    Route::get('/viewDeductionList','HrController@viewDeductionList');
    Route::get('/editDeductionDetailForm', 'HrController@editDeductionDetailForm');

    Route::get('/createAdvanceSalaryForm', 'HrController@createAdvanceSalaryForm');
    Route::get('/viewAdvanceSalaryList','HrController@viewAdvanceSalaryList');
    Route::get('/editAdvanceSalaryDetailForm', 'HrController@editAdvanceSalaryDetailForm');

    Route::get('/createLeavesPolicyForm', 'HrController@createLeavesPolicyForm');
    Route::get('/createManualLeaves', 'HrController@createManualLeaves');
    Route::get('/viewLeavesPolicyList','HrController@viewLeavesPolicyList');
    Route::get('/editLeavesPolicyDetailForm', 'HrController@editLeavesPolicyDetailForm');

    Route::get('/createLoanRequestForm', 'HrController@createLoanRequestForm');
    Route::get('/viewLoanRequestList','HrController@viewLoanRequestList');
    Route::get('/editLoanRequestDetailForm', 'HrController@editLoanRequestDetailForm');

    Route::get('/createEOBIForm', 'HrController@createEOBIForm');
    Route::get('/viewEOBIList','HrController@viewEOBIList');
    Route::get('/editEOBIDetailForm', 'HrController@editEOBIDetailForm');

    Route::get('/createTaxesForm', 'HrController@createTaxesForm');
    Route::get('/viewTaxesList','HrController@viewTaxesList');
    Route::get('/editTaxesDetailForm', 'HrController@editTaxesDetailForm');
    Route::get('/viewTaxCriteria','HrController@viewTaxCriteria');

    Route::get('/createBonusForm', 'HrController@createBonusForm');
    Route::get('/viewBonusList','HrController@viewBonusList');
    Route::get('/editBonusDetailForm', 'HrController@editBonusDetailForm');
    Route::get('/IssueBonusDetailForm', 'HrController@IssueBonusDetailForm');

    Route::get('/createLeaveApplicationForm','HrController@createLeaveApplicationForm');
    Route::get('/viewLeaveApplicationList','HrController@viewLeaveApplicationList');
    Route::get('/viewLeaveApplicationRequestList','HrController@viewLeaveApplicationRequestList');
    Route::get('/editLeaveApplicationDetailForm','HrController@editLeaveApplicationDetailForm');
    Route::get('/viewLeaveBalances','HrController@viewLeaveBalances');
    Route::get('/employeeTransferLeaves', 'HrController@employeeTransferLeaves');

    Route::get('/createHolidaysForm','HrController@createHolidaysForm');
    Route::get('/editHolidaysDetailForm','HrController@editHolidaysDetailForm');
    Route::get('/viewHolidaysList','HrController@viewHolidaysList');

    Route::get('/createEmployeeLocationsForm','HrController@createEmployeeLocationsForm');
    Route::get('/viewEmployeeLocationsList','HrController@viewEmployeeLocationsList');
    Route::get('/editEmployeeLocationsDetailForm','HrController@editEmployeeLocationsDetailForm');

    Route::get('/createEmployeeRegionsForm','HrController@createEmployeeRegionsForm');
    Route::get('/viewEmployeeRegionsList','HrController@viewEmployeeRegionsList');
    Route::get('/editEmployeeRegionsDetailForm','HrController@editEmployeeRegionsDetailForm');

    Route::get('/createEmployeeDegreeTypeForm','HrController@createEmployeeDegreeTypeForm');
    Route::get('/viewEmployeeDegreeTypeList','HrController@viewEmployeeDegreeTypeList');
    Route::get('/editEmployeeDegreeTypeDetailForm','HrController@editEmployeeDegreeTypeDetailForm');

    Route::get('/createEmployeeExitClearanceForm','HrController@createEmployeeExitClearanceForm');
    Route::get('/viewEmployeeExitClearanceList','HrController@viewEmployeeExitClearanceList');
    Route::get('/editEmployeeExitClearanceDetailForm','HrController@editEmployeeExitClearanceDetailForm');

    Route::get('/viewEmployeeDegreeTypeList','HrController@viewEmployeeDegreeTypeList');
    Route::get('/editEmployeeDegreeTypeDetailForm','HrController@editEmployeeDegreeTypeDetailForm');

    Route::get('/createEmployeeIdCardRequest','HrController@createEmployeeIdCardRequest');
    Route::get('/viewEmployeeIdCardRequestList','HrController@viewEmployeeIdCardRequestList');
    Route::get('/editEmployeeIdCardRequestDetailForm','HrController@editEmployeeIdCardRequestDetailForm');

    Route::get('/createEmployeePromotionForm','HrController@createEmployeePromotionForm');
    Route::get('/viewEmployeePromotions','HrController@viewEmployeePromotions');
    Route::get('/editEmployeePromotionDetailForm','HrController@editEmployeePromotionDetailForm');

    Route::get('/createEmployeeMedicalForm','HrController@createEmployeeMedicalForm');
    Route::get('/viewEmployeeMedicalList','HrController@viewEmployeeMedicalList');
    Route::get('/editEmployeeMedicalDetailForm','HrController@editEmployeeMedicalDetailForm');
    //new code end


    Route::get('/createEmployeeCategoryForm', 'HrController@createEmployeeCategoryForm');
    Route::get('/viewEmployeeCategoryList','HrController@viewEmployeeCategoryList');
    Route::get('/editEmployeeCategoryDetailForm', 'HrController@editEmployeeCategoryDetailForm');

    Route::get('/createShiftTypeForm', 'HrController@createShiftTypeForm');
    Route::get('/viewShiftTypeList','HrController@viewShiftTypeList');
    Route::get('/editShiftTypeForm', 'HrController@editShiftTypeForm');

    Route::get('/createHiringRequestAddForm','HrController@createHiringRequestAddForm');
    Route::get('/viewHiringRequestList','HrController@viewHiringRequestList');
    Route::get('/editHiringRequestForm','HrController@editHiringRequestForm');


    Route::get('/createCarPolicyForm', 'HrController@createCarPolicyForm');
    Route::get('/viewCarPolicyList','HrController@viewCarPolicyList');
    Route::get('/viewCarPolicyCriteria','HrController@viewCarPolicyCriteria');
    Route::get('/editCarPolicyDetailForm', 'HrController@editCarPolicyDetailForm');

    Route::get('/createVehicleTypeForm', 'HrController@createVehicleTypeForm');
    Route::get('/viewVehicleTypeList','HrController@viewVehicleTypeList');
    Route::get('/editVehicleTypeDetailForm', 'HrController@editVehicleTypeDetailForm');




    Route::get('/createWorkingHoursPolicyDetailForm', 'HrController@createWorkingHoursPolicyDetailForm');
    Route::get('/viewWorkingHoursPolicyList','HrController@viewWorkingHoursPolicyList');
    Route::get('/editWorkingHoursPolicyDetailForm','HrController@editWorkingHoursPolicyDetailForm');



    Route::get('/createEmployeeDepositForm','HrController@createEmployeeDepositForm');
    Route::get('/editEmployeeDepositDetail','HrController@editEmployeeDepositDetail');
    Route::get('/viewEmployeeDepositList','HrController@viewEmployeeDepositList');

    Route::get('/createEmployeeGradesForm','HrController@createEmployeeGradesForm');
    Route::get('/viewEmployeeGradesList','HrController@viewEmployeeGradesList');
    Route::get('/editEmployeeGradesDetailForm','HrController@editEmployeeGradesDetailForm');


    Route::get('/createEmployeeProjectsForm','HrController@createEmployeeProjectsForm');
    Route::get('/viewEmployeeProjectsList','HrController@viewEmployeeProjectsList');
    Route::get('/editEmployeeProjectsDetailForm','HrController@editEmployeeProjectsDetailForm');

    Route::get('/createEmployeeTransferForm','HrController@createEmployeeTransferForm');
    Route::get('/viewEmployeeTransferList','HrController@viewEmployeeTransferList');
    Route::get('/editEmployeeTransferDetailForm','HrController@editEmployeeTransferDetailForm');

    Route::get('/createEmployeeFuelDetailForm','HrController@createEmployeeFuelDetailForm');
    Route::get('/viewEmployeeFuel','HrController@viewEmployeeFuel');
    Route::get('/editEmployeeFuelDetailForm','HrController@editEmployeeFuelDetailForm');
    Route::get('/updateLabourSalaryForm','HrController@updateLabourSalaryForm');

    Route::get('/createHrLetters','HrController@createHrLetters');
    Route::get('/viewHrLetters','HrController@viewHrLetters');
    Route::get('/uploadLettersFile','HrController@uploadLettersFile');


    Route::get('/createEquipmentsForm','HrController@createEquipmentsForm');
    Route::get('/viewEquipmentsList','HrController@viewEquipmentsList');
    Route::get('/editEquipmentDetailForm','HrController@editEquipmentDetailForm');

    Route::get('/createEmployeeEquipmentsForm','HrController@createEmployeeEquipmentsForm');
    Route::get('/viewEmployeeEquipmentsList','HrController@viewEmployeeEquipmentsList');
    Route::get('/editEmployeeEquipmentsDetailForm','HrController@editEmployeeEquipmentsDetailForm');

    Route::get('/createDiseasesForm','HrController@createDiseasesForm');
    Route::get('/viewDiseasesList','HrController@viewDiseasesList');
    Route::get('/editDiseasesDetailForm','HrController@editDiseasesDetailForm');



    Route::get('/viewHrReports','HrController@viewHrReports');

    Route::get('/createTrainingForm','HrController@createTrainingForm');
    Route::get('/viewTrainingList','HrController@viewTrainingList');
    Route::get('/editTrainingDetailForm','HrController@editTrainingDetailForm');

    Route::get('/editFinalSettlementDetailForm','HrController@editFinalSettlementDetailForm');

    Route::get('/viewArrearsList','HrController@viewArrearsList');
    Route::get('/viewS2bReport','HrController@viewS2bReport');

    Route::get('/uploadOtAndFuelFile','HrController@uploadOtAndFuelFile');
    Route::get('/viewS2bNewsReport','HrController@viewS2bNewsReport');
    Route::get('/viewOtandFuelReport','HrController@viewOtandFuelReport');

    Route::get('/createEmployeeGratuityForm','HrController@createEmployeeGratuityForm');

    Route::get('/addEmployeeTransferProject', 'HrController@addEmployeeTransferProject');
    Route::get('/viewProjectTransferList', 'HrController@viewProjectTransferList');
    Route::get('/createProjectTransferForm', 'HrController@createProjectTransferForm');
    Route::get('/editEmployeeTransferProject', 'HrController@editEmployeeTransferProject');

    Route::get('/createAllowanceTypeForm', 'HrController@createAllowanceTypeForm');
    Route::get('/viewAllowanceTypeList', 'HrController@viewAllowanceTypeList');
    Route::get('/editAllowanceTypeForm', 'HrController@editAllowanceTypeForm');

});

Route::group(['prefix' => 'had','before' => 'csrf'], function () {
    Route::post('/addEmployeeTransferProject', 'HrAddDetailControler@addEmployeeTransferProject');
    Route::post('/ediTransferProject', 'HrEditDetailControler@ediTransferProject');

    Route::post('/addEmployeeTransferLeave', 'HrAddDetailControler@addEmployeeTransferLeave');
    Route::post('/addDepartmentDetail', 'HrAddDetailControler@addDepartmentDetail');
    Route::post('/editDepartmentDetail', 'HrEditDetailControler@editDepartmentDetail');

    Route::post('/addSubDepartmentDetail', 'HrAddDetailControler@addSubDepartmentDetail');
    Route::post('/editSubDepartmentDetail', 'HrEditDetailControler@editSubDepartmentDetail');

    Route::post('/addDesignationDetail', 'HrAddDetailControler@addDesignationDetail');
    Route::post('/editDesignationDetail', 'HrEditDetailControler@editDesignationDetail');

    Route::post('/addHealthInsuranceDetail', 'HrAddDetailControler@addHealthInsuranceDetail');
    Route::post('/editHealthInsuranceDetail', 'HrEditDetailControler@editHealthInsuranceDetail');

    Route::post('/addLifeInsuranceDetail', 'HrAddDetailControler@addLifeInsuranceDetail');
    Route::post('/editLifeInsuranceDetail', 'HrEditDetailControler@editLifeInsuranceDetail');

    Route::post('/addJobTypeDetail', 'HrAddDetailControler@addJobTypeDetail');
    Route::post('/editJobTypeDetail', 'HrEditDetailControler@editJobTypeDetail');

    Route::post('/addQualificationDetail', 'HrAddDetailControler@addQualificationDetail');
    Route::post('/editQualificationDetail', 'HrEditDetailControler@editQualificationDetail');

    Route::post('/addLeaveTypeDetail', 'HrAddDetailControler@addLeaveTypeDetail');
    Route::post('/editLeaveTypeDetail', 'HrEditDetailControler@editLeaveTypeDetail');

    Route::post('/addLoanTypeDetail', 'HrAddDetailControler@addLoanTypeDetail');
    Route::post('/editLoanTypeDetail', 'HrEditDetailControler@editLoanTypeDetail');

    Route::post('/addAdvanceTypeDetail', 'HrAddDetailControler@addAdvanceTypeDetail');
    Route::post('/editAdvanceTypeDetail', 'HrEditDetailControler@editAdvanceTypeDetail');

    Route::post('/addShiftTypeDetail', 'HrAddDetailControler@addShiftTypeDetail');
    Route::post('/editShiftTypeDetail', 'HrEditDetailControler@editShiftTypeDetail');

    Route::post('/addHiringRequestDetail','HrAddDetailControler@addHiringRequestDetail');
    Route::post('/editHiringRequestDetail','HrEditDetailControler@editHiringRequestDetail');

    Route::post('/addEmployeeDetail','HrAddDetailControler@addEmployeeDetail');
    Route::post('/editEmployeeDetail','HrEditDetailControler@editEmployeeDetail');
    Route::post('/uploadEmployeeFileDetail','HrAddDetailControler@uploadEmployeeFileDetail');
    Route::post('/editEmployeeLeavingDetail','HrEditDetailControler@editEmployeeLeavingDetail');


    Route::post('/addManageAttendenceDetail','HrAddDetailControler@addManageAttendenceDetail');
    Route::post('/editEmployeeAttendanceDetail','HrEditDetailControler@editEmployeeAttendanceDetail');
    Route::post('/addEmployeeAttendanceFileDetail','HrAddDetailControler@addEmployeeAttendanceFileDetail');
    Route::post('/addDayWiseAttendanceDetail','HrAddDetailControler@addDayWiseAttendanceDetail');

    Route::post('/addPayrollDetail','HrAddDetailControler@addPayrollDetail');

    Route::post('/addMaritalStatusDetail','HrAddDetailControler@addMaritalStatusDetail');
    Route::post('/editMaritalStatusDetail','HrEditDetailControler@editMaritalStatusDetail');


    Route::post('/addEmployeeAllowanceDetail','HrAddDetailControler@addEmployeeAllowanceDetail');
    Route::post('/editAllowanceDetail','HrEditDetailControler@editAllowanceDetail');

    Route::post('/addEmployeeDeductionDetail','HrAddDetailControler@addEmployeeDeductionDetail');
    Route::post('/editDeductionDetail','HrEditDetailControler@editDeductionDetail');

    Route::post('/addAdvanceSalaryDetail','HrAddDetailControler@addAdvanceSalaryDetail');
    Route::post('/editAdvanceSalaryDetail','HrEditDetailControler@editAdvanceSalaryDetail');

    Route::post('/addLeavesPolicyDetail','HrAddDetailControler@addLeavesPolicyDetail');
    Route::post('/editLeavesPolicyDetail','HrEditDetailControler@editLeavesPolicyDetail');

    Route::post('/addVehicleTypeDetail','HrAddDetailControler@addVehicleTypeDetail');
    Route::post('/editVehicleTypeDetail','HrEditDetailControler@editVehicleTypeDetail');

    Route::post('/addCarPolicyDetail','HrAddDetailControler@addCarPolicyDetail');
    Route::post('/editCarPolicyDetail','HrEditDetailControler@editCarPolicyDetail');

    Route::post('/addLoanRequestDetail','HrAddDetailControler@addLoanRequestDetail');
    Route::post('/editLoanRequestDetail','HrEditDetailControler@editLoanRequestDetail');

    Route::post('/addEOBIDetail','HrAddDetailControler@addEOBIDetail');
    Route::post('/editEOBIDetail','HrEditDetailControler@editEOBIDetail');

    Route::post('/addTaxesDetail','HrAddDetailControler@addTaxesDetail');
    Route::post('/editTaxesDetail','HrEditDetailControler@editTaxesDetail');


    Route::post('/addBonusDetail','HrAddDetailControler@addBonusDetail');
    Route::post('/editBonusDetail','HrEditDetailControler@editBonusDetail');

    Route::post('/addEmployeeBonusDetail','HrAddDetailControler@addEmployeeBonusDetail');
    //Route::post('/editBonusDetail','HrEditDetailControler@editBonusDetail');

    Route::post('/addWorkingHoursPolicyDetail','HrAddDetailControler@addWorkingHoursPolicyDetail');
    Route::post('/editWorkingHoursDetail','HrEditDetailControler@editWorkingHoursDetail');

    Route::get('/addHolidaysDetail','HrAddDetailControler@addHolidaysDetail');
    Route::post('/editHolidayDetail','HrEditDetailControler@editHolidayDetail');

    Route::post('/addEmployeeDepositDetail','HrAddDetailControler@addEmployeeDepositDetail');
    Route::post('/editEmployeeDepositDetail','HrEditDetailControler@editEmployeeDepositDetail');

    Route::post('/addAttendanceProgressDetail','HrAddDetailControler@addAttendanceProgressDetail');

    Route::get('/viewAttendanceProgress','HrDataCallController@viewAttendanceProgress');

    Route::post('/addManuallyLeaves', 'HrAddDetailControler@addManuallyLeaves');

    Route::post('/addEmployeeCategoryDetail', 'HrAddDetailControler@addEmployeeCategoryDetail');
    Route::post('/editEmployeeCategoryDetail', 'HrEditDetailControler@editEmployeeCategoryDetail');

    Route::post('/addEmployeeGradesDetail', 'HrAddDetailControler@addEmployeeGradesDetail');
    Route::post('/editEmployeeGradesDetail', 'HrEditDetailControler@editEmployeeGradesDetail');

    Route::post('/addEmployeeLocationsDetail', 'HrAddDetailControler@addEmployeeLocationsDetail');
    Route::post('/editEmployeeLocationsDetail', 'HrEditDetailControler@editEmployeeLocationsDetail');

    Route::post('/addEmployeeRegionsDetail', 'HrAddDetailControler@addEmployeeRegionsDetail');
    Route::post('/editEmployeeRegionsDetail', 'HrEditDetailControler@editEmployeeRegionsDetail');

    Route::post('/addEmployeeDegreeTypeDetail', 'HrAddDetailControler@addEmployeeDegreeTypeDetail');
    Route::post('/editEmployeeDegreeTypeDetail', 'HrEditDetailControler@editEmployeeDegreeTypeDetail');

    Route::post('/addEmployeeExitClearanceDetail','HrAddDetailControler@addEmployeeExitClearanceDetail');
    Route::post('/editEmployeeExitClearanceDetail', 'HrEditDetailControler@editEmployeeExitClearanceDetail');

    Route::post('/addEmployeeIdCardRequestDetail','HrAddDetailControler@addEmployeeIdCardRequestDetail');
    Route::post('/editEmployeeIdCardRequestDetail', 'HrEditDetailControler@editEmployeeIdCardRequestDetail');

    Route::post('/addEmployeePromotionDetail', 'HrAddDetailControler@addEmployeePromotionDetail');
    Route::post('/editEmployeePromotionDetail', 'HrEditDetailControler@editEmployeePromotionDetail');

    Route::post('/addEmployeeProjectsDetail', 'HrAddDetailControler@addEmployeeProjectsDetail');
    Route::post('/editEmployeeProjectsDetail', 'HrEditDetailControler@editEmployeeProjectsDetail');

    Route::post('/addEmployeeTransferDetail', 'HrAddDetailControler@addEmployeeTransferDetail');
    Route::post('/editEmployeeTransferDetail', 'HrEditDetailControler@editEmployeeTransferDetail');

    Route::post('/addEmployeeFuelDetail', 'HrAddDetailControler@addEmployeeFuelDetail');
    Route::post('/editEmployeeFuelDetail', 'HrEditDetailControler@editEmployeeFuelDetail');

    Route::post('/addEmployeeGsspVeriDetail', 'HrAddDetailControler@addEmployeeGsspVeriDetail');

    Route::post('/updateLabourSalary', 'HrEditDetailControler@updateLabourSalary');

    Route::post('/addHrLetters', 'HrAddDetailControler@addHrLetters');
    Route::post('/AddLettersFile', 'HrAddDetailControler@AddLettersFile');


    Route::post('/addEquipmentDetail', 'HrAddDetailControler@addEquipmentDetail');
    Route::post('/editEquipmentsDetail', 'HrEditDetailControler@editEquipmentsDetail');

    Route::post('/addEmployeeEquipmentDetail', 'HrAddDetailControler@addEmployeeEquipmentDetail');
    Route::post('/editEmployeeEquipmentDetail', 'HrEditDetailControler@editEmployeeEquipmentDetail');

    Route::post('/addDiseaseDetail', 'HrAddDetailControler@addDiseaseDetail');
    Route::post('/editDiseaseTypeDetail', 'HrEditDetailControler@editDiseaseTypeDetail');

    Route::post('/addEmployeeMedicalDetail', 'HrAddDetailControler@addEmployeeMedicalDetail');
    Route::post('/editEmployeeMedicalDetail', 'HrEditDetailControler@editEmployeeMedicalDetail');

    Route::post('/addTrainingDetail', 'HrAddDetailControler@addTrainingDetail');
    Route::post('/editTrainingDetail', 'HrEditDetailControler@editTrainingDetail');

    Route::post('/editFinalSettlementDetail', 'HrEditDetailControler@editFinalSettlementDetail');

    Route::post('/uploadOvertimeAndFuelFile', 'HrAddDetailControler@uploadOvertimeAndFuelFile');
    Route::post('/addEmployeeGratuityDetail', 'HrAddDetailControler@addEmployeeGratuityDetail');

    Route::post('/addBuildingsDetail','HrAddDetailControler@addBuildingsDetail');
    Route::post('/editBuildingsDetail','HrEditDetailControler@editBuildingsDetail');

    Route::post('/addAllowanceTypeDetail','HrAddDetailControler@addAllowanceTypeDetail');
    Route::post('/editAllowanceTypeDetail','HrEditDetailControler@editAllowanceTypeDetail');


});

Route::group(['prefix' => 'hdc','before' => 'csrf'], function (){
    Route::get('/viewProjectLetter','HrDataCallController@viewProjectLetter');
    Route::get('/getPendingLeaveApplicationDetail','HrDataCallController@getPendingLeaveApplicationDetail');
    Route::get('/checkManualLeaves','HrDataCallController@checkManualLeaves');
    Route::get('/viewPreviousEmployeeProject','HrDataCallController@viewPreviousEmployeeProject');
    Route::get('/viewPromotionLetter','HrDataCallController@viewPromotionLetter');
    Route::get('/viewTransferLetter','HrDataCallController@viewTransferLetter');
    Route::get('/employeeGetLeavesBalances','HrDataCallController@employeeGetLeavesBalances');
    Route::get('/filterEmployeeList','HrDataCallController@filterEmployeeList');
    Route::get('/filterWorkingHoursPolicList','HrDataCallController@filterWorkingHoursPolicList');
    Route::get('/viewDepartmentList','HrDataCallController@viewDepartmentList');
    Route::get('/viewEmployeeListManageAttendence','HrDataCallController@viewEmployeeListManageAttendence');
    Route::get('/viewAttendanceReport','HrDataCallController@viewAttendanceReport');
    Route::get('/viewDayWiseAttendanceDetail', 'HrDataCallController@viewDayWiseAttendanceDetail');
    Route::get('/viewEmployeePayrollForm','HrDataCallController@viewEmployeePayrollForm');
    Route::get('/viewEmployeePayrollList','HrDataCallController@viewEmployeePayrollList');
    Route::get('/viewEmployeeDetail','HrDataCallController@viewEmployeeDetail');
    Route::get('/viewHiringRequestDetail','HrDataCallController@viewHiringRequestDetail');
    Route::get('/viewLeavePolicyDetail','HrDataCallController@viewLeavePolicyDetail');
    Route::get('/viewCarPolicyCriteria','HrDataCallController@viewCarPolicyCriteria');
    Route::get('/viewCarPolicy','HrDataCallController@viewCarPolicy');
    Route::get('/viewLoanRequestDetail','HrDataCallController@viewLoanRequestDetail');
    Route::get('/viewTaxCriteria','HrDataCallController@viewTaxCriteria');
    Route::get('/viewTax','HrDataCallController@viewTax');
    Route::get('/viewEmployeesBonus','HrDataCallController@viewEmployeesBonus');
    Route::get('/viewLeaveApplicationDetail','HrDataCallController@viewLeaveApplicationDetail');
    Route::get('/viewLeaveApplicationRequestDetail','HrDataCallController@viewLeaveApplicationRequestDetail');
    Route::get('/filterEmployeeAttendanceList','HrDataCallController@filterEmployeeAttendanceList');
    Route::get('/viewEmployeeLeaveDetail','HrDataCallController@viewEmployeeLeaveDetail');
    Route::get('/viewAttendanceProgress','HrDataCallController@viewAttendanceProgress');
    Route::get('/viewPayrollReport','HrDataCallController@viewPayrollReport');
    Route::get('/companyWisePayrollReport','HrDataCallController@companyWisePayrollReport');
    Route::get('/viewPayrollReceivingReport','HrDataCallController@viewPayrollReceivingReport');
    Route::get('/viewApplicationDateWise','HrDataCallController@viewApplicationDateWise');
    Route::get('/viewHolidayDate','HrDataCallController@viewHolidayDate');
    Route::get('/viewOverTimeDetail','HrDataCallController@viewOverTimeDetail');
    Route::get('/viewLateArrivalDetail','HrDataCallController@viewLateArrivalDetail');
    Route::get('/viewLeaveApplicationClientForm','HrDataCallController@viewLeaveApplicationClientForm');
    Route::get('/viewHolidaysMonthWise','HrDataCallController@viewHolidaysMonthWise');
    Route::get('/viewHalfDaysDetail','HrDataCallController@viewHalfDaysDetail');
    Route::get('/viewOvertimeHoursDetail','HrDataCallController@viewOvertimeHoursDetail');
    Route::get('/viewEmployeeDepositDetail','HrDataCallController@viewEmployeeDepositDetail');
    Route::get('/viewLeaveBalances','HrDataCallController@viewLeaveBalances');
    Route::get('/viewRangeWiseLeaveApplicationsRequests','HrDataCallController@viewRangeWiseLeaveApplicationsRequests');
    Route::get('/viewLeavesBalances','HrDataCallController@viewLeavesBalances');
    Route::get('/viewEmployeeExitClearanceForm', 'HrDataCallController@viewEmployeeExitClearanceForm');
    Route::get('/viewEmployeeExitClearanceDetail', 'HrDataCallController@viewEmployeeExitClearanceDetail');
    Route::post('/checkEmrNoExist','HrDataCallController@checkEmrNoExist');
    Route::get('/viewEmployeeIdCardRequest', 'HrDataCallController@viewEmployeeIdCardRequest');
    Route::get('/viewEmployeeIdCardRequestDetail', 'HrDataCallController@viewEmployeeIdCardRequestDetail');
    Route::get('/viewEmployeePreviousPromotionsDetail', 'HrDataCallController@viewEmployeePreviousPromotionsDetail');
    Route::get('/viewEmployeeDocuments', 'HrDataCallController@viewEmployeeDocuments');
    Route::get('/viewEmployeeFilteredList', 'HrDataCallController@viewEmployeeFilteredList');
    Route::get('/viewEmployeePreviousTransferDetail', 'HrDataCallController@viewEmployeePreviousTransferDetail');
    Route::get('/viewExpiryAndUpcomingAlerts', 'HrDataCallController@viewExpiryAndUpcomingAlerts');
    Route::get('/viewEmployeeFuelDetailForm', 'HrDataCallController@viewEmployeeFuelDetailForm');
    Route::get('/viewEmployeeFuelDetail', 'HrDataCallController@viewEmployeeFuelDetail');
    Route::get('/viewEmployeeFilteredFuelDetail', 'HrDataCallController@viewEmployeeFilteredFuelDetail');
    Route::get('/viewUpcomingBirthdaysDetail', 'HrDataCallController@viewUpcomingBirthdaysDetail');
    Route::get('/viewEmployeeCnicExpireDetail', 'HrDataCallController@viewEmployeeCnicExpireDetail');
    Route::get('/viewEmployeeOverAgeDetail', 'HrDataCallController@viewEmployeeOverAgeDetail');
    Route::get('/viewNonVerifiedNadraEmployeeDetail', 'HrDataCallController@viewNonVerifiedNadraEmployeeDetail');
    Route::get('/viewNonVerifiedPoliceEmployeeDetail', 'HrDataCallController@viewNonVerifiedPoliceEmployeeDetail');
    Route::get('/viewEmployeeGsspVeriDetail', 'HrDataCallController@viewEmployeeGsspVeriDetail');
    Route::get('/viewEmployeeMissingImageDetail', 'HrDataCallController@viewEmployeeMissingImageDetail');
    Route::get('/viewEmployeeWarningLetterDetail', 'HrDataCallController@viewEmployeeWarningLetterDetail');
    Route::get('/viewDemiseEmployeeDetail', 'HrDataCallController@viewDemiseEmployeeDetail');
    Route::get('/viewEmployeeProbationPeriodOverDetail', 'HrDataCallController@viewEmployeeProbationPeriodOverDetail');
    Route::get('/viewHolidayCalender', 'HrDataCallController@viewHolidayCalender');

    Route::get('/viewHrEmployeeAuditDetail', 'HrDataCallController@viewHrEmployeeAuditDetail');
    Route::get('/viewHrLetters','HrDataCallController@viewHrLetters');
    Route::get('/getEmployeeDateOfJoining','HrDataCallController@getEmployeeDateOfJoining');
    Route::get('/getConclusionLettersDate','HrDataCallController@getConclusionLettersDate');
    Route::get('/getWithoutIncrementLettersDate','HrDataCallController@getWithoutIncrementLettersDate');
    Route::get('/getIncrementLettersDetails','HrDataCallController@getIncrementLettersDetails');
    Route::get('/getTransferLettersDetails','HrDataCallController@getTransferLettersDetails');
    Route::get('/viewEmployeeEquipmentsForm','HrDataCallController@viewEmployeeEquipmentsForm');
    Route::get('/viewEmployeeEquipmentsDetail','HrDataCallController@viewEmployeeEquipmentsDetail');
    Route::get('/viewEmployeePreviousAllowancesDetail','HrDataCallController@viewEmployeePreviousAllowancesDetail');


    Route::get('/viewHrWarningLetter/{id}/{company_id}','HrDataCallController@viewHrWarningLetter');
    Route::get('/viewHrMfmSouthIncrementLetter/{id}/{company_id}','HrDataCallController@viewHrMfmSouthIncrementLetter');
    Route::get('/viewHrMfmSouthWithoutIncrementLetter/{id}/{company_id}','HrDataCallController@viewHrMfmSouthWithoutIncrementLetter');
    Route::get('/viewHrContractConclusionLetter/{id}/{company_id}','HrDataCallController@viewHrContractConclusionLetter');
    Route::get('/viewHrTerminationFormat1Letter/{id}/{company_id}','HrDataCallController@viewHrTerminationFormat1Letter');
    Route::get('/viewHrTerminationFormat2Letter/{id}/{company_id}','HrDataCallController@viewHrTerminationFormat2Letter');
    Route::get('/viewHrTransferLetter/{id}/{company_id}','HrDataCallController@viewHrTransferLetter');

    Route::get('/viewEmployeeCnicCopy','HrDataCallController@viewEmployeeCnicCopy');
    Route::get('/viewEmployeeExperienceDocuments','HrDataCallController@viewEmployeeExperienceDocuments');
    Route::post('/checkCnicNoExist','HrDataCallController@checkCnicNoExist');

    Route::get('/viewMasterTableForm','HrDataCallController@viewMasterTableForm');

    Route::get('/viewDayWiseAttendence','HrDataCallController@viewDayWiseAttendence');
    Route::get('/viewMonthWiseAttendence','HrDataCallController@viewMonthWiseAttendence');
    Route::get('/viewUploadFileAttendance','HrDataCallController@viewUploadFileAttendance');
    Route::get('/viewEmployeeEobiCopy','HrDataCallController@viewEmployeeEobiCopy');
    Route::get('/viewEmployeeInsuranceCopy','HrDataCallController@viewEmployeeInsuranceCopy');
    Route::get('/viewEmployeeEobiDetail','HrDataCallController@viewEmployeeEobiDetail');
    Route::get('/viewEmployeeInsuranceDetail','HrDataCallController@viewEmployeeInsuranceDetail');
    Route::get('/viewEmployeeSettlementDetail','HrDataCallController@viewEmployeeSettlementDetail');

    Route::get('/viewHrLetterFiles','HrDataCallController@viewHrLetterFiles');
    Route::get('/viewEmployeeMedicalDocuments','HrDataCallController@viewEmployeeMedicalDocuments');

    Route::get('/getMoreEmployeesDetail','HrDataCallController@getMoreEmployeesDetail');
    Route::get('/viewTrainingDetail','HrDataCallController@viewTrainingDetail');

    Route::get('/viewFinalSettlement','HrDataCallController@viewFinalSettlement');
    Route::get('/viewFinalSettlementDetail','HrDataCallController@viewFinalSettlementDetail');

    Route::get('/viewPendingRequests','HrDataCallController@viewPendingRequests');
    Route::get('/viewDashboardDetails','HrDataCallController@viewDashboardDetails');
    Route::get('/viewS2bReport','HrDataCallController@viewS2bReport');
    Route::get('/viewS2bNewsReport','HrDataCallController@viewS2bNewsReport');
    Route::get('/viewOtAndFuelReport','HrDataCallController@viewOtAndFuelReport');
    Route::get('/viewAdvanceSalaryDetail','HrDataCallController@viewAdvanceSalaryDetail');
    Route::get('/viewAllowanceDetail','HrDataCallController@viewAllowanceDetail');
    Route::get('/viewDeductionDetail','HrDataCallController@viewDeductionDetail');
    Route::get('/viewHolidaysDetail','HrDataCallController@viewHolidaysDetail');
    Route::get('/viewEmployeePromotionDetailForLog','HrDataCallController@viewEmployeePromotionDetailForLog');
    Route::get('/viewEmployeeTransferDetailForLog','HrDataCallController@viewEmployeeTransferDetailForLog');
    Route::get('/viewLeaveApplicationRequestDetailForLog','HrDataCallController@viewLeaveApplicationRequestDetailForLog');

    Route::get('/viewEmployeeGratuityForm','HrDataCallController@viewEmployeeGratuityForm');
    Route::get('/viewBuildingsDetail','HrDataCallController@viewBuildingsDetail');

    Route::get('/viewEmployeesDetail', 'HrDataCallController@viewEmployeesDetail');

    Route::get('/viewAbsentAndLeaveApplicationDetail', 'HrDataCallController@viewAbsentAndLeaveApplicationDetail');
    Route::get('/viewLateArrivalDetail', 'HrDataCallController@viewLateArrivalDetail');
    Route::get('/viewOvertimeHoursDetail', 'HrDataCallController@viewOvertimeHoursDetail');
    Route::get('/viewEmployeePromotionsDetail', 'HrDataCallController@viewEmployeePromotionsDetail');
    Route::get('/viewAllowanceForm', 'HrDataCallController@viewAllowanceForm');



});

Route::group(['prefix' => 'hmfal','before' => 'csrf'], function () {
    Route::get('/makeFormEmployeeDetail','HrMakeFormAjaxLoadController@makeFormEmployeeDetail');
    Route::get('/addMoreAllowancesDetailRows','HrMakeFormAjaxLoadController@addMoreAllowancesDetailRows');
    Route::get('/addMoreDeductionsDetailRows','HrMakeFormAjaxLoadController@addMoreDeductionsDetailRows');
    Route::get('/makeFormDepartmentDetail','HrMakeFormAjaxLoadController@makeFormDepartmentDetail');
    Route::get('/makeFormSubDepartmentDetail','HrMakeFormAjaxLoadController@makeFormSubDepartmentDetail');
    Route::get('/makeFormDesignationDetail','HrMakeFormAjaxLoadController@makeFormDesignationDetail');
    Route::get('/makeFormHealthInsuranceDetail','HrMakeFormAjaxLoadController@makeFormHealthInsuranceDetail');
    Route::get('/makeFormEmployeeCategoryDetail','HrMakeFormAjaxLoadController@makeFormEmployeeCategoryDetail');
    Route::get('/makeFormJobTypeDetail','HrMakeFormAjaxLoadController@makeFormJobTypeDetail');
    Route::get('/makeFormQualificationDetail','HrMakeFormAjaxLoadController@makeFormQualificationDetail');
    Route::get('/makeFormLeaveTypeDetail','HrMakeFormAjaxLoadController@makeFormLeaveTypeDetail');
    Route::get('/makeFormLoanTypeDetail','HrMakeFormAjaxLoadController@makeFormLoanTypeDetail');
    Route::get('/makeFormAdvanceTypeDetail','HrMakeFormAjaxLoadController@makeFormAdvanceTypeDetail');
    Route::get('/makeFormShiftTypeDetail','HrMakeFormAjaxLoadController@makeFormShiftTypeDetail');
    Route::get('/makeFormLoanRequestDetail','HrMakeFormAjaxLoadController@makeFormLoanRequestDetail');
    Route::get('/makeFormEOBIDetail','HrMakeFormAjaxLoadController@makeFormEOBIDetail');
    Route::get('/makeFormTaxesDetail','HrMakeFormAjaxLoadController@makeFormTaxesDetail');
    Route::get('/makeFormBonusDetail','HrMakeFormAjaxLoadController@makeFormBonusDetail');
    Route::get('/makeFormEmployeeLeaveApplicationDetailByEmployeeId','HrMakeFormAjaxLoadController@makeFormEmployeeLeaveApplicationDetailByEmployeeId');
    Route::get('/makeFormEmployeeInActive','HrMakeFormAjaxLoadController@makeFormEmployeeInActive');



});

Route::group(['prefix' => 'hadbac','before' => 'csrf'], function () {
    Route::get('/addLeaveApplicationDetail', 'HrAddDetailByAjaxController@addLeaveApplicationDetail');
    Route::get('/addLeaveApplicationDetail', 'HrAddDetailByAjaxController@addLeaveApplicationDetail');
    Route::get('/addEmployeeSixthMonthAuditDetail', 'HrAddDetailByAjaxController@addEmployeeSixthMonthAuditDetail');
    Route::get('/addEmployeeTwelfthMonthAuditDetail', 'HrAddDetailByAjaxController@addEmployeeTwelfthMonthAuditDetail');
    Route::get('/addMasterTableDetail', 'HrAddDetailByAjaxController@addMasterTableDetail');
    Route::get('/addManualyAttendance', 'HrAddDetailByAjaxController@addManualyAttendance');

    //  Route::get('/', 'HrAddDetailByAjaxController@');

});

Route::group(['prefix' => 'hedbac','before' => 'csrf'], function () {
    Route::get('/EditEmployeeCarPolicyDetail', 'HrEditDetailByAjaxController@EditEmployeeCarPolicyDetail');
    Route::get('/EditEmployeeTaxDetail', 'HrEditDetailByAjaxController@EditEmployeeTaxDetail');
    Route::get('/editLeaveApplicationDetail', 'HrEditDetailByAjaxController@editLeaveApplicationDetail');
    Route::get('/NeglectEmployeeAttendance', 'HrEditDetailByAjaxController@NeglectEmployeeAttendance');
    Route::get('/updateDemiseEmployeeReview', 'HrEditDetailByAjaxController@updateDemiseEmployeeReview');


});
//End HR
Route::get('/deleteMasterTableReceord', 'DeleteMasterTableRecordController@deleteMasterTableReceord');

//Start Company Database Record Delete
Route::group(['prefix' => 'cdOne','before' => 'csrf'], function () {
    Route::get('/deleteRowCompanyHRRecordsProjectTransfer', 'DeleteCompanyHRRecordsController@deleteRowCompanyHRRecordsProjectTransfer');
    Route::get('/deleteProjectLetter', 'DeleteCompanyHRRecordsController@deleteProjectLetter');
    Route::get('/approveAndRejectLeaveApplication3', 'DeleteCompanyHRRecordsController@approveAndRejectLeaveApplication3');
    Route::get('/approveAndRejectLeaveApplication2', 'DeleteCompanyHRRecordsController@approveAndRejectLeaveApplication2');
    Route::get('/deletePromotionLetter', 'DeleteCompanyHRRecordsController@deletePromotionLetter');
    Route::get('/deleteTransferLetter', 'DeleteCompanyHRRecordsController@deleteTransferLetter');
    Route::get('/deleteRowCompanyHRRecords', 'DeleteCompanyHRRecordsController@deleteRowCompanyHRRecords');
    Route::get('/deleteRowCompanyRecords', 'DeleteCompanyRecordsController@deleteRowCompanyRecords');
    Route::get('/repostOneTableRecords', 'DeleteCompanyHRRecordsController@repostOneTableRecords');
    Route::get('/approveOneTableRecords', 'DeleteCompanyHRRecordsController@approveOneTableRecords');
    Route::get('/rejectOneTableRecords', 'DeleteCompanyHRRecordsController@rejectOneTableRecords');
    Route::get('/repostMasterTableRecords', 'DeleteCompanyHRRecordsController@repostMasterTableRecords');
    Route::get('/approvePayroll', 'DeleteCompanyHRRecordsController@approvePayroll');
    Route::get('/deleteEmployeePayroll', 'DeleteCompanyHRRecordsController@deleteEmployeePayroll');
    Route::get('/approveAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@approveAdvanceSalaryWithPaySlip');
    Route::get('/rejectAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@rejectAdvanceSalaryWithPaySlip');
    Route::get('/deleteAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@deleteAdvanceSalaryWithPaySlip');
    Route::get('/deleteLeavesDataPolicyRows', 'DeleteCompanyHRRecordsController@deleteLeavesDataPolicyRows');
    Route::get('/approveLoanRequest', 'DeleteCompanyHRRecordsController@approveLoanRequest');
    Route::get('/rejectLoanRequest', 'DeleteCompanyHRRecordsController@rejectLoanRequest');
    Route::get('/deleteLoanRequest', 'DeleteCompanyHRRecordsController@deleteLoanRequest');
    Route::get('/deleteEmployeeBonus', 'DeleteCompanyHRRecordsController@deleteEmployeeBonus');
    Route::get('/deleteLeaveApplicationDetail', 'DeleteCompanyHRRecordsController@deleteLeaveApplicationDetail');
    Route::get('/approveAndRejectLeaveApplication', 'DeleteCompanyHRRecordsController@approveAndRejectLeaveApplication');
    Route::get('/approveAndRejectRequestHiring', 'DeleteCompanyHRRecordsController@approveAndRejectRequestHiring');
    Route::get('/deleteEmployeeAttendance', 'DeleteCompanyHRRecordsController@deleteEmployeeAttendance');
    Route::get('/deleteEmployeeDocument', 'DeleteCompanyHRRecordsController@deleteEmployeeDocument');
    Route::get('/approveAndRejectTableRecord', 'DeleteCompanyHRRecordsController@approveAndRejectTableRecord');
    Route::get('/approveAndRejectEmployeeLocationAndPromotion', 'DeleteCompanyHRRecordsController@approveAndRejectEmployeeLocationAndPromotion');
    Route::get('/deleteEmployeeLocationAndPromotion', 'DeleteCompanyHRRecordsController@deleteEmployeeLocationAndPromotion');
    Route::get('/approveMonthViseTableRecord', 'DeleteCompanyHRRecordsController@approveMonthViseTableRecord');
    Route::get('/deleteEmployeeGsspDocument', 'DeleteCompanyHRRecordsController@deleteEmployeeGsspDocument');
    Route::get('/test', 'DeleteCompanyHRRecordsController@test');
    Route::get('/printAndDeliverIdCard', 'DeleteCompanyHRRecordsController@printAndDeliverIdCard');
    Route::get('/deleteEmployeeSixthMonthAuditDetail', 'DeleteCompanyHRRecordsController@deleteEmployeeSixthMonthAuditDetail');
    Route::get('/deleteEmployeeTwelfthMonthAuditDetail', 'DeleteCompanyHRRecordsController@deleteEmployeeTwelfthMonthAuditDetail');
    Route::get('/deleteEmployeeCnicCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeCnicCopy');
    Route::get('/deleteEmployeeExperienceDocuments', 'DeleteCompanyHRRecordsController@deleteEmployeeExperienceDocuments');
    Route::get('/approveAndRejectEmployeeExit', 'DeleteCompanyHRRecordsController@approveAndRejectEmployeeExit');
    Route::get('/deleteEmployeeEobiCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeEobiCopy');
    Route::get('/deleteEmployeeInsuranceCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeInsuranceCopy');
    Route::get('/deleteEmployeeEquipments', 'DeleteCompanyHRRecordsController@deleteEmployeeEquipments');
    Route::get('/deleteEmployee', 'DeleteCompanyHRRecordsController@deleteEmployee');
    Route::get('/restoreEmployee', 'DeleteCompanyHRRecordsController@restoreEmployee');
    Route::get('/deleteEmployeeExitClearance', 'DeleteCompanyHRRecordsController@deleteEmployeeExitClearance');
    Route::get('/approveAndRejectForAjaxPages', 'DeleteCompanyHRRecordsController@approveAndRejectForAjaxPages');




});