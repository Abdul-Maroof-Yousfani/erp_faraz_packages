
<?php use App\Helpers\CommonHelper; ?>
<div  id="sales_tax<?php echo $count1; ?>" @if($purchase_data->sales_tax_amount >0)  @else style="display: none" @endif class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks  removeDemandsRows_dept_1_<?php echo  $count1 ?>">



    <div class="row">


        <?php

        $data2= CommonHelper::sales_tax_allocation_data($purchase_data->id,$type);


        ?>

        <div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">
            <input @if (empty($data2)) checked @endif type="checkbox" name="sales_tax_check_box1" value="1" id="sales_tax_check_box1" class="">Allow Null</label>
        </div>
        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label>(Department For Sales Tax)</label>
        </div>
    </div>



    <input type="hidden" name="sales_tax_dept_hidden_amount<?php echo $count1 ?>" value="{{$purchase_data->sales_tax_amount}}" id="sales_tax_dept_hidden_amount<?php echo $count1 ?>"/>

    <table   id="sales_tax_table<?php echo $count1 ?>" class="table table-bordered">

        <tbody class="sales_tax_addrows<?php echo $count1 ?>" id="">
        <input type="hidden" name="" class="form-control requiredField" id="" value="1" />

        <?php   $total_amount3=0; ?>

        @if (!empty($data2))

            @foreach(CommonHelper::sales_tax_allocation_data($purchase_data->id,$type) as $row3)


        <tr>
            <td class="sales" style="width: 40%">
                <select onchange="open_form(this.id)" style="width: 180px"  name="sales_tax_department<?php echo $count1 ?>[]" id="sales_tax_department_<?php echo $count1 ?>_<?php echo $sales_tax_count ?>"
                        class="form-control select2 dept_form">
                    <option value="0">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                    <option  value="add_new"><b>Add New</b>  </option>
                    @foreach($department as $row)
                        <option  @if($row3->dept_id==$row->id)selected @endif value="{{$row->id}}">{{  ucwords($row->name)}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input value="{{$row3->percent}}" placeholder="Percentage" onkeyup="sales_tax_calculation_dept_item(this.id,'<?php echo $count1 ?>')" type="any" name="sales_tax_percent<?php echo $count1 ?>[]" id="sales_tax_percent_<?php echo $count1 ?>_<?php echo $sales_tax_count ?>" class="form-control" />
            </td>
            <td class="text-right">

                <input value="{{$row3->amount}}" onblur="add_sales_tax('<?php echo $count1 ?>',this.id)" onkeyup="sales_tax_calculation_dept_item_amount(this.id,'<?php echo $count1 ?>')" class="sales_tax_department_amount<?php echo $count1 ?> form-control" style="text-align: right" type="text" name="sales_tax_department_amount<?php echo $count1 ?>[]" id="sales_tax_department_amount_<?php echo $count1 ?>_<?php echo $sales_tax_count ?>" class="form-control" />
            </td><!-->
        </tr>
        <?php $total_amount3+=$row3->amount; ?>

                <?php $sales_tax_count++ ?>
            @endforeach
            <?php  ?>

        @else
            <tr>
                <td style="width: 40%">
                    <select onchange="open_form(this.id)" style="width: 180px"  name="sales_tax_department<?php echo $count1 ?>[]" id="sales_tax_department_<?php echo $count1 ?>_1"
                            class="form-control select2 dept_form">
                        <option value="0">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                        <option  value="add_new"><b>Add New</b>  </option>
                        @foreach($department as $row)
                            <option value="{{$row->id}}">{{  ucwords($row->name)}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input placeholder="Percentage" onkeyup="sales_tax_calculation_dept_item(this.id,'<?php echo $count1 ?>')" type="any" name="sales_tax_percent<?php echo $count1 ?>[]" id="sales_tax_percent_<?php echo $count1 ?>_1" class="form-control" />
                </td>
                <td class="text-right">

                    <input onblur="add_sales_tax('<?php echo $count1 ?>',this.id)" onkeyup="sales_tax_calculation_dept_item_amount(this.id,'<?php echo $count1 ?>')" class="sales_tax_department_amount<?php echo $count1 ?> form-control" style="text-align: right" type="text" name="sales_tax_department_amount<?php echo $count1 ?>[]" id="sales_tax_department_amount_<?php echo $count1 ?>_1" class="form-control" />
                </td><!-->
            </tr>
            @endif
        </tbody>
        <tr>
            <td colspan="1">Total</td>
            <td colspan="2"><input value="{{$total_amount3}}" style="text-align: right;" readonly type="text" class="form-control" name="sales_tax_total_dept<?php echo $count1 ?>" id="sales_tax_total_dept<?php echo $count1 ?>"></td>
        </tr>
    </table>
</div>
<!--
            <div class="sales_tax_dept_part">
                <div  class="table-responsive">
                    <h4 style="text-align: center"><b> Depratment For Sales Tax</h4>
                    <h5 id="sales_tax_dept_item1">---</h5>
                    

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                            <label>   <input type="checkbox" name="sales_tax_check_box1" value="1" id="sales_tax_check_box1" class="">Allow Null</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
                            <h4 style="text-align: right" id="sales_tax_dept_amount1">0</h4>
                        </div>
                    </div>


                    <input type="hidden" name="sales_tax_dept_hidden_amount1" id="sales_tax_dept_hidden_amount1"/>

                    <table  id="sales_tax_table1" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 200px;" class="text-center">Department <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="width: 100px;" class="text-center">Percentage <span class="rflabelsteric"><strong>*</strong></span></th>
                            <th style="width: 200px" class="text-center">Amount <span class="rflabelsteric"><strong>*</strong></span></th>

                        </tr>
                        </thead>
                        <tbody class="sales_tax_addrows1" id="">
                        <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
                        <tr>
                            <td>
                                <select style="width: 100%" name="sales_tax_department1[]" id="sales_tax_department_1_1"
                                        class="form-control select2">
                                    <option value="0">Select</option>
                                    @foreach($department as $row)
        <option value="{{$row->id}}">{{ $row->code .' ---- '. $row->name}}</option>
                                    @endforeach
        </select>
    </td>
    <td>
        <input onkeyup="sales_tax_calculation_dept_item(this.id,1)" type="any" name="sales_tax_percent1[]" id="sales_tax_percent_1_1" class="form-control requiredField" />
                            </td>
                            <td class="text-right">

                                <input onkeyup="sales_tax_calculation_dept_item_amount(this.id,1)" class="sales_tax_department_amount1 form-control" style="text-align: right" type="any" name="sales_tax_department_amount1[]" id="sales_tax_department_amount_1_1" class="form-control requiredField" />
                            </td><!-- >
                        </tr>


                        </tbody>
                        <tr>
                            <td colspan="2">Total</td>
                            <td colspan="1"><input style="text-align: right;" readonly type="number" class="form-control" name="sales_tax_total_dept1" id="sales_tax_total_dept1"></td>
                        </tr>
                    </table>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input  type="button" class="btn btn-xs btn-primary" onclick="sales_tax_AddMore(1)" value="Add More Demand's Rows" />
                            <input type="button" onclick="sales_tax_remove(1)" class="btn btn-xs btn-danger" name="Remove" value="Remove">
                        </div>
                    </div>
                </div>
            </div>
<!-->
<script>

    var lengthh=0;
    $('.sales').each(function()
    {

        lengthh++;
    });

    var dept_allocation=lengthh;

    var sales_tax_allocation=1;

    $(document).ready(function() {
        var max_fields      = 10; //maximum input boxes allowed





    });

    function sales_tax_AddMore(number)
    {

        var wrapper      = $(".sales_tax_addrows"+number);
        dept_allocation++;
        sales_tax_allocation++;
        $(wrapper).append('<tr><td style="40%"><select onchange="open_form(this.id)" style="width: 170px;" name="sales_tax_department'+number+'[]" id="sales_tax_department_'+number+'_'+dept_allocation+'" class="form-control select2 dept_form">' +
                '<option value="0">Select</option><option  value="add_new"><b>Add New</b>  </option>@foreach($department as $row)<option value="<?php echo $row->id ?>"><?php echo   ucwords($row->name)?></option>@endforeach</select></td>' +
                '<td> <input type="any" onkeyup="sales_tax_calculation_dept_item(this.id,'+number+')" name="sales_tax_percent'+number+'[]" id="sales_tax_percent_'+number+'_'+dept_allocation+'" class="form-control" /></td>' +
                '<td class="text-right"><input onblur="add_sales_tax('+number+',this.id)" onkeyup="sales_tax_calculation_dept_item_amount(this.id,'+number+')" class="sales_tax_department_amount'+number+' form-control" style="text-align: right"  type="text" name="sales_tax_department_amount'+number+'[]" id="sales_tax_department_amount_'+number+'_'+dept_allocation+'" class="form-control" /></td></tr>');
        if (ajaxformdept==1)
        {
            var array_count=SelectVal.length;
            for(i=0; i<array_count; i++)
            {

                $("#sales_tax_department_"+number+"_"+dept_allocation).append($('<option>', {
                    value: SelectVal[i],
                    text: Selecttxt[i]
                }));
            }
        }
        $('#sales_tax_department_'+number+'_'+dept_allocation).select2();
        $('#sales_tax_department_'+number+'_'+dept_allocation).focus();
        $('#sales_tax_department_amount_'+number+'_'+dept_allocation+'').number(true,2);
    }
    function sales_tax_remove(number)
    {
        var table = document.getElementById('sales_tax_table'+number);
        var rowCount = table.rows.length;
        if (rowCount==3)
        {
            return false;
        }
        else
        {
            table.deleteRow(rowCount - 2);
            total_dept(number);
            var actual_amount = $('#sales_tax_dept_hidden_amount' + number).val();
            var total_dept_amountt = $('#sales_tax_total_dept' + number).val();
            sales_tax_colour_change(number, total_dept_amountt, actual_amount);
            sales_tax_heading_change(number);
            sales_tax_total_dept(number);
        }
    }

    function sales_tax_calculation_dept_item(id,number1)
    {

        var array=id.split('_');
        var number2=array[4];

        var actual_amount= $('#sales_tax_dept_hidden_amount'+number1).val();
        var percentage=$('#'+id).val();
        if (isNaN(percentage)==true)
        {
            percentage=0;
        }
        else
        {
            if (percentage > 100)
            {

                alert('Percentage Cannot Exceed 100');
                $('#'+id).val(0);
                $('#sales_tax_department_amount_' + number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = percentage * actual_amount;
                var s_tax_amount =parseFloat(x / 100).toFixed(2);

                $('#sales_tax_department_amount_' + number1+'_'+number2).val(s_tax_amount);



                var total_dept_amountt= sales_tax_total_dept(number1);
                //  alert(total_dept_amountt);

                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {
                    $('#'+id).val(0);
                    $('#sales_tax_department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    sales_tax_total_dept(number1);
                    sales_tax_heading_change(number1);
                }
            }
            sales_tax_colour_change(number1,total_dept_amountt,actual_amount);
            sales_tax_heading_change(number1);


        }
    }


    function sales_tax_total_dept(number1)
    {
        var total_dept_amount=0;
        $( ".sales_tax_department_amount"+number1 ).each(function( index ) {
            total_dept_amount+=+$(this).val();


        });
        total_dept_amount=parseFloat(total_dept_amount).toFixed(2);
        $('#sales_tax_total_dept'+number1).val(total_dept_amount);
        return total_dept_amount;

    }

    function sales_tax_colour_change(number1,total_dept_amountt,actual_amount)
    {

        var amount=  value_check_deiffrence(total_dept_amountt,actual_amount);

        if (amount==1)
        {

            $("#sales_tax_total_dept"+number1).delay(3000).css("background-color","yellow");
        }
        else
        {
            $("#sales_tax_total_dept"+number1).delay(3000).css("background-color","red");
        }
    }


    function sales_tax_calculation_dept_item_amount(id,number1)
    {
        var array=id.split('_');
        var number2=array[5];

        var actual_amount= parseFloat($('#sales_tax_dept_hidden_amount'+number1).val());
        var tax_amount=parseFloat($('#'+id).val());

        if (isNaN(tax_amount)==true)
        {
            tax_amount=0;
            $('#sales_tax_percent_'+number1+'_'+number2).val(0);
            sales_tax_total_dept(number1);
            sales_tax_heading_change(number1);
            sales_tax_colour_change(number1,total_dept_amountt,actual_amount);
        }
        else
        {


            if (tax_amount > actual_amount)
            {

                alert('Amount Cannot Exceed'+' '+actual_amount);

                $('#'+id).val(0);
                $('#sales_tax_percent_'+number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = tax_amount / actual_amount;
                percentage = parseFloat(x * 100).toFixed(2);
                $('#sales_tax_percent_' + number1+'_'+number2).val(percentage);
                var total_dept_amountt= sales_tax_total_dept(number1);
                //  alert(total_dept_amountt);



                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {
                    $('#'+id).val(0);
                    $('#sales_tax_department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    $('#sales_tax_percent_'+number1+'_'+number2).val(0);
                    sales_tax_total_dept(number1);
                }
            }
            sales_tax_colour_change(number1,total_dept_amountt,actual_amount);
            sales_tax_heading_change(number1);

        }
    }

    function sales_tax_heading_change(number)
    {
        var amount= $('#sales_tax_dept_hidden_amount'+number).val();
        amount=parseFloat(amount);
        var dept_total_amount= $('#sales_tax_total_dept'+number).val();
        var total=parseFloat(amount-dept_total_amount);

        $('#sales_tax_dept_amount'+number).text(total);


    }

    function sales_tax_amount_display(id)
    {

        var amount= $('#sales_tax_amount_1_'+id+'').val();
        if (amount>0)
        {
            $('#sales_tax'+id).css("display","block");
        }
        else
        {
            $('#sales_tax'+id).css("display","none");
        }
    }




    function add_sales_tax(number,id)
    {
        var more=1;
        var amount= $('#sales_tax_dept_hidden_amount'+number).val();
        amount=parseFloat(amount);
        var department_amount= $('#sales_tax_total_dept'+number).val();
        department_amount=parseFloat(department_amount);


        var select_cureent_amount=$('#'+id).val();



        if (select_cureent_amount >0 && select_cureent_amount!='')
        {
            var v=value_check_deiffrence(department_amount,amount);
            if (v==0)
            {
                $( ".sales_tax_department_amount"+number).each(function( index ) {
                    var vall=$(this).val();
                    if (vall==0 || vall=='')
                    {
                        more=0;
                    }



                });

                if (more==1)
                {sales_tax_AddMore(number);}

            }
        }


    }
    function value_check_deiffrence(total_dept_amountt,actual_amount)
    {
        total_dept_amountt=parseFloat(total_dept_amountt).toFixed(0);
        actual_amount=  parseFloat(actual_amount).toFixed(0);


        //  if (v <0.1 || v==0)
        if (actual_amount==total_dept_amountt)

        {
            v=1;
        }
        else
        {
            v=0;
        }

        return v;
    }

</script>