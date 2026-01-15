
<div class="" id="contentUnique">
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

        // get data of paid
$data=CommonHelper::get_advancee_from_outstanding($supplier_id);

        // get remaining amount of purchase
$PurchaseAmount = CommonHelper::PurchaseAmountCheck($id);
$PaymentAmount = CommonHelper::PaymentPurchaseAmountCheck($id);
$RemainingAmount = $PurchaseAmount-$PaymentAmount;

 // get total purchase
 $purchase_amount=ReuseableCode::get_purchased_amount($id);


?>
<?php
$total_amount=0;
$adjusted_data=ReuseableCode::already_adjust_amount_fetch($supplier_id,$id) ?>
    <div class="row">
        <div class="col-lg-4">
@if (!empty($adjusted_data))

    <h3>Already Adjusted Amount</h3>

    <table class="table table-bordered">
        <tr>
            <th>Voucher No</th>
            <th>Adjust Amount</th>
        </tr>

        <tbody>
        @foreach($adjusted_data as $row)
            <tr>
                <td><a class="btn btn-sm btn-success" onclick="getVoucherDetailByVoucherNo('<?php echo $row->new_pv_no?>')"><?php echo $row->new_pv_no?></a></td>
                <td>{{number_format($row->amount,2)}}</td>
                <?php $total_amount+=$row->amount; ?>
            </tr>
        @endforeach
        <tr style="background-color: darkgrey">
            <td>Total</td>
            <td>{{number_format($total_amount,2)}}</td>
        </tr>


        </tbody>
    </table>

@endif
@if ($purchase_amount!=0)

    <p>Purchase Amount {{number_format($purchase_amount,2)}}</p>
    <p>Paid Amount {{number_format($total_amount,2)}}</p>
    <p>Total <b> {{number_format($purchase_amount-$total_amount,2)}}</b></p>
    @endif
<input type="hidden" id="total_payable" value="{{$purchase_amount-$total_amount}}"/>




<form id="AdjustmentForm" method="post" action="{{url('/adjust_amount_entry')}}">
    <?php


    foreach($data as $key => $row):


    $diffrence=CommonHelper::get_debit_credit_from_outstanding($supplier_id,$row->new_pv_no);

    if ($diffrence<0):?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <input type="hidden" name="supplier" value="{{$supplier_id}}"/>
        <input type="hidden" name="purchase_voucher_id" value="{{$id}}"/>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="sf-label"> Advance</label>
            <span class="rflabelsteric"><strong>*</strong></span>
            <?php     ?>
            <select   name="advance[]" id="advance{{$key}}" class="form-control select2 requiredField">
                <option value="0,0"> SELECT</option>
                <option value="<?php echo $row->new_pv_no.','.$diffrence ?>"><?php echo $row->new_pv_no.'=>'.number_format($diffrence,2); ?></option>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label class="sf-label"> Amount Adjust</label>
            <input onkeyup="check_amount('{{$key}}')" type="text" name="adjust_amount[]" id="amount{{$key}}" class="form-control">


        </div>
    </div>
    <?php endif; endforeach ?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{ Form::submit('Submit', ['class' => '']) }}
        </div>
    </div>

</form>
</div>
        <div class="col-lg-8" id="dataAjaxView"></div>
    </div>
</div>


<script>


    function getVoucherDetailByVoucherNo(VoucherNo)
    {

        $('#dataAjaxView').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');


        $.ajax({
            url: '<?php echo url('/')?>/getVoucherDetailDataByVoucherNo',
            type: "GET",
            data: {VoucherNo:VoucherNo},
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