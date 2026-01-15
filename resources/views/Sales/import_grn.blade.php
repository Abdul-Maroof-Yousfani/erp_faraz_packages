
<?php
use App\Helpers\CommonHelper;
$ImportPo = DB::Connection('mysql2')->table('import_po')->where('id',$voucher_no)->first();
$warehouse = DB::Connection('mysql2')->table('warehouse')->where('status',1)->get();
?>

<div style="display: none" id="convert_to_grn" class="row">
    <?php echo Form::open(array('url' => 'stad/addConvertGrnData','id'=>'import_grn','class'=>'stop'));?>
    <input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $ImportPo->vendor?>">
    <input type="hidden" id="voucher_no" name="voucher_no" value="<?php echo $ImportPo->voucher_no?>">
    <input type="hidden" id="import_id" name="import_id" value="<?php echo $voucher_no?>">
    <h3 style="text-align: center">Convert To GRN</h3>
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Item</th>
                <th class="text-center">QTY</th>
                <th class="text-center">FCY Unit Price</th>
                <th class="text-center">FCY Amunt</th>
                <th class="text-center">FCY Average Rate</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Landed Cost</th>
                <th class="text-center">Total Amount After Expense</th>
                <th class="text-center">Add More</th>

                </thead>

                <?php $data=DB::Connection('mysql2')->table('import_po_data as a')
                        ->select('a.*',DB::raw('SUM(b.amount_in_pkr) as pkr_lum_sum'),DB::raw('SUM(b.foreign_amount) as forign_lum_sum'),
                                DB::raw('SUM(c.duty) as total_exp'))
                        ->join('import_payment as b','a.master_id','=','b.import_id')
                        ->leftJoin('import_expense as c','a.master_id','=','c.import_id')
                        ->where('a.master_id',$voucher_no)
                        ->where('a.status',1)
                        ->where('b.status',1)
                        ->where('c.status',1)
                        ->groupBy('a.id')
                        ->groupby('b.import_id')
                        ->get();


                ?>
                <tbody id="data">


                @php

                $total_landed_cost=0;
                $total_amount_toal=0;
                $grand_total=0;
                $counter=1; @endphp
                @foreach($data as $row)

                    @php
                    $average=$row->pkr_lum_sum / $row->forign_lum_sum;
                    $fyc_amount=$row->qty * $row->foreign_currency_price;
                    $total_amount=$average*$fyc_amount;

                    $landed_cost=DB::Connection('mysql2')->table('import_po_data')->where('master_id',$row->master_id)->where('status',1)->sum('amount');
                    $landed_costt=$landed_cost*$average;
                    $expense_data=DB::Connection('mysql2')->table('import_expense')->where('import_id',$row->master_id)->where('status',1);
                    $total_exp=$expense_data->sum('duty')+$expense_data->sum('eto')+$expense_data->sum('do')+$expense_data->sum('appraisal')+$expense_data->sum('fright')+$expense_data->sum('insurance')
                    +$expense_data->sum('expense')+$expense_data->sum('other_expense');

                    $landed_cost=($total_amount / $landed_costt)*$total_exp;
                    $total_landed_cost+=$landed_cost;
                    $total_amount_toal+=$total_amount;


                    @endphp
                    <tr id="{{ $row->id }}">
                        <td class="text-center counter">{{$counter}}</td>
                        <td class="text-center counter <?php echo $row->item_id?>"><?php echo CommonHelper::get_item_name($row->item_id);?></td>
                        <td class="text-center main_{{$counter}}" id="GetQty<?php echo $row->id?>">{{ $row->qty }}</td>
                        <td class="text-center" id="Getfcprice<?php echo $row->id?>">{{ $row->foreign_currency_price }}</td>
                        <td class="text-center" id="Getfcamount<?php echo $row->id?>">{{ $fyc_amount }}</td>
                        <td class="text-center" id="Getaverage<?php echo $row->id?>">{{ number_format($average,2)}}</td>
                        <td class="text-center" >{{ number_format($total_amount,2) }}</td>

                        <td title="<?php echo (number_format($total_amount,2) .'/' .number_format($landed_costt,2)).'*'.number_format($total_exp,2); ?>"> {{number_format($landed_cost,2)}}</td>
                        <td title="<?php echo number_format($landed_cost,2).'+'.number_format($total_amount,2) ?>">{{number_format($landed_cost+$total_amount,2)}}</td>
                        <input type="hidden" id="FinalAmount<?php echo $row->id?>" value="<?php echo $landed_cost+$total_amount?>">
                        <input type="hidden" id="Totalamount<?php echo $row->id?>" value="{{ $landed_cost+$total_amount }}">
                        <input type="hidden" id="grn_total_qty<?php echo $row->id?>" value="{{ $row->qty}}">
                        <input type="hidden" id="GetItemId<?php echo $row->id?>" value="{{ $row->item_id }}">
                        <input type="hidden" id="GetImportDataId<?php echo $row->id?>" value="{{ $row->id }}">

                        <td>
                            <button type="button" class="btn btn-xs btn-primary" onclick="AddMoreGrnRows('<?php echo $row->id?>','{{$counter}}')">Add More</button>
                        </td>
                        <?php $grand_total+=$landed_cost+$total_amount; ?>

                    </tr>
                    <input type="hidden" id="id{{$counter}}" value="{{$row->id}}"/>


                    <tr>
                <tbody id="AppendHtml<?php echo $row->id?>"></tbody>
                </tr>
                <?php $counter++ ?>    @endforeach
                <input type="hidden" id="count" value="{{$counter-1}}"/>
                <tr style="font-weight: bolder">
                    <td colspan="5">Total</td>
                    <td>{{number_format($total_amount_toal,2)}}</td>
                    <td>{{number_format($total_landed_cost,2)}}</td>
                    <td>{{number_format($grand_total,2)}}</td>

                </tr>

                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="count" id="count" value="{{$counter-1}}"/>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="">
            {{ Form::submit('Submit', ['class' => 'btn']) }}

        </div>


    </div>
    <?php echo Form::close();?>
