<?php
use App\Helpers\PurchaseHelper;
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')

    @include('select2');

    <style>
        input[type=radio] {
            background-color: transparent;
            border: 0.0625em solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            box-shadow: inset 0 0 0 0 white;
            cursor: pointer;
            font: inherit;
            height: 1em;
            outline: none;
            width: 1em;
        }

        .radio_select {
            display: flex;
            gap: 10px;
            align-items: baseline;
        }
    </style>

    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                                @include('Purchase.' . $accType . 'purchaseMenu')
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Supplier</span>
                                </div>
                            </div>
                            <hr style="border:1px solid #ddd" ;>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="well">
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php echo Form::open(array('url' => 'pad/editSupplierDetail?m=' . $m . '', 'id' => ''));?>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="pageType" value="">
                                                        <input type="hidden" name="parentCode" value="">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <h2 class="subHeadingLabelClass">Supplier Details </h2>
                                                            </div>

                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Supplier Name</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="supplier_id" name="supplier_id"
                                                                            type="hidden" class="form-control requiredField"
                                                                            required value="{{ $supplier->id }}">
                                                                        <input id="vendor_name" name="vendor_name"
                                                                            type="text" class="form-control requiredField"
                                                                            required value="{{ $supplier->name }}"
                                                                            onkeyup="validateField('1', 'vendor_name')" />

                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 hide">
                                                                        <label>Supplier Type</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <select onchange="" name="Vendor_Type"
                                                                            id="Vendor_Type" class="form-control select2">
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                        </select>
                                                                    </div>


                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Registration No</label>
                                                                        <input name="registration_no" id="Registration_No"
                                                                            type="text" class="form-control"
                                                                            value="{{ $supplier->registration_no }}">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Address</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Address" name="address" type="text"
                                                                            class="form-control requiredField" required
                                                                            value="{{ $supplier->address }}">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Telephone</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Telephone" name="mobile_no" type="text"
                                                                            class="form-control requiredField" required
                                                                            value="{{ $supplier->mobile_no }}"
                                                                            onkeyup="validateField('2', 'Telephone')">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Email</label>
                                                                        <input id="Email" name="email" type="email"
                                                                            class="form-control "
                                                                            value="{{ $supplier->email }}">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Products/ Services Provided</label>
                                                                        <input id="Products_Services_Provided"
                                                                            name="product_services_provided" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->product_services_provided }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="border:1px solid #ddd" ;>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <h2 class="subHeadingLabelClass">Tax Details</h2>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>NTN/CNIC</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="ntn" id="NTN/CNIC" type="text"
                                                                            class="form-control requiredField" required
                                                                            value="{{ $supplier->ntn }}" onkeyup="validateField('3', 'NTN/CNIC')">
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Sales Tax Status</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <div class="radio_select">
                                                                            <input type="radio" name="register_sales_tax"
                                                                                id="register_sales_tax1" value="1"
                                                                                @if($supplier->register_sales_tax == 1)
                                                                                checked @endif class="requiredField" />
                                                                            <label
                                                                                for="register_sales_tax1">Registered</label>

                                                                            <input type="radio" name="register_sales_tax"
                                                                                id="register_sales_tax2" value="2"
                                                                                @if($supplier->register_sales_tax == 2)
                                                                                checked @endif class="requiredField" />
                                                                            <label
                                                                                for="register_sales_tax2">Unregistered</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>VAT/ Sales Tax Registration No.</label>
                                                                        <input name="register_srb"
                                                                            id="VAT_Sales_Tax_Registration_No" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->register_srb }}" onkeyup="validateField('3', 'VAT_Sales_Tax_Registration_No')">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="border:1px solid #ddd" ;>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <h2 class="subHeadingLabelClass">Sales Rep Details </h2>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Name</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Name" name="contact_person" type="text"
                                                                            class="form-control requiredField" required
                                                                            value="{{ $supplier->contact_person }}"
                                                                            onkeyup="validateField('1', 'Name')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Phone Number</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Phone_Number" name="contact_person_no"
                                                                            type="text" class="form-control  requiredField"
                                                                            required
                                                                            value="{{ $supplier->contact_person_no }}"
                                                                            onkeyup="validateField('2', 'Phone_Number')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Email:</label>
                                                                        <input id="email" name="contact_person_email"
                                                                            type="email" class="form-control"
                                                                            value="{{ $supplier->contact_person_email }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="border:1px solid #ddd" ;>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <h2 class="subHeadingLabelClass">Payment Details </h2>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Representative Name:</label>
                                                                        <input id="Accounts_Representative_Name"
                                                                            name="account_representative_name" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->account_representative_name }}"
                                                                            onkeyup="validateField('1', 'Accounts_Representative_Name')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Rep Phone Number:</label>
                                                                        <input id="Accounts_Rep_Phone_Number"
                                                                            name="account_representative_no" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->account_representative_no }}"
                                                                            onkeyup="validateField('2', 'Accounts_Rep_Phone_Number')" />
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Rep Email Address:</label>
                                                                        <input id="Accounts_Rep_Email_Address:"
                                                                            name="account_representative_email"
                                                                            type="email" class="form-control"
                                                                            value="{{ $supplier->account_representative_email }}">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Agreed Payment Terms</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <!-- <select onchange="" name="Vendor_Type" id="Agreed_Payment_Terms" class="form-control requiredField select2">
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                        </select> -->
                                                                        <div class="radio_select">
                                                                            <input type="radio"
                                                                                @if($supplier->terms_of_payment == 1) checked
                                                                                @endif name="term" value="1"
                                                                                class="form-control requiredField"
                                                                                required /><label>Advance</label>
                                                                            <input type="radio"
                                                                                @if($supplier->terms_of_payment == 2) checked
                                                                                @endif name="term" value="2"
                                                                                class="form-control requiredField"
                                                                                required /><label>Against Delivery</label>
                                                                            <input type="radio"
                                                                                @if($supplier->terms_of_payment == 3) checked
                                                                                @endif name="term" value="3"
                                                                                class="form-control requiredField"
                                                                                required /><label>Credit</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>No. of Days</label>
                                                                        <input id="No_of_Days" name="no_of_days"
                                                                            type="number" class="form-control "
                                                                            value="{{ $supplier->no_of_days }}">
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Bank Account Title</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Bank_Account_Title" name="account_title"
                                                                            type="text" class="form-control requiredField"
                                                                            required value="{{ $supplier->account_title }}">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Bank Account Number</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="account_no" id="Bank_Account_number"
                                                                            type="text" class="form-control requiredField"
                                                                            required value="{{ $supplier->account_no }}" onkeyup="validateField('3', 'Bank_Account_number')">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>IBAN</label>
                                                                        <input id="ibn" name="ibn" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->ibn }}">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Bank Name and Branch</label>
                                                                        <span
                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="bank_name" id="Bank_Name_and_Branch"
                                                                            type="text" class="form-control requiredField"
                                                                            required value="{{ $supplier->bank_name }}">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>SWIFT (for import):</label>
                                                                        <input name="swift_code" id="SWIFT" type="text"
                                                                            class="form-control"
                                                                            value="{{ $supplier->swift_code }}">
                                                                    </div>
                                                                </div>
                                                                <div class="row">&nbsp;</div>
                                                                <div class="row">
                                                                    <div
                                                                        class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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

    <script type="text/javascript">

        function validateField(flag, id) {
            // name=1, number=2
            const field = document.getElementById(id);
            if (flag == 1) {
                field.value = field.value.replace(/[^a-zA-Z\s]/g, '');
            } else if (flag == 2) {
                field.value = field.value.replace(/(?!^\+)\D/g, '');

                // if (field.value.length > 11) {
                //     field.value = field.value.slice(0, 11); 
                // }
            } else if (flag == 3) {
                field.value = field.value.replace(/[^0-9-]/g, '');
            }
        }

        $(document).ready(function () {

            $('input[name="register_sales_tax"]').on('click', function () {
                toggleSalesTaxRequired();
            });

            function toggleSalesTaxRequired() {
                var register_sales_tax = $('input[name="register_sales_tax"]:checked').val();
                if (register_sales_tax == 1) {
                    $('#VAT_Sales_Tax_Registration_No').attr('required', 'required');
                } else {
                    $('#VAT_Sales_Tax_Registration_No').removeAttr('required');
                }
            }

            $('select[name="country"]').on('change', function () {
                var countryID = $(this).val();
                if (countryID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                        type: "GET",
                        data: { id: countryID },
                        success: function (data) {
                            $('select[name="state"]').html(data).empty();
                            $('select[name="city"]').empty();
                        }
                    });
                } else {
                    $('select[name="state"]').empty();
                    $('select[name="city"]').empty();
                }
            });

            $('select[name="state"]').on('change', function () {
                var stateID = $(this).val();
                if (stateID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                        type: "GET",
                        data: { id: stateID },
                        success: function (data) {
                            $('select[name="city"]').html(data).empty();
                        }
                    });
                } else {
                    $('select[name="city"]').empty();
                }
            });

            $("form").submit(function (e) {
                var input = document.getElementsByClassName('requiredField');
                var v = input.length;

                if ($('#regd_in_income_tax').is(':checked')) {
                    if ($('#business').is(':checked') == false && $('#company').is(':checked') == false && $('#aop').is(':checked') == false) {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }


                if ($('#regd_in_sales_tax').is(':checked')) {
                    if ($('#business').is(':checked') == false && $('#company').is(':checked') == false && $('#aop').is(':checked') == false) {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }

                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++) {
                    var v = input[i].id;
                    if (v == '') {

                    } else {
                        if ($('#' + v).val() == '') {
                            $('#' + v).css('border-color', 'red');
                            $('#' + v).focus();
                            return false;
                        } else {
                            $('#' + v).css('border-color', '#ccc');
                        }
                    }
                }

                var max_fields = 10; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap");

                var add_button = $(".add_field_button"); //Add button ID

                $(add_button).click(function (e) {
                    e.preventDefault();
                    count++;
                    $(wrapper).append('<input  type="text" name="contact_no[]" id="contact_no' + count + '" value="" class="form-control requiredField remove' + count + '"/>');

                });


                var max_fields_address = 10; //maximum input boxes allowed
                var wrapper_address = $(".input_fields_wrap_address");

                var add_button_address = $(".add_field_button_address");

                $(add_button_address).click(function (e) {
                    e.preventDefault();

                    count_address++;
                    $(".removes").css("display", "block");
                    $(wrapper_address).append('<input  type="text" name="address[]" id="address' + count + '" value="" class="form-control requiredField remove_address' + count_address + '"/>');

                });

                var max_fields_contact_person = 10; //maximum input boxes allowed
                var wrapper_contact_person = $(".input_fields_wrap_contact_person");

                var add_button_contact_person = $(".add_field_button_contact_person");

                $(add_button_contact_person).click(function (e) { //on add input button click
                    e.preventDefault();
                    count_contact_person++;
                    $(".remove_contact_person").css("display", "block");
                    $(wrapper_contact_person).append('<input  type="text" name="contact_person[]" id="contact_person' + count + '" value="" class="form-control requiredField remove_contact_person' + count_address + '"/>');

                });

                var max_fields_fax = 10; //maximum input boxes allowed
                var wrapper_fax = $(".input_fields_wrap_fax");

                var add_button_fax = $(".add_field_button_fax");
                $(add_button_fax).click(function (e) {
                    e.preventDefault();
                    count_fax++;
                    $(".remove_fax").css("display", "block");
                    $(wrapper_fax).append('<input  type="text" name="fax[]" id="fax' + count + '" value="" class="form-control requiredField remove_fax' + count_fax + '"/>');

                });
            });
        });

        function ntn_cnic(id) {
            if (id == 1) {
                $("#ntn").fadeIn(500).addClass("requiredField").attr('required', true);
                $("#cnic").fadeIn(500).addClass("requiredField").attr('required', true);
            } else {
                $("#ntn").fadeOut(500).removeClass("requiredField").removeAttr('required');
                $("#cnic").fadeOut(500).removeClass("requiredField").removeAttr('required');
            }
        }

        $('#regd_in_income_tax').change(function () {
            if ($(this).is(':checked')) {
                $('.income').prop('checked', false);
                $("#income_tax_div").show();
            } else {
                $("#income_tax_div").hide();
                $("#ntn").val(""); // Clear field value
            }
        });


        $('#regd_in_sales_tax').change(function () {
            if ($(this).is(':checked')) {
                document.getElementById("sales_tax_div").style.display = "block";
                $("#strn").addClass("requiredField").attr('required', true);
            } else {
                document.getElementById("sales_tax_div").style.display = "none";
                $('#strn').val("");
                $("#strn").removeClass("requiredField").removeAttr('required');
            }
        });

        $('#regd_in_srb').change(function () {
            if ($(this).is(':checked')) {
                document.getElementById("sales_tax_srb").style.display = "block";
                $("#srb").addClass("requiredField").attr('required', true);
            } else {
                document.getElementById("sales_tax_srb").style.display = "none";
                $('#srb').val("");
                $("#srb").removeClass("requiredField").removeAttr('required');
            }
        });


        $('#regd_in_pra').change(function () {
            if ($(this).is(':checked')) {
                document.getElementById("sales_tax_pra").style.display = "block";
                $("#pra").addClass("requiredField").attr('required', true);
            } else {
                document.getElementById("sales_tax_pra").style.display = "none";
                $('#pra').val("");
                $("#pra").removeClass("requiredField").removeAttr('required');
            }
        });

        $('#bank_detail').change(function () {
            if ($(this).is(':checked')) {
                $(".banks").css("display", "block");
                $(".required").addClass("requiredField").attr('required', true);
            } else {
                $(".banks").css("display", "none");
                $(".required").removeClass("requiredField").removeAttr('required');
            }
        });

        var count = 1;
        var count_address = 1;
        var count_contact_person = 1;
        var count_fax = 1;

        function remove() {
            $('.remove' + count).remove();
            count--;
        }

        function remove_address() {
            $('.remove_address' + count_address).remove();
            count_address--;
        }

        function remove_contact_person() {
            $('.remove_contact_person' + count_contact_person).remove();
            count_contact_person--;
        }

        function remove_fax() {
            $('.remove_fax' + count_fax).remove();
            count_fax--;
        }

        var MCounter = 1;
        function AddMoreRows() {
            MCounter++;
            $('#AppendHtml').append('<div class="row" id="RemoveRows' + MCounter + '">' +
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                '<label>Contact Person :</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input  type="text" name="contact_person[]" id="contact_person' + MCounter + '" value="" class="form-control" />' +
                '</div>' +
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                '<label>Contact No :</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input  type="text" name="contact_no[]" id="contact_no' + MCounter + '" value="" class="form-control" />' +
                '</div>' +
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                '<label>Fax :</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input  type="text" name="fax[]" id="fax' + MCounter + '" value="" class="form-control" />' +
                '</div>' +
                '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                '<label>Address :</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input  type="text" name="address[]" id="address' + MCounter + '" value="" class="form-control" />' +
                '</div>' +
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                '<label>Work Phone:</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input  type="text" name="work_phone[]" id="work_phone1" value="" class="form-control" />' +
                '<button type="button" class="btn btn-xs btn-danger pull-right" id="BtnRemove" onclick="RemoveRows(' + MCounter + ')" style="width: 50px; height: 25px;"> &times; </button>' +
                '</div>' +
                '</div>');
        }

        function RemoveRows(Rows) {
            $('#RemoveRows' + Rows).remove();
        }

        $('#account_head').select2();
        $('#vendor_type').select2();
        $('#country').select2();
        $('#state').select2();
        $('#city').select2();
        $('#account_id').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection