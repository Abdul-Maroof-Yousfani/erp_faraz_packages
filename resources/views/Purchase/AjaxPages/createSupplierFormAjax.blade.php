<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>


    @include('select2');
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Vendors</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pad/addSupplierDetail?m='.$m.'','id'=>''));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                                            <label>Account Head :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select   name="account_head" id="account_head"  class="form-control requiredField">



                                                                @foreach($accounts as $key => $y)
                                                                    <option @if($y->code=='2-1-2-2') selected @endif value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input name="name" id="name" type="text" class="form-control requiredField">
                                                        </div>


                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Company Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input autofocus type="text" name="company_name" id="company_name" value="" class="form-control requiredField" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label>Country :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select   name="country" id="country" class="form-control requiredField">
                                                                        <option value="">Select Country :</option>
                                                                        @foreach($countries as $key => $y)
                                                                            <option value="{{ $y->id}}">{{ $y->name}}</option>
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
                                                                    <select  name="state" id="state" class="form-control requiredField">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <label>City :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select  name="city" id="city" class="form-control requiredField">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 input_fields_wrap">
                                                            <label>Contact No :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <button readonly  type="button" onclick="remove()" style="float: right"  class="btn btn-xs  btn-danger">Remove</button>
                                                            <button  readonly type="button" style="float: right;" class="add_field_button btn btn-xs  btn-success">Add More Fields</button>
                                                            <input  type="text" name="contact_no[]" id="contact_no" value="" class="form-control requiredField" />
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Email :</label>

                                                            <input  type="email" name="email" id="email" value="" class="form-control" />

                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 input_fields_wrap_address">
                                                            <label>Address :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <button  tabindex="-1" type="button" onclick="remove_address()" style="float: right"  class="btn btn-xs  btn-danger">Remove</button>
                                                            <button  tabindex="-1" type="button" style="float: right;" class="add_field_button_address btn btn-xs  btn-success">Add More Fields</button>
                                                            <input  type="text" name="address[]" id="address" value="" class="form-control requiredField" />
                                                        </div>

                                                    </div>
                                                </div>


                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Print Cheque As :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>


                                                            <input  type="text" name="print_check_as" id="print_check_as" value="" class="form-control" />
                                                        </div>
                                                        <!--
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Vendor Type :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select   name="vendor_type" id="vendor_type"  class="form-control requiredField">

                                                                <option value="">SELECT</option>
                                                                <option value="">{{strtoupper('outside services/professional fees')}}</option>


                                                                <option value="1">{{strtoupper('advertising/marketing/promotion')}}</option>
                                                                <option value="2">{{strtoupper('rent and occupancy related')}}</option>
                                                                <option value="3">{{strtoupper('supplies')}}</option>
                                                                <option value="4">{{strtoupper('taxes and licenses')}}</option>
                                                                <option value="5">{{strtoupper('employee fringe benefits')}}</option>
                                                                <option value="6">{{strtoupper('utilities')}}</option>
                                                                <option value="7">{{strtoupper('travel and entertainment')}}</option>
                                                                <option value="8">{{strtoupper('insurance')}}</option>
                                                                <option value="9">{{strtoupper('security')}}</option>
                                                                <option value="10">{{strtoupper('auto')}}</option>


                                                            </select>

                                                        </div>
