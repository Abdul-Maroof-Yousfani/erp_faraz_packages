<?php
   $m = Session::get('run_company');
   use App\Helpers\PurchaseHelper;
   use App\Helpers\CommonHelper;
   use App\Helpers\NotificationHelper;
   ?>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="well_N">
            <div class="dp_sdw">
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="headquid">
                        <h2 class="subHeadingLabelClass">Purchase Request Against MR</h2>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <?php echo Form::open(array('url' => 'pad/addDemandDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                  <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="panel">
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                       <label class="sf-label">MR NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                       <select class="form-control select2 requiredField"name="mr_id" id="mr_no" onchange="fetchDataOfMr(this.value)">
                                        <option value="">Select Option </option>
                                            @foreach(DB::connection('mysql2')
                                                ->table('material_requests')
                                                ->leftJoin('demand', 'demand.material_request_id', '=', 'material_requests.id')
                                                ->where('material_requests.status', 1)
                                                ->where(function ($query) {
                                                    $query->whereNull('demand.material_request_id')
                                                        ->orWhere('demand.status', '!=', 1);
                                                })
                                                ->where('material_requests.material_request_status', 2)
                                                ->select('material_requests.*')
                                                ->get() as $val)
                                                <option value="{{ $val->id }}">{{ $val->material_request_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>    
                                 
                                 
                              </div>
                              
                           </div>
                           <div class="mrDataSection">
                            </div>
                          
                           
                         
                        </div>
                     </div>
                  </div>
               
                
                  <?php echo Form::close();?>
               </div>
               
            </div>
         </div>
      </div>
   </div>
</div>
<script>
     
   
   function fetchDataOfMr(val){
        var mr_id=val;
        if(mr_id==''){
            $('.mrDataSection').html('');
            return false; 
        }
        $('.mrDataSection').html('');
    
        $.ajax({
            url:'{{url('/pdc/fetchDataOfMr')}}',
            data:{mr_id:mr_id},
            type:'GET',
            success:function(response)
            {
                var data=response;
                $('.mrDataSection').html(data);
            }
        })
   }
   function AddMoreDetails()
   {   
        let Counter=0;
        $('.qty').each(function(){
            Counter++;
        });
        Counter+=1;
        
       var category ='category_id'+Counter;
       $('#AppnedHtml').append(
               '<tr class="RemoveRows'+Counter+'  AutoNo">' +
               '<td>' +
               '<select onchange="get_sub_item(`'+category+'`)" name="category" id="category_id'+Counter+'"  class="form-control category select2 normal_width">'+
                '<option value="">Select</option>'+
               '@foreach (CommonHelper::get_all_category() as $category):'+
               '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>'+
               '@endforeach'+
               '</select>'+
               '<td>'+
               '<select onchange="get_item_name('+Counter+')" name="item_id[]" id="item_id'+Counter+'" class="form-control select2">'+
               '<option>Select</option>'+
               '</select>'+
               '</td>' +
               ' <td><input readonly type="text" class="form-control" name="item_code[]" id="item_code'+Counter+'"></td>'+
               '<td>' +
               '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
               '</td>' +
               '<td>' +
               '<input type="text" class="form-control requiredField qty" name="quantity[]" id="quantity'+Counter+'">' +
               '</td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock'+Counter+'">' +
               '</td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty'+Counter+'">' +
               '</td>' +
               '<td class="hide">' +
               '<input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty'+Counter+'">' +
               '</td>' +
               '<td  class="text-center" style=""><input onclick="view_history('+Counter+')" type="checkbox" id="view_history'+Counter+'">' +
              
               '</td>' +
               '<td  class="text-center" style="">' +
               '<button type="button" class="btn  btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')"><span class="glyphicon glyphicon-trash"></span></button>' +
               '</td>' +
               '</tr>' +
               '</tr>' +
               '</tbody>' +
               '</table>');
       var AutoNo = $(".AutoNo").length;
       $('#span').text(AutoNo);
   
   
       $('#category_id'+Counter).select2();
       $('#item_id'+Counter).select2();
       var AutoCount = 1;
       $(".AutoCounter").each(function(){
           AutoCount++;
           $(this).html(AutoCount);
       });
   
   
   
   
   
   
   }
   
   function RemoveSection(Row) {
   //            alert(Row);
       $('.RemoveRows' + Row).remove();
       $(".AutoCounter").html('');
       var AutoCount = 1;
       $(".AutoCounter").each(function () {
           AutoCount++;
           $(this).html(AutoCount);
       });
       var AutoNo = $(".AutoNo").length;
       $('#span').text(AutoNo);
   }
   
   
   function clear_fiel(id)
   {
       $('#'+id).prop('readonly', false);
       $('#'+id).val('');
   
   }
   
   $('.sam_jass').bind("enterKey",function(e){
   
   
       $('#items').modal('show');
       e.preventDefault();
   
   });
   $('.sam_jass').keyup(function(e){
       if(e.keyCode == 13)
       {
           selected_id=this.id;
           $(this).trigger("enterKey");
           e.preventDefault();
   
       }
   
   });
   
   
   $('.stop').on('keyup keypress', function(e) {
       var keyCode = e.keyCode || e.which;
       if (keyCode === 13) {
           e.preventDefault();
           return false;
       }
   });
   $(function() {
   
   
   
       $(".btn-success").click(function(e){
           var purchaseRequest = new Array();
           var val;
           //$("input[name='demandsSection[]']").each(function(){
           purchaseRequest.push($(this).val());
           //});
           var _token = $("input[name='_token']").val();
           for (val of purchaseRequest) {
               jqueryValidationCustom();
               if(validate == 0){
   
                   $('#cashPaymentVoucherForm').submit();
               }
               else
               {
                   return false;
               }
           }
   
       });
   });
   
   
   
   
   
   
   
   
</script>
<script>
   function get_detail(id,number)
   {
       var item=$('#'+id).val();
   
   
       $.ajax({
           url:'{{url('/pdc/get_data')}}',
           data:{item:item},
           type:'GET',
           success:function(response)
           {
   
               var data=response.split(',');
               $('#uom_id'+number).val(data[0]);
               $('#last_ordered_qty'+number).val(data[1]);
               $('#last_received_qty'+number).val(data[2]);
               $('#closing_stock'+number).val(data[3]);
   
           }
       })
   
   
   
   }
   
</script>
<script>
   function view_history(id)
   {
    
       var v= $('#item_id'+id).val();
   
         
       if ($('#view_history' + id).is(":checked"))
       {
        
           if (v!='Select')
           {
               
               showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
           }
           else
           {
            
           }
   
       }
   
   
   
   
   
   }
   
   
</script>
<script type="text/javascript">
   $('.select2').select2();
   
   
   function get_item_name(index)
   {
       
    var item=   $('#item_id'+index).val();
    var uom =item.split('@');
    $('#uom_id'+index).val(uom[1]);
    $('#item_code'+index).val(uom[2]);
   }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection