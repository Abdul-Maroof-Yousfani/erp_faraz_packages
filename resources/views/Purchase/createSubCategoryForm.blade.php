<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2');
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
              
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Sub Category</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pad/addSubCategoryDetail?m='.$m.'','id'=>'addCategoryForm'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label>Category</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select name="CategoryId" id="CategoryId" class="form-control select2 requiredField">
                                                        <option value="">Select Category</option>
                                                        <?php foreach($categories as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->main_ic?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label>Sub Category Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="SubCategoryName" id="SubCategoryName" value="" class="form-control requiredField" onkeyup="clearText();" />
                                                    <span id="DuplicateMsg"></span>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <button type="submit" class="btn btn-success" id="BtnSub">Submit</button>
                                                    <button  type="reset" id="reset" class="btn btn-danger">Clear Form</button>

                                                    <?php
                                                    //echo Form::submit('Click Me!');
                                                    ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        var flag;
        $(document).ready(function() {
            $(".btn-success").click(function(e){
                var category = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                category.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of category) {

                    jqueryValidationCustom();

                    if(validate == 0){
                        $('#BtnSub').prop('disabled',true);
                        $("form").submit();
//                        $('#BtnSub').prop('type','button');
//                        var SubCategoryName = $('#SubCategoryName').val();
//                        var CategoryId = $('#CategoryId').val();
//                        $.ajax({
//                            url: '/pdc/checkDuplicateSubCategory',
//                            type: 'Get',
//                            data: {SubCategoryName: SubCategoryName,CategoryId:CategoryId},
//
//                            success: function (response)
//                            {
//                                if(response > 0)
//                                {
//                                    $('#DuplicateMsg').html('<p class="text-danger">'+SubCategoryName+' Already exist.</p>');
//                                    //$('#BtnSub').prop('type','button');
//                                }
//                                else
//                                {
//                                    $('#BtnSub').prop('type','submit');
//                                    $('#addCategoryForm').submit();
//                                }
//                            }
//                        });

                    }else{
                        return false;
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">

//        $('#addCategoryForm').on('keyup keypress', function(e) {
//            var keyCode = e.keyCode || e.which;
//            if (keyCode === 13) {
//                e.preventDefault();
//                return false;
//            }
//        });
        function clearText()
        {
            $('#DuplicateMsg').html('');
        }

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection