<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
?>
@extends('layouts.default')

@section('content')
    @include('loader')
    @include('number_formate')
    @include('select2')


    <style>
        * {
            font-size: 12px!important;

        }
        label {
            text-transform: capitalize;
        }
    </style>


    <div   class="row well_N" style="display: none;" id="main">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Sales Tax Invoice</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/addeSalesTaxInvoice?m='.$m.'','id'=>'createSalesOrder'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php

                                            //$gi_no=$sales_order->so_no;
                                            $so_date=date('Y-m-d');//$sales_order->so_date;
                                            //$gi_no=str_replace("SO","GI",$gi_no);
                                            $gi_no= SalesHelper::get_unique_no_sales_tax_invoice(date('y'),date('m'));
                                            ?>


                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Invoice No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="gi_no" id="gi_no" value="{{strtoupper($gi_no)}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Invoice Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  autofocus type="date" {{--onkeyup="calculate_due_date()"--}} class="form-control requiredField" placeholder="" name="gi_date" id="gi_date" value="{{$so_date}}" />
                                            </div>
                                                <?php
                                                $Date = $so_date;
                                                $DueDue =  date('Y-m-d', strtotime($Date. ' + '.$sales_order->model_terms_of_payment.' days'));
                                                ?>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">SO NO. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="so_no" id="so_no" value="{{$sales_order->so_no}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">SO Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control" placeholder="" name="so_date" id="so_date" value="{{$sales_order->so_date}}" />
                                                <input type="hidden" name="dn_ids" value="{{$ids}}"/>
                                            </div>
                                        </div>

                                        <div class="row hide">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label  class="sf-label">Mode / Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control " placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="{{$sales_order->model_terms_of_payment}}" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Other Reference(s) <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control " placeholder="" name="other_refrence" id="other_refrence" value="{{$sales_order->other_refrence}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="order_no" id="order_no" value="{{$sales_order->order_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="date" class="form-control" placeholder="" name="order_date" id="order_date" value="{{$sales_order->order_date}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document No<span class="rflabelsteric"></span></label>
                                                <input readonly  type="text" class="form-control" placeholder="" name="despacth_document_no" id="despacth_document_no" value="{{$delivery_not->despacth_document_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document Date</label>
                                                <input readonly  type="date" class="form-control" placeholder="" name="despacth_document_date"  id="despacth_document_date" value="{{$delivery_not->despacth_document_date}}" />
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                                <label class="sf-label">Despatched through<span class="rflabelsteric"></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="despacth_through" id="despacth_through" value="{{$sales_order->desptch_through}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                                <label class="sf-label">Destination<span class="rflabelsteric"></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="destination" id="destination" value="{{$sales_order->destination}}" />
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                                <label class="sf-label">Terms Of Delivery<span class="rflabelsteric"></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="{{$sales_order->terms_of_delivery}}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select style="width: 100%" disabled name="" id="ntn" onchange="get_ntn()" class="form-control select2">
                                                    <option>Select</option>
                                                    @foreach(SalesHelper::get_all_customer() as $row)
                                                        <option @if($sales_order->buyers_id==$row->id) selected @endif value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="buyers_id" value="{{$sales_order->buyers_id}}"/>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's Ntn </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_ntn" id="buyers_ntn" value="" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's Sales Tax No </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"></span></label>
                                                <input readonly type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="<?php echo $DueDue?>" />
                                            </div>

                                        </div>

                                        <div class="row">

                                            <?php
                                            $accounts=DB::Connection('mysql2')->table('accounts')->where('status',1)->whereIn('id',array(266, 267))->get();
                                            ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Cr Account<span class="rflabelsteric requiredField"><strong>*</strong></span></label>
                                                <select class="form-control" id="acc_id" name="acc_id" >
                                                    <option value="">Select</option>
                                                    @foreach($accounts as $row)
                                                        <option @if($row->id==16) selected @endif  value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="demand_type" id="demand_type">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric">
                                                <textarea  name="description" id="description" rows="4" cols="50" style="resize:none;text-transform: capitalize" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="lineHeight">&nbsp;</div>
                                <div class="">
                                    <span ondblclick="show()" class="subHeadingLabelClass">Sales Tax Invoice Data</span>
                                    <input type="checkbox" id="amount_data" checked/>
                                </div> -->
                                <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>

                                <div id="addMoreDemandsDetailRows_1" class="panel addMoreDemandsDetailRows_1">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S.NO</th>
                                                    <th class="text-center">DN NO</th>
                                                    <th class="text-center">Item</th>
                                                    <th style="display: none" class="text-center">So Data ID</th>
                                                    {{--<th class="text-center">DN No.</th>--}}
                                                    <!-- <th class="text-center" >Pack Size</th>
                                                    <th class="text-center" >Color</th> -->
                                                    <th class="text-center" >Uom</th>

                                                    <th class="text-center" >Orderd QTY</th>
                                                    <th class="text-center" >DN QTY</th>
                                                    <th class="text-center" >Return QTY</th>
                                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hidee">Rate</th>
                                                    <th class="text-center hidee">Tax %</th>
                                                    <th class="text-center hidee">Tax Amount</th>
                                                    <th class="text-center hidee">Further Tax %</th>
                                                    <th class="text-center hidee">Further Tax Amount</th>
                                                    {{-- <th class="text-center hidee">Discount Amount</th> --}}
                                                    <th class="text-center hidee">Net Amount</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $counter=1;
                                                $id_count=0;
                                                $total=0;
                                                $total_qty=0;

                                                foreach ($sale_order_data as $row1)
                                                {
                                                    
                                                if ($row1->bundles_id==0):

                                                    $dn_data = SalesHelper::dn_qty($row1->so_data_id,$ids);
                                                    $dn_qty = $dn_data->qty;
                                                    $dn_rate = $dn_data->rate;
                                                    $discount_percent = $dn_data->tax ?? 0;
                                                    $return_qty = SalesHelper::return_qty(1,$row1->so_data_id,$row1->gd_no);
                                                    $qty = $dn_qty - $return_qty;
                                                // dump($dn_qty , $return_qty , $row1->qty);
                                                if ($qty > 0):
                                                    
                                                $id_count++;
                                                $orderd_qty=CommonHelper::generic('sales_order_data',array('id'=>$row1->so_data_id),['qty'])->first();


                                                ?>
                                                <input type="hidden" name="master_id[]" id="master_id" value="{{$row1->master_id}}"/>
                                                <input type="hidden" name="dn_data_id{{$id_count}}" id="dn_data_id{{$id_count}}" value="{{$row1->id}}"/>
                                                <input type="hidden" name="so_data_id{{$id_count}}" id="so_data_id{{$id_count}}" value="{{$row1->so_data_id}}"/>
                                                <input type="hidden" name="bundles_id{{$id_count}}" id="bundles_id" value="{{$row1->bundles_id}}"/>
                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$row1->groupby}}"/>
                                                <?php

                                                $sale_order_id=Input::get('sales_order_id');
                                                $delivery_note_id=Input::get('delivery_note_id');
                                                ?>
                                                <input type="hidden" name="sales_order_id" id="sales_order_id" value="{{$row1->so_id}}"/>
                                                <input type="hidden" name="sales_order_data_id" id="sales_order_data_id" value="{{$row1->so_data_id}}"/>
                                                <input type="hidden" name="delivery_note_id" id="delivery_note_id" value="{{$delivery_note_id}}"/>

                                                <input type="hidden" name="item_id{{$id_count}}" id="item_id{{$id_count}}" value="{{$row1->item_id}}"/>
                                                <input type="hidden" name="warehouse_id{{$id_count}}" id="warehouse_id{{$id_count}}" value="{{$row1->warehouse_id }}"/>
                                                <input type="hidden" name="item_desc<?php echo $id_count?>" id="item_desc" value='<?php echo $row1->desc?>'/>

                                                <tr>
                                                    <td class="text-center" class="text-center"><?php echo $counter ?></td>
                                                    <td class="text-center" class="text-center">{{ $row1->gd_no }}
                                                        <input type="hidden" name="gd_no" value="{{ $row1->gd_no }}">
                                                    </td>
                                                    <td class="text-left"><?php  echo CommonHelper::get_item_name($row1->item_id);?></td>
                                                    <td style="display: none;">{{$row1->so_data_id.' '.$row1->groupby}}</td>

                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                    ?>
                                                    <!-- <td>{{ $row1->pack_size.' '.$row1->type }}</td>
                                                    <td>{{ $row1->color }}</td> -->
                                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                                    <td class="text-center">{{$orderd_qty->qty}}</td>
                                                    <td class="text-center">{{$dn_qty}}</td>
                                                    <td class="text-center">{{$return_qty}}</td>

                                                    <?php  $total_qty+=$dn_qty-$return_qty;

                                                    $amount = $dn_rate * $dn_qty;
                                                    $discount_amount = 0;
                                                    $tax_amount = 0;
                                                    $further_tax_amount = 0;
                                                    
                                                    if ($sales_order->sales_tax_rate != 0):
                                                        $tax_amount = ($amount / 100) * $sales_order->sales_tax_rate;
                                                    endif;
                                                    if ($delivery_not->sales_tax_further != 0):
                                                        $further_tax_amount = $delivery_not->sales_tax_further;
                                                    endif;
                                                    $net_amount = $amount;


                                                    ?>
                                                    <td class="text-right">
                                                        <input readonly type="text" class="form-control qty" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{$row1->qty-$return_qty}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="rate{{$id_count}}" id="rate{{$id_count}}" value="{{$row1->rate}}"/>
                                                    </td>
                                                    <td class="text-right hidee" style="width: 10%">
                                                        <input readonly type="text" class="form-control" name="tax_percent{{$id_count}}" id="tax_percent{{$id_count}}" value="{{ $sales_order->sales_tax_rate }}"/>

                                                    </td>
                                                    <td class="text-right hidee" style="width: 10%">
                                                        <input readonly type="text" class="form-control" name="tax_amount{{$id_count}}" id="tax_amount{{$id_count}}" value="{{$tax_amount}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="sales_tax_further_per{{$id_count}}" id="sales_tax_further_per{{$id_count}}" value="{{ $delivery_not->sales_tax_further_per }}"/>

                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="sales_tax_further{{$id_count}}" id="sales_tax_further{{$id_count}}" value="{{$further_tax_amount}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control amount comma_seprated" name="net_amount{{$id_count}}" id="net_amount{{$id_count}}" value="{{ $net_amount }}"/>
                                                    </td>
                                                </tr>

                                                <?php endif;
                                                $counter++;
                                                else:  ?>



                                                <?php

                                                $product_data=DB::Connection('mysql2')->table('delivery_note_data')->where('bundles_id',$row1->bundles_id)->select('*')
                                                ->groupby('so_data_id')->get();



                                                $item_count=$counter+0.1;
                                                $bundle_stop=1;
                                                $working_counter=1;

                                                foreach ($product_data as $bundle_data):



                                                $qty=SalesHelper::get_dn_total_qty($bundle_data->so_data_id);


                                                 $qty=$bundle_data->qty-$qty;


                                               // if ($qty>0):
                                                $working_counter++;

                                                ?>

                                                @if ($bundle_stop==1)
                                                    <tr  style="font-size: larger;font-weight: bold;background-color: lightyellow">
                                                        <td class="text-center" class="text-center"><?php echo $counter;?></td>
                                                        <td  id="" class="text-left"><?php echo $row1->product_name;?></td>
                                                        <td class="text-left"> <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?> </td>
                                                        <td class="text-right"> <?php echo number_format($row1->bqty,3)?></td>
                                                        <td></td>
                                                        <td class="text-right hidee"><?php echo number_format($row1->bundle_rate,2);?></td>
                                                        <td class="text-right hidee"><?php echo number_format($row1->bundle_amount,2);?></td>
                                                        <td class="text-right hidee"><?php echo number_format($row1->b_percent,2);?></td>
                                                        <td class="text-right hidee"><?php echo number_format($row1->b_dis_amount,2);?></td>
                                                        <td class="text-right hidee"><?php echo number_format($row1->b_net,2);?></td>

                                                    </tr>
                                                    <?php $bundle_stop++ ?>
                                                @endif

                                                <?php // endif;


                                                $dn_data=SalesHelper::dn_qty($bundle_data->so_data_id,$ids);

                                                $dn_qty= $dn_data->qty;
                                                $dn_rate=   $dn_data->rate;
                                                $discount_percent = $dn_data->discount_percent;
                                                $return_qty=SalesHelper::return_qty(1,$row1->so_data_id,$row1->gd_no);
                                                $qty=$dn_qty-$return_qty;
                                                if ($qty>0):
                                                $id_count++;
                                                $orderd_qty=CommonHelper::generic('sales_order_data',array('id'=>$bundle_data->so_data_id),['qty'])->first();


                                                ?>
                                                {{--hidden data--}}
                                                <input type="hidden" name="master_id[]" id="master_id" value="{{$row1->master_id}}"/>
                                                <input type="hidden" name="dn_data_id{{$id_count}}" id="dn_data_id{{$id_count}}" value="{{$bundle_data->id}}"/>
                                                <input type="hidden" name="so_data_id{{$id_count}}" id="so_data_id{{$id_count}}" value="{{$bundle_data->so_data_id}}"/>
                                                <input type="hidden" name="bundles_id{{$id_count}}" id="bundles_id" value="{{$bundle_data->bundles_id}}"/>

                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$bundle_data->groupby}}"/>
                                                <?php

                                                $sale_order_id=Input::get('sales_order_id');
                                                $delivery_note_id=Input::get('delivery_note_id');
                                                ?>
                                                <input type="hidden" name="sales_order_id" id="sales_order_id" value="{{$row1->so_id}}"/>
                                                <input type="hidden" name="sales_order_data_id" id="sales_order_data_id" value="{{$row1->so_data_id}}"/>
                                                <input type="hidden" name="delivery_note_id" id="delivery_note_id" value="{{$delivery_note_id}}"/>

                                                <input type="hidden" name="item_id{{$id_count}}" id="item_id{{$id_count}}" value="{{$row1->item_id}}"/>
                                                <input type="hidden" name="warehouse_id{{$id_count}}" id="warehouse_id{{$id_count}}" value="{{$row1->warehouse_id }}"/>
                                                <input type="hidden" name="item_desc<?php echo $id_count?>" id="item_desc" value="<?php echo $row1->desc?>"/>

                                                {{--hidden data End --}}


                                                <tr>
                                                    <td class="text-center" class="text-center"><?php echo $item_count;?></td>
                                                    <td class="text-left"><?php echo $bundle_data->desc;//CommonHelper::get_item_name($bundle_data->item_id)?></td>
                                                    <td style="display: none">{{$bundle_data->so_data_id.' '.$bundle_data->groupby}}</td>

                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($bundle_data->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                    ?>
                                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                                    <td class="text-center">{{$orderd_qty->qty}}</td>
                                                    <td class="text-center">{{$dn_qty}}</td>
                                                    <td class="text-center">{{$return_qty}}</td>

                                                    <?php

                                                    $total_qty+=$dn_qty-$return_qty;



                                                    $amount=$dn_rate*$dn_qty;
                                                    $discount_amount=0;
                                                    $net_amount=$amount=0;
                                                    if ($row1->discount_percent!=0):
                                                    $discount_amount=($amount/100)*$discount_percent;
                                                    $net_amount=$amount-$discount_amount;
                                                    endif;


                                                    ?>
                                                    <td class="text-right">
                                                        <input readonly type="text" class="form-control qty" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{$dn_qty-$return_qty}}"/></td>


                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="rate{{$id_count}}" id="rate{{$id_count}}" value="{{$dn_rate}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="discount_percent{{$id_count}}" id="discount_percent{{$id_count}}" value="{{$discount_percent}}"/>

                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control" name="discount_amount{{$id_count}}" id="discount_amount{{$id_count}}" value="{{$discount_amount}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly type="text" class="form-control amount comma_seprated" name="net_amount{{$id_count}}" id="net_amount{{$id_count}}" value="{{$net_amount}}"/>


                                                </tr>
                                                <?php endif;

                                                $item_count+=0.1;    endforeach; ;$bundle_stop=1;


                                                $counter++;
                                                endif;
                                                }
                                                ?>
                                                <input type="hidden" name="count" id="count" value="{{$id_count}}"/>
                                                <tr>

                                                    <td id="total_" style="background-color: darkgray" class="text-center" colspan="7">Total</td>
                                                    <td style="font-weight: bolder" colspan="1"> <input readonly type="text" id="total_qty" class="form-control"  value=""/></td>
                                                    <td colspan="4"></td>
                                                    <td class="text-right" style="font-weight: bolder" colspan="2"> <input readonly type="text" id="total_amount" class="form-control text-right comma_seprated"  value=""/></td>

                                                </tr>


                                                </tbody>
                                                @if($sales_order->sales_tax >0)
                                                    <?php // $total+=$sales_order->sales_tax;

                                                    $sales_order->sales_tax;
                                                    $sales_tax; $sales_tax=($total / 100)*17;

                                                    ?>
                                                    <input type="hidden" name="sales_tax_value" value="{{$sales_tax}}" />
                                                    <tr class="hidee" style="display:none;">
                                                        <td class="text-center" colspan="6"></td>
                                                        <td class="text-right" colspan="6"><b>(Sales Tax 17%)</b>

                                                            <input readonly type="text" class="text-right comma_seprated" name="sales_tax" id="sales_tax"/>
                                                        </td>
                                                    </tr>
                                                @endif


                                                @if($delivery_not->advance_tax_amount > 0)
                                                    <tr>
                                                        <td  class="text-right" colspan="10"></td>
                                                        <td class="text-right" colspan="2">Advance Tax {{ number_format($delivery_not->advance_tax_rate,2) }}</td>
                                                        <td class="text-right" colspan="2">
                                                            
                                                        <input type="hidden" name="advance_tax_rate" id="advance_tax_rate" value="{{ $delivery_not->advance_tax_rate }}" />

                                                        <input style="font-weight: bolder;" class="form-control text-right comma_seprated" readonly type="text"   name="advance_tax_amount" id="advance_tax_amount" value="{{ $delivery_not->advance_tax_amount }}" />
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($delivery_not->cartage_amount >0)
                                                    <tr>
                                                        <td  class="text-right" colspan="10"></td>
                                                        <td class="text-right" colspan="2">Cartage Amount</td>
                                                        
                                                        <td colspan="2"> <input class="form-control text-right" type="text" name="cartage_amount" id="cartage_amount" value="{{ $delivery_not->cartage_amount }}" readonly />
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr class="hidee">

                                                    <td  style="background-color: darkgray;font-weight: bolder;font-size: x-large" class="text-center" colspan="12">Grand Total</td>
                                                    <td colspan="2" style="background-color: darkgray;font-weight: bolder;font-size: x-large"> <input disabled type="text" class="form-control text-right comma_seprated" name="" id="grand_total"/></td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                                <table>
                                    <tr>

                                        <td style="text-transform: capitalize;" id="rupees"></td>
                                        <input type="hidden" value="" name="rupeess" id="rupeess1"/>
                                    </tr>
                                </table>
                                <input type="hidden" id="d_t_amount_1" >
                                <!--
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                                                <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">

                                            </div>
                                            <!-->
                                <div class="row" style="display: none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Addional Expenses</span>
                                    </div>

                                    <?php $accountss=DB::Connection('mysql2')->table('accounts')->where('status',1)->get(); ?>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                <th class="text-center">Account Head</th>
                                                <th class="text-center">Expense Amount</th>
                                                <th class="text-center">
                                                    <button type="button" class="btn btn-xs btn-primary" id="BtnAddMoreExpense" onclick="AddMoreExpense()">More Expense</button>
                                                </th>
                                                </thead>
                                                <tbody id="AppendExpense">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                <div class="demandsSection"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        <button type="submit" id="BtnSaveAndPrint" class="btn btn-info" >Save & Print</button>
                                <!--
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                        <!-->
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function(){
            //calculate_due_date();
        });



        function padNumber(number) {
            var string  = '' + number;
            string      = string.length < 2 ? '0' + string : string;
            return string;
        }



        function calculate_due_date()
        {



            var days=parseFloat($('#model_terms_of_payment').val());
            var tt = document.getElementById('gi_date').value;


            var date      = new Date(tt);
            next_date = new Date(date.setDate(date.getDate() + days));
            formatted = next_date.getUTCFullYear() + '-' + padNumber(next_date.getUTCMonth() + 1) + '-' + padNumber(next_date.getUTCDate())


//            var date = new Date(tt);
//            var newdate = new Date(date);
//            newdate.setDate(newdate.getDate() + days);
//            var dd = newdate.getDate();
//
//            var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
//            var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
//            var y = newdate.getFullYear();
//            var someFormattedDate =  + y+'-'+ mm +'-'+dd;
//
            document.getElementById('due_date').value = formatted;
        }



        $(".btn-info").click(function(e)
        {
            $('#SavePrintVal').val('1');
            var demands = new Array();
            var val;
            demands.push($(this).val());
            var _token = $("input[name='_token']").val();

            for (val of demands)
            {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    alert(validate);
                    return false;
                }
            }

        });

        $(document).ready(function() {

            $('.comma_seprated').number(true,3);
            var cal_count=$('#count').val();

            for (i=1; i<=cal_count; i++)
            {

                calc(i);
            }

            get_ntn();
            $('#acc_id').select2();
            //	$('.hidee').fadeOut();


            var d = 1;





            $(".btn-success").click(function(e)
            {
                $('#SavePrintVal').val('0');
//                var CrAccId = $('#acc_id').val();
//                if(CrAccId == "")
//                {
//                    alert('Required Cr Account.');
//                    return false;
//                }
                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands)
                {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        alert(validate);
                        return false;
                    }
                }

            });
        });
        var x = 1;
        function addMoreDemandsDetailRows(id){
            x++;

            //alert(id+' ---- '+x);
            var m = '<?php echo $_GET['m'];?>';

            $.ajax({
                url: '<?php echo url('/')?>/sdc/addSalesOrder',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {

                    $('.addMoreDemandsDetailRows_'+id+'').append(data);
                    $('#item_id_'+x).select2();
                    $('#batch_id_'+x).select2();
                    $('#item_id_'+x).focus();

                    $('#qty_'+x).number(true,3);
                    $('#per_pcs_item_'+x).number(true,2);
                    $('#rate_'+x).number(true,2);
                    $('#discount_percent_'+x).number(true,2);
                    $('#discount_amount_'+x).number(true,2);
                    $('#amount_'+x).number(true,2);
                    $('#per_pcs_item_'+x).number(true,2);
                    $('#discount_percent_'+x).number(true,2);

                    $('#count').val(x);

                }
            });
        }

        function show()
        {


        }

        $('#amount_data').change(function()
        {

            if($(this).is(':checked'))
            {
                $('.hidee').fadeOut();
                $('#total_').attr('colspan',4);
                $('.resize').attr("cols","50");
            }
            else
            {
                $('.hidee').fadeIn(1000);
                $('.resize').attr("rows","5");
                $('.resize').attr("cols","20");
                $('#total_').attr('colspan',6);
            }

        });

        function amount_calc(id,number)
        {
            var qty=parseFloat($('#qty_'+number).val());
            var rate=parseFloat($('#rate_'+number).val());
            var pack_size=parseFloat($('#pack_size_'+number).val());


            // for amount
            var total=qty * rate;
            $('#amount_'+number).val(total);



            // for per pcs qty
            var pack_size=qty * pack_size;
            $('#per_pcs_item_'+number).val(pack_size);



            // for discount percentage

            if (id=='discount_percent_'+number)
            {


                var discount=parseFloat($('#discount_percent_'+number).val());
                if (discount<=100 && discount >0)
                {
                    var discount_amount = (total / 100) * discount;
                    $('#discount_amount_' + number).val(discount_amount);
                    var amount_total=total-discount_amount;
                    $('#amount_'+number).val(amount_total);
                }
                else
                {
                    $('#discount_percent_'+number).val(0);
                    $('#discount_amount_'+number).val(0);
                }

                // end discount percent
            }
            else
            {
                if (id=='discount_amount_'+number)
                {
                    // for discount amount
                    var discount_amount =parseFloat($('#discount_amount_'+number).val());
                    if (discount_amount>total)
                    {
                        discount_amount=0;
                        $('#discount_amount_'+number).val(0)
                    }

                    var discount_percentage=(discount_amount / total)*100;
                    $('#discount_percent_'+number).val(discount_percentage);
                    var amount_total=total-discount_amount;
                    $('#amount_'+number).val(amount_total);

                }
            }

            net_amount_func();
            sales_tax();

        }


        function net_amount_func(sales_tax_count)
        {


            var net_amount=0;
            $('.amount').each(function (i, obj) {
                var id=(obj.id);

                net_amount += +$('#'+id).val();


            });


            $('#total').val(net_amount);
        }

        function sales_tax()
        {
            var total=	parseFloat($('#total_amount').val());
            var sales_tax=(total/100)*17;
            $('#sales_tax').val(sales_tax);


            var strn= $('#buyers_sales').val();
            if (strn=='')
            {

                var sales_tax_further=(total/100)*3;
                $('#sales_tax_further').val(sales_tax_further);

            }
            else
            {
                sales_tax_further=0;
                $('#sales_tax_further').val(0);
            }

            var total=sales_tax+sales_tax_further;
            $('#sales_total').val(total);

            var total_amount=	parseFloat($('#total').val());
            var total_after_sales_tax=total_amount+total;
            $('#total_after_sales_tax').val(total_after_sales_tax);

            $('#d_t_amount_1').val(total_after_sales_tax);
            toWords(1);
        }


    </script>


    <script>
        var CounterExpense = 1;
        function AddMoreExpense()
        {
            CounterExpense++;
            $('#AppendExpense').append("<tr id='RemoveExpenseRow"+CounterExpense+"'>" +
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+CounterExpense+"'><option value=''>Select Account</option><?php foreach($accountss as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "</td>" +
                    "<td>" +
                    "<input type='number' name='expense_amount[]' id='expense_amount"+CounterExpense+"' class='form-control requiredField'>" +
                    "</td>" +
                    "<td class='text-center'>" +
                    "<button type='button' id='BtnRemoveExpense"+CounterExpense+"' class='btn btn-sm btn-danger' onclick='RemoveExpense("+CounterExpense+")'> X </button>" +
                    "</td>" +
                    "</tr>");
            $('#account_id'+CounterExpense).select2();
        }

        function RemoveExpense(Row)
        {
            $('#RemoveExpenseRow'+Row).remove();
        }

        function get_batch_detail(id,number) {


            $("#batch_id_"+number).empty().trigger('change')


            //	var number=id.replace("sub_item_id_", "");
            //	number=number.split('_');
            //	number=number[1];


            id=$('#'+id).val();
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/sdc/get_batch_details',
                type: "GET",
                data: { id:id},
                success:function(data)
                {

                    data=data.split('*');
                    $('#batch_id_'+number).html(data[0]);
                    $('#pack_size_'+number).val(data[1]);
                    $('#description_'+number).val(data[2]);
                    $('#uom_'+number).val(data[2]);

                }
            });
        }
    </script>

    <script>
        function removeDemandsRows(){

            var id=1;

            if (x > 1)
            {
                $('#removeDemandsRows_'+id+'_'+x+'').remove();
                x--;
                $('#count').val(x);
            }
        }

        function calc(num)
        {
            var grand_total = 0;
            var send_qty=parseFloat($('#qty'+num).val());
            var rate=parseFloat($('#rate'+num).val());
            var total=send_qty*rate;

            var x = parseFloat($('#discount_percent'+num).val());
            var tax = parseFloat($('#tax_amount'+num).val());
            var sales_tax_further = parseFloat($('#sales_tax_further'+num).val());

            if (isNaN(x)) {
                x=0;
            }

            if(isNaN(tax)) {
                tax = 0;
            }

            if (x > 0) {
                x=x*total;
                var discount_amount = parseFloat(x / 100);

                $('#discount_amount'+num).val(discount_amount.toFixed(2));
                total = total + discount_amount;
            }
            grand_total += total + tax + sales_tax_further

            $('#net_amount'+num).val(total + tax + sales_tax_further);

            var cartage = parseFloat($('#cartage_amount').val());
            var advance_tax = parseFloat($('#advance_tax_amount').val());

            $('#grand_total').val(grand_total + cartage + advance_tax);
            $('#d_t_amount_1').val(grand_total + cartage + advance_tax);

            net();
            toWords(1);
        }

        function net()
        {

            var amount=0;
            $('.amount').each(function (i, obj) {

                amount += +parseFloat($('#'+obj.id).val());

            });
            amount=parseFloat(amount);
            $('#total_amount').val(amount);

            var qty=0;
            $('.qty').each(function (i, obj) {

                qty += +parseFloat($('#'+obj.id).val());

            });
            qty=parseFloat(qty);
            $('#total_qty').val(qty);
        }

        // sales_tax
        function sales_tax()
        {
            return false;
            var total=	parseFloat($('#total_amount').val());
            var sales_tax=(total/100)*17;
            $('#sales_tax').val(sales_tax);

            var check = $('#sales_tax_further').val();

            if (typeof check=='undefined')
            {
                sales_tax_further=0;
            }

            else
            {
                var sales_tax_further=(total/100)*3;
                $('#sales_tax_further').val(sales_tax_further);
            }

            //  var total=sales_tax+sales_tax_further;
            $('#sales_total').val(total);

            var total_amount = parseFloat($('#total_amount').val());
            var total_val = sales_tax+total+sales_tax_further;
            $('#grand_total').val(total_val);

            $('#d_t_amount_1').val(total_val);



            toWords(1);
        }
    </script>

    <script>
        function get_ntn()
        {
            var ntn=$('#ntn').val();
            ntn=ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            sales_tax();
        }
    </script>
    <script type="text/javascript">

        $('.select2').select2()


    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
