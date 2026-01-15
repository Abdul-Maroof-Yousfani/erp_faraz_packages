
<?php use App\Helpers\CommonHelper; ?>
<style>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }

    tr:hover {
        background-color: yellow;
    }
</style>


                        <div class="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" class="form-control">
                                        <div class="table-responsive" style="height:500px;overflow:auto;">

                                            <table  id="myTable" class="table table-bordered sf-table-list">
                                                <thead>
                                                <th style="" class="text-center">S.No</th>
                                                {{--<th class="text-center">Acc-Id</th>--}}

                                                <th class="text-left">Supplier Name</th>
                                                <th class="text-center">Ledger Amount</th>
                                                <th class="text-center">Bill Wise Amount</th>
                                                <th class="text-center">View Bill Wise</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $Counter =1;
                                                $payable=0;
                                                $advance=0;
                                                $total_amount=0;
                                                $ledger_amount=0;
                                                foreach($Supplier as $row):

                                              $bill_amount=  CommonHelper::bill_wise_remaining_amount($row->id)
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $Counter++;?></td>
                                                    {{--<td>< ?php echo $Fil->acc_id?></td>--}}

                                                    <td class="text-left"><b style="font-size: large;font-weight: bolder"> <a target="_blank" href="<?php echo URL('finance/viewLedgerReport?AccId='.$row->acc_id.'&&FromDate='.$from.'&&ToDate='.$to.'&&m='.$m)?>"><?php echo $row->name?></a></b></td>
                                                    <td class="text-right">
                                                        <?php $amount=CommonHelper::get_ledger_amount($row->acc_code,$m,0,1,$from,$to);
                                                        if ($amount<0):
                                                            $other_amount=$amount*-1;
                                                            $ledger_amount=$amount;
                                                            $total_amount+=$amount;
                                                            $amount=$amount*-1;
                                                            $advance+=$amount;
                                                            $amount=number_format($amount,2);
                                                            $amount='('.$amount.')';


                                                        else:
                                                            $other_amount=$amount;
                                                            $ledger_amount=$amount;
                                                            $payable+=$amount;
                                                            $total_amount+=$amount;
                                                            $amount=number_format($amount,2);
                                                        endif;
                                                        echo $amount;
                                                        ?>
                                                    </td>
                                                    <td @if($bill_amount!=$ledger_amount) style="color: red" @endif class="text-right">{{number_format($bill_amount,2)}}</td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-success" onclick="Generate('<?php echo $row->id?>')">View</a>
                                                    </td>

                                                </tr>
                                                <?php endforeach;?>
                                                <tr style="background-color: darkgray">
                                                    <td class="text-center" style="font-weight: bold;font-size: large" colspan="2">Total</td>
                                                    <td class="text-right" style="font-weight: bold;font-size: large" colspan="1">{{number_format($total_amount,2)}}</td>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <h4>Payables : {{number_format($payable,2)}}</h4>
                                            <h4>Advance : {{number_format($advance,2)}}</h4>
                                            <?php $total_payables=$payable-$advance;
                                            if ($total_payables<0):
                                                $total_payables=$total_payables*-1;
                                            endif;
                                            ?>
                                            <h4>Total Payable : {{number_format($total_payables,2)}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" id="dataAjax">

                                    </div>
                                </div>
                            </div>
                        </div>
<script !src="">

    function Generate(SupplierId)
    {

        $('#dataAjax').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        var m = '<?php echo $_GET['m'];?>';
        $.ajax({
            url: '<?php echo url('/')?>/DataSortBySupplier',
            type: "GET",
            data: {supplier_id:SupplierId, m:m,class:''},
            success:function(data) {
                $("#dataAjax").show();
                $("#dataAjax").html(data);
            }
        });
    }
    function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
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

