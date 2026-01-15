var baseUrl = $('#url').val();

function rejectAdvanceSalaryWithPaySlip(companyId,recordId,tableName,column){
    var companyId;
    var recordId;
    var tableName;
    var column;
    var functionName = 'cdOne/rejectAdvanceSalaryWithPaySlip';

    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId,tableName:tableName,column:column},
        success:function(data) {
            location.reload();
        }
    });

}


function approveAdvanceSalaryWithPaySlip(companyId,recordId,emp_id){
    var companyId;
    var recordId;
    var emp_id;
    var functionName = 'cdOne/approveAdvanceSalaryWithPaySlip';
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId,emp_id:emp_id},
        success:function(data) {
            location.reload();
        }
    });

}
function deleteAdvanceSalaryWithPaySlip(companyId,recordId,tableName){
    var companyId;
    var recordId;
    var tableName;
    var functionName = 'cdOne/deleteAdvanceSalaryWithPaySlip';

    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId,tableName:tableName},
        success:function(data) {
            location.reload();
        }
    });

}

function approveLoanRequest(companyId,recordId) {
    var companyId;
    var recordId;

    var functionName = 'cdOne/approveLoanRequest';
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId},
        success:function(data) {
            location.reload();
        }
    });

}

function rejectLoanRequest(companyId,recordId) {
    var companyId;
    var recordId;

    var functionName = 'cdOne/rejectLoanRequest';
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId},
        success:function(data) {
            location.reload();
        }
    });

}

function deleteLoanRequest(companyId,recordId)
{
    var companyId;
    var recordId;

    var functionName = 'cdOne/deleteLoanRequest';
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId},
        success:function(data) {
            location.reload();
        }
    });
}

function deleteLeaveApplicationData(companyId,recordId)
{
    var companyId;
    var recordId;
    var functionName = 'cdOne/deleteLeaveApplicationDetail';

if(confirm('Do you Want To Delete Leave Application ?')){
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId},
        success:function(data) {
            location.reload();
        }
    });
}
}


function approveAndRejectEmployeeRequisition(companyId,recordId,approval_status)
{
    var functionName = 'cdOne/approveAndRejectEmployeeRequisition';
   $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        type: "GET",
        data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
        success:function(data) {
            location.reload();
        }
    });
}






