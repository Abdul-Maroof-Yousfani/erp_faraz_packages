@extends('layouts.default')
<?php
use App\Helpers\CommonHelper;
$total_amount = 0;
?>
@section('content')
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; View Sales Order</h3>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row" id="printReport">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="headquid">
                                                <h1 class="subHeadingLabelClass"> Sale Order</h1>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Sale Order No</label>
                                                    <div class="col-sm-8">{{ $sale_orders[0]->so_no }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Sale Order Date</label>
                                                    <div class="col-sm-8">{{ CommonHelper::changeDateFormat($sale_orders[0]->so_date) }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Purchase Order No</label>
                                                    <div class="col-sm-8">{{ $sale_orders[0]->purchase_order_no }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Purchase Order Date</label>
                                                    <div class="col-sm-8">{{ CommonHelper::changeDateFormat($sale_orders[0]->purchase_order_date) }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Customer</label>
                                                    <div class="col-sm-8">{{ $sale_orders[0]->customer_name }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Sales Tax Group</label>
                                                    <div class="col-sm-8">{{ $sale_orders[0]->sales_tax_group }}</div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-4 control-label">Sales Tax rate</label>
                                                    <div class="col-sm-8">{{ $sale_orders[0]->sales_tax_rate }}</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2"></div>
                                            <div class="col-md-4 box-s">
                                                <div class="">
                                                    <h1 class="subHeadingLabelClass">Other Details</h1>
                                                </div>
                                                <div class="padt">
                                                    <ul class="sale-l">
                                                        <li>Total Sales</li>
                                                        <li class="text-right">00.0</li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Total Payments</li>
                                                        <li class="text-right">00.0</li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Received Refunds</li>
                                                        <li class="text-right">00.0</li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Last payment receiving date</li>
                                                        <li class="text-right"> 00.0</li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Current Sale Order total</li>
                                                        <li class="text-right" id="grand_total_top"> 00.0</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 ">
                                            <h1 class="subHeadingLabelClass">Sale Order Details</h1>
                                            <div>
                                                <table class="table">
                                                    <tr>
                                                        <th class="text-center">Product</th>
                                                        <th class="text-center">Item Code</th>
                                                        <th class="text-center">Thickness</th>
                                                        <th class="text-center">Diameter</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Rate</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">Printing</th>
                                                        <th class="text-center">Special Instruction</th>
                                                        <th class="text-center">Delivery Date</th>
                                                        <th class="text-center">Total</th>
                                                    </tr>
                                                    <tbody id="more_details">
                                                    @foreach($sale_orders as $key => $value)
                                                            <?php
                                                            $total_amount += $value->amount;
                                                            ?>
                                                        <tr>
                                                            <td>{{ $value->sub_category_name }}</td>
                                                            <td>{{ $value->item_code }}</td>
                                                            <td class="text-right">{{ $value->thickness }}</td>
                                                            <td class="text-right">{{ $value->diameter }}</td>
                                                            <td class="text-right">{{ number_format($value->qty) }}</td>
                                                            <td class="text-right">{{ number_format($value->rate, 2) }}</td>
                                                            <td>{{ $value->uom_name }}</td>
                                                            <td>{{ $value->printing }}</td>
                                                            <td>{{ $value->special_instruction }}</td>
                                                            <td class="text-center">{{ CommonHelper::changeDateFormat($value->delivery_date) }}</td>
                                                            <td class="text-right">{{ number_format($value->amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach()
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 padt">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4">
                                                <div class="padt">
                                                    <ul class="sale-l">
                                                        <li>Total Amount</li>
                                                        <li class="text-right">{{ number_format($total_amount, 2) }}</li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Total Tax</li>
                                                        <li class="text-right">
                                                            <?php
                                                            $total_tax = ($total_amount / 100) * $sale_orders[0]->sales_tax_rate;
                                                            ?>
                                                            {{ number_format($total_tax, 2) }}
                                                        </li>
                                                    </ul>
                                                    <ul class="sale-l">
                                                        <li>Total Amount With Tax</li>
                                                        <li class="text-right">{{ number_format($total_amount + $total_tax, 2) }}</li>
                                                    </ul>
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
@endsection