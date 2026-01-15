
<?php $pra='42101';
$register_pra='42154' ?>
<div class="container-fluid">
    <h4>SRB (Sindh Revenue Board)</h4>
    <div class="well">-
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


                <input style="color: red" class="form-control" value="" type="text" readonly name="pra" id="pra" />

            </div>

            <div id="" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 srb">


                <input  class="form-control" value="" type="text"  name="srb_amount" id="srb_amount" />

            </div>

        </div>
        <div>
            &nbsp;&nbsp;&nbsp;
        </div>

        <div style="display: none" class="row srb">



            <div id="" style="display: none;font-size: 12px;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 srb">

                <!-- > Register In  Sales tax <!-->

                <label>Register In SRB</label>

                <label class="radio-inline">
                    <input onclick="applicable_rate_check(1)" class="register_srb"      type="radio" name="registerd_in_srb" id="register_srb_yes" value="1">Yes
                </label>


                <label class="radio-inline">
                    <input onclick="applicable_rate_check(2)" class="register_srb"     type="radio" name="registerd_in_srb" id="register_srb_no" value="2">No
                </label>

                <!-- > Register In  Sales tax End<!-->


                &nbsp;&nbsp;&nbsp;

                <!-- > Active Status <!-->
                <label class="active_status">Advertisment , Renting , Auction , Intencity Transport Or Carriage Of goods</label>
                <label class="radio-inline active_status">
                    <input class="advertisment_srb" class="" ondblclick="unchech(this.id)"    type="radio" name="advertisment_srb" id="advertisment_srb_yes" value="1">Yes
                </label>


                <label class="radio-inline active_status">
                    <input class="advertisment_srb" ondblclick="unchech(this.id)"  type="radio" name="advertisment_srb" id="advertisment_srb_no" value="2">No
                </label>

                &nbsp;&nbsp;&nbsp;
                <label class="active_status">Exclusion</label>
                <label class="radio-inline active_status">
                    <input class="exclusion" class="" ondblclick="unchech(this.id)" onclick="exclusions(1)"   type="radio" name="exclusion" id="exclusion_yes" value="1">Yes
                </label>


                <label class="radio-inline active_status">
                    <input class="exclusion" ondblclick="unchech(this.id)" onclick="exclusions(2)"  type="radio" name="exclusion" id="exclusion_no" value="2">No
                </label>

                <select style="width: 150px;display: none" class="" id="exclision">
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
                <input  class="" type="number" step="0.01" id="applicable_rate" name="applicable_rate"/>
            </div>
            <input style="float: right"  id="btn_cal_srb" type="button" onclick="srb_tax_calculation()" class="btn-primary" value="Calculate"/>

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
           var srb= $('#srb').val();
            $('#pra').val(srb);
            var register_srb=$('#register_srb').val();
            if (register_srb==1)
            {

                $("#register_srb_yes").prop("checked", true);

            }
            else
            {
                $("#register_srb_no").prop("checked", true);

                $('#pra').val('');
            }


        }
        else
        {
            $(".srb").fadeOut(500);
            //      $("#btn_cal_fbr").fadeOut(500);
        }
    }


    function srb_tax_calculation()
    {


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
            data:{registerd:registerd,advertisment:advertisment,exclusion:exclusion,applicable_rate:applicable_rate},

            success:function(data)
            {
                var txt='';
                var response=data.split(',');
                if (response[1]=='1')
                {
                    var rate= $('#applicable_rate').val();
                    txt=rate+'%';
                }
                $('#btn_cal_srb').val('Calculate');
                $('#srb_html').css('display','block');

                $('#srb_html').html(txt+' '+response[0]);
                var applicable=response[1];
                var percent=response[3];


                var apply_amount=0;

                if (applicable==0)
                {
                    apply_amount= $('#srb_amount').val();


                }
                else
                {
                    apply_amount=   $('#srb_amount').val();
                    percent=rate;
                    var friction=1+'.'+percent;
                    friction=parseFloat(friction);
                    apply_amount=(apply_amount / friction).toFixed(0);

                    //    var back_amount=1+'.'
                }
                $('#percent_cal2').html(txt+' '+response[0]+' '+'('+apply_amount+')');
                var  cal_amount=(percent / 100)*apply_amount;

                $('#tax1').fadeIn(500);
                $('#c_amount_1_4').val(cal_amount);

                $('.perc2').html('SRB Withholding '+txt+' '+response[0]);

                $('#dept_hidden_amount4').val(cal_amount);
                $('#dept_amount4').text(cal_amount);

                $('#cost_center_dept_amount4').text(cal_amount);
                $('#cost_center_dept_hidden_amount4').val(cal_amount);

                sum(1);
                credit_amount_mines();




            }
        });
    }



</script>
