<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Edit Region</span>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <?php echo Form::open(array('url' => 'pad/updateRegionDetail','id'=>'updateRegionDetail'));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Region Code<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input autofocus type="text" class="form-control requiredField" placeholder="REGION CODE" name="region_code" id="region_code" value="<?php echo $Region->region_code ?>" />
                                            <input type="hidden" name="edit_id" id="edit_id" value="<?php echo $Region->id ?>">
                                            <input type="hidden" name="CompanyId" id="CompanyId" value="<?php echo $_GET['m']?>">
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Region Name<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input autofocus type="text" class="form-control requiredField" placeholder="REGION NAME" name="region_name" id="region_name" value="<?php echo $Region->region_name ?>" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Cluster<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <select name="cluster_id" id="cluster_id" class="form-control requiredField ">
                                                <option value="">Select Cluster</option>
                                                <?php foreach($Cluster as $Fil):?>
                                                <option value="<?php echo $Fil->id?>" <?php if($Region->cluster_id == $Fil->id){echo "selected";}?>><?php echo $Fil->cluster_name;?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
    $( document ).ready(function() {
        $(".btn-primary").click(function(e){
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
