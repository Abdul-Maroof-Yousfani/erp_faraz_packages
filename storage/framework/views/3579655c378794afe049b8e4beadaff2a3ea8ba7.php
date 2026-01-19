<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m =  Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

 <?php $__env->startSection('content'); ?>
<div class="well_N">
  <div class="dp_sdw">
    <div class="panel">
      <div class="panel-body">
        <div class="headquid">
          <div class="row">
            <div class="col-md-6">
                <div class="Quotation_head">

                  <h2 class="subHeadingLabelClass">Quotation List</h2>
                </div>
              </div>
              <div class="col-md-6 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
    
                <?php echo CommonHelper::displayExportButton('demandVoucherList','','1')?>
              </div>
            
          </div>
        </div>
        <hr style="border:1px solid #ddd">
        <div class="row">
       
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
            
          

              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>From Date</label>
                  <input
                    type="Date"
                    name="fromDate"
                    id="fromDate"
                    max="<?php echo $current_date;?>"
                    value="<?php echo $currentMonthStartDate;?>"
                    class="form-control"
                  />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                  <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input
                    type="text"
                    readonly
                    class="form-control text-center"
                    value="Between"
                  />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>To Date</label>
                  <input
                    type="Date"
                    name="toDate"
                    id="toDate"
                    max="<?php echo $current_date;?>"
                    value="<?php echo $currentMonthEndDate;?>"
                    class="form-control"
                  />
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 " style="margin-top: 35px;">
                 <a href="#" class="btn btn-primary"
                 onclick="get_data();">Search</a>
                </div>
              </div>
              <div class="lineHeight">&nbsp;</div>
              <div id="printDemandVoucherList">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                      <div class="panel-body">
                        <div class="headquid">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                        </div>
                     
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                              <table
                              class="userlittab table table-bordered sf-table-list"
                                id="demandVoucherList"
                              >
                                <thead>
                                  <th class="text-center">S.No</th>

                                  <th class="text-center">Quotation No</th>
                                  <th class="text-center">Quotation Date</th>
                                  <th class="text-center">Vendor</th>
                                  <th class="text-center">Ref No</th>
                                  <th class="text-center">Total Amount</th>
                                  <th class="text-center">Status</th>
                                  <th class="text-center hidden-print">
                                    Action
                                  </th>
                                </thead>
                                <tbody id="data"></tbody>
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
    </div>
  </div>
</div>
<script>



      $(document).ready(function(){

          get_data();
      });

      function delete_quotation(id) {
          if (confirm('Are you sure you want to delete this request')) {
              $.ajax({
                  url: '<?php echo e(url('/quotation/delete_quotation')); ?>',
                  type: "GET",
                  data: {
                      id: id
                  },
                  success: function(data) {
                      if (data.status == "error") {
                          alert(data.message);
                      } else if (data.status == "Success") {
                          alert(data.message);
                          get_data()
                      } else {
                          alert(data)
                      }
                  }
              });
          }
      }

  function get_data()
  {
      var from = $('#fromDate').val();
      var to = $('#toDate').val();
      $('#data').html('<tr class="loader"></tr>');

      $.ajax({
              url: '<?php echo e(url("/quotation/quotation_list_ajax")); ?>',
              type: "GET",
              data: {from: from,to,to},
              success: function (data)
              {

                  $("#data").html(data);

              }
          });
  }
</script>
<script src="<?php echo e(URL::asset('assets/custom/js/customPurchaseFunction.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>