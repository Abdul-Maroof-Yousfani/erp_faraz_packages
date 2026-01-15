<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="width:50%; float:left;">
            <table  class="table table-bordered table-striped table-condensed tableMargin">
                <tbody>
                <tr>
                    <td style="width:40%;">JV No.</td>
                    <td style="width:60%;"><?php echo $NewJvs->jv_no;?></td>
                </tr>
                {{--<tr>--}}
                {{--<td style="width:40%;">Ref / Bill No.</td>--}}
                {{--<td style="width:60%;">< ?php echo $row->slip_no;?></td>--}}
                {{--</tr>--}}
                <tr>
                    <td>JV Date</td>
                    <td><?php echo FinanceHelper::changeDateFormat($NewJvs->jv_date);?></td>
                </tr>
                </tbody>
            </table>
        </div>

        {{--<div style="width:30%; float:right;">--}}
        {{--<table  class="table table-bordered table-striped table-condensed tableMargin">--}}
        {{--<tbody>--}}
        {{--<tr>--}}
        {{--<td style="width:40%;">Bill Date</td>--}}
        {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->bill_date);?></td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td style="width:40%;">Due Date</td>--}}
        {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->due_date);?></td>--}}
        {{--</tr>--}}

        {{--</tbody>--}}
        {{--</table>--}}
        {{--</div>--}}
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="text" id="myInput2" onkeyup="myFunctionJvs()" placeholder="Search for names.." title="Type in a name" class="form-control">
        <div class="table-responsive" style="height:290px;overflow:auto;">

            <table id="myTable2" class="table table-bordered table-striped table-condensed tableMargin">
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
                foreach ($NewJvData as $row2) {
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
                    <td><?php  echo $row2->description;?></td>
                    <td class="debit_amount text-right">

                        <?php
                        if($row2->debit_credit == 1)
                        {
                            $g_t_credit += $row2->amount;
                            echo number_format($row2->amount,2);
                        }
                        ?>
                    </td>
                    <td class="credit_amount text-right">
                        <?php
                        if($row2->debit_credit == 0)
                        {
                            $g_t_debit += $row2->amount;
                            echo number_format($row2->amount,2);
                        }
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
                    <td class="text-right"><b><?php echo number_format($g_t_credit,0);?></b></td>
                    <td class="text-right"><b><?php echo number_format($g_t_debit,0);?></b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script !src="">function myFunctionJvs() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable2");
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