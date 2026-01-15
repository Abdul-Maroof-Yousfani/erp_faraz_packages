<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\Account;
class SaleMakeFormAjaxLoadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function subItemListLoadDepandentCategoryId(){
        $id = $_GET['id'];
        $m = $_GET['m'];
        $value = $_GET['value'];
        return SaleHelper::subItemList($m,$id,$value);
    }

    public function addMoreSalesRvsDetailRows(){
        $m = Input::get('m');
        $counter = Input::get('counter');
        $code = Input::get('code');
        CommonHelper::companyDatabaseConnection($m);
		// $accounts = new Account;
		// $accounts = $accounts::orderBy('level1', 'ASC')
		// 	->orderBy('level2', 'ASC')
		// 	->orderBy('level3', 'ASC')
		// 	->orderBy('level4', 'ASC')
		// 	->orderBy('level5', 'ASC')
		// 	->orderBy('level6', 'ASC')
		// 	->orderBy('level7', 'ASC')
		// 	->get();
        $accounts = CommonHelper::get_all_account_operat_with_unique_code($code);

		CommonHelper::reconnectMasterDatabase();
        ?>
            <tr id="removeSaleRvsRows_<?php echo $counter;?>">
                <td>
                <input type="hidden" name="seletedSaleOrderRow[]" readonly id="seletedSaleOrderRow" value="<?php echo $counter;?>" class="form-control" />
                    <select class="form-control requiredField" name="account_id_<?php echo $counter?>" id="account_id_<?php echo $counter?>">
                        <option value="">Select Account</option>
                        <?php 
                            foreach($accounts as $key => $y){
                        ?>
                                <option value="<?php echo $y->id;?>"><?php echo $y->code .' ---- '. $y->name;?></option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="debit_<?php echo $counter?>" id="debit_<?php echo $counter?>" class="form-control ddAmount requiredField" value="" placeholder="Please Put Debit Amount" onkeyup="checkReceiptAmount(this.id)" />
                </td>
                <td class="text-center"><a onclick="removeSaleRvsRows('<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</a></td>
            </tr>
        <?php
    }

    public function loadSaleReceiptVoucherDetailBySQNo(){
        $m = Input::get('m');
		$makeGetValue = explode('*',$_GET['sqDetail']);
		$sqNo = $makeGetValue[0];
        $sqDate = $makeGetValue[1];
        // $saleOrderDetail = DB::Connection('mysql2')->table('sales_order')->where('so_no',$soNo)->first();
        // $saleOrderDataDetail = DB::Connection('mysql2')->table('sales_order_data')->where('so_no',$soNo)->get();
        $saleQuotationDetail = DB::Connection('mysql2')->table('sale_quotations')->where('quotation_no',$sqNo)->first();
        $saleQuotationDataDetail = DB::Connection('mysql2')->table('sale_quotation_datas')->where('quotation_no',$sqNo)->get();
        CommonHelper::companyDatabaseConnection($m);
		// $accounts = new Account;
		// $accounts = $accounts::orderBy('level1', 'ASC')
		// 	->orderBy('level2', 'ASC')
		// 	->orderBy('level3', 'ASC')
		// 	->orderBy('level4', 'ASC')
		// 	->orderBy('level5', 'ASC')
		// 	->orderBy('level6', 'ASC')
		// 	->orderBy('level7', 'ASC')
		// 	->get();

        // bank CODE 1-2-8

        // cash code 1-2-9
        $code = "1-2-8,1-2-9";
 
        $accounts = CommonHelper::get_all_account_operat_with_unique_code($code);
        
        CommonHelper::reconnectMasterDatabase();
        // dd($saleOrderDetail , $saleOrderDataDetail);
        return view('Sales.AjaxPages.loadSaleReceiptVoucherDetailBySONo',compact('saleQuotationDetail','saleQuotationDataDetail','accounts'));
    }

    public function loadSaleReceiptVoucherDetailBySONo(){
        $m = Input::get('m');
		$makeGetValue = explode('*',$_GET['sqDetail']);
        // echo "<pre>";
        // print_r($makeGetValue);
        // exit();
		$sqNo = $makeGetValue[0];
        $sqDate = $makeGetValue[1];
        // $saleOrderDetail = DB::Connection('mysql2')->table('sales_order')->where('so_no',$soNo)->first();
        // $saleOrderDataDetail = DB::Connection('mysql2')->table('sales_order_data')->where('so_no',$soNo)->get();
        $saleQuotationDetail = DB::Connection('mysql2')->table('sales_order')->where('so_no',$sqNo)->first();
        $saleQuotationDataDetail = DB::Connection('mysql2')->table('sales_order_data')->where('so_no',$sqNo)->get();
        CommonHelper::companyDatabaseConnection($m);
		// $accounts = new Account;
		// $accounts = $accounts::orderBy('level1', 'ASC')
		// 	->orderBy('level2', 'ASC')
		// 	->orderBy('level3', 'ASC')
		// 	->orderBy('level4', 'ASC')
		// 	->orderBy('level5', 'ASC')
		// 	->orderBy('level6', 'ASC')
		// 	->orderBy('level7', 'ASC')
		// 	->get();

        // bank CODE 1-2-8

        // cash code 1-2-9
        $code = "1-2-8,1-2-9";
 
        $accounts = CommonHelper::get_all_account_operat_with_unique_code($code);
        
        CommonHelper::reconnectMasterDatabase();
        // dd($saleOrderDetail , $saleOrderDataDetail);
        return view('Sales.AjaxPages.loadSaleReceiptVoucherDetailBySONo',compact('saleQuotationDetail','saleQuotationDataDetail','accounts'));
    }

    public function bank_or_cash_options(Request $request)
    {
        if($request->receiptType == 1)
        {
            $code = '1-2-8';
        }
        if($request->receiptType == 2)
        {
            $code = '1-2-9';
        }

        $accounts = CommonHelper::get_all_account_operat_with_unique_code($code);

        
        ?>
            <option value="">Select Account</option>
        
            <?php foreach($accounts as $key => $y){ ?>
            <option value="<?php echo $y->id; ?> "><?php echo $y->code .' ---- '. $y->name; ?></option>
            
        <?php 
            }
    }


    public function addMoreCreditSaleDetailRows(){
        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
        ?>
        <tr id="removeCreditSaleRows_<?php echo $id?>_<?php echo $counter?>">
            <input type="hidden" name="creditSaleDataSection_<?php echo $id?>[]" class="form-control requiredField" id="creditSaleDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
            <td>
                <select name="category_id_<?php echo $id?>_<?php echo $counter?>" id="category_id_<?php echo $id?>_<?php echo $counter?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                    <?php echo SaleHelper::categoryList($_GET['m'],'0');?>
                </select>
            </td>
            <td>
                <select name="sub_item_id_<?php echo $id?>_<?php echo $counter?>" id="sub_item_id_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField">
                </select>
            </td>
            <td>
                <input type="text" name="description_<?php echo $id?>_<?php echo $counter?>" id="description_<?php echo $id?>_<?php echo $counter?>" placeholder="Description" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" name="price_<?php echo $id?>_<?php echo $counter?>" id="price_<?php echo $id?>_<?php echo $counter?>" onkeyup="updateAmount(this.id,<?php echo $id?>,<?php echo $counter?>)" placeholder="Price" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" name="qty_<?php echo $id?>_<?php echo $counter?>" id="qty_<?php echo $id?>_<?php echo $counter?>" onkeyup="updateAmount(this.id,<?php echo $id?>,<?php echo $counter?>)" onchange="checkQuantityinStock(this.id,<?php echo $id?>,<?php echo $counter?>,'category_id_<?php echo $id?>_<?php echo $counter?>','sub_item_id_<?php echo $id?>_<?php echo $counter?>')" placeholder="Quantity" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" readonly name="amount_<?php echo $id?>_<?php echo $counter?>" id="amount_<?php echo $id?>_<?php echo $counter?>" placeholder="Amount" class="form-control requiredField" />
            </td>
            <td class="text-center"><button  onclick="removeCreditSaleRows('<?php echo $id?>','<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</button></td>
        </tr>
        <?php
    }

    public function addMoreCashSaleDetailRows(){
        $counter = $_GET['counter'];
        $m = $_GET['m'];
        $id = $_GET['id'];
        ?>
        <tr id="removeCashSaleRows_<?php echo $id?>_<?php echo $counter?>">
            <input type="hidden" name="cashSaleDataSection_<?php echo $id?>[]" class="form-control requiredField" id="cashSaleDataSection_<?php echo $id?>" value="<?php echo $counter?>" />
            <td>
                <select name="category_id_<?php echo $id?>_<?php echo $counter?>" id="category_id_<?php echo $id?>_<?php echo $counter?>" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField">
                    <?php echo SaleHelper::categoryList($_GET['m'],'0');?>
                </select>
            </td>
            <td>
                <select name="sub_item_id_<?php echo $id?>_<?php echo $counter?>" id="sub_item_id_<?php echo $id?>_<?php echo $counter?>" class="form-control requiredField">
                </select>
            </td>
            <td>
                <input type="text" name="description_<?php echo $id?>_<?php echo $counter?>" id="description_<?php echo $id?>_<?php echo $counter?>" placeholder="Description" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" name="price_<?php echo $id?>_<?php echo $counter?>" onkeyup="updateAmount(this.id,<?php echo $id?>,<?php echo $counter?>)" id="price_<?php echo $id?>_<?php echo $counter?>" placeholder="Price" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" name="qty_<?php echo $id?>_<?php echo $counter?>" onkeyup="updateAmount(this.id,<?php echo $id?>,<?php echo $counter?>)" onchange="checkQuantityinStock(this.id,<?php echo $id?>,<?php echo $counter?>,'category_id_<?php echo $id?>_<?php echo $counter?>','sub_item_id_<?php echo $id?>_<?php echo $counter?>')" id="qty_<?php echo $id?>_<?php echo $counter?>" placeholder="Quantity" class="form-control requiredField" />
            </td>
            <td>
                <input type="number" readonly name="amount_<?php echo $id?>_<?php echo $counter?>" id="amount_<?php echo $id?>_<?php echo $counter?>" placeholder="Amount" class="form-control requiredField" />
            </td>
            <td class="text-center"><button  onclick="removeCashSaleRows('<?php echo $id?>','<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</button></td>
        </tr>
        <?php
    }

    public function makeFormCreditSaleVoucher(){
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
        return view('Purchase.AjaxPages.makeFormGoodsReceiptNoteDetailByPRNo',compact('purchaseRequestDetail','purchaseRequestDataDetail'));
    }

    public function deleteSurvey()
    {
        $m = $_GET['m'];
        $id = $_GET['id'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data = DB::table('survey')->where('survey_id', $id)->first();

        if($id != ""):
            DB::table('survey')
                ->where('survey_id', $id)
                ->update(['status' => 2]);

            DB::table('survey_data')
                ->where('survey_id', $id)
                ->update(['status' => 2]);
        endif;
        CommonHelper::reconnectMasterDatabase();

        $voucher_no     = $data->tracking_no;
        $voucher_date   = $data->survey_date;
        $action_type    = 3;
        $client_id      = $data->client_id;
        $table_name     = "survey";
        CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);

        //return Redirect::to('sales/surveylist?pageType='.$_GET['pageType'].'&&parentCode='.$_GET['parentCode'].'&&m='.$_GET['m'].'#SFR');
    }

    public function jobOrderDelete()
    {
        $m = $_GET['m'];
        $id = $_GET['id'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data = DB::table('job_order')->where('job_order_id', $id)->first();

        if($id != ""):
            DB::table('job_order')
                ->where('job_order_id', $id)
                ->update(['status' => 2]);

            DB::table('job_order_data')
                ->where('job_order_id', $id)
                ->update(['status' => 2]);
        endif;
        CommonHelper::reconnectMasterDatabase();

        $voucher_no     = $data->job_order_no;
        $voucher_date   = $data->date_ordered;
        $action_type    = 3;
        $client_id      = $data->client_name;
        $table_name     = "job_order";
        CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);

    }

    public function job_Order_Jvc_Submitted()
    {
        $m = $_GET['m'];
        $id = $_GET['id'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        if($id != ""):
            $data['jvc_to_managaer'] = 1;
            $data['jvc_submitted_by'] = Auth::user()->name;
            DB::table('job_order')
                ->where('job_order_id', $id)
                ->update($data);
        endif;
        CommonHelper::reconnectMasterDatabase();
    }

    public function QuotationDelete()
    {
        $m = $_GET['m'];
        $id = $_GET['id'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data = DB::table('quotation')->where('id', $id)->first();

        if($id != ""):
            DB::table('quotation')
                ->where('id', $id)
                ->update(['status' => 2]);

            DB::table('quotation_data')
                ->where('master_id', $id)
                ->update(['status' => 2]);
        endif;
        CommonHelper::reconnectMasterDatabase();

        $voucher_no     = $data->quotation_no;
        $voucher_date   = $data->quotation_date;
        $action_type    = 3;
        $client_id      = $data->client_id;
        $table_name     = "quotation";
        CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);

    }

    public function invoiceDelete()
    {
        $m = $_GET['m'];
        $id = $_GET['id'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data = DB::table('invoice')->where('id', $id)->first();

        $job_order_no=$data->job_order_no;

        if($id != ""):
            DB::table('invoice')
                ->where('id', $id)
                ->update(['status' => 0]);

            DB::table('inv_data')
                ->where('master_id', $id)
                ->update(['status' => 0]);

            $job_order_data=explode(',',$job_order_no);
            foreach($job_order_data as $row):
                DB::table('job_order')
                    ->where('job_order_no', $row)
                    ->update(['invoice_created' => 0]);
            endforeach;
       
        endif;
        CommonHelper::reconnectMasterDatabase();

        $voucher_no     = $data->inv_no;
        $voucher_date   = $data->inv_date;
        $action_type    = 3;
        $client_id      = $data->bill_to_client_id;
        $table_name     = "invoice";
        CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);
    }


}
?>