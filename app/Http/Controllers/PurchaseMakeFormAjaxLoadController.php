<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use App\Models\GRNData;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\BreakupData;
use App\Models\Customer;
use App\Models\Client;
use App\Models\Branch;
use App\Models\DemandType;
use App\Helpers\FinanceHelper;
use App\Helpers\Payment_through_jvs;
use App\Models\GoodsReceiptNote;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Estimate;


class PurchaseMakeFormAjaxLoadController extends Controller
{
    public function subItemListLoadDepandentCategoryId(){
        $id = $_GET['id'];
        if (isset($_GET['subitem'])) {
            $subitem = $_GET['subitem'];
        } else {
            $subitem='ok';
        }
        $m = $_GET['m'];
        $value = $_GET['value'];
        return PurchaseHelper::subItemList($m,$id,$value,$subitem);
    }


function addDirectgrn()
    {

    $counter=$_GET['counter'];

    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="tr<?php echo $counter?>" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th colspan="" class="text-center">Region <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th style="width: 250px;" colspan="" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createCategoryFormAjax')" class="">Category</a></th>
                                                    <th style="width: 250px;" colspan="" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax/0')" class="">Sub Item</a></th>
                                                    <th colspan="" class="text-center" >Uom <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th colspan="" class="text-center" >Pack Size <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th colspan="" class="text-center">Qty in Unit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th colspan="" class="text-center">Rate <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th colspan="" class="text-center">Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="">
                                                <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="<?php echo $counter ?>" />
                                                <tr>
                                                    <td>
                                                        <select class="form-control requiredField select2" name="region_1_<?php echo $counter ?>" id="region_1_<?php echo $counter ?>">
                                                            <option value="">Select</option>
                                                            <?php foreach(CommonHelper::get_all_regions() as $row): ?>
                                                            <option value="<?= $row->id ?>"> <?= ucwords($row->region_name) ?> </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td colspan="">
                                                        <select name="category_id_1_<?php echo $counter ?>" id="category_id_1_<?php echo $counter ?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField select2">
                                                            <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                        </select>
                                                    </td>
                                                    <td colspan="">
                                                        <select onchange="get_detail(this.id)"  name="sub_item_id_1_<?php echo $counter ?>" id="sub_item_id_1_<?php echo $counter ?>" class="form-control requiredField select2">
                                                            <option>Select Sub Item</option>
                                                        </select>
                                                    </td>
                                                    <td colspan="">
                                                        <input class="form-control" readonly type="text" id="uom_1_<?php echo $counter ?>" name="uom_1_<?php echo $counter ?>"/>
                                                        <input type="hidden" id="uom_id_1_1" name="uom_id_1_1"/>
                                                    </td>
                                                    <td colspan="">
                                                        <input class="form-control" readonly type="text" id="pack_size_1_<?php echo $counter ?>" name="pack_size_1_<?php echo $counter ?>"/>
                                                    </td>
                                                    <input class="form-control" readonly type="hidden" id="demand_type_id_1_<?php echo $counter ?>" name="demand_type_id_1_<?php echo $counter ?>"/>

                                                    <td colspan="">
                                                        <input type="text" name="qty_1_<?php echo $counter ?>" id="qty_1_<?php echo $counter ?>" onkeyup="CalculationQtyRate('<?php echo $counter ?>')" class="form-control requiredField" />
                                                    </td>
                                                    <td colspan="">
                                                        <input type="text" name="rate_1_<?php echo $counter ?>" id="rate_1_<?php echo $counter ?>" onkeyup="CalculationQtyRate('<?php echo $counter ?>')" class="form-control requiredField" />
                                                    </td>
                                                    <td colspan="">
                                                        <input type="text" name="amount_1_<?php echo $counter ?>" id="amount_1_<?php echo $counter ?>" class="form-control requiredField" readonly/>
                                                    </td>
                                                    
                                                </tr>


<tr>
                                                <th colspan="1" class="text-center hidden-print">Remarks</th>
                                                <th colspan="1" class="text-center hidden-print">Item Manufacturing Date</th>
                                                <th colspan="1" class="text-center" >Item Expiry Date <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th colspan="1" class="text-center" >Item Batch no. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th colspan="1" class="text-center" >NO Of PKG Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th colspan="1" class="text-center"> Gross Weight Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th colspan="1" class="text-center"> Net Weight Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th colspan="1" class="text-center"> Action <span class="rflabelsteric"><strong>*</strong></span></th>

</tr>

                                                <tr>
                                                    <td colspan="1"><textarea  style="resize: none" class="requiredField form-control" name="description_1_<?php echo $counter ?>" id="description_1_<?php echo $counter ?>"></textarea> </td>
                                                    <td><input name="maufac_date_1_<?php echo $counter  ?>" id="maufac_date_1_<?php echo $counter  ?>"  class="form-control requiredField" type="date" value=""/> </td>

                                                    <!--Item Expiry Date-->
                                                    <td><input name="expiry_date_1_<?php echo $counter ?>" id="expiry_date_1_<?php echo $counter ?>"  class="form-control requiredField" type="date" value=""/> </td>





                                                    <!--Item Batch no.-->
                                                    <td><input name="batch_no_1_<?php echo $counter ?>" id="batch_no_1_<?php echo $counter ?>"  class="form-control requiredField" type="text" value=""/> </td>


                                                    <!--Number Of Packages Per Item..-->
                                                    <td><input name="no_pack_per_item_1_<?php echo $counter ?>" id="no_pack_per_item_1_<?php echo $counter ?>"  class="form-control requiredField" type="text" value=""/> </td>

                                                    <!--Net & Gross Weight Per Item-->
                                                    <td><input  name="gross_1_<?php echo $counter ?>" id="gross_1_<?php echo $counter ?>" class="form-control requiredField" type="text" value=""/> </td>

                                                    <td><input  name="net_1_<?php echo $counter ?>" id="net_1_<?php echo $counter ?>" class="form-control requiredField" type="text" value=""/> </td>
                                                    <td> <input type="button" onclick="removeDemandsRows(<?php echo $counter ?>)" class="btn btn-sm btn-danger" name="Remove" value="Remove"> </td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
    }



