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
$export=ReuseableCode::check_rights(262);
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
                                <span class="subHeadingLabelClass">So Tracking Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>So No</label>
                            <select name="SoId" id="SoId" class="form-control select2">
                                <option value="">Select So No</option>
                                <?php foreach($SoNo as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo strtoupper($Fil->so_no);?></option>
                                <?php endforeach;?>
                            </select>
                            <span id="SoIdError"></span>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="Get SO Report" class="btn btn-sm btn-primary" onclick="getSoReportBySoNo();" style="margin-top: 32px;" />
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
                    XLSX.writeFile(wb, fn || ('So Tracking <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>

    <script>
        $(document).ready(function(){
            $('#SoId').select2();
        });

        function getSoReportBySoNo()
        {
            var SoId = $('#SoId').val();
            var m = '<?php echo $m?>';
            if(SoId == "")
            {
                $('#SoIdError').html('<p class="text-danger">Please Select So No.</p>');
            }
            else
            {
                $('#SoIdError').html('');
                $('#AjaxData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '/sdc/getSoReportBySoNo',
                    type: 'Get',
                    data: {SoId: SoId,m:m},

                    success: function (response) {

                        $('#AjaxData').html(response);


                    }
                });
            }
        }
    </script>

@endsection