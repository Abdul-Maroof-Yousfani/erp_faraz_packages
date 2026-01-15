<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')


<?php echo  CommonHelper::table_counting('purchase_request','purchase_request_status','1'); ?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('/site.css')}}">
    <link rel="stylesheet" href="{{url('/richtext.min.css')}}">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{url('/jquery.richtext.js')}}"></script>
    @include('select2')
    @include('number_formate')
    {{Form::open(array('url'=>'sad/updateQuotation'))}}

    <div class="">
        <div class="">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="stage2">  </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span class="subHeadingLabelClass">Quotation</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Quotation #<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" name="quotation_no" id="quotation_no" value="<?php echo $Quotation->quotation_no?>" class="form-control requiredField" placeholder="Quotation #">
                                                        <input type="hidden" name="editId" id="editId" value="<?php echo $EditId; ?>">
                                                    </div>

                                                    @if ($Quotation->type!=2)
                                                        <?php
                                                        $client_name=CommonHelper::get_client_name_by_id($Quotation->client_id);
                                                        $client_data=CommonHelper::get_client_data_by_id($Quotation->client_id)
                                                        ?>
                                                        <input type="hidden" name="job_tracking_no" value="{{$Quotation->tracking_no}}"/>
                                                        <input type="hidden" name="client_id" value="{{$Quotation->client_id}}"/>
                                                        <input type="hidden" name="branch_id" value="{{$Quotation->branch_id}}"/>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Client Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <input readonly type="text" name="client_name" id="client_name"  value="{{$client_name}}" class="form-control" placeholder="Client Name">
                                                        </div>
                                                            <input type="hidden" name="type" value="1">
                                                            <input type="hidden" name="tracking_no" value="<?php echo $Quotation->survey_id ?>">

                                                    @else
                                                        <input type="hidden" name="tracking_no" value="0">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Client. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select onchange="data_get()" class="form-control requiredField select2" id="client" name="client_id">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_clients() as $row)
                                                                    <option value="{{$row->id.'*'.$row->ntn.'*'.$row->strn.'*'.$row->address}}" <?php if($Quotation->client_id == $row->id){echo "selected";}?>>{{$row->client_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="type" value="2">
                                                    @endif

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">NTN #<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" name="ntn_no" id="ntn_no" value="@if($Quotation->type!=2){{$client_data->ntn}} @endif" class="form-control" placeholder="NTN #">
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">STRN #<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" name="strn_no" id="strn_no" value="@if($Quotation->type!=2){{$client_data->strn}}@endif" class="form-control" placeholder="STRN #">
                                                    </div>


                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Quotation To <span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input  type="text" name="quotation_to" id="quotation_to" value="<?php echo $Quotation->quotation_to?>" class="form-control requiredField" placeholder="">
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Designation<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input type="text" name="designation" id="designation" value="<?php echo $Quotation->designation?>" class="form-control requiredField" placeholder="Designation">
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Survey Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input @if($Quotation->type!=2) readonly @endif type="date" name="survey_date" id="survey_date" value="<?php echo $Quotation->surey_date?>" class="form-control">
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Quotation Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input type="date" name="quotation_date" id="quotation_date" value="<?php echo $Quotation->quotation_date?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Region <span class="rflabelsteric"><strong>*</strong></span></label>

                                                        @if ($Quotation->type==2):
                                                        <select class="form-control select2 requiredField" id="region_id" name="region_id">
                                                            <option>Select</option>
                                                            @foreach(CommonHelper::get_all_regions() as $row)
                                                                <option value="{{$row->id}}" <?php if($Quotation->region_id == $row->id){echo "selected";}?>>{{$row->region_name}}</option>
                                                            @endforeach

                                                        </select>
                                                        @else
                                                            <?php  $region_data=CommonHelper::get_rgion_name_by_id($Quotation->region_id);
                                                            $region_name=$region_data->region_name;
                                                            ?>
                                                            <input type="hidden" name="region_id" value="{{$Quotation->region_id}}"/>
                                                            <input readonly type="text" name="region" id="region" value="{{$region_name}}" class="form-control">
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Revise Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input type="date" name="reviewed_date" id="reviewed_date" value="<?php echo $Quotation->reevived_date?>" class="form-control">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Address<span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" name="address" id="address" value="@if($Quotation->type!=2){{$client_data->address}}@endif" class="form-control" placeholder="Address">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row" style="height: 100px">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div style="width: 100%" class="page-wrapper box-content">


            <textarea  class="content1" name="subject"><?php echo $Quotation->subject;?></textarea>

                                                </div>
                                            </div>

                                        </div>



                                        <div class="lineHeight">&nbsp;</div>
                                        <?php ?>
                                        <div class="well">
                                            <div class="">
                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <table id="buildyourform" class="table table-bordered">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="text-center" style="width: 10px;">Serial No<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center">Product Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center">Description<span class="rflabelsteric"></span></th>
                                                                        <th class="text-center" style="width: 112px">Height<span class="rflabelsteric"></span></th>
                                                                        <th class="text-center" style="width: 112px">Width<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center" style="width: 150px">Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center" style="width: 120px">Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center" style="width: 120px">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        <th class="text-center" style="width: 140px">Total Cost<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="TrAppend">
                                                                    <?php $count=1;

                                                                            $TotQty = 0;
                                                                            $TotRate = 0;
                                                                            $TotCost = 0;
                                                                    ?>


                                                                        @foreach($QuotationData as $row)

                                                                            <?php

                                                                            $product_data=CommonHelper::get_product_name_by_id($row->product_id);
                                                                            $product_name=$product_data->p_name;
                                                                            $description=$row->description;
                                                                            $height=$row->height;
                                                                            $width=$row->width;
                                                                            $qty=$row->qty;
                                                                            $rate=$row->rate;
                                                                            $total_cost=$row->total_cost;
                                                                                    $TotQty += $qty;
                                                                                    $TotRate += $rate;
                                                                                    $TotCost += $total_cost;
                                                                            ?>
                                                                            <input type="hidden" id="quotation_id" name="quotation_id[]" value="{{$row->id}}"/>
                                                                            <?php if($Quotation->type == 1):?>
                                                                            <tr class="text-center" id="Row_<?php echo $count?>">
                                                                                <input type="hidden" id="product_id" name="product_id[]" value="{{$row->product_id}}"/>
                                                                                <input type="hidden" name="survey_data_id[]"  value="{{$row->survey_data_id}}"/>
                                                                                <input type="hidden" name="tracking_no" value="<?php echo $Quotation->survey_id ?>">
                                                                                <input type="hidden" name="tracking_no" value="<?php echo $Quotation->survey_id ?>">
                                                                                <td>{{$count}}</td>
                                                                                <td><input readonly type="text" class="form-control" id="product_name_{{$count}}" value="{{$product_name}}" placeholder="Product Name"></td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-primary btn-xs btn-success" data-toggle="modal" data-target="#open<?php echo $count?>">Open Description</button>
                                                                                    <div class="modal fade" id="open{{$count}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Description</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body text-left">
                                                                                                    <div style="width: 100%;" class="page-wrapper box-content">


                                                                                                        <textarea readonly class="des{{$count}} text-left" name="descr[]"><?php echo $row->description?></textarea>
                                                                                                        <script !src="">
                                                                                                            $(document).ready(function(){
                                                                                                                $('.des'+'<?php echo $count?>').richText();
                                                                                                            });
                                                                                                        </script>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td><input type="text" class="form-control requiredField" name="height[]" value="{{$height}}" id="height_{{$count}}" placeholder="Height"></td>
                                                                                <td><input type="text" class="form-control requiredField" name="width[]" value="{{$width}}" id="width_{{$count}}" placeholder="Width"></td>
                                                                                <td><select onchange="calculate_height('{{$count}}')" class="form-control select2 requiredField" id="uom_{{$count}}" name="uom[]">
                                                                                        <option value="">select</option>
                                                                                        @foreach(CommonHelper::get_all_uom() as $urow)
                                                                                            <option value="{{$urow->id}}" <?php if($row->uom_id == $urow->id){echo "selected";}?>>{{$urow->uom_name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td><input type="text" onkeyup="calcuate('{{$count}}')" name="qty[]" class="form-control number qty requiredField" id="qty_{{$count}}" placeholder="" value="<?php echo $qty?>"></td>
                                                                                <td><input type="text" class="form-control number rate requiredField" name="rate[]" onkeyup="calcuate('{{$count}}')" id="rate_{{$count}}" placeholder="Rate" value="<?php echo $rate?>"></td>
                                                                                <td><input readonly type="text" class="form-control number cost requiredField" name="amount[]" id="total_cost_{{$count}}" placeholder="Total Cost" value="<?php echo $row->total_cost?>"></td>
                                                                            </tr>
                                                                            <?php else:?>
                                                                            <tr class="text-center" id="Row_<?php echo $count?>">
                                                                                <input type="hidden" name="survey_data_id[]"  value="0"/>
                                                                                <td><?php echo $count?></td>
                                                                                <td><select style="width: 100%" name="product_id[]"  id="product_<?php echo $count?>" class="form-control requiredField select2">
                                                                                        <option value="">Select Product</option>
                                                                                        <?php foreach(CommonHelper::get_all_products() as $prow):?>
                                                                                        <option value="<?php echo $prow->product_id?>"<?php if($row->product_id == $prow->product_id){echo "selected";}?>><?php echo $prow->p_name?></option>
                                                                                        <?php endforeach;?>
                                                                                    </select>
                                                                                </td>

                                                                                <td>
                                                                                    <button type="button" class="btn btn-primary btn-xs btn-success" data-toggle="modal" data-target="#open<?php echo $count?>">Open Description</button>
                                                                                    <div class="modal fade" id="open{{$count}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">Description</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <div class="modal-body text-left">
                                                                                                    <div style="width: 100%;" class="page-wrapper box-content">


                                                                                                        <textarea  class="des{{$count}}" name="descr[]"><?php echo $row->description?></textarea>
                                                                                                        <script !src="">
                                                                                                            $(document).ready(function(){
                                                                                                                $('.des'+'<?php echo $count?>').richText();
                                                                                                            });
                                                                                                        </script>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>

                                                                                <td><input type="text" name="height[]" class="form-control requiredField" id="height_<?php echo $count?>" placeholder="Height" value="<?php echo $height;?>"></td>

                                                                                <td><input type="text" name="width[]" class="form-control requiredField" id="width_<?php echo $count?>" placeholder="Width" value="<?php echo $width?>"></td>

                                                                                <td><select style="width: 100%" name="uom[]" id="uom_<?php echo $count?>" class="form-control requiredField">
                                                                                        <option value="">Select</option>
                                                                                        <?php foreach(CommonHelper::get_all_uom() as $urow):?>
                                                                                        <option value='<?php echo $urow->id?>' <?php if($row->uom_id == $urow->id){echo "selected";}?>><?php echo $urow->uom_name?></option>
                                                                                        <?php endforeach;?>
                                                                                    </select>
                                                                                </td>

                                                                                <td><input type="text" name="qty[]" class="form-control number qty requiredField" onkeyup="calcuate('<?php echo $count?>')" id="qty_<?php echo $count?>" placeholder="" value="<?php echo $qty?>"></td>
                                                                                <td><input type="text" name="rate[]" class="form-control number rate requiredField" id="rate_<?php echo $count?>" onkeyup="calcuate('<?php echo $count?>')" placeholder="Rate" value="<?php echo $rate?>"></td>
                                                                                <td><input readonly name="amount[]" type="text" class="form-control number cost requiredField" id="total_cost_<?php echo $count?>" placeholder="Total Cost" value="<?php echo $row->total_cost?>"></td>

                                                                                </tr>
                                                                            <?php

                                                                            endif;?>
                                                                            <?php $count++; ?>
                                                                            @endforeach


                                                                            {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">--}}
                                                                            {{--Launch demo modal--}}
                                                                            {{--</button>--}}

                                                                                    <!-- Modal -->

                                                                    </tbody>
                                                                    <tr style="background-color: darkgray">
                                                                        <td class="text-center" colspan="6"><b style="font-size: larger;font-weight: bold">Total Cost</b></td>
                                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_qty" class="form-control number" value="<?php echo $TotQty;?>"/> </td>
                                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_rate" class="form-control number" value="<?php echo $TotRate;?>"/> </td>
                                                                        <td><input readonly style="font-size: larger;font-weight: bold" type="text" id="total_amount" class="form-control number" value="<?php echo $TotCost;?>"/> </td>
                                                                    </tr>
                                                                </table>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreRows()" value="Add More Row">
                                                                        {{--<button type="button" class="btn btn-sm btn-danger" id="BtnRemove" onclick="RemoveRows()">Remove Rows</button>--}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered">

                                                                                    {{--<tr>--}}
                                                                                    {{--<th>Discount %</th>--}}
                                                                                    {{--<th>Discount Amount</th>--}}
                                                                                    {{--</tr>--}}

                                                                                    <tr>
                                                                                        <td><b>Trade Discount</b></td>
                                                                                        <td><input type="text" class="form-control number" onkeyup="discount_calc()" id="discount_percent" name="discount_percent" placeholder="Discount Percent" value="<?php echo $Quotation->discount_percent?>"/></td>
                                                                                        <td class="text-right"><input type="text" class="form-control number" onkeyup="discount_percent_calc()" name="discount_amount" placeholder="Discount Amount" id="discount_amount" value="<?php echo $Quotation->discount_amount?>"/> </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan=""><b>Total Cost After Trade Discount</b></td>
                                                                                        <td></td>
                                                                                        <td class="text-right"><input name="discount" readonly  style="font-size: larger;font-weight: bold" readonly type="text" class="form-control number" id="discount" /> </td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td><b>Sales Tax</b></td>
                                                                                        <td><input type="text" class="form-control number" onkeyup="sales_tax_calc()" id="sales_tax_percent" name="sales_tax_percent" placeholder="Sales Tax Percent" value="<?php echo $Quotation->sales_tax_percent?>" /></td>
                                                                                        <td class="text-right"><input onkeyup="sales_tax_percent_calc()" type="text" class="form-control number" name="sales_tax_amount" placeholder="Sales Tax Amount" id="sales_tax_amount" value="<?php echo $Quotation->sales_tax_amount?>" /> </td>
                                                                                    </tr>
                                                                                    <tr style="background-color: darkgray">
                                                                                        <td colspan="2"><b>Nte Amount</b></td>
                                                                                        <td class="text-right"><input style="font-size: larger;font-weight: bold"  type="text" class="form-control number" id="net_amount" /> </td>
                                                                                    </tr>
                                                                                    {{--<tr>--}}
                                                                                    {{--<th>Total Cost After Trade Discount</th>--}}
                                                                                    {{--<th></th>--}}
                                                                                    {{--<td colspan="2" class="text-right"><input type="text" class="form-control number" id="discount" /></td>--}}
                                                                                    {{--</tr>--}}
                                                                                    {{--<tr>--}}
                                                                                    {{--<th>Sales Tax</th>--}}
                                                                                    {{--<td><input type="text" class="form-control number" id="sales_tax_percent" /></td>--}}
                                                                                    {{--<td class="text-right"><input type="text" class="form-control number" id="sales_tax" /></td>--}}
                                                                                    {{--</tr>--}}
                                                                                    {{--<tr>--}}
                                                                                    {{--<th>Total Cost After Trade Discount</th>--}}
                                                                                    {{--<th></th>--}}
                                                                                    {{--<td colspan="2" class="text-right"><input type="text" class="form-control number" id="net_amount" /></td>--}}
                                                                                    {{--</tr>--}}
                                                                                </table>
                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="page-wrapper box-content" style="width: 100%">
                                                                            <label>Other Terms And COndition</label>

            <textarea style="" class="content" name="terms"><?php echo $Quotation->other_terms_conditions?></textarea>

                                                                        </div>
                                                                    </div>

                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <input type="submit" class="btn btn-sm btn-success" id="BtnSubmit" value="Submit">
                                    <?php //echo  Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) ?>
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    <!-- <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" /> -->
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>

    {{Form::close()}}
    <script src="<?php echo URL('/assets/textEditor')?>/editor.js"></script>
    <script>
        $(document).ready(function()
        {

//            $('.content').richText();
//
//            $("#txtEditor").Editor();
//            $('#tracking_no').select2();


        });

    </script>
    <script>


        function data_get()
        {

            var client_data= $('#client').val();

            client_data=client_data.split('*');
            $('#ntn_no').val(client_data[1]);
            $('#strn_no').val(client_data[2]);
            $('#address').val(client_data[3]);
        }


        function GetTrackingSheet() {

            var TrackNo = $('#tracking_no').val();
            var m = '<?php echo $_GET['m'];?>';
            if(TrackNo !="")
            {
                $('#TrackNoError').html('');
                $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/sdc/getQuatationForm',
                    type: "GET",
                    data: { TrackNo:TrackNo,m:m},
                    success:function(data) {
//                        alert(data); return false;
                        $('#ShowHide').html('');
                        $('#ShowHide').html(data);
                    }
                });
            }
            else{
                $('#TrackNoError').html('<p class="text-danger">Please Select Tracking #</p>');
            }

        }

        $("#formid").submit(function(event){
            event.preventDefault();
            if (confirm("Want to Add Data...?")) {
                $('#ShowHide').css('display','none');
                $('#ShowHide2').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                var post_url = $(this).attr("action"); //get form action url
                var form_data = $(this).serialize(); //Encode form elements for submission
                $.ajax({
                    url: post_url,
                    type: "POST",
                    data: form_data,
                    success: function (data) {
                        $('#ShowHide').css('display','block');
                        $('#ShowHide2').html('');

                    }
                });

            } else {
                alert("You didn't submit the form");
            }
        });

    </script>
    <script !src="">

        var CounterRow = '{{$count}}';
        function AddMoreRows()
        {
            CounterRow++;
            $('#TrAppend').append('<tr class="text-center" id="Row_'+CounterRow+'">' +
                    '<input type="hidden" name="survey_data_id[]"  value="0"/>' +
                    '<td>'+CounterRow+'</td>'+
                    '<td><select style="width: 100%" name="product_id[]"  id="product_'+CounterRow+'" class="form-control requiredField select2"><option value="">Select Product</option><?php
                foreach(CommonHelper::get_all_products() as $row):?><option value="<?php echo $row->product_id?>"><?php echo $row->p_name?></option><?php endforeach;?></select></td>'+

                    '<td> <button type="button" class="btn btn-primary btn-xs btn-success" data-toggle="modal" data-target="#open'+CounterRow+'">Open Description</button>' +
                    '<div class="modal fade" id="open'+CounterRow+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">'+
                    '<div class="modal-dialog" role="document">'+
                    '<div class="modal-content">'+
                    '<div class="modal-header">'+
                    '<h5 class="modal-title" id="exampleModalLabel">Description</h5>'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                    '</button>'+
                    '</div>'+
                    '<div class="modal-body text-left">'+
                    '<div style="width: 100%;" class="page-wrapper box-content">'+
                    '<textarea  class="des'+CounterRow+'" name="descr[]"></textarea>'+
                    '</div>'+
                    '</div>'+
                    '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'+
                    '<button type="button" class="btn btn-primary">Save changes</button>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</td>'+

                    '<td><input type="text" name="height[]" class="form-control requiredField" id="height_'+CounterRow+'" placeholder="Height"></td>'+
                      '<input type="hidden" id="quotation_id" name="quotation_id[]" value="0"/>'  +
                    '<td><input type="text" name="width[]" class="form-control requiredField" id="width_'+CounterRow+'" placeholder="Width"></td>'+

                    '<td><select style="width: 100%" name="uom[]" id="uom_'+CounterRow+'" class="form-control requiredField"><option value="">Select'+
                    "</option><?php foreach(CommonHelper::get_all_uom() as $row):?><option value='<?php echo $row->id?>'><?php echo $row->uom_name?></option><?php endforeach;?></select></td>"+

                    '<td><input type="text" name="qty[]" class="form-control number qty requiredField" onkeyup="calcuate('+CounterRow+')" id="qty_'+CounterRow+'" placeholder=""></td>'+
                    '<td><input type="text" name="rate[]" class="form-control number rate requiredField" id="rate_'+CounterRow+'" onkeyup="calcuate('+CounterRow+')" placeholder="Rate"></td>'+
                    '<td><input readonly name="amount[]" type="text" class="form-control number cost requiredField" id="total_cost_'+CounterRow+'" placeholder="Total Cost"></td>' +

                    '</tr>');
            $('.des'+CounterRow).richText();
            $('#uom_'+CounterRow).select2();

            $('#product_'+CounterRow).select2();
            $('.number').number(true,2);


        }
        //    function RemoveRows()
        //    {
        //        if(CounterRow>1)
        //        {
        //            $('#Row_'+CounterRow).remove();
        //        }
        //        CounterRow-1;
        //    }

        $('document').ready(function()
        {
            $('.content').richText();
            $('.content1').richText();
            $('.content2').richText();

            $('.select2').select2();

            $('.number').number(true,2);
            $('textarea').each(function(){
                        $(this).val($(this).val().trim());
                    }
            );
        });

        function calcuate(number)
        {
            var qty=  $('#qty_'+number).val();
            var rate=  $('#rate_'+number).val();
            var uom=$('#uom_'+number).val();
//        if (uom==15)
//        {
//            var height=  $('#height_'+number).val();
//            var width=  $('#width_'+number).val();
//            qty=height*width;
//            $('qty_'+number).val(qty);
//
//
//        }
            var total=qty*rate;


            $('#total_cost_'+number).val(total);
            discount_calc();
            sales_tax_calc();
            total_cost();
        }
        $(document).ready(function(){
            discount_calc();
            sales_tax_calc();
            total_cost();
        });

        function total_cost()
        {

            // for amount
            var total_cost=0;
            var count=1;
            $('.cost').each(function()
            {
                total_cost+=+$('#total_cost_'+count).val();
                count++;
            });

            // for rate
            $('#total_amount').val(total_cost);

            var total_rate=0;
            var counter_rate=1;
            $('.rate').each(function()
            {
                total_rate+=+$('#rate_'+counter_rate).val();
                counter_rate++;
            });
            $('#total_rate').val(total_rate);

            // for qty

            var total_qty=0;
            var counter_qty=1;
            $('.qty').each(function()
            {
                total_qty+=+$('#qty_'+counter_qty).val();
                counter_qty++;
            });

            $('#total_qty').val(total_qty);

            var total_amount=  parseFloat($('#total_amount').val());
            var total_discount=  parseFloat($('#discount_amount').val());
            var after_trade_discount=total_amount-total_discount;
            $('#discount').val(after_trade_discount);

            var sales_tax_amount=  parseFloat($('#sales_tax_amount').val());
            var total=total_amount-total_discount;
            total=total+sales_tax_amount;
            //  alert(total_amount+' '+total_discount+' '+sales_tax_amount);
            $('#net_amount').val(total);

        }


        function discount_calc()
        {
            var total=	parseFloat($('#total_amount').val());
            var discount_percent=parseFloat($('#discount_percent').val());
            var discount_amount=(total/100)*discount_percent;
            $('#discount_amount').val(discount_amount);
            //  var after_discount=total-discount_amount;
            //  $('#discount').val(after_discount);
            total_cost();
        }

        function discount_percent_calc()
        {
            var total=	parseFloat($('#total_amount').val());
            var discount_amount=parseFloat($('#discount_amount').val());
            var discount_percent=(discount_amount/total)*100;
            $('#discount_percent').val(discount_percent);
            total_cost();
        }

        function sales_tax_calc()
        {
            var total=	parseFloat($('#discount').val());
            var sales_tax_percent=parseFloat($('#sales_tax_percent').val());
            var sales_tax_amount=(total/100)*sales_tax_percent;
            $('#sales_tax_amount').val(sales_tax_amount);
            total_cost();
        }

        function sales_tax_percent_calc()
        {
            var total=	parseFloat($('#discount').val());
            var sales_tax_amount_amount=parseFloat($('#sales_tax_amount').val());
            var sales_tax_percent_percent=(sales_tax_amount_amount/total)*100;
            $('#sales_tax_percent').val(sales_tax_percent_percent);
            total_cost();
        }
        function calculate_height(number)
        {
            var uom= $('#uom_'+number).val();

            if (uom==15)
            {
                var height=  $('#height_'+number).val();
                var width=  $('#width_'+number).val();
                var  qty=height*width;
                $('#qty_'+number).val(qty);


            }
        }

        function remove_row(Count)
        {
            $('#Row_'+Count+'').remove();

        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection