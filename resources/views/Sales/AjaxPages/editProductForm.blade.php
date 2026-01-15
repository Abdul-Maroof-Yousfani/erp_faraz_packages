<?php


use App\Helpers\CommonHelper;

?>

 <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Product</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/updateProductForm','id'=>'cashPaymentVoucherForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <input type="hidden" name="m" value="<?php echo $_GET['m']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Account: <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control requiredField select2" name="acc_id" id="acc_id" disabled>
                                                    <option value=""><?php echo CommonHelper::get_account_name($product->acc_id) ?></option>
                                                </select>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Product Name: <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Product Name" name="product_name" id="product_name" value="<?php echo $product->p_name?>" />
                                            </div>
                                            <input type="hidden" name="CompanyId" id="CompanyId" value="<?php echo $_GET['m']?>">
                                            <input type="hidden" name="product_id" id="product_id" value="<?php echo $product->product_id?>">



                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Type: <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control requiredField select2" name="type_id" id="type_id">
                                                    <option value="">---SELECT---</option>
                                                    @foreach(CommonHelper::get_all_product_type() as $row)
                                                        <option value="{{$row->product_type_id}}" <?php if($product->type_id == $row->product_type_id){echo "selected";}?>>{{$row->type}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


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
    </div>

    <script>
        $( document ).ready(function() {
            $('.select2').select2();

            $(".btn-success").click(function(e){
                jqueryValidationCustom();
                if(validate == 0){

                    $('#BtnSubmit').css('display','none');
                    //return false;
                }else{
                    return false;
                }
            });

        });


    </script>