    public function addMoreDemandsDetailRows()
    {
        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
    ?>
        <tr id="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">
            <input type="hidden" name="demandDataSection_<?php echo $id?>[]" class="form-control requiredField" id="demandDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
            <td>
                <select name="category_id_<?php echo $id?>_<?php echo $counter?>" id="category_id_<?php echo $id?>_<?php echo $counter?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                    <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                </select>
            </td>
            <td>
                <select onchange="get_detail(this.id)" name="sub_item_id_<?php echo $id?>_<?php echo $counter?>" id="sub_item_id_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField">
                </select>
            </td>

            <td><input class="form-control" readonly type="text" id="uom_<?php echo $id?>_<?php echo $counter?>" name="uom_<?php echo $id?>_<?php echo $counter?>"/>
            <input type="hidden" id="uom_id_<?php echo $id?>_<?php echo $counter?>" name="uom_id_<?php echo $id?>_<?php echo $counter?>"/>
            </td>
             <td><input class="form-control" readonly type="text" id="pack_size_<?php echo $id?>_<?php echo $counter?>" name="pack_size_<?php echo $id?>_<?php echo $counter?>"/> </td>
 <td><input class="form-control" readonly type="text" id="demand_type_<?php echo $id?>_<?php echo $counter?>" name="demand_type_<?php echo $id?>_<?php echo $counter?>"/> </td>
   <input class="form-control" readonly type="hidden" id="demand_type_id_<?php echo $id?>_<?php echo $counter?>" name="demand_type_id_<?php echo $id?>_<?php echo $counter?>"/>

            <td>
            <textarea type="text" name="description_<?php echo $id?>_<?php echo $counter?>" id="description_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" ></textarea>
           </td>
            <td>
                <input type="number" step="0.01" name="qty_<?php echo $id?>_<?php echo $counter?>" id="qty_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
            </td>

               <td><input class="form-control" readonly type="text" id="remaining_qty_<?php echo $id?>_<?php echo $counter?>" name="remaining_qty_<?php echo $id?>_<?php echo $counter?>"/> </td>

            <td class="text-right"><input class="form-control" readonly type="text" id="orderd_<?php echo $id?>_<?php echo $counter?>" name="orderd_<?php echo $id?>_<?php echo $counter?>"/> </td>
            <td class="text-right"><input class="form-control" readonly type="text" id="received_<?php echo $id?>_<?php echo $counter?>" name="received_<?php echo $id?>_<?php echo $counter?>"/> </td>
            <td class="text-center"><input onclick="view_history('<?php echo $counter?>')" class="form-right" readonly type="checkbox" id="history_<?php echo $id?>_<?php echo $counter?>" name="history_<?php echo $id?>_<?php echo $counter?>"/> </td>
            <!--
            <td class="text-center"><button  onclick="removeDemandsRows('<?php echo $id?>','<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</button></td>
            <!-->
        </tr>
    <?php
    }

    public function addMoreIssuanceDetailRows()
    {
        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
        ?>
        <tr id="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">
            <input type="hidden" name="issuanceDataSection_<?php echo $id?>[]" class="form-control requiredField" id="issuanceDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
            <td>
                <select name="category_id_<?php echo $id?>_<?php echo $counter?>" id="category_id_<?php echo $id?>_<?php echo $counter?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                    <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                </select>
            </td>
            <td>
                <select onchange="get_detail(this.id,<?php echo $counter?>)" name="sub_item_id_<?php echo $id?>_<?php echo $counter?>" id="sub_item_id_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField">
                </select>
            </td>

<!--            <td><input class="form-control" readonly type="text" id="uom_--><?php //echo $id?><!--_--><?php //echo $counter?><!--" name="uom_--><?php //echo $id?><!--_--><?php //echo $counter?><!--"/>-->
<!--                <input type="hidden" id="uom_id_--><?php //echo $id?><!--_--><?php //echo $counter?><!--" name="uom_id_--><?php //echo $id?><!--_--><?php //echo $counter?><!--"/>-->
<!--            </td>-->
            <td>
                <input type="number" onkeyup="CurrentQty(this.id,<?php echo $counter?>)" step="0.01" name="qty_<?php echo $id?>_<?php echo $counter?>" id="qty_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
            </td>
            <td><input class="form-control" readonly type="text" id="stock_qty_<?php echo $id?>_<?php echo $counter?>" name="stock_qty_<?php echo $id?>_<?php echo $counter?>"/> </td>
            <td class="text-right"><input class="form-control" readonly type="text" id="current_qty_<?php echo $id?>_<?php echo $counter?>" name="current_qty_<?php echo $id?>_<?php echo $counter?>"/> </td>

        </tr>
        <?php
    }

