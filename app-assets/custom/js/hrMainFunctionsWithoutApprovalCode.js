var baseUrl = $('#baseUrl').val();

function approveAndRejectTableRecord(companyId, recordId, approval_status, tableName){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;

    $.ajax({
        url : ''+baseUrl+'/cdOne/approveAndRejectTableRecord',
        type: "GET",
        data: {companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status},
        success: function (data) {
            console.log(data);
            if(data == 'error')
            {
                alert('Incorrect Approval Code');
            }
            else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });
}

function approveAndRejectEmployeeLocationAndPromotion(companyId, recordId, approval_status){
    var companyId;
    var recordId;
    var approval_status;

    $.ajax({
        url : ''+baseUrl+'/cdOne/approveAndRejectEmployeeLocationAndPromotion',
        type: "GET",
        data: {companyId: companyId, recordId: recordId, approval_status: approval_status},
        success: function (data) {
            console.log(data);
            if(data == 'error')
            {
                alert('Incorrect Approval Code');
            }
            else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });

}

//function approveAndRejectLeaveApplication(recordId, approval_status){
//    var recordId;
//    var approval_status;
//
//
//    $.ajax({
//        url : ''+baseUrl+'/cdOne/approveAndRejectLeaveApplication',
//        type: "GET",
//        data: {recordId: recordId, approval_status: approval_status},
//        success: function (data) {
//            console.log(data);
//            if(data == 'error')
//            {
//                alert('Incorrect Approval Code');
//            }
//            else{
//                location.reload();
//            }
//        },
//        error: function () {
//            console.log("error");
//        }
//    });
//
//}

function approvePayroll(company_name, company_id, month_year){
    var company_name;
    var company_id;
    var month_year;

    $.ajax({
        url : ''+baseUrl+'/cdOne/approvePayroll',
        type: "GET",
        data: {company_name: company_name, company_id: company_id, month_year:month_year},
        success: function (data) {
            console.log(data);
            if(data == 'error')
            {
                alert('Incorrect Approval Code');
            }
            else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });

}


function approveAndRejectEmployeeExit(companyId, recordId, approval_status, tableName, emp_code, employee_status){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;
    var emp_code;
    var employee_status;


    $.ajax({
        url : ''+baseUrl+'/cdOne/approveAndRejectEmployeeExit',
        type: "GET",
        data: {'emp_code':emp_code,'companyId': companyId, 'recordId': recordId, 'tableName': tableName, 'approval_status': approval_status, 'emp_code': emp_code, 'employee_status': employee_status},
        success: function (data) {
            console.log(data);
            if(data == 'error') {
                alert('Incorrect Approval Code');
            }
            else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });

}

function rejectAdvanceSalaryWithPaySlip(companyId, recordId, approval_status, tableName){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;

    $.ajax({
        url : ''+baseUrl+'/cdOne/rejectAdvanceSalaryWithPaySlip',
        type: "GET",
        data: {companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status},
        success: function (data) {
            console.log(data);
            if(data == 'error')
            {
                alert('Incorrect Approval Code');
            }
            else{
                location.reload();
            }
        },
        error: function () {
            console.log("error");
        }
    });

}


