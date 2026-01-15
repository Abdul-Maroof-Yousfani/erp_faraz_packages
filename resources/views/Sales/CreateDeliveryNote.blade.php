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
use App\Helpers\ReuseableCode;
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
    <?php $so_no= SalesHelper::get_unique_no(date('y'),date('m')); ?>

    <div class="row well_N"  style="display: none;" id="main">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Delivery Note</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/addeDeliveryNote?m='.$m.'','id'=>'createSalesOrder','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php
                                            //$gd_no=$sales_order->so_no;
                                            $gd_date=$sales_order->so_date;
                                            //$gd_no=str_replace("SO","gd",$gd_no);
                                            $gd_no= SalesHelper::get_unique_no_delivery_note(date('y'),date('m'));
                                            ?>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Delivery Note No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="gd_no" id="gd_no" value="{{strtoupper($gd_no)}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Delivery Note Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  autofocus type="date" class="form-control requiredField" placeholder="" name="gd_date" id="gd_date" value="{{date('Y-m-d')}}" />
                                            </div>

                                            <!-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Mode Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="{{$sales_order->model_terms_of_payment}}" />
                                            </div> -->

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">So No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control requiredField" placeholder="" name="so_no" id="so_no" value="{{$sales_order->so_no}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">SO Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control requiredField" placeholder="" name="so_date" id="so_date" value="{{$sales_order->so_date}}" />
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order No<span class="rflabelsteric"></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="order_no" id="order_no" value="{{$sales_order->order_no ?? '-'}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="date" class="form-control requiredField" placeholder="" name="order_date" id="order_date" value="{{$sales_order->purchase_order_date}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document No</label>
                                                <input  type="text" class="form-control" placeholder="" name="despacth_document_no" id="despacth_document_no" value="" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched Document Date</label>
                                                <input  type="date" class="form-control" placeholder="" name="despacth_document_date" id="despacth_document_date" value="" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Despatched through<span class="rflabelsteric"></span></label>
                                                <input  type="text" class="form-control" placeholder="" name="despacth_through" id="despacth_through" value="{{$sales_order->desptch_through}}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Destination<span class="rflabelsteric"></span></label>
                                                <input  type="text" class="form-control" placeholder="" name="destination" id="destination" value="{{$sales_order->destination}}" />
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
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
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Sales Tax No </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"></span></label>
                                                <input readonly type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="{{$sales_order->delivery_date}}" />
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
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span ondblclick="show()" class="subHeadingLabelClass">Delivery Note  Data</span>
                                    <input checked type="checkbox" id="amount_data"/>

                                </div>
                                <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>


                                <div id="addMoreDemandsDetailRows_1" class="panel addMoreDemandsDetailRows_1">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S.NO</th>
                                                    <th class="text-center">Item</th>
                                                    <th class="text-center">Pack Type</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center">QTY  <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">WareHouse <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <!-- <th style="width: 150px" class="text-center" >Batch Code <span class="rflabelsteric"><strong>*</strong></span></th> -->
                                                    <!-- <th class="text-center">In Stock<span class="rflabelsteric"><strong>*</strong></span></th> -->
                                                    <th class="text-center hidee">Rate</th>
                                                    <!-- <th class="text-center hidee">Discount %</th>
                                                    <th class="text-center hidee">Discount Amount</th> -->
                                                    <th class="text-center hidee">Net Amount</th>
                                                    <th class="text-center hidee">0 QTY</th>

                                                </tr>
                                                </thead>
                                                <input type="hidden" name="master_id" id="master_id" value="{{$sales_order->id}}"/>
                                                <tbody>
                                                <?php
                                                $counter=1;
                                                $id_count=0;
                                                $working_counter=0;
                                                $total=0;
                                                $total_qty=0;
                                               
                                                foreach ($sale_order_data as $row1)
                                                {

                                                    if ($row1->bundles_id==0):
                                                        $qty = SalesHelper::get_dn_total_qty($row1->id);
                                                        $qty = $row1->qty-$qty;
                                                        if ($qty > 0):
                                                            $working_counter++;
                                                            $id_count++;
                                                ?>
                                                {{--hidden data--}}
                                                <input type="hidden" name="data_id{{$id_count}}" id="data_id" value="{{$row1->id}}" />
                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$row1->groupby}}" />
                                                <input type="hidden" name="item_id{{$id_count}}" id="item_id{{$id_count}}" value="{{$row1->item_id}}" />
                                                <!-- <input type="hidden" name="rate{{$id_count}}" id="rate{{$id_count}}" value="{{$row1->rate}}" />
                                                <input type="hidden" name="discount_percent{{$id_count}}" id="discount_percent{{$id_count}}" value="" />
                                                <input type="hidden" name="discount_amount{{$id_count}}" id="discount_amount{{$id_count}}" value="" />
                                                <input type="hidden" name="amount{{$id_count}}" id="amount{{$id_count}}" value="{{$row1->amount}}" /> -->
                                                {{--hidden data End --}}

                                                <t title="">
                                                    <td class="text-center" class="text-center"><?php echo $counter;?></td>
                                                    <td id="{{$row1->item_id}}" class="text-left">
                                                        <textarea readonly class="form-control" name="desc{{$id_count}}" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;">{{ $row1->item_code .' -- '. $row1->sub_ic }}</textarea>
                                                    </td>
                                                    <td>{{ $row1->pack_size.' '.$row1->uom_name.' '.$row1->type }}</td>
                                                    <td>{{ $row1->color }}</td>

                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                    ?>
                                                    <!-- <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td> -->

                                                    <?php $total_qty+=$row1->qty; ?>
                                                    <td class="text-right">
                                                        <input onkeyup="calc('{{$id_count}}')" onblur="calc('{{$id_count}}')" class="form-control qty {{'item'.$row1->item_id}}" type="text" name="send_qty{{$id_count}}" id="send_qty{{$id_count}}" value="{{$qty}}"/>
                                                        <input type="hidden" class="" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{$qty}}"/>
                                                        </td>
                                                        <?php $type =  CommonHelper::get_item_type($row1->item_id); ?>
                                                    <td>

                                                        <select onchange="get_stock(this.id,'{{$id_count}}')" class="form-control select2 requiredField ClsAll ShowOn<?php echo $counter?>" name="warehouse{{$id_count}}" id="warehouse{{$id_count}}">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach

                                                        </select>
                                                    </td>


                                                    <!-- <td>
                                                        <select onchange="get_stock_qty(this.id,'{{$id_count}}')" class="form-control requiredField" name="batch_code{{$id_count}}" id="batch_code{{$id_count}}">
                                                            <option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>

                                                        </select>
                                                    </td> -->
                                                    <!-- <td>

                                                        <input readonly class="form-control instock {{$row1->item_id}}"  type="text" value="" id="instock{{$id_count}}"/>

                                                    </td> -->

                                                        <td class="text-right hidee">
                                                        <input readonly class="form-control" type="text" name="send_rate{{$id_count}}" id="send_rate{{$id_count}}" value="{{$row1->rate}}"/>
                                                    </td>
                                                    <!-- <td class="text-right hidee">
                                                        <input class="form-control" type="text" name="send_discount{{$id_count}}" id="send_discount{{$id_count}}" value=""/>
                                                    </td>

                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control" type="text" name="send_discount_amount{{$id_count}}" id="send_discount_amount{{$id_count}}" value=""/>
                                                    </td> -->
                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control amount " type="text" name="send_amount{{$id_count}}" id="send_amount{{$id_count}}" value="{{$row1->amount}}"/>
                                                    </td>

                                                    <td><input type="checkbox" class="" id="check{{$id_count}}" onclick="required_none('{{$id_count}}','{{$qty}}')" ></td>
                                                        <input type="hidden" name="bundles_id{{$working_counter}}" value="0"/>
                                                    
                                                    
                                                </tr>
                                                <!-- Batch Code Rows Placeholder -->
                                                <tr id="append_batch_code{{$id_count}}"></tr>
                                                        <?php endif;
                                                    
                                                    else:  ?>



                                                        <?php $product_data=DB::Connection('mysql2')->table('sales_order_data')->where('bundles_id',$row1->bundles_id)->select('*')->get();



                                                        $item_count=$counter+0.1;
                                                        $bundle_stop=1;
                                                        foreach ($product_data as $bundle_data):


                                                         $qty=SalesHelper::get_dn_total_qty($bundle_data->id);

                                                        $qty=$bundle_data->qty-$qty;

                                                            if ($qty>0):
                                                                $working_counter++;
                                                                $id_count++;
                                                            ?>
                                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$bundle_data->groupby}}"/>
                                                                @if ($bundle_stop==1)
                                                                <tr   style="font-size: larger;font-weight: bold;background-color: lightyellow">
                                                                    <td class="text-center" class="text-center"><?php echo $counter;?></td>
                                                                    <td  id="" class="text-left"><?php echo $row1->product_name;?></td>
                                                                    <td class="text-left"> <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?> </td>
                                                                    <td class="text-right"> <?php echo number_format($row1->bqty,3)?></td>
                                                                    <td></td>
                                                                    <td class="text-right hidee"><?php echo number_format($row1->bundle_rate,2);?></td>
                                                                    <td class="text-right hidee"><?php echo number_format($row1->amount,2);?></td>
                                                                    <td class="text-right hidee"><?php echo number_format($row1->b_percent,2);?></td>
                                                                    <td class="text-right hidee"><?php echo number_format($row1->b_dis_amount,2);?></td>
                                                                    <td class="text-right hidee"><?php echo number_format($row1->b_net,2);?></td>

                                                                </tr>
                                                                <?php $bundle_stop++ ?>
                                                                @endif

                                                            <input type="hidden" name="data_id{{$id_count}}" id="data_id" value="{{$bundle_data->id}}"/>
                                                            <input type="hidden" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{$qty}}"/>
                                                                <input type="hidden" name="bundles_id{{$id_count}}" value="{{$bundle_data->bundles_id}}"/>
                                                            <input type="hidden" name="item_id{{$id_count}}" id="item_id{{$id_count}}" value="{{$bundle_data->item_id}}"/>

                                                            <tr title="{{CommonHelper::get_item_name($bundle_data->item_id)}}" style="background-color: lightyellow">
                                                                <td class="text-center" class="text-center"><?php echo $item_count;?></td>
                                                                <td id="{{$bundle_data->item_id}}" class="text-left">
                                                                    <?php echo $bundle_data->desc;?>
                                                                        <textarea readonly name="desc{{$id_count}}" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"> {{$bundle_data->desc}}</textarea>
                                                                </td>



                                                                <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                                $sub_ic_detail= explode(',',$sub_ic_detail)
                                                                ?>
                                                                <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                                                <td class="text-right">   <input onkeyup="calc('{{$id_count}}')" onblur="calc('{{$id_count}}')" class="form-control qty {{'item'.$bundle_data->item_id}}" type="text" name="send_qty{{$id_count}}" id="send_qty{{$id_count}}" value="{{$qty}}"/>
                                                                    <input type="hidden" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{$qty}}"/></td>
                                                                <td><select onchange="get_stock(this.id,'{{$id_count}}');ApplyAll('<?php echo $id_count?>')" class="form-control requiredField ClsAll ShowOn<?php echo $counter?>" name="warehouse{{$working_counter}}" id="warehouse{{$working_counter}}">
                                                                        <option value="">Select</option>
                                                                        @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                                                   <?php ?>     @endforeach
                                                                    </select></td>


                                                                <td><select onchange="get_stock_qty(this.id,'{{$id_count}}')" class="form-control requiredField" name="batch_code{{$id_count}}" id="batch_code{{$id_count}}">
                                                                        <option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
