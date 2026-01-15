<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export = ReuseableCode::check_rights(232);
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
  $m = $_GET['m'];
} else {
  $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');
//$export=ReuseableCode::check_rights(230); ?> @extends('layouts.default')
@section('content')
@include('select2')
<div class="well_N">
  <div class="dp_sdw">
    <div class="panel">
      <div class="panel-body">
        <div class="headquid">
          <div class="row">
            <div class="col-md-6">
              <div>
                <h2 class="subHeadingLabelClass">View Purchase Request List</h2>
              </div>

            </div>
            <div class="col-md-6 text-right">
              <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList', '', '1');?>
              <?php if ($export == true):?>
              <?php  echo CommonHelper::displayExportButton('demandVoucherList', '', '1')?>
              <?php endif;?>
            </div>
          </div>
        </div>
        <div class="row">

          <div class="lineHeight">&nbsp;</div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">


              <input type="hidden" name="functionName" id="functionName" value="pdc/filterDemandVoucherList"
                readonly="readonly" class="form-control" />
              <input type="hidden" name="tbodyId" id="tbodyId" value="filterDemandVoucherList" readonly="readonly"
                class="form-control" />
              <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
              <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly"
                class="form-control" />
              <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
              <input type="hidden" name="filterType" id="filterType" value="1" readonly="readonly"
                class="form-control" />
              <input type="hidden" name="selectSubDepartment" id="selectSubDepartment">
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label class="sf-label">Department</label>
                  <select class="form-control requiredField select2" name="selectSubDepartmentId"
                    id="selectSubDepartmentId">
                    <option value="">Select Department</option>
                    @foreach($departments as $key => $y)
            <option value="{{ $y->id}}">
              {{ $y->department_name}}
            </option>
          @endforeach
                  </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>From Date</label>
                  <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>"
                    value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="text" readonly class="form-control text-center" value="Between" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>To Date</label>
                  <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>"
                    value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                  <label>Voucher Status</label>
                  <select name="selectVoucherStatus" id="selectVoucherStatus" class="form-control select2">
                    <?php echo CommonHelper::voucherStatusSelectList();?>
                  </select>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                  <a href="#" class="btn btn-primary" onclick="viewRangeWiseDataFilter();">Search</a>
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
                                  <th class="text-center">Demand Status</th>
                                  <th class="text-center hidden-print">
                                    Action
                                  </th>
                                </thead>
                                <tbody id="filterDemandVoucherList"></tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img
                      src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Demand Voucher List'))!!} " />
                  </div> -->
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
  $('.select2').select2();
  var table = ["demand", "demand_data"];
  var id = ["id", "master_id"];

</script>

<script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection