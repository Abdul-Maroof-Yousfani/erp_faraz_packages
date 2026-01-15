<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Purchase Voucher List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="">Client Name</label>
                            <select name="ClientId" id="ClientId" class="form-control select2" onchange="GetBranch('')">
                                <option value="">All</option>
                                <?php foreach($client as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label"> Branch: </label>
                            <select name="BranchId" id="BranchId" class="form-control select2 requiredField"></select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="getDataClientWise();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <span id="Loader"></span>
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

    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });
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


        function getDataClientWise()
        {


            var ClientId = $('#ClientId').val();
            var BranchId = $('#BranchId').val();

            var m = '<?php echo $m?>';
            $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/sdc/getDataClientWise',
                type: 'Get',
                data: {ClientId:ClientId, BranchId:BranchId, m:m},

                success: function (response) {
                    $('#ShowHide').html(response);
                }
            });
        }

        function CreateInvoice(JoId)
        {
            alert('Job Order wali Id == '+JoId);
        }

        function GetBranch()
        {
            var ClientName = $('#ClientId').val();
            if(ClientName !="")
            {
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/GetBranch',
                    type: "GET",
                    data: {ClientName: ClientName,Selected:''},
                    success: function (data) {
                        //alert("Successfully requested address");
                        $("#BranchId").html(data);

                    }
                });
            }
        }

    </script>

@endsection
