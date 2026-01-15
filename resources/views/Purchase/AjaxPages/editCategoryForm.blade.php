<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
$id = $_GET['id'];
$categoryDetail = App\Models\Category::where('id', '=', $id)
    ->first(['main_ic','acc_id']);
?>
<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="subHeadingLabelClass">Edit Category Detail</span>
        </div>
    </div>
    <div class="lineHeight">&nbsp;</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <?php echo Form::open(array('url' => 'pad/editCategoryDetail?m='.$m.'','id'=>'editCategoryDetail'));?>
                        <input type="hidden" readonly name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" readonly name="pageType" value="<?php echo 'edit'?>">
                        <input type="hidden" readonly name="parentCode" value="<?php echo '000'?>">
                        <input type="hidden" readonly name="recordId" id="recordId" class="form-control" value="<?php echo $id?>" />
                        <input type="hidden" readonly name="recordAccId" id="recordAccId" class="form-control" value="<?php echo $categoryDetail->acc_id?>" />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Category Name :</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="category_name" id="category_name" value="<?php echo $categoryDetail->main_ic?>" class="form-control requiredField" />
                        </div>
                        <div>&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{ Form::button('Submit', ['class' => 'btn btn-success btn-success-edit']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        </div>
                        <?php
                        echo Form::close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-success-edit").click(function(e){
            var Category = new Array();
            var val;
            Category.push($(this).val());
            var _token = $("input[name='_token']").val();
            for (val of Category) {
                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
            formSubmitOne(e);
        });


        function formSubmitOne(e){

            var postData = $('#editCategoryDetail').serializeArray();
            var formURL = $('#editCategoryDetail').attr("action");
            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data){
                    $('#showMasterTableEditModel').modal('toggle');
                    viewCategoryList();
                }
            });
        }
    });
</script>