<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;



$m = Session::get('run_company');
$EditId = $_GET['edit_id'];

$WordOrder = DB::Connection('mysql2')->table('production_work_order')->where('id',$EditId)->first();
$WordOrderData = DB::Connection('mysql2')->table('production_work_order_data')->where('master_id',$EditId)->orderBy('id','ASC')->get();
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style xmlns="http://www.w3.org/1999/html">
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
    <?php echo Form::open(array('url' => 'prad/update_operation_detail','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
    <div class="well">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <span class="subHeadingLabelClass">Edit Operation Form</span>
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

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Operation Name</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select onchange="get_data()" name="finish_goods" id="finish_goods" class="form-control select2 requiredField" disabled>
                                                <option value="">Select Finish Good</option>
                                                <?php foreach(CommonHelper::get_finish_goods(1) as $Fil):?>
                                                <option value="<?php echo $Fil->id?>" class="abc EnDis<?php echo $Fil->id?>" <?php if($WordOrder->finish_good_id == $Fil->id): echo "selected"; endif;?>><?php echo $Fil->sub_ic?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>

                                        <div>&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxDataHere">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <button style="float: right" type="button" class="btn btn-primary text-right hide" onclick="GetData()">Check</button>

                                                    <table id="myTable1" class="table table-bordered table-striped table-condensed tableMargin">
                                                        <thead>
                                                        <tr>

                                                            <th class="text-center">Machine Name</th>
                                                            <th class="text-center">Capacity(%)</th>
                                                            <th class="text-center">Qeue Time (Second)</th>
                                                            <th class="text-center">Move Time (Second)</th>
                                                            <th class="text-center">Wait Time (Second)</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="AppendOtherRow">
                                                        <?php
                                                        $Counter =0;
                                                        foreach($WordOrderData as $Fil):
                                                        $Counter++;


                                                        $str_time = $Fil->wait_time;
                                                        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                                        $wait_second = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;


                                                        $str_time = $Fil->move_time;
                                                        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                                        $move_time_second = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;


                                                        $str_time = $Fil->que_time;
                                                        sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                                        $que_second = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;


                                                        ?>
                                                        <tr class="text-center">

                                                            <td>
                                                                <?php echo strtoupper(ProductionHelper::get_machine_name($Fil->machine_id));?>
                                                                <input type="hidden" id="machine_id" name="machine_id[]" value="<?php echo $Fil->machine_id?>">
                                                                    <input type="hidden" name="detail_id[]" value="<?php echo $Fil->id?>">
                                                            </td>
                                                            <?php

                                                            $machnes[]=$Fil->machine_id;
                                                            ?>
                                                            <td>
                                                                <input type="number" class="form-control requiredField" name="capacity[]" id="capacity<?php echo $Counter?>" value="<?php echo $Fil->capacity?>">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control requiredField" name="que_time[]" id="que_time<?php echo $Counter?>"
                                                                          value="<?php echo $que_second?>">
                                                            </td>
                                                            <td>
                                                                <input  type="number" class="form-control requiredField" name="move_time[]" id="move_time<?php echo $Counter?>"
                                                                        value="<?php echo $move_time_second?>">
                                                            </td>
                                                            <td>
                                                                <input  type="text" class="form-control requiredField" name="wait_time[]" id="wait_time<?php echo $Counter?>"
                                                                        value="<?php echo $wait_second?>">
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;

                                                                $machnes=implode(',',$machnes);
                                                        ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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
                    {{--Checking MAchine ajax--}}
                    <span id="AjaxDataHereOther"></span>

                    {{--Checking MAchine ajax--}}
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

        function GetData()
        {
            var FinishGoodId = $('#finish_goods').val();
            var Counter = '<?php echo $Counter?>';
            var array='{{$machnes}}';

            if(FinishGoodId !="")
            {
                $('#FinishGoodError').html('');
                $('#AjaxDataHereOther').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/production/get_machine_data_by_finish_good_for_operation',
                    type: 'GET',
                    data: {FinishGoodId: FinishGoodId,array:array,Counter:Counter},
                    success: function (response)
                    {
                        $('#AjaxDataHereOther').html(response);
                        $('.other_attach').remove();
                    }
                });
            }
            else
            {
                $('#FinishGoodError').html('<p class="text-danger">Select Finish Good...!</p>');
            }


        }
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection