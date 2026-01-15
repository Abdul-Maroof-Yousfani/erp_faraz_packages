<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <span class="subHeadingLabelClass">Add New Client</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'sad/addClientDetail?m='.$m.'','id'=>'addClientDetail'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Client Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="client_name" id="client_name" value="" class="form-control requiredField" placeholder="Client Name" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>NTN:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="ntn" id="ntn" class="form-control requiredField" placeholder="ENTER NTN" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>STRN:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="strn" id="strn" class="form-control requiredField" placeholder="Enter STRN" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Address:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <textarea name="address" id="address" class="form-control requiredField"></textarea>
                                                        </div>
                                                    </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
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
    <script type="text/javascript">
        $(document).ready(function() {

            $(".btn-success").click(function(e){
                var subItem = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                subItem.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of subItem) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //return false;
                    }else{
                        return false;
                    }
                }
            });
        });
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection