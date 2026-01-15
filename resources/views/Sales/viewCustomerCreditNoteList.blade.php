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
use App\Helpers\ReuseableCode;
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$view=ReuseableCode::check_rights(124);
$edit=ReuseableCode::check_rights(125);
$export=ReuseableCode::check_rights(258);


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
                                <span class="subHeadingLabelClass">Sales Return List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
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
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">SO No</th>
                                            <th class="text-center col-sm-1">Type</th>
                                            <th class="text-center col-sm-1">CR No</th>
                                            <th class="text-center col-sm-1">CR Date</th>
                                            <th class="text-center col-sm-2">Buyer</th>
                                            <th class="text-center col-sm-2">Amount</th>
                                            <th class="text-center col-sm-1">View</th>

                                            {{--<th class="text-center">Edit</th>--}}
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                                    $OverAllTotal = 0;

                                            ?>

                                            @foreach($credit_note as $row)
                                                <?php $customer=CommonHelper::byers_name($row->buyers_id);
                                                $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->so_id);
                                                        $SoNo = "";
                                                        if($row->so_id > 0):
                                                        $SoData = CommonHelper::get_single_row('sales_order','id',$row->so_id);
                                                        $SoNo = $SoData->so_no;
                                                        else:
                                                        $SoNo = "";
                                                        endif;
                                                        $NetAmount = DB::Connection('mysql2')->table('credit_note_data')->where('master_id',$row->id)->select('net_amount')->sum('net_amount');
                                                         if ($row->type==3):

                                                    $pos_no = DB::Connection('mysql2')->table('credit_note_data')->where('master_id',$row->id)->select('voucher_no')->value('voucher_no');
                                                        endif;
                                                $OverAllTotal+=$NetAmount;
                                                ?>
                                                <tr @if($row->type==3) style="background-color: lavenderblush" @endif title="" id="{{$row->id}}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center"><?php if($row->type==3): echo strtoupper($pos_no); else: echo strtoupper($SoNo); endif?></td>
                                                    <td class="text-center">@if($row->type==1) DN @elseif($row->type==2) SI @else POS @endif</td>
                                                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->cr_no)}}</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->cr_date);?></td>
                                                    <td class="text-center"><?php   $customer=CommonHelper::byers_name($row->buyer_id);
                                                      echo   $customer->name;
                                                        ?></td>
                                                    <td class="text-center"><?php echo number_format($NetAmount,2);?></td>



                                                    <td class="text-center">
                                                            <?php if($view == true):?>
                                                                <button onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                                                                        type="button" class="btn btn-success btn-xs">View</button>
                                                                <a href="{{ route('sales.salereturn.edit', ['id'=> $row->id]) }}" type="button" class="btn btn-warning btn-xs">Edit</a>
                                                            <?php endif;?>
                                                            <?php if($edit == true):?>
                                                           
                                                                <button onclick="delete_sales_return('{{$row->id}}','{{$row->cr_no}}')" type="button" class="btn btn-danger btn-xs">Delete</button>
                                                            <?php endif;?>

                                                    </td>


                                                </tr>


                                            @endforeach
                                            <tr>
                                                <td colspan="6"><strong>TOTAL</strong></td>
                                                <td class="text-center"><?php echo number_format($OverAllTotal,2)?></td>
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('EmpExitInterviewList');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Sales Return <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        function delete_sales_return(id,cr_no)
        {


        if (confirm('Are you sure you want to delete this request')) {
            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/sdc/delete_sales_return',
                type: 'GET',
                data: {id: id,cr_no:cr_no},
                success: function (response) {

                    if (response=='0')
                    {
                        alert('Can not Deleted')
                    }

                    else {
                        alert('Deleted');

                        $('#' + id).remove();
                    }



                }
            });
        }
        else{}
        }

        function viewRangeWiseDataFilter()
        {
            //var BuyerId = $('#BuyerId').val();
            var from= $('#from').val();
            var to= $('#to').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/getCustomerCreditNoteData',
                type: 'Get',
                data: {from: from,to:to,m:m},

                success: function (response)
                {

                    $('#data').html(response);


                }
            });

        }

    </script>
@endsection