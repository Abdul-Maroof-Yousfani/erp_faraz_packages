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

    <?php

    $data=DB::Connection('mysql2')->select('select count(j.id)countt,c.client_name from invoice as j
INNER JOIN
client c
ON
c.id=j.bill_to_client_id
where j.status=1 GROUP by j.bill_to_client_id order by countt desc');

    ?>
    <div class="lineHeight">&nbsp;</div>
    <div class="row container-fluid">

        <?php  foreach($data as $row): ?>
        {{--<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="border: solid 1px #ccc">--}}
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="border: solid 1px #ccc">
            <p>{{$row->client_name}} <span class="badge badge-primary">&nbsp;{{' '.$row->countt}}</span></p>
        </div>
        <?php endforeach;?>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Invoice List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo date('Y-m-d');?>" class="form-control" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Client</label>
                            <select name="ClientId" id="ClientId" class="form-control select2">
                                <option value="">Select Client</option>
                                <?php foreach($Client as $ClientFil):?>
                                <option value="<?php echo $ClientFil->id;?>"><?php echo $ClientFil->client_name;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
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
                                            <th class="text-center">Invoice No.</th>
                                            <th class="text-center">Invoice Date</th>
                                            <th class="text-center">Job Order No.</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Client Job</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">UserName</th>

                                            <th class="text-center">View</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/viewInvoiceDetail?m=".$m;
                                            $edit_url= url('/sales/editInvoice/');
                                            ?>

                                            @foreach($invoice as $row)
                                                <tr id="{{ $row->id }}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{ strtoupper($row->inv_no) }}</td>
                                                    <td class="text-center">{{ CommonHelper::changeDateFormat($row->inv_date) }}</td>

                                                    <td class="text-center">{{ strtoupper($row->job_order_no) }}</td>
                                                    <td class="text-center">{{ CommonHelper::get_client_name_by_id($row->bill_to_client_id)}}</td>

                                                    <td class="text-center">{{ $row->ship_to }}</td>
                                                    <td></td>
                                                    <td class="text-center">{{ ucwords($row->username) }}</td>

                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Invoice Detail')" type="button" class="btn btn-success btn-xs">View</button>
                                                    </td>

                                                    <?php
                                                     $row->inv_status;
                                                    if($row->type == 0):?>
                                                    <td class="text-center hide{{$row->id}}">
                                                        <a href='<?php echo  $edit_url.'/'.$row->id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs  hide{{$row->id}}">Edit</a>
                                                    </td>
                                                    <?php else:?>
                                                    <td class="text-center">
                                                        <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
                                                    </td>
                                                    <?php endif;?>

                                                    <?php if($row->inv_status == 1):?>
                                                    <td class="text-center">
                                                        <button onclick="invoiceDelete('<?= $row->id ?>','smfal/invoiceDelete')" class="btn btn-danger btn-xs  hide{{$row->id}}">Delete</button>
                                                    </td>
                                                    <?php else:?>
                                                    <td class="text-center">
                                                        <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
                                                    </td>
                                                    <?php endif;?>
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
            $('.select2').select2();
        });

        function invoiceDelete(id,url)
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

        function delete_record(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deleteClientList',
                    type: 'Get',
                    data: {id: id},
                    success: function (response)
                    {
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
            var ClientId= $('#ClientId').val();

            var m='{{$m}}';

            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/invoice_list',
                type: 'Get',
                data: {from: from,to:to,m:m,ClientId:ClientId},

                success: function (response)
                {

                    $('#data').html(response);

                }
            });


        }
    </script>

@endsection