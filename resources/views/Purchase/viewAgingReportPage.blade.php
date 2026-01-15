<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(246);

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
    <div class="container">
    <h3 style="font-weight: bold;text-decoration: underline;">Vendor Ageing Report</h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
        <div class="dp_sdw">    
            <div class="row">


                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">

                    <label for="">As  On </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">

                        <input type="date" class="form-control" id="as_on" value="{{date('Y-m-d')}}"/>

                </div>


                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="">Supplier

                    </label>
                </div>



                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <select name="SupplierId" id="SupplierId" class="form-control">
                        <option value="all">All Supplier's</option>
                        <?php foreach($Supplier as $Fil):?>
                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                        <?php endforeach;?>
                    </select>
                    <span id="SupplierError"></span>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="">Report Type</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <select name="ReportType" id="ReportType" class="form-control">
                        <option value="1">Summary</option>
                        <option value="2">Detail</option>
                    </select>
                </div>

                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                    <button type="button" class="btn btn-sm btn-primary" id="BtnShow" onclick="GetAgingReportData()">Submit</button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    &nbsp;
                    </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" onclick="printView('GetDataAjax','','1')" style="">
                        <span class="glyphicon glyphicon-print"></span> Print
                    </button>
                    <?php if($export == true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                    <?php endif;?>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" id="GetDataAjax">

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
                    XLSX.writeFile(wb, fn || ('Vendor Ageing <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        $(document).ready(function(){
            $('#SupplierId').select2();
        });

        function GetAgingReportData(){

            var SupplierId = $('#SupplierId').val();
            var as_on =$('#as_on').val();
            var ReportType =$('#ReportType').val();
            var m = '<?php echo $_GET['m'];?>';
            if(SupplierId !="")
            {
                $('#GetDataAjax').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/getAgingReportDataAjax',
                    type: "GET",
                    data:{m:m,SupplierId:SupplierId,as_on:as_on,ReportType:ReportType},
                    success:function(data) {
                        setTimeout(function(){
                            $('#GetDataAjax').html(data);
                        },1000);
                    }
                });
            }
            else
            {
                $('#SupplierError').html('<p class="text-danger">Please Select Supplier</p>');
            }

        }

    </script>


@endsection