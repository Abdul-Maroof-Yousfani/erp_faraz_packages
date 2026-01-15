<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');


use App\Helpers\PurchaseHelper;
?>
@extends('layouts.default')

@section('content')




    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/site.css')}}">
    <link rel="stylesheet" href="{{url('/richtext.min.css')}}">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{url('/jquery.richtext.js')}}"></script>
    @include('select2')
    @include('number_formate')
    {{Form::open(array('url'=>'sad/addQuotation'))}}

    <div class="">
        <div class="">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="stage2">  </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span class="subHeadingLabelClass">Quotation</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 well">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <label class="sf-label">Survey/Job Tracking #</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control select2" name="tracking_no" id="tracking_no"   required="required">
                                            <option value="">Select Tracking No</option>
                                            <?php foreach($survey as $Filter2):?>
                                            <option value="<?php echo $Filter2['survey_id']?>"><?php echo $Filter2['tracking_no']?></option>
                                            <?php endforeach;?>
                                            <option value="direct">Direct Quotation</option>
                                        </select>
                                        <span id="TrackNoError"></span>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button type="button" class="btn btn-sm btn-success" id="BtnShow" style="margin: 33px 0px 0px 0px;"  onclick="GetTrackingSheet();">Show</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span id="ShowHide"></span>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>
    {{Form::close()}}
    <script src="<?php echo URL('/assets/textEditor')?>/editor.js"></script>
    <script>
        $(document).ready(function()
        {

            $('.content').richText();

            $("#txtEditor").Editor();
            $('#tracking_no').select2();


        });

    </script>
    <script>


        function data_get()
        {

            var client_data= $('#client').val();

            client_data=client_data.split('*');
            $('#ntn_no').val(client_data[1]);
            $('#strn_no').val(client_data[2]);
            $('#address').val(client_data[3]);
        }


        function GetTrackingSheet() {

            var TrackNo = $('#tracking_no').val();
            var m = '<?php echo $_GET['m'];?>';
            if(TrackNo !="")
            {
                $('#TrackNoError').html('');
                $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/sdc/getQuatationForm',
                    type: "GET",
                    data: { TrackNo:TrackNo,m:m},
                    success:function(data) {
//                        alert(data); return false;
                        $('#ShowHide').html('');
                        $('#ShowHide').html(data);
                    }
                });
            }
            else{
                $('#TrackNoError').html('<p class="text-danger">Please Select Tracking #</p>');
            }

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
                        $('#ShowHide').css('display','block');
                        $('#ShowHide2').html('');

                    }
                });

            } else {
                alert("You didn't submit the form");
            }
        });

    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection
