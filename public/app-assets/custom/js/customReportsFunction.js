//Start Common Function For Reports
function dataFilterDateWise(dateWiseFunction) {

    var fromDate = $('#startDate').val();
    var toDate = $('#endDate').val();
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
    //if (difference <= 1) {
    //alert("The range must be at least seven days apart.");
    //return false;
    //}
    //return true;
    this[dateWiseFunction]();

}

function dataFilterMonthWise(monthWiseFunction) {
    this[monthWiseFunction]();
}

function dataFilterYearWise(yearWiseFunction) {
    this[yearWiseFunction]();
}
//End Common Function For Reports

function dateWiseFilterViewInventoryPerformanceDetail() {
    alert('Date Wise')
}

function monthWiseFilterViewInventoryPerformanceDetail() {
    alert('Month Wise')
}

function yearWiseFilterViewInventoryPerformanceDetail() {
    alert('Year Wise')
}

//Start viewInventoryPerformanceDetailReports
$("#selectSubItemTwo").change(function() {
    var selectSubItemTwo = $('#selectSubItem option[value="' + $('#selectSubItemTwo').val() + '"]').data('id');
    $('#selectSubItemId').val(selectSubItemTwo);
}).change();

//End viewInventoryPerformanceDetailReports



