<?php

$m = Session::get('run_company');
$EditId = $_GET['edit_id'];
$Dai= DB::Connection('mysql2')->table('production_dai')->where('status',1)->where('id',$EditId)->first();
?>
@extends('layouts.default')
@section('content')

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
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Die Form</span>
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


                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Die Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                    <input type="text" name="DaiName" id="DaiName" class="form-control requiredField" placeholder="Die Name" onkeyup="RemoveMsg()" value="<?php echo $Dai->dai_name?>">
                                                    <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $EditId?>">
                                                </div>



                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Size</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="Size" id="Size" class="form-control requiredField" placeholder="Size" onkeyup="RemoveMsg()" value="<?php echo $Dai->size?>">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <button  type="button" class="btn btn-sm btn-success" id="BtnSave" style="margin: 30px 0px 0px 0px">Update</button>
                                                </div>

                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

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

                    if(validate == 0)
                    {
                        var DaiName = $('#DaiName').val();
                        var BatchCode = $('#BatchCode').val();
                        var Size = $('#Size').val();
                        var life = $('#life').val();
                        var edit_id = $('#edit_id').val();

                        $.ajax({
                            url:'{{url('/production/update_dai')}}',
                            data:{DaiName:DaiName,BatchCode:BatchCode,Size:Size,life:life,edit_id:edit_id},
                            type:'GET',
                            success:function(response)
                            {
                                if(response == 'duplicate')
                                {
                                    $('#DuplicateError').html('<span class="text-danger">Already Exists.</span>');
                                    $('.btn-success').prop('disabled',false);
                                }
                                else if(response == 'yes')
                                {
                                    $('.btn-success').prop('disabled',false);
                                    $('#SuccessMsg').html('Recored Update Successfully.');
                                    window.location.href = "<?php echo URL::asset('production/daiList');?>";
                                }
                                else{}
                            }
                        });
                        $('.btn-success').prop('disabled',true);
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

    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection