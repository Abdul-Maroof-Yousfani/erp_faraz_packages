<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$approve=ReuseableCode::check_rights(20);
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();

$MasterData = DB::Connection('mysql2')->table('product_creation')->where('id','=',$id)->get();

     $pi=    Request::get('Pi');
?>
<style>
    .textBold{
        font-weight: bolder;
        font-size: 18px;
    }
</style>
<?php
foreach ($MasterData as $row)
{

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
@if ($pi!=0)
    <?php echo Form::open(array('url' => 'stad/add_production','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
@endif
<div class="row" id="printDemandVoucherVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php echo CommonHelper::getCompanyName($m);?>
                            <h3 style="text-align: center;">Work Order</h3>
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php //PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status);?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Word Order No.</td>
                            <td class="text-center"><?php echo strtoupper($row->voucher_no);?></td>
                        </tr>
                        <tr>
                            <td>Work Order Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->voucher_date);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Supplier.</td>
                            <td class="text-center">
                                <?php $Supp = CommonHelper::get_single_row('supplier','id',$row->supplier_id);

                                echo $Supp->name;
                                ?>
                                    <input type="hidden" name="supplier" value="{{$row->supplier_id}}"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="">Detailed Format
                        <input type="checkbox" id="CheckedUnChecked" onclick="ShowHideInnerDetail()">
                    </label>
                </div>
            </div>
            <div class="">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Uom</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Make Type</th>
                            <th class="text-center">Make Cost Per PCS</th>
                            <th class="text-center">Net Maake Cost</th>
                            @if($pi>0)
                                <th class="text-center">Final Cost</th>
                                <th class="text-center">Warehouse </th>
                                <th class="text-center">Bacth Code</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        CommonHelper::companyDatabaseConnection($m);
                        $DetailData = DB::Connection('mysql2')->table('product_creation_data')->where('master_id','=',$id)->get();
                        CommonHelper::reconnectMasterDatabase();
                        $counter = 1;
                        $totalCountRows = count($DetailData);
                        $total=0;
                        $total_rm_cost=0;
                        foreach ($DetailData as $row1){
                        $InnerDetail = DB::Connection('mysql2')->table('issuence_for_production')->where('status',1)->where('main_id',$id)->where('master_id',$row1->id);

                        ?>


                        <tr class="text-center">
                            <td class="text-center">
                                <?php echo $counter;?>

                            </td>
                            <td  id="{{$row1->id}}" title="{{$row1->product_id}}" class="textChanger">
                                <?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->product_id);?>

                            </td>
                            <td><?php echo CommonHelper::get_uom($row1->product_id);?></td>
                            <td class="text-center"><?php echo number_format($row1->qty,2);?></td>
                            <td class="text-center"><?php echo  CommonHelper::get_make_type($row1->maketype);?></td>
                            <td class="text-center"><?php echo number_format($row1->amount,2);?></td>
                            <td class="text-center"><?php echo number_format($row1->net_amount,2);?></td>
                            @if ($pi>0)

                                <?php
                                 $issue_amount=   DB::Connection('mysql2')->selectOne('select sum(a.amount)amount
                                from stock a
                                where a.pos_status=2
                                and a.status=1
                                and a.master_id="'.$row1->id.'"')->amount;


                                $issue_amount_return=   DB::Connection('mysql2')->selectOne('select sum(a.amount)amount
                                from stock a
                                where a.status=1
                                and a.pos_status=3
                                and a.master_id="'.$row1->id.'"')->amount;

                                 $amount=$issue_amount-$issue_amount_return;



                                ?>
                            <td title="{{$row1->id}}">
                                <?php  echo number_format(($amount+$row1->net_amount)/$row1->qty,2); ?>
                                <?php $finanl_cost=($amount+$row1->net_amount)/$row1->qty; ?>

                                    <?php $validation=true; ?>
                                    @if ($row1->pi_no=='')
                                <td>


                                    <select onchange="" name="warehouse_id[]" id="warehouse_id" class="form-control requiredField" style="width: 180px;">
                                        <option value="">Select Warehouse</option>
                                        <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select></td>

                                <td><input type="text" class="form-control" name="bacth_code[]"/> ></td>
                                    <input type="hidden" name="main_id" value="{{$row1->master_id}}"/>
                                    <input type="hidden" name="master_id[]" value="{{$row1->id}}"/>
                                    <input type="hidden" name="voucher_no" value="{{$row1->voucher_no}}"/>
                                    <input type="hidden" name="product_id[]" value="{{$row1->product_id}}"/>
                                    <input type="hidden" name="make_qty[]" value="{{$row1->qty}}"/>
                                    <input type="hidden" name="amount[]" value="{{$row1->amount}}"/>
                                    <input type="hidden" name="final_cost[]" value="{{$finanl_cost}}"/>
                                    <input type="hidden" name="net_amount[]" value="{{$row1->net_amount}}"/>
                                    <input type="hidden" name="work_in_progress[]" value="{{$amount}}"/>
                                    <input type="hidden" name="new_pv_rate[]" value="{{$row1->amount}}"/>
                                    </td>

                                        <?php $validation=true; ?>

                                @else
                                <td>Done</td>
                                <td>Done</td>
                               <?php $validation=false; ?>
                                @endif
                            @endif

                            <?php $total+= $row1->net_amount?>
                        </tr>
                        <tr class="InnerDetail" style="display: none; background: radial-gradient(black, transparent)">
                            <td colspan="7">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Batch Code</th>
                                        <th class="text-center">Qty</th>


                                            <th class="text-center">Rate</th>
                                            <th class="text-center">Amount</th>

                                        <th class="text-center">Return Qty</th>

                                            <th class="text-center">Return Amount</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $InnerCounter = 1;
                                    $total_amount=0;
                                    $total_return_amount=0;

                                    if($InnerDetail->count() > 0):
                                    foreach($InnerDetail->get() as $InnerFil):
                                    ?>
                                        <tr class="text-center">
                                            <td><?php echo $counter.' . '.$InnerCounter++;?></td>
                                            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$InnerFil->item_id);?></td>
                                            <td><?php echo CommonHelper::get_location($InnerFil->warehouse_id);?></td>
                                            <td><?php echo $InnerFil->batch_code?></td>
                                            <td><?php echo number_format($InnerFil->qty,2);?></td>

                                                <?php $stock=DB::Connection('mysql2')->table('stock')
                                                        ->where('issuence_for_production_id',$InnerFil->id)
                                                        ->where('voucher_no',$row->voucher_no)
                                                        ->where('pos_status',2)
                                                        ->where('voucher_type',5)
                                                        ->first();
                                                ?>
                                                <td>{{number_format($stock->rate,2)}}</td>
                                                <td>{{number_format($stock->amount),2}}</td>
                                                <?php $total_amount+=$stock->amount; ?>
                                                <?php $total_rm_cost+=$stock->amount; ?>

                                            <td><?php echo number_format($InnerFil->return_qty,2);?></td>


                                                <td><?php echo number_format($InnerFil->return_qty*$stock->rate,2);?></td>
                                                <?php $total_return_amount+=$InnerFil->return_qty*$stock->rate; ?>
                                                <?php  $total_rm_cost-=$InnerFil->return_qty*$stock->rate; ?>




                                        </tr>
                                    <?php endforeach; ?>
                                            <tr>
                                                <td colspan="6">Total RM Cost</td>
                                                <td class="text-center">{{number_format($total_amount,2)}}</td>
                                                <?php //$total_rm_cost+=$total_amount; ?>
                                                <td></td>
                                                <td class="text-center">{{number_format($total_return_amount,2)}}</td>
                                                <?php  ?>

                                            </tr>
                                    <?php
                                    else:
                                    ?>
                                        <tr class="text-center">
                                            <td colspan="5" class="text-center text-danger">No Data Found...!</td>

                                        </tr>
                                    <?php endif;?>


                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                                $counter++;
                        }
                        ?>

                        <tr style="background-color: inherit;font-size: large;font-weight: bold">
                            <td class="text-center" colspan="6">Total RM Cost</td>
                            <td class="text-center">{{number_format($total_rm_cost,2)}}</td>
                        </tr>
                        <tr style="background-color: inherit;font-size: large;font-weight: bold">
                            <td class="text-center" colspan="6">Total Make Cost</td>

                            <td  class="text-center"><?php echo number_format($total,2); ?></td>
                        </tr>

                        <tr style="background-color: inherit;font-size: large;font-weight: bold">
                            <td class="text-center" colspan="6">Grand Total</td>

                            <td  class="text-center"><?php echo number_format($total_rm_cost+$total,2); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left printHide">
                    <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
                </div>
                <?php $tra= DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$row->voucher_no)->where('voucher_type',13)->orderBy('debit_credit',1); ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ShowVoucherDetail" id="" style="display: none">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                            <thead>
                            <tr>
                                <td style="border:1px solid black" colspan="4"><strong><h4>Cost On Issuence</h4></strong></td>
                            </tr>
                            <tr>

                                <th style="border:1px solid black" class="text-center">Sr No</th>
                                <th style="border:1px solid black" class="text-center">Account Head<span class="rflabelsteric"></span></th>
                                <th style="border:1px solid black" class="text-center">Debit<span class="rflabelsteric"></span></th>
                                <th style="border:1px solid black" class="text-center">Credit<span class="rflabelsteric"></span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 1;
                            $debit_total = 0;
                            $credit_total = 0;
                            foreach($tra->get() as $tra): ?>
                            <tr class="text-center">
                                <td style="border:1px solid black;"><?php echo $count++;?></td>
                                <td style="border:1px solid black;">
                                    <?php
                                    $acc_name = CommonHelper::get_single_row('accounts','id',$tra->acc_id);
                                    echo $acc_name->name;
                                    ?>
                                </td>
                                <td style="border:1px solid black;"><?php if($tra->debit_credit == 1): echo number_format($tra->amount,2); $debit_total+=$tra->amount; endif;?></td>
                                <td style="border:1px solid black;"><?php if($tra->debit_credit == 0): echo number_format($tra->amount,2); $credit_total+=$tra->amount; endif;?></td>
                            </tr>
                            <?php endforeach;?>
                            <tr class="text-center">
                                <td style="border:1px solid black" colspan="2">TOTAL</td>
                                <td style="border:1px solid black"><?php echo number_format($debit_total,2)?></td>
                                <td  style="border:1px solid black"><?php echo number_format($credit_total,2)?></td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                <?php $tra= DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$row->voucher_no)->where('voucher_type',14)->orderBy('debit_credit',1); ?>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ShowVoucherDetail" id="" style="display: none">
                    <div class="table-responsive">
                        @if($tra->count()>0)
                        <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                            <thead>
                            <tr>
                                <td style="border:1px solid black" colspan="4"><strong><h4>Cost On Return Return</h4></strong></td>
                            </tr>
                            <tr>

                                <th style="border:1px solid black" class="text-center">Sr No</th>
                                <th style="border:1px solid black" class="text-center">Account Head<span class="rflabelsteric"></span></th>
                                <th style="border:1px solid black" class="text-center">Debit<span class="rflabelsteric"></span></th>
                                <th style="border:1px solid black" class="text-center">Credit<span class="rflabelsteric"></span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 1;
                            $debit_total = 0;
                            $credit_total = 0;
                            foreach($tra->get() as $tra): ?>
                            <tr class="text-center">
                                <td style="border:1px solid black;"><?php echo $count++;?></td>
                                <td style="border:1px solid black;">
                                    <?php
                                    $acc_name = CommonHelper::get_single_row('accounts','id',$tra->acc_id);
                                    echo $acc_name->name;
                                    ?>
                                </td>
                                <td style="border:1px solid black;"><?php if($tra->debit_credit == 1): echo number_format($tra->amount,2); $debit_total+=$tra->amount; endif;?></td>
                                <td style="border:1px solid black;"><?php if($tra->debit_credit == 0): echo number_format($tra->amount,2); $credit_total+=$tra->amount; endif;?></td>
                            </tr>
                            <?php endforeach;?>
                            <tr class="text-center">
                                <td style="border:1px solid black" colspan="2">TOTAL</td>
                                <td style="border:1px solid black"><?php echo number_format($debit_total,2)?></td>
                                <td  style="border:1px solid black"><?php echo number_format($credit_total,2)?></td>
                            </tr>
                            </tbody>

                        </table>
                            @endif
                    </div>
                </div>





                 <?php $tra= DB::Connection('mysql2')->table('transactions as a')
                             ->join('new_purchase_voucher as b','a.voucher_no','=','b.pv_no')
                             ->join('product_creation as c','b.work_order_id','c.id')
                             ->where('c.status',1)
                             ->where('c.id',$row->id)
                              ->orderBy('a.debit_credit',1);

                 ?>


                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ShowVoucherDetail" id="" style="display: none">
                    <div class="table-responsive">
                        @if($tra->count()>0)
                            <table  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
                                <thead>
                                <tr>
                                    <td style="border:1px solid black" colspan="4"><strong><h4>IN Cost</h4></strong></td>
                                </tr>
                                <tr>

                                    <th style="border:1px solid black" class="text-center">Sr No</th>
                                    <th style="border:1px solid black" class="text-center">Account Head<span class="rflabelsteric"></span></th>
                                    <th style="border:1px solid black" class="text-center">Debit<span class="rflabelsteric"></span></th>
                                    <th style="border:1px solid black" class="text-center">Credit<span class="rflabelsteric"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                $debit_total = 0;
                                $credit_total = 0;
                                foreach($tra->get() as $tra): ?>
                                <tr class="text-center">
                                    <td style="border:1px solid black;"><?php echo $count++;?></td>
                                    <td style="border:1px solid black;">
                                        <?php
                                        $acc_name = CommonHelper::get_single_row('accounts','id',$tra->acc_id);
                                        echo $acc_name->name;
                                        ?>
                                    </td>
                                    <td style="border:1px solid black;"><?php if($tra->debit_credit == 1): echo number_format($tra->amount,2); $debit_total+=$tra->amount; endif;?></td>
                                    <td style="border:1px solid black;"><?php if($tra->debit_credit == 0): echo number_format($tra->amount,2); $credit_total+=$tra->amount; endif;?></td>
                                </tr>
                                <?php endforeach;?>
                                <tr class="text-center">
                                    <td style="border:1px solid black" colspan="2">TOTAL {{ $tra->pv_no }}</td>
                                    <td style="border:1px solid black"><?php echo number_format($debit_total,2)?></td>
                                    <td  style="border:1px solid black"><?php echo number_format($credit_total,2)?></td>
                                </tr>
                                </tbody>

                            </table>
                        @endif
                    </div>
                </div>


            </div>
            <div style="line-height:8px;">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h6>Remarks: <?php echo strtoupper($row->desc); ?></h6>
                    </div>
                </div>
                <style>
                    .signature_bor {
                        border-top:solid 1px #CCC;
                        padding-top:7px;
                    }
                </style>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Prepared By: </h6>
                                <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Checked By:</h6>
                                <b>   <p><?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Approved By:</h6>
                                <b>  <p></p></b>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <!--
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Demand Voucher Detail'))!!} ">
            </div>
            <!-->
        </div>
    </div>

