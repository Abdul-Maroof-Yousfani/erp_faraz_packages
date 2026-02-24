<?php
$m = Session::get('run_company');
        use App\Helpers\CommonHelper;
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
                                    <span class="subHeadingLabelClass">Add New Sub Item</span>
                                </div>
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" style="float: right;"> Import csv </button>
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
                                                        <?php echo Form::open(array('url' => 'pad/addSubItemDetail?m='.$m.'','id'=>'addSubItemDetail'));?>
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Category :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select autofocus  name="CategoryId" id="CategoryId" onchange="get_sub_category_by_id()" class="form-control requiredField select2 " required>
                                                                    <option value="">Select Category</option>
                                                                    @foreach(CommonHelper::get_category()->get() as $key => $y)
                                                                        <option value="{{ $y->id}}">{{ $y->main_ic}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Sub Category :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select autofocus  name="SubCategoryId" id="SubCategoryId" class="form-control requiredField select2 " required>
                                                                    <option value="">Select Category</option>
                                                
                                                                </select>
                                                            </div>
                                                            {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                                <label>Brand :</label>
                                                                <span class="rflabelsteric"></span>
                                                                <select autofocus  name="brand" id="brand" class="form-control  select2">
                                                                    <option value="">Select Brand</option>
                                                                    @foreach($brand as $key => $row)
                                                                        <option value="{{ $row->id}}">{{ $row->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div> --}}
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Item Name :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input type="text" name="sub_item_name" id="sub_item_name" value="" class="form-control requiredField " required />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Item Code :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <span class="rflabelsteric"></span>
                                                                <input  type="text" name="item_code" id="item_code" value="" class="form-control requiredField " required/>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                                <label>SKU Code :</label>
                                                                <input type="text" name="sku" id="sku" value="" class="form-control" />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary UOM :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <select name="uom_id" id="uom_id" class="form-control requiredField select2 " required>
                                                                    <option value="">Select UOM</option>
                                                                    @foreach($uom as $key => $i)
                                                                        <option value="{{ $i->id}}">{{ $i->uom_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary Bags size :</label>
                                                                
                                                                {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                <input step="0.01" value="0" min="0" class="form-control requiredField" type="number" name="pack_size" id="pack_size" required>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Primary Packaging Type</label>
                                                                {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                <select style="width: 100%" name="primary_pack_type" id="primary_pack_type" class="form-control requiredField select2">
                                                                    <option value="">Select Option</option>
                                                                    @foreach($pack_type as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Rate :</label>
                                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                                <input step="0.01" value="0" class="form-control text-right requiredField" type="number" name="rate" id="rate" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary UOM :</label>
                                                                <select name="uom2" id="uom2" class="form-control select2">
                                                                    <option value="">Select UOM</option>
                                                                    @foreach($uom as $key => $i)
                                                                        <option value="{{ $i->id}}">{{ $i->uom_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary Pack size :</label>
                                                                <input step="0.01" value="0" min="0" class="form-control" type="number" name="secondary_pack_size" id="secondary_pack_size">
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Secondary Packaging Type</label>
                                                                {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                <select style="width: 100%" name="secondary_pack_type" id="secondary_pack_type" class="form-control select2">
                                                                <option value="">Select Option</option> 
                                                                    @foreach($pack_type as $row)
                                                                        <option value="{{ $row->id }}">{{ $row->type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>H.S Code :</label>
                                                                <input type="text" name="hs_code_id" id="hs_code_id" class="form-control"/>
                                                            </div>
                                                           
                                                        </div>
                                                        <div class="row">

                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Color :</label>
                                                                <input class="form-control" type="text" name="color" id="color">
                                                            </div>

                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Type</label>
                                                                {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                <select style="width: 100%" name="maintain" id="maintain" class="form-control requiredField">
                                                                    @foreach(CommonHelper::get_all_demand_type() as $row)
                                                                        <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Type</label><br>
                                                                <label>
                                                                    <input type="checkbox" name="packing_type" value="primary"> Primary Packing
                                                                </label><br>
                                                                <label>
                                                                    <input type="checkbox" name="packing_type" value="secondary"> Secondary Packing
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label>Remarks :</label>
                                                                <textarea name="remark" id="remark" class="form-control"></textarea>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered" id="">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" style="">SR No</th>
                                                                <th style="" class="text-center" >Warehouse<span class="rflabelsteric"></span></th>
                                                                <th style="" class="text-center" > Closing Stock<span class="rflabelsteric"></span></th>
                                                                <th class="text-center">Closing Value<span class="rflabelsteric"></span></th>
                                                                <th class="text-center">Batch Code<span class="rflabelsteric"></span></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="append_bundle">
                                                            <?php $counter=1; ?>


                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                <tr>
                                                                    <td>{{$counter++}}</td>
                                                                    <input type="hidden" name="warehouse[]" value="{{$row->id}}"/>
                                                                    <td class="text-center">{{$row->name}}</td>
                                                                    <td><input step="any" type="number" class="form-control requiredField" value="0" name="closing_stock[]" id="closing_stock{{$counter}}" /> </td>
                                                                    <td><input step="any" type="number" class="form-control requiredField" value="0" name="closing_val[]" id="closing_val{{$counter}}" /> </td>
                                                                    <td><input type="text" class="form-control requiredField" value="0" name="batch_code[]" id="batch_code{{$counter}}" /> </td>

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

                                                        <div class="lineHeight">&nbsp;</div>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                            {{ Form::submit('Submit', ['class' => 'btn btn-success submitButton']) }}
                                                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                                        </div>
                                                        <?php
                                                        echo Form::close();
                                                        ?>
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


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="importProducts___BV_modal_body_" class="modal-body">
                    <form action="{{ url('pad/uploadSubItems') }}" id="uploadSubItems" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-12">
                                <fieldset class="form-group" id="__BVID__194">
                                    <div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="company_id" id="company_id"
                                                value="{{ $m }}" />
                                        <input type="file" name='file' label="Choose File" required>
                                        <div id="File-feedback" class="d-block invalid-feedback">Field must be in csvformat</div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm btn-block submitCsv">Submit</button>
                            </div>
                            <div class="col-sm-6 col-md-6"><a class="btn btn-sm btn-primary" href="{{ url('/') }}/app-assets/images/sample files/items sample file.csv">Download Sample / Format </a></div>
                    
                        </div>
                    </form>

                    <div class="col-sm-12 col-md-12">
                        <table class="table table-bordered table-sm mt-4">
                            <tbody>
                            <tr>
                                <td>Category</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Sub Category</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Item code</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Item Name</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Primary UOM</td>
                                <th><span class="badge badge-outline-success"></span></th>
                            </tr>
                            <tr>
                                <td>Primary pack size</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Primary pack type</td>
                                <th><span class="badge badge-outline-success">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Secondary UOM</td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>SSecondary pack size</td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Secondary pack type </td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Rate<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Color<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>HS Code<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>SKU Code<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Remarks<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Qty<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Amount<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Batch Code<td>
                                <th><span class="badge badge-outline-success">Field is optional</span></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        (document).ready(function() {
            $('.submitButton').click(function() {
                $('#addSubItemDetail').submit();
            });

            $('.submitCsv').click(function() {
                $('#uploadSubItems').submit();
            });
        });
        function get_item_master()
        {
            var category= $('#CategoryId').val();
            var sub_category = $('#sub_category').val();
            if(category > 0 && sub_category >0)
            {
                $.ajax({
                    url: '/pdc/get_item_master',
                    type: 'Get',
                    data: {category: category,sub_category:sub_category},
                    success: function (response) {
                        $('#item_master').html(response);
                    }
                });
            }

        }
        
        function get_sub_category_by_id()
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
                        }
                });
            }

        }

        function get_sub_item_code()
        {
            var category= $('#CategoryId').val();
            var sub_category = $('#sub_category').val();
            var item_master_id = $('#item_master').val();
            if(category > 0 && sub_category >0 && item_master_id > 0)
            {
                $.ajax({
                    url: '/pdc/get_sub_item_code',
                    type: 'Get',
                    data: {category: category,sub_category:sub_category,item_master_id:item_master_id},
                    success: function (response)
                    {
                        $('#item_code').val(response);
                    }
                });
            }
            else
            {
                $('#item_code').val('');
            }

        }

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
                        $('.btn-success').prop('disabled',true);
                        $("form").submit();
                        //return false;
                    }else{
                        return false;
                    }
                }
            });
        });

    
    </script>
    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection
