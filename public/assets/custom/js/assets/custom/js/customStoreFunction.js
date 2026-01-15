window.onload=function () {
    var pageType = $('#pageType').val();
    if(pageType == 0){
        filterVoucherList();
    }else if(pageType == 1){
        viewDataFilterOneParameter();
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
    //alert(tbodyId);
    var m = $('#m').val();
    var url = ''+baseUrl+'/'+functionName+'';
    $('#'+tbodyId+'').html('<tr><td colspan="8"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
    $.getJSON(url, { fromDate:fromDate,toDate:toDate,m:m} ,function(result){
        $.each(result, function(i, field){
            $('#'+tbodyId+'').html(''+field+'');
            //console.log(field);
        });
    })
    /*
    $.ajax({
        url: ''+baseUrl+'/'+functionName+'',
        method:'GET',
        dataType: "json",
        data:{fromDate:fromDate,toDate:toDate,m:m},
        error: function(){
            alert('error');
        },
        success: function(response){
            setTimeout(function(){
                $('#'+tbodyId+'').html(response);
            },1000);
        }
    });*/
}


function deleteCompanyPurchaseTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
    $.ajax({
        url: ''+baseUrl+'/fd/deleteCompanyFinanceTwoTableRecords',
        type: "GET",
        data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
        success:function(data) {
            filterVoucherList();
        }
    });
}

function repostCompanyPurchaseTwoTableRecords(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo){
    $.ajax({
        url: ''+baseUrl+'/fd/repostCompanyFinanceTwoTableRecords',
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



