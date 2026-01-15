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
                                    <form action="{{route('QaPacking.store')}}" method="post" id="qaPackingForm" onsubmit="return validateQcForm()">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>QC Against Production</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="col-md-6">
                                                            <div class="form-group hide">
                                                                <div class="col-md-4">
                                                                    <label>Sales Order</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="so_id"
                                                                        onchange="productionPlanAgainstSo(this)"
                                                                        class="form-control" id="so_id">
                                                                        <option value="">Select Sales Order</option>
    
                                                                        @foreach($Sales_Order as $key => $value)
                                                                            <option value="{{ $value->id}}">{{ $value->so_no }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group hide">
                                                                <div class="col-md-4">
                                                                    <label>Delivery Challan</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="delivery_challan_id"
                                                                        onchange="getPackingListNo(this)"
                                                                        class="form-control" id="delivery_challan_id">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>Production Plan</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="material_requisition_id" class="form-control" id="material_requisition_id" onchange="getQcValueForm(this.value);">
                                                                        <option value="">Select</option>
                                                                        @foreach($material_requisition as $key => $val)
                                                                            <option value="{{ $val->id }}" data-value="{{ $val->pp_id }}">{{ $val->order_no . " - " . $val->order_date . " - " . $val->sub_ic }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input readonly name="pp_id" id="pp_id" class="form-control" type="hidden">
                                                                </div>
                                                            </div>
                                                            <div class="form-group hide">
                                                                <div class="col-md-4">
                                                                    <label>Packing List No</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="packing_list_id"
                                                                        onchange="validationUsingDropDown()"
                                                                        class="form-control" id="packing_list_id">
                                                                    </select>
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group hide">
                                                                <div class="col-md-4">
                                                                    <label>Customer Name</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input readonly name="customer_name" id="customer_name"
                                                                        value="" class="form-control" type="text">
                                                                    <input readonly name="customer_id" id="customer_id"
                                                                        value="" class="form-control" type="hidden">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Qc Date</label>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_packing_date" value="{{date('Y-m-d')}}"
                                                                        class="form-control" type="date">
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>QC by</label>
                                                                    <!-- <label>Purchase Request No.</label> -->
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_by" value="" class="form-control"
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
                                                            <button type="submit" disabled class="btn btn-primary mr-1"
                                                                id="btn" data-dismiss="modal">Save</button>
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

    function productionPlanAgainstSo(datas) {
        $('#material_requisition_id').empty();
        var id = datas.value;
        $.ajax({
            url: '{{ url('/') }}/Production/QaPacking/productionPlanAndCustomerAgainstSo',
            type: 'Get',
            data: { id: id },
            success: function (data) {

                var select = document.getElementById("material_requisition_id");

                // Clear existing options
                select.innerHTML = "";

                // Add default option
                var defaultOption = document.createElement("option");
                defaultOption.text = "Select Production Plan";
                defaultOption.value = "";
                select.appendChild(defaultOption);

                data?.material_requisition.forEach(function (mr) {
                    var option = document.createElement("option");
                    option.value = mr.id;
                    option.setAttribute('data-value', mr.pp_id);
                    option.text = mr.order_no + " - " + mr.order_date + " - " + mr.sub_ic;

                    select.appendChild(option);
                });

                // var delivery_challan = document.getElementById("delivery_challan_id");
                //  // Clear existing options
                //  delivery_challan.innerHTML = "";
                //  // Add default option
                // var deliverydefaultOption = document.createElement("option");
                // deliverydefaultOption.text = "Select Delivery Challan";
                // deliverydefaultOption.value = "";
                // delivery_challan.appendChild(deliverydefaultOption);

                // // Loop through the array and create options
                // data?.delivery_challan.forEach(function(dc) {
                //     var option = document.createElement("option");
                //     option.value = dc.id;
                //     option.setAttribute('data-qty', dc.qty);
                //     option.setAttribute('data-uom', dc.uom_name);
                //     option.setAttribute('data-value', dc.sub_ic);
                //     option.text = dc.dc_no + " - " + dc.so_no + " ( " +dc.sub_ic+" ) ";

                //     delivery_challan.appendChild(option);
                // });


                $('#customer_id').val(data?.customerDetails?.customer_id)
                $('#customer_name').val(data?.customerDetails?.name)
                $('#po_no').val(data?.customerDetails?.purchase_order_no)

            }
        });
    }

    function getQcValueForm(material_requisition_id) {
        var pp_id = $('#material_requisition_id option:selected').attr('data-value');
        $('#pp_id').val(pp_id);

        $.ajax({
            url: '{{ url('/') }}/Production/QaPacking/getQcValueForm',
            type: 'Get',
            data: { material_requisition_id: material_requisition_id },
            success: function (data) {
                $('#addQCForm').html(data);

            }
        });
    }

    function getPackingListNo(datas) {
        var pp_id = $('#material_requisition_id option:selected').attr('data-value');
        $('#pp_id').val(pp_id)

        document.getElementById("btn").disabled = true;
        // var id = datas.value;
        var dc_id = $('#delivery_challan_id').val();
        var id = $('#material_requisition_id').val();

        if (id && dc_id) {

            $.ajax({
                url: '<?php echo url('/')?>/Production/QaPacking/getPackingListNo',
                type: 'Get',
                data: { id: id, dc_id: dc_id },
                success: function (data) {

                    var select = document.getElementById("packing_list_id");

                    // Clear existing options
                    select.innerHTML = "";

                    // Add default option
                    var defaultOption = document.createElement("option");
                    defaultOption.text = "Select Packing ";
                    defaultOption.value = "";
                    select.appendChild(defaultOption);

                    // Loop through the array and create options
                    data?.packing.forEach(function (pk) {
                        var option = document.createElement("option");
                        option.value = pk.id;
                        option.text = pk.packing_list_no;
                        select.appendChild(option);
                    });


                }
            });
        }
    }

    function checkedCheckBox(e) {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let material_requisition_id = $('#material_requisition_id').val();

        if (allCheckBox.length > 0) {

            if (e.checked) {
                allCheckBox.forEach(function (checkbox) {
                    if (checkbox.type == 'checkbox') {
                        checkbox.checked = true;
                        // Get the qa_test_id from the data attribute
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
                        // Get the qa_test_id from the data attribute
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
            // Make test_value and test_status required when checkbox is checked
            $('#test_value' + count).attr('required', true);
            $('#test_status' + count).attr('required', true);
        }
        else {
            $('#checkBox' + count).val(0);
            // Remove required attribute when checkbox is unchecked
            $('#test_value' + count).removeAttr('required');
            $('#test_status' + count).removeAttr('required');
            // Clear the values when unchecked
            $('#test_value' + count).val('');
            $('#test_status' + count).val('');
        }
    }

    // function validationUsingDropDown() {
    //     let allCheckBox = document.querySelectorAll('.checkbox');
    //     let material_requisition_id = $('#material_requisition_id').val();

    //     let flag = true;
    //     allCheckBox.forEach(function (e) {
    //         if (e.type == 'checkbox') {
    //             let checkValue = e.checked;

    //             if (checkValue) {
    //                 document.getElementById("btn").disabled = false;

    //                 flag = false;
    //                 return;
    //             }
    //         }
    //     });

    //     if (flag) {
    //         document.getElementById("btn").disabled = true;
    //     }

    //     if (!material_requisition_id) {
    //         document.getElementById("btn").disabled = true;

    //     }
    // }

    function validateQcForm() {
        let allCheckBox = document.querySelectorAll('.checkbox');
        let isValid = true;
        let errorMessages = [];

        allCheckBox.forEach(function (checkbox) {
            if (checkbox.type == 'checkbox' && checkbox.checked) {
                // Get the qa_test_id from the data attribute
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