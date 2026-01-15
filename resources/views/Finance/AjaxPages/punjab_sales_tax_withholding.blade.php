<?php
$register_pra='';
$pra='';
$business_type='';
use App\Helpers\CommonHelper;
?>
<div class="container-fluid PunjabSalesTaxWithholding well">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Punjab Sales Tax Withholding
    </div>
    <div class="">-
        <div class="row">

            <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 frb_tax">

                <label class="radio-inline">
                    <input onclick="pra_sales_tax_function(1)" type="radio" name="pra" id="pra_sales_tax" value="1"> Applicable
                </label>
                <label class="radio-inline">
                    <input checked onclick="pra_sales_tax_function('2')" type="radio" name="pra" id="pra_sales_tax" value="2">Non  Applicable
                </label>

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pra">


                <input style="color: red" class="form-control" value="{{$pra}}" type="text" readonly name="pra" id="pra" />

            </div>

            <div style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pra">
                <input type="text" class="form-control" placeholder="Applicable Amount" name="pra_amount" id="pra_amount" value="">
            </div>

            <div style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pra">
                <input @if($register_pra==1)style="display: none" @endif   class="" type="number" step="0.01" id="applicable_rate_pra" name="pra_percent"/>
            </div>

            <div style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pra">
                <select name="pra_acc_id" id="pra_acc_id" class="form-control">

                    <option value="">SELECT ACCOUNT</option>
                    <?php $Accounts = CommonHelper::get_accounts_by_parent_code('1-2-4-4');
                    foreach($Accounts as $Fil):?>
                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                    <?php endforeach;?>
                </select>
            </div>

        </div>

        <div style="display: none" class="row pra">


            <div id="" style="display: none" class="col-lg-5 col-md-5 col-sm-5 col-xs-12 pra">

                <!-- > Register In  Sales tax <!-->

                <label>Register In Sales Tax</label>

                <label class="radio-inline">
                    <input class="register_pra"  onclick="active_status_check_pra(1)"  @if($register_pra==1)checked @endif   type="radio" name="registerd_in_pra" id="registerd_pra_yes" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input class="register_pra" onclick="active_status_check_pra(2)" @if($register_pra!=1)checked @endif    type="radio" name="registerd_in_pra" id="registerd_pra_no" value="2">No
                </label>

                <!-- > Register In  Sales tax End<!-->


                &nbsp;&nbsp;&nbsp;
                <label class="">Advertisment Services</label>
                <label class="radio-inline">
                    <input class="advertisment_pra" onclick="company_active_status_hide(1,'company_active_status_hide','company_active_status_hide')"  ondblclick="unchech(this.id)"   type="radio" name="advertisment_pra" id="advertisment_service_pra_yes" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input class="advertisment_pra"  ondblclick="unchech(this.id)" onclick="company_active_status_hide(2,'company_active_status_hide','company_active_status_hide')"   type="radio" name="advertisment_pra" id="advertisment_service_pra_no" value="2">No
                </label>

                <!-- > Active Status <!-->








            </div>



            <div  class="col-lg-5   col-md-5 col-sm-5 col-xs-12">


                <label @if($register_pra!=1)style="display: none" @endif class="company_active_status_hide">Company</label>
                <label @if($register_pra!=1)style="display: none" @endif class="radio-inline company_active_status_hide">
                    <input @if($business_type==2   && $register_pra!=1)checked @endif class="company_pra" onclick="active_status_check_pra_for_company(1)"  ondblclick="unchech(this.id)"   type="radio" name="company_pra" id="company_pra_yes" value="1">Yes
                </label>


                <label @if($register_pra!=1)style="display: none" @endif class="radio-inline company_active_status_hide">
                    <input @if($business_type==1 || $business_type==3 && $register_pra!=1)checked @endif onclick="active_status_check_pra_for_company(2)"  class="company_pra"  ondblclick="unchech(this.id)"    type="radio" name="company_pra" id="company_pra_no" value="2">No
                </label>

                &nbsp;&nbsp;

                <label  @if($register_pra!=1)style="display: none" @endif class="company_active_status_hide active_pra">Active In Punjab Sales Tax</label>


                <label  @if($register_pra!=1)style="display: none" @endif class="radio-inline company_active_status_hide active_pra">
                    <input  class="active_pra company_active_status_hide"  ondblclick="unchech(this.id)"   type="radio" name="active_pra" id="active_pra_yes" value="1">Yes
                </label>


                <label  @if($register_pra!=1)style="display: none" @endif class="radio-inline company_active_status_hide active_pra">
                    <input class="active_pra company_active_status_hide" ondblclick="unchech(this.id)"    type="radio" name="active_pra" id="active_pra_no" value="2">No
                </label>
            </div>



            <div  class="col-lg-1   col-md-1 col-sm-1 col-xs-12">
                <input   id="btn_cal_pra" type="button" onclick="pra_tax_calculation()" class="btn-primary" value="Calculate"/>
            </div>
        </div>



    </div>
</div>
<blockquote style="display: none;color: red" id="pra_html" cite="">

</blockquote>

