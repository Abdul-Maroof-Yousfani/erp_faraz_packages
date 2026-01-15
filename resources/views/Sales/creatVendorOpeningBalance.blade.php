<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <style>
        * {
            font-size: 12px!important;

        }
        label {
            text-transform: capitalize;
        }
    </style>

    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Vendor Opening Balance</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/addVendorOpeningBalance?m='.$m.'','id'=>'createSalesOrder'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">Vendor</label>
                                                <select name="vendor_id" id="vendor_id" class="form-control select2 requiredField" onchange="GetVendorWiseOpening()">
                                                    <option value="">Select Supplier</option>
                                                    <?php foreach($Supplier as $Fil):?>
                                                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" id="ShowOn">

                                        </div>

                                        <span id="AppendHtml">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="date" class="form-control requiredField" placeholder="" name="vo_date[]" id="vo_date1" value="<?php echo date('Y-m-d')?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PI No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  autofocus type="text" class="form-control requiredField" placeholder="" name="pi_no[]" id="si_no1" value="" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PO No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="text" class="form-control requiredField" placeholder="" name="po_no[]" id="po_no1" value="" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Invoice Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="number" class="form-control requiredField" placeholder="" name="invoice_amount[]" id="invoice_amount1" value="" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Balance Amount<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="number" class="form-control requiredField" placeholder="" name="balance_amount[]" id="balance_amount1" value="" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <button type="button" class="btn btn-primary" id="BtnAddMore" onclick="AddMoreRows()" style="margin: 33px 0px 0px 0px; width: 200px;">Add More</button>
                                            </div>
                                        </div>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="demandsSection"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
            </div>
        </div>
    </div>

    <script>

        function GetVendorWiseOpening()
        {
            var VendorId = $('#vendor_id').val();
            if(VendorId !="")
            {
                $('#ShowOn').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 loader"></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/stdc/getVendorWiseOpeningData',
                    type: "GET",
                    data: {VendorId:VendorId},
                    success:function(data) {
                        $('#ShowOn').html(data);

                    }
                });
            }
            else
            {
                $('#ShowOn').html('');
            }

        }
        var Counter = 1;
        function AddMoreRows()
        {
            Counter++;
            $('#AppendHtml').append('<div class="row" id="RemoveRows'+Counter+'">' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label class="sf-label">Date<span class="rflabelsteric"><strong>*</strong></span></label>' +
                    '<input type="date" class="form-control requiredField" placeholder="" name="vo_date[]" id="vo_date'+Counter+'" value="<?php echo date('Y-m-d')?>" />'+
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label class="sf-label">PI No<span class="rflabelsteric"><strong>*</strong></span></label>' +
                    '<input  autofocus type="text" class="form-control requiredField" placeholder="" name="pi_no[]" id="pi_no'+Counter+'" value="" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label class="sf-label">PO No<span class="rflabelsteric"><strong>*</strong></span></label>' +
                    '<input  type="text" class="form-control requiredField" placeholder="" name="po_no[]" id="po_no'+Counter+'" value="" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label class="sf-label">Invoice Amount<span class="rflabelsteric"><strong>*</strong></span></label>' +
                    '<input type="number" class="form-control requiredField" placeholder="" name="invoice_amount[]" id="invoice_amount'+Counter+'" value="" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label class="sf-label">Balance Amount<span class="rflabelsteric"><strong>*</strong></span></label>' +
                    '<input type="number" class="form-control requiredField" placeholder="" name="balance_amount[]" id="balance_amount'+Counter+'" value="" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<button type="button" class="btn btn-sm btn-danger" id="BtnAddMore" onclick="RemoveRows('+Counter+')" style="margin: 33px 0px 0px 0px; width: 200px;">Remove</button>' +
                    '</div>' +
                    '</div>');
        }

        function RemoveRows(Rows)
        {
            $('#RemoveRows'+Rows).remove();
        }

        $(document).ready(function() {



            $(".btn-success").click(function(e)
            {

                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands)
                {

                    jqueryValidationCustom();
                    if(validate == 0){


                    }else{
                        return false;
                    }
                }

            });
        });

    </script>
    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection