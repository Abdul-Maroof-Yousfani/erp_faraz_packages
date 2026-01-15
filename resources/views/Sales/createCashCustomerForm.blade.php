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
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Cash Customer</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'sad/addCashCustomerDetail?m='.$m.'','id'=>'addCashCustomerForm'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Account Head :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select onchange="get_nature_type()" name="account_head" id="account_id" class="form-control requiredField select2">
                                                        <option value="">Select Account</option>
                                                        @foreach($accounts as $key => $y)
                                                            <option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Customer Name :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="customer_name" id="customer_name" value="" class="form-control requiredField" />
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label>Country :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select name="country" id="country" class="form-control requiredField">
                                                                        <option value="">Select Country :</option>
                                                                        @foreach($countries as $key => $y)
                                                                            <option value="{{ $y->id}}">{{ $y->nicename}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label>State :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select name="state" id="state" class="form-control requiredField">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label>City :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select name="city" id="city" class="form-control requiredField">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Contact No :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="contact_no" id="contact_no" value="" class="form-control requiredField" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Email :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="email" name="email" id="email" value="" class="form-control requiredField" />
                                                        </div>


                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Address :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <textarea class="form-control requiredField" name="address" style="resize: none" cols="80" rows="2"></textarea>
                                                        </div>




                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>NTN :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="ntn" id="strn" value="" class="form-control requiredField" />
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>STRN :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="strn" id="strn" value="" class="form-control requiredField" />
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="o_blnc" >Opening Balance :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="number" name="o_blnc" maxlength="15" min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="" autocomplete="off"/>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="o_blnc_trans">Transaction :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
                                                                <option value="">select</option>
                                                                <option value="1"><strong>Debit</strong></option>
                                                                <option value="0"><strong>Credit</strong></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

                                                    <?php
                                                    //echo Form::submit('Click Me!');
                                                    ?>
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
            $('.select2').select2();
            $('#country').select2();
            $('#state').select2();
            $('#city').select2();
            $(".btn-success").click(function(e)
            {
                var cashCustomer = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                cashCustomer.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of cashCustomer) {

                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
            });

            $('select[name="country"]').on('change', function() {
                var countryID = $(this).val();
                if(countryID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                        type: "GET",
                        data: { id:countryID},
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="state"]').empty();
                            $('select[name="state"]').html(data);
                        }
                    });
                }else{
                    $('select[name="state"]').empty();
                    $('select[name="city"]').empty();
                }
            });

            $('select[name="state"]').on('change', function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                        type: "GET",
                        data: { id:stateID},
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').html(data);
                        }
                    });
                }else{
                    $('select[name="city"]').empty();
                }
            });
        });

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
    <script src="{{URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection