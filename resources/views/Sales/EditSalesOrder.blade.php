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
use App\Helpers\NotificationHelper;

$Cont = DB::Connection('mysql2')->table('delivery_note')->where('master_id',$sales_order_id)->where('status',1);
?>

@extends('layouts.default')

@section('content')

    @include('select2')

    @include('bundles_data')
    @include('modal')

    <style>
        * {
            font-size: 12px!important;

        }
        label {
            text-transform: capitalize;
        }
    </style>
    <?php $so_no= $sales_order->so_no; ?>
    <?php
    if($Cont->count() > 0)
    {
    ?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass"><?php

                            echo "Can't Edit :  Delivery Note Created  ".$Cont->first()->gd_no. ": against this Sales Order ".strtoupper($sales_order->so_no) ;

                            ?></span>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php



    }
            else
            {

    ?>

    <div class="row well_N">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Sale Order</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => '/update_sales_order?m='.$m.'','id'=>'createSalesOrder','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php //echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php //echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">voucher No <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input readonly type="text" class="form-control" placeholder="" name="so_no" id="so_no" value="{{strtoupper($so_no)}}" />
                                                <input type="hidden" id="EditId" name="EditId" value="<?php echo $sales_order->id?>">
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Voucher Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="date" class="form-control" placeholder="" name="so_date" id="so_date" value="{{$sales_order->so_date}}" />
                                            </div>

                                            <input type="hidden" id="sales_order_id" name="sales_order_id" value="<?php echo $sales_order_id?>"/>


                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Buyer's Order NO <span></span></label>
                                                <label for="" style="float: right">
                                                    verified / non verified
                                                    <input type="checkbox" value="1" name="verified" id="CheckVal" <?php if($sales_order->verified == 1): echo "checked"; endif;?>>
                                                </label>
                                                <input  type="text" class="form-control" placeholder="" name="order_no" id="order_no" value="<?php echo $sales_order->order_no?>" />
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Type<span class="rflabelsteric"></label>
                                                <select class="form-control  select2" name="v_type" id="v_type">
                                                    <option value="">Select Type</option>
                                                    @foreach(NotificationHelper::get_all_type() as $row)

                                                    <option {{ $row->id == $sales_order->p_type ? 'selected' : ''  }} value="{{ $row->id}}">{{ $row->name}}</option>

                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Order Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="date" class="form-control" placeholder="" name="order_date" id="order_date" value="{{$sales_order->order_date}}" />
                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Other Reference(s)</label>
                                                <input  type="text" class="form-control" placeholder="" name="other_refrence" id="other_refrence" value="<?php echo $sales_order->other_refrence?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Dispatched through <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="text" class="form-control" placeholder="" name="desptch_through" id="desptch_through" value="<?php echo $sales_order->desptch_through?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Destination<span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="text" class="form-control" placeholder="" name="destination" id="destination" value="<?php echo $sales_order->destination?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Terms Of Delivery <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="text" class="form-control" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="<?php echo $sales_order->terms_of_delivery?>" />
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select name="buyers_id" id="ntn" onchange="get_ntn()" class="form-control select2 requiredField">
                                                    <option>Select</option>
                                                    @foreach(SalesHelper::get_all_customer() as $row)
                                                        <option value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn.'*'.$row->terms_of_payment}}" <?php if($sales_order->buyers_id == $row->id): echo "selected"; endif;?>>{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>




                                        </div>



                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Buyer's NTN </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_ntn" id="buyers_ntn" value="<?php echo $BuyerData->cnic_ntn?>" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Buyer's Sales Tax No </label>
                                                <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="<?php echo $BuyerData->strn?>" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Mode/ Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calculate_due_date()"  type="number" class="form-control requiredField" placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="<?php echo $sales_order->model_terms_of_payment?>" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="<?php echo $sales_order->due_date?>" readonly />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_data()"  class="form-control" name="department" id="department">
                                                    <option value="">Select</option>
                                                    @foreach(Commonhelper::get_all_sub_department() as $key2 => $row)
                                                        <option {{ $row->id == $sales_order->department ? 'selected' : '' }} value="{{ $row->id}}">{{ $row->sub_department_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="demand_type" id="demand_type">
                                        <div class="row">


                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <textarea  name="description" id="description" rows="4" cols="50" style="resize:none;text-transform: capitalize" class="form-control"><?php echo $sales_order->description?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Sale Order Data</span>

                                </div>
                                <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" >
                                            <table class="table table-bordered">

                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 35%;">Item</th>
                                                    <th class="text-center" >Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center" > QTY.<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Tax %<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Tax Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Net Amount<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Delete<span class="rflabelsteric"><strong>*</strong></span></th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                <?php
                                                $Counter = count($sales_order_data);
                                                $TotalAmount =0;
                                                $totalTax =0;
                                                foreach($sales_order_data as $Fil):
                                                $ItemData = CommonHelper::get_single_row('subitem','id',$Fil->item_id);
                                                $Uom = CommonHelper::get_uom_name($ItemData->uom);

                                                     if($Fil->bundles_id == 0):
                                                $TotalAmount += $Fil->amount;
                                                $totalTax += $Fil->tax_amount;
                                                ?>
                                                <tr title="1" id="RemoveRows<?php echo $Counter?>">

                                                    <td>
                                                        <select onchange="get_uom('{{ $Counter }}')" name="item_id[]" id="sub_ic_des{{ $Counter }}" class="form-control select2">

                                                          <option value="">Select</option>
                                                          @foreach ( CommonHelper::get_all_subitem() as $row )

                                                          <?php $uom =  CommonHelper::get_uom($row->id); ?>
                                                          <option {{ $row->id == $Fil->item_id ? 'selected' : '' }} value="{{ $row->id.','.$uom }}" >{{ $row->sub_ic }}</option>
                                                          @endforeach
                                                        </select>
                                                      </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id{{ $Counter }}" value="{{ $Uom ?? '' }}" >
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="claculation('<?php echo $Counter?>')" onblur="claculation('<?php echo $Counter?>')" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty<?php echo $Counter?>" placeholder="ACTUAL QTY" min="1" value="<?php echo $Fil->qty?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="claculation('<?php echo $Counter?>')" onblur="claculation('<?php echo $Counter?>')" class="form-control requiredField" name="rate[]" id="rate<?php echo $Counter?>" placeholder="RATE" min="1" value="<?php echo $Fil->rate?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control amount" name="amount[]" id="amount<?php echo $Counter?>" placeholder="AMOUNT" min="1" value="<?php echo $Fil->sub_total?>" readonly>
                                                    </td>
                                                    <td>
                                                        <select onchange="tax_percent(this.id)"  class="form-control" name="tax[]" id="tax_percent1">
                                                            <option value="0,0">Select</option>
                                                                {{-- @if ($Fil->tax != null)
                                                                    <option selected value="{{ $Fil->tax }}">{{ $Fil->tax }}</option>
                                                                @endif --}}
                                                            @foreach (ReuseableCode::invoice_tax() as $row )
                                                                <option {{ $Fil->tax == $row->tax_rate ? 'selected' : ''}} value='{{ $row->acc_id.','.$row->tax_rate }}'>{{$row->tax_rate }}</option>
                                                            @endforeach
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control requiredField" name="tax_amount[]" id="tax_amount<?php echo $Counter?>" placeholder="DISCOUNT" min="1" value="<?php echo $Fil->tax_amount?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount<?php echo $Counter?>" placeholder="NET AMOUNT" min="1" value="<?php echo $Fil->amount; ?>" readonly>
                                                    </td>
                                                    <?php if($Counter > 1):?>
                                                    <td class="text-center">
                                                        <input onclick="view_history(<?php echo $Counter?>)" type="checkbox" id="view_history<?php echo $Counter?>">
                                                        <button type="button" class="btn btn-sm btn-danger" id="BtnRemove<?php echo $Counter?>" onclick="RemoveSection('<?php echo $Counter?>')"> - </button></td>
                                                    <?php else:?>
                                                    <td style="background-color: #ccc"><input onclick="view_history(<?php echo $Counter?>)" type="checkbox" id="view_history<?php echo $Counter?>"></td>
                                                    <?php endif;?>
                                                </tr>
                                                <?php else:
                                                $totalTax += $Fil->tax_amount;
                                                $TotalAmount +=$Fil->b_net;

                                                ?>
                                                <tr title="1" id="RemoveRows<?php echo $Counter?>">
                                                    <td>
                                                        <textarea readonly type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_<?php echo $Counter?>" placeholder="ITEM"  style="color: red;"><?php echo $Fil->product_name?></textarea>
                                                        <input type="hidden" class="" name="item_id[]" id="sub_<?php echo $Counter?>">
                                                        <input type="hidden" class="" name="product_id[]" id="product_<?php echo $Counter?>" value="<?php echo $Fil->bundles_id?>">
                                                    </td>

                                                    <td>
                                                        <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id<?php echo $Counter?>" value="<?php  echo CommonHelper::get_uom_name($Fil->bundle_unit);?> ">
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="claculation('<?php echo $Counter?>')" onblur="claculation('<?php echo $Counter?>')" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty<?php echo $Counter?>" placeholder="ACTUAL QTY" min="1" value="<?php echo $Fil->bqty?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="claculation('<?php echo $Counter?>')" onblur="claculation('<?php echo $Counter?>')" class="form-control requiredField" name="rate[]" id="rate<?php echo $Counter?>" placeholder="RATE" min="1" value="<?php echo $Fil->bundle_rate?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="amount[]" id="amount<?php echo $Counter?>" placeholder="AMOUNT" min="1" value="<?php echo $Fil->bundle_amount?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="discount_percent(this.id)" onblur="discount_percent(this.id)" class="form-control requiredField" name="discount_percent[]" id="discount_percent<?php echo $Counter?>" placeholder="DISCOUNT" min="1" value="<?php echo $Fil->b_percent?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="discount_amount(this.id)" onblur="discount_amount(this.id)" class="form-control requiredField" name="discount_amount[]" id="discount_amount<?php echo $Counter?>" placeholder="DISCOUNT" min="1" value="<?php echo $Fil->b_dis_amount?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount<?php echo $Counter?>" placeholder="NET AMOUNT" min="1" value="<?php echo $Fil->b_net; ?>" readonly>
                                                    </td>
                                                    <?php if($Counter > 1):?>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger" id="BtnRemove<?php echo $Counter?>" onclick="RemoveSection('<?php echo $Counter?>')"> - </button></td>

                                                    <?php else:?>
                                                    <td style="background-color: #ccc"></td>
                                                    <?php endif;?>
                                                </tr>
                                                <?php

                                                     endif;
                                                $Counter++;
                                                    endforeach;
                                                ?>
                                                </tbody>

                                                <tbody>
                                                <tr  style="font-size:large;font-weight: bold">
                                                    <td class="text-center" colspan="7">Total</td>
                                                    <td id="" class="text-right" colspan="1"><input readonly class="form-control" type="text" id="net" value="<?php echo $TotalAmount?>"/> </td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                    </div>
                                </div>
                                <table style="width: 40%;display: none" class="table table-bordered margin-topp table-">
                                    <thead>

                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="3">Sales Tax</td>
                                        <td colspan="3"><input readonly type="text" onkeyup="calculate_sales_tax()"  class="form-control" id="sales_percent" value="17"/> </td>
                                        <td><input readonly  class="form-control"  type="text" name="sales_tax" id="sales_tax" value="<?php echo $sales_order->sales_tax?>"/> </td>
                                        <td><label><input onclick="applicable()" class="form-control"  type="checkbox" checked name="sales_tax_applicable" id="sales_tax_applicable" value="0"/> Applicable </label></td>

                                    </tr>
                                    <tr>
                                        <td colspan="3">Further Sales Tax @3%</td>
                                        <td colspan="3"><input readonly type="text" id="sales_percent_other" onkeyup="calculate_sales_tax_other()" class="form-control" value="3"/> </td>
                                        <td><input readonly  class="form-control" type="text" id="sales_tax_further" name="sales_tax_further" id="sales_tax_further" value="<?php echo $sales_order->sales_tax_further?>"/> </td>
                                        <td><label><input onclick="applicable()"  class="form-control"  type="checkbox" checked name="sales_tax_further_applicable" id="sales_tax_further_applicable" value="0"/> Applicable </label></td>

                                    </tr>
                                    <tr>
                                        <?php $TotalTaxes = $sales_order->sales_tax+$sales_order->sales_tax_further;?>
                                        <td colspan="3">Total Sales Tax</td>
                                        <td colspan="3"> </td>
                                        <td><input style="font-weight: bold;font-size: x-large" readonly class="form-control" type="text" name="sales_total" id="sales_total" value="<?php echo $TotalTaxes;?>"/> </td>

                                    </tr>





                                    </tbody>
                                    </tr>
                                </table>
                                <div  class="form-group form-inline text-right">
                                    <label for="email">Total Before Tax </label>
                                    <input readonly type="text" class="form-control" id="total" value="{{ $TotalAmount - $totalTax  }}">
                                 </div>
                                 <div  class="form-group form-inline text-right hide">
                                    <label for="email">Total After Tax </label>
                                    <input readonly type="text" class="form-control" id="total_after_sales_tax" value="{{ $TotalAmount ?? '' }}">
                                 </div>

                                <table>
                                    <tr>

                                        <td style="text-transform: capitalize;" id="rupees"></td>
                                        <input type="hidden" value="" name="rupeess" id="rupeess1"/>
                                    </tr>
                                </table>
                                <input type="hidden" id="d_t_amount_1" >

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Addional Expenses</span>
                                    </div>
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
                                                <?php
                                                $AddionalCounter = 0;
                                                foreach($Addional as $AddFil):
                                                $AddionalCounter++;
                                                ?>
                                                <tr id='RemoveExpenseRow<?php echo $AddionalCounter?>'>
                                                    <td>
                                                        <select class='form-control requiredField select2' name='account_id[]' id='account_id<?php echo $AddionalCounter?>'>
                                                            <option value=''>Select Account</option>
                                                            <?php foreach($accounts as $Fil){?>
                                                            <option value='<?php echo $Fil->id?>' <?php if($Fil->id == $AddFil->acc_id):echo "selected"; endif;?>><?php echo $Fil->code.'--'.$Fil->name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type='number' name='expense_amount[]' id='expense_amount<?php echo $AddionalCounter?>' class='form-control requiredField' value="<?php echo $AddFil->amount?>">
                                                    </td>
                                                    <td class='text-center'>
                                                        <button type='button' id='BtnRemoveExpense<?php echo $AddionalCounter?>' class='btn btn-sm btn-danger' onclick='RemoveExpense("<?php echo $AddionalCounter?>")'> - </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
    </div>

    <script>

        var CounterExpense = '<?php echo $AddionalCounter?>';
        function AddMoreExpense()
        {
            CounterExpense++;
            $('#AppendExpense').append("<tr id='RemoveExpenseRow"+CounterExpense+"'>" +
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+CounterExpense+"'><option value=''>Select Account</option><?php foreach($accounts as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "<td>" +
                    "<input type='number' name='expense_amount[]' id='expense_amount"+CounterExpense+"' class='form-control requiredField'>" +
                    "</td>" +
                    "<td class='text-center'>" +
                    "<button type='button' id='BtnRemoveExpense"+CounterExpense+"' class='btn btn-sm btn-danger' onclick='RemoveExpense("+CounterExpense+")'> - </button>" +
                    "</td>" +
                    "</tr>");
            $('#account_id'+CounterExpense).select2();
        }
        function RemoveExpense(Row)
        {
            $('#RemoveExpenseRow'+Row).remove();
        }

        var Counter = '<?php echo $Counter?>';

        function AddMoreDetails()
        {
            Counter++;
            $('#AppnedHtml').append('<tr id="RemoveRows'+Counter+'">' +
                '<td><select onchange="get_uom('+Counter+')" name="item_id[]" id="sub_ic_des'+Counter+'" class="form-control select2">'+
		 '<option value="">Select</option>'+
		 '@foreach ( CommonHelper::get_all_subitem() as $row )'+
		 '<?php $uom =  CommonHelper::get_uom($row->id); ?>'+
		 '<option value="{{ $row->id.','.$uom }}">{{ $row->sub_ic }}</option>'+
		 '@endforeach'+
		'</select>'+
		'</td>'+
                    '<td>' +
                    '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id'+Counter+'" >' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" onkeyup="claculation('+Counter+')" onblur="claculation('+Counter+')" class="form-control zerovalidate" name="actual_qty[]" id="actual_qty'+Counter+'" placeholder="ACTUAL QTY">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" onkeyup="claculation('+Counter+')" onblur="claculation('+Counter+')" class="form-control" name="rate[]" id="rate'+Counter+'" placeholder="RATE">' +
                    '</td>' +
                    '<td>' +
                    '<input readonly type="text" class="form-control amount" name="amount[]" id="amount'+Counter+'" placeholder="AMOUNT">' +
                    '</td>' +
                    '<td style="width: 110px">'+
			'<select onchange="tax_percent(this.id)"  class="form-control" name="tax[]" id="tax_percent'+Counter+'">'+
			'<option value="0,0">Select</option>'+
			'@foreach (ReuseableCode::invoice_tax() as $row)'+
			'<option value="{{ $row->acc_id.','.$row->tax_rate }}">{{$row->tax_rate }}</option>'+
			'@endforeach'+
			 '</select>'+
	        '</td>'+
            '<td>'+
	        '<input readonly type="text"  class="form-control requiredField tax_amount" name="tax_amount[]" id="tax_amount'+Counter+'"  min="1" value="0.00">'+
	         '</td>'+
                    '<td>' +
                    '<input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount'+Counter+'" placeholder="NET AMOUNT">' +
                    '</td>' +
                    '<td class="text-center">' +
                    '<input onclick="view_history('+Counter+')" type="checkbox" id="view_history'+Counter+'">&nbsp;&nbsp;' +
                    '<button type="button" class="btn btn-sm btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')"> - </button>' +
                    '</td>' +
                    '</tr>');
            $('.select2').select2();

            var AutoCount=1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).prop('title', AutoCount);

            });
            $('.sam_jass').bind("enterKey",function(e){

                var check =(this.id).split('_');

                if ($('#product_'+check[1]).val()!='')
                {
                    alert('Bundles Selectd Against This');
                    return false;
                }
                $('#items').modal('show');


            });
            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");


                }

            });

            $('.sam_jass').bind("enterKeyy",function(e){


                $('#budles_dataa').modal('show');


            });

            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 113)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKeyy");


                }

            });


            $('.sami').bind("enterKey",function(e){


            $('#items_searc_for_bundless').modal('show');


            });
            $('.sami').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_idd=this.id;
                    $(this).trigger("enterKey");


                }

            });
        }
        function RemoveSection(Row) {
//            alert(Row);
            $('#RemoveRows' + Row).remove();
            //   $(".AutoCounter").html('');
            var AutoCount = 1;
            var AutoCount=1;;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).prop('title', AutoCount);
            });
            claculation(Row);
        }

        function view_history(id)
        {
            var v= $('#sub_'+id).val();
            if ($('#view_history' + id).is(":checked"))
            {
                if (v!=null)
                {
                    showDetailModelOneParamerter('sdc/sals_history?id='+v);
                }
                else
                {
                    alert('Select Item');
                }
            }
        }


    </script>
    <script>
        var x=0;


        $('.sam_jass').bind("enterKey",function(e){

            var check =(this.id).split('_');

            if ($('#product_'+check[1]).val()!='')
            {
                alert('Bundles Selectd Against This');
                return false;
            }
            $('#items').modal('show');


        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");


            }

        });

        $('.sam_jass').bind("enterKeyy",function(e){


            $('#budles_dataa').modal('show');


        });

        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 113)
            {
                selected_id=this.id;
                $(this).trigger("enterKeyy");


            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {

                e.preventDefault();
                return false;
            }
        });



        function net_amount()
        {
            console.log('in');
            var amount=0;
            $('.amount').each(function (i, obj) {

                amount += +$('#'+obj.id).val();
                console.log(obj.id);
            });
            amount=parseFloat(amount).toFixed(3);


            $('#net').val(amount);
            $('#total').val(amount);


            var net_amount=0;
            $('.net_amount_dis').each(function (i, obj) {

                net_amount += +$('#'+obj.id).val();
        });
        net_amount=parseFloat(net_amount).toFixed(3);
        $('#total_after_sales_tax').val(net_amount);


        }








        $(document).ready(function() {


            $(".btn-success").click(function(e){

                //alert();
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());



                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });
        });
        function removeSeletedPurchaseRequestRows(id,counter){
            var totalCounter = $('#totalCounter').val();
            if(totalCounter == 1){
                alert('Last Row Not Deleted');
            }else{
                var lessCounter = totalCounter - 1;
                var totalCounter = $('#totalCounter').val(lessCounter);
                var elem = document.getElementById('removeSelectedPurchaseRequestRow_'+counter+'');
                elem.parentNode.removeChild(elem);
            }

        }

        $(document).ready(function() {
//            toWords(1);
        });


        function claculation(number)
        {
            var  qty=$('#actual_qty'+number).val();
            var  rate=$('#rate'+number).val();

            var total=parseFloat(qty*rate).toFixed(2);

            $('#amount'+number).val(total);

            var amount = 0;
            count=1;
            $('.net_amount_dis').each(function (i, obj) {

                amount += +$('#'+obj.id).val();

                count++;
            });
            amount=parseFloat(amount);


            // sales_tax('sales_taxx');
            discount_percent('discount_percent'+number);
            net_amount();
            // sales_tax();
            //  toWords(1);
        }






    </script>
    <script>




        function discount_percent(id)
        {
            var  number= id.replace("discount_percent","");
            var amount = $('#amount' + number).val();

            var x = parseFloat($('#'+id).val());

            if (x >100)
            {
                alert('Percentage Cannot Exceed by 100');
                $('#'+id).val(0);
                x=0;
            }

            x=x*amount;
            var discount_amount =parseFloat( x / 100).toFixed(2);
            $('#discount_amount'+number).val(discount_amount);
            var discount_amount=$('#discount_amount'+number).val();

            if (isNaN(discount_amount))
            {

                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }



            var amount_after_discount=amount-discount_amount;

            $('#after_dis_amount'+number).val(amount_after_discount);
            var amount_after_discount=$('#after_dis_amount'+number).val();

            if (amount_after_discount==0)
            {
                $('#after_dis_amount'+number).val(amount);
                $('#net_amounttd_'+number).val(amount);
                $('#net_amount'+number).val(amount_after_discount);
            }

            else
            {

                $('#net_amounttd_'+number).val(amount_after_discount);
                $('#after_dis_amount'+number).val(amount_after_discount);
            }

            $('#cost_center_dept_amount'+number).text(amount_after_discount);
            $('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);


            // sales_tax('sales_taxx');
            net_amount();
            // sales_tax();
            //  toWords(1);


        }


        function discount_amount(id)
        {
            var  number= id.replace("discount_amount","");
            var amount=parseFloat($('#amount'+number).val());

            var discount_amount=parseFloat($('#'+id).val());

            if (discount_amount > amount)
            {
                alert('Amount Cannot Exceed by '+amount);
                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }

            if (isNaN(discount_amount))
            {

                $('#discount_amount'+number).val(0);
                discount_amount=0;
            }

            var percent=(discount_amount / amount *100).toFixed(2);
            $('#discount_percent'+number).val(percent);
            var amount_after_discount=amount-discount_amount;
            $('#after_dis_amount'+number).val(amount_after_discount);


            $('#net_amounttd_'+number).val(amount_after_discount);
            $('#net_amount_'+number).val(amount_after_discount);
            // sales_tax('sales_taxx');
            //   toWords(1);
            net_amount();
            // sales_tax();


        }


        function get_detail(id,number)
        {


            var item=$('#'+id).val();


            $.ajax({
                url:'{{url('/pdc/get_data')}}',
                data:{item:item},
                type:'GET',
                success:function(response)
                {

                    var data=response.split(',');
                    $('#uom_id'+number).val(data[0]);


                }
            })



        }
        $(".remove").each(function(){

            $(this).html($(this).html().replace(/,/g , ''));
        });
        function get_ntn()
        {
            var ntn=$('#ntn').val();
            ntn=ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            $('#model_terms_of_payment').val(ntn[3]);
            calculate_due_date();
            net_amount();
            // sales_tax();
        }



        function calculate_due_date()
        {

            var days=parseFloat($('#model_terms_of_payment').val())-1;
            var tt = document.getElementById('so_date').value;

            var date = new Date(tt);
            var newdate = new Date(date);
            newdate.setDate(newdate.getDate() + days);
            var dd = newdate.getDate();

            var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
            var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
            var y = newdate.getFullYear();
            var someFormattedDate =  + y+'-'+ mm +'-'+dd;

            document.getElementById('due_date').value = someFormattedDate;
        }
        function total_cal()
        {

            var total=	parseFloat($('#net').val());
            if (isNaN(total))
            {
                total=0;
            }

            if($("#sales_tax_applicable").prop('checked') == false)
            {
                total=0;
            }

            var sales_tax_percent=parseFloat($('#sales_percent').val());
            var sales_tax=((total/100)*sales_tax_percent).toFixed(2);
            $('#sales_tax').val(sales_tax);


            var strn= $('#buyers_sales').val();
            var total=	parseFloat($('#net').val());

            if($("#sales_tax_further_applicable").prop('checked') == false)
            {
                total=0;
            }

            if (strn=='')
            {
                var sales_tax_percent=parseFloat($('#sales_percent_other').val());
                var sales_tax_further=((total/100)*sales_tax_percent).toFixed(2);
                $('#sales_tax_further').val(sales_tax_further);

            }
            else
            {
                sales_tax_further=0;
                $('#sales_tax_further').val(0);
            }

            total_cal();


            toWords(1);
        }





        function applicable()
        {
            net_amount();
        }
        function get_uom(id)
        {
            var sub_ic_data = $('#sub_ic_des'+id).val();
            sub_ic_data = sub_ic_data.split(',');
            $('#uom_id'+id).val(sub_ic_data[1]);
        }
        function tax_percent(id)
        {
            var  number= id.replace("tax_percent","");
            var amount = parseFloat($('#amount' + number).val());

            var x = $('#'+id).val();

            x=x.split(',');
            x=parseFloat(x[1]);


            if (x >100)
            {
                alert('Percentage Cannot Exceed by 100');
                $('#'+id).val(0);
                x=0;
            }

            x=x*amount;
            var tax_amount =parseFloat( x / 100).toFixed(2);
            $('#tax_amount'+number).val(tax_amount);

            var tax_amount=parseFloat($('#tax_amount'+number).val());


            if (isNaN(tax_amount))
            {

                $('#tax_amount'+number).val(0);
                tax_amount=0;
            }



            var amount_after_discount=parseFloat(amount+tax_amount).toFixed(3);



            $('#after_dis_amount'+number).val(amount_after_discount);
            var amount_after_discount=$('#after_dis_amount'+number).val();

            if (amount_after_discount==0)
            {
                $('#after_dis_amount'+number).val(amount);
                $('#net_amounttd_'+number).val(amount);
                $('#net_amount'+number).val(amount_after_discount);
            }

            else
            {

                $('#net_amounttd_'+number).val(amount_after_discount);
                $('#after_dis_amount'+number).val(amount_after_discount);
            }

            $('#cost_center_dept_amount'+number).text(amount_after_discount);
            $('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);


        //	sales_tax('sales_taxx');
            net_amount();
        //  	sales_tax();
            //  toWords(1);


        }
    </script>




    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <?php }?>
@endsection
