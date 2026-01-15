var baseUrl = $('#baseUrl').val();
var emrNo = $('#emrNo').val();
function approveAndRejectTableRecord(companyId, recordId, approval_status, tableName){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectTableRecord',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject','companyId': companyId, 'recordId': recordId, 'tableName': tableName, 'approval_status': approval_status},
            success: function (data) {
                console.log(data);
                if(data == 'error')
                {
                    alert('Incorrect Approval Code');
                }
                else{
                    if(tableName == 'employee_pending_details') {
                        $('#showDetailModelTwoParamerter').modal('hide');
                        viewEmployeePendingDetails(companyId);
                    }
                    else {
                        location.reload();
                    }

                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}

function rejectAdvanceSalaryWithPaySlip(companyId, recordId, approval_status, tableName){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/rejectAdvanceSalaryWithPaySlip',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject',companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status},
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
}

function approveAndRejectEmployeeLocationAndPromotion(companyId, recordId, approval_status){
    var companyId;
    var recordId;
    var approval_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectEmployeeLocationAndPromotion',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject',companyId: companyId, recordId: recordId, approval_status: approval_status},
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
}

function approveAndRejectLeaveApplication(recordId, approval_status){

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectLeaveApplication',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject', recordId: recordId, approval_status: approval_status},
            success: function (data) {
                console.log(data);
                if(data == 'error1') {
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
}

function approvePayroll(company_name, company_id, month_year){
    var company_name;
    var company_id;
    var month_year;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approvePayroll',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject', company_name: company_name, company_id: company_id, month_year:month_year},
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
}


function approveAndRejectEmployeeExit(companyId, recordId, approval_status, tableName, employee_emr_no, employee_status){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;
    var employee_emr_no;
    var employee_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectEmployeeExit',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject',companyId: companyId, recordId: recordId, tableName: tableName, approval_status: approval_status, employee_emr_no: employee_emr_no, employee_status: employee_status},
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
}

function approveAndRejectAllTableRecords(m, approval_status, tableName)
{
    var approvalCode = prompt('Enter Approval Code !');
    if(!approvalCode){
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectAllTableRecords',
            type: "GET",
            data: {emr_no:emrNo, request_type:'approve_reject', approvalCode:approvalCode, m:m, tableName:tableName, approval_status:approval_status},
            success: function (data) {
                console.log(data);
                if(data == 'error1'){
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
}

function approveAndRejectEmployeePendingDetails(companyId, recordId, approval_status){
    var companyId;
    var recordId;
    var approval_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectEmployeePendingDetails',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject','companyId': companyId, 'recordId': recordId,  'approval_status': approval_status},
            success: function (data) {
                console.log(data);
                if(data == 'error')
                {
                    alert('Incorrect Approval Code');
                }
                else{
                    $('#showDetailModelTwoParamerter').modal('hide');
                    viewEmployeePendingDetails(companyId);
                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}

function resolvePendingComplaints(companyId, recordId, resolve_status,table_name){

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/assetsApi/resolvePendingComplaints',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject','companyId': companyId, 'recordId': recordId,  'resolve_status': resolve_status,table_name:table_name},
            success: function (data) {
                console.log(data);
                if(data == 'error')
                {
                    alert('Incorrect Approval Code');
                }
                else{
                    $('#showDetailModelTwoParamerter').modal('hide');
                    viewPendingComplaints(companyId);
                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}

function approveAndRejectAttendanceOvertime(companyId, recordId, approval_status, tableName){
    var companyId;
    var recordId;
    var tableName;
    var approval_status;

    var approvalCode=prompt('Enter Approval Code !');

    if(!approvalCode)
    {
        alert('Approval Code Required !');
        return false;
    }
    else if(approvalCode != '') {

        $.ajax({
            url : ''+baseUrl+'/cdOne/approveAndRejectAttendanceOvertime',
            type: "GET",
            data: {'emr_no':emrNo,'approvalCode':approvalCode,'request_type':'approve_reject','companyId': companyId, 'recordId': recordId, 'tableName': tableName, 'approval_status': approval_status},
            success: function (data) {
                console.log(data);
                if(data == 'error')
                {
                    alert('Incorrect Approval Code');
                }
                else{
                    $("#showAttendenceReport").click();
                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}