    public function addMorPurchaseVoucherRow()
    {

        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
        ?>


        <table  id="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>" class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 200px;" class="text-center"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax/category_id_<?php echo $id?>_<?php echo $counter?>')" class="">Acc. Head</a> <span class="rflabelsteric"><strong>*</strong></span></th>
            <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a></th>
            <th style="width: 100px" class="text-center">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
            <th style="width: 200px;" class="text-center">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
            <th style="width: 200px;" class="text-center">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>
            <th style="width: 200px;" class="text-center">Amount. <span class="rflabelsteric"><strong>*</strong></span></th>
           <th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax/accounts_<?php echo $id?>_<?php echo $counter?>')" class="">Sales Tax Acc</a></th>
           <th style="width: 150px;" class="text-center" style="">Sales Tax Amount <span class="rflabelsteric"><strong>*</strong></span></th>
            <th style="width: 200px;" class="text-center" style="">Total Amount <span class="rflabelsteric"><strong>*</strong></span></th>
            <!--
            <th style="width: 200px;" class="text-center" style="">Department <span class="rflabelsteric"><strong>*</strong></span></th>
            <!-->



        </tr>
        </thead>
        <tbody class="" id="">

        <tr id="">

            <input type="hidden" name="demandDataSection_<?php echo $id?>[]" class="form-control requiredField" id="demandDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
            <td>
                <select style="width: 200px;"  class="form-control requiredField select2"  id="category_id_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" name="category_id_<?php echo $id?>_<?php echo $counter?>" onchange="">
                    <option value="">Select Expense</option>


                        <?php


                            foreach(FinanceHelper::get_accounts() as $row):?>
                           <option value="<?php echo $row->id ?>"> <?php echo  ucwords($row->name) ?></option>
                         <?php   endforeach ?>

                </select>
            </td>
            <td>
                <select onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_<?php echo $id?>_<?php echo $counter?>" id="sub_item_id_<?php echo $id?>_<?php echo $counter?>" class="form-control select2">
                <option value="">Select</option>
                  <?php foreach(CommonHelper::get_all_subitem() as $row): ?>
                  <option value="<?php echo  $row->id ?>"><?php echo  $row->sub_ic?></option>
                 <?php endforeach ?>
                </select>
            </td>
            <td>
                <input type="text" name="uom_<?php echo $id?>_<?php echo $counter?>" id="uom_<?php echo $id?>_<?php echo $counter?>" class="form-control" />
                <input type="hidden" name="uom_id_<?php echo $id?>_<?php echo $counter?>" id="uom_id_<?php echo $id?>_<?php echo $counter?>" class="" />
            </td>
            <td>
                <input onkeyup="calculation_amount(this.id)"  type="number" name="qty_<?php echo $id?>_<?php echo $counter?>" id="qty_<?php echo $id?>_<?php echo $counter?>" class="form-control qty" />
            </td>

            <td>
                <input onkeyup="calculation_amount(this.id)" step="0.01" type="number" name="rate_<?php echo $id?>_<?php echo $counter?>" id="rate_<?php echo $id?>_<?php echo $counter?>" class="form-control rate" />
            </td>

            <td>
                <input onkeyup="pick_amount(this.id,'amount_<?php echo $id ?>_<?php echo $counter ?>');calc_amount(this.id)" type="text"  name="amounttd_<?php echo $id?>_<?php echo $counter?>" id="amounttd_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField amount" />
                  <input type="hidden" step="0.01" name="amount_<?php echo $id?>_<?php echo $counter?>" id="amount_<?php echo $id?>_<?php echo $counter?>"/>
            </td>


            <td>
                <select  onchange="sales_tax(this.id)" style="width: 200px"  name="accounts_<?php echo $id?>_<?php echo $counter?>" id="accounts_<?php echo $id?>_<?php echo $counter?>"
                        class="form-control select2">
                    <option value="0">Select</option>

                    <?php foreach(FinanceHelper::get_accounts() as $row):
                        $code=explode('-',$row->code);

                        ?>
                        <option value="<?php echo  $row->id?>"><?php echo ($code[0]).'--'.ucwords($row->name)?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input onkeyup="pick_amount(this.id,'sales_tax_amount_<?php echo $id ?>_<?php echo $counter ?>');tax_by_amount(this.id)"   type="text" name="sales_tax_amounttd_<?php echo $id?>_<?php echo $counter?>" id="sales_tax_amounttd_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField sales_tax_amount" />
                 <input type="hidden" name="sales_tax_amount_<?php echo $id?>_<?php echo $counter?>" id="sales_tax_amount_<?php echo $id?>_<?php echo $counter?>" />
            </td>

            <td>
                <input type="text"   name="net_amounttd_<?php echo $id?>_<?php echo $counter?>" id="net_amounttd_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
                   <input class="net_amount" type="hidden" name="net_amount_<?php echo $id?>_<?php echo $counter?>" id="net_amount_<?php echo $id?>_<?php echo $counter?>" />
            </td>
            <!--
            <td>
                <select style="width: 100%" name="department_<?php echo $id?>_<?php echo $counter?>" id="department_<?php echo $id?>_<?php echo $counter?>"
                        class="form-control select2">
                    <option value="0">Select</option>
                    <?php foreach(FinanceHelper::get_finance_department() as $row): ?>
                    <option value="<?php echo  $row->id?>"><?php echo  $row->name?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        <!-->
        </tr>

        <tr>
            <td>
                <select class="form-control" id="txt_nature_<?php echo $id?>_<?php echo $counter?>" name="txt_nature_<?php echo $id?>_<?php echo $counter?>">
                    <option value="0">Select</option>
                    <option value="1">FBR</option>
                    <option value="2">SRB</option>
                    <option value="3">PRA</option>
                </select>
            </td>

            <td>
                <select class="form-control" name="income_txt_nature_<?php echo $id?>_<?php echo $counter?>" id="income_txt_nature_<?php echo $id?>_<?php echo $counter?>">
                    <option value="0">Select</option>
                    <option value="1">Supplies</option>
                    <option value="2">Services</option>

                </select>
            </td>
        </tr>
 </tbody>




</table>





        <div id="dept_allocation<?php echo  $counter ?>"  style="display: none"  class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

        <div class="row">
        <div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">

            <input type="checkbox" name="dept_check_box<?php echo $counter ?>" value="1" id="dept_check_box<?php echo $counter ?>" class="">Allow Null</label>
        </div>
        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label><b>(Department)</b></label>
        </div>
    </div>

        <input type="hidden" name="dept_hidden_amount<?php echo $counter ?>" id="dept_hidden_amount<?php echo $counter ?>"/>
        <table  id="table<?php echo $counter ?>" class="table table-bordered removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

            <tbody class="addrows<?php echo $counter ?>" id="">
            <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
            <tr>

                <td style="width: 40%">

                    <select onchange="open_form(this.id)" style="width: 170px;" name="department<?php echo $counter ?>[]" id="department_<?php echo $counter ?>_1"
                            class="form-control select2 requiredField dept<?php echo $counter ?> dept_form">
                        <option value="0">Select Department &nbsp;&nbsp;&nbsp;&nbsp;</option>
                           <option  value="add_new"><b>Add New</b>  </option>
                    <?php    foreach(CommonHelper::get_all_department() as $row):?>
                        <option value="<?php echo  $row->id ?>"><?php echo ucwords($row->name) ?></option>
                     <?php   endforeach; ?>
                    </select>
                </td>
                <td>
                    <input placeholder="Percentage" value="" onkeyup="calculation_dept_item(this.id,<?php echo $counter ?>)" type="any" name="percent<?php echo $counter ?>[]" id="percent_<?php echo $counter ?>_1" class="form-control dept<?php echo $counter ?>" />
                </td>
                <td>
                    <input onblur="add('<?php echo $counter ?>',this.id)" value="0" onkeyup="calculation_dept_item_amount(this.id,'<?php echo $counter ?>')" class="department_amount<?php echo $counter ?>  form-control dept<?php echo $counter ?>" style="text-align: right" type="text" name="department_amount<?php echo $counter ?>[]" id="department_amount_<?php echo $counter ?>_1" />

                </td><!-->
            </tr>


            </tbody>
            <tr>
                <td colspan="1">Total</td>
                <td colspan="1"  style="text-align: right" id="dep_allocation_remainig_amount<?php echo $counter ?>">0</td>
                <td colspan="1"><input style="text-align: right;" readonly type="text" class="form-control" name="total_dept1" id="total_dept<?php echo $counter ?>"></td>
            </tr>
        </table>
        </div>


<div id="cost_center<?php echo $counter ?>"  style="display: none;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">



<div class="row">
        <div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-6 banks">
         <input type="checkbox" name="cost_center_check_box<?php echo  $counter ?>" value="1" id="cost_center_check_box<?php echo  $counter ?>" class="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">Allow Null</label>
        </div>


        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label><b>(Cost Center)</b></label>
        </div>


    </div>

        <input type="hidden" name="cost_center_dept_hidden_amount<?php echo $counter ?>" id="cost_center_dept_hidden_amount<?php echo $counter ?>"/>
        <table  id="cost_center_table<?php echo $counter ?>" class="table table-bordered removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

            <tbody class="cost_center_addrows<?php echo $counter ?>" id="">
            <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
            <tr>

                <td style="width: 40%">

                    <select onchange="open_form_cost_center(this.id);check_repeatition(this.id,'<?php echo $counter ?>')" style="width: 170px" name="cost_center_department<?php echo $counter ?>[]" id="cost_center_department_<?php echo $counter ?>_1"
                            class="form-control select2 CostCenter cost_allocation<?php echo $counter ?>">
                        <option value="">Select Cost Center</option>
                           <option  value=""><b>Add New</b>  </option>
                        <?php    foreach(CommonHelper::get_all_cost_center() as $row):?>
                            <option value="<?php echo  $row->id ?>"><?php echo ucwords($row->name) ?></option>
                        <?php   endforeach; ?>
                    </select>
                </td>
                <td>
                    <input placeholder="Percentage" onkeyup="cost_center_calculation_dept_item(this.id,<?php echo $counter ?>)" type="any" name="cost_center_percent<?php echo $counter ?>[]" id="cost_center_percent_<?php echo $counter ?>_1" class="form-control" />
                </td>
                <td>
                    <input onblur="add_cost_center('<?php echo $counter ?>',this.id)" onkeyup="cost_center_calculation_dept_item_amount(this.id,<?php echo $counter ?>);ddclass(this.id,'<?php echo $counter.'_1' ?>')" class="cost_center_department_amount<?php echo $counter ?> form-control" style="text-align: right" type="text" name="cost_center_department_amount<?php echo $counter ?>[]" id="cost_center_department_amount_<?php echo $counter ?>_1" class="form-control" />

                </td><!-->
            </tr>


            </tbody>
            <tr>
                <td colspan="1">Total</td>
                  <td   style="text-align: right" id="cost_center_dept_amount<?php echo $counter ?>">0</td>
                <td colspan="1"><input style="text-align: right;font-size: 12px" readonly type="text" class="form-control" name="cost_center_total_dept1" id="cost_center_total_dept<?php echo $counter ?>"></td>
            </tr>
        </table>
        </div>



        <div id="sales_tax<?php echo $counter ?>" style="display: none;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

         <div class="row">
        <div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">
            <input type="checkbox" name="sales_tax_check_box<?php echo $counter ?>" value="1" id="sales_tax_check_box<?php echo $counter ?>" class="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">Allow Null</label>
        </div>
        <div id="dept_allocation1"   class="col-lg-6 col-md-6 col-sm-6 col-xs-6 banks">
            <label>(Department For Sales Tax)</label>
        </div>
    </div>


        <input type="hidden" name="sales_tax_dept_hidden_amount<?php echo $counter ?>" id="sales_tax_dept_hidden_amount<?php echo $counter ?>"/>
        <table  id="sales_tax_table<?php echo $counter ?>" class="table table-bordered removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

            <tbody class="sales_tax_addrows<?php echo $counter ?>" id="">
            <input type="hidden" name="" class="form-control requiredField" id="" value="1" />
            <tr>

                <td style="width: 40%">

                    <select onchange="open_form(this.id)" style="width: 170px" name="sales_tax_department<?php echo $counter ?>[]" id="sales_tax_department_<?php echo $counter ?>_1"
                            class="form-control select2 dept_form">
                        <option value="0">Select Department</option>
                            <option  value="add_new"><b>Add New</b>  </option>
                        <?php    foreach(CommonHelper::get_all_department() as $row):?>

                            <option value="<?php echo  $row->id ?>"><?php echo ucwords($row->name) ?></option>
                        <?php   endforeach; ?>
                    </select>
                </td>
                <td>
                    <input placeholder="Percentage" onkeyup="sales_tax_calculation_dept_item(this.id,<?php echo $counter ?>)" type="any" name="sales_tax_percent<?php echo $counter ?>[]" id="sales_tax_percent_<?php echo $counter ?>_1" class="form-control" />
                </td>
                <td>
                    <input onblur="add_sales_tax('<?php echo $counter ?>',this.id)" onkeyup="sales_tax_calculation_dept_item_amount(this.id,<?php echo $counter ?>)" class="sales_tax_department_amount<?php echo $counter ?> form-control" style="text-align: right" type="text" name="sales_tax_department_amount<?php echo $counter ?>[]" id="sales_tax_department_amount_<?php echo $counter ?>_1" class="form-control" />

                </td><!-->
            </tr>


            </tbody>
            <tr>
                <td colspan="2">Total</td>
                <td colspan="1"><input style="text-align: right;" readonly type="text" class="form-control" name="sales_tax_total_dept1" id="sales_tax_total_dept<?php echo $counter ?>"></td>
            </tr>
        </table>
        </div>







            <!-->
        <?php
    }

