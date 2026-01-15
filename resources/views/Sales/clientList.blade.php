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
                                <span class="subHeadingLabelClass">Client List</span>
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
                                            <th class="text-center">Client Id</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">NTN</th>
                                            <th class="text-center">STRN</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">Account Head</th>
                                            <th class="text-center">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/editClientForm?m=".$m;
                                            ?>

                                            @foreach($client as $row)
                                                <tr title="{{$row->id}}"  id="{{ $row->id }}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{ $row->id }}</td>
                                                    <td class="text-center">{{ $row->client_name }}</td>
                                                    <td class="text-center">{{ $row->ntn }}</td>
                                                    <td class="text-center">{{ $row->strn }}</td>
                                                    <td class="text-center">{{ $row->address }}</td>
                                                    <td class="text-center">

                                                    </td>
                                                    <td class="text-center" style="width: 290px !important;">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','Edit Condition Form')" type="button" class="btn btn-success btn-xs">EDIT</button>
                                                        <button class="btn btn-xs btn-danger" onclick="delete_record('<?php echo $row->id; ?>')"> DELETE </button>
                                                        <?php
                                                        if($row->acc_id == 0):
                                                        ?>
                                                        <span id="Created<?php echo $row->id?>">
                                                            <button  class="btn btn-xs btn-primary" onclick="addData('<?php echo $row->id; ?>')"> Create Account <span id="Loader<?php echo $row->id?>"></span></button>
                                                        </span>
                                                        <?php else:?>
                                                        <span class="badge badge-success" style="background-color: #00c851 !important">Account Created</span>
                                                        <?php endif;?>


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
            $('.select2').select2();
        });
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

        function addData(id)
        {
            var AccId = $('#AccId'+id).val();

            if (confirm('Are you sure you want to Create this Vendor Account')) {
                if(AccId !="") {
                    $('#AccError'+id).html('');
                    $('#Loader' + id).html('<img src="/assets/img/loading.gif" alt="">');
                    $.ajax({
                        url: '/sdc/addData',
                        type: 'Get',
                        data: {id: id},
                        success: function (response) {
                            $('#Created' + response).html('<span class="badge badge-success" style="background-color: #00c851 !important">Account Created</span>');
                        }
                    });
                }else{
                    $('#AccError'+id).html('<p class="text-danger">Please Select Account.</p>');
                }
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