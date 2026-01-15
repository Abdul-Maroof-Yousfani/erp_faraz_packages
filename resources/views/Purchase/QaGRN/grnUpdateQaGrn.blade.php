<?php
use App\Helpers\CommonHelper;
$i = 1;
?>
@extends('layouts.default')
@section('content')
@include('select2')

<style>
    .my-lab label {
        padding-top: 0px;
    }
</style>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form action="{{route('QaGrn.update', $QcGrn->id)}}" method="post" id="qaGrnForm" onsubmit="return validateQcForm()">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Edit QC Against GRN</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>GRN No</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="grn_id" class="form-control" id="grn_id" onchange="getGrnDetails(this.value);" disabled>
                                                                        <option value="">Select GRN</option>
                                                                        @foreach($grns as $key => $val)
                                                                            <option value="{{ $val->id }}" 
                                                                                data-po_no="{{ $val->po_no }}" 
                                                                                data-supplier_id="{{ $val->supplier_id }}"
                                                                                {{ $QcGrn->grn_id == $val->id ? 'selected' : '' }}>
                                                                                {{ $val->grn_no . " - " . $val->grn_date . " - " . ($val->supplier_name ?? '') }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input readonly name="grn_id" type="hidden" value="{{ $QcGrn->grn_id }}">
                                                                    <input readonly name="po_no" id="po_no" class="form-control" type="hidden" value="">
                                                                    <input readonly name="supplier_id" id="supplier_id" class="form-control" type="hidden" value="{{ $QcGrn->supplier_id }}">
                                                                    <input readonly name="new_pv_id" id="new_pv_id" class="form-control" type="hidden" value="{{ $QcGrn->new_pv_id }}">
                                                                </div>
                                                            </div>

                                                            <div class="form-group" id="grn_data_item_group">
                                                                <div class="col-md-4">
                                                                    <label>GRN Item</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="grn_data_id" class="form-control" id="grn_data_id" onchange="getGrnDetails({{ $QcGrn->grn_id }}, {{ $QcGrn->id }});">
                                                                        <option value="">Select GRN Item</option>
                                                                    </select>
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">QC Date</label>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_grn_date" value="{{ $QcGrn->qc_grn_date }}"
                                                                        class="form-control" type="date" required>
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>QC by</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_by" value="{{ $QcGrn->qc_by }}" class="form-control"
                                                                        type="text" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 padt" id="addQCForm">
                                                        
                                                    </div>
    
                                                    <div class="col-md-12 padtb text-right">
                                                        <div class="col-md-9"></div>
                                                        <div class="col-md-3 my-lab">
                                                            <button type="submit" class="btn btn-primary mr-1"
                                                                id="btn" data-dismiss="modal">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        // Load existing QC form data on page load
        var grn_id = $('#grn_id').val();
        var qc_grn_id = {{ $QcGrn->id }};
        
        if (grn_id) {
            // First load GRN data items
            $.ajax({
                url: '{{ url('/') }}/purchase/QaGrn/getGrnDataItems',
                type: 'GET',
                data: { grn_id: grn_id },
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        var options = '<option value="">Select GRN Item</option>';
                        $.each(response.data, function(index, item) {
                            var itemName = item.item_name || item.description || 'Item #' + item.id;
                            var qty = item.purchase_recived_qty || 0;
                            options += '<option value="' + item.id + '">' + 
                                       itemName + ' (Qty: ' + qty + ')' + 
                                       '</option>';
                        });
                        $('#grn_data_id').html(options);
                        $('#grn_data_item_group').show();
                        
                        // Select first item and load QC form
                        if (response.data.length > 0) {
                            var firstItem = response.data[0];
                            $('#grn_data_id').val(firstItem.id);
                            loadQcForm(grn_id, firstItem.id, qc_grn_id);
                        }
                    }
                }
            });
        }
    });

    function getGrnDetails(grn_id, qc_grn_id) {
        if (!grn_id) {
            $('#addQCForm').html('');
            return;
        }

        // Get the selected GRN data item
        var grn_data_id = $('#grn_data_id').val();
        if (!grn_data_id) {
            // If no item selected, try to get the first item from the existing QC GRN
            $.ajax({
                url: '{{ url('/') }}/purchase/QaGrn/getGrnDataItems',
                type: 'GET',
                data: { grn_id: grn_id },
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        var firstItem = response.data[0];
                        loadQcForm(grn_id, firstItem.id, qc_grn_id);
                    }
                }
            });
        } else {
            loadQcForm(grn_id, grn_data_id, qc_grn_id);
        }
    }

    function loadQcForm(grn_id, grn_data_id, qc_grn_id) {
        $.ajax({
            url: '{{ url('/') }}/purchase/grnGetQcValueForm',
            type: 'GET',
            data: { 
                id: grn_id,
                grn_id: grn_id,
                grn_data_id: grn_data_id,
                qc_grn_id: qc_grn_id || 0
            },
            success: function (data) {
                $('#addQCForm').html(data);
                document.getElementById("btn").disabled = false;
            },
            error: function() {
                alert('Error loading QC form');
            }
        });
    }

    function checkedCheckBox(e) {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let grn_id = $('#grn_id').val();

        if (allCheckBox.length > 0) {
            if (e.checked) {
                allCheckBox.forEach(function (checkbox) {
                    if (checkbox.type == 'checkbox') {
                        checkbox.checked = true;
                        var testId = checkbox.getAttribute('data-test-id');
                        if (testId) {
                            $('#test_value' + testId).attr('required', true);
                            $('#test_status' + testId).attr('required', true);
                            $('#test_type' + testId).attr('required', true);
                            $('#remarks' + testId).attr('required', true);
                            $('#checkBox' + testId).val(1);
                        }
                        document.getElementById("btn").disabled = false;
                    }
                })
            }
            else {
                allCheckBox.forEach(function (checkbox) {
                    if (checkbox.type == 'checkbox') {
                        checkbox.checked = false;
                        var testId = checkbox.getAttribute('data-test-id');
                        if (testId) {
                            $('#test_value' + testId).removeAttr('required');
                            $('#test_status' + testId).removeAttr('required');
                            $('#test_type' + testId).removeAttr('required');
                            $('#remarks' + testId).removeAttr('required');
                            $('#test_value' + testId).val('');
                            $('#test_status' + testId).val('');
                            $('#test_type' + testId).val('');
                            $('#remarks' + testId).val('');
                            $('#checkBox' + testId).val(0);
                        }
                        document.getElementById("btn").disabled = true;
                    }
                })
            }
        }
    }

    function setValueOnCheckBox(e, count) {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let grn_id = $('#grn_id').val();

        let flag = true;
        allCheckBox.forEach(function (e) {
            if (e.type == 'checkbox') {
                let checkValue = e.checked;

                if (checkValue) {
                    document.getElementById("btn").disabled = false;
                    flag = false;
                    return;
                }
            }
        });

        if (flag) {
            document.getElementById("btn").disabled = true;
        }

        if (e.checked) {
            $('#checkBox' + count).val(1);
            $('#test_value' + count).attr('required', true);
            $('#test_status' + count).attr('required', true);
            $('#test_type' + count).attr('required', true);
            $('#remarks' + count).attr('required', true);
        }
        else {
            $('#checkBox' + count).val(0);
            $('#test_value' + count).removeAttr('required');
            $('#test_status' + count).removeAttr('required');
            $('#test_type' + count).removeAttr('required');
            $('#remarks' + count).removeAttr('required');
            $('#test_value' + count).val('');
            $('#test_status' + count).val('');
            $('#test_type' + count).val('');
            $('#remarks' + count).val('');
        }
    }

    function validateQcForm() {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let isValid = true;
        let errorMessages = [];

        allCheckBox.forEach(function (checkbox) {
            if (checkbox.type == 'checkbox' && checkbox.checked) {
                var testId = checkbox.getAttribute('data-test-id');
                if (testId) {
                    var testValue = $('#test_value' + testId).val();
                    var testStatus = $('#test_status' + testId).val();
                    var testType = $('#test_type' + testId).val();
                    var remarks = $('#remarks' + testId).val();
                    var testName = checkbox.closest('tr').querySelector('td:nth-child(3)').textContent.trim();
                    
                    if (!testValue || testValue.trim() === '') {
                        isValid = false;
                        errorMessages.push('Test value is required for: ' + testName);
                    }
                    if (!testStatus || testStatus === '') {
                        isValid = false;
                        errorMessages.push('Test status is required for: ' + testName);
                    }
                    if (!testType || testType === '') {
                        isValid = false;
                        errorMessages.push('Test type is required for: ' + testName);
                    }
                    if (!remarks || remarks.trim() === '') {
                        isValid = false;
                        errorMessages.push('Remarks is required for: ' + testName);
                    }
                }
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields for checked tests:\n\n' + errorMessages.join('\n'));
            return false;
        }

        return true;
    }
</script>

@endsection
