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
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Job Order List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>

                    <?php
                    $data=DB::Connection('mysql2')->select('select count(j.job_order_id)countt,c.client_name from job_order as j
INNER JOIN
client c
ON
c.id=j.client_name
where j.status=1 GROUP by j.client_name order by countt desc');

                    ?>
                    <div class="lineHeight">&nbsp;</div>
                    <?php /*?>
                    <div class="row container-fluid">

                        <?php  foreach($data as $row): ?>
                        {{--<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="border: solid 1px #ccc">--}}
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="border: solid 1px #ccc">
                            <p><?php echo $row->client_name?> <span class="badge badge-primary">&nbsp;<?php echo ' '.$row->countt?></span></p>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <?php */?>

                    <div class="lineHeight">&nbsp;</div>
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

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="">Client Job</label>
                            <select name="ClientJobId" id="ClientJobId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($ClientJob as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_job?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                        {{--<label>From Date</label>--}}
                        {{--<input type="Date" name="from" id="from"  value="< ?php echo date('Y-m-d');?>" class="form-control" />--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>--}}
                        {{--<input type="text" readonly class="form-control text-center" value="Between" /></div>--}}
                        {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                        {{--<label>To Date</label>--}}
                        {{--<input type="Date" name="to" id="to" max="< ?php ?>" value="< ?php echo date('Y-m-d');?>" class="form-control" />--}}
                        {{--</div>--}}


                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="filterByClientAndRegion();" style="margin-top: 32px;" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="FilterError"></div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>

                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <span id="Loader"></span>
                            <div class="row" id="ShowHide">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Job Order No</th>
                                            <th class="text-center">Ordered Date</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center"><a href="#" onclick="branchDetail('sales/addBranchAjax','<?= $m ?>')" class=""> Branch: </a></th>
                                            <th class="text-center">Update Btn</th>
                                            <th class="text-center">Client Job</th>
                                            <th class="text-center">Contact Person</th>
                                            <th class="text-center">Job Location</th>
                                            <th class="text-center">Client Address</th>

                                            {{--<th class="text-center">Invoice</th>--}}
                                            {{--<th class="text-center">Edit</th>--}}
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php

                                            $counter = 1;$total=0;
                                            $edit_url= url('/purchase/editJobOrder/');
                                            $paramOne = "pdc/viewJobOrderDetail?m=".$m;
                                            /*
                                            ?>

                                            {{--@foreach($joborder as $row)--}}
                                            {{--<?php $client_name = CommonHelper::client_name($row->client_name);--}}
                                            {{--$count= CommonHelper::check_job_order_data_count($row->job_order_id);--}}
                                            {{--?>--}}
                                            {{--<tr id="<?= $row->job_order_id ?>" @if($count==0) style="background-color: lightcoral" @endif  @if($row->date==date('Y-m-d')) style="background-color: lightyellow" @endif title="{{$row->job_order_id}}">--}}
                                            {{--<td class="text-center">{{$counter++}}</td>--}}
                                            {{--<td class="text-center">{{ $row->job_order_no }}</td>--}}
                                            {{--<td title="{{$row->job_order_id}}" class="text-center"><?php  echo CommonHelper::changeDateFormat($row->date_ordered);?></td>--}}
                                            {{--<td class="text-center"><?php echo $client_name->client_name?></td>--}}
                                            {{--<td class="text-center">{{ $row->client_job }}</td>--}}
                                            {{--<td class="text-center">{{ $row->contact_person }}</td>--}}
                                            {{--<td class="text-center">{{ $row->job_location }}</td>--}}
                                            {{--<td class="text-center">{{ $row->client_address }}</td>--}}
                                            {{--<td class="text-center">--}}
                                            {{--<?php if($row->type == "")--}}
                                            {{--{echo '<span class="badge badge-success" style="background-color: #5bc0de !important">Direct</span>';}--}}
                                            {{--elseif($row->type == 1){echo '<span class="badge badge-success" style="background-color: #5bc0de !important">Through Quotation</span>';}--}}
                                            {{--else{'<span class="badge badge-success" style="background-color: #5bc0de !important">Through Survey</span>';}?>--}}
                                            {{--</td>--}}
                                            {{--<td id="{{$row->job_order_id}}" class="">--}}
                                            {{--<?php if($row->jo_status == 1):?>--}}
                                            {{--<span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>--}}
                                            {{--<?php else:?>--}}
                                            {{--<span class="badge badge-success" style="background-color: #00c851 !important">Success</span>--}}
                                            {{--<?php endif;?>--}}
                                            {{--</td>--}}
                                            {{--<td class="text-center">  <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->job_order_id ?>','Job Order')" type="button" class="btn btn-success btn-xs">View</button>  </td>--}}
                                            {{--<td class="text-center" id="BtnAppend<?php echo $row->job_order_id?>">--}}
                                            {{--<?php if($row->jo_status == 2 ):--}}
                                            {{--if($row->invoice_created == 0){--}}
                                            {{--?>--}}
                                            {{--<a class="btn btn-info btn-xs" id="BtnInvoice<?php echo $row->job_order_id?>" href="<?php echo URL('sales/createInvoiceForm/'.$row->job_order_id.'?&&m='.$m)?>">Create Invoice</a>--}}
                                            {{--<?php }else{?>--}}
                                            {{--<span class="badge badge-success" style="background-color: #00c851 !important">Invoice Created</span>--}}
                                            {{--<?php }--}}
                                            {{--endif;?>--}}
                                            {{--</td>--}}
                                            {{--<?php if($row->jo_status == 1):?>--}}
                                            {{--<td>--}}
                                            {{--<a href='<?php echo  $edit_url.'/'.$row->job_order_id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs">Edit</a>--}}
                                            {{--</td>--}}
                                            {{--<td>--}}
                                            {{--<button onclick="jobOrderDelete('<?= $row->job_order_id ?>','smfal/jobOrderDelete')" class="btn btn-danger btn-xs">Delete</button>--}}
                                            {{--</td>--}}
                                            {{--<?php else:?>--}}
                                            {{--<td class="text-center">--}}
                                            {{--<i class="fa fa-ban" aria-hidden="true" style="color: red"></i>--}}
                                            {{--</td>--}}
                                            {{--<td class="text-center">--}}
                                            {{--<i class="fa fa-ban" aria-hidden="true" style="color: red"></i>--}}
                                            {{--</td>--}}
                                            {{--<?php endif;?>--}}
                                            {{--</tr>--}}

                                            {{--@endforeach--}}
                                            <?php */?>
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
            $('#ClientJobId').select2();

        });
        function jobOrderDelete(id,url)
        {
            if(confirm("Want TO Delete...? Press ok")) {
                var pageType = '<?php echo $_GET['pageType'] ?>';
                var parentCode = '<?php echo $_GET['parentCode'] ?>';
                var m = '<?php echo $_GET['m'] ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/' + url + '',
                    type: "GET",
                    data: {id: id, m: m, pageType: pageType, parentCode: parentCode},
                    success: function (data) {
                        alert("Successfully Deleted");
                        $("#"+id).remove();
                        //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                    }
                });
            } else {
                alert('Delete Cancel');
            }
        }

        function job_Order_Jvc_Submitted(id,url)
        {
            if(confirm("Want TO Submit JVC To Manager...? Press ok")) {
                var pageType = '<?php echo $_GET['pageType'] ?>';
                var parentCode = '<?php echo $_GET['parentCode'] ?>';
                var m = '<?php echo $_GET['m'] ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/' + url + '',
                    type: "GET",
                    data: {id: id, m: m, pageType: pageType, parentCode: parentCode},
                    success: function (data) {
                        alert("Successfully Deleted");
                        $("#m"+id).html('<span class="badge badge-success" style="background-color: #5bc0de !important">JVC SUBMITTED</span>');
                        //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                    }
                });
            } else {
                alert('JVC Cancel');
            }
        }

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


        function UpdateDpdn(id)
        {

            var val = $('#ClientJobId'+id).val();
            $.ajax({
                url: '/pdc/UpdateDpdn',
                type: 'Get',
                data: {id: id,val:val},

                success: function (response) {
                    alert("Success Client Id updated "+response);
                }
            });
        }

        function filterByClientAndRegion()
        {

//            var from= $('#from').val();
//            var to= $('#to').val();
            var ClientId = $('#ClientId').val();
            var RegionId = $('#RegionId').val();
            var ClientJobId = $('#ClientJobId').val();

            var m = '<?php echo $m?>';
//            if(ClientId !="" || RegionId !="")
//            {
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/filterByClientAndRegionJobOrderTwo',
                type: 'Get',
                data: {ClientId: ClientId,RegionId:RegionId,ClientJobId:ClientJobId,m:m},

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

        function CreateInvoice(JoId)
        {
            alert('Job Order wali Id == '+JoId);
        }

        function UpdateBranchId(JobOrderId)
        {
            //alert(); return false;

            var BranchId = $('#BranchId'+JobOrderId).val();
            if(BranchId !="")
            {
                $.ajax({
                    url: '/pdc/UpdateBranchId',
                    type: 'Get',
                    data: {BranchId: BranchId,JobOrderId:JobOrderId},

                    success: function (response)
                    {
                        $('#ShowHide'+response).html('');
                        alert("Success Branch Id updated ");
                    }
                });
            }
            else
            {
                $('#Error'+JobOrderId).html('<p class="text-danger">Please Select Branch.</p>');
            }


        }

        function branchDetail(url,m){
            $.ajax({
                url: '<?php echo url('/')?>/'+url+'',
                type: "GET",
                data: {m:m},
                success:function(data) {
                    //alert(data);
                    jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                    //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                    //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                    jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    jQuery('#showDetailModelOneParamerter .modal-body').html(data);
                }
            });
        }

        function AddBranchRequest()
        {

            var client_id = $("#client_id").val();
            var branch_name = $("#branch_name").val();
            var ntn = $("#ntn").val();
            var strn = $("#strn").val();
            //var address = $("textarea#address").html();
            var branch_address = $("#branch_address").val();

            var m = '<?= $_GET['m']?>';

            url='sad/insertBranchAjax';
            if(client_id == ""){$('#ErrorOne').html('<p class="text-danger">Field Required.</p>'); return false;}
            else{$('#ErrorOne').html('');}
            if(branch_name == ""){$('#ErrorTwo').html('<p class="text-danger">Field Required.</p>'); return false;}
            else{$('#ErrorTwo').html('');}
            if(ntn == ""){$('#ErrorThree').html('<p class="text-danger">Field Required.</p>'); return false;}
            else{$('#ErrorThree').html('');}
            if(strn == ""){$('#ErrorFour').html('<p class="text-danger">Field Required.</p>'); return false;}
            else{$('#ErrorFour').html('');}
            if(branch_address == ""){$('#ErrorFive').html('<p class="text-danger">Field Required.</p>'); return false;}
            else{$('#ErrorFive').html(''); }

            $('#BtnBranchSubmit').prop('disbaled',true);
            $.ajax({
                url: '<?php echo url('/')?>/' + url + '',
                type: "GET",
                data: {client_id:client_id,branch_name:branch_name,ntn:ntn,strn:strn,address:branch_address},
                success: function (data) {
                    setTimeout(function () {
                        $('#showDetailModelOneParamerter').modal("hide");
                    }, 500);
                }
            });
        }
    </script>

@endsection