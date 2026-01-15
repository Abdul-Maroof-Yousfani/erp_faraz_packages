

<script>

</script>


<div id="dept_allocations<?php echo $count ?>"  style="display: none"  class="col-lg-6 col-md-6 col-sm-6 col-xs-12 banks">

    <div class="row">
        <div id="dept_allocation<?php echo $count ?>"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">
            <input type="checkbox" checked name="dept_check_box<?php echo $count ?>" value="1" id="dept_check_box<?php echo $count ?>" class="">Allow Null</label>
        </div>
        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label><b>(Department)</b></label>
        </div>
    </div>


    <table  id="table1" class="table table-bordered">
        <input type="hidden" name="dept_hidden_amount<?php echo $count ?>" id="dept_hidden_amount<?php echo $count ?>" value="<?php  if ($good_recipt_note->type==0): echo $net_amount; endif; ?>"/>
        <tbody class="addrows<?php echo $count ?>" id="">
        <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
        <tr>
            <td style="width: 40%">
                <select  onchange="open_form(this.id)" style="width: 100%" name="department<?php echo $count ?>[]" id="department_<?php echo $count ?>_1"
                         class="form-control select2 requiredField dept1 dept_form">
                    <option value="0">Select Department&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                    <option  value="add_new"><b>Add New</b>  </option>
                    @foreach($department as $row)
                        <option value="{{$row->id}}">{{  ucwords($row->name)}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input  onkeyup="calculation_dept_item(this.id,'<?php echo $count ?>')" placeholder="Percentage" type="any" name="percent<?php echo $count ?>[]" id="percent_<?php echo $count ?>_1" class="form-control dept<?php echo $count ?>" />
            </td>
            <td class="text-right">

                <input onblur="add('<?php echo $count ?>',this.id)"   onkeyup="calculation_dept_item_amount(this.id,'<?php echo $count ?>')" class="department_amount<?php echo $count ?> form-control dept<?php echo $count ?>" style="text-align: right" type="text" name="department_amount<?php echo $count ?>[]" id="department_amount_<?php echo $count ?>_1" />
            </td><!-->
        </tr>


        </tbody>
        <tr>
            <td colspan="1">Total</td>

            <td colspan="2" ><input style="text-align: right;" readonly type="text" class="form-control" name="total_dept<?php echo $count ?>"  id="total_dept<?php echo $count ?>"></td>
        </tr>
    </table>
</div>


<!--
            <div class="dept_part">
            <div  class="table-responsive">
                <h4 style="text-align: center"><b>Depratment For Item / Expense</h4>
                <h5 id="dept_item1">---</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
             <label>
                        </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
                <h4 style="text-align: right" id="dept_amount1">0</h4>
                    </div>
                    </div>


                <input type="hidden" name="dept_hidden_amount1" id="dept_hidden_amount1"/>

                <table  id="table1" class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 200px;" class="text-center">Department <span class="rflabelsteric"><strong>*</strong></span></th>
                        <th style="width: 100px;" class="text-center">Percentage <span class="rflabelsteric"><strong>*</strong></span></th>
                        <th style="width: 200px" class="text-center">Amount <span class="rflabelsteric"><strong>*</strong></span></th>

                    </tr>
                    </thead>
                    <tbody class="addrows1" id="">
                    <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
                    <tr>
                        <td>
                            <select style="width: 100%" name="department1[]" id="department_1_1"
                                    class="form-control select2 requiredField dept1">
                                <option value="0">Select</option>
                                @foreach($department as $row)
        <option value="{{$row->id}}">{{ $row->code .' ---- '. $row->name}}</option>
                                @endforeach
        </select>
    </td>
    <td>
        <input value="0" onkeyup="calculation_dept_item(this.id,1)" type="any" name="percent1[]" id="percent_1_1" class="form-control requiredField dept1" />
                        </td>
                        <td class="text-right">

                            <input value="0" onkeyup="calculation_dept_item_amount(this.id,1)" class="department_amount1 form-control  requiredField dept1" style="text-align: right" type="any" name="department_amount1[]" id="department_amount_1_1" />
                        </td><!-- >
                    </tr>


                    </tbody>
                    <tr>
                        <td colspan="2">Total</td>
                        <td colspan="1"><input style="text-align: right;" readonly type="number" class="form-control" name="total_dept1" id="total_dept1"></td>
                    </tr>
                </table>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input  type="button" class="btn btn-xs btn-primary" onclick="AddMore(1)" value="Add More Demand's Rows" />
                        <input type="button" onclick="remove(1)" class="btn btn-xs btn-danger" name="Remove" value="Remove">
                    </div>
                </div>
</div>
            </div>

<!-->






<script type="text/javascript">

    var dept_allocation=1;

    $(document).ready(function() {

        $('#department_amount_1_1').number(true,2);

        var max_fields      = 10; //maximum input boxes allowed





    });
</script>

<script type="text/javascript">
    function AddMore(number)
    {

        var wrapper      = $(".addrows"+number);
        dept_allocation++;
        $(wrapper).append('<tr><td style="40%"><select onchange="open_form(this.id)" style="" name="department'+number+'[]" id="department_'+number+'_'+dept_allocation+'" class="form-control select2 dept'+number+' dept_form">' +
                '<option value="0">Select</option> <option  value="add_new"><b>Add New</b> </option>@foreach($department as $row)<option value="<?php echo $row->id ?>"><?php echo  ucwords($row->name)?></option>@endforeach</select></td>' +
                '<td> <input type="any" onkeyup="calculation_dept_item(this.id,'+number+')" name="percent'+number+'[]" id="percent_'+number+'_'+dept_allocation+'" class="form-control requiredField dept'+number+'" /></td>' +
                '<td class="text-right"><input onblur="add('+number+',this.id)" onkeyup="calculation_dept_item_amount(this.id,'+number+')" class="department_amount'+number+' form-control  requiredField dept'+number+'" style="text-align: right"  type="text" name="department_amount'+number+'[]" id="department_amount_'+number+'_'+dept_allocation+'" /></td></tr>');

        if (ajaxformdept==1)
        {
            var array_count=SelectVal.length;
            for(i=0; i<array_count; i++)
            {

                $("#department_"+number+"_"+dept_allocation).append($('<option>', {
                    value: SelectVal[i],
                    text: Selecttxt[i]
                }));
            }
        }


        $('#department_'+number+'_'+dept_allocation).select2();
        $('#department_'+number+'_'+dept_allocation).focus();

        $('#department_amount_'+number+'_'+dept_allocation+'').number(true,2);
    }
    function remove(number)
    {
        var table = document.getElementById('table'+number);
        var rowCount = table.rows.length;
        if (rowCount==3)
        {
            return false;
        }
        else
        {
            table.deleteRow(rowCount - 2);
            total_dept(number);
            var actual_amount = $('#dept_hidden_amount' + number).val();
            var total_dept_amountt = $('#total_dept' + number).val();
            colour_change(number, total_dept_amountt, actual_amount);
            heading_change(number);
        }
    }

    function calculation_dept_item(id,number1)
    {

        var array=id.split('_');
        var number2=array[2];
        var actual_amount= $('#dept_hidden_amount'+number1).val();
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
                $('#department_amount_' + number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = parseFloat(percentage * actual_amount).toFixed(2);

                var s_tax_amount =parseFloat(x / 100).toFixed(2);
                $('#department_amount_' + number1+'_'+number2).val(s_tax_amount);



                var total_dept_amountt= total_dept(number1);

                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {

                    $('#'+id).val(0);
                    $('#department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    total_dept(number1);
                    heading_change(number1);
                }
            }
            colour_change(number1,total_dept_amountt,actual_amount);
            heading_change(number1);


        }
    }


    function total_dept(number1)
    {
        var total_dept_amount=0;
        $( ".department_amount"+number1 ).each(function( index ) {
            total_dept_amount+=+$(this).val();


        });
        total_dept_amount=parseFloat(total_dept_amount).toFixed(2);
        $('#total_dept'+number1).val(total_dept_amount);
        return total_dept_amount;

    }

    function colour_change(number1,total_dept_amountt,actual_amount)
    {
        var amount=  value_check_deiffrence_dept(total_dept_amountt,actual_amount);

        if (amount==1)
        {

            $("#total_dept"+number1).delay(3000).css("background-color","yellow");
        }
        else
        {
            $("#total_dept"+number1).delay(3000).css("background-color","red");
        }
    }


    function calculation_dept_item_amount(id,number1)
    {
        var array=id.split('_');
        var number2=array[3];
        var actual_amount= parseFloat($('#dept_hidden_amount'+number1).val());
        var tax_amount=parseFloat($('#'+id).val());
        if (isNaN(tax_amount)==true)
        {
            percentage=0;
        }
        else
        {
            if (tax_amount > actual_amount)
            {

                alert('Amount Cannot Exceed'+' '+actual_amount);
                $('#'+id).val(0);
                $('#percent_' + number1+'_'+number2).val(0);
                return false;
            }
            else
            {
                var x = tax_amount / actual_amount;
                percentage = parseFloat(x * 100).toFixed(2);
                $('#percent_' + number1+'_'+number2).val(percentage);
                var total_dept_amountt= total_dept(number1);
                //  alert(total_dept_amountt);
                total_dept_amountt=Math.round(total_dept_amountt);
                actual_amount=Math.round(actual_amount);
                if (total_dept_amountt > actual_amount)
                {
                    $('#'+id).val(0);
                    $('#department_amount_' + number1+'_'+number2).val(0);
                    alert('Amount Cannot Exceed'+' '+actual_amount);
                    $('#percent_' + number1+'_'+number2).val(0);

                    total_dept(number1);
                }
            }
            colour_change(number1,total_dept_amountt,actual_amount);
            heading_change(number1);

        }
    }

    function heading_change(number)
    {
        var amount= $('#dept_hidden_amount'+number).val();
        amount=parseFloat(amount);
        var dept_total_amount= $('#total_dept'+number).val();
        var total=parseFloat(amount-dept_total_amount).toFixed(2);

        $('#dept_amount'+number).text(total);

    }

    function dept_allocation_amount_display(id)
    {


        var amount= $('#amount_1_'+id+'').val();
        if (amount>0)
        {
            $('#dept_allocation'+id).css("display","block");
            //   window.scrollBy(0,180);
        }
        else
        {
            $('#dept_allocation'+id).css("display","none");
        }
    }




    function add(number,id)
    {

        var more=1;
        var amount= $('#dept_hidden_amount'+number).val();

        amount=parseFloat(amount);
        var department_amount= $('#total_dept'+number).val();
        department_amount=parseFloat(department_amount);

        var select_cureent_amount=$('#'+id).val();

        if (select_cureent_amount >0 && select_cureent_amount!='')
        {

            var v=value_check_deiffrence_dept(department_amount,amount);

            if (v==0)
            {
                $( ".department_amount"+number).each(function( index ) {
                    var vall=$(this).val();
                    if (vall==0 || vall=='')
                    {
                        more=0;
                    }



                });

                if (more==1)
                {

                    AddMore(number);
                    //     window.scrollBy(0,180)
                }

            }
        }


    }
    function value_check_deiffrence_dept(total_dept_amountt,actual_amount)
    {
        //   var v=0;
//    var v= Math.abs(total_dept_amountt - actual_amount);
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


    function open_form(id)
    {
        var dept=$('#'+id).val();
        if (dept=='add_new')
        {
            showDetailModelOneParamerter('pdc/createDepartmentFormAjax/'+id);
        }

    }



</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>