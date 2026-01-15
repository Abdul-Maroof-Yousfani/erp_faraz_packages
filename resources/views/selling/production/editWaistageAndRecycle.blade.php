@extends('layouts.default')

@section('content')
    @include('select2')

    <?php
    use App\Helpers\CommonHelper;
    $ppc = CommonHelper::generateUniquePosNoWithStatusOne('production_plane', 'order_no', 'PPC');
    
    // echo "<pre>";
    // print_r(CommonHelper::get_sub_category()->get());
    // exit();
    
    ?>
    <style>
        tbody.disabled {
            opacity: 0.5;
            /* You can adjust the styling for disabled rows */
            /* Add any other styles as needed */
        }

        input[type="checkbox"] {
            width: 30px;
            height: 30px;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Waistage And Recycle</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <!-- <ul class="cus-ul2">
                                            <li>
                                                <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                                            </li>
                                            {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
                                        </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid bor-bo">
                                        <h2 class="subHeadingLabelClass">Edit Waistage and Recycle</h2>
                                    </div>
                                    <form action="{{ route('updateWaistageAndRecycle') }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{$recycle_wastage->id}}">
                                        <div class="col-md-12">
                                            <div class="row">
                                                {{-- <div class="col-md-3">
                                                    <label><input type="radio" class="form-control" onclick="changeForm(this)"
                                                            checked name="type" value="1">Waistage</label>
                                                    <label><input type="radio" class="form-control" onclick="changeForm(this)"
                                                            name="type" value="2">Recycle</label>
                                                </div> --}}
    
    
    
    
    
    
    
    
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 {{-- cus-tab --}}">
                                                    @if ($recycle_wastage->type == 1) 
                                                        <input type="hidden" name="type" value="1">
                                                        <div class="row qout-h wastage_detail">
                                                            <div class="col-md-12 padt ">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h2>Waistage Details</h2>
                                                                    </div>
                                                                    @foreach ($recycle_wastage_data as $row)
                                                                        @php
                                                                        $main_category_id = CommonHelper::get_main_category_by_itemid(
                                                                            $row->item_id,
                                                                        );
                                                                        $sub_category_id = CommonHelper::get_category_by_itemid(
                                                                            $row->item_id,
                                                                        );
                                                                    @endphp
                                                                    <div class="col-md-10 rowData">
                                                                        <div class="row ">
                                                                            <div class="addWaistageRow">
                                                                                <div class="col-md-3">
                                                                                    <label for="">Category</label>
                                                                                    <select
                                                                                        onchange="get_sub_category_by_mainCategory(this, 'item')"
                                                                                        name="Category_main" id="Category"
                                                                                        class="form-control">
                                                                                        <option value="">Select Category</option>
                                                                                        @foreach (CommonHelper::get_category()->get() as $value)
                                                                                            <option value="{{ $value->id }}"  {{ $main_category_id == $value->id ? 'selected' : '' }}>
                                                                                                {{ $value->main_ic }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Sub Category</label>
                                                                                    <select
                                                                                        onchange="get_sub_item_by_id_main(this , 'item')"
                                                                                        name="sub_Category" id="sub_Category"
                                                                                        class="form-control sub_Category">
                                                                                        <option value="">Select Sub Category
                                                                                        </option>
                                                                                        @foreach (CommonHelper::get_sub_category_by_main_category_id($main_category_id)->get() as $value)
                                                                                            <option
                                                                                                value="{{ $value->id }}"
                                                                                                {{ $sub_category_id == $value->id ? 'selected' : '' }}>
                                                                                                {{ $value->sub_category_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Item </label>
                                                                                    <select onchange="work_change(this)"
                                                                                        name="item_id[]" id="item"
                                                                                        class="form-control item requiredField">
                                                                                        <option value="">Select Item</option>
                                                                                        @foreach (CommonHelper::get_item_by_sub_category($sub_category_id) as $value)
                                                                                            <option
                                                                                                value="{{ $value->id }}"
                                                                                                {{ $value->id == $row->item_id ? 'selected' : '' }}>
                                                                                                {{ $value->sub_ic }}</option>
                                                                                        @endforeach
            
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Qty.</label>
                                                                                    <input type="text" name="qty[]"
                                                                                        onkeyup="cal_qty()" value="{{$row->qty}}"
                                                                                        class="form-control qty requiredField">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">PPC.</label>
                                                                                    <input type="text" name="ppc[]" value="{{$row->ppc}}"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="wegis">
                                                                                        <button onclick="addWaistageRow()" type="button" class="btn btn-success mr-1" data-dismiss="modal"> Add more </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="col-md-12 padt">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        {{-- <label>Recycle Details</label> --}}
                                                                        <h2>Out Put</h2>
                                                                    </div>
                                                                    @php
                                                                        $main_category_id = CommonHelper::get_main_category_by_itemid(
                                                                            $recycle_wastage->item_id,
                                                                        );
                                                                        $sub_category_id = CommonHelper::get_category_by_itemid(
                                                                            $recycle_wastage->item_id,
                                                                        );
                                                                    @endphp
                                                                    <div class="col-md-10">
                                                                        <div class="rowData">
                                                                            <div class="row">

                                                                                <div class="col-md-3">
                                                                                    <label for="">Category</label>
                                                                                    <select
                                                                                        onchange="get_sub_category_by_mainCategory(this , 'item')"
                                                                                        name="Category_main" id="Category"
                                                                                        class="form-control">
                                                                                        <option value="">Select Category</option>
                                                                                        @foreach (CommonHelper::get_category()->get() as $value)
                                                                                            <option value="{{ $value->id }}" {{ $main_category_id == $value->id ? 'selected' : '' }}>
                                                                                                {{ $value->main_ic }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Sub Category</label>
                                                                                    <select
                                                                                        onchange="get_sub_item_by_id_main(this , 'item')"
                                                                                        name="sub_Category" id="sub_Category"
                                                                                        class="form-control sub_Category">
                                                                                        <option value="">Select Sub Category</option> 
                                                                                        @foreach (CommonHelper::get_sub_category_by_main_category_id($main_category_id)->get() as $value)
                                                                                            <option
                                                                                                value="{{ $value->id }}"
                                                                                                {{ $sub_category_id == $value->id ? 'selected' : '' }}>
                                                                                                {{ $value->sub_category_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Item </label>
                                                                                    <select onchange="work_change(this)"
                                                                                        name="output_item_id" id="item"
                                                                                        class="form-control item requiredField">
                                                                                        <option value="">Select Item</option> 
                                                                                        @foreach (CommonHelper::get_item_by_sub_category($sub_category_id) as $value)
                                                                                            <option
                                                                                                value="{{ $value->id }}"
                                                                                                {{ $value->id == $recycle_wastage->item_id ? 'selected' : '' }}>
                                                                                                {{ $value->sub_ic }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Qty.</label>
                                                                                    <input type="text" readonly name="output_qty"
                                                                                        id="output_qty" value="{{$recycle_wastage->qty}}"
                                                                                        class="form-control output_qty">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Ware House </label>
                                                                                    <select onchange="getStock1(this)"
                                                                                        class="form-control output_warehouse_id select2 requiredField"
                                                                                        name="output_warehouse_id" id="">
                                                                                        <option value="">Select Warehouse</option>
                                                                                        @foreach (CommonHelper::get_all_warehouse() as $warehouse)
                                                                                            <option value="{{ $warehouse->id }}" {{$recycle_wastage->warehouse_id == $warehouse->id ? 'selected' : ''}}>
                                                                                                {{ $warehouse->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Batch Code.</label>
                                                                                    <input type="text" name="output_batch_code"
                                                                                        value="{{$recycle_wastage->batch_code}}"
                                                                                        class="form-control output_batch_code">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Wastage Date.</label>
                                                                                    <input type="date" name="wastage_date"
                                                                                        value="{{$recycle_wastage->date}}"
                                                                                        class="form-control wastage_date requiredField">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-12 padtb text-right">
                                                            <div class="col-md-9"></div>
                                                            <div class="col-md-3 my-lab">
                                                                <button type="submit" class="btn btn-primary mr-1"
                                                                    data-dismiss="modal">Save</button>
                                                                <button type="button" class="btnn btn-secondary "
                                                                    data-dismiss="modal">Cancel</button>
                                                            </div>
                                                            </div> --}}
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="type" value="2">
                                                        <div class="row qout-h recycle_detail">
                                                            <div class="col-md-12 padt ">
                                                                <div class="col-md-2">
                                                                    <h2>Recycle Details</h2>
                                                                </div>
                                                                <div class="row addRecycleRow">
                                                                    <div class="col-md-12">
                                                                        <h2>Recycle Material </h2>
                                                                    </div>
                                                                    @php
                                                                        $counter = 1;
                                                                    @endphp
                                                                    @foreach ($recycle_wastage_data as $row)
                                                                        @if ($row->item_type == 1)
                                                                            @php
                                                                                $main_category_id = CommonHelper::get_main_category_by_itemid(
                                                                                    $row->item_id,
                                                                                );
                                                                                $sub_category_id = CommonHelper::get_category_by_itemid(
                                                                                    $row->item_id,
                                                                                );
                                                                            @endphp
                                                                            <div class="col-md-12 ">
                                                                                <div class="rowData">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Category</label>
                                                                                            <select
                                                                                                onchange="get_sub_category_by_mainCategory(this , 'recycle_item')"
                                                                                                name="Category_main" id="Category"
                                                                                                class="form-control">
                                                                                                <option value="">Select Category
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_category()->get() as $value)
                                                                                                    <option value="{{ $value->id }}"
                                                                                                        {{ $main_category_id == $value->id ? 'selected' : '' }}>
                                                                                                        {{ $value->main_ic }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Sub Category</label>
                                                                                            <select
                                                                                                onchange="get_sub_item_by_id_main(this , 'recycle_item')"
                                                                                                name="sub_Category" id="sub_Category"
                                                                                                class="form-control sub_Category">
                                                                                                <option value="">Select Sub
                                                                                                    Category</option>
                                                                                                @foreach (CommonHelper::get_sub_category_by_main_category_id($main_category_id)->get() as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->id }}"
                                                                                                        {{ $sub_category_id == $value->id ? 'selected' : '' }}>
                                                                                                        {{ $value->sub_category_name }}
                                                                                                    </option>
                                                                                                @endforeach
            
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Item </label>
                                                                                            <select onchange="getStock1(this)"
                                                                                                style="width: 100%;"
                                                                                                name="recycle_item_id[]"
                                                                                                id="recycle_item"
                                                                                                class="form-control recycle_item requiredField">
                                                                                                <option value="">Select Item
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_item_by_sub_category($sub_category_id) as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->id }}"
                                                                                                        {{ $value->id == $row->item_id ? 'selected' : '' }}>
                                                                                                        {{ $value->sub_ic }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                            <input type="hidden" name="item_type[]"
                                                                                                value="1">
            
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Ware House </label>
                                                                                            <select onchange="getStock1(this)"
                                                                                                class="form-control warehouse_id selec2 requiredField"
                                                                                                name="warehouse_id[]" id="">
                                                                                                <option value="">Select Warehouse
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_all_warehouse() as $warehouse)
                                                                                                    <option
                                                                                                        value="{{ $warehouse->id }}"
                                                                                                        {{ $warehouse->id == $row->warehouse_id ? 'selected' : '' }}>
                                                                                                        {{ $warehouse->name }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Batch Code </label>
                                                                                            <select onchange="getStock2(this)"
                                                                                                class="form-control batch_code select2"
                                                                                                name="batch_code[]" id="">
                                                                                                <option value="">Select</option>
                                                                                                @foreach (CommonHelper::batch_code_edit($row->warehouse_id, $row->item_id) as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->batch_code }}"
                                                                                                        {{ $value->batch_code == $row->batch_code ? 'selected' : '' }}>
                                                                                                        {{ $value->batch_code }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Stock Qty.</label>
                                                                                            <input type="number" readonly
                                                                                                name="stock_qty[]"
                                                                                                value="{{ CommonHelper::in_stock_edit($row->item_id, $row->warehouse_id, $row->batch_code) }}"
                                                                                                class="form-control stock_qty">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Qty.</label>
                                                                                            <input type="number"
                                                                                                onkeyup="cal_recycle_qty(this)"
                                                                                                name="recycle_qty[]"
                                                                                                value="{{ $row->qty }}"
                                                                                                class="form-control recycle_qty requiredField">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for=""></label>
                                                                                            @if ($counter == 1) 
                                                                                            <button onclick="addRecycleRow()"
                                                                                                type="button"
                                                                                                class="btn btn-success mr-1"
                                                                                                data-dismiss="modal">
                                                                                                Add more
                                                                                            </button>
                                                                                            @else
                                                                                            <button onclick="removeRecycleRow(this)" type="button"
                                                                                                class="btn btn-danger mr-1" data-dismiss="modal">
                                                                                                Remove Row
                                                                                            </button>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @php
                                                                                $counter++;
                                                                            @endphp
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="row addRecycleRow2">
                                                                    <div class="col-md-12">
                                                                        <h2>Raw Material</h2>
                                                                        <button onclick="addRecycleRow2()" type="button"class="btn btn-success mr-1" data-dismiss="modal">Add more</button>
                                                                    </div>
                                                                    @foreach ($recycle_wastage_data as $row)
                                                                        @if ($row->item_type == 2)
                                                                            @php
                                                                                $main_category_id = CommonHelper::get_main_category_by_itemid(
                                                                                    $row->item_id,
                                                                                );
                                                                                $sub_category_id = CommonHelper::get_category_by_itemid(
                                                                                    $row->item_id,
                                                                                );
                                                                            @endphp
                                                                            <div class="col-md-12 ">
                                                                                <div class="rowData">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Category</label>
                                                                                            <select
                                                                                                onchange="get_sub_category_by_mainCategory(this , 'recycle_item')"
                                                                                                name="Category_main" id="Category"
                                                                                                class="form-control">
                                                                                                <option value="">Select Category
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_category()->get() as $value)
                                                                                                    <option value="{{ $value->id }}"
                                                                                                        {{ $main_category_id == $value->id ? 'selected' : '' }}>
                                                                                                        {{ $value->main_ic }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Sub Category</label>
                                                                                            <select
                                                                                                onchange="get_sub_item_by_id_main(this , 'recycle_item')"
                                                                                                name="sub_Category" id="sub_Category"
                                                                                                class="form-control sub_Category">
                                                                                                <option value="">Select Sub
                                                                                                    Category</option>
                                                                                                @foreach (CommonHelper::get_sub_category_by_main_category_id($main_category_id)->get() as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->id }}"
                                                                                                        {{ $sub_category_id == $value->id ? 'selected' : '' }}>
                                                                                                        {{ $value->sub_category_name }}
                                                                                                    </option>
                                                                                                @endforeach
            
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Item </label>
                                                                                            <select onchange="getStock1(this)"
                                                                                                style="width: 100%;"
                                                                                                name="recycle_item_id[]"
                                                                                                id="recycle_item"
                                                                                                class="form-control recycle_item requiredField">
                                                                                                <option value="">Select Item
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_item_by_sub_category($sub_category_id) as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->id }}"
                                                                                                        {{ $value->id == $row->item_id ? 'selected' : '' }}>
                                                                                                        {{ $value->sub_ic }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                            <input type="hidden" name="item_type[]"
                                                                                                value="2">
            
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Ware House </label>
                                                                                            <select onchange="getStock1(this)"
                                                                                                class="form-control warehouse_id select2 requiredField"
                                                                                                name="warehouse_id[]" id="">
                                                                                                <option value="">Select Warehouse
                                                                                                </option>
                                                                                                @foreach (CommonHelper::get_all_warehouse() as $warehouse)
                                                                                                    <option
                                                                                                        value="{{ $warehouse->id }}"
                                                                                                        {{ $warehouse->id == $row->warehouse_id ? 'selected' : '' }}>
                                                                                                        {{ $warehouse->name }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Batch Code </label>
                                                                                            <select onchange="getStock2(this)"
                                                                                                class="form-control batch_code select2 "
                                                                                                name="batch_code[]" id="">
                                                                                                <option value="">Select</option>
                                                                                                @foreach (CommonHelper::batch_code_edit($row->warehouse_id, $row->item_id) as $value)
                                                                                                    <option
                                                                                                        value="{{ $value->batch_code }}"
                                                                                                        {{ $value->batch_code == $row->batch_code ? 'selected' : '' }}>
                                                                                                        {{ $value->batch_code }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Stock Qty.</label>
                                                                                            <input type="number" readonly
                                                                                                name="stock_qty[]"
                                                                                                value="{{ CommonHelper::in_stock_edit($row->item_id, $row->warehouse_id, $row->batch_code) }}"
                                                                                                class="form-control stock_qty ">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for="">Qty.</label>
                                                                                            <input type="number"
                                                                                                onkeyup="cal_recycle_qty(this)"
                                                                                                name="recycle_qty[]"
                                                                                                value="{{ $row->qty }}"
                                                                                                class="form-control recycle_qty requiredField">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label for=""></label>
                                                                                            <button onclick="removeRecycleRow2(this)" type="button"
                                                                                            class="btn btn-danger mr-1" data-dismiss="modal">
                                                                                            Remove Row
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 padt">
                                                                <div class="col-md-1">
                                                                    {{-- <label>Recycle Details</label> --}}
                                                                    <h2>Out Put</h2>
                                                                </div>
                                                                <div class="col-md-11 rowData">
                                                                    @php
                                                                        $main_category_id = CommonHelper::get_main_category_by_itemid(
                                                                            $recycle_wastage->item_id,
                                                                        );
                                                                        $sub_category_id = CommonHelper::get_category_by_itemid(
                                                                            $recycle_wastage->item_id,
                                                                        );
                                                                        // dd( $sub_category_id , $recycle_wastage->item_id );
                                                                    @endphp
                                                                    <div class="col-md-2">
                                                                        <label for="">Category</label>
                                                                        <select
                                                                            onchange="get_sub_category_by_mainCategory(this , 'recycle_item')"
                                                                            name="Category_main" id="Category"
                                                                            class="form-control">
                                                                            <option value="">Select Category</option>
                                                                            @foreach (CommonHelper::get_category()->get() as $value)
                                                                                <option value="{{ $value->id }}" {{ $main_category_id == $value->id ? 'selected' : '' }}>
                                                                                    {{ $value->main_ic }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="">Sub Category</label>
                                                                        <select
                                                                            onchange="get_sub_item_by_id_main(this , 'recycle_item')"
                                                                            name="sub_Category" id="sub_Category"
                                                                            class="form-control sub_Category">
                                                                            <option value="">Select Sub Category</option>
                                                                            @foreach (CommonHelper::get_sub_category_by_main_category_id($main_category_id)->get() as $value)
                                                                                <option
                                                                                    value="{{ $value->id }}"
                                                                                    {{ $sub_category_id == $value->id ? 'selected' : '' }}>
                                                                                    {{ $value->sub_category_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
    
                                                                    <div class="col-md-2">
                                                                        <label for="">Item </label>
                                                                        <select onchange="work_change(this)"
                                                                            name="recycle_output_item_id" id="recycle_item"
                                                                            class="form-control recycle_item select2  "
                                                                            style="width: 100%;">
                                                                            <option value="">Select Item</option>
                                                                            @foreach (CommonHelper::get_item_by_sub_category($sub_category_id) as $value)
                                                                                <option
                                                                                    value="{{ $value->id }}"
                                                                                    {{ $value->id == $recycle_wastage->item_id ? 'selected' : '' }}>
                                                                                    {{ $value->sub_ic }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="">Qty.</label>
                                                                        <input type="text" readonly
                                                                            name="recycle_output_qty" value="{{$recycle_wastage->qty}}"
                                                                            class="form-control  recycle_output_qty">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="">Ware House </label>
                                                                        <select onchange="getStock1(this)"
                                                                            class="form-control recycle_output_warehouse_id select2"
                                                                            name="recycle_output_warehouse_id" id="">
                                                                            <option value="">Select Warehouse</option>
                                                                            @foreach (CommonHelper::get_all_warehouse() as $warehouse)
                                                                                <option value="{{ $warehouse->id }}" {{$recycle_wastage->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                                                                    {{ $warehouse->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="">Batch Code.</label>
                                                                        <input type="text" name="recycle_output_batch_code"
                                                                            value="{{$recycle_wastage->batch_code}}"
                                                                            class="form-control recycle_output_batch_code">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="">Recycle Date.</label>
                                                                        <input type="date" name="recycle_date"
                                                                            value="{{$recycle_wastage->date}}" class="form-control recycle_date">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-12 padtb text-right">
                                                    <div class="col-md-9"></div>
                                                    <div class="col-md-3 my-lab">
                                                        <button type="submit" class="btn btn-primary mr-1"
                                                            data-dismiss="modal">Save</button>
                                                        <button type="button" class="btnn btn-secondary "
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getBomReceipe(datas) {
            $(datas).closest('.receipe_main').find('.recipe_qty').empty();
            var receipe_id = $(datas).closest('.receipe_main').find('.receip_id').val();
            let category_id = datas.value;
            $.ajax({
                url: '<?php echo url('/'); ?>/selling/getOverAllstock',
                type: 'Get',
                data: {
                    category_id: category_id,
                    receipe_id: receipe_id
                },
                success: function(data) {

                    $(datas).closest('.receipe_main').find('.recipe_qty').val(data);
                    var Order_qty = $(datas).closest('.receipe_main').find('.order_qty').val();
                    var total = data * Order_qty;
                    $(datas).closest('.receipe_main').find('.required_qty').val(total);
                }
            });

        }

        function work_change(datas) {
            $('#more_details').empty();

            let id = datas.value;
            if (id) {

                $.ajax({
                    url: '<?php echo url('/'); ?>/selling/getRecipeOfItem',
                    type: 'Get',
                    data: {
                        id: id
                    },
                    success: function(data) {

                        $('#more_details').empty();
                        $('#more_details').append(data);

                        setTimeout(() => {
                            $('.receip_id').trigger('change');
                        }, 2000);
                    }
                });

            } else {
                $('#more_details').empty();
                $('#so_number').val('');
                $('#customer').val('');
            }
        }

        function toggleRow(checkbox) {
            // Get the closest <tr> element
            var row = checkbox.closest('.row_of_data');
            var inputs = row.querySelectorAll('input:not([type="checkbox"])');
            // Check the checkbox state
            if (checkbox.checked) {
                row.classList.add('disabled');
                $(checkbox).closest('.receipe_main').find('.category').prop('disabled', true);
                $(checkbox).closest('.receipe_main').find('.receip_id').prop('disabled', true);


                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = true;
                }

            } else {
                $(checkbox).closest('.receipe_main').find('.category').prop('disabled', false);
                $(checkbox).closest('.receipe_main').find('.receip_id').prop('disabled', false);
                // If checkbox is unchecked, disable the row
                row.classList.remove('disabled');
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = false;
                }
            }
        }

        function removes(count) {
            console.log(count);
            $('#remove' + count).remove();
            counter--;
        }

        var counter = 1;
        var option = '';

        function addWaistageRow() {
            var html = '';

            $('.addWaistageRow').append(`
            <div class="col-md-12">
                <div class="rowData">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Category</label>
                            <select onchange="get_sub_category_by_mainCategory(this, 'item')"
                                name="Category_main" id="Category"
                                class="form-control">
                                <option value="">Select Category</option>
                                @foreach (CommonHelper::get_category()->get() as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->main_ic }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Sub Category</label>
                            <select
                                onchange="get_sub_item_by_id_main(this , 'item')"
                                name="sub_Category" id="sub_Category"
                                class="form-control sub_Category">
                                <option value="">Select Sub Category</option> 
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Item </label>
                            <select onchange="work_change(this)" name="item_id[]"
                                id="item" class="form-control item requiredField">
                                <option value="">Select Item</option>
                                
        
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Qty.</label>
                            <input type="text" name="qty[]" value="" onkeyup="cal_qty()"
                                class="form-control qty requiredField">
                        </div>
                        <div class="col-md-3">
                            <label for="">PPC.</label>
                            <input type="text" name="ppc[]" value=""
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <div class="wegis">
                                <button onclick="removeRow(this)" type="button"class="btn btn-danger mr-1" data-dismiss="modal"> Remove Row </button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
      `);
            $('#item' + counter).select2();
            counter++;
            // $('.item').select2();
        }


        function removeRow(count) {
            console.log(count);
            $(count).closest('.rowData').remove();
            // $('#remove' + count).remove();
            counter--;
        }

        var counter2 = 1;

        function addRecycleRow() {
            var html = '';

            $('.addRecycleRow').append(`
          <div class="col-md-12 rowData"> 
            <div class="col-md-2">
                <label for="">Category</label>
                <select onchange="get_sub_category_by_mainCategory(this , 'recycle_item')"
                    name="Category_main" id="Category"
                    class="form-control">
                    <option value="">Select Category</option>
                    @foreach (CommonHelper::get_category()->get() as $value)
                        <option value="{{ $value->id }}">
                            {{ $value->main_ic }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="">Sub Category</label>
                <select
                    onchange="get_sub_item_by_id_main(this , 'recycle_item')"
                    name="sub_Category" id="sub_Category"
                    class="form-control sub_Category">
                    <option value="">Select Sub Category</option> 
                </select>
            </div>
            <div class="col-md-2">
              <label for="">Item </label>
              <select onchange="getStock1(this)" name="recycle_item_id[]"
                  id="recycle_item" class="form-control recycle_item requiredField">
                  <option value="">Select Item</option>
                  
              </select>
              <input type="hidden" name="item_type[]" value="1"> 
          </div>
          <div class="col-md-2">
            <label for="">Ware House </label>
            <select  onchange="getStock1(this)" class="form-control warehouse_id select2 requiredField" name="warehouse_id[]" id="">
              <option value="">Select Warehouse</option>
              @foreach (CommonHelper::get_all_warehouse() as $warehouse)
              <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-1">
                <label for="">Batch Code </label>
                <select  onchange="getStock2(this)" class="form-control batch_code select2" name="batch_code[]" id="">
                     
                </select>
            </div>
          <div class="col-md-1">
              <label for="">Stock Qty.</label>
              <input type="number"  readonly
                  name="stock_qty[]" value=""
                  class="form-control stock_qty requiredField">
          </div>
          <div class="col-md-1">
              <label for="">Qty.</label>
              <input type="number"  onkeyup="cal_recycle_qty(this)"
                  name="recycle_qty[]" value=""
                  class="form-control recycle_qty requiredField">
          </div> 
            <div class="col-md-1">
                <label for=""></label>
                <button onclick="removeRecycleRow(this)" type="button"
                    class="btn btn-danger mr-1" data-dismiss="modal">
                    Remove Row
                </button>
            </div>
          </div>
          `);
            // $('#recycle_item' + counter2).select2();
            counter2++;
            $('.recycle_item').select2();
        }

        function removeRecycleRow(count) {
            console.log(count);
            $(count).closest('.rowData').remove();
            // $('#remove' + count).remove();
            counter2--;
        }

        function addRecycleRow2() {
            var html = '';

            $('.addRecycleRow2').append(`
          <div class="col-md-12 rowData"> 
            <div class="col-md-2">
                <label for="">Category</label>
                <select onchange="get_sub_category_by_mainCategory(this , 'recycle_item')"
                    name="Category_main" id="Category"
                    class="form-control">
                    <option value="">Select Category</option>
                    @foreach (CommonHelper::get_category()->get() as $value)
                        <option value="{{ $value->id }}">
                            {{ $value->main_ic }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="">Sub Category</label>
                <select
                    onchange="get_sub_item_by_id_main(this , 'recycle_item')"
                    name="sub_Category" id="sub_Category"
                    class="form-control sub_Category">
                    <option value="">Select Sub Category</option> 
                </select>
            </div>
            <div class="col-md-2">
              <label for="">Item </label>
              <select onchange="getStock1(this)" name="recycle_item_id[]"
                  id="recycle_item" class="form-control recycle_item requiredField">
                  <option value="">Select Item</option>
                  
              </select>
              <input type="hidden" name="item_type[]" value="2"> 
          </div>
          <div class="col-md-2">
            <label for="">Ware House </label>
            <select  onchange="getStock1(this)" class="form-control warehouse_id select2 requiredField" name="warehouse_id[]" id="">
              <option value="">Select Warehouse</option>
              @foreach (CommonHelper::get_all_warehouse() as $warehouse)
              <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-1">
                <label for="">Batch Code </label>
                <select  onchange="getStock2(this)" class="form-control batch_code select2" name="batch_code[]" id="">
                     
                </select>
            </div>
          <div class="col-md-1">
              <label for="">Stock Qty.</label>
              <input type="number"  readonly
                  name="stock_qty[]" value=""
                  class="form-control stock_qty requiredField">
          </div>
          <div class="col-md-1">
              <label for="">Qty.</label>
              <input type="number"   onkeyup="cal_recycle_qty(this)"
                  name="recycle_qty[]" value=""
                  class="form-control recycle_qty requiredField">
          </div> 
            <div class="col-md-1">
                <label for=""></label>
                <button onclick="removeRecycleRow2(this)" type="button"
                    class="btn btn-danger mr-1" data-dismiss="modal">
                    Remove Row
                </button>
            </div>
          </div>
          `);
            // $('#recycle_item' + counter2).select2();
            counter2++;
            $('.recycle_item').select2();
        }

        function removeRecycleRow2(count) {
            console.log(count);
            $(count).closest('.rowData').remove();
            // $('#remove' + count).remove();
            counter2--;
        }

        function getStock1(datas) {
            let ids = $(datas).closest('.rowData').find('.recycle_item').val();
            var warehouse_id = $(datas).closest('.rowData').find('.warehouse_id').val();
            $.ajax({
                url: '<?php echo url('/'); ?>/selling/getStockForProduction',
                type: 'Get',
                data: {
                    id: ids,
                    warehouse_id: warehouse_id
                },
                success: function(data) {
                    console.log(data);
                    $(datas).closest('.rowData').find('.stock_qty').val(data);
                    $(datas).closest('.rowData').find('.recycle_qty').val('');
                    get_batch_code(datas);
                    total_recycle_qty();
                }
            });
        }

        function getStock2(datas) {
            let ids = $(datas).closest('.rowData').find('.recycle_item').val();
            var warehouse_id = $(datas).closest('.rowData').find('.warehouse_id').val();
            var batch_code = $(datas).closest('.rowData').find('.batch_code').val();
            $.ajax({
                url: '<?php echo url('/'); ?>/selling/getStockForProduction',
                type: 'Get',
                data: {
                    id: ids,
                    warehouse_id: warehouse_id,
                    batch_code: batch_code
                },
                success: function(data) {
                    console.log(data);
                    $(datas).closest('.rowData').find('.stock_qty').val(data);
                }
            });
        }

        function get_batch_code(datas) {
            let ids = $(datas).closest('.rowData').find('.recycle_item').val();
            var warehouse_id = $(datas).closest('.rowData').find('.warehouse_id').val();
            var batch_code = '';
            $.ajax({
                url: '<?php echo url('/'); ?>/pdc/get_stock_location_wise?batch_code=' + batch_code,
                type: 'Get',
                data: {
                    item: ids,
                    warehouse: warehouse_id,
                },
                success: function(data) {
                    console.log(data);
                    console.log(data);
                    //   $('#batch_code'+number).html(data);
                    //     $('#instock'+number).html('');
                    if (data == null || data == '') {
                        $(datas).closest('.rowData').find('.batch_code').html('<option value="">Select</option>');
                    }
                    else{
                        $(datas).closest('.rowData').find('.batch_code').html(data);
                    }
                    // $(datas).closest('.rowData').find('.stock_qty').val(0);
                }
            });
        }




        function getReceipeDataOfSingleItem(instnace) {

            let uomArray = ['Metre', 'Mtrs'];
            let uom = $('#uom').val()
            let total = 0;
            let id = instnace.value;
            $(instnace).closest('.recipe_details').find('.receipe1').empty();
            $.ajax({
                url: '<?php echo url('/'); ?>/selling/getReceipeDataOfSingleItem',
                type: 'Get',
                data: {
                    id: id
                },
                success: function(responsedata) {
                    $(instnace).closest('.recipe_details').find('.receipe1').append(responsedata);
                    //    var order_qty = $('.order_qty').val();
                    var order_qty = $(instnace).closest('.receipe_main').find('.order_qty').val();
                    $('.row_recipe').each(function(key, value) {
                        var required_qty = $(this).closest('.row_recipe').find('.reqired_qty').val();

                        if (uomArray.includes(uom)) {
                            total = (Number(parseFloat(order_qty) / 1000)) * parseFloat(required_qty);
                        } else {
                            total = Number(parseFloat(order_qty)) * parseFloat(required_qty);
                        }




                        $(this).closest('.row_recipe').find('.requested_qty').val(total);
                    });

                }
            });

        }


        function get_sub_item_by_id_main(instance, item_class) {
            // $('#item').empty().select2();

            // $('#more_details').empty();

            // console.log(item_class);
            var category = instance.value;

            // $('#item').empty();
            $(instance).closest('.rowData').find('.' + item_class).empty();
            $.ajax({
                // url: '{{ url('/getSubItemByMainCategory') }}',
                url: '{{ url('/getSubItemByCategory') }}',
                type: 'Get',
                data: {
                    category: category
                },
                success: function(response) {
                    // console.log(response , item_class);
                    // $('#item').empty().append(response);
                    $(instance).closest('.rowData').find('.' + item_class).empty().append(response);
                    $(instance).closest('.rowData').find('.' + item_class).select2();
                    // Reinitialize Select2
                    // $('#item').select2();
                }
            });
        }

        function get_sub_category_by_mainCategory(instance, item_class) {
            var category = instance.value;

            // $('#item').empty();
            $(instance).closest('.rowData').find('.sub_Category').empty();
            $(instance).closest('.rowData').find('.' + item_class).empty();
            $.ajax({
                url: '{{ url('/pdc/get_sub_category_by_id') }}',
                type: 'Get',
                data: {
                    category: category
                },
                success: function(response) {
                    $(instance).closest('.rowData').find('.sub_Category').empty().append(response);
                    $(instance).closest('.rowData').find('.sub_Category').select2();
                }
            });
        }

        $(document).ready(() => {
            $('#Category').select2();
            $('#item').select2();
            $('.item').select2();
            $('#recycle_item').select2();
            $('.recycle_item').select2();
            // $('.output_item_id').select2();

            // $('.warehouse_id').each(function() {
            //     // val = parseFloat($(this).val());
            //     getStock1(this);
            // });

        })

        function cal_qty() {
            qty = 0;
            $('.qty').each(function() {
                val = parseFloat($(this).val());
                if (isNaN(val)) {
                    val = 0;
                }
                qty += val;
            });
            $('#output_qty').val(qty);
        }

        function changeForm(count) {
            if ($(count).val() == 1) {
                $('.wastage_detail').show();
                $('.recycle_detail').hide();
                ////// wastage
                $('.item').addClass('requiredField');
                $('.qty').addClass('requiredField');
                $('.output_qty').addClass('requiredField');
                $('.wastage_date').addClass('requiredField');
                $('.output_warehouse_id').addClass('requiredField');

                ////// recycle
                $('.recycle_item').removeClass('requiredField');
                $('.warehouse_id').removeClass('requiredField');
                $('.recycle_qty').removeClass('requiredField');
                $('.recycle_output_qty').removeClass('requiredField');
                $('.recycle_date').removeClass('requiredField');
                $('.recycle_output_warehouse_id').removeClass('requiredField');



            } else {
                $('.wastage_detail').hide();
                $('.recycle_detail').show();

                ////// wastage
                $('.item').removeClass('requiredField');
                $('.qty').removeClass('requiredField');
                $('.output_qty').removeClass('requiredField');
                $('.wastage_date').removeClass('requiredField');
                $('.output_warehouse_id').removeClass('requiredField');

                ////// recycle
                $('.recycle_item').addClass('requiredField');
                $('.warehouse_id').addClass('requiredField');
                $('.recycle_qty').addClass('requiredField');
                $('.recycle_output_qty').addClass('requiredField');
                $('.recycle_date').addClass('requiredField');
                $('.recycle_output_warehouse_id').addClass('requiredField');
            }
        }


        function cal_recycle_qty(instance) {
            var stock_qty = parseFloat($(instance).closest('.rowData').find('.stock_qty').val());
            var qty = parseFloat($(instance).val());
            console.log(stock_qty);
            if (isNaN(stock_qty)) {
                alert("select item to get stock");
                $(instance).val('');
                return;
            }
            if (qty > stock_qty || qty == 0) {
                alert("Quantity couldn't be 0 or greater than stock quantity");
                $(instance).val('');
            }
            total_recycle_qty();
            // recycle_qty = 0;
            // $('.recycle_qty').each(function() {
            //   val = parseFloat($(this).val()); 
            //   if (isNaN(val)) {
            //     val = 0;
            //   }
            //   recycle_qty += val;
            // });
            // $('.recycle_output_qty').val(recycle_qty); 

        }

        function total_recycle_qty() {
            recycle_qty = 0;
            $('.recycle_qty').each(function() {
                val = parseFloat($(this).val());
                if (isNaN(val)) {
                    val = 0;
                }
                recycle_qty += val;
            });
            $('.recycle_output_qty').val(recycle_qty);
        }

        $('form').submit(function(e) {
            // alert();
            e.preventDefault();
            // type = $('input[name=type]:checked').val();
            // console.log(type);
            // if (type = 1) {

            // }
            required = false;

            $('.requiredField').each(function() {
                console.log($(this).val());
                val = $(this).val()
                if (val == '') {
                    e.preventDefault();
                    alert(this.name + ' field is required');
                    required = true;
                    return false;
                }
            });
            $('.batch_code').each(function() {
                length = $(this).children('option').length;
                if (length > 1) {
                    val = $(this).val();
                    if (val == '') {
                        e.preventDefault();
                        alert(this.name + ' field is required');
                        required = true;
                        return false;
                    }
                }
            });

            if (required == true) {
                return false;
            } else {
                $('form').unbind('submit').submit();
            }
        });
    </script>
@endsection
