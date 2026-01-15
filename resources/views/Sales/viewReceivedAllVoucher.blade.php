
<div class="" id="contentUnique">
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\ReuseableCode;

    ?>
    <?php
    $total_amount=0;

    ?>
    <div class="row">
        <div class="col-lg-4">
            <table class="table table-bordered">
                <tr>
                    <th>Voucher No</th>
                    <th>Adjust Amount</th>
                </tr>

                <tbody>
                @foreach($AllReceipt as $row)
                    <tr>
                        <td>

                            <a class="btn btn-sm btn-success" onclick="getVoucherDetailByVoucherNo('<?php echo $row->receipt_id?>')"><?php echo $row->receipt_no?></a>
                        </td>
                        <td>{{number_format($row->received_amount,2)}}</td>
                        <?php $total_amount+=$row->received_amount; ?>
                    </tr>
                @endforeach
                <tr style="background-color: darkgrey">
                    <td>Total</td>
                    <td>{{number_format($total_amount,2)}}</td>
                </tr>


                </tbody>
            </table>
        </div>
        <div class="col-lg-8" id="dataAjaxView"></div>
    </div>
</div>


<script>


    function getVoucherDetailByVoucherNo(id)
    {
        var m = '<?php echo Session::get('run_company')?>';
        $('#dataAjaxView').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');


        $.ajax({
            url: '<?php echo url('/')?>/sdc/viewReceiptVoucher',
            type: "GET",
            data: {id:id,m:m},
            success:function(data) {
                $("#dataAjaxView").show();
                $("#dataAjaxView").html(data);
            }
        });
    }
    function check_amount(index)
    {
        var advance=  $('#advance'+index).val();
        var payable=  parseFloat($('#total_payable').val());
        advance=advance.split(',');
        advance=advance[1];
        advance=parseFloat(advance);
        advance=advance*-1;
        if (advance==0)
        {
            alert('select Voucher No');
            $('#amount'+index).val(0);
            return false;
        }
        else
        {

            var adjust_amount= parseFloat($('#amount'+index).val());

            if (adjust_amount > advance)
            {
                alert('Amount Should Not Be Greater than '+advance);
                $('#amount'+index).val(0);
                return false;

            }

            if (adjust_amount >payable )
            {
                alert('Amount Should Not Be Greater than '+payable);
                $('#amount'+index).val(0);
                return false;

            }
        }


    }

    $( "#AdjustmentForm" ).submit(function( event )
    {
        event.preventDefault();
        $('#contentUnique').html('<tr><td colspan="10" class="loader"></td></tr>');
        var me=$(this);
        $.ajax({
            url: me.attr('action'),
            type: 'post',
            data: me.serialize(),
            success: function (response)
            {


                $('#showDetailModelOneParamerter').modal('hide');
                $('#contentUnique').html('');
            }

        })
    })

</script>