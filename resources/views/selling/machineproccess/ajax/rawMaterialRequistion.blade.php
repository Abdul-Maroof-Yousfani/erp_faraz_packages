<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m = Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$count = 1;
?>

  <div class="dp_sdw">
    <div class="panel">
      <div class="panel-body">
        <div class="headquid">
          <div class="row">
            <div class="col-md-6">
                <div class="Quotation_head">
                  <h2 class="subHeadingLabelClass">RAW MATERIAL REQUISITION</h2>
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
                                <h2 class="subHeadingLabelClass">RAW MATERIAL REQUISITION</h2>
                                <div class="cons hide">
                                    <p>CONTRACT NO. 786/PIPE/09/2023-24</p>
                                </div>



                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="tect_Cc2">
                                            <p>TO</p>
                                            <p>STORE MANAGER</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="tect_Cc">
                                            <p>DATE :</p>
                                            <div class="tes">
                                                <p>{{ $issuance_date }}</p>
                                            </div>
                                        </div>
                                        <div class="tect_Cc">
                                            <p>CABLE SIZE : </p>
                                            <div class="tes">
                                                <p>{{ $mainData->sub_ic }}( {{$mainData->wall_thickness_1}}MM)</p>
                                            </div>
                                        </div>
                                        <div class="tect_Cc hide">
                                            <p>BATCH QTY. : </p>
                                            <div class="tes">
                                                <p>50.00</p>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>


                            
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2 class="subHeadingLabelClass">PLEASE ISSUE THESE RAW MATERIAL FOR WORK ORDER NO. {{ $mainData->purchase_order_no }}</h2>
                                    <div class="row">
                                        <table class="userlittab table table-bordered sf-table-list">
                                            <thead>
                                            <tr>
                                                <th>S NO </th>
                                                <th>PRODUCT NAME</th>
                                                <th>QTY. REQUIRED</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($list as $key => $value)
                                                <tr>
                                                    <td>
                                                        {{ $count++ }}
                                                    </td>
                                                    <td>
                                                        {{ $value->item }}
                                                    </td>
                                                    <td>
                                                        {{ $value->qty }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                   
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                        <br>
                                        <br>
                                        <div class="signature">
                                            <p>____________________________</p>
                                            <div class="snaems">
                                                <p>PRODUCTION MANAGER</p>
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
