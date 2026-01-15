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
                  <h2 class="subHeadingLabelClass">RAW MATERIAL (PIPE SECTION) CONSUMPTION REPORT</h2>
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
                                <h2 class="subHeadingLabelClass">RAW MATERIAL REQUIREMENT REPORT</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                      <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list requirement">
                                            <thead>
                                                <tr>
                                                    <th>ORDER</th>
                                                    <th>WORK ORDER </th>
                                                    <th>PARTY</th>
                                                    <th>PIPE</th>
                                                    <th>TOT.ORD. QTY (KM)</th>
                                                    <th>TOT.PROD. QTY (KM)</th>
                                                    <th>FB1460</th>
                                                    <th>HE3363</th>
                                                    <th>HE3364</th>
                                                    <th>HE4900N</th>
                                                    <th>HE2640</th>
                                                    <th>HE1490N</th>
                                                    <th>HE3490LS</th>
                                                    <th>HDPE PIPE GRADE</th>
                                                    <th>MASTER BATCH</th>
                                                    <th>HOT FOIL TAPE</th>
                                                    <th>RECYCLE PE</th>
                                                    <th>HDPE/P100</th>
                                                    <th>L5005/N</th>
                                                    <th>3460/BK </th>
                                                    <th>2630/NT</th>
                                                    <th>PIPE GRADE N</th>
                                                    <th>4985 SASOL/N</th>
                                                    <th>3461/BK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> 1000725</td>
                                                    <td>786/PIPE/01/2016-17 </td>
                                                    <td> CYBER INTERNET SERVICES</td>
                                                    <td> HDPE PIPE 63 MM</td>
                                                    <td rowspan="2"> 10.00</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>5,625.00 </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>1,300.00</td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>0.47</td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td>6,500.00</td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
                                                    <td> </td>
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
