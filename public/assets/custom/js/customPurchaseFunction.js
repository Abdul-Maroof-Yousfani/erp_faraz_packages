window.onload=function () {
    var pageType = $('#pageType').val();
    if(pageType == 0){
        filterVoucherList();
    }else if(pageType == 1){
        viewDataFilterOneParameter();
    }else if(pageType == 2){
        viewDataFilterTwoParameter();
    }
}
var baseUrl = $('#baseUrl').val();
function viewRangeWiseDataFilter() {
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    // Parse the entries
    var startDate = Date.parse(fromDate);
    var endDate = Date.parse(toDate);
    // Make sure they are valid
    if (isNaN(startDate)) {
        alert("The start date provided is not valid, please enter a valid date.");
        return false;
    }
    if (isNaN(endDate)) {
        alert("The end date provided is not valid, please enter a valid date.");
        return false;
    }
    // Check the date range, 86400000 is the number of milliseconds in one day
    var difference = (endDate - startDate) / (86400000 * 7);
    if (difference < 0) {
        alert("The start date must come before the end date.");
        return false;
    }
    filterVoucherList();
}
function filterVoucherList(){
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var functionName = $('#functionName').val();
    var tbodyId = $('#tbodyId').val();
    var filterType = $('#filterType').val();
    //alert(tbodyId);
    var m = $('#m').val();
    var url = ''+baseUrl+'/'+functionName+'';
    $('#'+tbodyId+'').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
    if(filterType == 1){
        var selectSubDepartmentId = $('#selectSubDepartmentId').val();
        var selectSubDepartment = $('#selectSubDepartmentTwo').val();
        var selectVoucherStatus = $('#selectVoucherStatus').val();
        $.getJSON(url, {fromDate: fromDate, toDate: toDate, m: m,selectSubDepartment:selectSubDepartment,selectSubDepartmentId:selectSubDepartmentId,selectVoucherStatus:selectVoucherStatus}, function (result) {
            $.each(result, function (i, field) {
                $('#' + tbodyId + '').html('' + field + '');
            });
        })
    }else if(filterType == 2){
        var selectSubDepartmentId = $('#selectSubDepartmentId').val();
        var selectSubDepartment = $('#selectSubDepartmentTwo').val();
        var selectSupplierId = $('#selectSupplierId').val();
        var selectSupplier = $('#selectSupplierTwo').val();
        var selectVoucherStatus = $('#selectVoucherStatus').val();
        $.getJSON(url, {fromDate: fromDate, toDate: toDate, m: m,selectSupplier:selectSupplier,selectSupplierId:selectSupplierId,selectSubDepartment:selectSubDepartment,selectSubDepartmentId:selectSubDepartmentId,selectVoucherStatus:selectVoucherStatus}, function (result) {
            $.each(result, function (i, field) {
                $('#' + tbodyId + '').html('' + field + '');
            });
        })
    }else if(filterType == 3){
        var selectBranchId = $('#selectBranchId').val();
        var selectBranch = $('#selectBranchTwo').val();
        var selectVoucherStatus = $('#selectVoucherStatus').val();
        $.getJSON(url, {fromDate: fromDate, toDate: toDate, m: m,selectBranch:selectBranch,selectBranchId:selectBranchId,selectVoucherStatus:selectVoucherStatus}, function (result) {
            $.each(result, function (i, field) {
                $('#' + tbodyId + '').html('' + field + '');
            });
        })
    }
}

function viewDataFilterOneParameter() {
    var paramOne = $('#paramOne').val();

    var functionName = $('#functionName').val();
    var divId = $('#divId').val();
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var parentCode = $('#parentCode').val();
    var m = $('#m').val();
    $('#'+divId+'').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        method:'GET',
        data:{fromDate:fromDate,toDate:toDate,m:m,parentCode:parentCode,paramOne:paramOne},
        error: function(){
            alert('error');
        },
        success: function(response){
            setTimeout(function(){
                $('#'+divId+'').html(response);
            },1000);
        }
    });
}

function viewDataFilterTwoParameter() {
    var paramOne = $('#selectBranchId').val();
    var paramTwo = $('#selectBranchTwo').val();
    var functionName = $('#functionName').val();
    var divId = $('#divId').val();
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    var parentCode = $('#parentCode').val();
    var m = $('#m').val();
    $('#'+divId+'').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        method:'GET',
        data:{fromDate:fromDate,toDate:toDate,m:m,parentCode:parentCode,paramOne:paramOne,paramOne:paramOne},
        error: function(){
            alert('error');
        },
        success: function(response){
            setTimeout(function(){
                $('#'+divId+'').html(response);
            },1000);
        }
    });
}



function deleteCompanyPurchaseTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
    $.ajax({
        url: ''+baseUrl+'/pd/deleteCompanyPurchaseTwoTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function repostCompanyPurchaseTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
    $.ajax({
        url: ''+baseUrl+'/pd/repostCompanyPurchaseTwoTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function approveCompanyPurchaseTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
    $.ajax({
        url: ''+baseUrl+'/pd/approveCompanyPurchaseTwoTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function deleteCompanyPurchaseThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo,tableThree){
    $.ajax({
        url: ''+baseUrl+'/fd/deleteCompanyFinanceThreeTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function repostCompanyPurchaseThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo,tableThree){
    $.ajax({
        url: ''+baseUrl+'/fd/repostCompanyFinanceThreeTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree},
        success:function(data) {
            filterVoucherList();
        }
    });
}

$("#selectSubDepartmentTwo").change(function() {
    var selectSubDepartmentTwo = $('#selectSubDepartment option[value="' + $('#selectSubDepartmentTwo').val() + '"]').data('id');
    $('#selectSubDepartmentId').val(selectSubDepartmentTwo);
}).change();


$("#selectSupplierTwo").change(function() {
    var selectSupplierTwo = $('#selectSupplier option[value="' + $('#selectSupplierTwo').val() + '"]').data('id');
    $('#selectSupplierId').val(selectSupplierTwo);
}).change();

$("#selectBranchTwo").change(function() {
    var selectBranchTwo = $('#selectBranch option[value="' + $('#selectBranchTwo').val() + '"]').data('id');
    $('#selectBranchId').val(selectBranchTwo);
}).change();