</div>


<script>

    $(document).ready(function(){
        $('#ShowHide').css('display','none');
    });

    $(function() {

        var count=parseFloat($('#count').val());
        for (i=1; i<=count; i++)
        {
            var id=$('#id'+i).val();

            AddMoreGrnRows(id,i);
            var main= parseFloat($('.main_'+i).html());
            $('.child_'+i).val(main);
        }

        $(".btn-success").click(function(e){
            var purchaseRequest = new Array();
            var val;
            //$("input[name='demandsSection[]']").each(function(){
            purchaseRequest.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of purchaseRequest) {
                jqueryValidationCustom();
                if(validate == 0){

                    $('#cashPaymentVoucherForm').submit();
                }
                else
                {
                    return false;
                }
            }

        });
    });




    function convert_grn()
    {

        $('#convert_to_grn').fadeIn(500);
        $('#payment_section').fadeOut(500);
    }
    var Counter ='<?php echo $counter?>';
    function AddMoreGrnRows(Id,classs)
    {

        Counter++;
        var TotalAmount = parseFloat($('#Totalamount'+Id).val());
        var total_qty = parseFloat($('#grn_total_qty'+Id).val());
        var GetItemId = $('#GetItemId'+Id).val();
        var GetImportDataId = $('#GetImportDataId'+Id).val();



        var child='child_'+classs;
        $('#AppendHtml'+Id).append('<tr id="RemoveGrnRow'+Counter+'">' +
                '<input type="hidden" id="Totalamount" name="GrnTotalAmount[]" value="'+TotalAmount+'">'+
                '<input type="hidden" id="total_qty" name="total_qty[]" value="'+total_qty+'">'+
                '<input type="hidden" id="GrnItemId" name="GrnItemId[]" value="'+GetItemId+'">'+
                '<input type="hidden" id="GrnImportDataId" name="GrnImportDataId[]" value="'+GetImportDataId+'">'+
                '<input type="hidden" id="AutoCountVal'+Id+'" class="AutoCounter'+Id+'">'+
                '<input type="hidden" class="CountHtml'+Id+'" id="Gelines'+Counter+'" name="getLines[]">'+

                '<td colspan="4" id="formula_'+Id+'_'+Counter+'"></td>'+
                '<td><input type="text" name="GetBatchCode[]" class="form-control requiredField batch'+Counter+'" id="batch_code_'+Id+'_'+Counter+'" value="0" placeholder="Batch Code"></td>' +
                '<td><input type="number" step="any" name="grn_qty[]" class="form-control requiredField '+child+' GrnQtyLoop'+Id+' qty'+Counter+'" onkeyup="Calculate('+Id+','+Counter+')" id="grn_qty_'+Id+'_'+Counter+'" placeholder="Quantity"></td>' +
                '<td><select name="warehouse_id[]" onchange="select_ware_house('+Counter+')" id="warehouse_id'+Counter+'" class="form-control warehouse ware'+Counter+'">' +
                '<option value="">Select Location</option>'+
                <?php foreach($warehouse as $w):?>
                '<option value="<?php echo $w->id?>"><?php echo $w->name?></option>'+
                <?php endforeach;?>
                '</select></td>'+
                '<td><input type="hidden" name="grn_amount[]" class="form-control requiredField" onkeyup="Calculate('+Id+','+Counter+')" id="grn_amount_'+Id+'_'+Counter+'" placeholder="Quantity"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="RemoveGrnRows('+Counter+','+Id+')" id="BtnRemoveGrnRow'+Counter+'">Remove</button></td>' +
                '<td colspan="2" id="CountHtml'+Id+'"></td>'+
                '</tr>');
        var AutoCount = 0;
        $(".AutoCounter"+Id).each(function(){
            AutoCount++;
            $('#AutoCountVal'+Id).val(AutoCount);
            $('#CountHtml'+Id).html(AutoCount);
            $('.CountHtml'+Id).val(AutoCount);
        });
//        var TotalCount = $('#CountHtml'+Id).html();
//        $('#Gelines'+Counter).val(TotalCount);

    }

    function RemoveGrnRows(Row,Id)
    {
        $('#RemoveGrnRow'+Row).remove();
        var AutoCount = 0;
        $(".AutoCounter"+Id).each(function(){
            AutoCount++;
            $('#RowNumber'+Id).val(AutoCount);
            $('#CountHtml'+Id).html(AutoCount);

        });
    }

    function Calculate(Id,Count)
    {
        var ActualQty = parseFloat($('#GetQty'+Id).html());
//        var FinalAmount = parseFloat($('#FinalAmount'+Id).val());
//        var AutoCounterVal = $('#AutoCountVal'+Id).val();

        var TotalQty = 0;
        $(".GrnQtyLoop"+Id).each(function(){
            TotalQty +=parseFloat($(this).val());
        });


        if(TotalQty > ActualQty )
        {
            alert('Quantity can`t exceed '+ActualQty+'...!');
            $('#grn_qty_'+Id+'_'+Count).val('');
        }

//        var LineQty = parseFloat($('#grn_qty_'+Id+'_'+Count).val());
//        var LinesWiseRate = parseFloat(FinalAmount / AutoCounterVal).toFixed(2);
//        var LineWiseAmount = parseFloat(LineQty*LinesWiseRate).toFixed(2);
//        $('#formula_'+Id+'_'+Count).html(FinalAmount + ' / ' + AutoCounterVal + ' = ' +LinesWiseRate + ' * ' + LineQty + ' = ' + LineWiseAmount);

    }
    $('#import_grn').submit('click', function (event) {
        // using this page stop being refreshing

        var count=$('#count').val();
        for(i=1; i<=count; i++)
        {

            var sum=0;
            var main_qty= parseFloat($('.main_'+i).html());



            $(".child_"+i).each(function(){
                sum+=+parseFloat($('#'+this.id).val());

            });

            if (sum!=main_qty)
            {
                $('.main_'+i).css("color", "red");
                return false;
            }
            else
            {
                $('.main_'+i).css("color", "");
            }
        }
        $('form').submit();
        /*
         $.ajax({
         type: 'POST',
         url:'{{url('/sad/addConvertGrnData')}}',
         data: $('#import_grn').serialize(),
         success: function (data) {
         if(data == 'yes')
         {
         alert('Record Submit');

         }
         }
         });
         */


    });


    function select_ware_house(count)
    {

        if (count==2)
        {

            var  warehouse= $('#warehouse_id'+count).val();
            $('.warehouse').val(warehouse);
        }
    }
</script>