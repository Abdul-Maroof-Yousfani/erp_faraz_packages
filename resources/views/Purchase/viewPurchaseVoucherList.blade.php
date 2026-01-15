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
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list " id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">Pv No</th>
                                            <th class="text-center col-sm-1">Pv Date</th>
                                            <th class="text-center col-sm-1">Ref  No</th>
                                            <th class="text-center col-sm-1">Bill Date</th>
                                            <th class="text-center">Supplier</th>

                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;?>

                                            @foreach($purchase_voucher as $row)
                                                    <tr title="{{$row->id}}" id="{{$row->id}}">
                                                        <td class="text-center">{{$counter++}}</td>
                                                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->pv_no)}}</td>
                                                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->pv_date);?></td>
                                                        <td class="text-center">{{$row['slip_no']}}</td>
                                                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                                                        <td class="text-center">{{CommonHelper::get_supplier_name($row->supplier)}}</td>
                                                        <td class="text-right">{{number_format($row['total_net_amount'],2)}}</td>

                                                        <?php $total+=$row['total_net_amount']; ?>
                                                        <td class="text-center"><button
                                                          onclick="showDetailModelOneParamerter('pdc/viewPurchaseVoucherDetail/<?php echo $row->id ?>','','View Purchase Voucher')"
                                                                    type="button" class="btn btn-success btn-xs">View</button></td>
                                                        <td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>
                                                        <td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>
                                                    </tr>


                                                @endforeach


                                            <tr>
                                                <td class="text-center" colspan="6" style="font-size: 20px;">Total</td>
                                                <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
                                                <td class="text-center" colspan="1" style="font-size: 20px;"></td>
                                                <td class="text-center" colspan="1" style="font-size: 20px;"></td>
                                            </tr>
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