<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(253);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">Debtor Summary</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <button class="btn btn-primary" onclick="printViewTwo('PrintEmpExitInterviewList','linkRem','1')" style="">
                                            <span class="glyphicon glyphicon-print"></span> Print
                                        </button>
                                        <?php if($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>As On</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Format</label>
                                                            <select name="Format" id="Format" class="form-control" >
                                                                <option value="1">Summary</option>
                                                 
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="ReceivablSummaryReport();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="receivablSummaryReport"></div>
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
            var elt = document.getElementById('receivablSummaryReport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Debtor Summary <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });


        function ReceivablSummaryReport() {

            var ToDate = $('#ToDate').val();
            var Format = $('#Format').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#receivablSummaryReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/receivablSummaryReport',
                method:'GET',
                data:{ToDate:ToDate,Format:Format,m:m},
                error: function(){
                    alert('error');
                },
                success: function(response)
                {

                    $('#receivablSummaryReport').html(response);

                }
            });
        }


    </script>
@endsection