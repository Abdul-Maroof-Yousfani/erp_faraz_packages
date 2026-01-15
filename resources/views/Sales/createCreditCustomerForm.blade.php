<?php
use App\Helpers\PurchaseHelper;
$accType = Auth::user()->acc_type;
$m = $_GET['m'];
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
                            @include('Purchase.'.$accType.'purchaseMenu')
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <span class="subHeadingLabelClass">Add Customer</span>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" style="float: right;"> Import csv </button>
                                </div>
                        </div>
                        <hr style="border:1px solid #ddd;">
                        <?php echo Form::open(array('url' => 'sad/addCreditCustomerDetail?m='.$m.'','id'=>'customerForm'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="pageType" value="">
                                                    <input type="hidden" name="parentCode" value="">
                                                   
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <h2 class="subHeadingLabelClass">Parent Head Details</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">    
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                <label>Account Head :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select onchange="get_nature_type()" name="account_head" id="account_id" class="form-control requiredField select2">
                                                                    <option value="">Select Account</option>
                                                                    @foreach($accounts as $key => $y)
                                                                        <option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                    @endforeach
                                                                        
                                                                </select>
                                                                </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
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
                                                            <h2 class="subHeadingLabelClass">Customer Details  </h2>
                                                        </div>
        
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Customer Name</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input id="Customer_name" name="customer_name" type="text" class="form-control requiredField" value="">
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Tel</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input id="tel" type="text" name="contact_no" class="form-control requiredField" value="">
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Email</label>
                                                                    <input id="Email" type="email" name="email" class="form-control " value="">
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
                                                            <h2 class="subHeadingLabelClass">Registered Address</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Streets Address</label>
                                                                    <input id="Streets_Address" name="address" type="text" class="form-control" value="">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>City</label>
                                                                    <input id="Streets_Address" type="text" name="city" class="form-control" value="">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Country</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input id="Country" type="text" name="country" class="form-control  requiredField" value="">
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
                                                            <h2 class="subHeadingLabelClass">Postal Address</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Postal Address</label>
                                                                    <input id="PostalAddress" type="text" name="postal_address" class="form-control" value="">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
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
                                                            <h2 class="subHeadingLabelClass">Point of Contact</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Name</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input id="Name" type="text" name="customer_name" class="form-control" value="" required>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Cell No</label>
                                                                    <input id="Phone_Number" type="number" name="contact_person_no" class="form-control" value="">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Email Address</label>
                                                                    <input id="emailadress" type="email" name="contact_person_email" class="form-control" value="">
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
                                                            <h2 class="subHeadingLabelClass">Tax Details</h2>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>NTN/CNIC</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <input id="NTN/CNIC" type="text" name="ntn" class="form-control requiredField" value="">
                                                                </div>
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>ATL Status</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <div class="radio_select">
                                                                        <input type="radio" name="atl_status" value="1" class="form-control requiredField"  /><label>Active</label>
                                                                        <input type="radio" name="atl_status" value="2" class="form-control requiredField"  checked /><label>In Active</label>
                                                                    </div>
                                                                </div>
        
        
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Sales Tax Status</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <div class="radio_select">
                                                                        <input type="radio" name="regd_in_sales_tax" value="1" class="form-control requiredField"  /><label>Registered</label>
                                                                        <input type="radio" name="regd_in_sales_tax" value="2" class="form-control requiredField"  checked /><label>Unregistered</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label>Status U/S 236G or H</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <div class="radio_select">
                                                                        <input type="radio" name="status_us_236g_h" value="1" class="form-control requiredField"  /><label>Manufacturer</label>
                                                                        <input type="radio" name="status_us_236g_h" value="2" class="form-control requiredField"  checked /><label>Wholesaler/ Distributor</label>
                                                                        <input type="radio" name="status_us_236g_h" value="3" class="form-control requiredField"  checked /><label>Retailer/ Others</label>
                                                                    </div>
                                                                </div>
        
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label>Payment Terms</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <div class="radio_select">
                                                                        <input type="radio" name="term" value="1" class="form-control requiredField"  /><label>Advance</label>
                                                                        <input type="radio" name="term" value="2" class="form-control requiredField"  checked /><label>Against Delivery</label>
                                                                        <input type="radio" name="term" value="3" class="form-control requiredField"  checked /><label>Credit</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>No of Days </label>
                                                                    <input id="No_of_Days" name="no_of_days" type="number" class="form-control" value="">
                                                                </div>

                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                    <label>Credit Limit</label>
                                                                    <input id="Credit Limit" name="	creditLimit" type="text" class="form-control" value="">
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <label>Comment</label>
                                                                    <input id="Comment" type="text" name="remarks" class="form-control" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
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
                        <?php echo Form::close();?>
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
                        <form action="{{ url('sales/uploadCreditCustomer') }}" method="post" enctype="multipart/form-data">
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
                                    <td>Tel</td>
                                    <th><span class="badge badge-outline-success">This Field is required</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <th><span class="badge badge-outline-info">This Field is required</span></th>
                                </tr>
                                <tr>
                                    <td>Street Address</td>
                                    <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                </tr>
                                
                                <tr>
                                    <td>City</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <th><span class="badge badge-outline-success">This Field is required</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Postal Address</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Point of Contact Name</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Cell No</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>NTN</td>
                                    <th><span class="badge badge-outline-success">This Field is required</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>ATL Status</td>
                                    <th><span class="badge badge-outline-success">This Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Sales Tax Status</td>
                                    <th><span class="badge badge-outline-success">Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Status U/S 236G or H</td>
                                    <th><span class="badge badge-outline-success">Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Payment Terms</td>
                                    <th><span class="badge badge-outline-success">Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>No of Days</td>
                                    <th><span class="badge badge-outline-success">Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Credit Limit</td>
                                    <th><span class="badge badge-outline-success">Field is optional</span>
                                    </th>


                                </tr>
                                <tr>
                                    <td>Comment</td>
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



    <script type="text/javascript">

    //create CSV file data in an array
    var csvFileData = [

    ['Fawad','0332-1234567','khan@gmail.com', 'North Nazimabad',  'Karachi', 'Pakistan', '2402','Ahmed','0300-2321212','ahmed@gmail.com','42403-23232323-1','active','unregistered','wholesaler/distributor','against delivery','30','100','remarks'],
    ];

        //create a user-defined function to download CSV file
        function download_csv_file() {

        //define the heading for each row of the data
        var csv = 'Name,Tel,Email,Address,City,Country,Postal Address,Point Of Contact Name,Cell No,Email,NTN/CNIC,ATL Status,Sales Tax Status,Status U/S 236G or H,Terms of Payment,No Of Days,Credit Limit,Comment \n';

        //merge the data with CSV
        csvFileData.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
        });

        //display the created CSV data on the web browser
        //document.write(csv);


        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';

        //provide the name for the CSV file to be downloaded
        hiddenElement.download = 'Cstomer file.csv';
        hiddenElement.click();
        }


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
            $("#customerForm").submit(function(){
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
@endsection
