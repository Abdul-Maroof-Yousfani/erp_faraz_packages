<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$data = DB::Connection('mysql2')->select('select sum(b.net_amount)grn_amount,a.grn_no,a.grn_date,a.id as grn_id,sum(c.net_amount)pi_amount
                                                      from goods_receipt_note a
                                                      inner  join
                                                       grn_data b
                                                       ON
                                                       b.master_id = a.id
                                                       left join
                                                       new_purchase_voucher_data c
                                                       on
                                                       c.grn_data_id=b.id
                                                       and c.staus=1
                                                      where a.status = 1
                                                      and a.grn_status in (2,3)
                                                      group by a.id');
?>

@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                        <?php echo CommonHelper::displayExportButton('goodsReceiptNoteList','','1')?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View GRN  Reconciliation</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printGoodsReceiptNoteList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Grn No</th>
                                                                <th class="text-center">Grn Date</th>
                                                                <th class="text-center">GRN Amount</th>
                                                                <th class="text-center">Return Amount Through Grn</th>
                                                                <th class="text-center">PI Amount</th>
                                                                <th class="text-center">Return Amount Through PI</th>

                                                                {{--<th class="text-center">BAL. QTY. Receivable</th>--}}
                                                                {{--<th class="text-center">Location</th>--}}
                                                                </thead>
                                                                <tbody id="GetDataAjax">
                                                                <?php $count=1;
                                                                $total_grn_amount=0;
                                                                $total_return_amount_grn=0;
                                                                $total_pi_amount=0;
                                                                $total_amount_pi=0;
                                                                $total_return_amount_pi=0;
                                                                $total_cr_amount_dn=0;





                                                                ?>
                                                                @foreach($data as $row)


                                                                    <?php
                                                                    $stock_amount_grn= DB::Connection('mysql2')->table('stock')->where('status',1)->where('voucher_no',$row->grn_no)->sum('amount');
                                                                    $return_amount_grn= ReuseableCode::return_amount($row->grn_id,1);
                                                                    $return_amount_pi= ReuseableCode::return_amount($row->grn_id,2);

                                                                    $grn_amount_actual=$row->grn_amount-$return_amount_grn;
                                                                    ?>

                                                                    @if($row->pi_amount==0 && $grn_amount_actual>0)

                                                                        <?php

                                                                        $grn_number[]=$row->grn_no;
                                                                        $grn_amount[]=$row->grn_amount;
                                                                        $grn_date[]=$row->grn_date;
                                                                        ?>
                                                                    @endif

                                                                    <?php


                                                                    $total_grn_amount+=$row->grn_amount;
                                                                    $total_return_amount_grn+=$return_amount_grn;
                                                                    $total_return_amount_pi+=$return_amount_pi;
                                                                    $total_pi_amount+=$row->pi_amount;

                                                                    ?>
                                                                    <tr @if($stock_amount_grn!=$row->grn_amount) style="background-color: red" @endif>
                                                                        <td class="text-center">{{$count++}}</td>
                                                                        <td class="text-center">{{strtoupper($row->grn_no)}}</td>
                                                                        <td class="text-center">{{date_format(date_create($row->grn_date), 'd-m-Y')}}</td>
                                                                        <td class="text-right">{{number_format($row->grn_amount,2)}}</td>
                                                                        <td class="text-right">{{number_format($return_amount_grn,2)}}</td>
                                                                        <td @if($row->pi_amount==0) style="color: red;" @endif class="text-right">{{number_format($row->pi_amount,2)}}</td>
                                                                        <td class="text-right">{{number_format($return_amount_pi,2)}}</td>
                                                                    </tr>
                                                                @endforeach


                                                                <tr style="font-size: larger;font-weight: bolder">
                                                                    <td colspan="3">Total</td>
                                                                    <td class="text-right">{{number_format($total_grn_amount,2)}}</td>
                                                                    <td class="text-right">{{number_format($total_return_amount_grn,2)}}</td>
                                                                    <td class="text-right">{{number_format($total_pi_amount,2)}}</td>
                                                                    <td class="text-right">{{number_format($total_return_amount_pi,2)}}</td>
                                                                </tr>

                                                                <?php  ?>
                                                                </tbody>
                                                                <tbody style="width: 20%">
                                                                <thead>
                                                                <th>No PI Found of This GRNS</th>
                                                                </thead>
                                                                <?php $total=0; ?>
                                                                @foreach($grn_number as $key=> $row1)
                                                                    <tr>
                                                                        <td>{{strtoupper($row1)}}</td>
                                                                        <td class="text-right">{{number_format($grn_amount[$key],2)}}</td>
                                                                        <td class="text-right">{{$grn_date[$key]}}</td>
                                                                        <?php $total+=$grn_amount[$key] ?>
                                                                    </tr>

                                                                @endforeach

                                                                <tr style="background-color: darkgrey">
                                                                    <td class="">Total</td>
                                                                    <td class="text-right">{{number_format($total,2)}}</td>
                                                                </tr>
                                                                </tbody>



                                                            </table>

                                                            <div style="font-weight: bold">
                                                                <p>Total Grn Amount : {{number_format($total_grn_amount,2)}}</p>
                                                                <p>Total Grn Return Amount : {{number_format($total_return_amount_grn,2)}}</p>
                                                                <p>Total Amount Of GRNS Which Invoice Not Created : {{number_format($total,2)}}</p>
                                                                <p style="background-color:lightcyan ">Purchase Invoice Should Be Created OF this  : {{number_format($total_grn_amount-$total_return_amount_grn-$total,2)}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                            {{--for sales return--}}


                                        @include('Purchase.sales_return_recon')



                                 






                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function get_data_ajax()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var m = '<?php echo $_GET['m']?>';

            $('#GetDataAjax').html('<tr><td colspan="10"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
            $('#PoStatusError').html('');
            $.ajax({
                url: '<?php echo url('/')?>/sdc/getSoDetailDateWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,m:m},
                success: function (response)
                {
                    $('#GetDataAjax').html(response);
                }
            });
        }



    </script>

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection