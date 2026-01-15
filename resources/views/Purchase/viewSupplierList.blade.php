<?php

use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>

    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                            @include('Purchase.' . $accType . 'purchaseMenu')
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <span class="subHeadingLabelClass">View Supplier List</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('printList', '', '1');?>
                                        <button type="button" class="btn btn-warning"
                                            onclick="tableToExcel('TableExportToCsv', 'Chart of Account List')">Export to
                                            CSV</button>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="searchSupplier" placeholder="Search by Name or Contact..." onkeyup="viewSupplierList()" />
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row panel" id="printList">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="vandertd table-bordered vander_fom" id="TableExportToCsv">
                                                        <thead>
                                                            <th class="snclas text-center">S.No</th>
                                                            <th class="text-center">Supplier Code</th>
                                                            <th class="text-center">Supplier Name</th>
                                                            <th style="width: 150px;" class="wsale2 text-center">
                                                                Address</th>
                                                            <th class="text-center">Contact Person</th>
                                                            <th class="text-center">Contact No</th>
                                                            <th class="text-center hidden-print">Action</th>
                                                        </thead>
                                                        <tbody id="viewSupplierList">
                                                        </tbody>
                                                    </table>
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
        function CreateAccount(AccId, SupplierName, SupplierId) {
            var acc_code = '';
            var headOne = '<?php echo CommonHelper::get_account_name_by_code('1');?>';
            var headTwo = '<?php echo CommonHelper::get_account_name_by_code('1');?>';
            var headThree = '<?php echo CommonHelper::get_account_name_by_code('1');?>';

            swal({
                title: 'Select Account To Create Supplier',
                input: 'select',
                inputOptions: {
                    '2-2-1': headOne,
                    '2-2-7': headTwo,
                    '2-2-8': headThree
                },
                inputPlaceholder: 'Select Account Head',
                showCancelButton: true,
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value !== '') {
                            acc_code = value;
                            resolve()

                        } else {
                            reject('You need to select account head :)')
                        }
                    })
                }
            }).then(function (result) {
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/createSupplierAccount',
                    type: "GET",
                    data: { AccId: AccId, SupplierName: SupplierName, SupplierId: SupplierId, value: acc_code },
                    success: function (data) {
                        if (data == 'yes') {
                            if (result == '2-2-1-1') { result = headOne; }
                            else if (result == '2-2-1-4') { result = headThree; }
                            else { result = headTwo; }
                            swal({
                                type: 'success',
                                html: '<b>' + SupplierName + '</b>' + '<br>' + ' Account Create againts this ' + '<br>' + '<b>' + result + '</b>'
                            });
                            $('#Btn' + SupplierId).prop('disabled', true);
                            $('#ShowHide' + SupplierId).html('Account Created');
                        }

                    }
                });

            });


        }
        function viewSupplierList() {
            $('#viewSupplierList').html('<tr><td colspan="100"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
            var m = '<?php echo $_GET['m'];?>';
            var search = $('#searchSupplier').val() || '';
            $.ajax({
                url: '<?php echo url('/')?>/pdc/viewSupplierList',
                type: "GET",
                data: { m: m, search: search },
                success: function (data) {
                    setTimeout(function () {
                        $('#viewSupplierList').html(data);
                    }, 1000);
                }
            });
        }
        viewSupplierList();

        function check_status(value) {
            //alert(value); return false;
            alert('We are checking Filer status for this request ' + value);

            var loader_img = '<img src="/assets/img/103.gif" alt="Loading" />';
            $("." + value).append(loader_img);

            var array = value.split(',');

            values = array[0];
            value = values.split('-');
            value = value[0] + value[1];
            var id = array[1];

            $.ajax({
                url: '/pdc/services',
                data: { value: value, id: id },
                type: 'GET',

                success: function (data) {

                    alert(data);
                    $("." + values).append(loader_img);

                    if (data == 1) {
                        $("." + values).text('FILER');
                    }

                    else {
                        $("." + values).text('Non FILER');
                    }

                }
            });
        }
        function delete_supp(id) {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/delete_supp',
                    type: 'Get',
                    data: { id: id },

                    success: function (response) {
                        $('#' + response).remove();

                    }
                });
            }
            else { }
        }

    </script>
@endsection