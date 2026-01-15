<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(252);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}



$data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
$from=$data[0];

$Date = date('d-M-Y');
?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Vendor Summary</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                                            <label>From Date</label>
                                                            <input type="Date" name="FromDate" id="FromDate" max="<?php ?>" value="<?php echo $from;?>" class="form-control" />
                                                        </div>
                                                       
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>As On</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php  ?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="vendor_summery();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="vendor_summery_append"></div>
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
            var elt = document.getElementById('export_table_to_excel_1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Vendor Summary <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });


        function vendor_summery() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#vendor_summery_append').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/vendor_summery',
                method:'GET',
                data:{FromDate:FromDate,ToDate:ToDate,m:m},
                error: function(){
                    alert('error');
                },
                success: function(response)
                {

                        $('#vendor_summery_append').html(response);

                }
            });
        }

    </script>
@endsection