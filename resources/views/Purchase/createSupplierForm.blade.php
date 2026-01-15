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
    input[type=radio]{background-color:transparent;border:0.0625em solid rgba(255,255,255,0.5);border-radius:50%;box-shadow:inset 0 0 0 0 white;cursor:pointer;font:inherit;height:1em;outline:none;width:1em;}
    .radio_select{display:flex;gap:10px;align-items:baseline;}
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
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <span class="subHeadingLabelClass">Create Supplier</span>
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" style="float: right;"> Import csv </button>
                                </div>
                            </div>
                            <hr style="border:1px solid #ddd";>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="well">
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php echo Form::open(array('url' => 'pad/addSupplierDetail?m=' . $m . '', 'id' => 'supplierForm'));?>
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
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="vendor_name" name="vendor_name" type="text" class="form-control requiredField" required value="" onkeyup="validateField('1', 'vendor_name')" />
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Account Head :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select onchange="get_nature_type()" name="account_head" id="account_id" class="form-control requiredField select2" required>
                                                                        <option value="">Select Account</option>
                                                                        @foreach($accounts as $key => $y)
                                                                            <option value="{{ $y->code}}">{{ $y->code . ' ---- ' . $y->name}}</option>
                                                                        @endforeach

                                                                    </select>
                                                                    </div>


                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Registration No</label>
                                                                        <input id="Registration_No" name="registration_no" type="text" class="form-control" value="">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Address</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Address" type="text" name="address"  class="form-control requiredField" required value="">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Telephone</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Telephone" name="mobile_no"  type="text" class="form-control requiredField" required value="" onkeyup="validateField('2', 'Telephone')" />
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Email</label>
                                                                        <input id="Email" name="email" type="email" class="form-control " value="">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Products/ Services Provided</label>
                                                                        <input id="Products_Services_Provided" name="product_services_provided"  type="text" class="form-control" value="">
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
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>NTN/CNIC</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="NTN/CNIC" type="text" name="ntn"  class="form-control requiredField" required value="" onkeyup="validateField('3', 'NTN/CNIC')">
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Sales Tax Status</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <div class="radio_select">
                                                                            <input type="radio" value="1" name="register_sales_tax" class="form-control requiredField" required  /><label>Registered</label>
                                                                            <input type="radio" value="2" name="register_sales_tax" class="form-control requiredField" required  checked /><label>Unregistered</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>VAT/ Sales Tax Registration No.</label>
                                                                        <input name="register_srb"  id="VAT_Sales_Tax_Registration_No" type="text" class="form-control" onkeyup="validateField('3', 'VAT_Sales_Tax_Registration_No')">
                                                                    </div>

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
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Name</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Name" type="text" name="contact_person"  class="form-control requiredField" required value="" onkeyup="validateField('1', 'Name')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Phone Number</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input id="Phone_Number" name="contact_person_no"  type="text" class="form-control  requiredField" required value="" onkeyup="validateField('2', 'Phone_Number')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Email:</label>
                                                                        <input id="email" name="contact_person_email"  type="email" class="form-control" value="">
                                                                    </div>
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
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Representative Name:</label>
                                                                        <input id="Accounts_Representative_Name" name="account_representative_name" type="text" name=""  class="form-control" value="" onkeyup="validateField('1', 'Accounts_Representative_Name')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Rep Phone Number:</label>
                                                                        <input id="Accounts_Rep_Phone_Number" name="account_representative_no"  type="text" class="form-control" value="" onkeyup="validateField('2', 'Accounts_Rep_Phone_Number')" />
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Accounts Rep Email Address:</label>
                                                                        <input id="Accounts_Rep_Email_Address:" type="email" name="account_representative_email"  class="form-control" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Agreed Payment Terms</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <!-- <select onchange="" name="Vendor_Type" id="Agreed_Payment_Terms" class="form-control requiredField select2">
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                            <option value="">Select Account</option>
                                                                        </select> -->
                                                                        <div class="radio_select">
                                                                            <input type="radio" name="term" value="1" class="form-control requiredField" required  /><label>Advance</label>
                                                                            <input type="radio" name="term" value="2" class="form-control requiredField" required  checked /><label>Against Delivery</label>
                                                                            <input type="radio" name="term" value="3" class="form-control requiredField" required  checked /><label>Credit</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>No. of Days</label>
                                                                        <input id="No_of_Days" name="no_of_days"  type="number" class="form-control " value="">
                                                                    </div>

                                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
                                                                        <label>Bank Account Title</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="account_title"  id="Bank_Account_Title" type="text" class="form-control requiredField" required value="">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>Bank Account Number</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="account_no"  id="Bank_Account_number" type="text" class="form-control requiredField" required value="" onkeyup="validateField('3','Bank_Account_number')">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>IBAN</label>
                                                                        <input name="ibn"  id="ibn" type="text" class="form-control" value="">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                            <label>Bank Name and Branch</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input name="bank_name"  id="Bank_Name_and_Branch" type="text" class="form-control requiredField" required value="">
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                        <label>SWIFT (for import):</label>
                                                                        <input name="swift_code"  id="SWIFT" type="text" class="form-control" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="row">&nbsp;</div>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                                        <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
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

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="importProducts___BV_modal_body_" class="modal-body">
                            <form action="{{ url('pad/uploadSupplier') }}" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="mb-3 col-sm-12 col-md-12">
                                        <fieldset class="form-group" id="__BVID__194">
                                            <div>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="company_id" id="company_id"
                                                       value="{{ $m }}" />
                                                <input type="file" name='file' label="Choose File" required>
                                                <div id="File-feedback" class="d-block invalid-feedback">Field must be in csvformat</div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                                    </div>
                                    <div class="col-sm-6 col-md-6"><button onclick="download_csv_file()" target="_self"
                                                                    class="btn btn-info btn-sm btn-block">Download example</button></div>

                                </div>
                            </form>

                            <div class="col-sm-12 col-md-12">
                                <table class="table table-bordered table-sm mt-4">
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <th><span class="badge badge-outline-success">This Field is required and name must be unique</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Registration No</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Telephone</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>

                                    <tr>
                                        <td>Email</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Product/Services Provided</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>NTN/CNIC</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Sales Tax Status</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>VAT/ Sales Tax Registration No</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Discount (%)</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Sales Rep Name</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Cell No</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Accounts Representative Name</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Accounts Representative Phone</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Accounts Representative Email</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Agreed Payment Terms</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>No. of Days</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Bank Account Title</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Bank Account No</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>IBAN</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Bank Name</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Swift Code</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>





                                    </tbody>
                                </table>
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


            function clearForm() {
                // Clear text inputs, email, number
                var inputs = document.querySelectorAll('#supplierForm input[type="text"], #myForm input[type="email"], #myForm input[type="number"]');
                inputs.forEach(input => input.value = '');

                // Clear radio buttons
                var radios = document.querySelectorAll('#supplierForm input[type="radio"]');
                radios.forEach(radio => radio.checked = false);

                // Clear checkboxes
                var checkboxes = document.querySelectorAll('#supplierForm input[type="checkbox"]');
                checkboxes.forEach(checkbox => checkbox.checked = false);

                // Clear select boxes
                var selects = document.querySelectorAll('#supplierForm select');
                selects.forEach(select => select.selectedIndex = 0);
            }


            //create CSV file data in an array
            var csvFileData = [

                ['Ahmed Ali', '12345678', 'North Nazimabad', '0300-1234567', 'ahmed@gmail.com', 'xyz', '42340-2333111-2', 'Unregistered', '13456', 'Ali', '0320-2313232', 'ali@gmail.com', 'ahmed', '0323-2323232', 'ahmed@gmail.com', 'against delivery', '30', 'Ahmed Ali', '1223121312123', 'IBAN No', 'Habib Metro', '123'],
            ];

            //create a user-defined function to download CSV file
            function download_csv_file() {

                //define the heading for each row of the data
                var csv = 'Name,Registration No,Address,Telephone,Email,Products/ Services Provided,NTN/CNIC,Sales Tax Status,VAT/ Sales Tax Registration No,Sales Rep Name,Phone Number,Email,Accounts Representative Name,Accounts Rep Phone Number,Accounts Rep Email Address,Agreed Payment Terms,No. of Days,Bank Account Title,Bank Account Number,IBAN,Bank Name and Branch,SWIFT\n';

                //merge the data with CSV
                csvFileData.forEach(function (row) {
                    csv += row.join(',');
                    csv += "\n";
                });

                //display the created CSV data on the web browser
                //document.write(csv);


                var hiddenElement = document.createElement('a');
                hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                hiddenElement.target = '_blank';

                //provide the name for the CSV file to be downloaded
                hiddenElement.download = 'Supplier file.csv';
                hiddenElement.click();
            }

            $(document).ready(function () {

                $('select[name="country"]').on('change', function () {
                    var countryID = $(this).val();
                    if (countryID) {
                        $.ajax({
                            url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                            type: "GET",
                            data: { id: countryID },
                            success: function (data) {
                                $('select[name="city"]').empty();
                                $('select[name="state"]').empty();
                                $('select[name="state"]').html(data);
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
                                $('select[name="city"]').empty();
                                $('select[name="city"]').html(data);
                            }
                        });
                    } else {
                        $('select[name="city"]').empty();
                    }
                });


            });


            function ntn_cnic(id) {
                if (id == 1) {

                    $(this).prop('checked', false);
                    $("#ntn").fadeIn(500);
                    $("#cnic").fadeIn(500);
                    $("#amir").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
                    $("#amir").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
                    $("#ntn").addClass("requiredField", required);
                    $("#cnic").addClass("requiredField", required);
                }

                else {

                    $("#ntn").fadeIn(500);
                    $("#ntn").addClass("requiredField", required);
                    $("#cnic").css("display", "none");
                    $("#cnic").removeClass("requiredField", required);
                    $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
                    $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");

                }
            }

            $('#regd_in_income_tax').change(function () {
                if ($(this).is(':checked')) {
                    $('.income').prop('checked', false);
                    document.getElementById("income_tax_div").style.display = "block";
                } else {
                    document.getElementById("income_tax_div").style.display = "none";
                    $("#cnic").css("display", "none");
                    // $("#ntn").css("display", "none");
                    $('#ntn').val("");
                }
            });


            $('#regd_in_sales_tax').change(function () {
                if ($(this).is(':checked')) {
                    document.getElementById("sales_tax_div").style.display = "block";
                    $("#strn").addClass("requiredField", required);
                } else {
                    document.getElementById("sales_tax_div").style.display = "none";
                    $('#strn').val("");
                    $("#strn").removeClass("requiredField", required);
                }
            });

            $('#regd_in_srb').change(function () {
                if ($(this).is(':checked')) {
                    document.getElementById("sales_tax_srb").style.display = "block";
                    $("#srb").addClass("requiredField", required);
                } else {
                    document.getElementById("sales_tax_srb").style.display = "none";
                    $('#srb').val("");
                    $("#srb").removeClass("requiredField", required);
                }
            });


            $('#regd_in_pra').change(function () {
                if ($(this).is(':checked')) {
                    document.getElementById("sales_tax_pra").style.display = "block";
                    $("#pra").addClass("requiredField", required);
                } else {
                    document.getElementById("sales_tax_pra").style.display = "none";
                    $('#pra').val("");
                    $("#pra").removeClass("requiredField", required);
                }
            });

            $('#bank_detail').change(function () {
                if ($(this).is(':checked')) {

                    $(".banks").css("display", "block");
                    $(".required").addClass("requiredField", required);

                    //   $("#pra").addClass("requiredField" required);
                } else {
                    $(".banks").css("display", "none");
                    $(".required").removeClass("requiredField", required);
                    //  $('#pra').val("");
                    // $("#pra").removeClass("requiredField" required);
                }
            });

            $(document).ready(function () {
                $("#supplierForm").submit(function () {
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

                        }

                        else {
                            if ($('#' + v).val() == '') {


                                $('#' + v).css('border-color', 'red');

                                $('#' + v).focus();
                                return false;
                            }

                            else {
                                $('#' + v).css('border-color', '#ccc');
                            }
                        }
                    }


                });
            });



            var count = 1;
            var count_address = 1;
            var count_contact_person = 1;
            var count_fax = 1;
            $(document).ready(function () {
                var max_fields = 10; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap");

                var add_button = $(".add_field_button"); //Add button ID

                //initlal text box count
                $(add_button).click(function (e) { //on add input button click
                    e.preventDefault();
                    //max input box allowed
                    //text box increment

                    count++;

                    $(wrapper).append('<input  type="text" name="contact_no[]" id="contact_no' + count + '" value="" class="form-control requiredField remove' + count + '"/>');

                });


                var max_fields_address = 10; //maximum input boxes allowed
                var wrapper_address = $(".input_fields_wrap_address");

                var add_button_address = $(".add_field_button_address");
                $(add_button_address).click(function (e) { //on add input button click
                    e.preventDefault();
                    //max input box allowed
                    //text box increment

                    count_address++;
                    $(".removes").css("display", "block");
                    $(wrapper_address).append('<input  type="text" name="address[]" id="address' + count + '" value="" class="form-control requiredField remove_address' + count_address + '"/>');

                });

                var max_fields_contact_person = 10; //maximum input boxes allowed
                var wrapper_contact_person = $(".input_fields_wrap_contact_person");

                var add_button_contact_person = $(".add_field_button_contact_person");
                $(add_button_contact_person).click(function (e) { //on add input button click
                    e.preventDefault();
                    //max input box allowed
                    //text box increment

                    count_contact_person++;
                    $(".remove_contact_person").css("display", "block");
                    $(wrapper_contact_person).append('<input  type="text" name="contact_person[]" id="contact_person' + count + '" value="" class="form-control requiredField remove_contact_person' + count_address + '"/>');

                });

                var max_fields_fax = 10; //maximum input boxes allowed
                var wrapper_fax = $(".input_fields_wrap_fax");

                var add_button_fax = $(".add_field_button_fax");
                $(add_button_fax).click(function (e) { //on add input button click
                    e.preventDefault();
                    //max input box allowed
                    //text box increment

                    count_fax++;
                    $(".remove_fax").css("display", "block");
                    $(wrapper_fax).append('<input  type="text" name="fax[]" id="fax' + count + '" value="" class="form-control requiredField remove_fax' + count_fax + '"/>');

                });


            });
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
@endsection
