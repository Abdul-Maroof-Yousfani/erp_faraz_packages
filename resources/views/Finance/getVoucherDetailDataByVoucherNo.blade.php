<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="width:40%; float:left;">
            <table  class="table table-bordered table-striped table-condensed tableMargin">
                <tbody>
                <tr>
                    <td style="width:40%;">BPV No.</td>
                    <td style="width:60%;"><?php echo $NewPv->pv_no?></td>
                </tr>
                <tr>
                    <td style="width:40%;">Ref / Bill No.</td>
                    <td style="width:60%;"><?php echo $NewPv->bill_no;?></td>
                </tr>
                <tr>
                    <td>BPV Date. </td>
                    <td><?php echo FinanceHelper::changeDateFormat($NewPv->pv_date);?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="width:40%; float:right;">
            <?php if($Condition == 'bp'):?>
            <table class="table table-bordered table-striped table-condensed tableMargin">
                <tbody>
                <tr>
                    <td style="width:40%;">Cheque No.</td>
                    <td style="width:60%;"><?php echo $NewPv->cheque_no;?></td>
                </tr>
                <tr>
                    <td>Cheque Date</td>
                    <td><?php  echo  FinanceHelper::changeDateFormat($NewPv->cheque_date);?></td>
                </tr>
                </tbody>
            </table>
            <?php endif;?>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="text" id="myInput1" onkeyup="myFunctionPvs()" placeholder="Search for names.." title="Type in a name" class="form-control">
        <div class="table-responsive" style="height:290px;overflow:auto;">

            <table id="myTable1" class="table table-bordered table-striped table-condensed tableMargin">
                <thead>
                <tr>
                    <th class="text-center" style="width:50px;">S.No</th>
                    <th class="text-center">Account</th>





                    <th class="text-center">Paid To</th>

                    <th class="text-center">Description</th>
                    <th class="text-center" style="width:150px;">Debit</th>
                    <th class="text-center" style="width:150px;">Credit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $counter = 1;
                $g_t_debit = 0;
                $g_t_credit = 0;
                foreach ($NewPvData as $row2)
                {
                ?>
                <tr>
                    <td class="text-center"><?php echo $counter++;?></td>
                    <td><?php  echo FinanceHelper::getAccountNameByAccId($row2->acc_id,1);?></td>
                    <?php
                    $Type = "";
                    if($row2->paid_to_type==1)
                    {
                        $Type = '[Employee)';
                    }
                    elseif($row2->paid_to_type==2)
                    {
                        $Type = '[Supplier)';
                    }
                    elseif($row2->paid_to_type==3)
                    {
                        $Type = '[Client)';
                    }
                    elseif($row2->paid_to_type==4)
                    {
                        $Type = '[Other)';
                    }
                    else
                    {
                        $Type= "";
                    }
                    ?>
                    <td class="text-center">{{CommonHelper::get_paid_to_name($row2->paid_to_id,$row2->paid_to_type).$Type}}</td>

                    <td><?php  echo $row2->description; ?></td>

                    {{--<td> < ?php echo CommonHelper::get_item_name($row2->sub_item);?></td>--}}

                    {{--<td class="text-center">< ?php if ($row2->qty!=''): echo $row2->qty;endif; ?></td>--}}
                    {{--<td class="text-center">< ?php echo number_format($row2->rate,2); ?></td>--}}

                    <td   class="debit_amount text-right">
                        <?php
                        if($row2->debit_credit == 1){
                        $g_t_credit += $row2->amount;?>


                        <a onclick="amount({{$row2->amount}})" style="cursor: pointer"> <?php echo  number_format($row2->amount,2); ?></a>
                        <?php
                        }else{}
                        ?>
                    </td>
                    <td class="credit_amount text-right">
                        <?php
                        if($row2->debit_credit == 0){
                        $g_t_debit += $row2->amount; ?>
                        <a onclick="amount({{$row2->amount}})" style="cursor: pointer">  <?php echo     number_format($row2->amount,2);?></a>
                        <?php
                        }else{}
                        ?>
                    </td>

                </tr>
                <?php
                }
                ?>
                <tr class="sf-table-total">
                    <td colspan="4">
                        <label for="field-1" class="sf-label"><b>Total</b></label>
                    </td>
                    <td id="" class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                    <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                </tr>
                <input id="d_t_amount_1" type="hidden" value="<?php echo $g_t_debit ?>"/>
                <tr>

                    <td colspan="6" style="font-size: 15px;" id="rupees"><script>toWords(1);</script></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h6 id="amount_in_words">Description: TEST</h6>
            </div>
        </div>
        <style>
            .signature_bor {
                border-top:solid 1px #CCC;
                padding-top:7px;
            }
        </style>



    </div>
</div>

<script !src="">function myFunctionPvs() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable1");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>