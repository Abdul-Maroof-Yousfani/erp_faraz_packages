

<?php


echo $url= Request::url(); 
if ($url==URL::to('/finance/createBankPaymentVoucherForm')):
    $strn='';
    $register_sales_tax='';
endif;

?>
<div class="container-fluid">
    <h4>FBR Sales Tax Withholding</h4>

    <input type="hidden" id="fbr_sales_tax" value="">
    <div class="well">-
        <div class="row">

            <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 frb_tax">

                <label class="radio-inline">
                    <input onclick="fbr(1)" type="radio" name="fbr_sales_tax" id="business" value="1"> Applicable
                </label>
                <label class="radio-inline">
                    <input checked onclick="fbr('2')" type="radio" name="fbr_sales_tax" id="company" value="2">Non  Applicable
                </label>

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 fbr">


                <input style="color: red" class="form-control" value="{{$strn}}" type="text" readonly name="strn" id="strn" />

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 fbr">



                <input  type="text" class="form-control" id="fbr_amount" name="fbr_amount" value="">
            </div>

            <div id="" class="col-lg-3 col-md-3 col-sm-3 col-xs-12">



                <p style="display: none;color: red" id="fbr_html" cite="">

                </p>
            </div>

            <div  id="" style="display: none;font-size: 12px;" class="col-lg-7 col-md-7 col-sm-7 col-xs-12 fbr">

                <!-- > Register In  Sales tax <!-->

                <label>Register In Sales Tax</label>

                <label class="radio-inline">
                    <input  onclick="active_status_check(1)"  @if($register_sales_tax==1)checked @endif   type="radio" name="registerd<?php  ?>" id="registerd<?php  ?>" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input  onclick="active_status_check(2)" @if($register_sales_tax!=1)checked @endif    type="radio" name="registerd<?php  ?>" id="no_registerd<?php  ?>" value="2">No
                </label>

                <!-- > Register In  Sales tax End<!-->


                &nbsp;&nbsp;&nbsp;

                <!-- > Active Status <!-->
                <label class="active_status">Active In Sales Tax</label>
                <label class="radio-inline active_status">
                    <input class="checkbox1" ondblclick="unchech(this.id)"    type="radio" name="active_status<?php  ?>" id="active_status_yes<?php ?>" value="1">Yes
                </label>


                <label class="radio-inline active_status">
                    <input class="checkbox1" ondblclick="unchech(this.id)"  type="radio" name="active_status<?php ?>" id="active_status_no<?php ?>" value="2">No
                </label>

                <label class="">Advertisment Services</label>
                <label class="radio-inline">
                    <input  class="checkbox2"  ondblclick="unchech(this.id)"   type="radio" name="advertisment" id="advertisment_service_yes" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input class="checkbox2" ondblclick="unchech(this.id)"    type="radio" name="advertisment" id="advertisment_service_no" value="2">No
                </label>

            </div>
        </div>

        <div class="row">
            <div  id="" style="display: none;font-size: 12px;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fbr">
                <input style="display: none;float: right"  id="btn_cal_fbr" type="button" onclick="sindh_sales_tax_calculation()" class="btn-primary" value="Calculate"/>
            </div>

        </div>


    </div>

</div>




<script>
    function fbr(type)

    {

        if (type==1)
        {

            $(".advertisment").fadeIn(500);
            $(".fbr").fadeIn(500);
            $("#btn_cal_fbr").fadeIn(500);

            if ($("#registerd").is(':checked'))
            {
                $(".active_status").fadeIn(500);
                $(".exemt").fadeIn(500);
            }
            else
            {
                $(".active_status").fadeOut(500);
                $(".exemt").fadeOut(500);
            }
        }
        else
        {
            $(".fbr").fadeOut(500);
            $(".advertisment").fadeOut(500);
            $("#btn_cal_fbr").fadeOut(500);
        }
    }

    function sindh_sales_tax_calculation()
    {

        $('#btn_cal_fbr').val('waiting......');
        var actice_status=0;
        var advertisment=0;
        var registerd=  document.querySelector('input[name="registerd"]:checked').value;

        if ($(".checkbox1").is(':checked'))
        {

            actice_status=  document.querySelector('input[name="active_status"]:checked').value;
        }
        else
        {

            actice_status= 0;
        }

        if ($(".checkbox2").is(':checked'))
        {
            advertisment=  document.querySelector('input[name="advertisment"]:checked').value;
        }
        else
        {
            advertisment= 0;
        }


        $.ajax({
            url: '<?php echo url('/')?>/fdc/fbr_tax_calculation',
            type: "GET",
            data:{registerd:registerd,actice_status:actice_status,advertisment:advertisment},

            success:function(data)
            {
                var response=data.split(',');
                $('#fbr_html').css('display','block');
                $('#fbr_html').html(response[0]);
                $('#btn_cal_fbr').val('Calculate');

                var amount_deduction=response[1];
                var percent=response[2];

                var apply_amount=0;

                if (amount_deduction==1)
                {
                    apply_amount= $('#fbr_amount').val();


                }
                else
                {
                    apply_amount=   $('#fbr_amount').val();

                }

                var  cal_amount=(percent / 100)*apply_amount;

                if (response[3]!=0)
                {

                    $('#account_id_1_11').val(response[3]).trigger('change');
                }

                $('#fbr_html').html(response[0]+' ( '+cal_amount+' )');
                $('#tax4').fadeIn(500);
                $('#c_amount_1_6').val(cal_amount);
                $('.perc11').html(response[0]);
                sum(1);
                credit_amount_mines();
            }
        });
    }
</script>