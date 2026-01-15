<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Complaint List</span>
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
                            <select name="ClientId" id="ClientId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($Client as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="">Region</label>
                            <select name="RegionId" id="RegionId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($Region as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->region_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="filterByClientAndRegion();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Branch Name</th>
                                            <th class="text-center">Branch code</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Contact Person</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">View</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/viewComplaintDetail?m=".$m;
                                            ?>

                                            @foreach($Complaint as $row)
                                                <?php $client_name = CommonHelper::client_name($row->client_name); ?>
                                                <tr id="{{ $row->id }}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center"><?php echo $client_name->client_name?></td>
                                                    <td class="text-center"><?php echo $row->branch_name?></td>
                                                    <td class="text-center"><?php echo $row->branch_code?></td>
                                                    <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->date); ?></td>
                                                    <td class="text-center"><?php echo $row->contanct_person?></td>
                                                    <td class="text-center"><?php echo $row->designation?></td>
                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Complaint Detail')" type="button" class="btn btn-success btn-sm">View</button>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                        </table>
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
            $('#ClientId').select2();
            $('#RegionId').select2();
        });


        function filterByClientAndRegion()
        {

//            var from= $('#from').val();
//            var to= $('#to').val();
            var ClientId = $('#ClientId').val();
            var RegionId = $('#RegionId').val();
            var m = '<?php echo $m?>';
//            if(ClientId !="" || RegionId !="")
//            {
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/filterByClientAndRegionComplaint',
                type: 'Get',
                data: {ClientId: ClientId,RegionId:RegionId,m:m},

                success: function (response) {
                    //alert(response);
                    $('#data').html(response);


                }
            });
//            }
//            else{
//                $('#FilterError').html('<p class="text-danger">Please Select Client OR Region...!</p>');
//            }



        }
        function delete_record(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deleteClientList',
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


        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/purchase_voucher_list_ajax',
                type: 'Get',
                data: {from: from,to:to},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>

@endsection