    public function addMorJobOrderRow()
    {

        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
        ?>


        <table  id="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>" class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 100px" class="text-center">Product <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 200px;" class="text-center">Width. <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 200px;" class="text-center">Height. <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 200px;" class="text-center">Depth. <span class="rflabelsteric"><strong>*</strong></span></th>
                <th style="width: 150px;" class="text-center" style="">Quantity <span class="rflabelsteric"><strong>*</strong></span></th>

            </tr>
            </thead>
            <tbody class="" id="">

            <tr id="">

                <input type="hidden" name="demandDataSection_<?php echo $id?>[]" class="form-control requiredField" id="demandDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
                <td>
                    <select style="width: 200px;"  class="form-control requiredField select2"  id="product_<?php echo $id?>_<?php echo $counter?>" name="product_<?php echo $id?>_<?php echo $counter?>" onchange="">
                        <option value="">---Select---</option>
                        <?php foreach(CommonHelper::get_all_products() as $row): ?>
                        <option value="<?= $row->product_id ?>"> <?= ucwords($row->p_name) ?> </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <input type="text" name="width_<?php echo $id?>_<?php echo $counter?>" id="width_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
                </td>

                <td>
                    <input type="text" name="height_<?php echo $id?>_<?php echo $counter?>" id="height_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
                </td>

                <td>
                    <input type="text" name="depth_<?php echo $id?>_<?php echo $counter?>" id="depth_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField" />
                </td>

                <td>
                    <input type="number" step="0.01" name="qty_<?php echo $id?>_<?php echo $counter?>" id="qty_<?php echo $id?>_<?php echo $counter?>" class="form-control qty requiredField" />
                </td>

            </tr>

            <tr>
                <td colspan="5"><label class="sf-label">Description</label>
                    <span class="rflabelsteric"><strong>*</strong></span>
                    <textarea name="description_<?php echo $id?>_<?php echo $counter?>" id="description_<?php echo $id?>_<?php echo $counter?>" rows="3" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                </td>
            </tr>

            </tbody>
        </table>


        <div class="lineHeight">&nbsp;</div>

        <?php
    }

