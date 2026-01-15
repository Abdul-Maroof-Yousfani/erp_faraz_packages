<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

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
                                <span class="subHeadingLabelClass">Po Tracking Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Po No</label>
                            <select name="PoId" id="PoId" class="form-control select2">
                                <option value="">Select Po No</option>
                                <?php foreach($PoNo as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo strtoupper($Fil->purchase_request_no);?></option>
                                <?php endforeach;?>
                            </select>
                            <span id="PoIdError"></span>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="Get PO Report" class="btn btn-sm btn-primary" onclick="getPoReportByPoNo();" style="margin-top: 32px;" />
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

    <script>
        $(document).ready(function(){
            $('#PoId').select2();
        });

        function getPoReportByPoNo()
        {
            var PoId = $('#PoId').val();
            var m = '<?php echo $m?>';
            if(PoId == "")
            {
                $('#PoIdError').html('<p class="text-danger">Please Select Po No.</p>');
            }
            else
            {
                $('#AjaxData').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '/purchase/getPoReportByPoNo',
                    type: 'Get',
                    data: {PoId: PoId,m:m},

                    success: function (response)
                    {
                        $('#AjaxData').html(response);
                    }
                });
            }
        }
    </script>

@endsection