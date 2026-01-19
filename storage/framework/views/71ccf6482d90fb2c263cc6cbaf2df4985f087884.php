<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
$summary_approve=ReuseableCode::check_rights(400);

use App\Helpers\SalesHelper;?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>



<div style="line-height:5px;">&nbsp;</div>
<?php $var = 1; ?>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Quotation Detail </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
           
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">S.No</th>
                            <!-- <th class="text-center">Quotation No</th> -->
                            <th class="text-center">Demand No</th>
                            <th class="text-center">Required Date</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Qty</th>
                            <?php $__currentLoopData = $vendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center"><?php echo e($row->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>

                        </thead>
                            <input type="hidden" id="dept_id" value="<?php echo e($row->dept_id); ?>"/>
                            <input type="hidden" id="pr_no" value="<?php echo e($row->demand_no); ?>"/>
                            <input type="hidden"  id="p_type" value="<?php echo e($row->p_type); ?>"/>
                        <tbody>
                        <?php
            

                        $counter = 1;
                        $total_amount=0;
                        $validate = false; 
                        foreach ($demand_data  as $key => $row1):
                        ?>
                        <tr id="tr<?php echo e($row1->id); ?>" class="tex-center">
                            <td class="text-center">
                                <?php if($row1->vendor!=0): ?>
                            <p style="font-size: 11px">   <?php echo e(CommonHelper::get_supplier_name($row1->vendor)); ?></p> 
                                <?php else: ?>
                                <input name="quotation[]" id="quotation_id<?php echo e($key); ?>"  type="checkbox" value="<?php echo e($row1->quotation_id.','.$row1->id.','.$row1->master_id); ?>"/></td>
                               <?php  $validate = true; ?>
                                <?php endif; ?>
                            <td class="text-center"><?php echo e($counter++); ?></td>
                            <!-- <td class="text-center"><?php echo e($row1->voucher_no); ?></td> -->
                            <td class="text-center"><?php echo e($row1->demand_no); ?></td>
                            <td class="text-center"><?php echo e(CommonHelper::changeDateFormat($row1->required_date)); ?></td>
                    
                            <td class="text-center"><?php echo e($row1->sub_ic); ?></td>
                            <td class="text-center"><?php echo e($row1->description); ?></td>
                            <td class="text-center"> <?php echo e(number_format($row1->qty,2)); ?></td>
                            <?php $__currentLoopData = $vendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $amount=ReuseableCode::get_quotation_amount_supp_wise($row1->id,$row->vendor_id); ?>
                            <td class="text-center"> <?php echo e(number_format($amount / $row1->qty,2)); ?> <br><?php echo e(number_format($amount)); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         </tr>
                        <?php
                      
                        endforeach
                        ?>
                        
                      
                        </tbody>
                    </table>

                    <?php if($validate==true && $summary_approve==true): ?>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <select class="form-control" id="vendor">
                                    <option value="">Select</option>
                                    <?php $__currentLoopData = $vendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($row->vendor_id); ?>"><?php echo e($row->name); ?></option>  
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                             
                                <textarea id="desc" name="desc" style="width: 417px; height: 44px;" placeholder="Narration"> </textarea>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <button onclick="save()" type="submit" class="btn btn-success">Approved</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function approve(id,pr_id)
    {


     
            $.ajax({

                url:'<?php echo e(url('quotation/approve')); ?>',
                type:'GET',
                data:{id:id,pr_id:pr_id,dept_id:dept_id,pr_no:pr_no},
                success:function(response)
                {

                    if (response=='no')
                    {
                        alert('Quotation Againts This PR Alreday Approved');
                        return false;
                    }else{
                        alert('Quotation Againts This PR Approved');
                    }
                    $('#'+id).html('Approved');
                    $('#showDetailModelOneParamerter').modal('hide');
                },
                err:function(err)
                {
                    $('#data').html(err);
                }
            })

    }

        var array = [];

    function set_values()
    {
        $("input[name='quotation[]']").each(function (index, obj) {
       
        if ($("#"+obj.id).is(":checked")) 
        {
        
            if(jQuery.inArray(obj.value, array) === -1)
            {
               
                array.push(obj.value);
            }
            
        }
        else 
        {
            array = $.grep(array, function(n) {
            return n != obj.value;
            });
        }
    });


    
  
    }

    function save()
    {
        set_values();
        var vendor = $('#vendor').val();
        var desc = $('#desc').val();
        var dept_id = $('#dept_id').val();
        var pr_no = $('#pr_no').val();
        var p_type = $('#p_type').val();
        if (vendor=='' || array.length==0)
        {
            alert('Required All Fields');
            return false;
        }
        $.ajax({


                url:'<?php echo e(url('quotation/approved_quotation_summary')); ?>',
                type:'GET',
                data:{array:array,vendor:vendor,desc:desc,dept_id:dept_id,pr_no:pr_no,p_type:p_type},
                success:function(response)
                {

               
                //    $('#'+id).html('Approved');
                    $('#showDetailModelOneParamerter').modal('hide');
                    get_data();
                },
                err:function(err)
                {
                    $('#data').html(err);
                }
                })
    }

    
</script>


