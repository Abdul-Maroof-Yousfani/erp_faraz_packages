window.onload=function () {
    var pageType = $('#pageType').val();
    if(pageType == 0){
        filterVoucherList();
    }else if(pageType == 1){
        viewDataFilterOneParameter();
    }else if(pageType == 2){
        viewFilterDateWiseStockInventoryReport();
    }
}
var baseUrl = $('#baseUrl').val();

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
    var orderby  = $('#orderby').val();
    if (orderby == null){
      var   orderby='';
    }
    //alert(tbodyId);
    var m = $('#m').val();
    var url = ''+baseUrl+'/'+functionName+'';
    $('#'+tbodyId+'').html('<tr><td colspan="9"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
    if(filterType == 1){
        var selectSubDepartmentId = $('#selectSubDepartmentId').val();
        var selectSubDepartment = $('#selectSubDepartmentTwo').val();
        var selectVoucherStatus = $('#selectVoucherStatus').val();
        $.getJSON(url, {fromDate: fromDate, toDate: toDate,orderby:orderby, m: m,selectSubDepartment:selectSubDepartment,selectSubDepartmentId:selectSubDepartmentId,selectVoucherStatus:selectVoucherStatus}, function (result) {
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
        $.getJSON(url, {fromDate: fromDate, toDate: toDate,orderby:orderby, m: m,selectSupplier:selectSupplier,selectSupplierId:selectSupplierId,selectSubDepartment:selectSubDepartment,selectSubDepartmentId:selectSubDepartmentId,selectVoucherStatus:selectVoucherStatus}, function (result) {
            $.each(result, function (i, field) {
                $('#' + tbodyId + '').html('' + field + '');
            });
        })
    }
}

function viewFilterDateWiseStockInventoryReport() {
    var paramOne = $('#paramOne').val();
    if(paramOne != '') {
        var res = paramOne.split("^");
        var categoryId = res[0];
        var subItemId = res[1];
    }else{
        var categoryId = 0;
        var subItemId = 0;
    }
    var paramTwo = $('#paramTwo').val();
    var functionName = $('#functionName').val();
    var tbodyId = $('#tbodyId').val();
    //alert(tbodyId);
    var m = $('#m').val();
    var url = ''+baseUrl+'/'+functionName+'';
    $('#'+tbodyId+'').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.getJSON(url, { categoryId:categoryId,subItemId:subItemId,paramTwo:paramTwo,m:m} ,function(result){
        $.each(result, function(i, field){
            $('#'+tbodyId+'').html(''+field+'');
        });
    })
}



function deleteCompanyStoreThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,tableOne,tableTwo,tableThree,columnTwo,columnThree){
    $.ajax({
        url: ''+baseUrl+'/std/deleteCompanyStoreThreeTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree,columnTwo:columnTwo,columnThree:columnThree},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function repostCompanyStoreThreeTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,tableOne,tableTwo,tableThree,columnTwo,columnThree,columnFour) {
    $.ajax({
        url: ''+baseUrl+'/std/repostCompanyStoreThreeTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,tableOne:tableOne,tableTwo:tableTwo,tableThree:tableThree,columnTwo:columnTwo,columnThree:columnThree,columnFour:columnFour},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function approvePurchaseRequest(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo) {
    $.ajax({
        url: ''+baseUrl+'/std/approvePurchaseRequest',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function approvePurchaseRequestSale(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo) {
    $.ajax({
        url: ''+baseUrl+'/std/approvePurchaseRequestSale',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
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