    public function ClientInfo(){
        $id  = $_GET['id'];
        $m  = $_GET['m'];
//        $client=$client->SetConnection('mysql2');
//        $client = Client::where('status','=','1')->where('id','=',$id)->first();
//        CommonHelper::reconnectMasterDatabase();
//        echo $client->address;

        $client=new Client();
        $client=$client->SetConnection('mysql2');
        $client=$client->where('status',1)->where('id',$id)->first();
        echo $client=$client->address;
    }
    public function GetBranch(){
        $ClientName = $_GET['ClientName'];
        $Selected = $_GET['Selected'];

        $Branch=new Branch();
        $Branch=$Branch->SetConnection('mysql2');
        $Branch=$Branch->where('status',1)->where('client_id',$ClientName)->get();
        $Html = '';
        $Html = '<option value="">Select Branch</option>';

        foreach($Branch as $Fil):
            $Html .= '<option value="'.$Fil->id.'" ';
            if($Selected == $Fil->id):
            $Html .= 'selected';
            endif;
                $Html.='>'.$Fil->branch_name.'</option>';
        endforeach;
        echo $Html;
    }




    public function makeFormDemandVoucher(){
        $id  = $_GET['id'];
        $m  = $_GET['m'];
        $departments = DB::select('select `id`,`department_name` from `department` where `company_id` = '.$m.'');
    ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="<?php echo $id?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Slip No.</label>
                        <input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_<?php echo $id?>" id="slip_no_<?php echo $id?>" value="" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Demand Date.</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="demand_date_<?php echo $id?>" id="demand_date_<?php echo $id?>" value="<?php echo date('Y-m-d') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <label class="sf-label">Department / Sub Department</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <select class="form-control requiredField" name="sub_department_id_<?php echo $id?>" id="sub_department_id_<?php echo $id?>">
                            <option value="">Select Department</option>
                            @foreach($departments as $key => $y)
                            <?php foreach ($departments as $key => $y){?>
                            <optgroup label="<?php echo $y->department_name; ?>" value="<?php $y->id; ?>">
                                <?php $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');?>
                                <?php foreach ($subdepartments as $key2 => $y2){?>
                                <option value="<?php echo $y2->id ?>"><?php echo $y2->sub_department_name ?></option>
                                <?php }?>
                            </optgroup>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label">Demand Type</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <select class="form-control requiredField" name="demand_type_<?php echo $id?>" id="demand_type_<?php echo $id?>">
                            <option value="">Select Demand Type</option>
                            <option value="1">Office Use</option>
                            <option value="2">For Sale</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="sf-label">Description</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <textarea name="description_<?php echo $id?>" id="description_<?php echo $id?>" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="well">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="buildyourform" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Category <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center">Sub Item <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center">Description <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center" style="width:100px;">Qty in Unit <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center" style="width:100px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="addMoreDemandsDetailRows_<?php echo $id?>" id="addMoreDemandsDetailRows_<?php echo $id?>">
                                    <input type="hidden" name="demandDataSection_<?php echo $id?>[]" class="form-control requiredField" id="demandDataSection_<?php echo $id?>" value="1" />
                                    <tr>
                                        <td>
                                            <select name="category_id_<?php echo $id?>_1" id="category_id_<?php echo $id?>_1" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                                                <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="sub_item_id_<?php echo $id?>_1" id="sub_item_id_<?php echo $id?>_1" class="form-control requiredField">
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="description_<?php echo $id?>_1" id="description_<?php echo $id?>_1" class="form-control requiredField" />
                                        </td>
                                        <td>
                                            <input type="number" name="qty_<?php echo $id?>_1" id="qty_<?php echo $id?>_1" class="form-control requiredField" />
                                        </td>
                                        <td class="text-center">---</td>
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
                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('<?php echo $id?>')" value="Add More Demand's Rows" />
            </div>
        </div>
    <?php
    }

    public function makeFormGoodsReceiptNoteDetailByPRNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['prNo']);
        $prNo = $makeGetValue[0];
        $prDate = $makeGetValue[1];

