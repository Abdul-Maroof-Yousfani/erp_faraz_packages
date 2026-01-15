<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
    @include('select2');

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">GRN List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="">Supplier</label>
                                        <select onchange="GetDataAjax()" name="SupplierId" id="SupplierId" class="form-control select2">
                                            <option value="">Select Supplier</option>
                                            <?php foreach($Supplier as $Fil):?>
                                            <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <button type="button" class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetDataAjax();" style="margin: 35px 0px 0px 0px;">Get Data</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div></div>

                            <span id="AjaxDataHere"></span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });
        var temp = [];
        function check(Id)
        {
            if($(".checkbox1").is(':checked'))
            {$("#add").prop('disabled', false);}else{$("#add").prop('disabled', true);}
            if($("input:checkbox:checked").length > 0){}else{temp = [];}

            $(".AddRemoveClass"+Id).each(function() {
                if ($(this).is(':checked')) {
                    var checked = ($(this).attr('id'));
                    temp.push(checked);
                    if(temp.indexOf(checked))
                    {
                        if ($(this).is(':checked')) {
                            alert('Please Checked Same Supplier and then Create Voucher...!');
                            $(this).prop("checked", false);

                        }
                    }
                    else
                    {}
                }
                else
                {

                }
            });
        }

        function GetDataAjax()
        {
            var SupplierId = $('#SupplierId').val();
            var m = '<?php echo $_GET['m']?>';
            $('#AjaxDataHere').html('<div class="loader"></div>');
            $.ajax({
                url: '<?php echo url('/')?>/pdc/getDataAjaxSupplierWise',
                type: 'Get',
                data: {SupplierId: SupplierId,m:m},
                success: function (response)
                {
                    $('#AjaxDataHere').html(response);
                }
            });

        }
    </script>

@endsection