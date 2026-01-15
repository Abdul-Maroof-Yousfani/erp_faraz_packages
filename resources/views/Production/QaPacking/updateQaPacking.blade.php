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
                                    <form action="{{route('QaPacking.update', $QaPacking->id)}}" method="post" id="qaPackingForm" onsubmit="return validateQcForm()">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Edit QC Against Production</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>Production Plan</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="material_requisition_id" class="form-control" id="material_requisition_id" onchange="getQcValueForm(this.value);" disabled>
                                                                        <option value="">Select</option>
                                                                        @foreach($material_requisition as $key => $val)
                                                                            <option value="{{ $val->id }}" data-value="{{ $val->pp_id }}" {{ $QaPacking->material_requisition_id == $val->id ? 'selected' : '' }}>{{ $val->order_no . " - " . $val->order_date . " - " . $val->sub_ic }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input readonly name="pp_id" id="pp_id" class="form-control" type="hidden" value="{{ $QaPacking->production_plan_id }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Qc Date</label>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_packing_date" value="{{ $QaPacking->qc_packing_date }}"
                                                                        class="form-control" type="date">
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>QC by</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_by" value="{{ $QaPacking->qc_by }}" class="form-control"
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
        var material_requisition_id = $('#material_requisition_id').val();
        if (material_requisition_id) {
            getQcValueForm(material_requisition_id);
        }
    });

    function getQcValueForm(material_requisition_id) {
        var pp_id = $('#material_requisition_id option:selected').attr('data-value');
        $('#pp_id').val(pp_id);

        $.ajax({
            url: '{{ url('/') }}/Production/QaPacking/getQcValueForm',
            type: 'Get',
            data: { 
                material_requisition_id: material_requisition_id,
                qc_packing_id: {{ $QaPacking->id }}
            },
            success: function (data) {
                $('#addQCForm').html(data);
                // Enable button if there are checked items
                var hasChecked = $('.checkbox[type="checkbox"]:checked').length > 0;
                if (hasChecked) {
                    $('#btn').prop('disabled', false);
                }
            }
        });
    }

    function checkedCheckBox(e) {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let material_requisition_id = $('#material_requisition_id').val();

        if (allCheckBox.length > 0) {

            if (e.checked) {
                allCheckBox.forEach(function (checkbox) {
                    if (checkbox.type == 'checkbox') {
                        checkbox.checked = true;
                        var testId = checkbox.getAttribute('data-test-id');
                        if (testId) {
                            $('#test_value' + testId).attr('required', true);
                            $('#test_status' + testId).attr('required', true);
                            $('#checkBox' + testId).val(1);
                        }
                        document.getElementById("btn").disabled = false;
                    } else {
                        checkbox.value = 1;
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
                            $('#test_value' + testId).val('');
                            $('#test_status' + testId).val('');
                            $('#checkBox' + testId).val(0);
                        }
                        document.getElementById("btn").disabled = true;
                    } else {
                        checkbox.value = 0;
                    }
                })
            }
        }

        if (!material_requisition_id) {
            document.getElementById("btn").disabled = true;
        }
    }

    function setValueOnCheckBox(e, count) {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let material_requisition_id = $('#material_requisition_id').val();

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

        if (!material_requisition_id) {
            document.getElementById("btn").disabled = true;
        }

        if (e.checked) {
            $('#checkBox' + count).val(1);
            $('#test_value' + count).attr('required', true);
            $('#test_status' + count).attr('required', true);
        }
        else {
            $('#checkBox' + count).val(0);
            $('#test_value' + count).removeAttr('required');
            $('#test_status' + count).removeAttr('required');
            $('#test_value' + count).val('');
            $('#test_status' + count).val('');
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
                    var testName = checkbox.closest('tr').querySelector('td:nth-child(3)').textContent.trim();
                    
                    if (!testValue || testValue.trim() === '') {
                        isValid = false;
                        errorMessages.push('Test value is required for: ' + testName);
                    }
                    
                    if (!testStatus || testStatus === '') {
                        isValid = false;
                        errorMessages.push('Test status is required for: ' + testName);
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
