<?php
use App\Helpers\PurchaseHelper;
use App\Models\Supplier;
$accType = Auth::user()->acc_type;
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$supplier = DB::connection('mysql2')->table('supplier')->where('id',$id)->first();

$m=Session::get('run_company');
?>
@section('content')
@include('select2')

<style>
input[type=radio]{background-color:transparent;border:0.0625em solid rgba(255,255,255,0.5);border-radius:50%;box-shadow:inset 0 0 0 0 white;cursor:pointer;font:inherit;height:1em;outline:none;width:1em;}
.radio_select{display:flex;gap:10px;align-items:baseline;}
</style>

<!-- <div class="well_N">
    <div class="dp_sdw"> -->
        <div class="panel">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                            @include('Purchase.'.$accType.'purchaseMenu')
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
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
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <h2 class="subHeadingLabelClass">Supplier Details </h2>
                                                        </div>
        
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Supplier Name</label>
                                                                    <p id="">{{ $supplier->name }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 hide">
                                                                    <label>Supplier Type</label>
                                                                        <p id=""></p>
                                                                </div>
        
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Registration No</label>
                                                                    <p id="">{{ $supplier->registration_no }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Address</label>
                                                                    <p id="">{{ $supplier->address }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Telephone</label>
                                                                    <p id="">{{ $supplier->mobile_no }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Email</label>
                                                                    <p id="">{{ $supplier->email }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Products/ Services Provided</label>
                                                                    <p id="">{{ $supplier->product_services_provided }}</p>
                                                                </div>
        
        
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="border:1px solid #ddd";>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <h2 class="subHeadingLabelClass">Tax Details</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>NTN/CNIC</label>
                                                                    <p id="">{{ $supplier->ntn }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Sales Tax Status</label>
                                   
                                                                    <div class="radio_select">
                                                                       @if($supplier->register_sales_tax == 1) <label>Registered</label>@endif
                                                                       @if($supplier->register_sales_tax == 2)<label>Unregistered</label>@endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>VAT/ Sales Tax Registration No.</label>
                                                                    <p id="">{{ $supplier->register_srb }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="border:1px solid #ddd";>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <h2 class="subHeadingLabelClass">Sales Rep Details </h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Name</label>
                                                                    <p id="">{{ $supplier->contact_person }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Phone Number</label>
                                                                    <p id="">{{ $supplier->contact_person_no }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Email:</label>
                                                                    <p id="">{{ $supplier->contact_person_email }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr style="border:1px solid #ddd";>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <h2 class="subHeadingLabelClass">Payment Details </h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Accounts Representative Name:</label>
                                                                    <p id="">{{ $supplier->account_representative_name }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Accounts Rep Phone Number:</label>
                                                                    <p id="">{{ $supplier->account_representative_no }}</p>

                                                                </div>
        
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Accounts Rep Email Address:</label>
                                                                    <p id="">{{ $supplier->account_representative_email }}</p>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Agreed Payment Terms</label>
                                                                    <p id="">
                                                                        @if($supplier->terms_of_payment == 1)
                                                                            Advance
                                                                        @elseif($supplier->terms_of_payment == 2)
                                                                            Against Delivery
                                                                        @elseif($supplier->terms_of_payment == 3)
                                                                            Credit
                                                                        @else
                                                                            {{ $supplier->terms_of_payment }}
                                                                        @endif
                                                                    </p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>No. of Days</label>
                                                                    <p id="">{{ $supplier->no_of_days }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Bank Account Title</label>
                                                                    <p id="">{{ $supplier->account_title }}</p>
                                                                </div>

                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Bank Account Number</label>
                                                                    <p id="">{{ $supplier->account_no }}</p>
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>IBAN</label>
                                                                    <p id="">{{ $supplier->ibn }}</p>
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Bank Name and Branch</label>
                                                                    <p id="">{{ $supplier->bank_name }}</p>
                                                                </div>

                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>SWIFT (for import):</label>
                                                                    <p id="">{{ $supplier->swift_code }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        

                                                <div>&nbsp;</div>

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
    <!-- </div>
</div>     -->
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
                // $("#ntn").css("display", "none");
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
        var count_contact_person=1;
        var count_fax=1;
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

            var max_fields_contact_person      = 10; //maximum input boxes allowed
            var wrapper_contact_person         = $(".input_fields_wrap_contact_person");

            var add_button_contact_person      = $(".add_field_button_contact_person");
            $(add_button_contact_person).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_contact_person++;
                $(".remove_contact_person").css("display", "block");
                $(wrapper_contact_person).append('<input  type="text" name="contact_person[]" id="contact_person'+count+'" value="" class="form-control requiredField remove_contact_person'+count_address+'"/>');

            });

            var max_fields_fax      = 10; //maximum input boxes allowed
            var wrapper_fax         = $(".input_fields_wrap_fax");

            var add_button_fax      = $(".add_field_button_fax");
            $(add_button_fax).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_fax++;
                $(".remove_fax").css("display", "block");
                $(wrapper_fax).append('<input  type="text" name="fax[]" id="fax'+count+'" value="" class="form-control requiredField remove_fax'+count_fax+'"/>');

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
        function remove_contact_person()
        {


            $('.remove_contact_person'+count_contact_person).remove();
            count_contact_person--;


        }

        function remove_fax()
        {


            $('.remove_fax'+count_fax).remove();
            count_fax--;


        }
        var MCounter = 1;
        function AddMoreRows()
        {
            MCounter++;
            $('#AppendHtml').append('<div class="row" id="RemoveRows'+MCounter+'">' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Contact Person :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="contact_person[]" id="contact_person'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Contact No :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="contact_no[]" id="contact_no'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Fax :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="fax[]" id="fax'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label>Address :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="address[]" id="address'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Work Phone:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="work_phone[]" id="work_phone1" value="" class="form-control" />' +
                    '<button type="button" class="btn btn-xs btn-danger pull-right" id="BtnRemove" onclick="RemoveRows('+MCounter+')" style="width: 50px; height: 25px;"> &times; </button>' +
                    '</div>' +
                    '</div>');
        }

        function RemoveRows(Rows)
        {
            $('#RemoveRows'+Rows).remove();
        }
    </script>

    <script type="text/javascript">
        $('#account_head').select2();
        $('#vendor_type').select2();
        $('#country').select2();
        $('#state').select2();
        $('#city').select2();
        $('#account_id').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

