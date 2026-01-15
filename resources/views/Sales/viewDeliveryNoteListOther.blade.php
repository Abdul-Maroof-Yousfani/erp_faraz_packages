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
?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Delivery Note List</span>
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
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>(SO NO) <input type="radio" class="form-control" name="FilterType" value="1" onclick="RadioChange()"></label>
                            <label for=""> OR </label>
                            <label>(DN NO) <input type="radio" class="form-control" name="FilterType" value="2" onclick="RadioChange()"></label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="">Search By <span id="ChangeType"></span></label>
                            <input type="text" class="form-control" id="SearchText" name="SearchText" disabled>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <button type="button" class="btn btn-xs btn-primary" style="margin-top: 32px;" id="BtnReset" onclick="ResetFields()">Reset</button>
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
                                            <th class="text-center">SO No</th>
                                            <th class="text-center">DN No</th>

                                            <th class="text-center">So Data Id</th>
                                            <th class="text-center">DN So Data Id</th>
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;?>

                                            @foreach($delivery_note as $row)

                                                <?php $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id); ?>
                                                <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
                                                <tr title="{{$row->id}}" id="{{$row->id}}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center"><?php echo  strtoupper($row->so_no) ?></td>
                                                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>

                                                    <td>
                                                        <?php
                                                        $MultiSoDataIds = "";
                                                        $SoDataIds = DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$row->master_id)->select('id')->get();
                                                         $NumCount = 1;
                                                        foreach($SoDataIds as $SFil):
                                                            $NumCount++;

                                                        $MultiSoDataIds .= $SFil->id.', <br>';

                                                        endforeach;
                                                            $MultiIdsArray = explode(',',$MultiSoDataIds);
                                                            //if(count($MultiIdsArray) > )
                                                        echo $MultiSoDataIds;
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        $MultiDnSoDataIds = "";
                                                        $DnSoDataIds = DB::Connection('mysql2')->table('delivery_note_data')->where('so_id',$row->master_id)->where('status',1)->select('so_data_id')->get();

                                                        foreach($DnSoDataIds as $DFil):

                                                        $MultiDnSoDataIds .= $DFil->so_data_id.', <br>';

                                                        endforeach;
                                                        //if(count($MultiIdsArray) > )
                                                        echo $MultiDnSoDataIds;
                                                        ?>
                                                    </td>
                                                    <td style="background-color: <?php if($MultiSoDataIds == $MultiDnSoDataIds): echo 'green'; else: echo 'red'; endif;?>">

                                                    </td>

                                                    {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
                                                    {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
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
    </div>

    <script>
        function delivery_note_delete(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sad/delivery_not_delete',
                    type: 'GET',
                    data: {id: id, m:m},
                    success: function (response) {
                        //alert(response); return false;
                        if (response=='0')
                        {
                            alert('Can not Deleted')
                        }

                        else {
                            alert('Deleted');
                            // alert(response);
                            $('#' + id).remove();
                        }



                    }
                });
            }
            else{}
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


        function RadioChange()
        {
            var radioValue = $("input[name='FilterType']:checked").val();

            if(radioValue == 1)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('SO NO');
                $('#SearchText').prop('placeholder','Enter SO NO');
            }
            else if(radioValue == 2)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('DN NO');
                $('#SearchText').prop('placeholder','Enter DN NO');
            }
            else
            {
                $('#ChangeType').html('');
                $('#SearchText').prop('placeholder','');
                $('#SearchText').prop('disabled',true);
            }
        }

        function ResetFields()
        {
            $('input[name="FilterType"]').attr('checked', false);
            $('#ChangeType').html('');
            $('#SearchText').prop('placeholder','');
            $('#SearchText').val('');
            $('#SearchText').prop('disabled',true);
        }

        function viewRangeWiseDataFilter()
        {
            var radioValue = $("input[name='FilterType']:checked").val();
            var SearchText = $('#SearchText').val();
            var from= $('#from').val();
            var to= $('#to').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/getDeliveryNoteFilterWise',
                type: 'Get',
                data: {from: from,to:to,m:m,radioValue:radioValue,SearchText:SearchText},

                success: function (response) {

                    $('#data').html(response);


                }
            });

        }

        function delivery_note(id,m)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            window.location.href = base_url+'/sales/EditDeliveryNote?id='+id+'&&'+'m='+m;
        }

    </script>

@endsection