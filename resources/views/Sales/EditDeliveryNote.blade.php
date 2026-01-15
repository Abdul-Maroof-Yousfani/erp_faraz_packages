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
?>
@extends('layouts.default')

@section('content')
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
    <?php //$so_no= SalesHelper::get_unique_no(date('y'),date('m')); ?>

    <div class="row well_N" id="main">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Delivery Note</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'sad/updateDeliveryNote?m='.$m.'','id'=>'createSalesOrder'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Delivery Note No<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="text" class="form-control requiredField" placeholder="" name="gd_no" id="gd_no" value="{{strtoupper($delivery_note->gd_no)}}" />
                                        <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $delivery_note->id;?>">
                                        <input type="hidden" id="master_id" name="master_id" value="<?php echo $delivery_note->master_id;?>">
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Delivery Note Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input  autofocus type="date" class="form-control requiredField" placeholder="" name="gd_date" id="gd_date" value="{{$delivery_note->gd_date}}" />
                                    </div>

                                    <!-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Mode / Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input  type="text" class="form-control requiredField" placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="{{$delivery_note->model_terms_of_payment}}" />
                                    </div> -->

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">SO No <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="text" class="form-control requiredField" placeholder="" name="so_no" id="so_no" value="{{$delivery_note->so_no}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">SO Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="date" class="form-control requiredField" placeholder="" name="so_date" id="so_date" value="{{$delivery_note->so_date}}" />
                                    </div>
                                    <!-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Other Reference(s) <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="text" class="form-control requiredField" placeholder="" name="other_refrence" id="other_refrence" value="{{$delivery_note->other_refrence}}" />
                                    </div> -->

                                </div>


                                <div class="row">

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Buyer's Order No<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="text" class="form-control requiredField" placeholder="" name="order_no" id="order_no" value="{{$delivery_note->order_no}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="date" class="form-control requiredField" placeholder="" name="order_date" id="order_date" value="{{$delivery_note->order_date}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Despatched Document No</label>
                                        <input  type="text" class="form-control" placeholder="" name="despacth_document_no" id="despacth_document_no" value="{{$delivery_note->despacth_document_no}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Despatched Document Date</label>
                                        <input  type="date" class="form-control" placeholder="" name="despacth_document_date" id="despacth_document_date" value="{{$delivery_note->despacth_document_date}}" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Despatched through</label>
                                        <input readonly type="text" class="form-control" placeholder="" name="despacth_through" id="despacth_through" value="{{$delivery_note->despacth_through}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Destination</label>
                                        <input readonly type="text" class="form-control" placeholder="" name="destination" id="destination" value="{{$delivery_note->destination}}" />
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Terms Of Delivery</label>
                                        <input readonly type="text" class="form-control" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="{{$delivery_note->terms_of_delivery}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                        <select style="width: 100%" disabled name="" id="ntn" onchange="get_ntn()" class="form-control select2">
                                            <option>Select</option>
                                            @foreach(SalesHelper::get_all_customer() as $row)
                                                <option @if($delivery_note->buyers_id==$row->id) selected @endif value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="buyers_id" value="{{$delivery_note->buyers_id}}"/>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Buyer's Ntn </label>
                                        <input  readonly type="text" class="form-control" placeholder="" name="buyers_ntn" id="buyers_ntn" value="" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Buyer's Sales Tax No </label>
                                        <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Due Date</label>
                                        <input readonly type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="{{$delivery_note->due_date}}" />
                                    </div>
                                </div>

                                <input type="hidden" name="demand_type" id="demand_type">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Description</label>
                                        <textarea  name="description" id="description" rows="4" cols="50" style="resize:none;text-transform: capitalize" class="form-control">{{$delivery_note->description}}</textarea>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span ondblclick="show()" class="subHeadingLabelClass">Delivery Note  Data</span>
                                        <input type="checkbox" id="amount_data"/>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>
                                <div id="addMoreDemandsDetailRows_1" class="panel addMoreDemandsDetailRows_1">

                                    <input type="hidden" name="count" id="count" value="1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S.NO</th>
                                                    <th class="text-center">Item</th>
                                                    <th class="text-center">Pack Type</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center" >QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" >WareHouse. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <!-- <th style="width: 150px" class="text-center" >Batch Code <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" >In Stock<span class="rflabelsteric"><strong>*</strong></span></th> -->
                                                    <th class="text-center">Rate</th>
                                                    <!-- <th class="text-center hidee">Tax%</th>
                                                    <th class="text-center hidee">Tax Amount</th> -->
                                                    <th class="text-center">Net Amount</th>
                                                    <th class="text-center">0 Qty</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $counter=1;
                                                $id_count=0;
                                                $working_counter=0;
                                                $total=0;
                                                $total_qty=0;
                                                $finalTotal =0;
                                           

                                                foreach ($delivery_note_data as $row1)
                                                {
                                                        $finalTotal+=$row1->amount;

                                                if ($row1->bundles_id==0):

                                                //$qty=SalesHelper::get_dn_total_qty($row1->so_data_id);



                                                $working_counter++;
                                                $id_count++;     ?>
                                                {{--hidden data--}}
                                                <input type="hidden" name="data_id{{$id_count}}" id="data_id" value="{{$row1->so_data_id}}"/>
                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$row1->groupby}}"/>
                                                <input type="hidden" name="item_id{{$working_counter}}" id="item_id{{$working_counter}}" value="{{$row1->item_id}}"/>
                                                <input type="hidden" name="rate{{$working_counter}}" id="rate{{$working_counter}}" value="{{$row1->rate}}"/>
                                                {{-- <input type="hidden" name="discount_percent{{$working_counter}}" id="discount_percent{{$working_counter}}" value="{{$row1->discount_percent}}"/> --}}
                                                {{-- <input type="hidden" name="discount_amount{{$working_counter}}" id="discount_amount{{$working_counter}}" value="{{$row1->discount_amount}}"/> --}}
                                                <input type="hidden" name="amount{{$working_counter}}" id="amount{{$working_counter}}" value="{{$row1->amount}}"/>


                                                <tr>
                                                    <td class="text-center" class="text-center"><?php echo $counter;?></td>
                                                    <td id="{{$row1->item_id}}" class="text-left">{{ $row1->item_code .' -- '. $row1->sub_ic }}</td>
                                                    <td>{{ $row1->pack_size.' '.$row1->uom_name.' '.$row1->type }}</td>
                                                    <td>{{ $row1->color }}</td>

                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail);
                                                    ?>
                                                    <!-- <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td> -->

                                                    <?php $total_qty+=$row1->qty; ?>
                                                    <td class="text-right">
                                                        <input onkeyup="calc('{{$id_count}}')" onblur="calc('{{$id_count}}')" class="form-control qty" type="text" name="send_qty{{$id_count}}" id="send_qty{{$id_count}}" value="{{$row1->qty}}"/>
                                                        <?php
                                                        $actual_qty = DB::Connection('mysql2')->table('sales_order_data')->where('id',$row1->so_data_id)->first()->qty;
                                                        $dn_qty = DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$row1->so_data_id)->sum('qty');
                                                        ?>
                                                        <input type="hidden" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{($actual_qty-$dn_qty)+$row1->qty}}"/>
                                                        <input type="hidden" id="aterCalcQty<?php echo $id_count?>" value="<?php echo $row1->qty?>">
                                                    </td>

                                                    <td><select onchange="get_stock(this.id,'{{$id_count}}')" class="form-control requiredField ClsAll ShowOn<?php echo $id_count?>" name="warehouse{{$id_count}}" id="warehouse{{$id_count}}">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                <option value="{{$row->id}}" <?php if($row1->warehouse_id == $row->id): echo "selected"; endif;?>>{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <!-- <td>
                                                        <?php $Batch = CommonHelper::batch_code_edit($row1->warehouse_id,$row1->item_id)?>
                                                        <select onchange="get_stock_qty(this.id,'{{$id_count}}')" class="form-control requiredField" name="batch_code{{$id_count}}" id="batch_code{{$id_count}}">
                                                            <option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                            <?php foreach($Batch as $bfil):?>
                                                            <option value="<?php echo $bfil->batch_code?>" <?php if($row1->batch_code == $bfil->batch_code):echo "selected"; endif;?>><?php echo $bfil->batch_code?></option>
                                                            <?php endforeach;?>
                                                        </select></td> -->
                                                    <!-- <td id="instock<?php echo $id_count?>">
                                                        <?php
                                                        $pizza = CommonHelper::in_stock_edit($row1->item_id,$row1->warehouse_id,$row1->batch_code);

                                                    
                                                        ?>

                                                    </td> -->


                                                    <td class="text-right ">
                                                        <input readonly class="form-control" type="text" name="send_rate{{$id_count}}" id="send_rate{{$id_count}}" value="{{$row1->rate}}"/>

                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control" type="text" name="send_discount{{$id_count}}" id="send_discount{{$id_count}}" value="{{$row1->tax}}"/>

                                                    </td>


                                                    <td class="text-right hidee">

                                                        <input readonly class="form-control" type="text" name="send_discount_amount{{$id_count}}" id="send_discount_amount{{$id_count}}" value="{{$row1->tax_amount}}"/>
                                                    </td>
                                                    <td class="text-right ">
                                                        <input readonly class="form-control amount comma_seprated" type="text" name="send_amount{{$id_count}}" id="send_amount{{$id_count}}" value="{{$row1->amount}}"/>
                                                    </td>
                                                    <td><input type="checkbox" class="" id="check{{$id_count}}" onclick="required_none('{{$id_count}}','{{$row1->qty}}')" ></td>
                                                    <input type="hidden" name="bundles_id{{$working_counter}}" value="0"/>
                                                </tr>
                                                <tr id="append_batch_code{{$id_count}}"></tr>
                                                <?php else:  ?>



                                                <?php $product_data=DB::Connection('mysql2')->table('delivery_note_data')->where('bundles_id',$row1->bundles_id)->where('master_id',$delivery_note->id)->select('*')->get();

                                                $item_count=$counter+0.1;
                                                $bundle_stop=1;
                                                foreach ($product_data as $bundle_data):


                                                //$qty=SalesHelper::get_dn_total_qty($bundle_data->id);

                                                //$qty=$bundle_data->qty-$qty;

                                                //if ($qty>0):
                                                $working_counter++;
                                                $id_count++;
                                                ?>
                                                <input type="hidden" name="groupby{{$id_count}}" id="groupby" value="{{$bundle_data->groupby}}"/>
                                                @if ($bundle_stop==1)
                                                    <tr  style="font-size: larger;font-weight: bold;background-color: lightyellow">
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

                                                <input type="hidden" name="data_id{{$id_count}}" id="data_id" value="{{$bundle_data->so_data_id}}"/>
                                                {{--<input type="hidden" name="qty{{$working_counter}}" id="qty{{$working_counter}}" value="{{$bundle_data->qty}}"/>--}}
                                                <input type="hidden" name="bundles_id{{$working_counter}}" value="{{$bundle_data->bundles_id}}"/>
                                                <input type="hidden" name="item_id{{$working_counter}}" id="item_id{{$working_counter}}" value="{{$bundle_data->item_id}}"/>

                                                <tr style="background-color: lightyellow">
                                                    <td class="text-center" class="text-center"><?php echo $item_count;?></td>
                                                    <td id="{{$bundle_data->item_id}}" class="text-left"><?php echo CommonHelper::get_item_name($bundle_data->item_id);?></td>


                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                    ?>
                                                    <td class="text-left"> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>

                                                    <td class="text-right">   <input onkeyup="calc('{{$id_count}}')" onblur="calc('{{$id_count}}')" class="form-control qty" type="text" name="send_qty{{$id_count}}" id="send_qty{{$id_count}}" value="{{$bundle_data->qty}}"/>
                                                        <?php
                                                        $actual_qty1 = DB::Connection('mysql2')->table('sales_order_data')->where('id',$bundle_data->so_data_id)->first()->qty;
                                                        $dn_qty1 = DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$bundle_data->so_data_id)->sum('qty');
                                                        ?>
                                                        <input type="hidden" name="qty{{$id_count}}" id="qty{{$id_count}}" value="{{($actual_qty1-$dn_qty1)+$bundle_data->qty}}"/>
                                                        <input type="hidden" id="aterCalcQty<?php echo $id_count?>" value="<?php echo $bundle_data->qty?>">

                                                    </td>
                                                    <td><select onchange="get_stock(this.id,'{{$working_counter}}');ApplyAll('<?php echo $id_count?>')" class="form-control requiredField ClsAll ShowOn<?php echo $counter?>" name="warehouse{{$working_counter}}" id="warehouse{{$working_counter}}">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                <option value="{{$row->id}}" <?php if($bundle_data->warehouse_id == $row->id): echo "selected"; endif;?>>{{$row->name}}</option>
                                                                <?php ?>     @endforeach
                                                        </select></td>


                                                    <td>
                                                        <?php $Batch = CommonHelper::batch_code_edit($bundle_data->warehouse_id,$bundle_data->item_id)?>
                                                        <select onchange="get_stock_qty(this.id,'{{$working_counter}}')" class="form-control requiredField" name="batch_code{{$id_count}}" id="batch_code{{$id_count}}">
                                                            <option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                            <?php foreach($Batch as $fil):?>
                                                            <option value="<?php echo $fil->batch_code?>" <?php if($bundle_data->batch_code == $fil->batch_code):echo "selected"; endif;?>><?php echo $fil->batch_code?></option>
                                                            <?php endforeach;?>

                                                        </select></td>
                                                    <td id="instock<?php echo $working_counter?>">
                                                        <?php
                                                        $Stock = CommonHelper::in_stock_edit($bundle_data->item_id,$bundle_data->warehouse_id,$bundle_data->batch_code);
                                                            echo $Stock;//;+$bundle_data->qty;
                                                        ?></td>


                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control" type="text" name="send_rate{{$id_count}}" id="send_rate{{$id_count}}" value="{{$bundle_data->rate}}"/>

                                                    </td>
                                                    <?php // if ($bundle_data->discount_percent=='') ?>

                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control" type="text" name="send_discount{{$id_count}}" id="send_discount{{$id_count}}" value="{{$bundle_data->discount_percent}}"/>

                                                    </td>


                                                    <td class="text-right hidee">

                                                        <input readonly class="form-control" type="text" name="send_discount_amount{{$id_count}}" id="send_discount_amount{{$id_count}}"

                                                               value="{{$bundle_data->discount_amount}}"/>
                                                    </td>
                                                    <td class="text-right hidee">
                                                        <input readonly class="form-control amount comma_seprated" type="text" name="send_amount{{$id_count}}" id="send_amount{{$id_count}}" value="{{$bundle_data->amount}}"/>
                                                    <td><input type="checkbox" class="" id="check{{$id_count}}" onclick="required_none('{{$id_count}}','{{$bundle_data->qty}}')" ></td>
                                                </tr>
                                                <?php  $item_count+=0.1; endforeach ;$bundle_stop=1; ?>




                                                <?php endif;




                                                $total+=$row1->amount;
                                                $counter++;


                                                }
                                                       
                                                ?>

                                                </tbody>
                                                <input type="hidden" id="count" name="count" value="{{$id_count}}"/>


                                                <tr>

                                                    <td id="total_" style="background-color: darkgray" class="text-center" colspan="6">Total</td>

                                                    <td   style="background-color: darkgray" class="text-right hidee nett"  colspan="7"><input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text" id="total_amount" value="{{$FinalTot}}" /></td>


                                                </tr>



                                                @if($delivery_note->sales_tax_rate > 0)
                                                    <?php  $total += $delivery_note->sales_tax_rate; ?>
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Sales Tax {{ number_format($delivery_note->sales_tax_rate,2) }}</td>
                                                        <td colspan="1"> <input style="font-weight: bolder" class="form-control text-right" readonly type="text" name="sales_tax" id="sales_tax" value="{{ $delivery_note->sales_tax_amount }}" /></td>
                                                        <td colspan="1"> <input type="hidden" name="sales_tax_rate" id="sales_tax_rate" value="{{ $delivery_note->sales_tax_rate }}" /></td>
                                                        <td class="text-right" colspan="1"></td>
                                                    </tr>
                                                @endif


                                                @if($delivery_note->sales_tax_further > 0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Further Sales Tax {{ number_format($delivery_note->sales_tax_further,2) }}</td>
                                                        <td class="text-right" colspan="1">
                                                            <input type="hidden" name="sales_tax_further_per" id="sales_tax_further_per" value="{{ $delivery_note->sales_tax_further }}" />
                                                            
                                                            <input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text" name="sales_tax_further" id="sales_tax_further" value="{{ $delivery_note->sales_tax_further }}" />
                                                        </td>
                                                        <td class="text-right" colspan="1"></td>

                                                    </tr>
                                                @endif
                                                @if($delivery_note->advance_tax_amount >0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Advance Tax {{ number_format($delivery_note->advance_tax_rate,2) }}</td>
                                                        <td class="text-right" colspan="1">
                                                            
                                                        <input type="hidden" name="advance_tax_rate" id="advance_tax_rate" value="{{ $delivery_note->advance_tax_rate }}" />

                                                        <input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text"   name="advance_tax_amount" id="advance_tax_amount" value="{{ $delivery_note->advance_tax_amount }}" />
                                                        </td>
                                                        <td class="text-right" colspan="1"></td>
                                                    </tr>
                                                @endif
                                                 @if($delivery_note->cartage_amount >0)
                                                    <tr>
                                                        <td  class="text-right" colspan="5"></td>
                                                        <td class="text-right" colspan="2">Cartage Amount</td>
                                                        
                                                        <td colspan=""> <input class="form-control text-right" type="text" name="cartage_amount" id="cartage_amount" value="{{ $delivery_note->cartage_amount }}" readonly />
                                                        </td>
                                                          <td></td>

                                                    </tr>
                                                @endif

                                                <tr>

                                                    <td  class="text-center" colspan="6"></td>
                                                    <td class="text-right" colspan="1"><b>Grand Total</td>
                                                    <td colspan="1">   <input style="font-weight: bolder" class="form-control text-right comma_seprated" readonly type="text" name="grand" id="grand" value="" /></td>
                                                    <td></td>

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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="demandsSection"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
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

    <script>
    
        let subIndexTracker = {};

        $(document).ready(function() {

            @foreach($delivery_note_data as $index => $row1)
                subIndexTracker[{{ $index + 1 }}] = {{ count(explode(',', $row1->batch_code)) }};
                @php
                    $batchCodes = explode(',', $row1->batch_code);
                    $outQtys = explode(',', $row1->out_qty_details);
                @endphp
                @foreach($batchCodes as $bIndex => $batch)
                    @php
                        $qty = isset($outQtys[$bIndex]) ? $outQtys[$bIndex] : 0;
                    @endphp
                    get_stock_on_edit("warehouse{{ $index + 1 }}", {{ $index + 1 }}, @json(trim($batch)), {{ $bIndex + 1 }}, {{ $qty }});
                @endforeach
            @endforeach
        });

        function get_stock_on_edit(warehouseId, count, selectedBatchCode, subIndex, outQty) {
           
            const warehouse = $('#' + warehouseId).val();
            const item = $('#item_id' + count).val();

            $.ajax({
                url: '{{ url('/') }}/pdc/get_stock_location_wise2',
                type: "GET",
                data: { warehouse: warehouse, item: item },
                success: function(data) {
                    let buttonHtml = '';

                    if (subIndex === 1) {
                        // Only first row gets the "Add More" button
                        buttonHtml = `
                            <button type="button" class="btn btn-sm btn-primary"
                                    onclick="AddMoreBatchCode('warehouse${count}', ${count}, ${subIndex}, 1)">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        `;
                    } else {
                        // Other rows get the "Remove" button
                        buttonHtml = `
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeBatchRow(${count}, ${subIndex})">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    }

                    let options = `<option value="">Select</option>`;
                    data.forEach(batch => {
                        options += `<option value="${batch.batch_code}" ${batch.batch_code == selectedBatchCode ? 'selected' : ''}>
                            ${batch.batch_code}</option>`;
                    });

                    const html = `
                        <tr id="removeBatchCodeRow${count}_${subIndex}">
                            <td colspan="5" class="batchQtyError${count}"></td>
                            <td>
                                <select name="batch_codes${count}[]" id="batch_code${count}_${subIndex}" class="form-control select2" onchange="get_stock_qty(${count},1)">
                                    ${options}
                                </select>
                            </td>
                            <td>
                                <input readonly type="text" name="in_stock_qty${count}[]" id="in_stock_qty${count}_${subIndex}" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="out_qtys${count}[]" id="out_qty${count}_${subIndex}" value="${outQty}" class="form-control" onkeyup="checkOutQtyLimit(${count})" />
                            </td>
                            <td>
                                ${buttonHtml}
                            </td>
                        </tr>
                    `;

                    $('#append_batch_code' + count).before(html);
                    $('.select2').select2();

                    // Optionally, call stock qty function
                    get_stock_qty(count, subIndex);
                }
            });
        }


        function required_none(number,qry)
        {
            if($("#check"+number).prop('checked') == true)
            {
                $("#batch_code"+number).removeClass("requiredField");
                $('#send_qty'+number).attr('readonly', true);
                $('#send_qty'+number).val(0);
                calc(number);
                //     sales_tax();
                net();
            }

            else
            {
                $("#batch_code"+number).addClass("requiredField");
                $('#send_qty'+number).attr('readonly', false);
                $('#send_qty'+number).val(qry);
                calc(number);
                //    sales_tax();
                net();

            }
        }

        // function get_stock(warehouse,number)
        // {


        //     var warehouse=$('#'+warehouse).val();
        //     var item=$('#item_id'+number).val();
        //     var batch_code='';

        //     $.ajax({
        //         url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
        //         type: "GET",
        //         data: {warehouse:warehouse,item:item},
        //         success:function(data)
        //         {

        //             $('#batch_code'+number).html(data);
        //             $('#instock'+number).html('');
        //         }
        //     });

        // }

        function get_stock(warehouseId, number, flag = 0) {
            var count = number;

            // Initialize tracker if not already set
            if (!subIndexTracker[count]) {
                subIndexTracker[count] = 1;
            } else {
                subIndexTracker[count]++;
            }

            let subIndex = subIndexTracker[count];

            number = parseInt(number) + parseInt(flag);
            var warehouse = $('#' + warehouseId).val();
            var item = $('#item_id' + count).val();
            var batch_code = '';

            $.ajax({
                url: '{{ url('/') }}/pdc/get_stock_location_wise?batch_code=' + batch_code,
                type: "GET",
                data: { warehouse: warehouse, item: item },
                success: function(data) {
                    let actionButtons = '';

                    if (flag === 0) {
                        actionButtons = `
                            <a href="javascript:;" class="btn btn-sm btn-primary" onclick="AddMoreBatchCode('${warehouseId}',${count},1)">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </a>
                        `;
                    } else {
                        actionButtons = `
                            <a href="javascript:;" class="btn btn-sm btn-danger" onclick="removeBatchCode(${count},${subIndex})">
                                <i class="fa fa-trash"></i>
                            </a>
                        `;
                    }

                    $('#append_batch_code' + count).before(`
                        <tr id="removeBatchCodeRow${count}_${subIndex}">
                            <td colspan="5" class="batchQtyError${count}"></td>
                            <td>
                                <select name="batch_codes${count}[]" id="batch_code${count}_${subIndex}" class="form-control select2" onchange="get_stock_qty(${count},${subIndex})">
                                    ${data}
                                </select>
                            </td>
                            <td>
                                <input readonly type="text" name="in_stock_qty${count}[]" id="in_stock_qty${count}_${subIndex}" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="out_qtys${count}[]" id="out_qty${count}_${subIndex}" class="form-control" onkeyup="checkOutQtyLimit(${count})" />
                            </td>
                            <td>${actionButtons}</td>
                        </tr>
                    `);

                    $('.select2').select2();
                }
            });
        }

        function AddMoreBatchCode(warehouseId, number, flag) {
            get_stock(warehouseId, number, flag);
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

        // function get_stock_qty(warehouse,number)
        // {
        //     var warehouse=$('#warehouse'+number).val();
        //     var item=$('#item_id'+number).val();
        //     var batch_code=$('#batch_code'+number).val();


        //     $.ajax({
        //         url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
        //         type: "GET",
        //         data: {warehouse:warehouse,item:item},
        //         success:function(data)
        //         {

        //             //   $('#batch_code'+number).html(data);

        //             data=data.split('/');
        //             $('#instock'+number).html(data[0]);
        //             //     $('#rate'+number).val(data[1]);
        //             //     var amount=data[0]*data[1];
        //             //     $('#net_amount'+number).val(amount);
        //             if (data[0]==0)
        //             {
        //                 $("#"+item).css("background-color", "red");
        //             }
        //             else
        //             {
        //                 $("#"+item).css("background-color", "");
        //             }

        //         }
        //     });

        // }
        $(document).ready(function() {

            get_ntn();
            $('.hidee').fadeOut();


            var d = 1;



            $(".btn-success").click(function(e)
            {

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
                $('.hidee').fadeIn(1000);
                $('.resize').attr("rows","5");
                $('.resize').attr("cols","20");
                $('#total_').attr('colspan',6);
            }
            else
            {


                $('.hidee').fadeOut();
                $('#total_').attr('colspan',4);
                $('.resize').attr("cols","50");
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
            toWords(1);
        }


    </script>


    <script>

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
            var send_qty=parseFloat($('#send_qty'+num).val());
            var actual_qty=parseFloat($('#qty'+num).val());
            var aterCalcQty=parseFloat($('#aterCalcQty'+num).val());



            if (send_qty > actual_qty)
            {
                alert('amount can not greater than sales order QTY');
                $('#send_qty'+num).val(aterCalcQty);
                net();
                return false;
            }

            var rate=parseFloat($('#send_rate'+num).val());
            var total=send_qty*rate;

            // discount
            var x = parseFloat($('#send_discount'+num).val());
            if (isNaN(x))
            {
                x=0;
            }
            if (x>0)
            {

                x=x*total;

                var discount_amount =parseFloat( x / 100).toFixed(2);
                $('#send_discount_amount'+num).val(discount_amount);
                total=total-discount_amount;

            }


            // discount end

            $('#send_amount'+num).val(total);


            net();
            sales_tax();


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
            alert()

            $('#grand').val(amount + sales_tax + sales_tax_further + advance_tax + cartage_amount);
            var qty = 0;
            $('.qty').each(function (i, obj) {
                qty += +parseFloat($('#'+obj.id).val());
            });
            qty = parseFloat(qty);
            $('#total_qty').val(qty);
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

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection