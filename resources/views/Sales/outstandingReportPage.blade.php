<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Debtor Outstanding</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <button class="btn btn-primary" onclick="printView('PrintEmpExitInterviewList','','1')" style="">
                                    <span class="glyphicon glyphicon-print"> </span> Print
                                </button>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>As On:</label>
                            <input type="hidden" name="FromDate" id="FromDate"  value="<?php echo $AccYearFrom;?>" class="form-control" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>"/>
                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $AccYearTo?>" value="<?php echo date('Y-m-d')?>" class="form-control" min="<?php echo $AccYearFrom?>"  />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="">CustomerName</label>
                            <select onchange="" name="ClientId" id="ClientId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($Customer as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">
                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="getRecieptDataClientWise()" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive" id="ShowHide">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var ClientName = $( "#ClientId option:selected" ).text();
            var elt = document.getElementById('ShowHide');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Debtor Outstanding '+ClientName+' <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });

        function getRecieptDataClientWise()
        {


            var ClientId = $('#ClientId').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();

            var m = '<?php echo $m?>';
            $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/sdc/getOutstandingReportAjax',
                type: 'Get',
                data: {ClientId: ClientId,FromDate:FromDate,ToDate:ToDate,m:m},

                success: function (response) {
                    $('#ShowHide').html(response);
                }
            });
        }
        function delete_record(id)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id},

                    success: function (response) {

                        alert('Deleted');
                        $('#' + id).remove();

                    }
                });
            }
            else{}
        }


        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/purchase_voucher_list_ajax',
                type: 'Get',
                data: {from: from,to:to},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

    </script>
    <script !src="">

        $(document).ready(function(){
            $('.BtnEnDs').prop('disabled',true);
        });

        var  temp = [];
        function CheckUncheck(Counter,Id)
        {
            if($("input:checkbox:checked").length > 0)
            {

            }
            else
            {
                temp = [];
            }
            $('.AllCheckbox').each(function()
            {

                if ($(this).is(':checked'))
                {
                    $('.BtnSub'+Id).prop('disabled',false);

                }
                else
                {
                    $('.BtnSub'+Id).prop('disabled',true);
                    //temp =[];
                }

            });


            $(".AddRemoveClass"+Id).each(function() {
                if ($(this).is(':checked')) {
                    var checked = ($(this).attr('id'));
                    temp.push(checked);

                    if(temp.indexOf(checked))
                    {
                        if ($(this).is(':checked')) {
                            alert('Please Checked Same Client and then Create Receipt...!');
                            $(this).prop("checked", false);
                            $('.BtnSub'+Id).prop("disabled", true);
                        }
                    }
                    else
                    {
                        $('.BtnSub'+Id).prop("disabled", false);
                    }
                }
                else
                {

                }
            });



        }


    </script>

@endsection