<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$m =  Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>
@extends('layouts.default') @section('content')
<div class="well_N">
  <div class="dp_sdw">
    <div class="panel">
      <div class="panel-body">
        <div class="headquid">
          <div class="row">
              <div class="col-md-6">
                <div class="Quotation_head">
                  <h2 class="subHeadingLabelClass">Stock flow from</h2>
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
                        <input type="Date" name="FromDate" id="FromDate" min="" class="form-control" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>To Date</label>
                        <input type="Date" name="ToDate" id="ToDate" min="" class="form-control" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>Account Head</label>
                        <select name="AccountId" id="AccountId" class="form-control select2">
                            <option value="">Select Account</option>
                            
                            <option value=""></option>
                            
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>Voucher Status</label>
                        <select name="VoucherStatus" id="VoucherStatus" class="form-control">
                            <option value="">All</option>
                            <option value="1">Pending</option>
                            <option value="2">Approved</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                        <input type="button" value="View Range Wise Data Filter" class="btn btn-sm btn-primary" onclick="" style="margin-top: 32px;" />
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
                                <div class="row">
                                    <div class="table-reponsive">
                                        <table class="userlittab table table-bordered sf-table-list">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" style="text-align:center;">PRODUCT</th>
                                                    <th colspan="2"  style="vertical-align: middle;" class="text-center" rowspan="2">UOM</th>
                                                    <th colspan="2"  style="vertical-align: middle;" class="text-center" rowspan="2">OPENING</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">NET PURCHASE</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">INTERNAL RETURNS</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">NET SALES</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">ADJUST</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">TRANSFER</th>
                                                    <th  style="vertical-align: middle;" class="text-center" rowspan="2">CLOSING</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="1">CODE</th>
                                                    <th colspan="5">NAME</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2001005</td>
                                                    <td>C/C - AL-TAPE-46 MM</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>509.000</td>
                                                    <td></td>
                                                    <td>KGS</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>RAW MATERIAL</td>
                                                </tr>
                                            </tbody>
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
</div>
<script>



      $(document).ready(function(){

          get_data();
      });

      function delete_quotation(id) {
          if (confirm('Are you sure you want to delete this request')) {
              $.ajax({
                  url: '{{ url('/quotation/delete_quotation') }}',
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
              url: '{{ url("/quotation/quotation_list_ajax") }}',
              type: "GET",
              data: {from: from,to,to},
              success: function (data)
              {

                  $("#data").html(data);

              }
          });
  }
</script>
<script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection
