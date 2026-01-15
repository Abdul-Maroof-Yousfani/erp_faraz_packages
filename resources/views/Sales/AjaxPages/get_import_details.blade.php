<?php use App\Helpers\CommonHelper;
$LoopTotal = 0;
$LoopTotal2 = 0;
        $GrnCount = DB::Connection('mysql2')->table('goods_receipt_note')->where('import_id',$voucher_no)->where('status',1);

foreach($data as $f): $LoopTotal+=$f->amount;endforeach;
foreach($data22 as $f2): $LoopTotal2+=$f2->amount;endforeach;
        $LoopTotal = $LoopTotal+$LoopTotal2;
?>
@include('number_formate')
<style>
    .panel{
        background-color: #e9e6ef !important;
    }
</style>
<div class="row">
    <div class="">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center">FCY Average Rate </th>
                            <th class="text-center">FCY Amount</th>
                            <th class="text-center" >Amount In PKR</th>
                            <th class="text-center" >Edit </th>
                            <th class="text-center" >Delete </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ImportPay = DB::Connection('mysql2')->table('import_payment')->where('import_id',$voucher_no)->where('status',1)->get();
                        $CounterP = 1;
                        $TotForeignCurrency = 0;
                        $TotAmountInPkr = 0;
                        foreach($ImportPay as $Fil):
                        $TotForeignCurrency+=$Fil->foreign_amount;
                        $TotAmountInPkr+=$Fil->amount_in_pkr;
                        ?>
                        <tr id="import{{$Fil->id}}">
                            <td><input type="text" name="CurrencyRate[]" id="CurrencyRate<?php echo $CounterP?>" value="<?php echo $Fil->cureency_rate?>" readonly  class="text-right number_format" disabled></td>
                            <td><input type="text" name="CurrencyAmount[]" id="CurrencyAmount<?php echo $CounterP?>" value="<?php echo $Fil->foreign_amount?>" readonly class="text-right foreign number_format" disabled></td>
                            <td><input type="text" name="AmountInPkr[]" id="AmountInPkr<?php echo $CounterP?>" value="<?php echo $Fil->amount_in_pkr?>" readonly  class="text-right pkr number_format" disabled></td>
                            <td>
                                <?php if($GrnCount->count() > 0):?>
                                    <span class="text-danger"><i class="fa fa-ban" aria-hidden="true"></i></span>
                                <?php else:?>
                                    <button class="btn btn-xs btn-primary" id="BtnPayEdit<?php echo $Fil->id?>" onclick="EditPayGetData('<?php echo $Fil->id?>','<?php echo $CounterP?>')">Edit</button>
                                <?php endif;?>


                            </td>
                            <td><button onclick="delete_payment('{{$Fil->id}}',1)" type="button" class="btn btn-danger btn-xs">Delete</button></td>
                        </tr>
                        <?php
                        $CounterP++;
                        endforeach;
                        ?>
                        <tr>
                            <?php $CurrencyRate = 0;
                            if($TotAmountInPkr > 0):
                            $CurrencyRate = $TotAmountInPkr/$TotForeignCurrency;
                            else:
                                $CurrencyRate =0;
                            endif;
                            ?>
                            <th class="text-center"><?php echo number_format($CurrencyRate,2);?></th>
                            <th class="text-right number_format"><?php echo number_format($TotForeignCurrency,2)?></th>
                            <th class="text-right number_format"><?php echo number_format($TotAmountInPkr,2)?></th>
                            <th style="background-color: #ccc;"></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php

        $Duties=DB::Connection('mysql2')->select('select * from import_expense where import_id='.$voucher_no.' and status=1');

        $DCounter =0;
        $duty = 0; $DutyCounter = 0;
        $eto = 0; $EtoCounter = 0;
        $do = 0; $DoCounter = 0;
        $appraisal = 0; $AppraisalCounter = 0;
        $fright = 0; $FrightCounter = 0;
        $insurance = 0; $InsuranceCounter = 0;
        $expense = 0; $ExpenseCounter = 0;
        $other_expense = 0; $OtherExpenseCounter = 0;
        $AllExpTot = 0;
        if(count($Duties) > 0):
            foreach($Duties as $DFil):
                $DCounter++;
                $duty+=$DFil->duty;
                $eto+=$DFil->eto;
                $do+=$DFil->do;
                $appraisal+=$DFil->appraisal;
                $fright+=$DFil->fright;
                $insurance+=$DFil->insurance;
                $expense+=$DFil->expense;
                $other_expense+=$DFil->other_expense;
                if($duty > 0): $DutyCounter++; endif;
                if($eto > 0): $EtoCounter++; endif;
                if($do > 0): $DoCounter++; endif;
                if($appraisal > 0): $AppraisalCounter++; endif;
                if($fright > 0): $FrightCounter++; endif;
                if($insurance > 0): $InsuranceCounter++; endif;
                if($expense > 0): $ExpenseCounter++; endif;
                if($other_expense > 0): $OtherExpenseCounter++; endif;
                ?>

        <?php endforeach;
        endif;
        ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" colspan="3">Book Expense</th>
                        </tr>
                        </thead>
                        <tbody class="text-right">
                        <?php if($duty > 0): $AllExpTot+=$duty;?>
                        <tr>
                            <th>Duty</th>
                            <td><?php echo $DutyCounter;?></td>
                            <td><input type="text" name="duty" id="duty" value="<?php echo $duty?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($eto > 0): $AllExpTot+=$eto;?>
                        <tr>
                            <th>Eto</th>
                            <td><?php echo $EtoCounter;?></td>
                            <td><input type="text" name="eto" id="eto" value="<?php echo $eto?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($do > 0): $AllExpTot+=$do;?>
                        <tr>
                            <th>Do</th>
                            <td><?php echo $DoCounter?></td>
                            <td><input type="text" name="do" id="do" value="<?php echo $do?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($appraisal > 0): $AllExpTot+=$appraisal;?>
                        <tr>
                            <th>Appraisal</th>
                            <td><?php echo $AppraisalCounter?></td>
                            <td><input type="text" name="appraisal" id="appraisal" value="<?php echo $appraisal?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($fright > 0): $AllExpTot+=$fright;?>
                        <tr>
                            <th>Fright</th>
                            <td><?php echo $FrightCounter?></td>
                            <td><input type="text" name="fright" id="fright" value="<?php echo $fright?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($insurance > 0): $AllExpTot+=$insurance;?>
                        <tr>
                            <th>Insurance</th>
                            <td><?php echo $InsuranceCounter?></td>
                            <td><input type="text" name="insurance" id="insurance" value="<?php echo $insurance?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($expense > 0): $AllExpTot+=$expense;?>
                        <tr>
                            <th>Expense</th>
                            <td><?php echo $ExpenseCounter?></td>
                            <td><input type="text" name="expense" id="expense" value="<?php echo $expense?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <?php if($other_expense > 0): $AllExpTot+=$other_expense;?>
                        <tr>
                            <th>Other Expense</th>
                            <td><?php echo $OtherExpenseCounter?></td>
                            <td><input type="text" name="otherexpense" id="otherexpense" value="<?php echo $other_expense?>" class="text-right number_format" readonly disabled></td>
                        </tr>
                        <?php endif;?>
                        <tr>
                            <th colspan="2" class="text-center">TOTAL</th>

                            <td class="text-right number_format"><?php echo number_format($AllExpTot,2)?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2" class="text-center">Edit Expense</th>
                            </tr>
                            <tr>
                                <td>Pv Date</td>
                                <td>Edit</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($Duties as $FilEdit):?>
                            <tr id="exp{{$FilEdit->id}}">
                                <td><?php echo $FilEdit->pv_date?></td>
                                <td>
                                    <?php if($GrnCount->count() > 0):?>
                                        <span class="text-danger"><i class="fa fa-ban" aria-hidden="true"></i></span>
                                    <?php else:?>
                                        <button type="button" class="btn btn-xs btn-primary" onclick="EditExpGetData('<?php echo $FilEdit->id?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                    <?php endif;?>


                                </td>
                                <td><button onclick="delete_payment_exp('{{$FilEdit->id}}',2)" type="button" class="btn btn-danger btn-xs">Delete</button></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <h3 style="text-align: center">Import By Piece</h3>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">


                <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                    <thead>
                    <tr>
                        <th class="text-center">S.NO</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" >Uom</th>
                        <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>

                        <th class="text-center">FCY Rate</th>
                        <th class="text-center">FCY Amount%</th>



                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter=1;
                    $total=0;
                    $total_qtyt=0;
                    $total_cureency =0;
                    $total_amount=0;
                    foreach ($data as $row): ?>
                    <?php
                    $sub_ic_detail=CommonHelper::get_subitem_detail($row->item_id);
                    $sub_ic_detail= explode(',',$sub_ic_detail); ?>

                    <tr>
                        <td>{{$counter++}}</td>
                        <td>{{$sub_ic_detail[4]}}</td>
                        <td><?php echo  CommonHelper::get_uom_name($sub_ic_detail[0]); ?></td>
                        <td>{{$row->qty}}</td>
                        <td>{{$row->foreign_currency_price}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>

                    <?php $total_qtyt+=$row->qty; $total_cureency+=$row->foreign_currency_price; $total_amount+=$row->amount;  endforeach ?>

                    <tr style="font-size:larger;font-weight: bolder">
                        <td colspan="3">Total</td>
                        <td>{{$total_qtyt}}</td>
                        <td>{{$total_cureency}}</td>
                        <td>{{number_format($total_amount,2)}}</td>

                    </tr>
                    <input type="hidden" name="voucher_no" id="voucher_no" value="{{$voucher_no}}"/>


                </table>
            </div>
        </div></div>


</div>

<div class="row">
    <h3 style="text-align: center">Import By Weight</h3>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">


                <table style="width: 100%"  class="table table-bordered table-striped table-condensed tableMargin">
                    <thead>
                    <tr>
                        <th class="text-center">S.NO</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" >Uom</th>
                        <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                        <th class="text-center">Total Weight</th>
                        <th class="text-center">Rate Per Weight</th>
                        <th class="text-center">Total As Per Weight</th>
                        <th class="text-center">FCY Rate</th>
                        <th class="text-center">FCY Amount%</th>



                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter2=1;
                    $total2=0;
                    $total_qtyt2=0;
                    $total_weight2 = 0;
                    $total_rate_per_weight2 = 0;
                    $as_per_weight2 = 0;
                    $total_cureency2 =0;
                    $total_amount2=0;
                    foreach ($data22 as $row): ?>
                    <?php
                    $sub_ic_detail=CommonHelper::get_subitem_detail($row->item_id);
                    $sub_ic_detail= explode(',',$sub_ic_detail); ?>

                    <tr>
                        <td>{{$counter2++}}</td>
                        <td>{{$sub_ic_detail[4]}}</td>
                        <td><?php echo  CommonHelper::get_uom_name($sub_ic_detail[0]); ?></td>
                        <td>{{$row->qty}}</td>
                        <td>{{$row->total_weight}}</td>
                        <td>{{$row->total_rate_per_weight}}</td>
                        <td>{{$row->as_per_weight}}</td>
                        <td>{{$row->foreign_currency_price}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>

                    <?php $total_qtyt2+=$row->qty; $total_cureency2+=$row->foreign_currency_price; $total_amount2+=$row->amount;
                            $total_weight2+=$row->total_weight; $total_rate_per_weight2+= $row->total_rate_per_weight; $as_per_weight2+=$row->as_per_weight;
                    endforeach ?>

                    <tr style="font-size:larger;font-weight: bolder">
                        <td colspan="3">Total</td>
                        <td>{{$total_qtyt2}}</td>
                        <td>{{$total_weight2}}</td>
                        <td>{{$total_rate_per_weight2}}</td>
                        <td>{{$as_per_weight2}}</td>
                        <td>{{$total_cureency2}}</td>
                        <td>{{number_format($total_amount2,2)}}</td>
                        <input type="hidden" name="tot_amount" id="tot_amount" value="<?php echo $total_amount+$total_amount2;?>">
                    </tr>
                    <tr>
                        <td colspan="8"><storng style="font-size: 20px;">Grand Total</storng></td>
                        <td><strong id="grand_total" style="font-size: 20px;"><?php echo $total_amount+$total_amount2;?></strong></td>
                    </tr>

                    <input type="hidden" name="voucher_no" id="voucher_no" value="{{$voucher_no}}"/>


                </table>
            </div>
        </div></div>


</div>
<?php if($GrnCount->count() > 0):?>
<div class="row" id="payment_section">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div style="width:40%; float:left;">
                    <?php $row = $GrnCount->first();?>
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td style="width:40%;">GRN No.</td>
                            <td style="width:60%;"><?php echo strtoupper($row->grn_no);?></td>
                        </tr>
                        <tr>
                            <td>GRN Date</td>
                            <td><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                        </tr>
                        <tr>
                            <td>Supplier Invoice No</td>
                            <td><?php  echo $row->supplier_invoice_no;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width:40%; float:right;">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Voucher No</td>
                            <td><?php  echo $row->po_no;?></td>
                        </tr>
                        <tr>
                            <td>Supplier Name</td>
                            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById(1,'supplier','name',$row->supplier_id);?></td>
                        </tr>
                        <tr>
                            <td>Supplier Address</td>
                            <td width=""><?php echo CommonHelper::get_supplier_address($row->supplier_id);;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table  class="table table-bordered table-striped table-condensed tableMargin">
                    <thead>
                    <tr>

                        <th class="text-center">Item Name</th>
                        <th class="text-center">Batch Code</th>
                        <th class="text-center">Qty<span class="rflabelsteric"></span></th>
                        <th class="text-center"> Rate</th>
                        <th class="text-center"> Amount</th>
                        <th class="text-center">Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                echo $row->grn_no;die;
                    $grnDataDetail = DB::Connection('mysql2')->table('grn_data')->where('grn_no','=',$row->grn_no)->get();

                    $counter = 1;
                    foreach ($grnDataDetail as $row1)
                    {

                    $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                    $sub_ic_detail= explode(',',$sub_ic_detail);
                    ?>




                    <?php

                    ?>
                    <tr>
                        <td class="text-center">{{CommonHelper::getCompanyDatabaseTableValueById(1,'subitem','sub_ic',$row1->sub_item_id)}}</td>
                        <td class="text-center"><?php echo $row1->batch_code?></td>
                        <td  class="text-center"><?php echo  number_format($row1->purchase_recived_qty,2)?></td>
                        <td  class="text-center"><?php echo  number_format($row1->rate,2)?></td>
                        <td  class="text-center"><?php echo  number_format($row1->amount,2)?></td>
                        <td class="text-center">
                            <?php $Warehouse =  CommonHelper::get_single_row('warehouse','id',$row1->warehouse_id);
                            echo $Warehouse->name;
                            ?>
                        </td>
                    </tr>
                    <?php

                    ?>

                    <?php  $counter++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php else:?>
<div class="row" id="payment_section">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="font-size: larger">

            <label onclick="get_pay_form(1,0)" class="radio-inline"><input type="radio" name="payment" >Book Payment</label>
            <label onclick="get_pay_form(2,0)" class="radio-inline"><input type="radio" name="payment">Book Expense </label>
        </div>

        <span id="pay"></span>
    </div>
</div>
<?php endif;?>


<?php if($GrnCount->count() > 0):?>

<?php else:
          $total=$LoopTotal-$TotForeignCurrency;
          $total=number_format($total);
if($total==0):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <button onclick="convert_grn()" type="button" class="btn btn-sm btn-primary" id="BtnConvert">Convert To Grn <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>

        </div>
    </div>
<?php
endif;
endif;?>
<a  href="{{url('sales/view_convert_grn?id='.$voucher_no)}}" class="btn btn-sm btn-primary" id="" target="_blank">View Convert GRN </a>
@include('Sales.import_grn')
<script>

    $(document).ready(function(){
        $('.number_format').number(true,2);
    });

    function get_pay_form(type,id) {

        var voucher_no = $('#voucher_no').val();
        $('#pay').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url: '{{url('/sdc/get_pay_form')}}',
            data: {type: type, voucher_no: voucher_no,id:id},
            type: 'GET',
            success: function (response) {



                $('#pay').html(response);
                $('#cr_account').select2();

                var grand_total=$('#grand_total').html();
                $('.grand_total').val(grand_total);

            }
        });
    }

    function EditPayGetData(Id,Count)
    {
        $('#CurrencyAmount'+Count).removeClass('foreign');
        $('html, body').animate({
            scrollTop: $("#payment_section").offset().top
        }, 1000);
        get_pay_form(1,Id);
    }

    function EditExpGetData(Id)
    {

        $('html, body').animate({
            scrollTop: $("#payment_section").offset().top
        }, 1000);
        get_pay_form(2,Id);
    }



    function delete_payment(id,type)
    {
        if (confirm('Are you sure you want to delete this request')) {
            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/sdc/import_payment_delete',
                type: 'GET',
                data: {id: id,type:type},
                success: function (response) {

                    if (response=='no')
                    {
                        alert('can not delete');
                        return false;
                    }
                    alert('Deleted');
                    // alert(response);
                    $('#import' + response).remove();

                }
            });
        }
        else{}
    }

    function delete_payment_exp(id,type)
    {
        if (confirm('Are you sure you want to delete this request')) {
            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/sdc/import_payment_delete',
                type: 'GET',
                data: {id: id,type:type},
                success: function (response) {


                    if (response=='no')
                    {
                        alert('can not delete');
                       return false;
                    }
                    alert('Deleted');
                    $('#exp' + response).remove();

                }
            });
        }
        else{}
    }
</script>