<script type="text/javascript">
    function pra_sales_tax_function(type)

    {

        if (type==1)
        {

            $(".pra").fadeIn(500);


        }
        else
        {
            $(".pra").fadeOut(500);
            //      $("#btn_cal_fbr").fadeOut(500);
        }
    }


    function pra_tax_calculation()
    {

        var applicable_rate=$('#applicable_rate_pra').val();
        var pra_amount=$('#pra_amount').val();
        var acc_id=$('#pra_acc_id').val();
        x++;
        var pv_data_counter=x;

        var exempt=0;
        var advertisment=0;
        var company=0;
        var active_pra=0;
        var registerd=  document.querySelector('input[name="registerd_in_pra"]:checked').value;



        if ($("#registerd_pra_no").is(':checked'))
        {

            var applicable_rate_pra= $('#applicable_rate_pra').val();
            if (applicable_rate_pra =='' || applicable_rate_pra ==0)
            {
                alert('Required Applicable %');
                return false;
            }
        }


        if ($(".advertisment_pra").is(':checked'))
        {
            advertisment=  document.querySelector('input[name="advertisment_pra"]:checked').value;
        }
        else
        {
            advertisment= 0;
        }


        if ($(".company_pra").is(':checked'))
        {
            company=  document.querySelector('input[name="company_pra"]:checked').value;
        }
        else
        {
            company= 0;
        }


        if ($(".active_pra").is(':checked'))
        {
            active_pra=  document.querySelector('input[name="active_pra"]:checked').value;
        }
        else
        {
            active_pra= 0;
        }
//alert(registerd); alert(advertisment); alert(company); alert(active_pra);
        $('#btn_cal_pra').val('waiting......');
        $.ajax({
            url: '<?php echo url('/')?>/fdc/pra_tax_calculation',
            type: "GET",
            data:{registerd:registerd,company:company,active_pra:active_pra,advertisment:advertisment,pra_amount:pra_amount,acc_id:acc_id,pv_data_counter:pv_data_counter,applicable_rate:applicable_rate},

            success:function(data)
            {

                if (data=='0')
                {
                    $('#pra_html').html('something went wrong');
                    remove('pra_html');
                    $('#btn_cal_pra').attr("disabled", false);
                    $('#btn_cal_pra').val('Calculate');
                    return false;
                }
                if($('tr').hasClass('pra_row'))
                {

                    $('#pra_row').replaceWith(data);
                }
                else

                {

                    $('#addMorePvsDetailRows_1').append(data);
                }

                var txt_deduction=$('.pra_txt').text();
                alert(txt_deduction);
                $('#pra_html').css('display','block');
                $('#pra_html').html(txt_deduction);
                $('#btn_cal_pra').attr("disabled", false);
                $('#btn_cal_pra').val('Calculate');
                sum(1);
            }
        });
    }

    function company_active_status_hide(number,type,cls)

    {


        if (number==1)
        {

            $("."+type).fadeOut(500);
            $('.company_pra').prop("checked", false);
            $('.active_pra').prop("checked", false);


        }
        else {

            //  $(".active_pra").fadeOut(500);
            var registerd_in_pra = $("input[name='registerd_in_pra']:checked").val();

            if (registerd_in_pra==1)
            {
                $("." + type).fadeIn(500);
                var company_pra = $("input[name='company_pra']:checked").val();
                if (company_pra == 1) {
                    if (company_pra == 1) {

                        $(".active_pra").fadeIn(500);
                        $('.active_pra ').prop("checked", false);
                    }
                    else {

                        $(".active_pra").fadeOut(500);
                        $('.active_pra ').prop("checked", false);
                    }

                    //      $("#btn_cal_fbr").fadeOut(500);
                }
            }



        }


    }

    function active_status_check_pra(number)

    {


        if (number==1)
        {

            $(".active_pra").fadeIn(500);
            $(".company_active_status_hide").fadeIn(500);

            $("#applicable_rate_pra").fadeOut(500);

        }
        else
        {

            $(".active_pra").fadeOut(500);
            $(".company_active_status_hide").fadeOut(500);
            $("#applicable_rate_pra").fadeIn(500);
            $('.company_pra ').prop("checked", false);
            $('.active_pra ').prop("checked", false);
            //      $("#btn_cal_fbr").fadeOut(500);
        }
        var radioValue = $("input[name='company_pra']:checked").val();
        var registerd_in_pra = $("input[name='registerd_in_pra']:checked").val();
        if (registerd_in_pra==1)
        {
            if (radioValue==1)
            {

                $(".active_pra").fadeIn(500);
                $('.active_pra ').prop("checked", false);
            }
            else
            {

                $(".active_pra").fadeOut(500);
                $('.active_pra ').prop("checked", false);
            }
        }

    }

    function active_status_check_pra_for_company(number)

    {


        if (number==1)
        {

            $(".active_pra").fadeIn(500);


            //   $("#applicable_rate_pra").fadeOut(500);

        }
        else
        {

            $(".active_pra").fadeOut(500);
            $('.active_pra ').prop("checked", false);
            // $("#applicable_rate_pra").fadeIn(500);
            //      $("#btn_cal_fbr").fadeOut(500);
        }
    }
</script>