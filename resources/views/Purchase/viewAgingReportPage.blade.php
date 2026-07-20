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
       .ApnaBorder{border-color:black !important;border:double;}
.Chnage-bg{background-color:#ccc !important;}
.var-wrapper{padding:20px;}
.var-card{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,0.06);padding:26px 30px;margin-bottom:24px;}
.var-top-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.var-page-title{font-weight:700;color:#1f2a5c;margin:0;font-size:22px;}
.var-btn-row{display:flex;align-items:center;gap:10px;}
.var-btn-row .btn{border-radius:6px;font-weight:600;}
.var-divider{border:none;border-top:1px solid #edeef2;margin:0 0 22px 0;}
.var-filter-row{display:flex;flex-wrap:wrap;align-items:flex-end;gap:24px;}
.var-filter-group{display:flex;flex-direction:column;min-width:150px;}
.var-filter-group.wide{min-width:220px;flex:1;}
.var-filter-group label{font-size:13px;font-weight:500;color:#6b7080;margin-bottom:8px;}
.var-filter-group .form-control{border-radius:8px;border:1px solid #e2e4ea;background:#fafbfc;height:42px;margin:0;}
.var-submit-btn{border-radius:8px;background:#1f2a5c;border-color:#1f2a5c;font-weight:600;height:42px;padding:0 24px;justify-content: center;}
#SupplierError{font-size:12px;}

    </style>
<div class="well_N">
    <div class="var-wrapper">

        <div class="var-card">

            <div class="var-top-row">
                <h3 class="var-page-title">Vendor Ageing Report</h3>
                <div class="var-btn-row">
                    <button class="btn btn-primary" onclick="printView('GetDataAjax','','1')">
                        <span class="glyphicon glyphicon-print"></span> Print
                    </button>
                    <?php if($export == true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                    <?php endif;?>
                </div>
            </div>

            <hr class="var-divider">

            <div class="var-filter-row">
                <div class="var-filter-group">
                    <label for="as_on">As On</label>
                    <input type="date" class="form-control" id="as_on" value="{{date('Y-m-d')}}"/>
                </div>

                <div class="var-filter-group wide">
                    <label for="SupplierId">Supplier</label>
                    <select name="SupplierId" id="SupplierId" class="form-control">
                        <option value="all">All Supplier's</option>
                        <?php foreach($Supplier as $Fil):?>
                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                        <?php endforeach;?>
                    </select>
                    <span id="SupplierError"></span>
                </div>

                <div class="var-filter-group">
                    <label for="ReportType">Report Type</label>
                    <select name="ReportType" id="ReportType" class="form-control">
                        <option value="1">Summary</option>
                        <option value="2">Detail</option>
                    </select>
                </div>

                <div class="var-filter-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-primary var-submit-btn" id="BtnShow" onclick="GetAgingReportData()">Submit</button>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive" id="GetDataAjax">

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