@extends('layouts.default')
@section('content')








    {{--<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    @include('select2')






    <?php
    use App\Helpers\CommonHelper;

    $accType = Auth::user()->acc_type;
    if($accType == 'client'){
        $m = $_GET['m'];
    }else{
        $m = Auth::user()->company_id;
    }
    ?>
    <style>
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit New Master Item</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <?php    ?>
                                    <div class="alert alert-success" role="alert">
                                        <h3 style="text-align: center">   @if (session('message'))  {{ session('message') }} @endif</h3>
                                    </div>


                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pad/update_item_master?m='.$m.'','id'=>'addCategoryForm'));?>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Category</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select onchange="get_sub_category(this.id,'sub_category')" name="" id="CategoryId" class="form-control select2" disabled>
                                                        <option value="">Select Category</option>
                                                        <?php foreach(CommonHelper::get_all_category() as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>" <?php if($ItemMaster->category_id == $Fil->id){echo "selected";}?>><?php echo $Fil->main_ic?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <input type="hidden" name="CategoryId" value="<?php echo $ItemMaster->category_id?>">
                                                </div>
                                                    <input type="hidden" name="EditId" value="<?php echo $ItemMaster->id?>">

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Sub Category Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select id="sub_category" name="" class="form-control select2" onchange="CheckItemMasterCode()" disabled>
                                                        <?php $SubCategory = DB::Connection('mysql2')->table('sub_category')->where('status',1)->where('category_id',$ItemMaster->category_id)->get();
                                                        foreach($SubCategory as $subcat):
                                                        ?>
                                                            <option value="<?php echo $subcat->id?>" <?php if($ItemMaster->sub_category_id == $subcat->id){echo "selected";}?>><?php echo $subcat->sub_category_name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <input type="hidden" name="sub_category" value="<?php echo $ItemMaster->sub_category_id?>">
                                                </div>



                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Item Master Code </label>
                                                    <span class="rflabelsteric"><strong>*</strong><span id="Generated" class="text-success blink_me" style="float: right; display: none">Code Already Generated.</span></span>
                                                    <input type="text" name="code" data-mask="00/00/0000" class="mixed form-control" data-mask-reverse="true" value="<?php echo $ItemMaster->item_master_code?>" />

                                                </div>




                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

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
    <script>


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
                        $('.btn-success').prop('disabled',true);
                        $("form").submit();

                    }else{
                        return false;
                    }
                }
            });
        });
        //  $('.input').mask('AAA 000-S0S');
        // $(":input").inputmask();

        function CheckItemMasterCode()
        {
            var category= $('#CategoryId').val();
            var sub_category = $('#sub_category').val();
            if(category > 0 && sub_category >0)
            {
                $.ajax({
                    url: '/sdc/check_item_master_code',
                    type: 'Get',
                    data: {category: category,sub_category:sub_category},
                    success: function (response) {
                        if(response != "")
                        {
                            $('#Generated').css('display','block');
                            $('.mixed').val(response);
                        }
                        else
                        {
                            $('#Generated').css('display','none');
                            $('.mixed').val('');
                        }

                    }
                });
            }

        }

        $(document).ready(function() {
            $('.select2').select2();
            $('.mixed').mask('AAAA-AAA-AAA-AAA');
        });





    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection