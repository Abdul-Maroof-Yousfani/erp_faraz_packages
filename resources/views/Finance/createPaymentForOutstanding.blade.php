<?php
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>
@extends('layouts.default')

@section('content')

    @include('select2')
    @include('number_formate')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create  Payment Voucher Form  <?php


                                        $vall= implode(",",$val); $vall;


                                        ?></span>
                                </div>
                            </div>

                            <?php



                            $supplier_id=CommonHelper::get_supplier_id_from_purchase_voucher($val[0],$type);
                            $supplier_idd= explode(',',$supplier_id);
                            $supplier_id= $supplier_idd[0];
                            $supplier_acc_id=  CommonHelper::get_supplier_acc_id($supplier_id);
                            $total_net_amount= CommonHelper::get_purchase_net_amount($vall,$type);



                            $register=CommonHelper::get_supp_income_tax_detail($supplier_id);
                            $ntn=explode(',',$register);
                            $ntn_no=$ntn[0];
                            $filer_status=$ntn[1];
                            $business_type=$ntn[2];
                            $register_sales_tax=$ntn[3];
                            $strn=$ntn[4];
                            if ($strn==''):
                                $strn='No STRN Found';
                            else:
                                $strn=$ntn[4];
                            endif;

                            ?>


                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/addBankPaymentVoucherDetail?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                            
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="type" value="3"/>
                                <input type="hidden" name="purchase_id" value="{{$vall}}"/>




                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Bank Payment Voucher</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input  checked  type="checkbox" class="" value="1" name="payment_mod"  />
                                                        </div>


                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 panel">
                                                            <label class="sf-label">Ref / Bill No.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <?php foreach (CommonHelper::get_purchase_detail($vall,$type) as $row):
                                                                echo '<p>'.$row->slip_no.' '.'</p>';?>


                                                        <?php endforeach;?>

                                                            <textarea name="slip_no_1" style="display: none"><?php foreach (CommonHelper::get_purchase_detail($vall,$type) as $row):echo '<p>'.$row->slip_no.'</p>';?><?php endforeach;?></textarea>
                                                        </div>

                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 panel">
                                                            <label class="sf-label">Amount</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <?php foreach (CommonHelper::get_purchase_detail($vall,$type) as $row):
                                                                echo '<p>'.number_format($row->total_net_amount,2).'</p>';?>


                                                        <?php endforeach;?>

                                                            <textarea name="slip_no_1" style="display: none"><?php foreach (CommonHelper::get_purchase_detail($vall,$type) as $row):echo '<p>'.$row->slip_no.' '.'</p>';?><?php endforeach;?></textarea>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">PV Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input autofocus  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_1" id="pv_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                        </div>
                                                        <input type="hidden" name="business_type" id="business_type" value="{{$business_type}}"/>



                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
                                                            <label class="sf-label">Cheque No.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input  type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
                                                            <label class="sf-label">Cheque Date.</label>
                                                            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                            <input  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                        </div>

                                                    </div>
                                                </div>

                                              <?php
                                                $previous_sales_tax=0;
                                                $all_debit=0;
                                                if ($type==1):
                                                foreach (CommonHelper::get_purchase_detail($vall,$type) as $row):

                                                if ($row->through_advance==1):
                                                    $payment_id=$row->payment_id;;
                                                ?>
                                                <div><style>
                                                        table, th, td {
                                                            border: 1px solid black;
                                                        }
                                                    </style>
                                                    </head>
                                                    <body>
                                                    <p> (Advanced)  </p>
                                                    <table width="400">
                                                        <tr>
                                                            <th>Account</th>
                                                            <th style="width: 100px">Debit</th>
                                                            <th style="width: 100px">Credit</th>
                                                        </tr>
                                                        <?php
                                                        $total_debit=0;
                                                        $total_credit=0;

                                                        ?>
                                                        @foreach(CommonHelper::get_pv_detail_for_outstanding($payment_id) as $row)
                                                        <tr>
                                                            <td>{{CommonHelper::get_account_name($row->acc_id)}}</td>
                                                            <td class="text-right">@if($row->debit_credit==1) {{number_format($row->amount,2)}} <?php $total_debit+=$row->amount; $all_debit+=$row->amount;; ?> @endif</td>
                                                            <td class="text-right">@if($row->debit_credit==0) {{number_format($row->amount,2)}} <?php $total_credit+=$row->amount;  ?> @endif</td>
                                                            <?php if ($row->srb==1):$previous_sales_tax=$row->amount; else:  $previous_sales_tax=0; endif; ?>
                                                        </tr>
                                                        @endforeach
                                                        <tr style="background-color: darkgray">
                                                            <td>Total</td>
                                                            <td class="text-right">{{number_format($total_debit,2)}}</td>
                                                            <td class="text-right">{{number_format($total_credit,2)}}</td>
                                                        </tr>
                                                    </table></div>

                                                <?php endif; endforeach; endif;?>
                                                <?php  $income_txt_data=CommonHelper::purchas_voucher_data_for_income_txt_calculation($vall,$type);

                                                if(!empty($income_txt_data)):
                                                ?>
                                                <div class="container-fluid">
                                                    <h4>Income Tax Withholding</h4>
                                                    <div class="well">-

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                                                                <label class="radio-inline">
                                                                    <input onclick="ntn_cnic(1)" type="radio" name="optradio" id="business" value="1"> Applicable
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input checked onclick="ntn_cnic('2')" type="radio" name="optradio" id="company" value="2">Non  Applicable
                                                                </label>

                                                            </div>

                                                            <div id="filer_statys_div" style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">





                                                                <label class="radio-inline">
                                                                    <input @if($filer_status==2) checked @endif    type="radio" name="filer_nonfiler" id="filer3" value="1">Filer
                                                                </label>


                                                                <label class="radio-inline">
                                                                    <input @if($filer_status==1) checked @endif   type="radio" name="filer_nonfiler" id="filer4" value="2">Non Filer
                                                                </label>
                                                            </div>

                                                            <div style="display: none" id="ntn_div" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <input style="color: red" readonly class="form-control" value="{{$ntn_no}}" type="text" name="ntn" id="ntn" placeholder="" />

                                                            </div>
                                                            <div style="display: none" id="filer_check" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <input  id="check_status" type="button" onclick="check_statuss('<?php echo $ntn_no.','.$supplier_id ?>')" class="btn-primary" value="Check Status"/>
                                                                </div>

                                                        </div>

                                                        <div class="row">
                                                            &nbsp;&nbsp;
                                                        </div>

                                                        <?php

                                                        $count=1;
                                                        foreach($income_txt_data as $data):
                                                        if ($data->income_txt_nature!=0):

                                                        ?>
                                                        <div class="row">




                                                            <div id="payment_mod_div<?php echo $count; ?>" style="display: none"  class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">

                                                                <select  style="width: 100%" onchange="" id="payment_mod<?php echo $count; ?>"  class="form-control select2">
                                                                    <option  value="0" style="color: red">SELECT</option>

                                                                    <option value="1">ALL GOODS</option>
                                                                    <option value="2">IN CASE OF RICE,COTTON,SEED,EDIBLE OIL</option>
                                                                    <option value="3">DISTRIBUTORS OF FAST MOVING CONSUMER GOODS</option>
                                                                    <option value="4">SERVICES</option>
                                                                    <option value="5">TRANSPORT SERVICES</option>
                                                                    <option value="6">ELECTRONIC AND PRINT MEDIA FOR ADVERTISING</option>
                                                                    <option value="7">CONTRACTS</option>
                                                                    <option value="8">SPORT PERSON</option>
                                                                    <option value="9">Services of Stitching , Dyeing , Printing , Embroidery etc</option>


                                                                </select>
                                                                <input type="hidden" class="nature<?php echo $count; ?>" value="{{$data->amount-$all_debit}}"/>

                                                            </div>


                                                            <div style="display: none;" id="submit" class="col-lg-1 col-md-1 col-sm-1 col-xs-12 payment_mod_div">
                                                                @if($count==1)
                                                                    <input  id="btn_cal" type="button" onclick="calculation_text({{$count}})" class="btn-primary" value="Calculate"/>
                                                                @endif
                                                            </div>





                                                            <div style="display: none;"  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">

                                                                <p class=""> <?php echo  number_format($data->amount-$all_debit,2).' '; echo '('.CommonHelper::get_income_txt_nature($data->income_txt_nature).')' ?></p>
                                                            </div>




                                                            <div style=""  class="col-lg-5 col-md-5 col-sm-5 col-xs-12 payment_mod_div">

                                                                <p style="color: red" id="percent_cal<?php echo $count; ?>" class=""> </p>
                                                            </div>
                                                        </div>

                                                        <?php  $count++; endif;   endforeach ?>
                                                        <input type="hidden" id="count" value="{{$count-1}}">

                                                    </div>
                                                </div>

                                                <?php endif; ?>



                                                <?php    $fbr=CommonHelper::purchas_voucher_data_fbr_srb_pra_calculation($vall,1);


                                                $array=explode(",",$fbr);
                                                ?>
                                                @if ($fbr>0)
                                                    @include('Finance.fbr')
                                                @endif


                                                <?php    $srb=CommonHelper::purchas_voucher_data_fbr_srb_pra_calculation($vall,2);

                                                    $array=explode(",",$srb);
                                                ?>

                                            @if (!empty($srb>0))
                                                    @include('Finance.srb')
                                                @endif
                                                <?php    $pra=CommonHelper::purchas_voucher_data_fbr_srb_pra_calculation($vall,3);

                                                $array=explode(",",$pra);?>
                                                @if (!empty($pra>0))

                                                @include('Finance.pra')
                                                @endif









                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="well">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive">
                                                                    <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                                                                            </th>
                                                                            <th class="text-center" style="width:150px;">Current Bal<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                                        <?php  for($j = 1 ; $j <= 2 ; $j++){?>
                                                                        <input type="hidden" name="pvsDataSection_1[]" class="form-control requiredField" id="pvsDataSection_1" value="<?php echo $j?>" />
                                                                        <tr class="myclass">
                                                                            <td>


                                                                                <select onselect="fo(this.id)"  @if($j==1) disabled @endif   onchange="get_current_amount(this.id)" class="form-control select2"
                                                                                        @if($j>1)  name="account_id_1_<?php echo $j?>"@endif id="account_id_1_<?php echo $j?>">
                                                                                    <option value="">Select Account</option>
                                                                                    @foreach($accounts as $key => $y)
                                                                                        <option @if($j==1)  @if ($supplier_acc_id==$y->id) selected  @endif @endif
                                                                                     
                                                                                        value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <input type="hidden" name="account_id_1_1" value="<?php echo $supplier_acc_id ?>">
                                                                            </td>
                                                                            <?php 	 ?>
                                                                            <td>
                                                                                <input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $j ?>"  value="" required="required"/>
                                                                            </td>

                                                                            <input type="hidden" id="current_amount_hidden<?php echo $j ?>"/>
                                                                            <script>
                                                                                get_current_amount('<?php echo $y->id ?>');
                                                                            </script>
                                                                            <td>
                                                                      <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit"
                                                                       class="form-control  d_amount_1" maxlength="15" min="0" type="text" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')"
                                                                      value="@if($j==1) {{$total_net_amount-$all_debit}} @endif"/>
                                                                            </td>

                                                                            <td>
                                                                                <input disabled  onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control @if($j>1) requiredField @endif c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'0')"value="@if($j==2) {{$total_net_amount-$all_debit,2}} @endif"/>
                                                                            </td>
                                                                            <td class="text-center">---</td>

                                                                        </tr>


                                                                        <?php  }?>
                                                                        <?php $wihholdings = array("income_tax_1", "income_tax_2", "sales_tax_fbr_3",'sales_tax_srb_4','sales_tax_pra_5'); ?>
                                                                            @for($i=0; $i<=4; $i++)
                                                                        <tr style="" class="taxes" id="tax{{$i}}">
                                                                            <input type="hidden" name="pvsDataSection_1[]" class="form-control requiredField" id="pvsDataSection_1" value="<?php echo $j?>" />
                                                                            <td>


                                                                                <select   style="width: 100%"   onchange="get_current_amount(this.id)" class="form-control select2"
                                                                                        name="account_id_1_<?php echo $j?>" id="{{$wihholdings[$i]}}">
                                                                                    <option value="">Select Account</option>
                                                                                    @foreach($accounts as $key => $y)
                                                                                        <option @if($j==1)  @if ($supplier_acc_id==$y->id) selected  @endif @endif value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                                                    @endforeach
                                                                                </select>

                                                                            </td>
                                                                            <?php  ?>
                                                                            <td>
                                                                                <input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $j ?>"  value="" required="required"/>
                                                                            </td>

                                                                            <input type="hidden" id="current_amount_hidden<?php echo $j ?>"/>
                                                                            <script>
                                                                                get_current_amount('<?php echo $y->id ?>');
                                                                            </script>
                                                                            <td>
                                                                                <input readonly    onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1" maxlength="15" min="0" type="text" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')"
                                                                                       value="" required="required"/>
                                                                            </td>
                                                                            <script></script>
                                                                            <td>
                                                                                <input  onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control @if($j>1) @endif c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'0')"value="@if($j==2) {{$total_net_amount,2}} @endif"/>
                                                                            </td>
                                                                            <td class="text-center">

                                                                                <button type="button"  onclick="Rmove_tax('<?php echo $i ?>','<?php echo  $j ?>')" class="">Remove</button>
                                                                                <p style="color: red;font-size:small" class="perc<?php echo $j ?>"/>

                                                                            </td>
                                                                        </tr>
                                                                                <?php $j++; ?>
                                                                                @endfor
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td style="width:150px;">
                                                                                <input
                                                                                        type="text"
                                                                                        readonly="readonly"
                                                                                        id="d_t_amount_1"
                                                                                        maxlength="15"
                                                                                        min="0"
                                                                                        name="d_t_amount_1"
                                                                                        class="form-control requiredField text-right"
                                                                                        value=""/>
                                                                            </td>
                                                                            <td style="width:150px;">
                                                                                <input
                                                                                        type="text"
                                                                                        readonly="readonly"
                                                                                        id="c_t_amount_1"
                                                                                        maxlength="15"
                                                                                        min="0"
                                                                                        name="c_t_amount_1"
                                                                                        class="form-control requiredField text-right"
                                                                                        value=""/>
                                                                            </td>
                                                                            <td class="" id="" style="width:150px;font-size: 20px;"><input  type="text" name="diff" id="diff" class="form-control diff"></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td colspan="12" style="font-size: 15px;color: navy;" id="rupees"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                    <input  type="button" class="btn btn-sm btn-primary" onclick="addMorePvsDetailRows('1')" value="Add More PV's Rows" />
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                        <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pvsSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <!--
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

										<input type="button" class="btn btn-sm btn-primary addMorePvs" value="Add More PV's Section" />
										<!-->
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    ?>
    <script>


        function calculation_text(number){


            var filer=0;
            var nature= $('#payment_mod'+number).val();
            //  nature= String(nature);
            //  var array=nature.split(",");


            var nature_name=$("#payment_mod"+number+" :selected").text();


            var business=$('#business_type').val();
            if($('#filer3').prop("checked") == true)
            {
                filer=2;

            }

            if($('#filer4').prop("checked") == true)
            {
                filer=1;

            }


            if (filer==0)
            {
                alert('Required All Information');
                return false;
            }

            var count=$('#count').val();
            for(i=1; i<=count; i++)
                var nature= $('#payment_mod'+i).val();
            {
                if (nature == 0) {
                    alert('Select Tax Nature');
                    return false;
                }
            }

            $('#btn_cal').val('waiting......');
            $('#btn_cal').attr("disabled", true);




            for(i=1; i<=count; i++)
            {
                var nature= $('#payment_mod'+i).val();

                $.ajax({
                    url: '<?php echo url('/')?>/fdc/tax_calculation',
                    type: "GET",
                    data:{nature:nature,filer:filer,business:business,i:i},

                    success:function(data)
                    {

                        data=data.split(',');
                        count=data[1];
                        data=data[0];

                        var percent=data;
                        $('#btn_cal').val('Calculate');
                        $('#btn_cal').attr("disabled", false);
                        var d_t_amount=$('#d_amount_1_1').val();
                        $('#tax').fadeIn(500);
                        $('#percent_cal'+count).html(data+' % Tax Applied');
                        var cal_amount=$('.nature'+count).val();
                        cal_amount=(data / 100)*cal_amount;

                      if (count==1)
                      {
                          $('#c_amount_1_3').val(cal_amount);
                          $('.perc3').text('Income Tax Withholding '+data+' %');
                      }
                        else

                      {
                          $('#c_amount_1_4').val(cal_amount);
                          $('.perc4').text('Income Tax Withholding '+data+' %');

                      }

                      var  countt=count-1;
                        $('#tax'+countt).fadeIn(500);

                        sum(1);
                        credit_amount_mines();

                        





                    }

                });

            }
        }

        function Rmove_tax(number,id)
        {

            $('#c_amount_1_'+id).val(0);
            $('#tax'+number).fadeOut(500);
            sum(1);
            var diffrence= parseFloat($('#diff').val());
            var credit_amount= parseFloat($('#c_amount_1_2').val());
            var total_amount=credit_amount+diffrence;
            $('#c_amount_1_2').val(total_amount);
            sum(1);
        }
        $(document).ready(function() {

        $('.taxes').fadeOut(500);

            for(i=1; i<=2; i++)
            {
                $('#d_amount_1_'+i).number(true,2);
                $('#c_amount_1_'+i).number(true,2);
                $('#current_amount'+i).number(true,2);

            }

            for(i=3; i<=6; i++)
            {

                $('#c_amount_1_'+i).number(true,0);
                $('#current_amount'+i).number(true,2);

            }

            $('#d_t_amount_1').number(true,2);
            $('#c_t_amount_1').number(true,2);
            $('#diff').number(true,2);
            get_current_amount('account_id_1_1');

            calculation('d_amount_1_1','1');
            sum('1');
            var number = 12345;


            var p = 1;
            $('.addMorePvs').click(function (e){
                e.preventDefault();
                p++;
                var m = '1';
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/makeFormBankPaymentVoucher',
                    type: "GET",
                    data: { id:p,m:m},
                    success:function(data) {
                        $('.pvsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bankPvs_'+p+'"><a href="#" onclick="removePvsSection('+p+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                    }
                });
            });

            $(".btn-success").click(function(e){
                var pvs = new Array();
                var val;
                $("input[name='pvsSection[]']").each(function(){
                    pvs.push($(this).val());
                });
                var _token = $("input[name='_token']").val();

                var debit=$('#d_t_amount_1').val();
                debit=debit.replace(/,/g, "");
                var credit=$('#c_t_amount_1').val();
                credit=credit.replace(/,/g, "");

                if (debit!=credit)
                {
                    alert('DEBIT & CREDIT NOt EQUAL');
                    return false;
                }
                for (val of pvs) {
                    jqueryValidationCustom();


                    if(validate == 0)
                    {
                        $("#account_id_1_1").prop('disabled', false);
                        $(".d_amount_1").prop('disabled', false);
                        $(".c_amount_1").prop('disabled', false);

                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });
        var x = '{{$j-1}}';
        function addMorePvsDetailRows(id){
            x++;
            var m = '1';
            $.ajax({
                url: '<?php echo url('/')?>/fmfal/addMoreBankPvsDetailRows',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {
                    $('.addMorePvsDetailRows_'+id+'').append(data);
                    $('#d_amount_1_'+x).number(true,2);
                    $('#c_amount_1_'+x).number(true,2);

                    $('#account_id_1_'+x+'').select2();
                    $('#account_id_1_'+x+'').focus();

                }
            });
        }

        function removePvsRows(id,counter){
            var elem = document.getElementById('removePvsRows_'+id+'_'+counter+'');
            elem.parentNode.removeChild(elem);
        }
        function removePvsSection(id){
            var elem = document.getElementById('bankPvs_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
    <script type="text/javascript">

        $('.select2').select2();


    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <script>
        $(document).keydown(function(evt){
            if (evt.keyCode==83 && (evt.ctrlKey))
            {
                evt.preventDefault();
                addMorePvsDetailRows(1);
            }


        });


        function ntn_cnic(type)
        {
            if (type==1)
            {
                $("#filer_statys_div").fadeIn(500);
                $(".payment_mod_div").fadeIn(500);
                $("#ntn_div").fadeIn(500);
                $("#submit").fadeIn(500);
                $("#filer_check").fadeIn(500);
            }


            if (type==2)
            {
                $("#filer_statys_div").fadeOut(500);
                $(".payment_mod_div").fadeOut(500);
                $("#ntn_div").fadeOut(500);
                $("#cnic_div").fadeOut(500);
                $("#submit").fadeOut(500);
                $("#filer_check").fadeOut(500);

                $( "#filer1").prop( "checked", true );
            }
        }


        function active_status_check(type)
        {
            if (type==1)
            {

                $(".active_status").fadeIn(500);
                $('.checkbox1').prop("checked", false);
                $(".exemt").fadeIn(500);
            }
            else
            {
                $(".active_status").fadeOut(500);
                $('.checkbox1').prop("checked", false);
                $(".exemt").fadeOut(500);
            }



        }





        function unchech(id)
        {

            $('#'+id).prop("checked", false);


        }



        function credit_amount_mines()
        {



            var diffrence= $('#diff').val();
            var debit_amount= $('#d_amount_1_1').val();
            var summ=0;
            for (i=3; i<=7; i++)
            {
                var credit_amount=$('#c_amount_1_'+i).val();
                if (isNaN(credit_amount))
                {
                    credit_amount=0;
                }
                summ+=+credit_amount;


            }

            var total_amount=debit_amount-summ;

            $('#c_amount_1_2').val(total_amount);

            sum(1);


        }

  </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('input[type="checkbox"]').click(function(){
                if($(this).prop("checked") == true){
                   $('.payment_nature').fadeIn(500);
                    $("#cheque_no_1").addClass("requiredField");
                }
                else if($(this).prop("checked") == false){
                    $('.payment_nature').fadeOut(500);
                    $("#cheque_no_1").removeClass("requiredField");


                }
            });
        });


        function check_statuss(value)
        {


            var array=value.split(',');
            value=array[0];

            if(value=='No NTN Found')
            {
                alert('Required Registration No');
                return false;
            }
            $('#check_status').val('Wait.......');
            var id=array[1];

            $.ajax({
                url:'/pdc/services',
                data:{value:value,id:id},
                type:'GET',

                success:function(data){

                    $('#check_status').val('Check Status');

                    if(data==1)
                    {
                        alert('Filer');
                        $("#filer3").prop("checked", true);
                    }

                    else
                    {
                        alert('Non Filer');
                        $("#filer4").prop("checked", true);
                    }

                }
            });
        }
    </script>
@endsection