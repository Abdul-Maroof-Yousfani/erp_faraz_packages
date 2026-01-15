
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\SalesHelper;
    $counter = 1;
    $total_amount = 0;

    if($sale_quotations->customer_type == 'customer'):
       $contact_person = strtoupper(CommonHelper::byers_name($sale_quotations->customer_id)->name);
    endif;
    if($sale_quotations->customer_type == 'prospect'):
        $contact_id = CommonHelper::get_prospect($sale_quotations->prospect_id)->contact_id;
        $contact = CommonHelper::get_contacts($contact_id);
       
        $contact_person = strtoupper( $contact->first_name.' '.$contact->last_name);
    endif;
    ?>
<style>
    .table-responsive{
        overflow-x: unset !important;
    }
</style>
    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="dp_sdw" id="printReport">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 "></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <div class="premier_igm">
                                                 <img src="{{asset('public/assets/img/premier.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="headquid  head_quotations">
                                                    <h2 class="subHeadingLabelClass">Ref: {{$sale_quotations->quotation_no}}</h2>
                                                    <h3 class="subHeadingLabelClass">ATTN: {{$contact_person}}</h3>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="headquid head_quotations2">
                                                    <h2 class="subHeadingLabelClass">Karachi: {{ date('d-M-Y', strtotime($sale_quotations->quotation_date)) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="headquid_aprk">
                                                    <h2 class="subHeadingLabelClass">
                                                        @if($sale_quotations->customer_type == 'customer')
                                                            {{strtoupper(CommonHelper::byers_name($sale_quotations->customer_id)->name)}}
                                                            @endif
                                                            @if($sale_quotations->customer_type == 'prospect')
                                                            {{strtoupper(CommonHelper::get_prospect($sale_quotations->prospect_id)->company_name)}}

                                                        @endif
                                                    </h2>
                                                </div>
                                                <div class="headquid_apr2">
                                                    <h2 class="subHeadingLabelClass">Dear Sir,</h2>
                                                </div>
                                                <div class="headquid_apr2">
                                                    <h2 class="subHeadingLabelClass">SUB:	<span>QUOTATION FOR HDPE DUCT (100% PURE MATERIAL)</span></h2>
                                                </div>
                                                <div class="headquid_apr2">
                                                    <h2 class="subHeadingLabelClass">We are pleased to quote you our rates for HDPE Ducts as under:</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <!-- <div class="headquid">
                                        <h2 class="subHeadingLabelClass">Quotation Chart</h2>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive"> 
                                                <table class="table table-bordered lsitsleques_print sf-table-list userlittab viewsalequotation">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">S No</th>
                                                            <th class="wsale text-center">Category </th>
                                                            <th class="text-center">Product</th>
                                                            <th class="wsale2 text-center">Item Description</th>
                                                            <th class="text-center">QTY</th>
                                                            <th class="text-center">UOM</th>
                                                            <th class="text-center">Unit Price (without GST)</th>
                                                            <th class="text-center">Total Value (without GST)</th>
                                                            <th class="text-center"> GST % </th>
                                                            <th class="text-center">Unit Price (with GST)</th>
                                                            <th class="text-center">Total Value (with GST)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total_with_gst = 0;
                                                            $total_amount=0;
                                                        @endphp
                                                        @foreach ($sale_quotation_data as $value)
                                                        <tr>        
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="wsale">
                                                                @php
                                                                    $product = CommonHelper::get_item_by_id($value->item_id);  
                                                                    
                                                                    $sub_category_id =  isset($product)?CommonHelper::get_sub_category_name($product->sub_category_id): '-';
                                                                    @endphp
                                                                
                                                                {{$sub_category_id}}
                                                            </td>
                                                            <td class="text-left">
                                                                {{ (!empty(CommonHelper::get_item_by_id($value->item_id))) ? CommonHelper::get_item_by_id($value->item_id)->sub_ic : '' }}
                                                            </td>

                                                            <td class="wsale2 text-left">{!! $value->item_description??'-' !!}</td>
                                                            <td class="text-left">{{ number_format($value->qty,2) }}</td>
                                                    
                                                            <td class="text-left">
                                                                {{ (!empty(CommonHelper::get_item_by_id($value->item_id))) ? CommonHelper::get_item_by_id($value->item_id)->uom_name : '' }}
                                                            </td>
                                                            <td class="text-left">{{ number_format($value->unit_price, 2) }}</td>
                                                            <td class="text-left">{{ number_format($value->total_amount, 2) }}</td>
                                                            @php
                                                                $tax_rat_unit_price =  $value->unit_price/100* $value->sales_tax_rate;
                                                                $tax_amount = $value->total_amount/100*$value->sales_tax_rate;
                                                                $total_with_gst +=$value->total_amount+$tax_amount;
                                                                $total_amount +=$value->total_amount;
                                                            @endphp
                                                            <td  class="text-left">
                                                                {{$value->sales_tax_rate}} %
                                                            </td>
                                                            <td>
                                                                {{number_format($value->unit_price+$tax_rat_unit_price,2)}}
                                                            </td>
                                                            <td class="text-left">{{ number_format( $value->total_amount+$tax_amount, 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <ul class="grand">
                                                    <li>
                                                        <div class="grndctn">
                                                            <h5>Grand Total</h5>
                                                            <p>(without GST)</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="grndctn">
                                                            <h6>{{ number_format($total_amount, 2) }}</h6>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="grndctn">
                                                            <h5>Grand Total</h5>
                                                            <p>(with GST)</p>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="grndctn">
                                                            <h6>{{ number_format($total_with_gst, 2) }}</h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks">
                                    <h2 class="subHeadingLabelClass">TERMS & CONDITIONS</h2>
                                    <h2 class="subHeadingLabelClass">{!! $sale_quotations->terms_condition !!}</h2>
                                </div>
                                <div class="headquid_apr2">
                                    {{-- <h2 class="subHeadingLabelClass">1.	Payment: Within Thirty (30) days from the date of delivery.</h2>
                                    <h2 class="subHeadingLabelClass">2.	DELIVERY DESTINATIONS: Hyderabad to Sukkur.</h2>
                                    <h2 class="subHeadingLabelClass">3.	DELIVERY LENGTH: 100 Meters.</h2>
                                    <h2 class="subHeadingLabelClass">4.	O CLOSURE TIMELINE: The Quantities mutually agreed to close the said project by or before 02 months. Prolonged remaining quantities may be subject to revision of price and delivery terms.</h2>
                                    <h2 class="subHeadingLabelClass">5.	Delivery/Unloading: Delivery at desired location will be manufacturer’s responsibility. However, unloading will be of customer’s responsibility, customer have to make arrangements at their own.</h2> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks">
                                    <h2 class="subHeadingLabelClass">Thanking you,</h2>
                                    <h2 class="subHeadingLabelClass">Yours faithfully,</h2>
                                    <h2 class="subHeadingLabelClass">for Zahabiya Chemicals Industries (Pvt) Limited</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprk">
                                    <h2 class="subHeadingLabelClass">MOIZ MITHANI</h2>
                                    <h2 class="subHeadingLabelClass">Deputy Manager</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
