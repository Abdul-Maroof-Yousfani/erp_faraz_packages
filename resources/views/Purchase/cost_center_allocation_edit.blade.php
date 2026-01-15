<?php
use App\Helpers\CommonHelper;
?>


<div id="cost_center1"  style="" class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_1_<?php echo  $count1 ?>">
    <?php
    $data1= CommonHelper::cost_center_allocation_data($purchase_data->id,$type);

    ?>
    <div class="row">
        <div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-6 banks">
            <input @if (empty($data1)) checked @endif  type="checkbox" name="cost_center_check_box<?php echo $count1 ?>" value="1" id="cost_center_check_box<?php echo $count1 ?>" class="">Allow Null  </label>
        </div>


        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label><b>(Cost Center)</b></label>
        </div>


    </div>



    <input type="hidden" name="cost_center_dept_hidden_amount<?php echo  $count1 ?>" id="cost_center_dept_hidden_amount<?php echo  $count1 ?>" value="{{$purchase_data->amount}}"/>

    <table  id="cost_center_table1" class="table table-bordered">

        <tbody class="cost_center_addrows<?php echo $count1 ?>" id="">
        <input type="hidden" name="" class="form-control requiredField" id="demandDataSection_1" value="1" />
        <?php $total_amount1=0; ?>
        <?php $cost_center_count=1; ?>
        @if (!empty($data1))
            @foreach(CommonHelper::cost_center_allocation_data($purchase_data->id,$type) as $row2)

                @if($row2->dept_id!='0')
            <tr>
            <td style="width: 40%">
                <select style="width: 180px;" onchange="open_form_cost_center(this.id)"  name="cost_center_department<?php echo  $count1 ?>[]" id="cost_center_department_<?php echo  $count1 ?>_<?php echo  $cost_center_count ?>"
                        class="form-control select2 CostCenter">
                    <option value="0">Select Cost Center&nbsp;&nbsp;&nbsp;</option>
                    <option    value="add_new"><b>Add New</b>  </option>
                    @foreach(CommonHelper::get_all_cost_center()  as $row)
                        <option  @if($row2->dept_id==$row->id)selected @endif  value="{{$row->id}}">{{ ucwords($row->name)}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input value="{{$row2->percent}}" onkeyup="cost_center_calculation_dept_item(this.id,'<?php echo $count1 ?>')" placeholder="Percentage" type="any" name="cost_center_percent<?php echo  $count1 ?>[]" id="cost_center_percent_<?php echo  $count1 ?>_<?php echo  $cost_center_count ?>" class="form-control" />
            </td>
            <td class="text-right">

                <input value="{{$row2->amount}}" onblur="add_cost_center('<?php echo $count1 ?>',this.id)"  onkeyup="cost_center_calculation_dept_item_amount(this.id,'<?php echo $count1 ?>')" class="cost_center_department_amount<?php echo  $count1 ?> form-control" style="text-align: right" type="text" name="cost_center_department_amount<?php echo  $count1 ?>[]" id="cost_center_department_amount_<?php echo  $count1 ?>_<?php echo  $cost_center_count ?>" class="form-control" />
            </td><!-->
        </tr>
            <?php $total_amount1+=$row2->amount; ?>
                @endif
                <?php $cost_center_count++; ?>
            @endforeach
@else

            <tr>
                <td style="width: 40%">
                    <select style="width: 180px;" onchange="open_form_cost_center(this.id)"  name="cost_center_department<?php echo  $count1 ?>[]" id="cost_center_department_<?php echo $count1 ?>_1"
                            class="form-control select2 CostCenter">
                        <option value="0">Select Cost Center&nbsp;&nbsp;&nbsp;</option>
                        <option   value="add_new"><b>Add New</b>  </option>
                        @foreach(CommonHelper::get_all_cost_center()  as $row)
                            <option value="{{$row->id}}">{{ ucwords($row->name)}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input onkeyup="cost_center_calculation_dept_item(this.id,'<?php echo $count1 ?>')" placeholder="Percentage" type="any" name="cost_center_percent<?php echo  $count1 ?>[]" id="cost_center_percent_<?php echo $count1 ?>_1" class="form-control" />
                </td>
                <td class="text-right">

                    <input onblur="add_cost_center('<?php echo $count1 ?>',this.id)"  onkeyup="cost_center_calculation_dept_item_amount(this.id,'<?php echo $count1 ?>')" class="cost_center_department_amount<?php echo $count1 ?> form-control" style="text-align: right" type="text" name="cost_center_department_amount<?php echo  $count1 ?>[]" id="cost_center_department_amount_<?php echo $count1 ?>_1" class="form-control" />
                </td><!-->
            </tr>

        @endif
        </tbody>
        <tr>
            <td colspan="1">Total</td>
            <td colspan="2"><input style="text-align: right;" value="{{$total_amount1}}" readonly type="text" class="form-control" name="cost_center_total_dept<?php echo  $count1 ?>" id="cost_center_total_dept<?php echo  $count1 ?>"></td>
        </tr>
    </table>
</div>
<!--
<div class="cost_center">
    <div  class="table-responsive">
        <h4 style="text-align: center"><b> Cost Center</h4>
        <h5 id="cost_center_dept_item1">---</h5>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                <label>   <input type="checkbox" name="cost_center_check_box1" value="1" id="cost_center_check_box1" class="">Allow Null</label>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
                <h4 style="text-align: right" id="cost_center_dept_amount1">0</h4>
            </div>
        </div>
        <input type="hidden" name="cost_center_dept_hidden_amount1" id="cost_center_dept_hidden_amount1"/>

        <table  id="cost_center_table1" class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 200px;" class="text-center">Cost Center <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 100px;" class="text-center">Percentage <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 200px" class="text-center">Amount <span class="rflabelsteric"><strong>*</strong></span></th>

            </tr>
            </thead>
            <tbody class="cost_center_addrows1" id="">
            <input type="hidden" name="" class="form-control requiredField" id="demandDataSection_1" value="1" />
            <tr>
                <td>
                    <select style="width: 100%" name="cost_center_department1[]" id="cost_center_department_1_1"
                            class="form-control select2">
                        <option value="0">Select</option>
                        @foreach(CommonHelper::get_all_cost_center()  as $row)
        <option value="{{$row->id}}">{{ $row->code .' ---- '. $row->name}}</option>
                        @endforeach
        </select>
    </td>
    <td>
        <input onkeyup="cost_center_calculation_dept_item(this.id,1)" type="any" name="cost_center_percent1[]" id="cost_center_percent_1_1" class="form-control requiredField" />
                </td>
                <td class="text-right">

                    <input onkeyup="cost_center_calculation_dept_item_amount(this.id,1)" class="cost_center_department_amount1 form-control" style="text-align: right" type="any" name="cost_center_department_amount1[]" id="cost_center_department_amount_1_1" class="form-control requiredField" />
                </td><!-- >
            </tr>


            </tbody>
            <tr>
                <td colspan="2">Total</td>
                <td colspan="1"><input style="text-align: right;" readonly type="number" class="form-control" name="cost_center_total_dept1" id="cost_center_total_dept1"></td>
            </tr>
        </table>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <input  type="button" class="btn btn-xs btn-primary" onclick="cost_center_AddMore(1)" value="Add More Demand's Rows" />
                <input type="button" onclick="cost_center_remove(1)" class="btn btn-xs btn-danger" name="Remove" value="Remove">
            </div>
        </div>
    </div>
</div>
<!-->
<script>

    //  var dept_allocation=1;
    var cost_center_allocation='<?php echo $cost_center_count ?>';

    $(document).ready(function() {
        var max_fields      = 10; //maximum input boxes allowed





    });

    function cost_center_AddMore(number)
    {

        var wrapper      = $(".cost_center_addrows"+number);
        //    cost_center_allocation++;
        cost_center_allocation++;
        $(wrapper).append('<tr><td style="width: 40%"><select onchange="open_form_cost_center(this.id)" style="width: 170px;" name="cost_center_department'+number+'[]" id="cost_center_department_'+number+'_'+cost_center_allocation+'" class="form-control select2 CostCenter">' +
                '<option value="0">Select</option>   <option  value="add_new"><b>Add New</b>  </option>@foreach(CommonHelper::get_all_cost_center() as $row)<option value="<?php echo $row->id ?>"><?php echo ucwords($row->name)?></option>@endforeach</select></td>' +
                '<td> <input type="any" onkeyup="cost_center_calculation_dept_item(this.id,'+number+')" name="cost_center_percent'+number+'[]" id="cost_center_percent_'+number+'_'+cost_center_allocation+'" class="form-control" /></td>' +
                '<td class="text-right"><input onblur="add_cost_center('+number+',this.id)" onkeyup="cost_center_calculation_dept_item_amount(this.id,'+number+')" class="cost_center_department_amount'+number+' form-control" style="text-align: right"  type="text" name="cost_center_department_amount'+number+'[]" id="cost_center_department_amount_'+number+'_'+cost_center_allocation+'" class="form-control" /></td></tr>');
        if (ajaxformdeptCostCenter==1)
        {
            var array_count=SelectValCostCenter.length;
            for(i=0; i<array_count; i++)
            {

                $("#cost_center_department_"+number+"_"+cost_center_allocation).append($('<option>', {
                    value: SelectValCostCenter[i],
                    text: SelecttxtCostCenter[i]
                }));
            }
        }
        $('#cost_center_department_'+number+'_'+cost_center_allocation).select2();
        $('#cost_center_department_'+number+'_'+cost_center_allocation).focus();
        $('#cost_center_department_amount_'+number+'_'+cost_center_allocation+'').number(true,2);
    }
    function cost_center_remove(number)
    {
        var table = document.getElementById('cost_center_table'+number);
        var rowCount = table.rows.length;
        if (rowCount==3)
        {
            return false;
        }
        else
        {
            table.deleteRow(rowCount - 2);
            total_dept(number);
            var actual_amount = $('#cost_center_dept_hidden_amount' + number).val();
            var total_dept_amountt = $('#cost_center_total_dept' + number).val();
            cost_center_colour_change(number, total_dept_amountt, actual_amount);
            cost_center_heading_change(number);
            cost_center_total_dept(number);
        }
    }

    function cost_center_calculation_dept_item(id,number1)
    {

        var array=id.split('_');
        var number2=array[4];

        var actual_amount= $('#cost_center_dept_hidden_amount'+number1).val();
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
                $('#cost_center_department_amount_' + number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = percentage * actual_amount;
                var s_tax_amount = parseFloat(x / 100).toFixed(2);
                $('#cost_center_department_amount_' + number1+'_'+number2).val(s_tax_amount);



                var total_dept_amountt= cost_center_total_dept(number1);
                //  alert(total_dept_amountt);
                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {
                    $('#'+id).val(0);
                    $('#cost_center_department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    cost_center_total_dept(number1);
                    cost_center_heading_change(number1);
                }
            }
            cost_center_colour_change(number1,total_dept_amountt,actual_amount);
            cost_center_heading_change(number1);


        }
    }


    function cost_center_total_dept(number1)
    {
        var total_dept_amount=0;
        $( ".cost_center_department_amount"+number1 ).each(function( index ) {
            total_dept_amount+=+$(this).val();


        });
        total_dept_amount=parseFloat(total_dept_amount).toFixed(2);
        $('#cost_center_total_dept'+number1).val(total_dept_amount);
        return total_dept_amount;

    }

    function cost_center_colour_change(number1,total_dept_amountt,actual_amount)
    {


        var amount=  value_check_deiffrence_cost(total_dept_amountt,actual_amount);

        if (amount==1)
        {

            $("#cost_center_total_dept"+number1).delay(3000).css("background-color","yellow");
        }
        else
        {
            $("#cost_center_total_dept"+number1).delay(3000).css("background-color","red");
        }
    }


    function cost_center_calculation_dept_item_amount(id,number1)
    {
        var array=id.split('_');
        var number2=array[5];

        var actual_amount= parseFloat($('#cost_center_dept_hidden_amount'+number1).val());
        var tax_amount=parseFloat($('#'+id).val());

        if (isNaN(tax_amount)==true)
        {
            tax_amount=0;
            $('#cost_center_percent_'+number1+'_'+number2).val(0);
            cost_center_total_dept(number1);
            cost_center_heading_change(number1);
            cost_center_colour_change(number1,total_dept_amountt,actual_amount);
        }
        else
        {

            if (tax_amount > actual_amount)
            {

                alert('Amount Cannot Exceed'+' '+actual_amount);

                $('#'+id).val(0);
                $('#cost_center_percent_'+number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = tax_amount / actual_amount;
                percentage = parseFloat(x * 100).toFixed(2);
                $('#cost_center_percent_' + number1+'_'+number2).val(percentage);
                var total_dept_amountt= cost_center_total_dept(number1);
                //  alert(total_dept_amountt);
                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {
                    $('#'+id).val(0);
                    $('#cost_center_department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    $('#cost_center_percent_'+number1+'_'+number2).val(0);
                    cost_center_total_dept(number1);
                }
            }
            cost_center_colour_change(number1,total_dept_amountt,actual_amount);
            cost_center_heading_change(number1);

        }
    }

    function cost_center_heading_change(number)
    {
        var amount= $('#cost_center_dept_hidden_amount'+number).val();
        amount=parseFloat(amount);
        var dept_total_amount= $('#cost_center_total_dept'+number).val();
        var total=parseFloat(amount-dept_total_amount);

        $('#cost_center_dept_amount'+number).text(total);


    }


    function cost_center_allocation_amount_display(id)
    {

        var amount= $('#amount_1_'+id+'').val();
        if (amount>0)
        {
            $('#cost_center'+id).css("display","block");
        }
        else
        {
            $('#cost_center'+id).css("display","none");
        }
    }


    function add_cost_center(number,id)
    {

        var more=1;
        var amount= $('#cost_center_dept_hidden_amount'+number).val();

        var cost_center_amount= $('#cost_center_total_dept'+number).val();

        cost_center_amount=parseFloat(cost_center_amount);

        var select_cureent_amount=$('#'+id).val();



        if (select_cureent_amount > 0 && select_cureent_amount!='')
        {

            var v=value_check_deiffrence_cost(cost_center_amount,amount);
            if (v==0)
            {

                $( ".cost_center_department_amount"+number).each(function( index ) {

                    var vall=$(this).val();

                    if (vall==0 || vall=='')
                    {

                        more=0;
                    }
                });

                if (more==1)
                {
             
                    cost_center_AddMore(number);
                  //  window.scrollBy(0,180);
                  }


            }
        }


    }


    function value_check_deiffrence_cost(total_dept_amountt,actual_amount)
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

    function open_form_cost_center(id)
    {
        var dept=$('#'+id).val();
        if (dept=='add_new')
        {
            showDetailModelOneParamerter('pdc/createCostCenterFormAjax/'+id);
        }

    }


</script>