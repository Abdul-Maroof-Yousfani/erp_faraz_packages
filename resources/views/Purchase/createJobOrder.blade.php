<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

?>


@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <style>
        .select2-container {
            font-size: 11px;
        }
    </style>


    <div class="row" id="job_order_next_step"></div>
    <div class="row" id="DirectJobOrderArea">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">JOB ORDER</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="">
                            <label><input type="radio" name="FormType" id="FormType1" value="1" class="text-left" onclick="get_form(0)"> Direct Job Order </label>&nbsp;
                            <label><input type="radio" name="FormType" id="FormType2" value="2" class="text-right" onclick="job_order_process('FormType2','quotation','survey')">Through  Quotation</label>
                            {{--<label><input type="radio" name="FormType" id="FormType3" value="3" class="text-right" onclick="job_order_process('FormType3','survey','quotation')">Through Survey </label>--}}
                        </div>


                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 quotation" style="display: none">
                            <label class="sf-label">Quotation: <span class="rflabelsteric"><strong>*</strong></span></label>
                            <select onchange="get_form(1)" style="width: 100%" class="form-control  select2" name="quotation" id="quotation" >
                                <option value="">---Select---</option>
                                @foreach($quotation as $row)
                                    <option value="{{$row->id}}">{{ucwords($row->quotation_no)}}</option>
                                @endforeach
                            </select>


                        </div>
                        {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 survey" style="display: none">--}}
                            {{--<label class="sf-label">Survey: <span class="rflabelsteric"><strong>*</strong></span></label>--}}
                            {{--<select style="width: 100%" class="form-control  select2" name="survey" id="survey" >--}}
                                {{--<option value="">---Select---</option>--}}
                                {{--@foreach($survey as $row)--}}
                                    {{--<option value="{{$row->id}}">{{ucwords($row->tracking_no)}}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    </div>

                </div>


               <span id="data">

               </span>

            </div>
        </div>
    </div>
        <script type="text/javascript">
            $( document ).ready(function()
            {
             $('#quotation').select2();
            });


            function get_form(type)
            {
                var id=0;

                if (type=='1')
                {
                    id= $('#quotation').val();


                }
                $('.quotation').css('display','none');
                var m='{{$_GET['m']}}';
                $.ajax({
                    url : '{{'/pdc/get_job_order'}}',
                    type: 'Get',
                    data: {m:m,type:type,id:id},
                    beforeSend: function()
                    {

                        $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    },
                }).done(function(response){ //

                    $("#data").html(response);
                });
            }

            function job_order_process(id,cls,disable)
            {
                if ($("#"+id).is(":checked"))
                {

                    $("."+cls).css("display", "block");
                    $("."+disable).css("display", "none");
                    $('#data').html('');
                }
                else
                {
                    $("."+cls).css("display", "none");
                }
            }
</script>


@endsection

