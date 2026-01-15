<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')
    @include('select2')

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
    <?php echo Form::open(array('url' => 'prad/insert_operation_detail','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
    <div class="well_N">
        <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Operation</span>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                    <p id="SuccessMsg" class="text-success" style="font-size: 20px;"></p>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                <input type="hidden" name="m" value="<?php echo $m?>">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Operation Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select onchange="get_data()" name="finish_goods" id="finish_goods" class="form-control select2 requiredField">
                                                        <option value="">Select Finish Good</option>
                                                        <?php foreach(CommonHelper::get_finish_goods(1) as $row):
                                                        $count= ProductionHelper::check_product_id('production_work_order',$row->id,'finish_good_id');
                                                        if ($count==0):
                                                        ?>
                                                        <option value="<?php echo $row->id?>" class="abc EnDis<?php echo $row->id?>"><?php echo $row->sub_ic?></option>
                                                        <?php endif; endforeach;?>
                                                    </select>
                                                </div>
                                                
                                                <div>&nbsp;</div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxDataHere">

                                                </div>

                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <button  type="submit" class="btn btn-sm btn-success" id="BtnSave" >Save</button>
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
    <?php echo Form::close();?>
    <script>


        $(document).ready(function() {
            $('#finish_goods').select2();
            $(".btn-success").click(function(e){
                var category = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                category.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of category) {

                    jqueryValidationCustom();

                    if(validate == 0)
                    {

                    }
                    else
                    {
                        return false;
                    }
                }
            });
        });

        function RemoveMsg()
        {
            $('#DuplicateError').html('');
            $('#SuccessMsg').html('');
        }


        function get_data()
        {
            var finish_goods=$('#finish_goods').val();
            if(finish_goods !="")
            {
                $('#AjaxDataHere').html('<div class="loader"></div>');
                $.ajax({
                    url:'{{url('/production/get_machine_by_finish_good')}}',
                    data:{finish_goods:finish_goods},
                    type:'GET',
                    success:function(response)
                    {
                        $('#AjaxDataHere').html(response);
                    }
                });
            }
            else{
                $('#AjaxDataHere').html(response);
            }

        }
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection