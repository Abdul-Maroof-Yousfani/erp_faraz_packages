<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Edit Resource Type</span>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <?php echo Form::open(array('url' => 'sad/updateResourceAssigned','id'=>'cashPaymentVoucherForm'));?>
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

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Resource Type<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input autofocus type="text" class="form-control requiredField" placeholder="Resource Type" name="resource_type" id="resource_type" value="<?php echo $ResourceAssigned->resource_type ?>" />
                                            <input type="hidden" name="id" id="id" value="<?php echo $ResourceAssigned->id ?>">
                                            <input type="hidden" name="CompanyId" id="CompanyId" value="<?php echo $_GET['m']?>">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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
</div>

<script>
    $( document ).ready(function() {
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
