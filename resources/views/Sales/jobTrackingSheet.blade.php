<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="stage2">  </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <span class="subHeadingLabelClass">JOB TRACKING SHEETS </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center"></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                                        Job Survey <input type="radio" onclick="jobTracking(1)" class="form-control" name="radio" id="radio" value="job Survey">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                                        Job Order <input type="radio"  onclick="jobTracking(2)" class="form-control" name="radio" id="radio" value="job order">
                                    </div>
                                    <input type="hidden" id="xval" name="xval" value="">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader">
                        </div>
                    </div>


                    <div class="row" id="jobtracking">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
                                <label class="sf-label">Job Survey #</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control select2" name="job_tracking_no" id="job_tracking_no"  required="required">
                                    <option value="">Select Survey No</option>
                                    <?php foreach($survey as $Filter2):?>
                                    <option value="<?php echo $Filter2['tracking_no']?>"><?php echo $Filter2['tracking_no']?></option>
                                    <?php endforeach;?>
                                </select>
                                <span id="TrackNoError"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                        </div>
                    </div>

                    <div class="row" id="joborder">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Job order #</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control select2" name="job_order" id="job_order"  required="required">
                                    <option value="">Select Job Order No</option>
                                    <?php $job_order = CommonHelper::job_order(); ?>
                                </select>
                                <span id="TrackNoError"></span>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <button type="button" class="btn btn-sm btn-primary" id="BtnShow" style="margin: 33px 0px 0px 0px;" onclick="GetTrackingSheet();">Show</button>
                    </div>

                    <?php echo Form::open(array('url' => 'pad/addJobTrackingDetails?m='.$m.'','id'=>'formid'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span id="ShowHide"></span>
                    <span id="ShowHide2"></span>
                    <?php echo Form::close();?>


                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $("#joborder").hide();
            $("#jobtracking").hide();
        });

        function jobTracking(x)
        {
            if(x==1){
                $("#jobtracking").show();
                $("#joborder").hide();
                $("#xval").val(1);
                $('#ShowHide').html('');
            } else{
                $("#joborder").show();
                $("#jobtracking").hide();
                $("#xval").val(2);
                $('#ShowHide').html('');
            }
            return x;
        }

        function GetTrackingSheet() {

            x = $("#xval").val();
            if(x==2){
                var TrackNo = $('#job_order').val();
            } else {
                var TrackNo = $('#job_tracking_no').val();
            }

            var m = '<?php echo $_GET['m'];?>';

            $('#TrackNoError').html('');
            $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/sdc/getTrackingSheet',
                type: "GET",
                data: {TrackNo:TrackNo, x:x, m:m},
                success:function(data) {
//                        alert(data); return false;
                    $('#ShowHide').html('');
                    $('#ShowHide').html(data);
                }
            });
        }

        $("#formid").submit(function(event){
            event.preventDefault();
            if (confirm("Want to Add Data...?")) {
                $('#ShowHide').css('display','none');
                $('#ShowHide2').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                var post_url = $(this).attr("action"); //get form action url
                var form_data = $(this).serialize(); //Encode form elements for submission
                $.ajax({
                    url: post_url,
                    type: "POST",
                    data: form_data,
                    success: function (data) {
                        console.log(data);
                        $('#ShowHide').css('display','block');
                        $('#ShowHide2').html('');
                    }
                });

            } else {
                alert("You didn't submit the form");
            }
        });

    </script>
@endsection