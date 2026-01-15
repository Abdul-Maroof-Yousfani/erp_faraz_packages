<?php
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Sales Tax Invoice</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList', '', '1')?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Customer</label>
                                <select class="form-control" onchange="getSobyCustomer()" name="customer_id"
                                    id="customer_id">
                                    <option value=""> Select Customer </option>
                                    @foreach($Customers as $key => $value)
                                        <option value="{{$value->id}}"> {{ $value->name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>SO No.</label>
                                <!-- <input type="text" name="to" id="so_no" max="" value="" class="form-control" /> -->
                                <select class="form-control" name="to" id="so_no">

                                </select>
                            </div>


                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-primary"
                                    onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                        <span id="data"></span>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function delete_record(id) {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '{{ url('/') }}/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: { id: id },

                    success: function (response) {

                        alert('Deleted');
                        $('#' + id).remove();

                    }
                });
            }
            else { }
        }

        function getSobyCustomer() {
            $('#so_no').empty();
            let customer_id = $('#customer_id').val();
            $.ajax({
                url: '{{ url('/') }}/sdc/getSobyCustomer',
                type: 'Get',
                data: { customer_id: customer_id },
                success: function (response) {
                    $('#so_no').append(response);
                }
            });
        }

        function viewRangeWiseDataFilter() {

            var so_no = $('#so_no').val();
            var m = '{{$m}}';
            $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '{{url("/sales/CreateSalesTaxInvoiceBySO")}}',
                type: 'Get',
                data: { so_no: so_no, m: m },
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }

        function sales_tax(sales_order_id, delivery_note_id, m) {

            var base_url = '<?php echo URL::to('/'); ?>';
            window.location.href = base_url + '/sales/CreateSalesTaxInvoice?sales_order_id=' + sales_order_id + '&&' + 'delivery_note_id=' + delivery_note_id + '&&' + 'm=' + m;
        }
        $(document).ready(function () {
            $('#customer_id').select2();
            $('#so_no').select2();
        });

    </script>

@endsection