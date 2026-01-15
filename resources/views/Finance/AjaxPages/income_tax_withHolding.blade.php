<?php
use App\Helpers\CommonHelper;
use App\Models\Account;

$Accounts=new Account();
$Accounts = $Accounts->SetConnection('mysql2');
$Accounts = $Accounts->where('status',1)->where('parent_code','1-2-4-1')->get();
?>
<div class="container-fluid IncomeTaxWithholding well">


    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <p>Income Tax Withholding</p>
        <label class="radio-inline">
            <input onclick="applicable(1)" type="radio" name="optradio" id="business" value="1"> Applicable
        </label>
        <label class="radio-inline">
            <input checked onclick="applicable(2)" type="radio" name="optradio" id="company" value="2">Non  Applicable
        </label>

        <label class="radio-inline">
            <input onclick="applicable(3)" type="radio" name="optradio" id="btl" value="3">BTL
        </label>
        <label class="radio-inline">
            <input onclick="applicable(4)" type="radio" name="optradio" id="exmpt" value="4">Exempt
        </label>
    </div>


    <div  style="display: none"  id="supplier_div"  class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        <label for="">Supplier</label>
        <select onchange="supplier_income_tax_data()" style="width: 100%"  class="form-control" name="supplier_id" id="supplier_id">
            <option value="0">Select Supplier</option>
            @foreach(CommonHelper::get_all_supplier() as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
    </div>


    <div  style="display: none;" class="col-lg-3 col-md-3 col-sm-3 col-xs-3" id="DisplayCode">
        <label for="">Exempt Code</label>
        <input type="text" name="VendorCode" id="VendorCode" class="form-control" placeholder="Exempt Code">
    </div>
    <span id="response" class="text-center"></span>

</div>


<script>
    function applicable(number)
    {
        if (number==1)
        {
            $('#supplier_div').fadeIn(500);
            $("#supplier_id").attr("onchange", "supplier_income_tax_data()");
            $('#DisplayCode').css('display','none');
            $("#supplier_id").val("0");
        }
        else if(number == 2)
        {
            $('#DisplayCode').css('display','none');
            $('#supplier_div').fadeOut(500);
            $('.payment_mod_div').html('');
        }
        else if(number == 4)
        {
            $('#DisplayCode').css('display','block');
            $('#supplier_div').fadeIn(500);
            $("#supplier_id").attr("onchange", "");
            $('.payment_mod_div').html('');
        }
        else{
            $('#DisplayCode').css('display','none');
            $('#supplier_div').fadeOut(500);
            $('.payment_mod_div').html('');
        }
    }


    function supplier_income_tax_data()
    {
        var supplier_id=$('#supplier_id').val();
        var loader_img = '<img src="/assets/img/103.gif" alt="Loading" />';
        if(supplier_id != "0") {
            $("#response").html(loader_img);

            $.ajax({
                url: '<?php echo url('/')?>/fdc/income_tax_calculation',
                type: "GET",
                data: {supplier_id: supplier_id},
                success: function (data) {


                    $('#response').html(data);
                    $('.nature1').focus();
                    $('.nature1').number(true, 2);
                    $('#income1').number(true, 2);
                    $('#payment_mod1').select2();
                    $('#tax_payment_section1').select2();
                    var srb = $('input[name=pra_sales_tax]:checked').val();
                    if (srb == 1) {
                        srb_sales_tax_function(1);
                    }

                }
            });
        }

    }


    function calculation_text(number)
    {

        var filer=0;
        var nature= $('#payment_mod'+number).val();
        var nature_name=$("#payment_mod"+number+" :selected").text();

        var business=$('#business_type').val();
        if($('#filer3').prop("checked") == true)
        {
            filer=2;
        }
        if($('#filer4').prop("checked") == true)
        {
            filer=1;
        }
        if (filer==0)
        {
            alert('Required All Information');
            return false;
        }
        var nature= $('#payment_mod'+number).val();
        if (nature == 0)
        {
            alert('Select Tax Nature');
            return false;
        }
        $('#btn_cal'+number).val('waiting......');
        $('#btn_cal'+number).attr("disabled", true);
        var nature= $('#payment_mod'+number).val();
        var acc_id=$('#tax_payment_section'+number).val();
        var income=$('#income'+number).val();
        var supplier_id=$('#supplier_id').val();
        x++;
        var pv_data_cocunter=x;


        $.ajax({
            url: '<?php echo url('/')?>/fdc/tax_calculation',
            type: "GET",
            data:{nature:nature,filer:filer,business:business,acc_id:acc_id,income:income,number:number,supplier_id:supplier_id,pv_data_cocunter:pv_data_cocunter},

            success:function(data)
            {

              

                $('#btn_cal'+number).val('Calculate');
                $('#btn_cal'+number).attr("disabled", false);


                if($('tr').hasClass('taxtation_row'+number))
                {

                    $('#taxtation_row'+number).replaceWith(data);
                }
                else

                {
                    $('#addMorePvsDetailRows_1').append(data);
                }
                var percent_text= $('.text'+number).text();

                $('#percent_cal'+number).html(percent_text);
                mainDisable('d_amount_1_'+x+'','c_amount_1_'+x+'')
                sum(1);

            }
        });

    }



    var add_more_count=1;
    function add_more()
    {

        add_more_count++;
        $('.append').append('<div class="row taxtation_row'+add_more_count+'"><div id="payment_mod_div" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">' +
                '<select  style="width: 100%" onchange="" id="payment_mod'+add_more_count+'"  name="nature'+add_more_count+'" class="form-control select2">'+
                '<option  value="0" style="color: red">SELECT</option><option value="1">ALL GOODS</option><option value="2">IN CASE OF RICE,COTTON,SEED,EDIBLE OIL</option>'+
                '<option value="3">DISTRIBUTORS OF FAST MOVING CONSUMER GOODS</option>'+
                '<option value="4">SERVICES</option>'+
                '<option value="5">TRANSPORT SERVICES</option>'+
                '<option value="6">ELECTRONIC AND PRINT MEDIA FOR ADVERTISING</option>'+
                '<option value="7">CONTRACTS</option>'+
                '<option value="8">SPORT PERSON</option>'+
                '<option value="9">Services of Stitching , Dyeing , Printing , Embroidery etc</option>'+
                '</select></div>'+
                '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">'+
                '<select name="tax_payment_section'+add_more_count+'" id="tax_payment_section'+add_more_count+'" class="form-control select2">'+
                '<option value="">Select Tax Payment Section</option>'+
                <?php foreach($Accounts as $row):?>
                '<option value="<?php echo $row->id?>"><?php echo $row->name?></option>'+
                <?php endforeach;?>
                '</select>'+
                '</div>'+
                '<div id="" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">'+
                '<input type="text" class="form-control" value="" name="income'+add_more_count+'" id="income'+add_more_count+'">'+
                '</div>'+
                '<div style=""  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div"><p style="color: red" id="percent_cal'+add_more_count+'" class=""> </p></div>'+
                '<div style="" id="submit" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">'+
                '<input  style="float:left" id="btn_cal'+add_more_count+'" type="button" onclick="calculation_text('+add_more_count+')" class="btn-primary" value="Calculate"/>'+
                '<input type="hidden" name="income_tax_id'+add_more_count+'" id="income_tax_id'+add_more_count+'"></div>'

        ); //add input box
        $('#payment_mod'+add_more_count).select2();
        $('#tax_payment_section'+add_more_count).select2();
        $('#income'+add_more_count).number(true,2);

    }
    function remove(cls)
    {
        $('.'+cls).remove();
    }
</script>
