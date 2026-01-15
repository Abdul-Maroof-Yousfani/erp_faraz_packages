<?php
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
    <div class="well" xmlns="http://www.w3.org/1999/html">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add Tax Section</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'fad/addTaxSectionDetail?m='.$m.'','id'=>'addTaxSectionDetail'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="addTaxSectionForm[]" class="form-control" id="addTaxSectionForm" value="1" />
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Section</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input name="Section" id="Section" placeholder="SECTION" class="form-control requiredField" autocomplete="off"/>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax Payment Nature</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input name="TaxPaymentNature" id="TaxPaymentNature" placeholder="TAX PAYMENT NATURE" class="form-control requiredField" autocomplete="off"/>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Code</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input name="Code" id="Code" placeholder="CODE" class="form-control requiredField" autocomplete="off"/>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                            <label>RATE (%)</label>
                                                            {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                                            <input name="Rate" id="Rate" placeholder="RATE (%)" class="form-control" autocomplete="off"/>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Tax Payment Section</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <textarea name="TaxPaymentSection" id="TaxPaymentSection" placeholder="TAX PAYMENT SECTION....." class="form-control requiredField" cols="8" style="resize: none;" autocomplete="off"/></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
    <script>
        $(document).ready(function() {





            $(".btn-success").click(function(e){
                var AddSection = new Array();
                var val;
                $("input[name='addTaxSectionForm[]']").each(function(){
                    AddSection.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of AddSection) {

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
    <script type="text/javascript">

        $('#account_id').select2();
    </script>

    <script>
        function get_nature_type()
        {
            var nature=  $("#account_id option:selected").text();
            nature=nature.split('-');
            nature=nature[0];
            if (nature==1 ||  nature==4)
            {
                $('#o_blnc_trans').val(1);
            }

            else
            {
                $('#o_blnc_trans').val(0);
            }
        }
    </script>
@endsection