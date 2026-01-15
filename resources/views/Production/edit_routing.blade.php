<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


$m = Session::get('run_company');
        $EditId = $_GET['edit_id'];
        $Master = DB::Connection('mysql2')->table('production_route')->where('id',$EditId)->first();
        $Detail = DB::Connection('mysql2')->table('production_route_data')->where('master_id',$EditId)->orderBy('id','ASC')->get();
        $OrderByCounter = DB::Connection('mysql2')->table('production_route_data')->where('master_id',$EditId)->where('orderby','!=',0)->count();
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
    <?php echo Form::open(array('url' => 'prad/update_route','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
    <div class="well">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <span class="subHeadingLabelClass">Edit Create Routing</span>
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
                                        <input type="hidden" name="m" value="<?php echo $m?>">
                                        <input type="hidden" name="EditId" value="<?php echo $EditId?>">


                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label> Routing Code </label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" readonly  value="<?php echo $Master->voucher_no?>" class="form-control"/>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                            <label>Finish Goods</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select onchange="get_operation_data()" name="finish_goods" id="finish_goods" class="form-control select2 requiredField" disabled>
                                                <option value="">Select Finish Good</option>
                                                <?php foreach($data as $row):?>
                                                <option value="<?php echo $row->finish_good_id?>" <?php if($Master->finish_goods == $row->finish_good_id): echo "selected"; endif;?> class=""><?php echo $row->sub_ic?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>

                                        <div>&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ajax">
                                            <div class="row">
                                                <?php
                                                $count=1;
                                                foreach ($Detail as $row):
                                                    $Machine = DB::Connection('mysql2')->table('production_machine')->where('status',1)->where('id',$row->machine_id)->select('machine_name')->first();
                                                ?>

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                                                    <fieldset style="border: solid 1px #b5afaf; border-radius: 10px; ">
                                                        <h4 class="well"><?php echo $Machine->machine_name ?> <span id="cls_counter<?php echo $count ?>" class="badge badge-secondary span"><?php echo $row->orderby?></span> </h4>
                                                        <input name="orderby[]" onclick="set_rout('<?php echo $count ?>')" id="orderby<?php echo $count ?>"  type="checkbox" class="form-control checbox" @if($row->orderby > 0) checked disabled @endif >
                                                        <input type="hidden" name="orderbyy[]" class="orderby<?php echo $count ?> order" value="<?php echo $row->orderby?>"/>
                                                        <input type="hidden" name="machine[]" value="<?php echo $row->machine_id ?>"/>
                                                        <input type="hidden" name="operation_data_id[]" value="<?php echo $row->operation_data_id ?>"/>
                                                        <input type="hidden" name="operation_id" value="<?php echo $Master->operation_id ?>"/>
                                                        <input type="hidden" name="detailed_id[]" value="<?php echo $row->id?>">
                                                    </fieldset>
                                                </div>
                                                <?php
                                                if($count == 3 || $count == 6 || $count == 9 || $count== 12 || $count == 15):?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                                                <?php endif;?>

                                                <?php $count++; endforeach;?>
                                            </div>
                                        </div>



                                        <div>&nbsp;</div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <button  type="submit" class="btn btn-sm btn-success" id="BtnSave" >Save</button>
                                            <button onclick="resett()" type="button" class="btn btn-sm btn-primary" id="BtnSave" >Reset</button>
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
        var orderby = '<?php echo $OrderByCounter?>';
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