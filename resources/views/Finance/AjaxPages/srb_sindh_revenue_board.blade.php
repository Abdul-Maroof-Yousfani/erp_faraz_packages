<?php
$register_pra='';
$pra='';
use App\Helpers\CommonHelper;
?>
<div class="container-fluid SrbSindhRevenueBoard well">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        Sbr (Sindh Revenue Board)
    </div>

    <div class="">-
        <div class="row">

            <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 frb_tax">

                <label class="radio-inline">
                    <input onclick="srb_sales_tax_function(1)" type="radio" name="pra_sales_tax" id="pra_sales_tax" value="1"> Applicable
                </label>
                <label class="radio-inline">
                    <input checked onclick="srb_sales_tax_function('2')" type="radio" name="pra_sales_tax" id="pra_sales_tax" value="2">Non  Applicable
                </label>

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 srb">


                <input style="color: red" class="form-control" value="{{$pra}}" type="text" readonly name="pra" id="pra" />

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 srb">


                <input type="text" class="form-control"  id="srb_amount" name="srb_amount" value="">

            </div>

            <div id="" class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <p style="display: none;color: red" id="srb_html" cite="">

                </p>
            </div>

        </div>
        <div>
            &nbsp;&nbsp;&nbsp;
        </div>

        <div style="display: none" class="row srb srb_row">



            <div id="" style="display: none;font-size: 12px;" class="col-lg-9 col-md-9 col-sm-9 col-xs-12 srb">

                <!-- > Register In  Sales tax <!-->

                <label>Register In SRB</label>

                <label class="radio-inline">
                    <input onclick="applicable_rate_check(1)" class="register_srb"   @if($register_pra==1)checked @endif   type="radio" name="registerd_in_srb" id="register_srb_yes" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input onclick="applicable_rate_check(2)" class="register_srb"  @if($register_pra!=1)checked @endif    type="radio" name="registerd_in_srb" id="register_srb_no" value="2">No
                </label>

                <!-- > Register In  Sales tax End<!-->


                &nbsp;&nbsp;&nbsp;

                <!-- > Active Status <!-->
                <label class="active_status_pra">Advertisment , Renting , Auction , Intencity Transport Or Carriage Of goods</label>
                <label class="radio-inline active_status_pra">
                    <input class="advertisment_srb" class="" ondblclick="unchech(this.id)"    type="radio" name="advertisment_srb" id="advertisment_srb_yes" value="1">Yes
                </label>


                <label class="radio-inline active_status_pra">
                    <input class="advertisment_srb" ondblclick="unchech(this.id)"  type="radio" name="advertisment_srb" id="advertisment_srb_no" value="2">No
                </label>

                &nbsp;&nbsp;&nbsp;
                <label class="active_status_pra">Exclusion</label>
                <label class="radio-inline active_status_pra">
                    <input class="exclusion" class="" ondblclick="unchech(this.id)" onclick="exclusions(1)"   type="radio" name="exclusion" id="exclusion_yes" value="1">Yes
                </label>


                <label class="radio-inline active_status_pra">
                    <input class="exclusion" ondblclick="unchech(this.id)" onclick="exclusions(2)"  type="radio" name="exclusion" id="exclusion_no" value="2">No
                </label>

                <select style="width: 150px;display: none" class="" name="exclusion_type" id="exclision">
                    <option value="">Select</option>
                    <option value="1">Telecommunication</option>
                    <option value="2">Banking Company</option>
                    <option value="3">Financial Institute</option>
                    <option value="4">Insurance Company</option>
                    <option value="5">Port operators</option>
                    <option value="6">Terminal operators</option>
                    <option value="7">Airport operators</option>
                    <option value="8"> Airport Grounf Services</option>
                </select>

                <input @if($pra!='')style="display: none" @endif  class="" type="number" step="0.01" id="applicable_rate" name="srb_percent"/>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <select name="srb_acc_id" id="srb_acc_id" class="form-control">

                    <option value="">SELECT ACCOUNT</option>
                    <?php $Accounts = CommonHelper::get_accounts_by_parent_code('1-2-4-4');
                    foreach($Accounts as $Fil):?>
                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input style="float: right"  id="btn_cal_srb" type="button" onclick="srb_tax_calculation()" class="btn-primary" value="Calculate"/>
            </div>

        </div>




    </div>
</div>
<script type="text/javascript">

    function exclusions(type)
    {

        if (type==1)
        {

            $('#exclision').fadeIn(500);
        }
        else
        {
            $('#exclision').fadeOut(500);
        }
    }


    function applicable_rate_check(type)
    {

        if (type==1)
        {


            $('#applicable_rate').fadeOut(500);
        }
        else
        {

            $('#applicable_rate').fadeIn(500);
        }
    }

    function srb_sales_tax_function(type)

    {

        if (type==1)
        {

            $(".srb").fadeIn(500);


        }
        else
        {
            $(".srb").fadeOut(500);
            //      $("#btn_cal_fbr").fadeOut(500);
        }
    }


    function srb_tax_calculation()
    {

        var srb_amount=$('#srb_amount').val();
        var acc_id=$('#srb_acc_id').val();
        x++;
        var pv_data_counter=x;

        var advertisment=0;
        var exclusion=0;
        var active_srb=0;

        var applicable_rate= $('#applicable_rate').val();
        if (applicable_rate =='' || applicable_rate ==0)
        {
            applicable_rate=0;
        }
        else
        {
            applicable_rate=1;
        }
        var registerd=  document.querySelector('input[name="registerd_in_srb"]:checked').value;
        applicable_rate=parseFloat(applicable_rate);
        if (registerd==2)
        {
            if (applicable_rate=='' || applicable_rate==0 || applicable_rate>100)
            {
                alert('Required Valid Applicable Percentage');
                return false;
            }
        }
        else
        {
            $('#applicable_rate').val(0);
            applicable_rate=0;
        }

        if ($(".advertisment_srb").is(':checked'))
        {

            advertisment=  document.querySelector('input[name="advertisment_srb"]:checked').value;


        }
        else
        {

            advertisment= 0;
        }
        var exclusion_val=0;
        if ($(".exclusion").is(':checked'))
        {

            exclusion=  document.querySelector('input[name="exclusion"]:checked').value;


            if (exclusion==1)
            {
                var exclision=  $('#exclision').val();
                if (exclision=='')
                {
                    alert('Required Exclusion Nature');
                    return false;
                }
                else
                {
                    exclusion_val =exclision;
                }
            }

        }
        else
        {
            exclusion= 0;

        }




        $('#btn_cal_srb').val('waiting......');
        $.ajax({
            url: '<?php echo url('/')?>/fdc/srb_tax_calculation',
            type: "GET",
            data:{registerd:registerd,advertisment:advertisment,exclusion:exclusion,applicable_rate:applicable_rate,
                srb_amount:srb_amount,acc_id:acc_id,applicable_rate:applicable_rate,pv_data_counter:pv_data_counter,exclusion_val:exclusion_val},

            success:function(data)
            {
                if (data=='0')
                {
                    $('#fbr_html').html('something went wrong');
                    remove('fbr_row');
                    $('#btn_cal_fbr').attr("disabled", false);
                    $('#btn_cal_fbr').val('Calculate');
                    $('#srb_row').remove();
                    return false;
                }
                if($('tr').hasClass('srb_row'))
                {

                    $('#srb_row').replaceWith(data);
                }
                else

                {

                    $('#addMorePvsDetailRows_1').append(data);
                }

                var txt_deduction=$('.srb_txt').text();
                $('#srb_html').css('display','block');
                $('#srb_html').html(txt_deduction);
                $('#btn_cal_srb').attr("disabled", false);
                $('#btn_cal_srb').val('Calculate');
                mainDisable('d_amount_1_'+x+'','c_amount_1_'+x+'');
            }
        });
    }



</script>