<?php
$count=1;
$data=DB::Connection('mysql2')->table('stock')->where('voucher_no',$row->voucher_no)->get();  ?>

    <table width="40%"  class="table table-bordered table-condensed tableMargin sales_Tax_Invoice_data">
        <thead>

        <tr>

            <th style="border:1px solid black" class="text-center">Sr No</th>
            <th style="border:1px solid black" class="text-center">Type<span class="rflabelsteric"></span></th>
            <th style="border:1px solid black" class="text-center">Value<span class="rflabelsteric"></span></th>

        </tr>
        </thead>
       <tbody>
       @foreach($data as $row)
           <tr class="text-center">
               <td><?php echo $count++ ?></td>
               <td>@if($row->voucher_type==1 && $row->pos_status==4) IN @elseif($row->voucher_type==5) Issued @elseif($row->voucher_type==1 && $row->pos_status==3) Returned @endif </td>
               <td><?php echo number_format($row->amount,2) ?></td>

           </tr>
       @endforeach
       </tbody>

    </table>
    <?php }?>

    @if ($pi!=0)
        @if($validation==true)
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

        <button type="submit" id="" class="btn btn-success">Submit</button>

    </div>
            @endif
        @endif

</div>
<script type="text/javascript">
    function ShowHideInnerDetail()
    {
        if ($('#CheckedUnChecked').is(':checked'))
        {
            $('.InnerDetail').fadeIn();
            $('.textChanger').addClass('textBold');
        }
        else
        {
            $('.InnerDetail').css('display','none');
            $('.textChanger').removeClass('textBold');
        }
    }


    function ViewVoucher()
    {
        if($('#ShowVoucher').is(':checked'))
        {
            $('.ShowVoucherDetail').css('display','block');
        }
        else
        {
            $('.ShowVoucherDetail').css('display','none');
        }
    }
</script>

