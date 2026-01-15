<?php
use App\Helpers\NotificationHelper;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;

$demand_no = $demand->demand_no;

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
                            <h2 class="subHeadingLabelClass">Edit Purchase Request </h2>
                        </div>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'pad/updateDemandDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php //echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php //echo $_GET['parentCode']?>">
                    <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PR NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="pr_no" id="pr_no" value="{{strtoupper($demand_no)}}" />
                                                <input type="hidden" name="EditId" value="<?php echo $id?>">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">PR Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="demand_date_1" id="demand_date_1" value="<?php echo $demand->demand_date; ?>" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Ref No.</label>
                                                <input autofocus type="text" class="form-control" placeholder="Ref  No" name="slip_no_1" id="slip_no_1" value="<?php echo $demand->slip_no?>" />
                                                <input type="hidden" name="material_request_no" value="{{ $demand->material_request_no }}" />
                                                <input type="hidden" name="material_request_id" value="{{ $demand->id }}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Department / Sub Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField select2" name="sub_department_id_1" id="sub_department_id_1">
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $key => $y)
                                                    <option value="{{ $y->id}}" <?php if($demand->sub_department_id == $y->id):echo "selected"; endif;?>>
                                                        {{ $y->department_name}}
                                                    </option>
                                                    
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Type</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2" name="v_type" id="v_type">
                                                    <option value="">Select Department</option>
                                                    @foreach(NotificationHelper::get_all_type() as $row)
                                                        <option @if ($demand->p_type==$row->id) selected @endif value="{{ $row->id}}">{{ $row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="demand_type" id="demand_type">
                                        <div class="row"> </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;" class="form-control requiredField"><?php echo $demand->description?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="headquid">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <h2 class="subHeadingLabelClass">Item Details</h2>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right" style="margin-top: 20px">
                                            <div>
                                                <a href="#" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" id="">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center col-sm-2">Item</th>
                                                    <th class="text-center hide">Item Name</th>
                                                    <th class="text-center" >UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" >QTY<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Purpose</th>
                                                    <th class="text-center col-sm-2">When Required</th>
                                                    <th class="text-center hide">Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hide">Last Order QTY</th>
                                                    <th class="text-center hide">Last Received QTY</th>
                                                    <th class="text-center">History</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                <?php
                                                $Counter = 0;
                                                foreach($demand_data as $Fil):
                                                $Counter++;
                                                $SubItem = CommonHelper::get_single_row('subitem','id',$Fil->sub_item_id);
                                                $ItemDetail = CommonHelper::get_data($Fil->sub_item_id);
                                                $ItemDetail = explode(',',$ItemDetail);
                                                $main_ic = $SubItem->main_ic_id ?? 0;
                                                        

                                                ?>
                                                <tr class="RemoveRows<?php echo $Counter?> AutoNo">
                                                    <td>
                                                        <select onchange="get_sub_item('category_id{{ $Counter }}')" name="category" id="category_id{{ $Counter }}"  class="form-control category select2 normal_width">
                                                            <option value="">Select</option>
                                                        @foreach (CommonHelper::get_all_category() as $category):
                                                            <option @if($main_ic==$category->id) selected @endif value="{{ $category->id }}"> {{ $category->main_ic }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select onchange="get_item_name('{{ $Counter }}')" name="item_id[]" id="item_id{{ $Counter }}" class="form-control select2">
                                                            <option>Select</option>
                                                            @foreach ( CommonHelper::get_item_by_category($main_ic) as $item_rows)
                                                            <?php $uom = CommonHelper::get_uom_name($item_rows->uom); ?>
                                                            <option @if($item_rows->id==$Fil->sub_item_id) selected @endif value="{{ $item_rows->id.'@'.$uom.'@'.$item_rows->item_code }}">{{ $item_rows->item_code.' -- '.$item_rows->sub_ic }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="hide">
                                                        <input  readonly onchange="get_detail(this.id,'<?php echo $Counter?>')" type="text" class="form-control sam_jass" name="sub_ic_des[]" id="item_code<?php echo $Counter?>" value="<?php echo $SubItem->sub_ic ?? 0?>">
                                                    
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id<?php echo $Counter?>" value="<?php echo $ItemDetail[0]?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control requiredField" name="quantity[]" id="quantity<?php echo $Counter?>" value="<?php echo $Fil->qty?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="purpose[]" id="purpose{{ $Counter }}" value="{{ $Fil->purpose }}" />
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control" name="required_date[]" id="required_date{{ $Counter }}" value="{{ $Fil->required_date }}" />
                                                    </td>
                                                    <td class="text-center"><input type="checkbox" id="view_history<?php echo $Counter?>"></td>
                                                    <td class="text-center">
                                                        @if($Counter > 1)
                                                            <button type="button" class="btn btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('<?php echo $Counter?>')"><span class="glyphicon glyphicon-trash"></span></button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <?php
                                                endforeach;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="demandsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>
        var Counter = '<?php echo $Counter?>';


        function AddMoreDetails()
        {
            Counter++;
            var category ='category_id'+Counter;
            $('#AppnedHtml').append(
                    '<tr class="RemoveRows'+Counter+' AutoNo">' +
                     '<td>'+
                     '<select onchange="get_sub_item(`'+category+'`)" name="category" id="category_id'+Counter+'"  class="form-control category select2 normal_width">'+
                     '<option value="">Select</option>'+
                     '@foreach (CommonHelper::get_all_category() as $category)'+
                     '<option  value="{{ $category->id }}"> {{ $category->main_ic }} </option>'+
                     '@endforeach'+
                    '</select>'+
                    '</td>'+
            '<td>'+
            '<select onchange="get_item_name('+Counter+')" name="item_id[]" id="item_id'+Counter+'" class="form-control select2">'+
                '<option>Select</option>'+
              '</select>'+
                '</td>'+
                    '<td class="hide">' +
                    '<input type="text" readonly class="form-control sam_jass" name="sub_ic_des[]" id="item_code'+Counter+'">' +
                 
                    '</td>' +
                    '<td>' +
                    '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control requiredField" name="quantity[]" id="quantity'+Counter+'">' +
                    '</td>' +
                    '<td><input type="text" class="form-control" name="purpose[]" id="purpose'+Counter+'""></td>' +
                    '<td><input type="date" class="form-control" name="required_date[]" id="required_date'+Counter+'""> </td>' +
                    '<td class="hide">' +
                    '<input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock'+Counter+'">' +
                    '</td>' +
                    '<td class="hide">' +
                    '<input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty'+Counter+'">' +
                    '</td>' +
                    '<td class="hide">' +
                    '<input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty'+Counter+'">' +
                    '</td>' +
                    '<td class="text-center" style=""><input onclick="view_history(1)" type="checkbox" id="view_history'+Counter+'">' +
                    '</td><td class="text-center"><button type="button" class="btn btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')"><span class="glyphicon glyphicon-trash"></span></button></td>' +
                    '</tr>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>');



            $('#category_id'+Counter).select2();
            $('#item_id'+Counter).select2();
            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);


            $('.sam_jass').bind("enterKey",function(e){


                $('#items').modal('show');
            });
            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");
                }
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

            var v= $('#sub_item_id_1_'+id).val();


            if ($('#history_1_' + id).is(":checked"))
            {
                if (v!=null)
                {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
                }
                else
                {
                    alert('Select Item');
                }

            }





        }

        function get_item_name(index)
        {
            
         var item=   $('#item_id'+index).val();
         var uom =item.split('@');
         $('#uom_id'+index).val(uom[1]);
         $('#item_code'+index).val(uom[2]);
        }
    </script>


    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection