<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(254);


$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        .ApnaBorder{
            border-color: black !important;
            border: double;
        }
        .Chnage-bg{
            background-color: #ccc !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">Debtors Ageing Report</span>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        <button class="btn btn-primary" onclick="printView('GetDataAjax','','1')" style="">
                            <span class="glyphicon glyphicon-print"></span> Print 
                        </button>
                        <?php if($export == true):?>
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                        <?php endif;?>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="">As On</label>
                        <input type="date" class="form-control" id="as_on" name="as_on" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label for="Buyer">Buyer</label>
                        <select name="CustomerId" id="CustomerId" class="form-control">
                            <option value="all">All Buyer's</option>
                            <?php foreach($Customer as $Fil):?>
                            <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                            <?php endforeach;?>
                        </select>
                        <span id="CustomerError"></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Report Type</label>
                        <select name="ReportType" id="ReportType" class="form-control">
                            <option value="1">Summary</option>
                            <option value="2">Detail</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <button type="button" class="btn btn-sm btn-primary" id="BtnShow" onclick="GetAgingReportData()" style="margin: 35px 0px 0px 0px;">Submit</button>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" id="GetDataAjax" >

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
    </div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('GetDataAjax');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Sales Ageing <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        $(document).ready(function(){
            $('#CustomerId').select2();
        });

        function GetAgingReportData(){

            var CustomerId = $('#CustomerId').val();
            var ReportType =$('#ReportType').val();
            var as_on = $('#as_on').val();
            var m = '<?php echo $_GET['m'];?>';
            if(CustomerId !="")
            {
                $('#GetDataAjax').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/sales/getAgingReportDataAjaxSales',
                    type: "GET",
                    data:{m:m,CustomerId:CustomerId,as_on:as_on,ReportType:ReportType},
                    success:function(data) {
                        setTimeout(function(){
                            $('#GetDataAjax').html(data);
                        },1000);
                    }
                });
            }
            else
            {
                $('#CustomerError').html('<p class="text-danger">Please Select Buyer</p>');
            }

        }

        


    </script>
@endsection