<!-->


                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Website :</label>


                                                            <input  type="text" name="website" id="website" value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Credit Limit :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>


                                                            <input  type="text" name="credit_limit" id="credit_limit" value="0" class="form-control" />
                                                        </div>





                                                    </div>
                                                </div>



                                                <div>&nbsp;</div>

                                                <div class="well">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <span class="subHeadingLabelClass"><input id="bank_detail" type="checkbox"> Bank Details </input>  </span>
                                                        </div>

                                                        <div id="" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banks">
                                                            <div class="row">

                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 input_fields_wrap_address">
                                                                    <label>Bank Acc. No :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>

                                                                    <input  type="text" name="acc_no" id="acc_no" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 input_fields_wrap">
                                                                    <label>Bank Name :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="bank_name" id="bank_name" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 input_fields_wrap">
                                                                    <label>Branch  Name :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="branch_name" id="branch_name" value="" class="form-control required" />
                                                                </div>




                                                            </div>
                                                        </div>

                                                        <div id="" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banks">
                                                            <div class="row">

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 input_fields_wrap_address">
                                                                    <label>Bank Address :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>

                                                                    <input  type="text" name="bank_address" id="bank_address" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 input_fields_wrap">
                                                                    <label>Swift Code :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="swift_code" id="swift_code" value="" class="form-control required" />
                                                                </div>





                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>





                                                <?php   //register income criteria ?>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        tabindex="15"
                                                                        type="checkbox"
                                                                        id="regd_in_income_tax"
                                                                        name="regd_in_income_tax"
                                                                        value="1"

                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen"> Registered In Income Tax?</b>
                                                            </label>
                                                        </div>


                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="income_tax_div" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0" >
                                                                <label class="radio-inline">
                                                                    <input class=""  onclick="ntn_cnic('1')" type="radio" name="optradio" class="income" id="business" value="1">Business Individual
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input onclick="ntn_cnic('2')" type="radio" name="optradio" class="income" id="company" value="2">Company
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input onclick="ntn_cnic('3')" type="radio" name="optradio" class="income" id="aop" value="3">Aop
                                                                </label>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div id="amir" class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox">


                                                            <input style="display: none;" placeholder="NTN" type="text" name="ntn" class="form-control" id="ntn"/>

                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox">


                                                            <input style="display: none;margin-top: 15px" placeholder="CNIC" type="text" name="cnic" class="form-control" id="cnic"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php   //register income End ?>



                                                <?php   //register Sales Tax Start ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_sales_tax"
                                                                        name="regd_in_sales_tax"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In Sales Tax?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_div" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn">STRN </label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="STRN"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="strn"
                                                                       name="strn"
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php   //register SRB ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_srb"
                                                                        name="regd_in_srb"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In SRB?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_srb" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn"> SRB</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="SRB"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="srb"
                                                                       name="srb"
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php   //register SRB End ?>


                                                <?php   //register in PRA ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_pra"
                                                                        name="regd_in_pra"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In PRA?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_pra" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn"> PRA</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="PRA"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="pra"
                                                                       name="pra"
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php   //register SRB End ?>



                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">


                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Opening Date :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <input  type="date" name="open_date" id="open_date" value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="o_blnc" >Opening Balance :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="number" name="o_blnc" maxlength="15"  min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="0" autocomplete="off"/>
                                                        </div>

                                                        <!--
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="o_blnc_trans">Transaction :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
                                                                <option value="">select</option>
                                                                <option value="1"><strong>Debit</strong></option>
                                                                <option value="0"><strong>Credit</strong></option>
                                                            </select>
                                                        </div>
                                                          <!-->
                                                    </div>
                                                </div>







                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
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


        function ntn_cnic(id)
        {
            if(id==1)
            {

                $(this).prop('checked', false);
                $("#ntn").fadeIn(500);
                $("#cnic").fadeIn(500);
                $("#amir").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
                $("#amir").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
                $("#ntn").addClass("requiredField");
                $("#cnic").addClass("requiredField");
            }

            else
            {

                $("#ntn").fadeIn(500);
                $("#ntn").addClass("requiredField");
                $("#cnic").css("display", "none");
                $("#cnic").removeClass("requiredField");
                $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
                $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");

            }
        }

        $('#regd_in_income_tax').change(function(){
            if ($(this).is(':checked'))
            {
                $('.income').prop('checked', false);
                document.getElementById("income_tax_div").style.display = "block";
            } else {
                document.getElementById("income_tax_div").style.display = "none";
                $("#cnic").css("display", "none");
                $("#ntn").css("display", "none");
                $('#ntn').val("");
            }
        });


        $('#regd_in_sales_tax').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_div").style.display = "block";
                $("#strn").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_div").style.display = "none";
                $('#strn').val("");
                $("#strn").removeClass("requiredField");
            }
        });

        $('#regd_in_srb').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_srb").style.display = "block";
                $("#srb").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_srb").style.display = "none";
                $('#srb').val("");
                $("#srb").removeClass("requiredField");
            }
        });


        $('#regd_in_pra').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_pra").style.display = "block";
                $("#pra").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_pra").style.display = "none";
                $('#pra').val("");
                $("#pra").removeClass("requiredField");
            }
        });

        $('#bank_detail').change(function(){
            if ($(this).is(':checked'))
            {

                $(".banks").css("display", "block");
                $(".required").addClass("requiredField");

                //   $("#pra").addClass("requiredField");
            } else {
                $(".banks").css("display", "none");
                $(".required").removeClass("requiredField");
                //  $('#pra').val("");
                // $("#pra").removeClass("requiredField");
            }
        });

        $(document).ready(function(){
            $("form").submit(function(){
                var input = document.getElementsByClassName('requiredField');
                var v= input.length;


                if ($('#regd_in_income_tax').is(':checked'))
                {
                    if ($('#business').is(':checked')==false && $('#company').is(':checked')==false && $('#aop').is(':checked')==false )
                    {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }


                if ($('#regd_in_sales_tax').is(':checked'))
                {
                    if ($('#business').is(':checked')==false && $('#company').is(':checked')==false && $('#aop').is(':checked')==false )
                    {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }

                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++){
                    var v = input[i].id;
                    if(v == '')
                    {

                    }

                    else{
                        if($('#'+v).val() == '')

                        {

                            $('#'+v).css('border-color', 'red');

                            $('#'+v).focus();
                            return false;
                        }

                        else
                        {
                            $('#'+v).css('border-color', '#ccc');
                        }
                    }
                }


            });
        });



        var count=1;
        var count_address=1;
        $(document).ready(function() {
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper         = $(".input_fields_wrap");

            var add_button      = $(".add_field_button"); //Add button ID

            //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count++;

                $(wrapper).append('<input  type="text" name="contact_no[]" id="contact_no'+count+'" value="" class="form-control requiredField remove'+count+'"/>');

            });


            var max_fields_address      = 10; //maximum input boxes allowed
            var wrapper_address         = $(".input_fields_wrap_address");

            var add_button_address      = $(".add_field_button_address");
            $(add_button_address).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_address++;
                $(".removes").css("display", "block");
                $(wrapper_address).append('<input  type="text" name="address[]" id="address'+count+'" value="" class="form-control requiredField remove_address'+count_address+'"/>');

            });


        });
        function remove()
        {


            $('.remove'+count).remove();
            count--;


        }

        function remove_address()
        {


            $('.remove_address'+count_address).remove();
            count_address--;


        }
    </script>

    <script type="text/javascript">
        $('#account_head').select2();
        $('#vendor_type').select2();
        $('#country').select2();
        $('#state').select2();
        $('#city').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
