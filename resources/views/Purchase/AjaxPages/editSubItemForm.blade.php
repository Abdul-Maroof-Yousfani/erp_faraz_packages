<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\ImportHelper;

?>


@extends('layouts.default')
@section('content')


@include('select2');
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <span class="subHeadingLabelClass">Edit Sub Item</span>
                                </div>
                            </div>
                            <hr style="border:1px solid #ddd";>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="well">
                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        {{ Form::open(array('url' => 'pad/editSubItemDetail?m='.$m.'','id'=>'addSubItemDetail')) }}
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Category :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select autofocus  name="" id="CategoryId" class="form-control select2" onchange="get_sub_category_by_id({{ $sub_item->sub_category_id }})" disabled>
                                                                    <option value="">Select Category</option>
                                                                    @foreach($categories as $key => $y)
                                                                        <option value="{{ $y->id}}" <?php if($sub_item->main_ic_id == $y->id){echo "selected";}?>>{{ $y->main_ic}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="CategoryId" value="<?php echo $sub_item->main_ic_id?>">
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Sub Category :</label>
                                                                <select autofocus  name="SubCategoryId" id="SubCategoryId" class="form-control requiredField select2">
                                                                    <option value="">Select Category</option>
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="EditId" value="{{ Request::get('id') }}"/>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Item Name :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="sub_item_name" id="sub_item_name" value="<?php echo $sub_item->sub_ic?>" class="form-control requiredField" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Item Code :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input  type="text" name="item_code" id="item_code" value="<?php echo $sub_item->item_code?>" class="form-control requiredField" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                                <label>SKU :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input  type="text" name="sku" id="sku" value="<?php echo $sub_item->sku_code?>" class="form-control" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary UOM :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select name="uom_id" id="uom_id" class="form-control requiredField select2">
                                                                    <option value="">Select UOM</option>
                                                                    @foreach($uom as $key => $row)
                                                                        <option @if($sub_item->uom == $row->id) selected @endif value="{{ $row->id}}">{{ $row->uom_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary Pack size :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input step="0.01" value="{{ $sub_item->pack_size }}" min="0" class="form-control requiredField" type="number" name="pack_size" id="pack_size">
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary Packaging Type</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select style="width: 100%" name="primary_pack_type" id="primary_pack_type" class="form-control requiredField select2">
                                                                    <option value="">Select Option</option>
                                                                    @foreach($pack_type as $row)
                                                                        <option @if($sub_item->primary_pack_type == $row->id) selected @endif value="{{ $row->id }}">{{ $row->type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Rate :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input step="0.01" value="{{ $sub_item->rate }}" class="form-control requiredField" type="number" name="rate" id="rate" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary UOM :</label>
                                                                <select name="uom2" id="uom2" class="form-control select2">
                                                                    <option value="">Select UOM</option>
                                                                    @foreach($uom as $key => $row)
                                                                        <option @if($sub_item->uom2 == $row->id) selected @endif value="{{ $row->id}}">{{ $row->uom_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary Pack size :</label>
                                                                <input step="0.01" value="{{ $sub_item->secondary_pack_size }}" min="0" class="form-control" type="number" name="secondary_pack_size" id="secondary_pack_size">
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary Packaging Type</label>
                                                                <select style="width: 100%" name="secondary_pack_type" id="secondary_pack_type" class="form-control select2">
                                                                <option value="">Select Option</option> 
                                                                    @foreach($pack_type as $row)
                                                                        <option @if($sub_item->secondary_pack_type == $row->id) selected @endif value="{{ $row->id }}">{{ $row->type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>H.S Code :</label>
                                                                <input type="text" name="hs_code_id" id="hs_code_id" class="form-control" value="{{ $sub_item->hs_code_id }}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Color :</label>
                                                                <input class="form-control" type="text" name="color" id="color" value="{{ $sub_item->color }}" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Type</label>
                                                                <select style="width: 100%" name="maintain" id="maintain" class="form-control requiredField">
                                                                    @foreach(CommonHelper::get_all_demand_type() as $row)
                                                                        <option @if($sub_item->stockType == $row->id) selected @endif value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Type</label><br>
                                                                <label>
                                                                    <input @if($sub_item->packing_type == 'primary') checked @endif type="checkbox" name="packing_type" value="primary"> Primary Packing
                                                                </label><br>
                                                                <label>
                                                                    <input @if($sub_item->packing_type == 'secondary') checked @endif type="checkbox" name="packing_type" value="secondary"> Secondary Packing
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Remarks :</label>
                                                                <textarea name="remark" id="remark" class="form-control" value="{{  $sub_item->remark }}">{{ $sub_item->remark }}</textarea>
                                                            </div>
                                                        </div>
                                                        <table class="table table-bordered" id="">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" style="">SR No</th>
                                                                <th style="" class="text-center" >Warehouse<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th style="" class="text-center" > Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th class="text-center">Closing Value<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th class="text-center">Batch Code<span class="rflabelsteric"><strong></strong></span></th>

                                                            </tr>
                                                            </thead>
                                                            <tbody id="append_bundle">
                                                            <?php $counter=1; ?>


                                                            @foreach(CommonHelper::get_all_warehouse() as $row)

                                                            @php
                                                            $opening =    ReuseableCode::opening_stock($sub_item->id,$row->id);
                                                            $qty = $opening->qty ?? 0;
                                                            $amount = $opening->amount ?? 0;
                                                            @endphp
                                                                <tr>
                                                                    <td>{{$counter++}}</td>
                                                                    <input type="hidden" name="warehouse[]" value="{{$row->id}}"/>
                                                                    <td class="text-center">{{$row->name}}</td>
                                                                    <td><input step="any" type="number" class="form-control requiredField" value="{{ $qty }}" name="closing_stock[]" id="closing_stock{{$counter}}" /> </td>
                                                                    <td><input step="any" type="number" value="<?php echo $amount ?>" class="form-control requiredField"  name="closing_val[]" id="closing_val{{$counter}}" /> </td>
                                                                    <td><input step="" type="text" value="<?php echo $opening->batch_code ?? '' ;?>" class="form-control requiredField"  name="batch_code[]" id="batch_code{{$counter}}" /> </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>

                                                            <tbody>
                                                            <tr  style="font-size:large;font-weight: bold">
                                                                <td class="text-center" colspan="2">Total</td>
                                                                <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_qty"/> </td>
                                                                <td id="" class="text-right" colspan="1"><input readonly class="form-control clear" type="text" id="total_rate"/> </td>
                                                            </tr>
                                                            </tbody>

                                                        </table>
                                                        <div>&nbsp;</div>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                                        </div>
                                                        {{ Form::close() }}
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-success").click(function(e){
            var subItem = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            subItem.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of subItem) {

                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
        });
        $('#CategoryId').trigger('change')
    });
</script>
<script type="text/javascript">

    $('.select2').select2();

    function get_sub_category_by_id(sub_category_id)
    {
        $('#SubCategoryId').empty();
        var category = $('#CategoryId').val();
        if(category)
        {
            $.ajax({
                    url: '/pdc/get_sub_category_by_id',
                    type: 'Get',
                    data:   {
                                category: category
                            },
                    success: function (response) {
                        $('#SubCategoryId').append(response);
                        $('#SubCategoryId').val(sub_category_id).select2();
                    }
            });
        }
    }     
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection

