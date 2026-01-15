//Start Print
function printView(param1,param2,param3) {


    $( ".qrCodeDiv" ).removeClass( "hidden" );
    if(param2!="")
    {
        $('.'+param2).prop('href','');
    }
    $('.printHide').css('display','none');
    var printContents = document.getElementById(param1).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    //if(param3 == 0){
    location.reload();

    //}
}


function printViewTwo(param1,param2,param3) {


    $( ".qrCodeDiv" ).removeClass( "hidden" );
    if(param2!="")
    {
        $('.'+param2).prop('href','');
    }
    $('.printHide').css('display','none');
    var printContents = document.getElementById(param1).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;

    $('#showDetailModelOneParamerter').css('display','none');
    $('.modal-backdrop').removeClass('modal-backdrop');
    $('body').removeClass('modal-open');
    $("body").css({ 'padding-right' : ''});
    //location.reload();
}
//End Print

//Start Export
function exportView(param1,param2,$param3) {
    $('#'+param1+'').tableToCSV();
}

jQuery.fn.tableToCSV = function() {
    var clean_text = function(text){
        text = text.replace(/"/g, '""');
        return '"'+text+'"';
    };

    $(this).each(function(){
        var table = $(this);
        var caption = $(this).find('caption').text();
        var title = [];
        var rows = [];

        $(this).find('tr').each(function(){
            var data = [];
            $(this).find('th').each(function(){
                var text = clean_text($(this).text());
                title.push(text);
            });
            $(this).find('td').each(function(){
                var text = clean_text($(this).text());
                data.push(text);
            });
            data = data.join(",");
            rows.push(data);
        });
        title = title.join(",");
        rows = rows.join("\n");

        var csv = title + rows;
        var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
        var download_link = document.createElement('a');
        download_link.href = uri;
        var ts = new Date().getTime();
        if(caption==""){
            download_link.download = ts+".csv";
        } else {
            download_link.download = caption+"-"+ts+".csv";
        }
        document.body.appendChild(download_link);
        download_link.click();
        document.body.removeChild(download_link);
    });

};
function expt(param1,param2,$param3) {
    $('.'+param1+'').tableToCSVcls();
}

jQuery.fn.tableToCSVcls = function() {
    var clean_text = function(text){
        text = text.replace(/"/g, '""');
        return '"'+text+'"';
    };

    $(this).each(function(){
        var table = $(this);
        var caption = $(this).find('caption').text();
        var title = [];
        var rows = [];

        $(this).find('tr').each(function(){
            var data = [];
            $(this).find('th').each(function(){
                var text = clean_text($(this).text());
                title.push(text);
            });
            $(this).find('td').each(function(){
                var text = clean_text($(this).text());
                data.push(text);
            });
            data = data.join(",");
            rows.push(data);
        });
        title = title.join(",");
        rows = rows.join("\n");

        var csv = title + rows;
        var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
        var download_link = document.createElement('a');
        download_link.href = uri;
        var ts = new Date().getTime();
        if(caption==""){
            download_link.download = ts+".csv";
        } else {
            download_link.download = caption+"-"+ts+".csv";
        }
        document.body.appendChild(download_link);
        download_link.click();
        document.body.removeChild(download_link);
    });

};
//End Export


//Start Datalist Filter Remove


jQuery(function($) {
    function tog(v){return v?'addClass':'removeClass';}
    $(document).on('input', '.clearable', function(){
        $(this)[tog(this.value)]('x');
    }).on('mousemove', '.x', function( e ){
        $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
    }).on('click', '.onX', function(){
        $(this).removeClass('x onX').val('');
    });

});

//End Datalist Filter Remove


//Start Reports Function
function adminRangeFilter(value){
    var rangeFilterValue = $('#adminRangeFilter').val();
    if(rangeFilterValue == 1){
        $('#startDate').removeAttr('readonly','readonly');
        $('#endDate').removeAttr('readonly','readonly');
        $('#btnDate').removeAttr('disabled','disabled');

        $('#startMonth').attr('readonly','readonly');
        $('#endMonth').attr('readonly','readonly');
        $('#btnMonth').attr('disabled','disabled');

        $('#startYear').attr('disabled','disabled');
        $('#endYear').attr('disabled','disabled');
        $('#btnYear').attr('disabled','disabled');


    }else if(rangeFilterValue == 2){
        $('#startDate').attr('readonly','readonly');
        $('#endDate').attr('readonly','readonly');
        $('#btnDate').attr('disabled','disabled');

        $('#startMonth').removeAttr('readonly','readonly');
        $('#endMonth').removeAttr('readonly','readonly');
        $('#btnMonth').removeAttr('disabled','disabled');

        $('#startYear').attr('disabled','disabled');
        $('#endYear').attr('disabled','disabled');
        $('#btnYear').attr('disabled','disabled');

    }else if(rangeFilterValue == 3){
        $('#startDate').attr('readonly','readonly');
        $('#endDate').attr('readonly','readonly');
        $('#btnDate').attr('disabled','disabled');

        $('#startMonth').attr('readonly','readonly');
        $('#endMonth').attr('readonly','readonly');
        $('#btnMonth').attr('disabled','disabled');

        $('#startYear').removeAttr('disabled','disabled');
        $('#endYear').removeAttr('disabled','disabled');
        $('#btnYear').removeAttr('disabled','disabled');
    }
}
//End Reports Function