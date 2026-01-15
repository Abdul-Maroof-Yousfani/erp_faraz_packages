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
              <h2 class="subHeadingLabelClass">Create Quotation</h2>
            </div>
            <div class="col-md-6 text-right">
              <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
  
              <?php echo CommonHelper::displayExportButton('demandVoucherList','','1')?>
            </div>
          </div>
        </div>
        <div class="row">

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
              <div class="row ">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>From Date</label>
                  <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
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

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 35px">
                  <a href="#" class="btn btn-sm btn-primary" onclick="get_data();">Search</a>
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
                              <table class="userlittab table table-bordered sf-table-list" id="demandVoucherList">
                                <thead>
                                  <th class="text-center">S.No</th>
                                  <th class="text-center">PR NO.</th>
                                  <th class="text-center">PR Date</th>
                                  <th class="text-center">Ref No.</th>
                                  <th class="text-center">Sub Department</th>
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
  $(document).ready(function () {
    get_data();
  });

  function get_data() {
    var from = $("#fromDate").val();
    var to = $("#toDate").val();
    $("#data").html('<tr class="loader"></tr>');

    $.ajax({
      url: '<?php echo e(url("/quotation/create_quotation_ajax")); ?>',
      type: "GET",
      data: { from: from, to, to },
      success: function (data) {
        $("#data").html(data);
      },
    });
  }
</script>
<script src="<?php echo e(URL::asset('assets/custom/js/customPurchaseFunction.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>