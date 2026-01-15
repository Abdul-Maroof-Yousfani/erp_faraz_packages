<?php
use App\Helpers\CommonHelper;
$i = 1;
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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
                                    <form action="<?php echo e(route('QaGrn.store')); ?>" method="post" id="qaGrnForm" onsubmit="return validateQcForm()">
                                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>QC Against GRN</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>GRN No</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="grn_id" class="form-control" id="grn_id" onchange="loadGrnDataItems(this.value);">
                                                                        <option value="">Select GRN</option>
                                                                        <?php $__currentLoopData = $grns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($val->id); ?>" 
                                                                                data-po_no="<?php echo e($val->po_no); ?>" 
                                                                                data-supplier_id="<?php echo e($val->supplier_id); ?>"
                                                                                data-grn_no="<?php echo e($val->grn_no); ?>"
                                                                                data-grn_date="<?php echo e($val->grn_date); ?>">
                                                                                <?php echo e($val->grn_no . " - " . $val->grn_date . " - " . ($val->supplier_name ?? '')); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <input readonly name="po_no" id="po_no" class="form-control" type="hidden">
                                                                    <input readonly name="supplier_id" id="supplier_id" class="form-control" type="hidden">
                                                                    <input readonly name="new_pv_id" id="new_pv_id" class="form-control" type="hidden" value="0">
                                                                </div>
                                                            </div>

                                                            <div class="form-group" id="grn_data_item_group" style="display: none;">
                                                                <div class="col-md-4">
                                                                    <label>GRN Item</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select name="grn_data_id" class="form-control" id="grn_data_id" onchange="getGrnDetails();">
                                                                        <option value="">Select GRN Item</option>
                                                                    </select>
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">QC Date</label>
                                                                <div class="col-sm-8">
                                                                    <input name="qc_grn_date" value="<?php echo e(date('Y-m-d')); ?>"
                                                                        class="form-control" type="date" required>
                                                                </div>
                                                            </div>
    
                                                            <div class="form-group">
                                                                <div class="col-md-4">
                                                                    <label>QC by</label>
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

    function loadGrnDataItems(grn_id) {
        if (!grn_id) {
            $('#grn_data_item_group').hide();
            $('#grn_data_id').html('<option value="">Select GRN Item</option>');
            $('#addQCForm').html('');
            $('#po_no').val('');
            $('#supplier_id').val('');
            document.getElementById("btn").disabled = true;
            return;
        }

        var selectedOption = $('#grn_id option:selected');
        var po_no = selectedOption.attr('data-po_no');
        var supplier_id = selectedOption.attr('data-supplier_id');
        
        $('#po_no').val(po_no);
        $('#supplier_id').val(supplier_id);
        $('#addQCForm').html('');
        document.getElementById("btn").disabled = true;

        // Load GRN data items
        $.ajax({
            url: '<?php echo e(url('/')); ?>/purchase/QaGrn/getGrnDataItems',
            type: 'GET',
            data: { 
                grn_id: grn_id 
            },
            success: function (response) {
                if (response.success && response.data.length > 0) {
                    var options = '<option value="">Select GRN Item</option>';
                    $.each(response.data, function(index, item) {
                        var itemName = item.item_name || item.description || 'Item #' + item.id;
                        var qty = item.purchase_recived_qty || 0;
                        options += '<option value="' + item.id + '" data-sub_item_id="' + item.sub_item_id + '">' + 
                                   itemName + ' (Qty: ' + qty + ')' + 
                                   '</option>';
                    });
                    $('#grn_data_id').html(options);
                    $('#grn_data_item_group').show();
                } else {
                    alert('No items found for this GRN');
                    $('#grn_data_item_group').hide();
                }
            },
            error: function() {
                alert('Error loading GRN items');
                $('#grn_data_item_group').hide();
            }
        });
    }

    function getGrnDetails() {
        var grn_id = $('#grn_id').val();
        var grn_data_id = $('#grn_data_id').val();
        
        if (!grn_id || !grn_data_id) {
            $('#addQCForm').html('');
            document.getElementById("btn").disabled = true;
            return;
        }

        $.ajax({
            url: '<?php echo e(url('/')); ?>/purchase/grnGetQcValueForm',
            type: 'GET',
            data: { 
                id: grn_id,
                grn_id: grn_id,
                grn_data_id: grn_data_id
            },
            success: function (data) {
                $('#addQCForm').html(data);
            },
            error: function() {
                alert('Error loading QC form');
            }
        });
    }

    function getQcValueForm(grn_id) {
        $.ajax({
            url: '<?php echo e(url('/')); ?>/purchase/QaGrn/grnViewQaGrnDetail',
            type: 'Get',
            data: { grn_id: grn_id },
            success: function (data) {
                $('#addQCForm').html(data);
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
                            $('#test_type' + testId).removeAttr('required');
                            $('#remarks' + testId).removeAttr('required');
                            $('#test_value' + testId).val('');
                            $('#test_status' + testId).val('');
                            $('#test_type' + testId).val('');
                            $('#remarks' + testId).val('');
                            $('#checkBox' + testId).val(0);
                        }
                        document.getElementById("btn").disabled = true;
                    } else {
                        checkbox.value = 0;
                    }
                })
            }
        }

        if (!grn_id) {
            document.getElementById("btn").disabled = true;
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

        if (!grn_id) {
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>