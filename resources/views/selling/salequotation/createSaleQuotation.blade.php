@extends('layouts.default')
@section('content')
    @include('select2')
    <?php
    use App\Helpers\CommonHelper;
    $quotation_no = CommonHelper::generateUniquePosNo('sale_quotations', 'quotation_no', 'QUO');
                            ?>
    <style>
        /* input#item_description{width:180px !important;}
                            .select2-container--default .select2-selection--single .select2-selection__rendered{font-size:16px;}
                            .my-lab label{padding-top:0px;}
                            */
        td.diswrp span {
            width: 233px !important;
        }

        td.diswrap span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 200px !important;
        }

        td.diswrp span b {
            right: 10px;
        }

        .m-tab tr td span b {
            right: 10px !important;
            left: inherit !important;
        }

        .m-tab tr td label {
            display: block;
        }

        .m-tab tr td span {
            font-size: 8px;
            width: 200px !important;
        }

        element.style {}

        @media (min-width:992px) {
            .col-md-2 {}
        }

        .cke_notifications_area {
            display: none;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <form action="{{route('saleQuotaionStore')}}" method="post" id="dataForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="formSection[]" class="form-control requiredField" id="demandsSection"
                            value="1" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="headquid bor-bo">
                                            <h2 class="subHeadingLabelClass">Create Sale Quotation</h2>
                                        </div>
                                        <div class="">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                <div class="row qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <!-- Quotation Details -->
                                                        <div class="bor-bo">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <h2>Quotation Details</h2>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label for="">Quotation Number</label>
                                                                            <input type="text" name="quotation_no"
                                                                                value="{{$quotation_no}}" readonly
                                                                                class="form-control">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Quotation Date</label>
                                                                            <input type="date" name="quotation_date"
                                                                                value="{{date('Y-m-d')}}"
                                                                                class="form-control">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Quotation Valid Up To</label>
                                                                            <input type="date" name="valid_up_to"
                                                                                value="{{date('Y-m-d')}}"
                                                                                class="form-control">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Revision No</label>
                                                                            <input readonly type="text" name="revision"
                                                                                class="form-control">
                                                                            <input readonly type="hidden"
                                                                                name="revision_count" class="form-control"
                                                                                value="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row hide">
                                                                        <div class="col-md-12">
                                                                            <!-- CheckBox -->
                                                                            <div class="checkbox_Details">
                                                                                <div class="check_felex">
                                                                                    <div class="check1">
                                                                                        <input type="checkbox" id="customer"
                                                                                            checked name="customer_type"
                                                                                            value="customer">
                                                                                        <label
                                                                                            for="customer">Customer</label>
                                                                                    </div>
                                                                                    <div class="check1">
                                                                                        <input type="checkbox" id="prospect"
                                                                                            name="customer_type"
                                                                                            value="prospect">
                                                                                        <label
                                                                                            for="prospect">Prospect</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12" id="prospect_details" hidden>
                                                                            <div class="col-md-3">
                                                                                <label for="">Prospect Company </label>
                                                                                <select class="form-control"
                                                                                    onchange="getContactByprospect(this)"
                                                                                    name="prospect_id" id="">
                                                                                    <option value="">Select Prospect
                                                                                    </option>
                                                                                    @foreach(CommonHelper::get_all_prospect() as $porspect)

                                                                                        <option value="{{$porspect->id}}">
                                                                                            {{$porspect->company_name}}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for=""> Contact Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="contact_name">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="">Contact Number </label>
                                                                                <input type="text" class="form-control"
                                                                                    id="contact_number">

                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="">Contact Email</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="contact_email">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12" id="customer_details">
                                                                            <div class="row">
                                                                                <div class="col-md-3">
                                                                                    <label for="">Customer</label>
                                                                                    <select name="customer_id"
                                                                                        onchange="get_customer_details(this.value)"
                                                                                        class="form-control requiredField" id="customer_id">
                                                                                        <option value="">Select Customer
                                                                                        </option>
                                                                                        @foreach (CommonHelper::get_customer() as $customer)
                                                                                            <option value="{{$customer->id}}">
                                                                                                {{$customer->name}}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Contact Person</label>
                                                                                    <input readonly type="text"
                                                                                        name="representative_name"
                                                                                        id="representative_name"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">Customer Address</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_address"
                                                                                        id="customer_address"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="">City</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_city"
                                                                                        id="customer_city"
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-4 hide">
                                                                                    <label for="">Customer Name</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_name"
                                                                                        id="customer_name"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-4 hide">
                                                                                    <label for="">Customer Code</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_code"
                                                                                        id="customer_code"
                                                                                        class="form-control">
                                                                                </div>

                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <label for="">Country</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_country"
                                                                                        id="customer_country"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="">STN No</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_stn"
                                                                                        id="customer_stn"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="">NTN No</label>
                                                                                    <input readonly type="text"
                                                                                        name="customer_ntn"
                                                                                        id="customer_ntn"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-3"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Date and Currency -->
                                                        <div class="col-md-12 bor-bo">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <h2>Date and Currency</h2>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <div class="col-md-3">
                                                                        <label for="">Currency</label>
                                                                        <select required onchange="getrate()" name="currency_id" class="form-control" id="currency_id">
                                                                            <option value="">Select Currency </option>
                                                                            @foreach(CommonHelper::get_all_currency() as $row)
                                                                                <option data-value="{{ $row->rate }}" value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <!-- <div class="col-md-3">
                                                                        <label for="">Inquiry Reference Date</label>
                                                                        <input type="date" name="inquiry_refer_date"
                                                                            class="form-control">
                                                                    </div> -->
                                                                    <div class="col-md-3">
                                                                        <label for="">Exchange Rate</label>
                                                                        <input type="text" name="exchange_rate" value="1"
                                                                            id="exchange_rate" class="form-control">
                                                                        {{-- <a href="#" class="btn-add"
                                                                            onclick="createContact('contact/createContact','','Add Contact','')">+</a>
                                                                        --}}
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Sales Details -->
                                                        <div class="col-md-12 bor-bo">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <h2>Sales Details</h2>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <div class="row">
                                                                        <div class="col-md-3 hide">
                                                                            <label for="">Sales Pool</label>
                                                                            <select name="sale_pool" class="form-control" id="">
                                                                                <option value="">Select</option>
                                                                                @foreach(CommonHelper::get_table_data('sales_pools') as $item)
                                                                                    <option value="{{$item->id}}">{{$item->name}}
                                                                                    </option>
                                                                                @endforeach

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Type</label>
                                                                            <select name="type" class="form-control" id="">
                                                                                <option value="">Select</option>
                                                                                @foreach(CommonHelper::get_table_data('sales_types') as $item)
                                                                                    <option value="{{$item->id}}">{{$item->name}}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Mode of Delivery</label>
                                                                            <select name="mode_of_delivry" class="form-control"
                                                                                id="">
                                                                                <option value="">Select </option>
                                                                                @foreach(CommonHelper::get_table_data('mode_deliveries') as $item)
                                                                                    <option value="{{$item->id}}">{{$item->name}}
                                                                                    </option>
                                                                                @endforeach

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 hide">
                                                                            <label for="">Subject Line</label>
                                                                            <input type="text" name="subject_line"
                                                                                class="form-control">
                                                                        </div>
                                                                        <div class="col-md-3 hide">
                                                                            <label for="">Storage Dimension</label>
                                                                            <select name="storgae_dimension"
                                                                                class="form-control" id="">
                                                                                <option value="">Select</option>
                                                                                @foreach(CommonHelper::get_table_data('storage_dimentions') as $item)
                                                                                    <option value="{{$item->id}}">{{$item->name}}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Sales Tax Group</label>
                                                                            <select onchange="saletax(this)"
                                                                                name="sale_taxt_group" class="form-control"
                                                                                id="sale_taxt_group">
                                                                                <option value="">Select</option>
                                                                                @foreach(CommonHelper::get_table_data('gst') as $item)
                                                                                    <option value="{{$item->id}},{{$item->rate}}">
                                                                                        {{$item->rate}} %
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Sales Tax Rate</label>
                                                                            <input type="text" class="form-control" readonly
                                                                                name="sale_tax_rate" id="sale_tax_rate">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Further Tax Group</label>
                                                                            <select style="width: 100%" onchange="furtherTax(this)" name="further_taxes_group" class="form-control select" id="further_taxes_group">
                                                                                <option value="">Select</option>
                                                                                @foreach(CommonHelper::get_table_data('gst') as $item)
                                                                                    <option value="{{$item->id}},{{$item->rate}}">
                                                                                        {{$item->rate}} %
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Further Tax Rate</label>
                                                                            <input type="text" class="form-control" readonly name="further_tax" id="further_tax" />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Advance Tax Group</label>
                                                                            <select onchange="advanceTax(this)" name="advance_tax_rate" id="advance_tax_rate" class="form-control">
                                                                                <option value="">Select</option>
                                                                                <option value="1,1.0">1.0 %</option>
                                                                                <option value="2,1.5">1.5 %</option>
                                                                                <option value="3,2.0">2.0 %</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="">Advance Tax Rate</label>
                                                                            <input type="number" class="form-control"
                                                                                name="advance_tax" id="advance_tax" readonly />
                                                                        </div>
                                                                    </div>
                                                                   
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label for="">Cartage Amount</label>
                                                                            <input type="number" class="form-control"
                                                                                name="cartage_amount" id="cartage_amount" />
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    {{-- <div class="col-md-3">
                                                                        <label for="">Delivery Terms</label>
                                                                        <select name="delivery_term" class="form-control"
                                                                            id="">
                                                                            <option value="1">01</option>
                                                                            <option value="2">02</option>
                                                                            <option value="3">03</option>
                                                                        </select>
                                                                    </div> --}}

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 bor-bo">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    &nbsp;
                                                                </div>
                                                                <!-- Terms & Condition: -->
                                                                <div class="col-md-10">
                                                                    <div class="col-md-12">
                                                                        <label for="">Terms & Condition:</label>
                                                                        <textarea rows="6" type="text" name="term_condition"
                                                                            id="terms_condition" class="form-control">
                                                                                                </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Quotation Created By -->
                                                        <div class="col-md-12 bor-bo hide">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <h2>Quotation Created By</h2>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <div class="col-md-3">
                                                                        <label for="">Name</label>
                                                                        <input type="text" name="created_by"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="">Designation</label>
                                                                        <input type="text" name="designation"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label for="">Company Name</label>
                                                                        <input type="text" name="company_name"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Quotation Chart -->
                                                        <!-- <div class="row bor-bo">
                                                                                    <div class="col-md-12">
                                                                                        <h2>Item Details</h2>
                                                                                    </div>
                                                                                </div> -->
                                                        <div class="row bor-bo">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive" id="">
                                                                    <table
                                                                        class="userlittab table table-bordered sf-table-list">
                                                                        <thead>
                                                                            <tr>
                                                                                <!-- <th class="text-center">Category</th> -->
                                                                                <th class="text-center col-sm-2">Item</th>
                                                                                <!-- <th class="text-center">Item Name</th> -->
                                                                                <th class="text-center">Pack Size</th>
                                                                                <th class="text-center">UOM</th>
                                                                                <th class="text-center">Color</th>
                                                                                <th class="text-center">Qty</th>
                                                                                <th class="text-center">Unit Price</th>
                                                                                <th class="text-center">Total</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="AppnedHtml">
                                                                            <tr class="main" id="RemoveRows1">
                                                                                <!-- <td>
                                                                                                    <select onchange="get_sub_item('category_id1')"  name="category[]" id="category_id1" class="form-control select2 category">
                                                                                                        <option value="">Select</option>
                                                                                                        @foreach (CommonHelper::get_all_category() as $category)
                                                                                                            <option value="{{ $category->id }}"> {{ $category->main_ic }} </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </td> -->
                                                                                <td>
                                                                                    <select
                                                                                        onchange="get_item_name(1);"
                                                                                        class="form-control item_id requiredField"
                                                                                        name="item_id[]" id="item_id1">
                                                                                        <option value="">Select</option>
                                                                                        @foreach($sub_item as $val)
                                                                                            <option
                                                                                                value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic. '@' . $val->pack_size . '@' . $val->type. '@' . $val->color }}">
                                                                                                {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type. ' ' . $val->color }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input readonly type="text" name="pack_size[]" id="pack_size1" class="form-control" />
                                                                                </td>
                                                                                <td>
                                                                                    <input readonly id="uom_id1" type="text"
                                                                                        name="uom[]"
                                                                                        class="form-control uom">
                                                                                </td>
                                                                                <td>
                                                                                    <input readonly type="text" name="color[]" id="color1" class="form-control" />
                                                                                </td>
                                                                                <!-- <td>    
                                                                                    <input readonly type="text" class="form-control item_code" name="item_code[]" id="item_code1">
                                                                                </td> 
                                                                                <td>
                                                                                    <select style="width: 100% !important;"
                                                                                        class="form-control select2 requiredField"
                                                                                        name="pack_type[]" id="pack_type1" onchange="getItemColors(1)"">
                                                                                        <option value="">Select Pack Type  </option>
                                                                                    </select>
                                                                                </td>
                                                                               
                                                                                <td>
                                                                                    <select style="width: 100% !important;"
                                                                                        class="form-control select2 requiredField"
                                                                                        name="color[]" id="color1">
                                                                                        <option value="">Select Color
                                                                                        </option>

                                                                                    </select>
                                                                                </td>-->
                                                                                <td>
                                                                                    <input type="text"
                                                                                        class="form-control requiredField qty"
                                                                                        onkeyup="calculation_amount()"
                                                                                        name="qty[]" id="qty1">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="unit_price[]"
                                                                                        onkeyup="calculation_amount()"
                                                                                        id="unit_price"
                                                                                        class="form-control requiredField unit_price">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" readonly
                                                                                        name="total[]" id="total"
                                                                                        class="form-control total">
                                                                                </td>
                                                                                <td>
                                                                                    <a href="#"
                                                                                        class="btn btn-sm btn-primary"
                                                                                        onclick="AddMoreDetails()">
                                                                                        <i class="fa fa-plus-circle"
                                                                                            aria-hidden="true"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-8"></div>
                                                            <div class="col-md-4 my-lab">
                                                                <label for="">
                                                                    Total Amount
                                                                </label>
                                                                <input type="text" readonly value="" name="grand_total"
                                                                    id="grand_total" class="form-control">
                                                                <label for="">
                                                                    Total Tax
                                                                </label>
                                                                <input type="text" readonly value="" name="total_tax"
                                                                    id="total_tax" class="form-control">

                                                                <label for="">
                                                                    Total Amount With Tax
                                                                </label>
                                                                <input type="text" readonly value=""
                                                                    name="grand_total_with_tax" id="grand_total_with_tax"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-right">
                                                                <button type="submit" class="btn btn-success mr-1"
                                                                    data-dismiss="modal">Save</button>

                                                                <a type="button"
                                                                    href="{{url('saleQuotation/listSaleQuotation')}}"
                                                                    class="btnn btn-secondary"
                                                                    data-dismiss="modal">Cancel</a>

                                                                <!-- <label for="">
                                                                                Save As Draft
                                                                                <input type="checkbox" name="save_as_draft"
                                                                                    class="form-control" value="1" id="">
                                                                            </label> -->
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            $(".btn-success").click(function (e) {
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        $('#dataForm').submit();
                    }
                    else {
                        return false;
                    }
                }
            });

            CKEDITOR.replace('terms_condition');
            // CKEDITOR.replace('item_description2',{
            //     toolbar: [],
            //     allowedContent: 'p h1 h2 strong'
            // });
            // CKEDITOR.replace('item_description1',{
            //     toolbar: [],
            //     allowedContent: 'p h1 h2 strong'
            // });

            // CKEDITOR.replace('item_description1', {
            //     toolbar: [],
            //     allowedContent: 'p h1 h2 strong'
            // });

            $("#prospect_details").hide();
        
            $('select').select2();
        });

        var Counter = 2;
        function AddMoreDetails() {
            $('#AppnedHtml').append
                (`
                <tr class="main" id="RemoveRows${Counter}">
                   
                    <td>
                        <select
                            onchange="get_item_name(${Counter}); getItemPackSize(${Counter})" 
                            class="form-control item_id requiredField"
                            name="item_id[]" id="item_id${Counter}">
                            <option value="">Select</option>
                            @foreach($sub_item as $val)
                                <option
                                    value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic. '@' . $val->pack_size . '@' . $val->type. '@' . $val->color }}">
                                    {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type. ' ' . $val->color }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input readonly type="text" name="pack_size[]" id="pack_size${Counter}" class="form-control" />
                    </td>
                    <td>
                        <input readonly id="uom_id${Counter}" type="text" name="uom[]" class="form-control uom" />
                    </td>
                    <td>
                        <input readonly type="text" name="color[]" id="color${Counter}" class="form-control" />
                    </td>

                    <td>
                        <input type="text" class="form-control requiredField qty" onkeyup="calculation_amount()" name="qty[]" id="qty${Counter}">
                    </td>
                    <td>
                        <input type="text" name="unit_price[]" onkeyup="calculation_amount()" id="unit_price${Counter}" class="form-control requiredField unit_price">  
                    </td>
                    <td>
                        <input type="text" readonly name="total[]" id="total${Counter}" class="form-control total"> 
                    </td>
                    <td> 
                        <a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            `);

             


            $('#item_id' + Counter).select2();

            calculation_amount();

            CKEDITOR.replace('item_description' + Counter, {
                toolbar: [],
                allowedContent: 'p h1 h2 strong'
            });
            Counter++;
        }

        function getrate() {
            var selectedOption = document.querySelector('#currency_id option:checked');
            var rate = selectedOption.getAttribute('data-value');
            document.getElementById('exchange_rate').value = rate ? rate : '1.0';
        }

        function deliverytype(instance) {
            id = instance.value;
            console.log(id);
            if (id == 1) {
                $(instance).closest('.main').find('.bundle_length').prop('readonly', false);

            } else {
                $(instance).closest('.main').find('.bundle_length').prop('readonly', false);
                // $(instance).closest('.main').find('.bundle_length').prop('readonly', true);
            }
        }


        $("input[name=customer_type]").on("click", function () {
            $("input[name=customer_type]").prop("checked", false);
            $(this).prop("checked", true);
        });
        $('#sale_taxt_group').on('change', function () {
            calculation_amount();
        });
        $("input[name=customer_type]:checkbox").click(function () {

            if ($(this).attr("value") == "customer") {

                $("#prospect_details").hide();
                $("#customer_details").show();
                $("#customer_details :input").prop("disabled", false);
                $("#prospect_details :input").prop("disabled", true);
            }
            if ($(this).attr("value") == "prospect") {
                $("#prospect_details").show();
                $("#customer_details").hide();
                $("#prospect_details :input").prop("disabled", false);
                $("#customer_details :input").prop("disabled", true);
            }
        });
        function saletax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#sale_tax_rate').val(excet_value[1])
        }

        function furtherTax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#further_tax').val(excet_value[1])
        }

        function advanceTax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#advance_tax').val(excet_value[1])
        }
    </script>
    <script type="text/javascript">

       function RemoveSection(row) {
                var element = document.getElementById("RemoveRows" + row);
                if (element) {
                    element.parentNode.removeChild(element);
                }
                calculation_amount();
            }
    </script>
    <script>
        function get_customer_details(id) {
            var id = id;
            console.log(id)
            $.ajax({
                url: '<?php echo url('/')?>/customer/get_customer',
                type: 'Get',
                data: { id: id },
                success: function (data) {
                    $('#customer_ntn').val(data.cnic_ntn);
                    $('#customer_stn').val(data.strn);
                    $('#customer_country').val(data.country_name);
                    $('#customer_city').val(data.city_name);
                    $('#customer_address').val(data.address);
                    $('#customer_name').val(data.name);
                    $('#customer_code').val(data.customer_code);
                    $('#representative_name').val(data.name);

                }
            });
        }

        function get_item_name(index) {
            var item = $('#item_id' + index).val();
            
            var uom = item.split('@');
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
            $('#pack_size' + index).val(uom[3]+ ' '+uom[4] );
            $('#color' + index).val(uom[5]);
        }

        function item_change(datas) {
            var id = datas.value;
            $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
                type: 'Get',
                data: { id: id },
                success: function (data) {
                    $(datas).closest('.main').find('.item_code').val(data.item_code);
                    $(datas).closest('.main').find('.item_description').val(data.description);
                    $(datas).closest('.main').find('.uom').val(data.uom_name);
                }
            });
        }
        function calculation_amount() {
            var grad_total = 0;
            var tax = $('#sale_tax_rate').val();
            var fTax = $('#further_tax').val();
            var aTax = $('#advance_tax').val();
            var rat_ex = $('#exchange_rate').val();
            let cartage_amount = parseFloat($('#cartage_amount').val()) || 0;
            
            var sale_tax = tax ? tax : 0;
            var advance_tax = aTax ? aTax : 0;
            var further_tax = fTax ? fTax : 0;
            
            var exchange_rate = rat_ex ? rat_ex : 1;

            var befor_tax = 0;
            var all_tax = 0;
            var actual_qty = 0;
            var actual_rate = 0;

            $('.item_id').each(function () {
                var total = 0;
                var actual_rate = $(this).closest('.main').find('.unit_price').val();
                var actual_qty = $(this).closest('.main').find('.qty').val();
                var rate = actual_rate ? actual_rate : 0;
                var qty = actual_qty ? actual_qty : 0;
                total = parseFloat(qty) * parseFloat(rate);

                var sale_tax_amount = total / 100 * sale_tax;
                var further_tax_amount = total / 100 * further_tax;
                var advance_tax_amount = total / 100 * advance_tax;

                grad_total += total + sale_tax_amount + advance_tax_amount + cartage_amount + further_tax_amount;
                befor_tax += total;
                all_tax += sale_tax_amount + advance_tax_amount + further_tax_amount;
                $(this).closest('.main').find('.total').val(total.toFixed(3));
            });

            $('#total_tax').val(all_tax.toFixed(3));
            $('#grand_total').val(befor_tax.toFixed(3));
            $('#grand_total_with_tax').val(grad_total.toFixed(3));
        }

        function get_sub_item_by_id(instance) {
            var category = instance.value;
            $(instance).closest('.main').find('#item_id').empty();
            $.ajax({
                url: '{{ url("/getSubItemByCategory") }}',
                type: 'Get',
                data: { category: category },
                success: function (response) {
                    $(instance).closest('.main').find('#item_id').append(response);

                }
            });
        }

        function getContactByprospect(instance) {
            var prospect_id = instance.value;
            $.ajax({
                url: '{{ url("/getContactByprospect") }}',
                type: 'Get',
                data: { prospect_id: prospect_id },
                success: function (response) {
                    $('#contact_name').val(response.first_name);
                    $('#contact_number').val(response.cell);
                    $('#contact_email').val(response.email);
                }
            });
        }

        function getItemColors(id) {
            var item_id = $('#item_id' + id).val();
            var pack_type = $('#pack_type' + id).val();
            $.ajax({
                url: '{{ url('/saleQuotation/getItemColors') }}',
                data: { item_id: item_id, pack_type: pack_type },
                type: 'GET',
                success: function (response) {
                    var colorSelect = $('#color' + id);
                    colorSelect.empty().append('<option value="">Select Color</option>');

                    response.forEach(function (color) {
                        colorSelect.append('<option value="' + color + '">' + color + '</option>');
                    });

                    colorSelect.select2();
                }
            });
        }

        function getItemPackSize(id) {
            var item_id = $('#item_id' + id).val();
            $.ajax({
                url: '{{ url('/saleQuotation/getItemPackSize') }}',
                data: { item_id: item_id },
                type: 'GET',
                success: function (response) {
                    var packSize = $('#pack_type' + id);
                    packSize.empty().append('<option value="">Select Color</option>');

                    response.forEach(function (pack) {
                        packSize.append('<option value="' + pack.id + '">' + pack.pack_size + ' ' + pack.uom_name + ' ' + pack.type + '</option>');
                    });

                    packSize.select2();
                }
            });
        }
    </script>
@endsection