


<?php
$accType = Auth::user()->acc_type;
if($accType == 'client')
{
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;


?>
@extends('layouts.default')
@section('content')
    @include('select2');

    <style>
        #myInput {

            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 100%;
            font-size: 16px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
        }
    </style>






    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Sales Return </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                            <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="tab-content">
                        <div id="home" class="tab-pane in active">
                            <div class="panel">
                                <div class="panel-body" id="PrintEmpExitInterviewList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <?php echo Form::open(array('url' => 'sales/addCustomerCredit_no?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Customer</label>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                <option value="">Select Customer</option>
                                                @foreach(($customers ?? []) as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>Invoice</label>
                                            <select name="invoice_no" id="invoice_no" class="form-control">
                                                <option value="">Select Invoice</option>
                                            </select>
                                            <input type="hidden" name="type" id="credit_note_type" value="">
                                        </div>



                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="myTable">
                                                    <thead>
                                                    <th class="text-center col-sm-1"></th>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center col-sm-1">Item</th>
                                          
                                                    <th class="text-center col-sm-1">UOM</th>
                                                    <th class="text-center col-sm-1">QTY.</th>
                                                    <th class="text-center col-sm-1">Bag QTY.</th>
                                                    <th class="text-center col-sm-1">Rate</th>
                                                    <th class="text-center col-sm-1">Discount</th>
                                                    <th class="text-center col-sm-1">Amount</th>

                                                    {{--<th class="text-center">Edit</th>--}}
                                                    {{--<th class="text-center">Delete</th>--}}
                                                    </thead>
                                                    <tbody id="ShowOn">
                                                    <?php $counter = 1;?>
                                                    </tbody>
                                                </table>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ShowBtn">
                                                    <input type="submit" value="Create Customer Credit Note" class="btn btn-xs btn-success pull-left" id="add" disabled="">
                                                </div>

                                                <div id="data1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php Form::close(); ?>
                                </div>
                            </div>
                        </div>



                    </div>


                    <div class="lineHeight">&nbsp;</div>

                </div>
                </div>
            </div>
        </div>
    </div>

    <script !src="">
        $(document).ready(function () {
            $('#customer_id').select2();
            $('#invoice_no').select2();
            var action = $('form').attr('action');
            $('form').attr('action',action);

            $('#customer_id').on('change', function () {
                loadCustomerInvoices();
            });

            $('#invoice_no').on('change', function () {
                if ($(this).val() !== '') {
                    $('#credit_note_type').val($(this).find(':selected').data('type') || '');
                    ShowData();
                } else {
                    $('#credit_note_type').val('');
                    $('#ShowOn').html('');
                    $('#add').prop('disabled', true);
                }
            });
        });

        function loadCustomerInvoices()
        {
            var customerId = $('#customer_id').val();

            $('#ShowOn').html('');
            $('#add').prop('disabled', true);
            $('#credit_note_type').val('');
            $('#invoice_no').html('<option value="">Select Invoice</option>').trigger('change.select2');

            if (customerId === '') {
                return false;
            }

            $.ajax({
                url: '<?php echo url('/')?>/sdc/getCustomerInvoicesForCreditNote',
                type: "GET",
                data: {customer_id: customerId},
                success:function(data) {
                    var options = '<option value="">Select Invoice</option>';

                    $.each(data, function(index, row) {
                        var voucherDate = row.voucher_date ? ' - ' + row.voucher_date : '';
                        options += '<option value="' + row.voucher_no + '" data-type="' + row.type + '">' + row.voucher_no + voucherDate + '</option>';
                    });

                    $('#invoice_no').html(options).trigger('change.select2');
                }
            });
        }

        function ShowData()
        {
            var so = $('#invoice_no').val();
            var type = $('#credit_note_type').val();

            if(so == "")

            {

               alert('Required Invoice');
                return false;

            }

                $('#SupplierError').html('');
                $('#ShowOn').html('<tr><td colspan="10" class="loader"></td></tr>');

                $.ajax({
                    url: '<?php echo url('/')?>/sdc/getSalesTaxInvoice',
                    type: "GET",
                    data: {so:so,type:type},
                    success:function(data) {
                        $('#data1').html('');
                        $('#ShowOn').html(data);


                    }
                });
        }


        
    </script>
@endsection
