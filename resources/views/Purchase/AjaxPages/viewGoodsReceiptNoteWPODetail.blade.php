<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\StoreHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$goodsReceiptNoteDetail = DB::table('goods_receipt_note')->where('grn_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($goodsReceiptNoteDetail as $row) {
$demandType = $row->demand_type;
$grn_status = $row->grn_status;
$type = $row->type;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printGoodsReceiptNoteVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row">
    <?php if($demandType == 2){?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <?php echo PurchaseHelper::displayApproveDeleteRepostButtonGoodsReceiptNote($m,$row->grn_status,$row->status,$row->grn_no,'grn_no','grn_status','status','goods_receipt_note','grn_data','1');?>
        </div>
        <div style="line-height:5px;">&nbsp;</div>
        <?php }?>
        <?php echo Form::open(array('url' => 'pad/createStoreChallanandApproveGoodsReceiptNote?m='.$m.'','id'=>'createStoreChallanandApproveGoodsReceiptNote'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
        <input type="hidden" name="grnNo" value="<?php echo $id; ?>">
        <input type="hidden" name="grnDate" value="<?php echo $row->grn_date; ?>">
        <input type="hidden" name="prNo" value="<?php echo $row->po_no; ?>">
        <input type="hidden" name="prDate" value="<?php echo $row->po_date; ?>">
        <input type="hidden" name="supplier_id" value="<?php echo $row->supplier_id; ?>">
        <input type="hidden" name="sub_deparment_id" value="<?php echo $row->sub_department_id; ?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printGoodsReceiptNoteVoucherDetail">
            <div class="well">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                            echo ' '.'('.date('D', strtotime($x)).')';?></label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                <img src="{{URL('assets/img/Gudia-Logo2.png')}}" />
                            </div>
                            <br />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                 style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                                <?php echo '(Goods Receipt Note)' // PurchaseHelper::checkVoucherStatus($row->demand_status,$row->status);?>
                            </div>
                        </div>
                    </div>

                </div>
                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="width:40%; float:left;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>
                                <tr>
                                    <td style="width:40%;">GRN No.</td>
                                    <td style="width:60%;"><?php echo strtoupper($row->grn_no);?></td>
                                </tr>
                                <tr>
                                    <td>GRN Date</td>
                                    <td><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                                </tr>
                                <tr>
                                    <td style="width:40%;">PO No.</td>
                                    <td style="width:60%;"><?php echo strtoupper($row->po_no);?></td>
                                    <?php  // $po_type=CommonHelper::get_po_type_query($row->po_no); ?>
                                </tr>
                                <tr>
                                    <td>PO Date</td>
                                    <td><?php if ($row->type==0): echo CommonHelper::changeDateFormat($row->po_date);endif;?></td>
                                </tr>
                                <tr>
                                    <td>Bill Date</td>
                                    <td><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                                </tr>
                                <tr>
                                    <td>Supplier Invoice No</td>
                                    <td><?php  echo $row->supplier_invoice_no;?></td>
                                </tr>

                                <tr>
                                    <td>Delivery Challan No </td>
                                    <td><?php echo $row->delivery_challan_no;;?></td>
                                </tr>


                                </tbody>
                            </table>
                        </div>


                        <div style="width:40%; float:right;">
                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <tbody>


                                <tr>
                                    <td style="width:60%;">Delivery Detail/ Vehicle #.</td>
                                    <td style="width:40%;"><?php echo $row->delivery_detail;?></td>
                                </tr>
                                <?php if($row->warehouse !=""){ ?>
                                <tr>
                                    <td style="width:60%;">Warehouse</td>
                                    <td style="width:40%;"><?php echo CommonHelper::get_name_warehouse(ucwords($row->warehouse))?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>Department / Sub Department</td>
                                    <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                                </tr>

                                <tr>
                                    <td>Supplier Name</td>
                                    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id);?></td>
                                </tr>
                                <?php if($row->supplier_id !=""){ ?>
                                <tr>
                                    <td>Supplier Address</td>
                                    <td width=""><?php echo CommonHelper::get_supplier_address($row->supplier_id);;?></td>
                                </tr>
                                <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $grnDataDetail = DB::table('grn_data')->where('grn_no','=',$id)->get();


                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            foreach ($grnDataDetail as $row1)
                            {

                            $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                            $sub_ic_detail= explode(',',$sub_ic_detail);
                            if($type==3){
                                $region = CommonHelper::get_rgion_name_by_id($row1->region_to);
                            } else{
                                $region = CommonHelper::get_rgion_name_by_id($row1->region);
                            }
                            ?>

                            <div class="">
                                <h5> <span></span><strong>(<?php echo $counter ?>). Category : </strong> {{CommonHelper::getCompanyDatabaseTableValueById($m,'category','main_ic',$row1->category_id)}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Item : </strong>{{CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id)}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>UOM : </strong>{{CommonHelper::get_uom_name($sub_ic_detail[0])}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Pack Size : </strong>{{$sub_ic_detail[1]}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Item Description : </strong>{{$sub_ic_detail[3]}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Region : </strong>{{ $region->region_name }}
                                </h5>
                            </div>

                            <table  class="table table-bordered table-striped table-condensed tableMargin">
                                <thead>
                                <tr>
                                    <th class="text-center">QTY. Ordered</th>
                                    <th class="text-center">Carton<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Pack Unit<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Loose Carton<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Pack Unit<span class="rflabelsteric"></span></th>
                                    <th class="text-center">Total Quantity Recived<span class="rflabelsteric"></span></th>
                                    <th class="text-center">BAL. QTY. Receivable</th>
                                    <th class="text-center">Remarks<span class="rflabelsteric"></span></th>

                                        <th class="text-center">Item Manufacturing Date<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Item Expiry Date<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Item Batch no.<span class="rflabelsteric"></span></th>
                                        <th class="text-center">No Of PKG Per Item.<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Gross Weight Per Item.<span class="rflabelsteric"></span></th>
                                        <th class="text-center">Net  Weight Per Item.<span class="rflabelsteric"></span></th>


                                    <?php $import_costing_exists=CommonHelper::import_costing_exists($row1->id); ?>
                                    @if($import_costing_exists->count()>0)
                                        <th class="text-center">View Costing<span class="rflabelsteric"></span></th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                ?>
                                <tr>

                                    <!--
                                <td class="text-center">
                                    < ?php echo $counter++;?>
                                        <input type="hidden" name="rowId[]" id="rowId_< ?php $row1->id;?>" value="< ?php echo $row1->id;?>">
                                    <input type="hidden" name="demandNo_< ?php echo $row1->id;?>" readonly id="demandNo_< ?php echo $row1->id;?>" value="< ?php echo CommonHelper::getDemandNoByPrNo($m,$row->po_no,$row1->category_id,$row1->sub_item_id);?>" class="form-control" />
                                    <input type="hidden" name="demandDate_< ?php echo $row1->id;?>" readonly id="demandDate_< ?php echo $row1->id;?>" value="< ?php echo CommonHelper::getDemandDateByPrNo($m,$row->po_no,$row1->category_id,$row1->sub_item_id);?>" class="form-control" />
                                    <input type="hidden" name="demandType_< ?php echo $row1->id;?>" readonly id="demandType_< ?php echo $row1->id;?>" value="< ?php echo $row1->demand_type;?>" class="form-control" />
                                    <input type="hidden" name="demandSendType_< ?php echo $row1->id;?>" readonly id="demandSendType_< ?php echo $row1->id;?>" value="<?php echo $row1->demand_send_type;?>" class="form-control" />
                                </td>

<!-->
                                    <td class="text-center">
                                        <?php echo number_format($row1->purchase_approved_qty,2);?>
                                    </td>

                                    <td  class="text-center"><?php echo  number_format($row1->carton,2)?></td>
                                    <td  class="text-center"><?php echo  number_format($row1->packunit,2)?></td>
                                    <td  class="text-center"><?php echo  number_format($row1->loose_carton,2)?></td>
                                    <td  class="text-center"><?php echo  number_format($row1->loose_packunit,2)?></td>

                                    <td  class="text-center"><?php echo  number_format($row1->purchase_recived_qty,2)?></td>
                                    <td @if($row->type==0) @if($row1->purchase_recived_qty > $row1->purchase_approved_qty)style="background-color: yellow"@endif @endif class="text-center"><?php echo number_format($row1->bal_reciable,2);?></td>
                                    <td class="text-center"><textarea class="form-control" style="resize: none">{{$row1->remarks}}</textarea></td>



                                        <td><?php echo CommonHelper::changeDateFormat($row1->manufac_date);?></td>
                                        <td><?php echo CommonHelper::changeDateFormat($row1->expiry_date);?></td>
                                        <td><?php echo $row1->batch_no;?></td>
                                        <td><?php echo $row1->no_pkg_item;?></td>
                                        <td><?php echo $row1->net_item;?></td>
                                        <td><?php echo $row1->gross_item;?></td>

                                    @if($import_costing_exists->count()>0)

                                        <td  class="text-center"><input onclick="import_costing(this.id,'<?php echo $row->id ?>')" type="checkbox" id="costing_<?php echo $row->id; ?>"/> </td>
                                    @endif

                                </tr>
                                <?php

                                ?>
                                </tbody>
                            </table>
                            <?php  $counter++;
                            if($import_costing_exists->count()>0):
                            $data= $import_costing_exists->first();?>

                            <?php     //$currency_rate=CommonHelper::get_amount_by_po_data_id($row1->po_data_id);
                            //print_r($currency_rate);

                            //$currency_rate =explode(',',$currency_rate);

                            ?>

                            <table style="display: none"   class="table table-bordered sf-table-list costing_<?php echo $row1->id; ?>">
                                <tr>
                                    <th colspan="2">{{CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id)}}</th>
                                    <th><input type="date" class="form-control" /> </th>
                                </tr>
                                <tr>
                                    <td>Beneficiary</td>
                                    <td>Bank Contract</br>
                                        Commercial Invoice Date</br>
                                        Goods Receipt Date
                                    </td>
                                    <td><input type="date" class="form-control" name="bank_contract" /></br>
                                        <input type="date" class="form-control" name="commercial_invoice_date" /></br>
                                        <input type="date" class="form-control" name="good_rec_date" />
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">SYNsfsd</td>
                                    <td>Foreign Currency
                                        </br>
                                        Exchange rate

                                    </td>
                                    <?php //$amount=$currency_rate[0]*$row1->purchase_recived_qty; ?>
                                    <td><b id="foriegn_amount_{{$row->id}}"><?php // echo  number_format($amount,2) ?></b><br></td>
                                </tr>
                                <tr>
                                    <td>{{CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id)}}</br>
                                        <b><u>Landed Cost</u></b>
                                        </br>
                                        <b>Sachets</b>
                                        </br>
                                        <b id="Sachets_<?php echo  $row->id ?>">{{number_format($row1->purchase_recived_qty,2)}}</b>

                                    </td>
                                    <td>Total pak Rupees</td>
                                </tr>
                                <?php
                                $bc_ope_chgs=$data->bc_ope_chgs;
                                $bs_ship_guar_chgs=$data->bs_ship_guar_chgs;
                                $remittance_chgs=$data->remittance_chgs;
                                $other_bank_chgs=$data->other_bank_chgs;
                                $insurance_expns=$data->insurance_expns;

                                ?>
                                <tr>
                                    <td>B / c Opening Charges</td>
                                    <td></td>
                                    <td class="text-right"><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="b_c_opening_charges_<?php echo  $row->id ?>"
                                                                  class="form-control" name="b_c_opening_charges_<?php echo  $row->id ?>" value="{{number_format($data->bc_ope_chgs,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>B/c Shipping Guarantee Charges</td>
                                    <td></td>
                                    <td class="text-right"><input  disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="b_c_shipping_charges_<?php echo  $row->id ?>" class="form-control"
                                                                   name="b_c_shipping_charges_<?php echo  $row->id ?>"  value="{{number_format($data->bs_ship_guar_chgs,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Remittance Charges</td>
                                    <td></td>
                                    <td class="text-right"><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="remittance_charges_<?php echo  $row->id ?>"
                                                                  class="form-control" name="remittance_charges_<?php echo  $row->id ?>" value="{{number_format($data->remittance_chgs,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Other Bank Charges</td>
                                    <td></td>
                                    <td class="text-right"><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="other_bank_charges_<?php echo  $row->id ?>"
                                                                  class="form-control" name="other_bank_charges_<?php echo  $row->id ?>" value="{{number_format($data->other_bank_chgs,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Insurance Expenses</td>
                                    <td></td>
                                    <td class="text-right"><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="insurance_exp_<?php echo  $row->id ?>"
                                                                  class="form-control" name="insurance_exp_<?php echo  $row->id ?>" value="{{number_format($data->insurance_expns,2)}}"/></td>
                                </tr>
                                <tr>
                                    <td>Cost</td>
                                    <td id="cost_sachet_<?php echo $row->id; ?>" style="font-size: 15px;font-weight: bold"></td>
                                    <?php $cost_amount=0;//$per_sacet_value * $row1->purchase_recived_qty * $currency_rate[1]; ?>
                                    <td><input disabled  readonly style="font-size: 20px;font-weight: 600" type="text" id="cost_in_pkr_<?php echo  $row->id ?>" class="form-control cost_in_pkr_{{$counter}}"
                                               name="cost_in_pkr_<?php echo  $row->id ?>" value="{{number_format($cost_amount,2)}}" /></td>
                                </tr>

                                <?php
                                $custome_duty=$data->custom_duty;
                                $additional_custom_duty=$data->additional_custom_duty;
                                $excise_taxation=$data->excise_taxation;
                                $wharfage_gdown_etc=$data->wharfage_gdown_etc;
                                $air_freight=$data->air_freight;
                                $sales_tax=$data->sales_tax;
                                $income_tax=$data->income_tax;

                                ?>
                                <tr>
                                    <td><b>Clearing & Forwarding </b></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Custom Duty 11 %</td>
                                    <td></td>
                                    <td><input onkeyup="costing_calcu(this.id,'{{$row->id}}')"  type="text" id="custome_duty_<?php echo  $row->id ?>"
                                               name="custome_duty_<?php echo  $row->id ?>" class="form-control" value="{{number_format($custome_duty,2)}}"/></td>
                                </tr>
                                <tr>
                                    <td>Additional Custom Duty 2</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="additional_custom_duty_<?php echo  $row->id ?>"
                                               name="additional_custom_duty_<?php echo  $row->id ?>" class="form-control" value="{{number_format($additional_custom_duty,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Excise & Taxation</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="excise_taxation_<?php echo  $row->id ?>"
                                               name="excise_taxation_<?php echo  $row->id ?>" class="form-control" value="{{number_format($excise_taxation,2)}}"/></td>
                                </tr>
                                <tr>
                                    <td>Wharfage ,Godown , Services Chgs. etc</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="whage_godown_charges_<?php echo  $row->id ?>"
                                               name="whage_godown_charges_<?php echo  $row->id ?>" class="form-control" value="{{number_format($wharfage_gdown_etc,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Air Freight-CSS</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="air_freight_<?php echo  $row->id ?>"
                                               name="air_freight_<?php echo  $row->id ?>" class="form-control" value="{{number_format($air_freight,2)}}" /></td>
                                </tr>
                                <?php $total_landed_cost=$custome_duty+$additional_custom_duty+$excise_taxation+$wharfage_gdown_etc+$air_freight+$cost_amount
                                        +$bc_ope_chgs+$bs_ship_guar_chgs+$remittance_chgs+$other_bank_chgs+$insurance_expns;
                                ?>

                                <tr>
                                    <td><b>Total Landed Cost</b></td>
                                    <?php $per_scaet=$total_landed_cost / $row1->purchase_recived_qty; ?>
                                    <td style="font-size: 20px;font-weight: 600" class="text-right" id="land_cost_qty_{{$row->id}}">{{number_format($per_scaet,2)}}</td>
                                    <td><input style="font-size: 20px;font-weight: 600" readonly type="text" id="total_landed_<?php echo  $row->id ?>" name="total_landed_<?php echo  $row->id ?>"
                                               class="form-control total_landed_<?php echo  $counter ?>" value="{{number_format($total_landed_cost,2)}}" /></td>
                                </tr>
                                <tr>
                                    <td>Sales Tax</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="sales_tax_<?php echo  $row->id ?>"
                                               name="sales_tax_<?php echo  $row->id ?>" class="form-control" value="{{number_format($sales_tax)}}" /></td>
                                </tr>


                                <tr>
                                    <td>Income Tax</td>
                                    <td></td>
                                    <td><input disabled onkeyup="costing_calcu(this.id,'{{$row->id}}')" type="text" id="income_tax_<?php echo  $row->id ?>"
                                               name="income_tax_<?php echo  $row->id ?>" class="form-control" value="{{number_format($income_tax)}}" /></td>
                                </tr>
                                <?php $total_cost_with_tax=$sales_tax+$income_tax+$total_landed_cost; ?>
                                <tr>
                                    <td></td>
                                    <td><b>Total Cash Out Flow</b></td>
                                    <td><input style="font-size: 20px;font-weight: 600" readonly type="text" id="total_cash_flow_<?php echo  $row->id ?>" name="total_cash_flow_<?php echo  $row->id ?>"
                                               class="form-control total_cash_flow_<?php echo  $counter ?>" value="{{number_format($total_cost_with_tax,2)}}" /></td>
                                </tr>

                            </table>
                            <table style="display: none"   class="table table-bordered sf-table-list costing_<?php echo $row->id; ?>">

                                <?php
                                $sachet_foli_per_pack=$data->sachet_foli_per_pack;
                                $uniit_carton_per_pack=$data->uniit_carton_per_pack;
                                $leaf_insert_per_pack=$data->leaf_insert_per_pack;
                                $master_carton_per_pack=$data->master_carton_per_pack;
                                $packing_cahrges_per_pack=$data->packing_cahrges_per_pack;
                                $total_per_pack=$sachet_foli_per_pack+$uniit_carton_per_pack+$leaf_insert_per_pack+$master_carton_per_pack+$packing_cahrges_per_pack;

                                $sachet_foli_per_item=$data->sachet_foli_per_item;
                                $unit_carton_per_item=$data->unit_carton_per_item;
                                $leaf_insert_per_item=$data->leaf_insert_per_item;
                                $master_carton_per_item=$data->master_carton_per_item;
                                $packing_cahrges_per_item=$data->packing_cahrges_per_item;
                                $total_per_item=$sachet_foli_per_item+$unit_carton_per_item+$leaf_insert_per_item+$master_carton_per_item+$packing_cahrges_per_item;

                                ?>


                                <tr>
                                    <td>Landed Cost</td>
                                    <td style="font-size: 20px;font-weight: 600" class="landed_cost_per_pack_{{$row->id}} text-right">{{number_format($per_scaet * 10,2)}}</td>
                                    <td style="font-size: 20px;font-weight: 600" class="landed_cost_per_item_{{$row->id}} text-right">{{number_format($per_scaet,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Sachet Foli</td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_cost(this.id,'{{$row->id}}')" type="text" id="sachet_foli_per_pack_<?php echo  $row->id ?>"
                                                                  name="sachet_foli_per_pack_<?php echo  $row->id ?>" class="form-control" value="{{$sachet_foli_per_pack}}" /></td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_item(this.id,'{{$row->id}}')" type="text" id="sachet_foli_per_item_<?php echo  $row->id ?>"
                                                                  name="sachet_foli_per_item_<?php echo  $row->id ?>" class="form-control" value="{{$sachet_foli_per_item}}" /></td>
                                </tr>

                                <tr>
                                    <td>Unit Carton</td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_cost(this.id,'{{$row->id}}')" type="text" id="uniit_carton_per_pack_<?php echo  $row->id ?>"
                                                                  name="uniit_carton_per_pack_<?php echo  $row->id ?>" class="form-control" value="{{$uniit_carton_per_pack}}" /></td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_item(this.id,'{{$row->id}}')" type="text" id="unit_carton_per_item_<?php echo  $row->id ?>"
                                                                  name="unit_carton_per_item_<?php echo  $row->id ?>" class="form-control" value="{{$unit_carton_per_item}}" /></td>
                                </tr>

                                <tr>
                                    <td>Leaf Insert</td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_cost(this.id,'{{$row->id}}')" type="text" id="leaf_insert_per_pack_<?php echo  $row->id ?>"
                                                                  name="leaf_insert_per_pack_<?php echo  $row->id ?>" class="form-control" value="{{$leaf_insert_per_pack}}" /></td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_item(this.id,'{{$row->id}}')" type="text" id="leaf_insert_per_item_<?php echo  $row->id ?>"
                                                                  name="leaf_insert_per_item_<?php echo  $row->id ?>" class="form-control" value="{{$leaf_insert_per_item}}" /></td>
                                </tr>

                                <tr>
                                    <td>Master Ctn.</td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_cost(this.id,'{{$row->id}}')" type="text" id="master_carton_per_pack_<?php echo  $row->id ?>"
                                                                  name="master_carton_per_pack_<?php echo  $row->id ?>" class="form-control" value="{{$master_carton_per_pack}}" /></td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_item(this.id,'{{$row->id}}')" type="text" id="master_carton_per_item_<?php echo  $row->id ?>"
                                                                  name="master_carton_per_item_<?php echo  $row->id ?>" class="form-control" value="{{$master_carton_per_item}}" /></td>

                                </tr>


                                <tr>
                                    <td>Packing Cahrges</td>
                                    <td class="text-right"> <input disabled onkeyup="costing_per_pac_cost(this.id,'{{$row->id}}')" type="text" id="packing_cahrges_per_pack_<?php echo  $row->id ?>"
                                                                   name="packing_cahrges_per_pack_<?php echo  $row->id ?>" class="form-control" value="{{$packing_cahrges_per_pack}}" /></td>
                                    <td class="text-right"><input disabled onkeyup="costing_per_pac_item(this.id,'{{$row->id}}')" type="text"
                                                                  id="packing_cahrges_per_item_<?php echo  $row->id ?>" name="packing_cahrges_per_item_<?php echo  $row->id ?>" class="form-control" value="{{$packing_cahrges_per_item}}" /></td>
                                </tr>

                                <tr>

                                    <td><b>Packing Cost</b></td>
                                    <td  style="font-size: 20px;font-weight: 600" class="per_pack_cost_{{$row->id}} text-right">{{number_format($total_per_pack+$per_scaet * 10,2)}}</td>
                                    <td style="font-size: 20px;font-weight: 600" class="per_item_cost_{{$row->id}} text-right">{{number_format($total_per_item+$per_scaet,2)}}</td>
                                </tr>

                            </table>


                            <?php endif;
                            }
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h6>Description: <?php echo ucwords($row->main_description); ?></h6>
                                    </div>
                                </div>
                                <style>
                                    .signature_bor {
                                        border-top:solid 1px #CCC;
                                        padding-top:7px;
                                    }
                                </style>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                                    <div class="container-fluid">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                                <h6 class="signature_bor">Prepared By: </h6>
                                                <b>   <p><?php //echo strtoupper($username);  ?></p></b>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                                <h6 class="signature_bor">Checked By:</h6>
                                                <b>   <p><?php  ?></p></b>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                                <h6 class="signature_bor">Approved By:</h6>
                                                <b>  <p></p></b>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                @if($grn_status==1):
                                    <button onclick="approve_grn({{$row->id}})" type="button" class="btn btn-success btn-xs">Approve</button>
                                @endif
                                <!--
                                { {  Form::button('Approve', ['class' => 'btn btn-success btn-abc-submit']) }}
                                        <!-->
                            </div>
                            <?php
                            die;?>
                        </div>
                    </div>
                    <div style="line-height:8px;">&nbsp;</div>

                </div>
            </div>
        </div>
        <?php }?>


    </div>
    <script type="text/javascript">
        function import_costing(id,number)
        {
            alert();
        }

        function approveCompanyPurchaseGoodsReceiptNote(m,voucherStatus,rowStatus,columnValue,columnOne,columnTwo,columnThree,tableOne,tableTwo) {
            $.ajax({
                url: ''+baseUrl+'/pd/approveCompanyPurchaseGoodsReceiptNote',
                type: "GET",
                data: {m:m,voucherStatus:voucherStatus,rowStatus:rowStatus,columnValue:columnValue,columnOne:columnOne,columnTwo:columnTwo,columnThree:columnThree,tableOne:tableOne,tableTwo:tableTwo},
                success:function(data) {
                    filterVoucherList();
                }
            });
        }

        $(".btn-abc-submit").click(function(e){
            var _token = $("input[name='_token']").val();
            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
            formSubmitOne();
        });

        function formSubmitOne(e){

            var postData = $('#createStoreChallanandApproveGoodsReceiptNote').serializeArray();
            var formURL = $('#createStoreChallanandApproveGoodsReceiptNote').attr("action");

            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data){
                    $('#showDetailModelOneParamerter').modal('toggle');
                    filterVoucherList();
                }
            });
        }

        function checkQty(paramOne,paramTwo,paramThree) {
            var remainingQty = $('#'+paramOne+'').val();
            if(parseInt(paramTwo) <= parseInt(remainingQty)){
            }else{
                $('#'+paramThree+'').val('');
                alert('Issue Qty not allow to max Demand Qty!');
            }
        }

        function makeSummarySection() {
            var netTotalAmount = $('#netTotalAmount').val();
            var totalPaymentAmount = $('#totalPaymentAmount').val();

            var totalBalanceAmount = parseInt(netTotalAmount) - parseInt(totalPaymentAmount);
            $('#cellTotalBalance').html(totalBalanceAmount);
            $('#cellTotalPaymentAmount').html(totalPaymentAmount);

        }
        makeSummarySection();

    </script>

