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
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Item Cost Classification Update To Subitem</span>
                            </div>
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
                                            <th class="text-center" style="width: 100px">S.No</th>
                                            <th class="text-center">Subitem Name</th>
                                            <th class="text-center">Cost Classification</th>
                                            <th class="text-center">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/editTypeList?m=".$m;
                                            ?>

                                            @foreach($Subitem as $row)
                                                <tr id="{{ $row->id }}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center"><small>{{ $row->sub_ic }}</small></td>
                                                    <td class="text-center">
                                                        <select id="item_cost_classification_id{{$row->id}}" name="item_cost_classification_id{{$row->id}}" class="form-control">
                                                            <option value="">---Select---</option>
                                                            @foreach($item_cost_classification as $val)
                                                                <option value="{{$val->id}}" @if($val->id == $row->item_cost_classification_id){{ 'selected' }} @endif> {{ $val->name }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center" id="updatemsg{{$row->id}}"> <button onclick="UpdateTableDataSubitem('{{$row->id}}')"> Update </button> </td>
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
        function UpdateTableDataSubitem(id)
        {

            item_cost_classification_id = $('#item_cost_classification_id'+id).val();
            //alert(item_cost_classification_id);
            //alert(id);
            if(item_cost_classification_id !=''){
                $('#updatemsg'+id).text('updated');
            //return false;
                if (confirm('Are you sure you want to update this request..?')) {
                    $.ajax({
                        url: '/stad/UpdateTableDataSubitem',
                        type: 'Get',
                        data: {id: id, item_cost_classification_id:item_cost_classification_id },
                        success: function (response) {
                            alert(response);
                        }
                    });
                }
            } else{
                alert('Please Select Cost Classification Item');
            }
        }


        // function delete_record(id)
        // {
        //     if (confirm('Are you sure you want to delete this request')) {
        //         $.ajax({
        //             url: '/pdc/deleteTypeList',
        //             type: 'Get',
        //             data: {id: id},
        //             success: function (response) {
        //                 alert('Deleted');
        //                 $('#' + id).remove();
        //             }
        //         });
        //     }
        //     else{}
        // }


        // function viewRangeWiseDataFilter()
        // {
        //
        //     var from= $('#from').val();
        //     var to= $('#to').val();
        //     $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
        //
        //     $.ajax({
        //         url: '/pdc/purchase_voucher_list_ajax',
        //         type: 'Get',
        //         data: {from: from,to:to},
        //
        //         success: function (response) {
        //
        //             $('#data').html(response);
        //
        //
        //         }
        //     });
        //
        //
        // }
    </script>

@endsection