f
                                                                    </select></td>
                                                                <td><input readonly class="form-control instock {{$bundle_data->item_id}}"  type="text" value="" id="instock{{$id_count}}"/> </td>


                                                                <td class="text-right hidee">
                                                                    <input readonly class="form-control" type="text" name="send_rate{{$id_count}}" id="send_rate{{$id_count}}" value="{{$bundle_data->rate}}"/>

                                                                </td>
                                                                <?php if ($bundle_data->discount=='') ?>

                                                                <td class="text-right hidee">
                                                                    <input readonly class="form-control" type="text" name="send_discount{{$id_count}}" id="send_discount{{$id_count}}" value="{{$bundle_data->discount}}"/>

                                                                </td>


                                                                <td class="text-right hidee">

                                                                    <input readonly class="form-control" type="text" name="send_discount_amount{{$id_count}}" id="send_discount_amount{{$id_count}}"

                                                                           value="{{$bundle_data->discount_amount}}"/>
                                                                </td>
                                                                <td class="text-right hidee">
                                                                    <input readonly class="form-control amount comma_seprated" type="text" name="send_amount{{$id_count}}" id="send_amount{{$id_count}}" value="{{$bundle_data->amount}}"/>
                                                                </td>
                                                                <td><input type="checkbox" class="" id="check{{$id_count}}" onclick="required_none('{{$id_count}}','{{$qty}}')" ></td>
                                                            </tr>
                                                            <?php endif;   $item_count+=0.1; endforeach ;$bundle_stop=1; ?>




                                                <?php endif;




                                                $total+=$row1->amount;
                                                $counter++;


                                                }
                                                ?>
                                                </tbody>

                                                <input type="hidden" id="count" name="count" value="{{$id_count}}"/>
                                                <tr>

                                                    <td id="total_" style="background-color: darkgray" class="text-center" colspan="3">Total</td>
                                                    <td  style="background-color: darkgray" class="text-right"  colspan="1"><input style="font-weight: bolder" class="form-control" readonly type="text" id="total_qty" value="{{$total_qty,3}}" /> </td>
                                                    <td   style="background-color: darkgray" class="text-right hidee nett"  colspan="4"><input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text" id="total_amount" value="{{$total}}" /></td>
                                                    <td style="background-color: darkgray" class="text-right" colspan="1"></td>


                                                </tr>

                                                @php 
                                                    $tax = $sales_order->total_amount / 100 * $sales_order->sales_tax_rate;
                                                    $further_tax = $sales_order->total_amount / 100 * $sales_order->sales_tax_further;

                                                    $advance_tax = $sales_order->total_amount / 100 * $sales_order->advance_tax;
                                                @endphp

                                                @if($sales_order->sales_tax_rate > 0)
                                                    <?php  $total += $sales_order->sales_tax_rate; ?>
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Sales Tax {{ number_format($sales_order->sales_tax_rate,2) }}</td>
                                                        <td colspan="1"> <input style="font-weight: bolder" class="form-control text-right" readonly type="text" name="sales_tax" id="sales_tax" value="{{ $tax }}" /></td>
                                                        <td colspan="1"> <input type="hidden" name="sales_tax_rate" id="sales_tax_rate" value="{{ $sales_order->sales_tax_rate }}" /></td>
                                                        <td class="text-right" colspan="1"></td>
                                                    </tr>
                                                @endif

                                                
                                                @if($sales_order->sales_tax_further > 0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Further Sales Tax {{ number_format($sales_order->sales_tax_further,2) }}</td>
                                                        <td class="text-right" colspan="1">
                                                            <input type="hidden" name="sales_tax_further_per" id="sales_tax_further_per" value="{{ $sales_order->sales_tax_further }}" />
                                                            
                                                            <input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text" name="sales_tax_further" id="sales_tax_further" value="{{ $further_tax }}" />
                                                        </td>
                                                        <td class="text-right" colspan="1"></td>

                                                    </tr>
                                                @endif
                                                @if($sales_order->advance_tax >0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Advance Tax {{ number_format($sales_order->advance_tax,2) }}</td>
                                                        <td class="text-right" colspan="1">
                                                            
                                                        <input type="hidden" name="advance_tax_rate" id="advance_tax_rate" value="{{ $sales_order->advance_tax }}" />

                                                        <input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text"   name="advance_tax_amount" id="advance_tax_amount" value="{{ $advance_tax }}" />
                                                        </td>
                                                        <td class="text-right" colspan="1"></td>
                                                    </tr>
                                                @endif
                                                 @if($sales_order->cartage_amount >0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Cartage Amount</td>
                                                        
                                                        <td colspan=""> <input class="form-control text-right" type="text" name="cartage_amount" id="cartage_amount" value="{{ $sales_order->cartage_amount }}" readonly />
                                                        </td>

                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td  class="text-right" colspan="5"></td>
                                                    <td  class="text-right" colspan="2">Grand Total</td>
                                                    <td colspan="1">   <input style="font-weight: bolder" class="form-control text-right" readonly type="text" name="grand" id="grand" value="" /></td>
                                                    <td class="text-right" colspan="1"></td>
                                                </tr>

                                            </table>

                                            <?php $count=1;
                                             ?>

                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="id_count" value="{{$id_count}}"/>


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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="demandsSection"></div>
                <div class="row">
                    <div id="subm"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

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
    </div>

    <script>

        $(document).ready(function() {

            $('.select2').select2();
            get_ntn();
            $('.comma_seprated').number(true,3);
        //    $('.hidee').fadeIn();
            var cal_count=$('#count').val();
            for (i=1; i<=cal_count; i++)
            {
                calc(i);
            }

            $(".btn-success").click(function(e){

                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands){
                    jqueryValidationCustom();
                    if(validate == 0){
                    }else{
                        return false;
                    }
                }
            });
        });

        $('#amount_data').change(function()
        {
            if($(this).is(':checked'))
            {
                $('.hidee').fadeIn(1000);
                $('.resize').attr("rows","5");
                $('.resize').attr("cols","20");
                $('#total_').attr('colspan',3);
                $('.nett').attr('colspan',6);
            }
            else
            {
                $('.hidee').fadeOut();
                $('#total_').attr('colspan',3);
                $('.resize').attr("cols","50");
            }

        });


        function sales_tax()
        {
            var total =	$('#total_amount').val();
            var sales_tax_per = $('#sales_tax_rate').val();
            var sales_tax_further_per = $('#sales_tax_further_per').val();
            var sales_tax = (total / 100) * sales_tax_per;
            var sales_tax_further = (total / 100) * sales_tax_further_per;
            var advance_tax_rate = $('#advance_tax_rate').val();
            var advance_tax = (total / 100) * advance_tax_rate;
            var cartage_amount = parseFloat($('#cartage_amount').val());

            $('#sales_tax').val(sales_tax);

            var strn= $('#buyers_sales').val();
            if (strn == '')
            {
                $('#sales_tax_further').val(sales_tax_further);
            }
            else
            {
                sales_tax_further = 0;
                $('#sales_tax_further').val(0);
            }

            var total_tax = sales_tax + sales_tax_further + advance_tax;

            var total_amount = $('#total_amount').val();
            var total_after_sales_tax = parseFloat(total_amount) + parseFloat(total_tax);
            
            $('#grand').val(total_after_sales_tax + cartage_amount);
            $('#d_t_amount_1').val(total_after_sales_tax + cartage_amount);

            // var sales_tax=parseFloat($('#sales_tax').val());
            // var amount=parseFloat($('#total_amount').val());
            // if (isNaN(sales_tax))
            // {
            //     sales_tax=0;
            // }
            //$('#grand').val(sales_tax+amount);
            toWords(1);
        }

        $("form").submit(function(e){

            $("#subm").css("display", "none");
            var validation=1;
             var id_count=1;
          var   val=0;
            $('.instock').each(function(i, obj)
            {
                var val=parseFloat($('#'+obj.id).val());

                var number=obj.id;

               var cls= obj.className;

                cls=cls.split(' ');
                cls=cls[2];
                cls='item'+cls;

               var qty=0;
                $('.'+cls).each(function(i, obj)
                {
                    qty+=+parseFloat($('#'+obj.id).val());
                });




                number= number.replace("instock", "");
                var qty=parseFloat($('#send_qty'+number).val());


                var total=val-qty;


                if (total<0 || isNaN(total))
                {

                 //   alert('something went wrong');
                    if($("#check"+id_count).prop('checked') == false)
                    {
                        $('#'+obj.id).css("background-color","red");

                        validation=0;
                    }


                }
                else
                {
                    $('#'+obj.id).css("background-color","");
                }

                id_count++;
            });

            if (validation==0)

            {    alert('something went wrong');
                $("#subm").css("display", "block");
               e.preventDefault();

            }
            else
            {
                $("#subm").css("display", "none");
                $('form').submit();

            }

        });

        function ApplyAll(number)
        {
            var count =$('#id_count').val();
            if (number==1) {
                for (i=1; i<=count; i++) {

                    var selectedVal = $('#warehouse'+number).val();
                    $('.ClsAll').val(selectedVal);
                    get_stock('warehouse'+i,i);
                }
            }
        }

        function get_stock(warehouseId, number, number2 = 0, flag = 0) {
            number2++;
            var count = number;
            number = parseInt(number) + parseInt(flag);
            var warehouse = $('#' + warehouseId).val();
            var item = $('#item_id' + count).val(); // make sure this input exists
            var batch_code = '';

            $.ajax({
                url: '{{ url('/') }}/pdc/get_stock_location_wise?batch_code=' + batch_code,
                type: "GET",
                data: { warehouse: warehouse, item: item },
                success: function(data) {
                    
                    let actionButtons = '';

                    if (flag === 0) {
                        actionButtons = `
                            <a href="javascript:;" class="btn btn-sm btn-primary" onclick="AddMoreBatchCode('${warehouseId}',${number},${number2},1)">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        `;
                    } else {
                        actionButtons = `
                            <a href="javascript:;" class="btn btn-sm btn-danger" onclick="removeBatchCode(${count},${number2})">
                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            </a>
                        `;
                    }

                    $('#append_batch_code' + count).before(`
                        <tr id="removeBatchCodeRow${count}_${number2}">
                            <td colspan="5" class="batchQtyError${count}"></td>
                            <td>
                                <select name="batch_codes${count}[]" id="batch_code${count}_${number2}" class="form-control select2" onchange="get_stock_qty(${count},${number2})">
                                    ${data}
                                </select>
                            </td>
                            <td>
                                <input readonly type="text" name="in_stock_qty${count}[]" id="in_stock_qty${count}_${number2}" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="out_qtys${count}[]" id="out_qty${count}_${number2}" class="form-control" onkeyup="checkOutQtyLimit(${count})" />
                            </td>
                            <td>${actionButtons}</td>
                        </tr>
                    `);

                    $('.select2').select2();
                }
            });
        }

        function AddMoreBatchCode(warehouseId, number, number2, flag) {
            get_stock(warehouseId, number, number2, flag);
        }

        function removeBatchCode(row, row2) {
            var element = document.getElementById("removeBatchCodeRow" + row + "_" + row2);

            if (element) {
                element.parentNode.removeChild(element);
            }
        }

        
        function get_stock_qty(number,number2) {
            var warehouse = $('#warehouse'+number).val();
            var item = $('#item_id'+number).val();
            var batch_code = $('#batch_code'+number+'_'+number2).val();

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item},
                success:function(data) {
                    data = data.split('/');
                    $('#in_stock_qty'+number+'_'+number2).val(data[0]);
   
                    if (data[0]==0) {
                        $("#"+item).css("background-color", "red");
                    } else {
                        $("#"+item).css("background-color", "");
                    }
                }
            });
        }

        function checkOutQtyLimit(idCount) {
          
            let totalOutQty = 0;

            // Select all inputs that start with "out_qty" and match the idCount prefix (e.g., "1_")
            $(`input[id^='out_qty${idCount}_']`).each(function () {
                let val = parseFloat($(this).val());
                if (!isNaN(val)) {
                    totalOutQty += val;
                }
            });

            let originalQty = parseFloat($(`#send_qty${idCount}`).val());

            if (totalOutQty > originalQty) {
                $('.batchQtyError'+idCount).text(`Total batch quantity (${totalOutQty}) cannot exceed ordered quantity (${originalQty}).`).css("color", "red");
                $('.btn-success').attr("disabled", true)
                return false;
            }
            $('.btn-success').removeAttr("disabled", true)
            $('.batchQtyError'+idCount).text(``);
            return true;
        }

        function get_ntn()
        {
            var ntn=$('#ntn').val();
            ntn=ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            sales_tax();
        }


        function send_qty_get(num)
        {
            var item_id=$('#item_id'+num).val();
            var send_qty=0
            $('.item'+item_id).each(function(i, obj) {
                send_qty+=+parseFloat($(this).val());
            });

            return send_qty;
        }

        function calc(num)
        {
            var send_qty=parseFloat($('#send_qty'+num).val());
            var actual_qty=parseFloat($('#qty'+num).val());

            if (send_qty > actual_qty)
            {
                alert('amount can not greater than sales order QTY');
                $('#send_qty'+num).val(actual_qty);
                net();
                return false;
            }

            var rate = parseFloat($('#send_rate'+num).val());
            var total = send_qty * rate;

            var x = parseFloat($('#send_discount'+num).val());
            if (isNaN(x)) {
                x = 0;
            }
            if (x > 0) {
                x = x*total;
                var discount_amount = parseFloat(x / 100);
                console.log(typeof discount_amount);
                $('#send_discount_amount'+num).val(discount_amount.toFixed(2));
                total= total + discount_amount;
            }

            $('#send_amount'+num).val(total);
            
            checkOutQtyLimit(num);
            net();
        }

        function net()
        {
            var amount = 0;
            $('.amount').each(function (i, obj) {
                amount += +parseFloat($('#'+obj.id).val());
            });
            amount = parseFloat(amount);
            $('#total_amount').val(amount);

            var sales_tax_per = $('#sales_tax_rate').val();
            var sales_tax_further_per = $('#sales_tax_further_per').val();
            var sales_tax = parseFloat((amount / 100) * sales_tax_per);
            var sales_tax_further = parseFloat((amount / 100) * sales_tax_further_per);
            var advance_tax_rate1 = $('#advance_tax_rate').val();
            var advance_tax = parseFloat((amount / 100) * advance_tax_rate1);
            var cartage_amount = parseFloat($('#cartage_amount').val());

            $('#grand').val(amount + sales_tax + sales_tax_further + advance_tax + cartage_amount);
            var qty = 0;
            $('.qty').each(function (i, obj) {
                qty += +parseFloat($('#'+obj.id).val());
            });
            qty = parseFloat(qty);
            $('#total_qty').val(qty);
        }


        function required_none(number,qry)
        {
            if($("#check"+number).prop('checked') == true)
            {
               // $("#batch_code"+number).removeClass("requiredField");
                $('#send_qty'+number).attr('readonly', true);
                $('#send_qty'+number).val(0);
                calc(number);
                sales_tax();
                net();
            }

            else
            {
                //$("#batch_code"+number).addClass("requiredField");
                $('#send_qty'+number).attr('readonly', false);
                $('#send_qty'+number).val(qry);
                calc(number);
                sales_tax();
                net();

            }
        }

    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
