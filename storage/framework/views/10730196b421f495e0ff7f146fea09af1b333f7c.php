<?php
   $m = Session::get('run_company');
   use App\Helpers\PurchaseHelper;
   use App\Helpers\CommonHelper;
   use App\Helpers\NotificationHelper;
   ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php
   $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`demand_no`,3,length(substr(`demand_no`,3))-4),signed integer)) reg from `demand` where substr(`demand_no`,-4,2) = " . date('m') . " and substr(`demand_no`,-2,2) = " . date('y') . "")->reg;
   $demand_no = 'pr' . ($str + 1) . date('my');
   ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="well_N">
            <div class="dp_sdw">
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="headquid">
                        <h2 class="subHeadingLabelClass">Purchase Request </h2>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <?php echo Form::open(array('url' => 'pad/addDemandDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                  <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                  <input type="hidden" name="pageType" value="<?php echo e($_GET['pageType']); ?>">
                  <input type="hidden" name="parentCode" value="<?php echo e($_GET['parentCode']); ?>">
                  <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="panel">
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">PR NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                       <input readonly type="text" class="form-control requiredField" placeholder="" name="pr_no" id="pr_no" value="<?php echo e(strtoupper($demand_no)); ?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">PR Date.</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="demand_date_1" id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">Ref No. <span class="rflabelsteric"></label>
                                       <input autofocus type="text" class="form-control" placeholder="Ref No" name="slip_no_1" id="Ref No" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                       <label class="sf-label">Department / Sub Department</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <select class="form-control requiredField select2" name="sub_department_id_1" id="sub_department_id_1">
                                          <option value="">Select Department</option>
                                          <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <option value="<?php echo e($y->id); ?>">
                                             <?php echo e($y->department_name); ?>

                                          </option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                       <label class="sf-label">Type</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <select class="form-control select2" name="v_type" id="v_type">
                                          <option value="">Select Type</option>
                                          <?php $__currentLoopData = NotificationHelper::get_all_type(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </select>
                                    </div>
                                 </div>
                                 <input type="hidden" name="demand_type" id="demand_type">
                                 <div class="row">
                                 </div>
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <label class="sf-label">Description</label>
                                       <span class="rflabelsteric"><strong>*</strong></span>
                                       <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                    </div>
                                 </div>
                              </div>
                           </div>  
                          
                           <div class="headquid">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div >
                                       <h2 class="subHeadingLabelClass">Item Details</h2>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="table-responsive" id="">
                                    <table class="userlittab table table-bordered sf-table-list">
                                       <thead>
                                          <tr>
                                             <th class="text-center">Category</th>
                                             <th class="text-center col-sm-2">Item</th>
                                             <th class="text-center hide">Item Name</th>
                                             <th class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th class="text-center" >QTY<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th class="text-center">Purpose</th>
                                             <th class="text-center col-sm-2">When Required</th>
                                             <th class="text-center hide">Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th class="text-center hide">Last Order QTY</th>
                                             <th class="text-center hide">Last Received QTY</th>
                                             <th class="text-center">History</th>
                                             <th class="text-center">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody id="AppnedHtml">
                                          <tr class="AutoNo">
                                             <td>
                                                <select style="width: 100% !important;" onchange="get_sub_item('category_id1')" name="category" id="category_id1"  class="form-control category select2 requiredField">
                                                   <option value="">Select</option>
                                                   <?php $__currentLoopData = CommonHelper::get_all_category(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                   <option value="<?php echo e($category->id); ?>"> <?php echo e($category->main_ic); ?> </option>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                             </td>
                                             <td>
                                                <select style="width: 100% !important;" onchange="get_item_name(1)" name="item_id[]" id="item_id1" class="form-control requiredField select2">
                                                   <option>Select</option>
                                                </select>
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="item_code[]" id="item_code1">
                                             </td>
                                             <td>
                                                <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id1">
                                             </td>
                                             <td>
                                                <input type="text" class="form-control requiredField" name="quantity[]" id="quantity1">
                                             </td>
                                             <td>
                                                <input type="text" class="form-control" name="purpose[]" id="purpose1">
                                             </td>
                                             <td>
                                                <input min="<?php echo e(date('Y-m-d')); ?>" type="date" class="form-control" name="required_date[]" id="required_date1">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock1">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty1">
                                             </td>
                                             <td class="hide">
                                                <input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty1">
                                             </td>
                                             <td  class="text-center" ><input onclick="view_history(1)" type="checkbox" id="view_history1"></td>
                                             <td> <a href="#"  class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span> </a></td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               
                
                  <?php echo Form::close();?>
               </div>
               <div class="mp-20 text-right">
                <?php echo e(Form::submit('Submit', ['class' => 'btnn btn-success'])); ?>

             </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   var Counter = 1;
   
   
   function AddMoreDetails()
   {
   
       Counter++;
       var category ='category_id'+Counter;
       $('#AppnedHtml').append(
               '<tr class="RemoveRows'+Counter+'  AutoNo">' +
               '<td>' +
               '<select style="width: 100% !important;" onchange="get_sub_item(`'+category+'`)" name="category" id="category_id'+Counter+'"  class="form-control category select2">'+
                '<option value="">Select</option>'+
               '<?php $__currentLoopData = CommonHelper::get_all_category(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>:'+
               '<option value="<?php echo e($category->id); ?>"> <?php echo e($category->main_ic); ?> </option>'+
               '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>'+
               '</select>'+
               '<td>'+
               '<select style="width: 100% !important;" onchange="get_item_name('+Counter+')" name="item_id[]" id="item_id'+Counter+'" class="form-control select2">' +
               '<option>Select</option>'+
               '</select>'+
               '</td>' +
               '<td class="hide"><input readonly type="text" class="form-control" name="item_code[]" id="item_code'+Counter+'"></td>' +
               '<td>' +
               '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
               '</td>' +
               '<td>' +
               '<input type="text" class="form-control requiredField" name="quantity[]" id="quantity'+Counter+'">' +
               '</td>' +
               '<td><input type="text" class="form-control" name="purpose[]" id="purpose'+Counter+'""></td>' +
               '<td><input type="date" min="<?php echo e(date('Y-m-d')); ?>" class="form-control" name="required_date[]" id="required_date'+Counter+'""> </td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock'+Counter+'">' +
               '</td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty'+Counter+'">' +
               '</td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty'+Counter+'">' +
               '</td>' +
               '<td  class="text-center" style=""><input onclick="view_history('+Counter+')" type="checkbox" id="view_history'+Counter+'">' +
              
               '</td>' +
               '<td  class="text-center" style="">' +
               '<button type="button" class="btn  btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')"><span class="glyphicon glyphicon-trash"></span></button>' +
               '</td>' +
               '</tr>' +
               '</tr>' +
               '</tbody>' +
               '</table>');
       var AutoNo = $(".AutoNo").length;
       $('#span').text(AutoNo);
   
   
       $('#category_id'+Counter).select2();
       $('#item_id'+Counter).select2();
       var AutoCount = 1;
       $(".AutoCounter").each(function(){
           AutoCount++;
           $(this).html(AutoCount);
       });
   
   }
   
   function RemoveSection(Row) {
   //            alert(Row);
       $('.RemoveRows' + Row).remove();
       $(".AutoCounter").html('');
       var AutoCount = 1;
       $(".AutoCounter").each(function () {
           AutoCount++;
           $(this).html(AutoCount);
       });
       var AutoNo = $(".AutoNo").length;
       $('#span').text(AutoNo);
   }
   
   
   function clear_fiel(id)
   {
       $('#'+id).prop('readonly', false);
       $('#'+id).val('');
   
   }
   
   $('.sam_jass').bind("enterKey",function(e){
   
   
       $('#items').modal('show');
       e.preventDefault();
   
   });
   $('.sam_jass').keyup(function(e){
       if(e.keyCode == 13)
       {
           selected_id=this.id;
           $(this).trigger("enterKey");
           e.preventDefault();
   
       }
   
   });
   
   
   $('.stop').on('keyup keypress', function(e) {
       var keyCode = e.keyCode || e.which;
       if (keyCode === 13) {
           e.preventDefault();
           return false;
       }
   });
   $(function() {
   
       $(".btn-success").click(function(e){
           var purchaseRequest = new Array();
           var val;
           //$("input[name='demandsSection[]']").each(function(){
           purchaseRequest.push($(this).val());
           //});
           var _token = $("input[name='_token']").val();
           for (val of purchaseRequest) {
               jqueryValidationCustom();
               if(validate == 0){
   
                   $('#cashPaymentVoucherForm').submit();
               }
               else
               {
                   return false;
               }
           }
   
       });
   });
   
   
</script>
<script>
   function get_detail(id,number)
   {
       var item=$('#'+id).val();
   
   
       $.ajax({
           url:'<?php echo e(url('/pdc/get_data')); ?>',
           data:{item:item},
           type:'GET',
           success:function(response)
           {
   
               var data=response.split(',');
               $('#uom_id'+number).val(data[0]);
               $('#last_ordered_qty'+number).val(data[1]);
               $('#last_received_qty'+number).val(data[2]);
               $('#closing_stock'+number).val(data[3]);
   
           }
       })
   }
   
</script>
<script>
   function view_history(id)
   {
    
       var v= $('#item_id'+id).val();
   
         
       if ($('#view_history' + id).is(":checked"))
       {
        
           if (v!='Select')
           {
               
               showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
           }
           else
           {
            
           }
   
       }
   }
   
   
</script>
<script type="text/javascript">
   $('.select2').select2();
   
   
   function get_item_name(index)
   {
       
    var item=   $('#item_id'+index).val();
    var uom =item.split('@');
    $('#uom_id'+index).val(uom[1]);
    $('#item_code'+index).val(uom[2]);
   }
</script>
<script src="<?php echo e(URL::asset('assets/js/select2/js_tabindex.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>