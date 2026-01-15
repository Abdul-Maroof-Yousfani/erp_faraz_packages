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
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Supplier</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="supplier" id="supplier" onchange="getGrnNoBySupplier()">
                                                        <option value="">Select Supplier</option>
                                                        <?php foreach(CommonHelper::get_all_supplier() as $row){?>
                                                        <option value="{{$row->id}}"> {{ucwords($row->name)}} </option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">GRN NO</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="grn_no" id="grn_no" onchange="loadGoodsReceiptNoteDetailByGrnNo()">
                                                        <option>select</option>

                                                        <?php ?>
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
            $('#supplier').select2();
        });

        function getGrnNoBySupplier() {

            $('.loadGoodsReceiptNoteDetailSection').html('');
            var supplier_id=$('#supplier').val();
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/getGrnNoBySupplier',
                type: "GET",
                data: { supplier_id:supplier_id},
                success:function(data)
                {
                    $('#grn_no').html(data);
                    $('#grn_no').select2();
                }
            });
        }

        function loadGoodsReceiptNoteDetailByGrnNo(){


            var GrnNo = $('#grn_no').val();
            var m = '<?php echo $_GET['m']?>';
            if(GrnNo == ''){
                alert('Please Select Purchase Request No');
                $('.loadGoodsReceiptNoteDetailSection').html('');
            }else{
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormGoodsReceiptNoteDetailByGrnNo',
                    type: "GET",
                    data: { GrnNo:GrnNo,m:m},
                    success:function(data) {
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