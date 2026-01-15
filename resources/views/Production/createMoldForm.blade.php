<?php
use App\Helpers\CommonHelper;


$m = Session::get('run_company');

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
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Mould Form</span>
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

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Mould Name</label>
                                                        <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                    <input type="text" name="MoldName" id="MoldName" class="form-control requiredField" placeholder="Mould Name" onkeyup="RemoveMsg()">
                                                </div>





                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Size</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="Size" id="Size" class="form-control requiredField" placeholder="Size" onkeyup="RemoveMsg()">
                                                </div>


                                               

                                                <div style="margin-top: 36px" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 center">
                                                    <button  type="button" class="btn btn-sm btn-success" id="BtnSave" >Save</button>
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
                        var MoldName = $('#MoldName').val();
                        var BatchCode = $('#BatchCode').val();
                        var life = $('#life').val();
                        var size = $('#Size').val();
                        $.ajax({
                            url:'{{url('/production/insert_mold')}}',
                            data:{MoldName:MoldName,BatchCode:BatchCode,life:life,size:size},
                            type:'GET',
                            success:function(response)
                            {
                                if(response == 'duplicate')
                                {
                                    $('#DuplicateError').html('<span class="text-danger">Already Exists</span>');
                                    $('.btn-success').prop('disabled',false);
                                }
                                else if(response == 'yes')
                                {
                                    $('.btn-success').prop('disabled',false);
                                    $('#SuccessMsg').html('Recored Saved Successfully.');
                                    $('#MoldName').val('');
                                    $('#Size').val('');
                                }
                                else{}
                            }
                        });
                        $('.btn-success').prop('disabled',true);

                    }else{
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