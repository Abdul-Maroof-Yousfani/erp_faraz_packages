<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>



<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well_N">
                            <div class="dp_sdw">    
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Create Purchase Order Form</span>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                                <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                                <input type="hidden" name="pageType" id="pageType" value="1" readonly="readonly" class="form-control" />
                                <input type="hidden" name="parentCode" id="parentCode" value="<?php echo $_GET['parentCode'];?>" readonly="readonly" class="form-control" />

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Create PO Against</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField select2" name="po_against" id="po_against" onchange="getPendingPurchaseRequest(this.value)">
                                            <option value="1">Purchase Request</option>
                                            <option value="2">Purchase Quotation</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pr_div">
                                        <label class="sf-label">PR No.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField select2" name="pr_no" id="pr_no" style="width: 100%;">
                                            <option value="">Select PR</option>
                                            <?php $__currentLoopData = $pr_no; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->demand_no); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide" id="dropdownContainer"> </div>
                                
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                        <label class="sf-label">Department / Sub Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control" name="paramOne" id="paramOne">
                                            <option value="">Select Department</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($y->department_name); ?>" value="<?php echo e($y->id); ?>">
                                                    <?php
                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                    ?>
                                                    <?php $__currentLoopData = $subdepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $y2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($y2->id); ?>"><?php echo e($y2->sub_department_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                        <label>From Date :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center hide"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                        <label>To Date :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                        <input type="button" value="Search" class="btn btn-primary" onclick="createPurchaseRequestDetailForm();" style="margin-top: 32px;" />
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="createPurchaseRequestDetailForm"></div>    
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2();
        function getPendingPurchaseRequest(value) {
            if (value == 1) {
                $('.pr_div').show();
                $('#dropdownContainer').addClass('hide');
                $('#pr_no').addClass('requiredField')
                $('#pq_no').removeClass('requiredField')
            } else if (value == 2) {
                $('.pr_div').hide();
                $('#pr_no').removeClass('requiredField')
                $('#pq_no').addClass('requiredField')
                $.ajax({
                    url: '<?php echo e(url('/')); ?>/stdc/getPendingPurchaseRequest',
                    method: 'GET',
                    data: { value: value },
                    success: function(response) {
                        $('#dropdownContainer').removeClass('hide');
                        let dropdown = '<label class="sf-label">Quotation No.</label><span class="rflabelsteric"><strong>*</strong></span><select style="width: 100%;" class="form-control select2" name="pq_no" id="pq_no">';
                        dropdown += '<option value="">Select Quotation</option>';
                        
                        response.forEach(function(item) {
                            dropdown += '<option value="' + item.id + '">' + item.voucher_no + '</option>';
                        });

                        dropdown += '</select>';
                        $('#dropdownContainer').html(dropdown);
                        $('#pq_no').select2();
                       
                    },
                    error: function() {
                        alert('error');
                    }
                });
            }
        }
        
        function createPurchaseRequestDetailForm() {
            var po_against = $('#po_against').val();
            if(po_against == 1) {
                var pr_no = $('#pr_no').val();
            } else if(po_against == 2) {
                var pr_no = $('#pq_no').val();

            }
            var m = $('#m').val();
            if(pr_no != '') {
                $('#createPurchaseRequestDetailForm').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo e(url('/')); ?>/stdc/createPurchaseRequestDetailForm',
                    method: 'GET',
                    data:{ po_against:po_against,pr_no:pr_no,m:m },
                    success: function(response){
                    $('#createPurchaseRequestDetailForm').html(response)
                    },
                    error: function(){
                        alert('error');
                    },
                });
            } else {
                alert('Please select PR No.')
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>