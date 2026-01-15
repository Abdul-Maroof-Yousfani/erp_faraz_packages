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
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(303);

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
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Freight Collection Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php if($export == true):?>
                                <a id="dlink"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>From Date</label>
                            <input type="date" class="form-control" id="FromDate" value="<?php echo $currentMonthStartDate?>">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>To Date</label>
                            <input type="date" class="form-control" id="ToDate" value="<?php echo $currentMonthEndDate?>">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="getFreightCollectionReport();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <span id="AjaxData"></span>
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
            var elt = document.getElementById('EmpExitInterviewList');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Freight Collection <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>

    <script>

        function getFreightCollectionReport()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var m = '<?php echo $m?>';

            $('#AjaxData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/sdc/getFreightCollectionReport',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,m:m},

                success: function (response)
                {
                    $('#AjaxData').html(response);
                }
            });

        }
    </script>

@endsection