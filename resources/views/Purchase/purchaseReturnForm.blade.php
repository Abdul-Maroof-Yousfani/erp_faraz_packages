<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Purchase Return Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'pad/addPurchaseReturnDetail?m='.$m.'','id'=>'addPurchaseReturnDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Return Against</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="return_against" id="return_against" onchange="resetPurchaseReturnSource()">
                                                        <option value="">Select Type</option>
                                                        <option value="direct_purchase_order">Direct Purchase Order</option>
                                                        <option value="purchase_order">Purchase Order</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Supplier</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="supplier" id="supplier" onchange="loadReturnSourceBySupplier()">
                                                        <option value="">Select Supplier</option>
                                                        <?php foreach(CommonHelper::get_all_supplier() as $row){?>
                                                        <option value="{{$row->id}}"> {{ucwords($row->name)}} </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="grn_source_section" style="display:none;">
                                                    <label class="sf-label">GRN No (Approved)</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="grn_value" id="grn_value" onchange="loadPurchaseReturnDetailByGrnNo()">
                                                        <option value="">Select GRN</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="invoice_source_section" style="display:none;">
                                                    <label class="sf-label">Purchase Invoice No (Direct)</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="purchase_invoice_value" id="purchase_invoice_value" onchange="loadPurchaseReturnDetailByInvoiceNo()">
                                                        <option value="">Select Purchase Invoice</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadGoodsReceiptNoteDetailSection"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                    <button type="reset" id="reset" class="btn btn-danger-2">Clear Form</button>
                                </div>
                            </div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>

        $(document).ready(function(){
            $('#return_against').select2();
            $('#supplier').select2();
            $('#grn_value').select2();
            $('#purchase_invoice_value').select2();
            resetPurchaseReturnSource();
        });

        function resetPurchaseReturnSource() {
            $('.loadGoodsReceiptNoteDetailSection').html('');
            $('#grn_value').html('<option value="">Select GRN</option>').val('').trigger('change.select2');
            $('#purchase_invoice_value').html('<option value="">Select Purchase Invoice</option>').val('').trigger('change.select2');

            if ($('#return_against').val() === 'purchase_order') {
                $('#grn_source_section').show();
                $('#invoice_source_section').hide();
                $('#grn_value').prop('required', true).addClass('requiredField');
                $('#purchase_invoice_value').prop('required', false).removeClass('requiredField');
            } else if ($('#return_against').val() === 'direct_purchase_order') {
                $('#grn_source_section').hide();
                $('#invoice_source_section').show();
                $('#grn_value').prop('required', false).removeClass('requiredField');
                $('#purchase_invoice_value').prop('required', true).addClass('requiredField');
            } else {
                $('#grn_source_section').hide();
                $('#invoice_source_section').hide();
                $('#grn_value, #purchase_invoice_value').prop('required', false).removeClass('requiredField');
            }

            if ($('#supplier').val()) {
                loadReturnSourceBySupplier();
            }
        }

        function loadReturnSourceBySupplier() {
            $('.loadGoodsReceiptNoteDetailSection').html('');
            var supplier_id = $('#supplier').val();
            var return_against = $('#return_against').val();

            if (!return_against) {
                $('#grn_source_section').hide();
                $('#invoice_source_section').hide();
                return false;
            }

            if (!supplier_id) {
                return false;
            }

            if (return_against === 'purchase_order') {
                getApprovedGrnBySupplier(supplier_id);
            } else {
                getApprovedPurchaseInvoiceBySupplier(supplier_id);
            }
        }

        function getApprovedGrnBySupplier(supplier_id) {
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/getApprovedGrnBySupplierForReturn',
                type: "GET",
                data: { supplier_id: supplier_id },
                success: function(data) {
                    $('#grn_value').html(data);
                    $('#grn_value').select2();
                }
            });
        }

        function getApprovedPurchaseInvoiceBySupplier(supplier_id) {
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/getPurchaseInvoiceNoBySupplier',
                type: "GET",
                data: { supplier_id: supplier_id, direct_only: 1 },
                success: function(data) {
                    $('#purchase_invoice_value').html(data);
                    $('#purchase_invoice_value').select2();
                }
            });
        }

        function loadPurchaseReturnDetailByGrnNo() {
            var GrnValue = $('#grn_value').val();
            var m = '<?php echo $_GET['m']?>';
            if (GrnValue == '' || GrnValue == null) {
                $('.loadGoodsReceiptNoteDetailSection').html('');
                return false;
            } else {
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormPurchaseReturnDetailByGrnNo',
                    type: "GET",
                    data: { GrnValue: GrnValue, m: m },
                    success: function(data) {
                        $('.loadGoodsReceiptNoteDetailSection').html(data);
                    }
                });
            }
        }

        function loadPurchaseReturnDetailByInvoiceNo() {
            var PurchaseInvoiceNo = $('#purchase_invoice_value').val();
            var m = '<?php echo $_GET['m']?>';
            if (PurchaseInvoiceNo == '' || PurchaseInvoiceNo == null) {
                $('.loadGoodsReceiptNoteDetailSection').html('');
                return false;
            } else {
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormPurchaseReturnDetailByInvoiceNo',
                    type: "GET",
                    data: { PurchaseInvoiceNo: PurchaseInvoiceNo, m: m },
                    success: function(data) {
                        $('.loadGoodsReceiptNoteDetailSection').html(data);
                    }
                });
            }
        }


        $( "form" ).submit(function( event ) {
            var validate=validatee();
           
            if (validate==true)
            {

            }
            else
            {
                return false;
            }

        });
        function validatee()
        {
            var validate=true;
            $( ".amount" ).each(function() {
                var id=this.id;
                if($('#'+id).prop("checked") == true)
                {

                    id=id.replace('enable_disable_','');
                    var amount=$('#return_qty_'+id).val();
                      
                    if (amount <= 0 || amount=='')
                    {
                        $('#return_qty_'+id).css('border', '3px solid red');

                         validate=false;
                    }
                    else
                    {
                        $('#return_qty_'+id).css('border', '');

                        if ($('#Remarks').val()=='')
                        {
                            $('#Remarks').css('border', '3px solid red');

                             validate=false;
                        }
                    }



                }

            });
            return validate;
        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection
