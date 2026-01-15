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
                  <h2 class="subHeadingLabelClass">RAW MATERIAL REQUIREMENT REPORT</h2>
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
              <div class="row align-items-center ">
                
              </div>
              <div class="lineHeight">&nbsp;</div>


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
                                <h2 class="subHeadingLabelClass">RAW MATERIAL REQUIREMENT REPORT</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <table class="userlittab table table-bordered sf-table-list">
                                            <thead>
                                                <tr>
                                                    <th>Raw Material</th>
                                                    <th>Stock In Hand - OFC</th>
                                                    <th>Unit</th>
                                                    <th>Gross Req. - OFC</th>
                                                    <th>Net Req. - OFC</th>
                                                    <th> Total Booked Qty. /Performa Inv. Issue</th>
                                                    <th>Total Local Purchase Orders</th>
                                                    <th>Total Under Ship. / LC Open</th>
                                                    <th>ETA</th>
                                                    <th>Total Bal. Qty. </th>
                                                    <th>Status</th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                </tr>
                                                <tr>
                                                    <td> HDPE - BLACK</td>
                                                    <td> 2,425.00</td>
                                                    <td> KGS</td>
                                                    <td> 234,412.00</td>
                                                    <td> (231,987.00</td>
                                                    <td>41,910.00 </td>
                                                    <td> -</td>
                                                    <td> 41,910.00</td>
                                                    <td> 14 Apr, 2016</td>
                                                    <td> (190,077.00)</td>
                                                    <td>SHORT </td>
                                                </tr>
                                                <tr>
                                                    <td> MDPE - BLACK</td>
                                                    <td> -</td>
                                                    <td> KGS</td>
                                                    <td> -/td>
                                                    <td> -</td>
                                                    <td>- </td>
                                                    <td> -</td>
                                                    <td> -</td>
                                                    <td>-</td>
                                                    <td> -</td>
                                                    <td>SHORT </td>
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
