@extends('layouts.default')

@section('content')
@include('select2')
<?php
use App\Helpers\CommonHelper;
$quotation_no =CommonHelper::generateUniquePosNo('sale_quotations','revision_no','REV');
$grand_total = 0;
$counter = 1;
?>
<style> 
td.diswrp span{width:233px !important;}
td.diswrap span.select2.select2-container.select2-container--default.select2-container--focus{width:200px !important;}
td.diswrp span b{right:10px;}
.m-tab tr td span b{right:10px !important;left:inherit !important;}
.m-tab tr td label{display:block;}
.m-tab tr td span{font-size:8px;}
element.style{}

@media (min-width: 992px)
{.col-md-2{}
}

.cke_notification_warning {
    display: none;
}
</style>

<!-- <div class="well_N"> -->
   
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">    
                    <form action="{{route('saleQuotaionStore')}}" method="post" id="dataForm" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" value="1" name="revison_status" >
                        <input type="hidden" name="quoatation_id" value="{{$salequoatation->id}}" >
                        <input type="hidden" name="formSection[]" class="form-control requiredField" id="demandsSection"
                            value="1" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                    <div class="headquid bor-bo">
                                <h2 class="subHeadingLabelClass">Edit Sale Quotation</h2>
                                </div>
                                <div class="">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">

                                                <div class="col-md-2">
                                                        <h2>Quotation Details</h2>
                                                    </div>
                                                    <div class="col-md-10">
                                                        {{-- <div class="col-md-3">
                                                            <label for="">Prospect Company </label>
                                                            <select class="form-control" name="prospect_id" id="">
                                                                <option value="">Select Prospect</option>
                                                                @foreach(CommonHelper::get_all_prospect() as $porspect)

                                                                <option value="{{$porspect->id}}">{{$porspect->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        
                                                        </div> --}}
                                                        <div class="col-md-3">
                                                            <label for="">Quotation Number</label>
                                                            <input type="text" name="quotation_no" value="{{$salequoatation->quotation_no}}" readonly class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Quotation Date</label>
                                                            <input type="date" name="quotation_date" value="{{$salequoatation->quotation_date}}" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Quotation Valid Up To</label>
                                                            <input type="date" name="valid_up_to" value="{{$salequoatation->q_valid_up_to}}" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Revision No</label>
                                                            <input readonly type="text" value="REV-00{{$salequoatation->revision_count+1}}" name="revision" class="form-control" />
                                                            <input type="hidden" name="revision_count" value="{{$salequoatation->revision_count+1}}" id="" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Customer</label>
                                                            <select name="customer_id" onchange="get_customer_details(this.value)" class="form-control select2 requiredField" id="customer_id">
                                                                <option value="">Select Customer</option>
                                                                @foreach (CommonHelper::get_customer() as $customer)
                                                                    <option @if($salequoatation->customer_id == $customer->id) selected @endif  value="{{$customer->id}}">{{$customer->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Contact Person</label>
                                                            <input readonly type="text" name="representative_name" id="representative_name" class="form-control" />
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            <label for="">Customer Address</label>
                                                            <input readonly type="text" name="customer_address" id="customer_address" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">City</label>
                                                            <input readonly type="text" name="customer_city" id="customer_city" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Country</label>
                                                            <input readonly type="text" name="customer_country" id="customer_country" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">STN No</label>
                                                            <input readonly type="text" name="customer_stn" id="customer_stn" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">NTN No</label>
                                                            <input readonly type="text" name="customer_ntn"  id="customer_ntn" class="form-control" />
                                                        </div>

                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12 bor-bo">
                                                    <div class="col-md-2">
                                                        
                                                    <!-- <input type="checkbox" id="customer" @if($salequoatation->customer_type == 'customer' ) checked @endif  name="customer_type" value="customer">
                                                    <label for="customer">Customer</label>
                                                    <input type="checkbox" id="prospect"  @if($salequoatation->customer_type == 'prospect' ) checked @endif name="customer_type" value="prospect">
                                                    <label for="prospect">Prospect</label>
                                                    </div> -->

                                                    <div class="col-md-10" id="prospect_details" @if($salequoatation->customer_type == 'customer' ) hidden @endif  > 
                                                        <div class="col-md-3">
                                                            <label for="">Prospect Company </label>
                                                            <select class="form-control select2" onchange="getContactByprospect(this)" name="prospect_id" id="prospect_id">
                                                                <option value="">Select Prospect</option>
                                                                @foreach(CommonHelper::get_all_prospect() as $porspect)
                                                                <option @if($salequoatation->prospect_id == $porspect->id) selected  @endif value="{{$porspect->id}}">{{$porspect->company_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        
                                                        </div>
                                                    
                                                        <div class="col-md-3">
                                                            <label for=""> Contact Name</label>
                                                            <input type="text" class="form-control" id="contact_name">
                                                        </div>
                                                        <div class="col-md-3">
                                                        <label for="">Contact Number </label>  
                                                        <input type="text" class="form-control" id="contact_number">

                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Contact Email</label>
                                                            <input type="text" class="form-control" id="contact_email">
                                                        </div>
                                                    </div>
                                    
                                                    <div class="col-md-10" id="customer_details" @if($salequoatation->customer_type == 'prospect' ) hidden @endif>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-md-12 bor-bo">
                                                    <div class="col-md-2">
                                                        <h2>Date and Currency</h2>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="col-md-3">
                                                            <label for="">Currency</label>
                                                            <select required onchange="getrate()" name="currency_id" class="form-control select2" id="currency_id">
                                                                <option value="">Select Currency </option>
                                                                @foreach(CommonHelper::get_all_currency() as $row)
                                                                    <option @if($salequoatation->currency_id == $row->id) selected @endif data-value="{{ $row->rate }}" value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Inquiry Reference Date</label>
                                                            <input type="date" value="{{$salequoatation->inquiry_reference_date}}"  name="inquiry_refer_date" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Exchange Rate</label>
                                                            <input type="text" value="{{$salequoatation->exchange_rate}}" name="exchange_rate" value="1" id="exchange_rate" class="form-control">
                                                            {{-- <a href="#" class="btn-add" onclick="createContact('contact/createContact','','Add Contact','')">+</a> --}}
                                                        </div>
                                                
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

                                                <div class="col-md-12 bor-bo">
                                                    <div class="col-md-2">
                                                        <h2>Sales Details</h2>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="row">
                                                            <div class="col-md-3 hide">
                                                                <label for="">Sales Pool</label>
                                                                <select name="sale_pool" class="form-control select2" id="">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_table_data('sales_pools') as $item)
                                                                    <option  @if($salequoatation->sales_poal == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="">Type</label>
                                                                <select name="type" class="form-control select2" id="">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_table_data('sales_types') as $item)
                                                                    <option @if($salequoatation->type == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 hide">
                                                                <label for="">Subject Line</label>
                                                                <input type="text"  value="{{$salequoatation->subject_line}}" name="subject_line" class="form-control">
                                                            </div>
                                                            <div class="col-md-3 hide">
                                                                <label for="">Storage Dimension</label>
                                                                <select name="storgae_dimension" class="form-control select2" id="">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_table_data('storage_dimentions') as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="">Sales Tax Group</label>
                                                                <select onchange="saletax(this)" name="sale_taxt_group" class="form-control select2" id="sale_taxt_group">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_table_data('sales_tax_groups') as $item)
                                                                    <option @if($salequoatation->sale_tax_group == $item->id) selected @endif value="{{$item->id}},{{$item->rate}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="">Sales Tax rate</label>
                                                                <input type="text" value="{{$salequoatation->sales_tax_rate}}" class="form-control" readonly name="sale_tax_rate" id="sale_tax_rate">
                                                            </div>
                                                            
                                                            <div class="col-md-3">
                                                                <label for="">Mode of Delivery</label>
                                                                <select name="mode_of_delivry" class="form-control select2" id="">
                                                                    <option value="">Select </option>
                                                                    @foreach(CommonHelper::get_table_data('mode_deliveries') as $item)
                                                                    <option @if($salequoatation->mode_of_delivery == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="control-label">Further Tax Rate</label>
                                                                <select style="width: 100%" onchange="furtherTax(this)" name="further_taxes_group" class="form-control select" id="further_taxes_group">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_table_data('gst') as $item)
                                                                        <option @if($salequoatation->further_tax_group == $item->id) selected @endif value="{{$item->id}},{{$item->rate}}">
                                                                            {{$item->rate}} %
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="control-label">Further Tax Amount</label>
                                                                <input type="text" class="form-control" readonly name="further_tax" id="further_tax" value="{{ $salequoatation->further_tax }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="">Advance Tax Rate</label>
                                                                <select onchange="advanceTax(this)" name="advance_tax_rate" id="advance_tax_rate" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <option @if($salequoatation->advance_tax_group == 1) selected @endif value="1,1.0">1.0 %</option>
                                                                    <option @if($salequoatation->advance_tax_group == 2) selected @endif value="2,1.5">1.5 %</option>
                                                                    <option @if($salequoatation->advance_tax_group == 3) selected @endif value="3,2.0">2.0 %</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="">Advance Tax Amount</label>
                                                                <input type="number" class="form-control"
                                                                    name="advance_tax" id="advance_tax" value="{{ $salequoatation->advance_tax }}"readonly />
                                                            </div>
                                                        </div>
                                                         <div class="row">
                                                            <div class="col-md-3">
                                                                <label for="">Cartage Amount</label>
                                                                <input type="number" class="form-control"
                                                                    name="cartage_amount" id="cartage_amount" value="{{ $salequoatation->cartage_amount }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 bor-bo">
                                                    <div class="col-md-2">
                                                    &nbsp;
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="col-md-12">
                                                            <label for="">Terms & Condition:</label>
                                                            <textarea rows="6" type="text" name="term_condition" id="terms_condition" class="form-control">
                                                                {{$salequoatation->terms_condition}}
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 bor-bo hide">
                                                    <div class="col-md-2">
                                                        <h2>Quotation Created By</h2>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="col-md-3">
                                                            <label for="">Name</label>
                                                            <input type="text" name="created_by"  value="{{$salequoatation->created_by}}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Designation</label>
                                                            <input type="text" name="designation" value="{{$salequoatation->designation}}" class="form-control">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="">Company Name</label>
                                                            <input type="text" name="company_name"  value="{{$salequoatation->company_name}}" class="form-control">
                                                        </div> 
                                                    </div>
                                                </div>


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
                                                                    @foreach($salequoatationdata as $key => $qouate)
                                                                        @php
                                                                        $grand_total += $qouate->total_amount;   
                                                                        @endphp
                                                                        <tr class="main" id="RemoveRows{{ $counter }}">
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
                                                                                    onchange="get_item_name('{{ $counter }}'))"
                                                                                    class="form-control item_id select2 requiredField"
                                                                                    name="item_id[]" id="item_id{{ $counter }}">
                                                                                    <option value="">Select</option>
                                                                                    @foreach($sub_item as $val)
                                                                                        <option @if($qouate->item_id == $val->id) selected @endif
                                                                                            value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic. '@' . $val->pack_size . '@' . $val->type. '@' . $val->color }}">
                                                                                            {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type. ' ' . $val->color }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <!-- <td>    
                                                                                <input readonly type="text" class="form-control item_code" name="item_code[]" id="item_code1">
                                                                            </td> -->
                                                                            <td>
                                                                                <input readonly type="text" name="pack_size[]" id="pack_size{{ $counter }}" class="form-control" />
                                                                            </td>
                                                                            <td>
                                                                                <input readonly id="uom_id{{ $counter }}" type="text"
                                                                                    name="uom[]"
                                                                                    class="form-control uom">
                                                                            </td>
                                                                            <td>
                                                                                <input readonly type="text" name="color[]" id="color{{ $counter }}" class="form-control" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                    class="form-control requiredField qty"
                                                                                    onkeyup="calculation_amount()"
                                                                                    name="qty[]" id="qty{{ $counter }}" value="{{ $qouate->qty }}" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="unit_price[]"
                                                                                    onkeyup="calculation_amount()"
                                                                                    id="unit_price{{ $counter }}"
                                                                                    class="form-control unit_price requiredField" value="{{ $qouate->unit_price }}" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" readonly
                                                                                    name="total[]" id="total{{ $counter }}"
                                                                                    class="form-control total" value="{{ $qouate->total_amount }}">
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
                                                                        <script>
                                                                            $(document).ready(function(){
                                                                                get_item_name('{{ $counter }}');
                                                                            });
                                                                        </script>
                                                                        @php $counter++ @endphp
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12  bor-bo padtb">

                                                    <div class="col-md-8"></div>    
                                                    <div class="col-md-4 my-lab">
                                                        <label for="">
                                                        Total Amount 
                                                        </label>
                                                        <input type="text" readonly value="{{$grand_total }}" name="grand_total" id="grand_total" class="form-control">
                                                        <label for="">
                                                        Total Tax 
                                                        </label>
                                                        @php 
                                                        $tax_amount = $grand_total/100*$salequoatation->sales_tax_rate;
                                                        @endphp
                                                        
                                                        <input type="text" readonly value="{{ $tax_amount}}" name="total_tax" id="total_tax" class="form-control">
                                                    
                                                        <label for="">
                                                        Total Amount With Tax
                                                        </label>
                                                        <input type="text" readonly value="{{$salequoatation->total_amount_after_sale_tax}}" name="grand_total_with_tax" id="grand_total_with_tax" class="form-control">
                                                    
                                                        <div class="save_buttons">
                                                            <div class="savms">
                                                                <button type="submit" class="btn btn-success mr-1" data-dismiss="modal">Update</button>
                                                                <!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button> -->
                                                            </div>
                                                            <div class="save_drafts">
                                                                <!-- <label for="">
                                                                    Save As Draft
                                                                </label>
                                                                <input type="checkbox" name="save_as_draft" class="form-control" value="1" id=""> -->
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
<!-- </div> -->

<script>
    var Counter = '{{ $counter }}';
    $(document).ready(function(){

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
        $('.item_description').each(function () {
            CKEDITOR.replace(this.id, {
                toolbar: [],
                allowedContent: 'p h1 h2 strong'
            });

            let instance = $('#prospect_id').val();
            getContactByprospect(instance);
            let instance2 = $('#customer_id').val();
            get_customer_details(instance2);

        });

          

    $("#prospect_details").hide();
        $('.item_id').each(function(){
            get_all_currenecy();
            
        });
       

        $('.select2').select2();
    });


    function getrate() {
        var selectedOption = document.querySelector('#currency_id option:checked');
        var rate = selectedOption.getAttribute('data-value');
        document.getElementById('exchange_rate').value = rate ? rate : '1.0';
    }
    
    function deliverytype(instance)
    {
        id  = instance.value;
        console.log(id);
        if(id == 1)
        {
            $(instance).closest('.main').find('.bundle_length').prop('readonly', false);

        }else{
            $(instance).closest('.main').find('.bundle_length').prop('readonly', true);
        }
    }


    $("input[name=customer_type]").on("click", function() {
    $("input[name=customer_type]").prop("checked", false);
    $(this).prop("checked", true);
    });
    $('#sale_taxt_group').on('change',function(){
        calculation_amount();
    });
    $("input[name=customer_type]:checkbox").click(function() {

        if($(this).attr("value")=="customer") {
        
            $("#prospect_details").hide();
            $("#customer_details").show();
            $("#customer_details :input").prop("disabled", false);
            $("#prospect_details :input").prop("disabled", true);
        }
        if($(this).attr("value")=="prospect") {
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
    
    function AddMoreDetails() {
        $('#AppnedHtml').append
            (`
            <tr class="main" id="RemoveRows${Counter}">
                <td>
                    <select
                        onchange="get_item_name(${Counter}); getItemColors('${Counter}')"
                        class="form-control item_id"
                        name="item_id[]" id="item_id${Counter}">
                        <option value="">Select</option>
                        @foreach($sub_item as $val)
                            <option
                                value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic }}">
                                {{ $val->item_code . ' -- ' . $val->sub_ic }}
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
                    <input type="text" name="unit_price[]" onkeyup="calculation_amount()" id="unit_price${Counter}" class="form-control unit_price">  
                </td>
                <td>
                    <input type="text" readonly name="total[]" id="total${Counter}" class="form-control total"> 
                </td>
                <td> 
                    <a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection()">
                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        `);


        // $('#item_id' + Counter).select2();
        // $('.item_id').each(function () {
        //     get_all_currenecy();
        // });

        // $('.category').each(function () {
        //     $('.category').select2();
        // });
        calculation_amount();

        CKEDITOR.replace('item_description' + Counter, {
            toolbar: [],
            allowedContent: 'p h1 h2 strong'
        });
        Counter++;
    }

    function RemoveSection(row) {
        var element = document.getElementById("RemoveRows" + row);
        if (element) {
            element.parentNode.removeChild(element);
        }
        Counter--;
        calculation_amount();
    }

    function get_customer_details(id) {
        var id = id;
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

    // function item_change(datas)
    // {
    //     var id = datas.value;
    //     $.ajax({
    //             url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
    //             type: 'Get',
    //             data: {id:id},
    //         success: function (data) {
    //             $(datas).closest('.main').find('#item_code').val(data.item_code);
    //             $(datas).closest('.main').find('#item_description').val(data.description);
    //             $(datas).closest('.main').find('#uom').val(data.uom_name);
    //         }
    //         });

    // }

    function calculation_amount() {
        var grad_total = 0;
        var tax = $('#sale_tax_rate').val();
        var fTax = $('#further_tax').val();
        var aTax = $('#advance_tax').val();
        var rat_ex = $('#exchange_rate').val();
        var exchange_rate = rat_ex ? rat_ex : 1;
        var befor_tax = 0;
        var all_tax = 0;
        var actual_qty = 0;
        var actual_rate = 0;
        let cartage_amount = parseFloat($('#cartage_amount').val()) || 0;

        var sale_tax = tax ? tax : 0;
        var advance_tax = aTax ? aTax : 0;
        var further_tax = fTax ? fTax : 0;

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

    // function  get_sub_item_by_id(instance)
    // {


    //     var category= instance.value;
    
    //     $(instance).closest('.main').find('#item_id').empty();
    //     $.ajax({
    //         url: '{{ url("/getSubItemByCategory") }}',
    //         type: 'Get',
    //         data: {category: category},
    //         success: function (response) {
    //             $(instance).closest('.main').find('#item_id').append(response);
            
    //         }
    //     });
    // }
    // function  getContactByprospect(instance)
    // {


    //     var prospect_id = instance.value;
    //     $.ajax({
    //         url: '{{ url("/getContactByprospect") }}',
    //         type: 'Get',
    //         data: {prospect_id: prospect_id},
    //         success: function (response) {
    //         $('#contact_name').val(response.first_name);
    //         $('#contact_number').val(response.cell);
    //         $('#contact_email').val(response.email);
            
    //         }
    //     });
    // }

     function get_item_name(index) {
        var item = $('#item_id' + index).val();
        var uom = item.split('@');
        $('#uom_id' + index).val(uom[1]);
        $('#item_code' + index).val(uom[2]);
        $('#pack_size' + index).val(uom[3]+ ' '+uom[4] );
        $('#color' + index).val(uom[5]);
    }


    function getItemColors(id, selectedColor = null) {
        var item_id = $('#item_id' + id).val();
        $.ajax({
            url: '{{ url('/saleQuotation/getItemColors') }}',
            data: { item_id: item_id },
            type: 'GET',
            success: function (response) {
                var colorSelect = $('#color' + id);
                colorSelect.empty().append('<option value="">Select Color</option>');

                response.forEach(function (color) {
                    colorSelect.append('<option value="' + color + '">' + color + '</option>');
                });

                colorSelect.select2();
                if (selectedColor) {
                    $(colorSelect).val(selectedColor).trigger('change');
                }
            }
        });
    }

</script>
@endsection