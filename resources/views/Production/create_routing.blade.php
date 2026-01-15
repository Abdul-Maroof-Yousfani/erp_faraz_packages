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
        .container{
            display: flex;
            column-gap: 20px;
            justify-content: space-between;
        }
        .element {
            background: lightgrey;
            height: 100px;
            width: 25%;
            text-align: center;
        }
    </style>
    <?php echo Form::open(array('url' => 'prad/add_route','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <span class="subHeadingLabelClass">Create Routing</span>
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


                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label> Routing Code </label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                       <input type="text" readonly  value="{{ProductionHelper::get_unique_code_for_routing()}}" class="form-control"/>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                            <label>Finish Goods</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select onchange="get_operation_data()" name="finish_goods" id="finish_goods" class="form-control select2 requiredField">
                                                <option value="">Select Finish Good</option>
                                                <?php foreach($data as $row):?>
                                                <option value="<?php echo $row->finish_good_id?>" class=""><?php echo $row->sub_ic?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>

                                        <div>&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ajax">
                                            </div>



                                        <div>&nbsp;</div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <button  type="submit" class="btn btn-success" id="BtnSave" >Save</button>
                                            <button onclick="resett()" type="button" class="btn btn-danger" id="BtnSave" >Reset</button>
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


        function get_operation_data()
        {
            var finish_goods=$('#finish_goods').val();
            $('#ajax').html('<div class="loader"></div>');
            $.ajax({
                url:'{{url('/production/get_operation_data')}}',
                data:{finish_goods:finish_goods},
                type:'GET',
                success:function(response)
                {
                    $('#ajax').html(response);
                    orderby=0;
                }
            });
        }
        var orderby=0;
        function set_rout(count)
        {

            if ($('#orderby'+count).is(":checked"))
            {
             
                orderby++;
                $('#cls_counter'+count).html(orderby);
                $('#orderby'+count).val(orderby);
                $('.orderby'+count).val(orderby);
                $('#orderby'+count).attr("disabled",true);
            }


        }

        function resett()
        {

            $('.checbox').prop('checked', false);
            $('.checbox').prop("disabled", false);
            $('.span').text('');
            orderby=0;
            $('.checbox').val(0);
            $('.order').val(0);
        }


        $( "form" ).submit(function( event ) {
            $('.checbox').prop("disabled", false);
        });
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection