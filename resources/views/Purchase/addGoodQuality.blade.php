<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
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
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Goods Receipt Note Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'pad/addGoodsReceiptNoteDetail?m=' . $m . '', 'id' => 'addGoodsReceiptNoteDetail')); ?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">



                                                <label class="sf-label">Supplier</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" required name="supplier" id="supplier" onchange="get_po()">
                                                    <option value="0">Select Supplier</option>
                                                    <?php foreach (CommonHelper::get_all_supplier() as $row) { ?>
                                                        <option value="{{$row->id}}"> {{ucwords($row->name)}} </option>
                                                    <?php } ?>
                                                </select>


                                                <label class="sf-label">GRN</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" required name="po" id="po" onchange="loadGoodsReceiptNoteDetailByGrnNo()">
                                                    <option>select</option>

                                                    <?php ?>
                                                </select>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 SectionTwo" style="display: none">
                                                    <label class="sf-label">PO NO Manual</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control" id="PoNoManual" name="PoNoManual" placeholder="Po No Manual">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 SectionTwo" style="display: none;">
                                                    <button type="button" class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetManualPoByNo()" style="margin: 33px 0px 0px 0px;">Get Data</button>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadGoodsReceiptNoteDetailSection"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function loadGoodsReceiptNoteDetailByGrnNo() {


        var GrnNo = $('#po').val();
        var m = '<?php echo $_GET['m'] ?>';
        if (GrnNo == '') {
            alert('Please Select Purchase Request No');
            $('.loadGoodsReceiptNoteDetailSection').html('');
        } else {
            $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/') ?>/pmfal/makeFormGoodsReceiptNoteDetailByGrnNo',
                type: "GET",
                data: {
                    GrnNo: GrnNo,
                    m: m
                },
                success: function(data) {
                    $('.loadGoodsReceiptNoteDetailSection').html(data);
                }
            });
        }
    }





    function get_po() {

        $('.loadGoodsReceiptNoteDetailSection').html('');
        var supplier_id = $('#supplier').val();
        $.ajax({
            url: '<?php echo url('/') ?>/pmfal/getGrnNoBySupplier',
            type: "GET",
            data: {
                supplier_id: supplier_id
            },
            success: function(data) {

                $('#po').html(data);
            }
        });
    }
</script>
@endsection