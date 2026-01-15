<?php
   use App\Helpers\PurchaseHelper;
   use App\Helpers\CommonHelper;
   use App\Helpers\ProductionHelper;
   ?>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')
@include('loader')
<?php echo Form::open(array('url' => 'prad/insert_ppc'));?>
<div class="container-fluid">
   <div class="row well_N">
      <div class="col-md-12 dp_sdw">
         <div style="display: none" class="" id="main">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="well">
                  <div class="row">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Production Plan </span>
                     </div>
                  </div>
                  <div class="lineHeight">&nbsp;</div>
                  <div class="row">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                           <div class="panel-body">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <?php                   $order_no=ProductionHelper::ppc_no(date('y'),date('m')); ?>
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">PPC No <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly value="{{$order_no}}" name="order_no" id="order_no" type="text" class="form-control required_sam">
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Order Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input type="date" value="{{date('Y-m-d')}}" name="order_date" id="order_date" class="form-control required_sam">
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                    <label class="sf-label">User<span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input readonly  value="{{ucfirst(Auth::user()->name)}}" id="user" type="text" class="form-control required_sam">
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Start Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input onchange="check_date()"  min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" required type="date" name="start_date" id="start_date"  class="form-control required_sam">
                                 </div>
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">End Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input onchange="check_date()"  required type="date" name="end_date" id="end_date"  class="form-control required_sam">
                                    <span id="date_err" style="color: lightcoral"></span>
                                 </div>
                              </div>
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Type <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <select onchange="get_type()" name="type" id="type" class="form-control select2 required_sam" >

                                       <option  value="1">Standard</option>

                                    </select>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                    <label class="sf-label">Status <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <select name="status" id="status" class="form-control select2 required_sam" >
                                       <option value="1">Planned</option>
                                    </select>
                                 </div>
                                 <?php $so_data=DB::Connection('mysql2')->table('sales_order as a')
                                    ->join('customers as b','a.buyers_id','=','b.id')
                                    ->where('a.status',1)
                                    ->select('a.so_no','a.id','b.name','a.buyers_id')
                                     ->get();
                                    ?>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                    <label class="sf-label">Sales Order <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <select onchange="get_customer_name()" disabled name="so_no" id="so_no" class="form-control select2 required_sam" >
                                       <option value="0*0*0">Select</option>
                                       @foreach($so_data as $row)
                                       <option value="{{$row->id.'*'.$row->name.'*'.$row->buyers_id}}">{{$row->so_no}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                    <label  class="sf-label">Customer <span class="rflabelsteric"><strong>*</strong></span></label>
                                    <input id="customer_name" name="customer_name" readonly type="text" class="form-control" value=""/>
                                 </div>
                              </div>
                              <div class="lineHeight">&nbsp;&nbsp;</div>
                              <div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="well">
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <span class="subHeadingLabelClass">Plan Detail (Step 1) </span>
                        </div>
                     </div>
                     <div class="panel">
                        <div class="panel-body">
                           <div class="table-responsive" id="data">
                              <table style="width: 80%;margin: auto" class="table table-bordered">
                                 <div class="text-right">
                                    <button type="button" onclick="add_more()" class="btn btn-primary">Add More</button>
                                 </div>
                                 <thead>
                                    <tr>
                                       <th  class="text-center">Sr No</th>
                                       <th style="width: 80%" style="" class="text-center">Product Name</th>
                                       <th style="width: 40%"  class="text-center  hide">Route</th>
                                       <th style="width: 20%"  class="text-center">Planned Quantity</th>
                                    </tr>
                                 </thead>
                                 <tbody id="append">
                                    <tr id="" class="">
                                       <td class="text-center">1</td>
                                       <td>
                                          <select onchange="get_route('{{1}}')" class="form-control select2 required_sam product" name="product[]" id="product1">
                                             <option value="">select</option>
                                             @foreach($data as $row)
                                             <option value="{{$row->finish_goods}}">{{CommonHelper::get_item_name($row->finish_goods)}}</option>
                                             @endforeach
                                          </select>
                                       </td>
                                       <td class="hide">
                                          <select class="form-control select2" name="route[]" id="route1">
                                             <option value="">select</option>
                                          </select>
                                       </td>
                                       <td><input type="number" id="planned_qty1" name="planned_qty[]" class="form-control required_sam" step="any"></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="text-center">
               <input type="submit" id="subm" class="btn btn-success"/>
            </div>
         </div>
      </div>
   </div>
</div>
<?php echo Form::close();?>
<script>
   $(document).ready(function(){
       $('#product1').select2();
       $('#so_no').select2();
   });

   var countt=1;
   function add_more()
   {
       countt++;
       $('#append').append(
               '<tr class="text-center" id="tr'+countt+'" >' +
               '<td class="text-center">'+countt+'</td>'+
               '<td><select style="text-align: left" onchange="get_route('+countt+')" class="form-control select2 product" name="product[]" id="product'+countt+'"><option value="">select</option>@foreach($data as $row)<option value="{{$row->finish_goods}}">{{CommonHelper::get_item_name($row->finish_goods)}}</option>@endforeach'+
               '</select></td>'+
                '<td class="hide"><select class="form-control select2" name="route[]" id="route'+countt+'">'+
                '<option value="">select</option></select></td>'+
               '<td><input type="number" id="planned_qty'+countt+'" name="planned_qty[]" class="form-control" step="any"></td>'+
               '<td class="text-center"> <input type="button" onclick="removeeeee('+countt+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
               '</tr>');


   $('#product'+countt).select2();
   }


   function removeeeee(number)
   {

       $('#tr'+number).remove();
   }

   function get_type()
   {
      var type= $('#type').val();

       if (type==2)
       {
           $('#so_no').prop("disabled", false);
       }
       else
       {
           $('#so_no').prop("disabled", true);
           $('#so_no').prop("disabled", true);
           $('#customer_name').val('');
           $('#so_no').val('0*0*0').trigger('change');

       }
   }

   function get_customer_name()
   {
       var so_no= $('#so_no').val();
       so_no=so_no.split('*');
       $('#customer_name').val(so_no[1]);
   }


   $("form").submit(function(e) {

       // avoid to execute the actual submit of the form.

       var validate=validate_sam();
       if (validate!=false)
       {

        //    $("#data").addClass("loader");
        //    $('.loader').show();
        //   $('#so_no').prop("disabled", false);
        //    var form = $(this);
        //    var url = form.attr('action');

        //    $.ajax({
        //        type: "GET",
        //        url: url,
        //        data: form.serialize(), // serializes the form's elements.
        //        success: function(response)
        //        {

        //            $("#data").removeClass("loader");
        //            $('#data').html(response);
        //            $('.subHeadingLabelClass').text('Plan Detail (Step 2)');
        //            $('#so_no').prop("disabled", true);
        //            $('#subm').prop("disabled", true);
        //        },
        //        error: function(data, errorThrown)
        //        {
        //            //   $('.hidee').prop('disabled', false);

        //        }
        //    });


       }
       else
       {
           e.preventDefault();
       }


   });

   $(".product").change(function()
   {



   });

   function get_route(number)
   {
       var product_id=$('#product'+number).val();



       $.ajax({
           type: "GET",
           url: '{{url('production/get_route')}}',
           data: {product_id:product_id}, // serializes the form's elements.
           success: function(response)
           {

               $('#route'+number).html(response);
           },
           error: function(data, errorThrown)
           {


           }
       });
   }

   function check_date()
   {
       var start_date=    $('#start_date').val();
       var end_date=    $('#end_date').val();

       if (end_date <=start_date)
       {
           $('#date_err').html('End Date Should Be Greater than Start Date');
       }
       else
       {
           $('#date_err').html('');
       }
   }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
