<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$new_purchase_voucher_payment = DB::connection('mysql2')->table('new_purchase_voucher_payment')->where('new_pv_no','=',$pv_no)->where('status',1)->get();

//$pv_no_new = $new_purchase_voucher_payment->pv_no;
//$new_purchase_voucher = DB::connection('mysql2')->table('new_purchase_voucher')->where('pv_no','=',$pv_no_new)->first();
//$new_purchase_voucher_data = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('pv_no','=',$pv_no_new)->get();
//print_r($new_purchase_voucher_data);
?>
<div class="container-fluid text-center">

    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
        {{--<div style="width:30%; float:left;">--}}
            {{--<table  class="table table-bordered table-striped table-condensed tableMargin">--}}
                {{--<tbody>--}}
                {{--<tr>--}}
                    {{--<td style="width:40%;">PV No.</td>--}}
                    {{--<td style="width:60%;">< ?php echo $new_purchase_voucher->pv_no;?></td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td>JV Date</td>--}}
                    {{--<td>< ?php echo FinanceHelper::changeDateFormat($new_purchase_voucher->pv_date);?></td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td style="width:40%;">Ref / Bill No.</td>--}}
                    {{--<td style="width:60%;">< ?php echo $new_purchase_voucher->slip_no;?></td>--}}
                {{--</tr>--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}

        {{--<div style="width:30%; float:right;">--}}
            {{--<table  class="table table-bordered table-striped table-condensed tableMargin">--}}
                {{--<tbody>--}}
                {{--<tr>--}}
                    {{--<td style="width:40%;">Bill Date</td>--}}
                    {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($new_purchase_voucher->bill_date);?></td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td style="width:40%;">Due Date</td>--}}
                    {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($new_purchase_voucher->due_date);?></td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td style="width:40%;">Vendor</td>--}}
                    {{--<td style="width:60%;">--}}
                        {{--< ?php  $Supplier = CommonHelper::get_single_row('supplier','id',$new_purchase_voucher->supplier);--}}
                        {{--echo $Supplier->name;--}}
                        {{--?>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="dataExport"  class="table table-bordered table-striped table-condensed tableMargin">
                <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">PI No</th>
                    <th class="text-center">GRN No</th>
                    <th class="text-center">Supplier Name</th>
                    <th class="text-center" >PI Amount</th>
                    <th class="text-center" >Return Amount</th>
                    <th class="text-center" >Previous Paid</th>
                    <th class="text-center" >Paid Against This Voucher</th>
                    <th class="text-center" >Username</th>
                </tr>
                </thead>
                <tbody>
                <?php $count=1;
                        $AmountTotal = 0;
                ?>
                @foreach($new_purchase_voucher_payment as $val)

                    <?php
                    $grn_id=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('id',$val->new_purchase_voucher_id)->value('grn_id');

                    $paid_previous= DB::Connection('mysql2')->table('new_purchase_voucher_payment')->where('status',1)
                     ->where('new_pv_no','!=',$pv_no)
                     ->where('new_purchase_voucher_id',$val->new_purchase_voucher_id)
                     ->sum('amount');


                    $against_this=DB::Connection('mysql2')->table('new_purchase_voucher_payment')->where('status',1)
                      ->where('new_pv_no','=',$pv_no)
                      ->where('new_purchase_voucher_id',$val->new_purchase_voucher_id)
                     ->sum('amount');
                            $GrnNo = DB::Connection('mysql2')->table('new_purchase_voucher')->where('pv_no',$val->pv_no)->select('grn_no')->first();
                            $GrnNo = $GrnNo->grn_no ?? '';
                    ?>  
                    <tr>
                        <td class="text-center">{{$count++}}</td>
                        <td class="text-center">{{strtoupper($val->pv_no)}}</td>
                        <td class="text-center">
                            <?php $accType = Auth::user()->acc_type;
                            if($accType == 'client'):
                            ?>
                            <p style="cursor: pointer"  onclick="redirect('{{$GrnNo}}')" >
                                <?php echo strtoupper($GrnNo);?>
                            </p>
                            <?php else:?>
                            <?php echo $GrnNo;?>
                            <?php endif;?>

                        </td>

                        <td class="text-center"><?php echo $Acc = CommonHelper::get_supplier_name($val->supplier_id); ?></td>
                        <td class="text-center">
                            <?php
                                if(ReuseableCode::get_purchased_amount($val->new_purchase_voucher_id) > 0)
                                {
                                    echo number_format($val->amount,2);

                                }
                                else
                                {
                                  echo 0.00;
                                }
                            ?>
                                                <!-- {{number_format(ReuseableCode::get_purchased_amount($val->new_purchase_voucher_id),2)}}   -->
                                                <?php // $AmountTotal += $val->amount; ?>
                                                <?php // echo number_format($val->amount,2); ?>
                        </td>
                        <td class="text-center">{{number_format(ReuseableCode::return_amount($grn_id,2))}}</td>
                        <td class="text-center">{{number_format($paid_previous,2)}} <?php  ?></td>
                        <td class="text-center">{{number_format($against_this,2)}} <?php  ?></td>
                        <td class="text-center">{{$val->username}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="6"><strong style="font-size: 18px;">TOTAL</strong></td>
                        <td><strong style="font-size: 18px;"><?php echo number_format($val->amount,2);?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    function redirect(grn_no)
    {

        
        window.open('<?php echo url('/') ?>/pdc/viewGoodsReceiptNoteDetailNewTab?GrnNo='+grn_no+'&&m=<?php echo Session::get('run_company')?>', '_blank');
    }
</script>
