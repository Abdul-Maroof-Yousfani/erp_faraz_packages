<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$m =  Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

@extends('layouts.default')
@section('content')
@include('select2')

<div class="well_N">
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

              <div class="row">

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>Production Plan</label>
                        <select id="pp_id" class="form-control select2">
                            <option value="">Select Production Plan</option>
                            
                            @foreach($ProductionPlane as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->order_no }}</option>
                            @endforeach
                          
                        </select>
                    </div>
                    
                    

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>From Date</label>
                        <input type="Date" id="fromDate" min="" max="{{$current_date}}" class="form-control" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label>To Date</label>
                        <input type="Date" id="toDate" min="" max="{{$current_date}}" class="form-control" />
                    </div>
                   
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">
                        <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="getMaterialIssueListAjax()" style="margin-top: 32px;" />
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
                        <div class="row" id="">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="userlittab table table-bordered sf-table-list" id="EmpExitInterviewList">
                                        <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-2">Production Plan</th>
                                            <th class="text-center col-sm-2">Mr no</th>
                                            <th class="text-center">Issuance date</th>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">qty</th>

                                            <th class="text-center col-sm-2">Action</th>
                                        </thead>
                                        <tbody id="data">
                                            
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
<script>




$('.select2').select2();

  

  function getMaterialIssueListAjax()
  {
      let pp_id = $('#pp_id').val();
      let fromDate = $('#fromDate').val();
      let toDate = $('#toDate').val();

      if(!pp_id)
      {
        alert('please select production plan');
        return

      }
      

      $('#data').html('<tr class="loader"></tr>');

      $.ajax({
              url: '{{ url("/selling/getMaterialIssueList") }}',
              type: "GET",
              data: {
                        pp_id:pp_id,
                        fromDate: fromDate,
                        toDate,toDate
                    },
              success: function (data)
              {

                  $("#data").html(data);

              }
          });
  }
</script>
<script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection
