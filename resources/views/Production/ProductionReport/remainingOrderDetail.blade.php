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
                  <h2 class="subHeadingLabelClass">LIST OF REMAINING ORDERS - OFC</h2>
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

                        <div class="row mb-gap">
                          
                          
                          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label>Sale Order</label>
                              <select id="so_id" class="form-control select2">
                                  <option value="">Select Sale Order</option>
                                  
                                  @foreach($Sales_Order as $key => $value)
                                      <option value="{{ $value->id }}">{{ $value->so_no }}</option>
                                  @endforeach
                                
                              </select>
                          </div>

                          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label >Customer</label>
                              <select  id="customer" class="form-control select2">
                                      <option value="">Select Customer</option>
                                      @foreach (CommonHelper::get_customer() as $customer)
                                          <option value="{{$customer->id}}" id="op{{$customer->id}}">{{$customer->name}}</option>
                                      @endforeach
                                  </select> 
                          </div>
                          
                          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label>Production Plan</label>
                              <select id="pp_id" class="form-control select2">
                                  <option value="">Select Production Plan</option>
                                  
                                  @foreach($ProductionPlane as $key => $value)
                                      <option value="{{ $value->id }}">{{ $value->order_no }}</option>
                                  @endforeach
                                
                              </select>
                          </div>
                          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                              <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="remainingOrderDetailAjax()" style="margin-top: 32px;" />
                          </div>
                      </div>
                      <div class="lineHeight">&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="table-reponsive" id="data">
                                        
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

$('.select2').select2();

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

  function remainingOrderDetailAjax()
  {
      let pp_id = $('#pp_id').val();
      let so_id = $('#so_id').val();
      let customer = $('#customer').val();

      $('#data').html('<tr class="loader"></tr>');

      $.ajax({
              url: '{{ url("/Production/Report/remainingOrderDetail") }}',
              type: "GET",
              data: {
                        pp_id:pp_id,
                        customer:customer,
                        so_id:so_id,
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
