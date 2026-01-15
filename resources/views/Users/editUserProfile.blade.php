@extends('layouts.default')
@section('content')
<div class="panel-body well_N">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit User Profile</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>


                <div class="panel">
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::open(array('url' => 'uad/editUserPasswordDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <h3 class="text-center">Change Password</h3>
                                <label>Password</label>
                                <input class="form-control" name="password" id="password" value="" required onkeyup="checkPassword()">
                                <label>Confirm Password</label>
                                <input class="form-control" name="confirm_password" id="confirm_password" required onkeyup="checkPassword()" value="">
                                <span id="pass_message"></span>
                                <br>
                                <button type="submit" class="btn btn-success text-center">Update</button>
                                <?php echo Form::close();?>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
                                <?php echo Form::open(array('url' => 'uad/editApprovalCodeDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="emr_no" value="<?=Auth::user()->emr_no?>">
                                <h3 class="text-center">Change Approval Code</h3>
                                <label>Code</label>
                                <input class="form-control" name="approval_code" id="approval_code" value="">
                                <br>
                                <button type="submit" class="btn btn-success text-center">Update</button>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>

            </div>
        </div>
    </div>
</div>

    <script>
        <?php if(Auth::user()->password_status == 1):?>
            $(".hideforPassChange").html('');
        <?php endif;?>
        $(document).ready(function() {


            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var lifeInsurance = new Array();
                var val;
                $("input[name='lifeInsuranceSection[]']").each(function(){
                    lifeInsurance.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of lifeInsurance) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

        });

    function checkPassword() {
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

      if(confirm_password != '')
      {
            if(password != confirm_password)
            {
                $("#pass_message").html('<span style="color:red;">Password Don,t Match ! </span>');
                $(".btn-success").attr("disabled","disabled");
            }
            else
                {
                    $("#pass_message").html('<span style="color:green;">Password Matched ! </span>');
                    $(".btn-success").removeAttr("disabled")
                }
        }
    }
    </script>
@endsection
