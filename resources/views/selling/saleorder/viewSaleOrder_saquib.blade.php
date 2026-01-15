
<?php
use App\Helpers\CommonHelper;
$total_amount = 0;
?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="dp_sdw2">
                    <div class="row" id="printReport">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                                
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                     <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?> 
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>
                                    <br/>

                                    <!-- <div class="contra">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            

                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="contr">
                                                    <div class="con_rew2" style="margin: unset;"><h2>CONTRACT REVIEW</h2></div>    
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="contr2">
                                                    <div class="con_rew3" style="text-align: center;">
                                                        <h2>SALE/FM/01 </h2>
                                                        <h2>ISSUE: 1</h2>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->


                                    <!-- <div class="bro_src">
                                        <div class="row" id="printReport">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="secc">
                                                    <hr style="border:1px solid #000">
                                                        <h2>SECTION-A</h2>
                                                    <hr style="border:1px solid #000">
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="row ">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="ode">
                                      
                                            </div>
                                            <div class="sal">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5">
                                                        <div class="ors_pra">
                                                            <p>Sale Order no:</p>
                                                            <p>P.O No. :</p>
                                                            <p>P.O Date</p>
                                                            <p>Customer:</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7">
                                                        <div class="ors_pra">
                                                            <p>{{$sale_order[0]->so_no}}</p>
                                                            <p>{{$sale_order[0]->purchase_order_no }}</p>
                                                            <p>
                                                                {{date("d-M-Y", strtotime($sale_order[0]->purchase_order_date ))}}
                                                            </p>
                                                            <p>{{CommonHelper::byers_name($sale_order[0]->buyers_id)->name}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                     
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="tasetb">
                                                <table class="userlittab3 table table-bordered sf-table-list3">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Item</th>
                                                        <th class="text-center">Pack Type</th>
                                                        <th class="text-center">Color</th>
                                                        <th class="text-center">UOM</th>
                                                        <!-- <th class="wsale2 text-center">Item Description </th> -->
                                                        <!-- <th class="text-center">Unit</th> -->
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Rate</th>
                                                        <th class="text-center">Total Amount</th>
                                                        <!-- <th>GST</th>
                                                        <th>Unit Price <span>with GST</span></th>
                                                        <th>Total Amount <span>with GST</span></th>
                                                        <th class="text-center">Delivery Date</th>
                                                        <th class="text-center">ID Tape Printing</th>
                                                        <th class="text-center">Special request (if any)</th>
                                                        <th class="text-center">Part #</th>
                                                        <th class=" wsale text-center">Printing</th> -->
                                                    </tr>
                                                    </thead>
                                                    <tbody id="data">
                                                        @foreach($sale_order as $sale_order_item)
                                                        @php
                                                         $product = CommonHelper::get_item_by_id($sale_order_item->item_id);   
                                                        @endphp
                                                        <tr>        

                                                            <td class="text-left">{{ $sale_order_item->item_code.' -- '.$sale_order_item->sub_ic }}</td>
                                                            <td class="text-left">{{ $sale_order_item->pack_size.' '.$sale_order_item->uom_name.' '.$sale_order_item->type }}</td>
                                                            <td class="text-left">{{ $sale_order_item->color }}</td>
                                                            <td class="text-center">{{ $sale_order_item->uom_name }}</td>
                                                            <td class="text-right">
                                                                {{number_format($sale_order_item->qty)}}
                                                            </td>
                                                            <td class="text-right">
                                                                {{number_format($sale_order_item->rate,2)}}
                                                            </td>
                                                        
                                                            <td class="text-right">
                                                                {{number_format($sale_order_item->amount,2)}}
                                                            </td>
                                                            
                                                            {{-- 
                                                                <td>
                                                                    {{number_format($sale_order_item->tax)}} %
                                                                </td>
                                                                
                                                                <td>
                                                                    {{number_format($sale_order_item->rate+$tax_rat ,2)}} 
                                                                </td>

                                                                <td class="wsale">
                                                                    {{number_format($sale_order_item->sub_total,2)}}
                                                                </td> 
                                                            

                                                            <td class="text-center">
                                                                {{date("d-M-Y", strtotime($sale_order_item->delivery_date))}}
                                                            </td>
                                                            
                                                            <td class="text-center"></td>
                                                            
                                                            <td class="text-center">
                                                               {{$sale_order_item->special_instruction??'-'}}
                                                            </td>
                                                            
                                                            <td class="text-center"></td> 
                                                            
                                                            <td class="text-center">
                                                                {{$sale_order_item->printing??'-'}}
                                                            </td>--}}
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- <div class="contra">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="contraA">
                                                    <h2>Date: </h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="con_rewB">
                                                    <h2></h2>
                                                </div>    
                                            </div>
                                        </div>
                                    </div> -->


                                    <!-- <div class="bro_src">
                                        <div class="row" id="printReport">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="secc">
                                                    <hr style="border:1px solid #000">
                                                        <h2>SECTION-B</h2>
                                                    <hr style="border:1px solid #000">
                                                </div>
                                                <div class="vomp">
                                                    <h2>To be completed by (1) Managing Director</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->


                                    @php 
                                        $tax = $sale_order[0]->total_amount / 100 * $sale_order[0]->sales_tax_rate;
                                        $further_tax = $sale_order[0]->total_amount / 100 * $sale_order[0]->sales_tax_further;
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="tasetb">
                                                <table class="userlittab3 table table-bordered sf-table-list3">
                                                    <tbody id="data">
                                                        @php
                                                        $advance_tax = $sale_order[0]->total_amount / 100 * $sale_order[0]->advance_tax
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">Total Amount</td>
                                                            <td class="text-center">{{number_format($sale_order[0]->total_amount,2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Sales Tax {{ number_format($sale_order[0]->sales_tax_rate) }} %</td>
                                                            <td class="text-center">{{number_format($tax ,2)}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Further Tax {{number_format($sale_order[0]->sales_tax_further)}} %</td>
                                                            <td class="text-center">{{number_format($further_tax ,2)}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Advance Tax {{number_format($sale_order[0]->advance_tax)}} %</td>
                                                            <td class="text-center">{{number_format($advance_tax ,2)}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Cartage Amount</td>
                                                            <td class="text-center">{{number_format($sale_order[0]->cartage_amount ,2)}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">Amount After Tax</td>
                                                            <td class="text-center">{{number_format($sale_order[0]->total_amount_after_sale_tax,2)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="vomp">
                                        <h2>Report Execution :2/3/2023 6:41:08 PM</h2>
                                    </div>


                                    <div class="vomp">
                                        <h2>Zahabiya Chemicalss</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="tasetb">
                                                <table class="userlittab3 table table-bordered sf-table-list4">
                                                    <tbody id="data">
                                                        <tr class="text-center">
                                                            <td colspan="6" class="secec text-center">SECTION-C</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                                To be completed by the concerned Director Marketing<br>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            
                                                        </tr>

                                                        <tr>
                                                            <td class="text-left">
                                                                1) Decision: On the basis of answer given in Section-B, it is decided to:<br>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            
                                                        </tr>

                                                        <tr>
                                                            <td class="text-left">
                                                                Accept the order
                                                            </td>
                                                            <td>
                                                                Y
                                                            </td>
                                                            <td>
                                                                Reject the order
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>
                                                                Negotiate Amendment
                                                            </td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                                2) Confirmation sent:
                                                            </td>
                                                            <td>
                                                                Yes Y
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>
                                                                No
                                                            </td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                                3) Obtain Customer's agrrement:
                                                            </td>
                                                            <td>
                                                                Yes Y
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>
                                                                No
                                                            </td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                              4) Amendment required after acceptance of an order**:
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>
                                                                Yes Y
                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>
                                                                No N
                                                            </td>
                                                            
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td>**Please fill new contract review form and send it to Managing Director</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>This is a computer generated document and does not require any signature.</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