        CommonHelper::companyDatabaseConnection($m);

        $purchaseRequestDetail = PurchaseRequest::where('status','=','1')->where('purchase_request_no','=',$prNo)->first();
        $purchaseRequestDataDetail = PurchaseRequestData::where('status','=','1')->where('purchase_request_no','=',$prNo)->get();
        CommonHelper::reconnectMasterDatabase();
        $po_type=$purchaseRequestDetail->po_type;
        $currency_id=$purchaseRequestDetail->currency_id;
        $currency_rate=$purchaseRequestDetail->currency_rate;
        return view('Purchase.AjaxPages.makeFormGoodsReceiptNoteDetailByPRNo',compact('purchaseRequestDetail','purchaseRequestDataDetail','po_type','currency_id','currency_rate','prNo','prDate'));
    }
    

    public function makeFormGoodsReceiptNoteDetailByPRNoManual(){
        $m = $_GET['m'];

        $prNo = $_GET['prNo'];


        CommonHelper::companyDatabaseConnection($m);

        $purchaseRequestDetail = PurchaseRequest::where('status','=','1')->where('purchase_request_no','=',$prNo)->first();
        $purchaseRequestDataDetail = PurchaseRequestData::where('status','=','1')->where('purchase_request_no','=',$prNo)->get();
        if ($purchaseRequestDetail) { 
            $prDate = $purchaseRequestDetail->purchase_request_date;
            CommonHelper::reconnectMasterDatabase();
            $po_type=$purchaseRequestDetail->po_type;
            $currency_id=$purchaseRequestDetail->currency_id;
            $currency_rate=$purchaseRequestDetail->currency_rate;
            return view('Purchase.AjaxPages.makeFormGoodsReceiptNoteDetailByPRNo',compact('purchaseRequestDetail','purchaseRequestDataDetail','po_type','currency_id','currency_rate','prDate','prNo'));
        }
        else{
            return "<p>No Data Found</p>";
        }
    }


    public function makeFormGoodsReceiptNoteDetailByGrnNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['GrnNo']);
        $GrnId = $makeGetValue[0];
        $GrnNo = $makeGetValue[1];
        $GrnDate = $makeGetValue[2];

        CommonHelper::companyDatabaseConnection($m);

        $DataMaster = GoodsReceiptNote::where('status','=','1')->whereIn('grn_status',[2,3])->where('grn_no','=',$GrnNo)->first();
        $DataDetail = GRNData::where('status','=','1')->whereIn('grn_status',[2,3])->where('grn_no','=',$GrnNo)->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Purchase.AjaxPages.makeFormGoodsReceiptNoteDetailByGrnNo',compact('DataMaster','DataDetail'));
    }



    public function get_detail_purchase_voucher()
    {
        $id = $_GET['id'];
        $sub_item=CommonHelper::sub_item_connection()->where('status',1)->where('id',$id)->select('uom','rate','pack_size','description','itemType')->first();
        $uom=CommonHelper::uom_connection()->where('status',1)->where('id',$sub_item->uom)->select('uom_name')->first();
        $grn_data=new GRNData();
        $grn_data=$grn_data->SetConnection('mysql2');
        $grn_data=$grn_data->where('status',1)->where('grn_status',4);

        $demand_type=new DemandType();
        $demand_type=$demand_type->SetConnection('mysql2');
        $demand_type=$demand_type->where('status',1)->where('id',$sub_item->itemType)->select('name')->first();
        $demand_type_name=$demand_type->name;


        if ($grn_data->count()>0):
        $qty=$grn_data->select('sum(purchaseRequestQty)as qty')->first();
        else:
        $qty=0;
        endif;

        $grn_data=DB::Connection('mysql2')->selectOne('select max(purchase_approved_qty)approve_qty,max(purchase_recived_qty)recived_qty from grn_data where status=1
        and sub_item_id="'.$id.'"');



        echo $uom->uom_name.'*'.$sub_item->rate.'*'.$sub_item->uom.'*'.$sub_item->pack_size.'*'
            .$sub_item->description.'*'.$demand_type_name.'*'.$qty.'*'.$sub_item->itemType.'*'.
             $grn_data->approve_qty.'*'.$grn_data->recived_qty;

    }

      public static function get_po(){
      $id = $_GET['supplier_id'];
        echo '<option value="">Select PO</option>';

        $purchaseRequest = new PurchaseRequest();
        $purchaseRequest=$purchaseRequest->SetCOnnection('mysql2');
        $purchaseRequest =$purchaseRequest->where('status',1)->where('supplier_id',$id)->where('purchase_request_status',2)->select('id','purchase_request_no','purchase_request_date')->get();

        foreach($purchaseRequest as $row){
    ?>
            <option value="<?php echo $row->purchase_request_no.'*'.$row->purchase_request_date;?>"> <?php echo strtoupper($row->purchase_request_no).' => '.CommonHelper::changeDateFormat($row->purchase_request_date)?></option>
    <?php
        }
    }


    public static function getGrnNoBySupplier(){
        $id = $_GET['supplier_id'];
        echo '<option value="">Select Grn No</option>';

        $GoodsReceiptNote = new GoodsReceiptNote();
        $GoodsReceiptNote=$GoodsReceiptNote->SetCOnnection('mysql2');
        $GoodsReceiptNote =$GoodsReceiptNote->where('status',1)->where('supplier_id',$id)->whereIn('grn_status',[2,3])->select('id','grn_no','grn_date')->get();

        foreach($GoodsReceiptNote as $row){
            ?>
            <option value="<?php echo $row->id.'*'.$row->grn_no.'*'.$row->grn_date;?>"> <?php echo strtoupper($row->grn_no).' => '.CommonHelper::changeDateFormat($row->grn_date)?></option>
            <?php
        }
    }


    public  static function get_refrence_nature($nature)
    {



        if ($nature==1):
            $nature='Opening';
        endif;
        if ($nature==2):
            $nature='Advance';
        endif;
        if ($nature==3):
            $nature='Receivable';
        endif;

        return $nature;

    }
    public  function get_refer(){
        $SupplierId = $_GET['SupplierId'];
        echo '<option value="">Select</option>';

//        $breakupData = new BreakupData();
//        $breakupData=$breakupData->SetCOnnection('mysql2');
//        $breakupData =$breakupData->where('status',1)->where('supplier_id',$SupplierId)->whereIn('breakup_type',[0,4])->whereIn('advnace',[1])->select('id','slip_no','amount')->get();
        $breakupData  = DB::Connection('mysql2')->select("select id,slip_no,amount,refrence_nature,main_id from breakup_data
                                            where supplier_id = ".$SupplierId."
                                            and refrence_nature in(1,2,3)
                                            and status = 1
                                            group by main_id");?>


        <option value="new"> New Refrence</option>
       <?php  foreach($breakupData as $row):

            $refrence= $this::get_refrence_nature($row->refrence_nature);
            $amount=   Payment_through_jvs::get_remaining_amount($row->main_id,$SupplierId);
            if($amount!=0):
            ?>

            <option value="<?php echo $row->id.','.$row->main_id;?>"> <?php echo  $refrence.'=>'.strtoupper($row->main_id).' => '.number_format($amount,2);?></option>
            <?php endif; endforeach;

    }


    public function new_refrence(Request $request)
    {
        $refrence= $request->refrence;
        $supplier_id= $request->vendor_id;
        if ($refrence==1):
            $refrence= DB::Connection('mysql2')->table('pvs')->max('id');
            $refrence= $refrence+1;
            ?>
            <label class="sf-label">Refrence</label>
            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
            <input  class="form-control" type="text" name="purchase_refrence" id="" value="<?php echo 'Refrence '.$refrence; ?>"/>

            <?php

        else:
            $breakupData  = DB::Connection('mysql2')->select("select id,slip_no,amount,refrence_nature,main_id from breakup_data
                                            where supplier_id = ".$supplier_id."
                                            and refrence_nature in (1,2,3)
                                            and status = 1
                                            group by main_id,supplier_id
                                            ");?>
            <label class="sf-label">Refrence</label>
            <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>

            <select onchange="get_ledger_refrence_wise()" class="form-control"  name="purchase_refrence" id="purchase_refrence">
                <option value="0">SELECT</option>
                <?php   foreach($breakupData as $row):

                    $refrence= $this::get_refrence_nature($row->refrence_nature);

                 $amount=   Payment_through_jvs::get_remaining_amount($row->main_id,$supplier_id);


                    if($amount!=0):
                    ?>

                    <option value="<?php echo $row->id.','.$row->main_id;?>"> <?php echo  $refrence.'=>'.strtoupper($row->main_id).' => '.$amount;?></option>
                <?php endif; endforeach; ?>
            </select>

        <?php    endif;
    }

    public static function get_stock(Request $request)
    {


        $item=$request->item;
       $sub_ic_data= CommonHelper::get_subitem_detail($item);
        $uom_id=explode(',',$sub_ic_data);
         $uom_id=$uom_id[0];

      $uom_name=  CommonHelper::get_uom_name($uom_id);

        $region=$request->region;
        $grn= $stock=DB::Connection('mysql2')->selectOne('select sum(qty)qty from stock
        where region_id="'.$region.'" and voucher_type="1" and sub_item_id="'.$item.'" group by sub_item_id');

        if (!empty($grn->qty)):
            $grn=$grn->qty;
            endif;

        $return= $stock=DB::Connection('mysql2')->selectOne('select sum(qty)qty from stock
        where region_id="'.$region.'" and voucher_type="3" and sub_item_id="'.$item.'" group by sub_item_id');

        if (!empty($return->qty)):
            $return=$return->qty;
        endif;

        $grn=$grn+$return;
        $issuence= $stock=DB::Connection('mysql2')->selectOne('select sum(qty)qty from stock
        where region_id="'.$region.'" and voucher_type="2" and sub_item_id="'.$item.'" group by sub_item_id');


        if (!empty($issuence->qty)):
            $issuence=$issuence->qty;
        endif;
     return   $total=$grn-$issuence.','.$uom_name;
    }


    function get_sub_item_all_ajax(Request $request)
    {
        $Category = $request->CategoryId;
        $SubCategory = $request->SubCategoryId;
        $SubItemData = DB::Connection('mysql2')->table('subitem')->where('main_ic_id',$Category)->where('sub_category_id',$SubCategory)->where('status',1)->get();
        echo '<option value="">Select Item</option>';
        foreach($SubItemData as $Fil):
            echo '<option value="'.$Fil->id.'">'.$Fil->sub_ic.'</option>';
        endforeach;
    }

    public static function deleteJobOrderData()
    {
        $job_order_data_id = $_GET['id'];
        $type = '';
        $Estimate = new Estimate();
        $Estimate->SetConnection('mysql2');
        $Estimate = $Estimate->where('status',1)->where('job_order_data_id','=',$job_order_data_id)->count();
        if($Estimate>0)
        {
            //echo "If You Press Ok ESTIMATE Also Delete";
            echo $type=1;
        }
        else
        {
            $JobOrderData = new JobOrderData();
            $JobOrderData->SetConnection('mysql2');
            $data['status'] = '0';
            $JobOrderData->where('job_order_data_id', $job_order_data_id)->update($data);
            echo $type=2;
        }
    }

    public static function deleteJobOrderAndEstimate()
    {
        $job_order_data_id = $_GET['id'];

        $JobOrderData = new JobOrderData();
        $JobOrderData->SetConnection('mysql2');
        $data['status'] = '0';
        $JobOrderData->where('job_order_data_id', $job_order_data_id)->update($data);

        $Estimate = new Estimate();
        $Estimate->SetConnection('mysql2');
        $data['status'] = '0';
        $Estimate->where('job_order_data_id', $job_order_data_id)->update($data);
    }

    public  static function get_category_wise_sub_category(Request $request)
    {
        $CategoryId = $request->category_id;
        $SubCategory = DB::Connection('mysql2')->table('sub_category')->where('status',1)->where('category_id',$CategoryId)->get();
        echo '<option value="">Select Sub Category</option>';
        foreach($SubCategory as $Fil):?>
            <option value="<?php echo $Fil->id?>"><?php echo $Fil->sub_category_name;?></option>
        <?php endforeach;
    }



}
?>