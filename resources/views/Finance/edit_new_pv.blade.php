<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$pv_no=CommonHelper::uniqe_no_for_pvv(date('y'),date('m'),1);



?>
@extends('layouts.default')

@section('content')
    @include('select2')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Pv</span>
                    </div>

                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => '/update_new_pv?m='.$m.'','id'=>'insert_new_pv'));?>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">PV No</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
                                                name="pv_no" id="pv_no" value="{{strtoupper($Master->pv_no)}}" />
                                        <input type="hidden" name="EditId" id="EditId" value="<?php echo $id?>">
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">PV Date.</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date" id="pv_date" value="<?php echo $Master->pv_date ?>" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Supplier Invoice No.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" id="supplier_invoice_no" name="supplier_invoice_no" placeholder="Supplier Invoice No" value="<?php echo $Master->supplier_invoice_no;?>">
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Supplier Invoice Date</label>
                                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" name="supplier_invoice_date" id="supplier_invoice_date" value="<?php echo $Master->supplier_invoice_date?>"  />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control requiredField">
                                            <?php $data=DB::Connection('mysql2')->select('select a.id,a.name from supplier a
                                                                                          INNER JOIN accounts b on b.id = a.acc_id
                                                                                          where a.status = 1 and b.parent_code = "2-2-8"');?>
                                            <option value="">Select Supplier</option>
                                            <?php foreach($data as $row):?>
                                            <option value="<?php echo $row->id?>" <?php if($Master->supplier_id == $row->id): echo "selected"; endif;?>><?php echo $row->name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Description</label>
                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"><?php echo $Master->description?></textarea>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="table table-bordered">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="5" class="text-center">Pv Detail Section</th>
                                                    <th colspan="1" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" /></th>
                                                    <th class="text-center"><span class="badge badge-success" id="span">1</span></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Sr No</th>
                                                    <th class="text-center" style="width: 30%;">Description</th>
                                                    <th class="text-center">Uom</th>
                                                    <th class="text-center">Qty <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="AppendHtml">
                                                <?php
                                                $Counter = 1;
                                                $TotalAmount = 0;
                                                foreach($Detail as $Fil):?>
                                                <tr class="text-center AutoNo" id="tr<?php echo $Counter?>">
                                                    <td><?php echo $Counter;?></td>
                                                    <td><textarea class="form-control requiredField" id="desc_<?php echo $Counter?>" name="desc[]" placeholder="Description" style="resize: none;"><?php echo $Fil->description?></textarea></td>
                                                    <td>
                                                        <select name="uom_id[]" id="uom_id_<?php echo $Counter?>" class="form-control requiredField">
                                                            <option value="">Select Uom</option>
                                                            <?php foreach(CommonHelper::get_all_uom() as $UFil):?>
                                                            <option value="<?php echo $UFil->id?>" <?php if($Fil->uom_id == $UFil->id): echo "selected"; endif;?>><?php echo $UFil->uom_name?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="form-control requiredField" id="qty_<?php echo $Counter?>" name="qty[]" placeholder="Qty" step="any" min="0" onkeyup="calc('<?php echo $Counter?>')" value="<?php echo $Fil->qty?>"></td>
                                                    <td><input type="number" class="form-control requiredField" id="rate_1" name="rate[]" placeholder="Rate" step="any" min="0" onkeyup="calc('<?php echo $Counter?>')" value="<?php echo $Fil->rate?>"></td>
                                                    <td><input type="number" class="form-control requiredField GetAmount" id="amount_1" name="amount[]" placeholder="Amount" step="any" min="0" readonly value="<?php echo $Fil->amount?>"><?php $TotalAmount+=$Fil->amount;?></td>
                                                    <?php if($Counter > 1):?>
                                                    <td class="text-center"> <input type="button" onclick="RemoveRow('<?php echo $Counter?>')" value="Remove" class="btn btn-sm btn-danger"> </td>
                                                    <?php else:?>
                                                    <td style="background-color: #ccc"></td>
                                                    <?php endif;?>
                                                </tr>

                                                <?php
                                                $Counter++;
                                                endforeach;?>
                                                </tbody>
                                                <tbody>
                                                <tr class="text-center">
                                                    <td colspan="3"></td>
                                                    <td colspan="2"><b style="font-size: 16px;">TOTAL</b></td>
                                                    <td id="SubTotal"><?php echo $TotalAmount?></td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td colspan="3"></td>
                                                    <td colspan="2">

                                                        <?php $data=DB::Connection('mysql2')->table('accounts')->where('status',1)->where('parent_code','1-2-10')->get();  ?>

                                                        <select   id="sales_tax" name="sales_tax" onchange="calculate_sales_tax()" class="form-control">
                                                            @foreach($data as $row)
                                                                <option value="{{$row->id}}" <?php if($Master->sales_tax_acc_id == $row->id): echo "selected"; endif;?> >{{$row->name}}</option>
                                                            @endforeach
                                                        </select>

                                                    </td>
                                                    <td><input type="number" class="form-control text-center" id="sales_tax_amount" name="sales_tax_amount" placeholder="Sales Tax Amount" step="any" min="0" readonly value="<?php echo $Master->sales_tax_amount?>"></td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td colspan="3"></td>
                                                    <td colspan="2"><b style="font-size: 16px;">AFTER TAX TOTAL</b></td>
                                                    <td id="AfterTaxTotal"><?php echo $TotalAmount+$Master->sales_tax_amount?></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pvsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>


    <script>

       var sales_tax=17;
        $(document).ready(function(){


            var name = $('#sales_tax').find(":selected").text();
               sales_tax = name.match(/\d+/);


            $('#supplier_id').select2();
            $('#sales_tax_acc_id').select2();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        });


    </script>

    <script>
        var x = '<?php echo $Counter?>';
        function AddMorePvs()
        {
            x++;

            $('#AppendHtml').append('<tr class="text-center AutoNo" id="tr'+x+'" >' +
                    '<td>'+x+'</td>' +
                    '<td><textarea class="form-control requiredField" id="desc_'+x+'" name="desc[]" placeholder="Description" style="resize:none;"></textarea></td>' +
                    '<td>' +
                    '<select name="uom_id[]" id="uom_id_'+x+'" class="form-control requiredField">' +
                    '<option value="">Select Uom</option>'+
                    <?php foreach(CommonHelper::get_all_uom() as $UFil):?>
                    '<option value="<?php echo $UFil->id?>"><?php echo $UFil->uom_name?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '</td>' +
                    '<td><input type="number" class="form-control requiredField" id="qty_'+x+'" name="qty[]" placeholder="Qty" step="any" min="0" onkeyup="calc('+x+')"></td>' +
                    '<td><input type="number" class="form-control requiredField" id="rate_'+x+'" name="rate[]" placeholder="Rate" step="any" min="0" onkeyup="calc('+x+')"></td>' +
                    '<td><input type="number" class="form-control requiredField GetAmount" id="amount_'+x+'" name="amount[]" placeholder="Amount" step="any" min="0" readonly></td>' +
                    '<td class="text-center"> <input type="button" onclick="RemoveRow('+x+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
                    '</tr>');
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            // $('.d_amount_1_3').number(true,2);
        }

        function RemoveRow(x)
        {
            $('#tr'+x).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            calc(x);

        }


        function calc(Row)
        {
            var sum = 0;
            var Qty = $('#qty_'+Row).val();
            var Rate = $('#rate_'+Row).val();
            var Amount = parseFloat(Qty*Rate).toFixed(2);
            $('#amount_'+Row).val(Amount);



            $('.GetAmount').each(function() {
                var getVal = parseFloat($(this).val());
                if(isNaN(getVal))
                {
                    getVal =0;
                }
                sum += getVal;
            });
            sum = sum.toFixed(2);

            $('#SubTotal').html('<b style="font-size: 20px;" id="AllTot">'+sum+'</b>');
            var TaxAmount = parseFloat(sum/100*sales_tax).toFixed(2);
            $('#sales_tax_amount').val(TaxAmount);
            var One = parseFloat($('#sales_tax_amount').val());
            var Tow = parseFloat($('#AllTot').html());

            var AfterTaxAmount =parseFloat(One+Tow).toFixed(2);

            $('#AfterTaxTotal').html('<b style="font-size: 20px;">'+AfterTaxAmount+'</b>');

        }


    </script>


    <script>
        $(function() {
            $(".btn-success").click(function(e){
                var purchaseRequest = new Array();
                var val;
                purchaseRequest.push($(this).val());
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0)
                    {
                        vala = 0;
                        var flag = false;
                        $('.SendQty').each(function(){
                            vala = parseFloat($(this).val());
                            if(vala == 0)
                            {
                                alert('Please Enter Correct  Qty....!');
                                $(this).css('border-color','red');
                                flag = true;
                                return false;
                            }
                            else
                            {
                                $(this).css('border-color','#ccc');
                            }
                        });
                        if(flag == true)
                        {return false;}
                    }
                    else
                    {
                        return false;
                    }
                }
            });
        });


        function calculate_sales_tax()
        {
            var name = $('#sales_tax').find(":selected").text();
            sales_tax = name.match(/\d+/);
            calc();

        }
    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection