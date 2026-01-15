<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Employee;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\Account;
use App\Models\GoodsReceiptNote;
use App\Models\GRNData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Transactions;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Cluster;
use App\Models\Region;

class FinanceMakeFormAjaxLoadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function addMoreJournalDetailRows(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $_GET['id'];
        $_GET['counter'];
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        ?>
        <tr id="removeJvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
            <input type="hidden" name="jvsDataSection_<?php echo $_GET['id']?>[]" class="form-control requiredField" id="jvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
            <td>
                <select class="form-control requiredField select2" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
                    <option value="">Select Account</option>
                    <?php foreach($accounts as $row){?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
                    <?php }?>
                </select>
            </td>
            <td>
                <input placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="any" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
            </td>
            <td>
                <input placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="any" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
            </td>
            <td class="text-center"><a href="#" onclick="removeJvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</a></td>
        </tr>
        <?php
	}

	public function addMorePurchaseBankPvsDetailRows(){
        $m = Input::get('m');
        $counter = Input::get('counter');
        CommonHelper::companyDatabaseConnection($m);
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		CommonHelper::reconnectMasterDatabase();
        ?>
            <tr id="removePurchaseBankPvsRows_<?php echo $counter;?>">
                <td>
                <input type="hidden" name="seletedPurchaseOrderRow[]" readonly id="seletedPurchaseOrderRow" value="<?php echo $counter;?>" class="form-control" />
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
                    <input type="number" name="credit_<?php echo $counter?>" id="credit_<?php echo $counter?>" class="form-control ccAmount requiredField" value="" placeholder="Please Put Credit Amount" onkeyup="checkPaidAmount(this.id)" />
                </td>
                <td class="text-center"><a onclick="removePurchaseBankPvsRows('<?php echo $counter?>')" class="btn btn-xs btn-danger">Remove</a></td>
            </tr>
        <?php
    }
	
	public function loadPurchaseBankPaymentVoucherDetailByPONo(){
		$m = Input::get('m');
		$makeGetValue = explode('*',$_GET['poDetail']);
		$poNo = $makeGetValue[0];
        $poDate = $makeGetValue[1];
		$purchaseOrderDetail = DB::connection('mysql2')->selectOne('SELECT sum(b.net_amount) as totalAmount,a.* FROM `purchase_request` as a INNER JOIN purchase_request_data b ON a.purchase_request_no = b.purchase_request_no where a.purchase_request_no = "'.$poNo.'"');
		$purchaseOrderDataDetail = DB::connection('mysql2')->select('select * from purchase_request where purchase_request_no = "'.$poNo.'"');
		CommonHelper::companyDatabaseConnection($m);
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();
		CommonHelper::reconnectMasterDatabase();
		return view('Finance.AjaxPages.loadPurchaseBankPaymentVoucherDetailByPONo',compact('purchaseOrderDetail','purchaseOrderDataDetail','accounts'));
	}

    public function makeFormJournalVoucher(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $_GET['id'];
        $currentDate = date('Y-m-d');
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="jvsSection[]" id="jvsSection" class="form-control requiredField" value="<?php echo $_GET['id'];?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">Slip No.</label>
                        <input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_<?php echo $_GET['id']?>" id="slip_no_<?php echo $_GET['id']?>" value="" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label class="sf-label">JV Date.</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="jv_date_<?php echo $_GET['id']?>" id="jv_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="sf-label">Description</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <textarea name="description_<?php echo $_GET['id']?>" id="description_<?php echo $_GET['id']?>" style="resize:none;" class="form-control requiredField"></textarea>
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
                                <table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Account Head <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                                        <th class="text-center" style="width:150px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="addMoreJvsDetailRows_<?php echo $_GET['id']?>" id="addMoreJvsDetailRows_<?php echo $_GET['id']?>">
                                    <?php for($j = 1 ; $j <= 2 ; $j++){?>
                                        <input type="hidden" name="jvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="jvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $j?>" />
                                        <tr>
                                            <td>
                                                <select class="form-control requiredField" name="account_id_<?php echo $_GET['id']?>_<?php echo $j?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $j?>">
                                                    <option value="">Select Account</option>
                                                    <?php foreach($accounts as $row){?>
                                                        <option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
                                            <td>
                                                <input placeholder="Debit" class="form-control requiredField d_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td>
                                                <input placeholder="Credit" class="form-control requiredField c_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td class="text-center">---</td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="d_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="d_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="c_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="c_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;"></td>
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
                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreJvsDetailRows('<?php echo $_GET['id']?>')" value="Add More JV's Rows" />
            </div>
        </div>
        <?php
    }


	public function addMoreCashPvsDetailRows(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$_GET['counter'];
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<tr id="removePvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
			<input type="hidden" name="pvsDataSection_<?php echo $_GET['id']?>[]" class="form-control requiredField" id="pvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
			<td>
				<select class="form-control requiredField  select2" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value="">Select Account</option>
					<?php foreach($accounts as $row){?>
						<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
					<?php }?>
				</select>
			</td>
			<td>
				<input placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="any" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
			</td>
			<td>
				<input placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="any" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
			</td>
			<td class="text-center"><a href="#" onclick="removePvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</a></td>
		</tr>
	<?php
	}


	 
	public function makeFormCashPaymentVoucher(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$currentDate = date('Y-m-d');
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="pvsSection[]" id="pvsSection" class="form-control requiredField" value="<?php echo $_GET['id'];?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Slip No.</label>
						<input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_<?php echo $_GET['id']?>" id="slip_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">PV Date.</label>
						 <span class="rflabelsteric"><strong>*</strong></span>
						<input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_<?php echo $_GET['id']?>" id="pv_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="sf-label">Description</label>
						 <span class="rflabelsteric"><strong>*</strong></span>
						<textarea name="description_<?php echo $_GET['id']?>" id="description_<?php echo $_GET['id']?>" style="resize:none;" class="form-control requiredField"></textarea>
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
								<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
									<thead>
										<tr>
											<th class="text-center">Account Head <span class="rflabelsteric"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Action</th>
										</tr>
									</thead>
									<tbody class="addMorePvsDetailRows_<?php echo $_GET['id']?>" id="addMorePvsDetailRows_<?php echo $_GET['id']?>">
										<?php for($j = 1 ; $j <= 2 ; $j++){?>
										<input type="hidden" name="pvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="pvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $j?>" />
										<tr>
											<td>
												<select class="form-control requiredField" name="account_id_<?php echo $_GET['id']?>_<?php echo $j?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $j?>">
													<option value="">Select Account</option>
													<?php foreach($accounts as $row){?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
													<?php }?>
												</select>
											</td>
											<td>
												<input placeholder="Debit" class="form-control requiredField d_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
											</td>
											<td>
												<input placeholder="Credit" class="form-control requiredField c_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
											</td>
											<td class="text-center">---</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td></td>
											<td style="width:150px;">
												<input 
												type="number"
												readonly="readonly"
												id="d_t_amount_<?php echo $_GET['id']?>"
												maxlength="15"
												min="0"
												name="d_t_amount_<?php echo $_GET['id']?>" 
												class="form-control requiredField text-right"
												value=""/>
											</td>
											<td style="width:150px;">
												<input 
												type="number"
												readonly="readonly"
												id="c_t_amount_<?php echo $_GET['id']?>"
												maxlength="15"
												min="0"
												name="c_t_amount_<?php echo $_GET['id']?>" 
												class="form-control requiredField text-right"
												value=""/>
											</td>
											<td style="width:150px;"></td>
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
				<input type="button" class="btn btn-sm btn-primary" onclick="addMorePvsDetailRows('<?php echo $_GET['id']?>')" value="Add More PV's Rows" />
			</div>
		</div>
	<?php
	}
	
	
	
	
	public function addMoreBankPvsDetailRows_costing()
	{
		$items=Input::get('items');
		CommonHelper::companyDatabaseConnection($_GET['m']);
			$id=	$_GET['id'];
		$counter=$_GET['counter'];

		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();

		?>

		<tr id="removePvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
			<input type="hidden" name="pvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="pvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
			<td>
				<select style="width: 100%;" onchange="get_current_amount(this.id)" class="form-control requiredField select2" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value="">Select Account</option>
					<?php foreach(CommonHelper::get_accounts_for_jvs() as $row) {?>
						<option value="<?php echo $row->id.'~'.$row->supp_id.'~'.$row->supplier_id;?>"><?php  echo $row->name;?></option>
					<?php }?>
				</select>
			</td>
			<td>
				<input readonly placeholder="" class="form-control" maxlength="15" min="0" type="any"  id="current_amount<?php echo  $_GET['counter']?>"  value="" required="required"/>

			</td>
			<input type="hidden" id="current_amount_hidden<?php echo $_GET['counter'] ?>"/>

			<td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
				<select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="sub_item_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control select2">
					<option value="">Select</option>

					<?php foreach(CommonHelper::get_all_subitem() as $row): ?>
					<option value="<?php echo  $row->id ?>"> <?php echo  ucwords($row->sub_ic) ?>	</option>
					<?php endforeach ?>
				</select>
			</td>
			<td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
				<input readonly type="text" name="uom_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="uom_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control" />
				<input type="hidden" name="uom_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="uom_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control" />
			</td>

			<td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
				<input onkeyup="calculation_amount(this.id); dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')"  type="number" step="0.01" name="qty_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="qty_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control qty" />
			</td>

			<td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
				<input  onkeyup="calculation_amount(this.id); dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')" type="text" step="0.01" name="rate_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="rate_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control rate" />
			</td>

			<td>
				<input placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');"
				onkeyup="sum('<?php echo $_GET['id']?>');calculation(this.id,'1');dept_cost_amount(this.id,'<?php echo $counter ?>')" required="required"/>
			</td>
			<td>
				<input placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');"
				 onkeyup="sum('<?php echo $_GET['id']?>');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $counter ?>')" required="required"/>
			</td>
			<td class="text-center">
			<input onclick="checked_unchekd('<?php echo $counter ?>','0')" type="checkbox" id="allocation<?php echo $counter ?>" name="allocation<?php echo $counter ?>"/>
				<button type="button" onclick="removePvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</button></td>
		</tr>

		<?php echo "*"; ?>
		<div id="remove_<?php echo $counter ?>">
			<p style="color: #e2a0a0;text-align: center" id="paragraph<?php echo $counter ?>"> </p>
			<!--For Department-->
		<div id="dept_allocation<?php echo  $counter ?>"  style="display: none"  class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

			<div class="row">
				<div id="dept_allocation<?php echo $counter ?>"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">

					<input checked type="checkbox" name="dept_check_box<?php echo $counter ?>" value="1" id="dept_check_box<?php echo $counter ?>" class="">Allow Null</label>
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
							<option value="">Select Department &nbsp;&nbsp;&nbsp;&nbsp;</option>
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


		<!--For Cost Center-->
		<div id="cost_center<?php echo $counter ?>"  style="display: none;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">



			<div class="row">
				<div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-6 banks">
					<input checked type="checkbox" name="cost_center_check_box<?php echo  $counter ?>" value="1" id="cost_center_check_box<?php echo  $counter ?>" class="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">Allow Null</label>
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
						<input placeholder="Percentage" onkeyup="cost_center_calculation_dept_item(this.id,<?php echo $counter ?>);addclass('<?php echo "cost_center_department_amount_$counter"."_1" ?>','<?php echo $counter.'_1' ?>')" type="any" name="cost_center_percent<?php echo $counter ?>[]" id="cost_center_percent_<?php echo $counter ?>_1" class="form-control" />
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
		</div>
		</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="cost_data<?php echo  $counter+1?>" class="row"></div>
						</div>

		<?php
	}

	 // for journal voucher costing

	 public function addJournalVoucherDetailRows_costing()
	{

	  	$items=Input::get('items');
		CommonHelper::companyDatabaseConnection($_GET['m']);
			$id=	$_GET['id'];
		$counter=$_GET['counter'];
		$accounts = new Account;

		?>




		<tr id="removePvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
			<input type="hidden" name="jvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="pvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
			<td>
				<select style="width: 100%" onchange="get_current_amount(this.id);" class="form-control requiredField select2 accounts" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value="">Select Account</option>
					<?php foreach(CommonHelper::get_accounts_for_jvs() as $row){?>
						<option value="<?php echo $row->id.'~'.$row->supp_id.'~'.$row->supplier_id;?>"><?php echo $row->code;?> ---- <?php echo $row->name;?></option>
					<?php }?>
				</select>
			</td>
			<td>
				<input readonly placeholder="" class="form-control" maxlength="15" min="0" type="any"  id="current_amount<?php echo  $_GET['counter']?>"  value="" required="required"/>

			</td>
			<input type="hidden" id="current_amount_hidden<?php echo $_GET['counter'] ?>"/>




											<td <?php if($items==0): ?>style="display: none" <?php endif; ?> class="hidee">
                                                        <select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="sub_item_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control select2">
                                                            <option value="">Select</option>

                                                           <?php foreach(CommonHelper::get_all_subitem() as $row): ?>
                                                                <option value="<?php echo $row->id ?>"><?php echo  ucwords($row->sub_ic) ?></option>
                                                           <?php endforeach ?>
                                                        </select>
                                                    </td>
                                                    <td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
                                                        <input readonly type="text" name="uom_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="uom_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control" />
                                                        <input type="hidden" name="uom_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="uom_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control" />
                                                    </td>

                                                    <td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
                                                        <input onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')"  type="number" step="0.01" name="qty_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="qty_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control qty" />
                                                    </td>

                                                    <td <?php if($items==0): ?>style="display: none" <?php endif; ?>  class="hidee">
                                                        <input  onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')" type="text" step="0.01" name="rate_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="rate_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" class="form-control rate" />
                                                    </td>



			<td>
				<input placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');"
				onkeyup="sum('<?php echo $_GET['id']?>');calculation(this.id,'1');dept_cost_amount(this.id,'<?php echo $counter ?>')" required="required"/>
			</td>
			<td>
				<input placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');"
				 onkeyup="sum('<?php echo $_GET['id']?>');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $counter ?>')" required="required"/>
			</td>
			<td class="text-center">
			<input onclick="checked_unchekd('<?php echo $counter ?>','0')" type="checkbox" id="allocation<?php echo $counter ?>" name="allocation<?php echo $counter ?>"/>
				<button type="button" onclick="removePvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</button></td>
		</tr>



		<?php echo "*";?>

<div id="remove_<?php echo $counter ?>">
				<p style="color: #e2a0a0;text-align: center" id="paragraph<?php echo $counter ?>"> </p>
			<!--For Department-->
		<div id="dept_allocation<?php echo  $counter ?>"  style="display: none"  class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">

			<div class="row">
				<div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-5 banks">

					<input checked type="checkbox" name="dept_check_box<?php echo $counter ?>" value="1" id="dept_check_box<?php echo $counter ?>" class="">Allow Null</label>
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
								class="form-control select2 dept<?php echo $counter ?> dept_form">
							<option value="">Select Department &nbsp;&nbsp;&nbsp;&nbsp;</option>
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


		<!--For Cost Center-->
		<div id="cost_center<?php echo $counter ?>"  style="display: none;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12 banks removeDemandsRows_dept_<?php echo $id?>_<?php echo $counter?>">



			<div class="row">
				<div id="dept_allocation1"  class="col-lg-5 col-md-5 col-sm-5 col-xs-6 banks">
					<input checked type="checkbox" name="cost_center_check_box<?php echo  $counter ?>" value="1" id="cost_center_check_box<?php echo  $counter ?>" class="removeDemandsRows_<?php echo $id?>_<?php echo $counter?>">Allow Null</label>
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
							<option  value="add_new"><b>Add New</b>  </option>
							<?php    foreach(CommonHelper::get_all_cost_center() as $row):?>
								<option value="<?php echo  $row->id ?>"><?php echo ucwords($row->name) ?></option>
							<?php   endforeach; ?>
						</select>
					</td>
					<td>
						<input placeholder="Percentage" onkeyup="cost_center_calculation_dept_item(this.id,<?php echo $counter ?>);addclass('<?php echo "cost_center_department_amount_$counter"."_1" ?>','<?php echo $counter.'_1' ?>')" type="any" name="cost_center_percent<?php echo $counter ?>[]" id="cost_center_percent_<?php echo $counter ?>_1" class="form-control" />
					</td>
					<td>
						<input onblur="add_cost_center('<?php echo $counter ?>',this.id)" onkeyup="cost_center_calculation_dept_item_amount(this.id,<?php echo $counter ?>);addclass(this.id,'<?php echo $counter.'_1' ?>')" class="cost_center_department_amount<?php echo $counter ?> form-control" style="text-align: right" type="text" name="cost_center_department_amount<?php echo $counter ?>[]" id="cost_center_department_amount_<?php echo $counter ?>_1" class="form-control" />

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
		</div>


		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="cost_data<?php echo  $counter+1?>" class="row"></div>

						</div>


		<?php
	}

	 // end journal voucher costing



	public function makeFormBankPaymentVoucher(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$currentDate = date('Y-m-d');
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="pvsSection[]" id="pvsSection" class="form-control" value="<?php echo $_GET['id'];?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Slip No.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="text" class="form-control" placeholder="Slip No" name="slip_no_<?php echo $_GET['id']?>" id="slip_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">PV Date.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="date" class="form-control" max="<?php echo date('Y-m-d') ?>" name="pv_date_<?php echo $_GET['id']?>" id="pv_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Cheque No.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="text" class="form-control" placeholder="Cheque No" name="cheque_no_<?php echo $_GET['id']?>" id="cheque_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Cheque Date.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="date" class="form-control" max="<?php echo date('Y-m-d') ?>" name="cheque_date_<?php echo $_GET['id']?>" id="cheque_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="sf-label">Description</label>
						<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
						<textarea name="description_<?php echo $_GET['id']?>" id="description_<?php echo $_GET['id']?>" style="resize:none;" class="form-control"></textarea>
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
								<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
									<thead>
										<tr>
											<th class="text-center">Account Head<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
										</tr>
									</thead>
									<tbody class="addMorePvsDetailRows_<?php echo $_GET['id']?>" id="addMorePvsDetailRows_<?php echo $_GET['id']?>">
										<?php for($j = 1 ; $j <= 2 ; $j++){?>
										<input type="hidden" name="pvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="pvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $j?>" />
										<tr>
											<td>
												<select class="form-control" name="account_id_<?php echo $_GET['id']?>_<?php echo $j?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $j?>">
													<option value="">Select Account</option>
													<?php foreach($accounts as $row){?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
													<?php }?>
												</select>
											</td>
                                            <td>
                                                <input placeholder="Debit" class="form-control requiredField d_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td>
                                                <input placeholder="Credit" class="form-control requiredField c_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td class="text-center">---</td>
										</tr>
										<?php }?>
									</tbody>
								</table>

                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="d_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="d_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="c_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="c_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;"></td>
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
				<input type="button" class="btn btn-sm btn-primary" onclick="addMorePvsDetailRows('<?php echo $_GET['id']?>')" value="Add More PV's Rows" />
			</div>
		</div>
	<?php
	}
	
	public function addMoreCashRvsDetailRows(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$_GET['counter'];
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<tr id="removeRvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
			<input type="hidden" name="rvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="rvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
			<td>
				<select class="form-control requiredField select2" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value="">Select Account</option>
					<?php foreach($accounts as $row){?>
						<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
					<?php }?>
				</select>
			</td>
			<td>
                <input onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" onkeyup="sum('<?php echo $_GET['id']?>')" value="" required="required"/>
			</td>
			<td>
                <input onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" onkeyup="sum('<?php echo $_GET['id']?>')" value="" required="required"/>
			</td>
			<td class="text-center"><a href="#" onclick="removeRvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</a></td>
		</tr>
	<?php
	}
	 
	public function makeFormCashReceiptVoucher(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$currentDate = date('Y-m-d');
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="rvsSection[]" id="rvsSection" class="form-control" value="<?php echo $_GET['id'];?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Slip No.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="text" class="form-control" placeholder="Slip No" name="slip_no_<?php echo $_GET['id']?>" id="slip_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">RV Date.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="date" class="form-control" max="<?php echo date('Y-m-d') ?>" name="rv_date_<?php echo $_GET['id']?>" id="rv_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="sf-label">Description</label>
						<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
						<textarea name="description_<?php echo $_GET['id']?>" id="description_<?php echo $_GET['id']?>" style="resize:none;" class="form-control"></textarea>
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
								<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
									<thead>
										<tr>
											<th class="text-center">Account Head<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
										</tr>
									</thead>
									<tbody class="addMoreRvsDetailRows_<?php echo $_GET['id']?>" id="addMoreRvsDetailRows_<?php echo $_GET['id']?>">
										<?php for($j = 1 ; $j <= 2 ; $j++){?>
										<input type="hidden" name="rvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="rvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $j?>" />
										<tr>
											<td>
												<select class="form-control" name="account_id_<?php echo $_GET['id']?>_<?php echo $j?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $j?>">
													<option value="">Select Account</option>
													<?php foreach($accounts as $row){?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
													<?php }?>
												</select>
											</td>
                                            <td>
                                                <input placeholder="Debit" class="form-control requiredField d_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td>
                                                <input placeholder="Credit" class="form-control requiredField c_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td class="text-center">---</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="d_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="d_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="c_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="c_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;"></td>
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
				<input type="button" class="btn btn-sm btn-primary" onclick="addMoreRvsDetailRows('<?php echo $_GET['id']?>')" value="Add More RV's Rows" />
			</div>
		</div>
	<?php
	}
	
	public function addMoreBankRvsDetailRows(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$_GET['counter'];
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<tr id="removeRvsRows_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
			<input type="hidden" name="rvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="rvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $_GET['counter']?>" />
			<td>
				<select onchange="get_current_amount(this.id)" class="form-control select2 requiredField" name="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value="">Select Account</option>
				<?php 	foreach(CommonHelper::get_accounts_for_jvs() as $key => $y):?>
					<option value="<?php echo   $y->id.'~'.$y->supp_id.'~'.$y->supplier_id ?>"><?php echo  $y->name ?></option>
					<?php endforeach ?>
				</select>
			</td>

			<td>
			 <?php $branchs = CommonHelper::get_all_branch();?>
				<select class="form-control select2" name="branch_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="branch_id_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>">
					<option value='0'>Select Recieved By</option>
					<?php foreach($branchs as $row): ?>
					<option value='<?php echo $row->id; ?>'> <?php echo $row->branch_name ?> </option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<textarea class="form-control requiredField" name="desc_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" id="desc_<?php echo $_GET['id']?>_<?php echo $_GET['counter']?>" required="required"/></textarea>
			</td>
			<input type="hidden" id="current_amount_hidden<?php echo $_GET['counter'] ?>"/>
            <td>
                <input onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" placeholder="Debit" class="form-control d_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" onkeyup="sum('<?php echo $_GET['id']?>')" value="" required="required"/>
            </td>
            <td>
                <input onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>');" placeholder="Credit" class="form-control c_amount_<?php echo $_GET['id']?> requiredField" maxlength="15" min="0" type="text" name="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $_GET['counter'] ?>" onkeyup="sum('<?php echo $_GET['id']?>')" value="" required="required"/>
            </td>
            <td class="text-center"><a href="#" onclick="removeRvsRows('<?php echo $_GET['id']?>','<?php echo $_GET['counter']?>'),sum('<?php echo $_GET['id']?>')" class="btn btn-xs btn-danger">Remove</a></td>
		</tr>
	<?php
	}
	 
	public function makeFormBankReceiptVoucher(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
		$_GET['id'];
		$currentDate = date('Y-m-d');
		$accounts = new Account;
		$accounts = $accounts::orderBy('level1', 'ASC')
    				->orderBy('level2', 'ASC')
					->orderBy('level3', 'ASC')
					->orderBy('level4', 'ASC')
					->orderBy('level5', 'ASC')
					->orderBy('level6', 'ASC')
					->orderBy('level7', 'ASC')
    				->get();
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="hidden" name="rvsSection[]" id="rvsSection" class="form-control" value="<?php echo $_GET['id'];?>" />
			</div>		
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Slip No.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="text" class="form-control" placeholder="Slip No" name="slip_no_<?php echo $_GET['id']?>" id="slip_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">RV Date.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="date" class="form-control" max="<?php echo date('Y-m-d') ?>" name="rv_date_<?php echo $_GET['id']?>" id="rv_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Cheque No.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="text" class="form-control" placeholder="Cheque No" name="cheque_no_<?php echo $_GET['id']?>" id="cheque_no_<?php echo $_GET['id']?>" value="" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="sf-label">Cheque Date.</label>
						<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
						<input type="date" class="form-control" max="<?php echo date('Y-m-d') ?>" name="cheque_date_<?php echo $_GET['id']?>" id="cheque_date_<?php echo $_GET['id']?>" value="<?php echo date('Y-m-d') ?>" />
					</div>
				</div>	
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="sf-label">Description</label>
						<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
						<textarea name="description_<?php echo $_GET['id']?>" id="description_<?php echo $_GET['id']?>" style="resize:none;" class="form-control"></textarea>
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
								<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
									<thead>
										<tr>
											<th class="text-center">Account Head<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											<th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
										</tr>
									</thead>
									<tbody class="addMoreRvsDetailRows_<?php echo $_GET['id']?>" id="addMoreRvsDetailRows_<?php echo $_GET['id']?>">
										<?php for($j = 1 ; $j <= 2 ; $j++){?>
										<input type="hidden" name="rvsDataSection_<?php echo $_GET['id']?>[]" class="form-control" id="rvsDataSection_<?php echo $_GET['id']?>" value="<?php echo $j?>" />
										<tr>
											<td>
												<select class="form-control select2" name="account_id_<?php echo $_GET['id']?>_<?php echo $j?>" id="account_id_<?php echo $_GET['id']?>_<?php echo $j?>">
													<option value="">Select Account</option>
													<?php foreach($accounts as $row){?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['code'];?> ---- <?php echo $row['name'];?></option>
													<?php }?>
												</select>
											</td>
                                            <td>
                                                <input placeholder="Debit" class="form-control requiredField d_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td>
                                                <input placeholder="Credit" class="form-control requiredField c_amount_<?php echo $_GET['id']?>" maxlength="15" min="0" type="number" name="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" id="c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>" value="" onfocus="mainDisable('d_amount_<?php echo $_GET['id']?>_<?php echo $j ?>','c_amount_<?php echo $_GET['id']?>_<?php echo $j ?>');" onkeyup="sum('<?php echo $_GET['id']?>')" required="required"/>
                                            </td>
                                            <td class="text-center">---</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="d_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="d_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;">
                                            <input
                                                    type="number"
                                                    readonly="readonly"
                                                    id="c_t_amount_<?php echo $_GET['id']?>"
                                                    maxlength="15"
                                                    min="0"
                                                    name="c_t_amount_<?php echo $_GET['id']?>"
                                                    class="form-control requiredField text-right"
                                                    value=""/>
                                        </td>
                                        <td style="width:150px;"></td>
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
				<input type="button" class="btn btn-sm btn-primary" onclick="addMoreRvsDetailRows('<?php echo $_GET['id']?>')" value="Add More RV's Rows" />
			</div>
		</div>
	<?php
	}

	public function loadPurchaseCashPaymentVoucherDetailByGRNNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['grnNo']);
        $grnNo = $makeGetValue[0];
        $grnDate = $makeGetValue[1];

        CommonHelper::companyDatabaseConnection($m);
        $accounts = Account::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $GoodsReceiptNoteDetail = GoodsReceiptNote::where('status','=','1')->where('grn_no','=',$grnNo)->first();
        $GRNDataDetail = GRNData::where('status','=','1')->where('grn_no','=',$grnNo)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.AjaxPages.makeFormPurchaseCashPaymentVoucherDetailByGRNNo',compact('GoodsReceiptNoteDetail','GRNDataDetail', 'accounts'));
    }

    public function loadPurchaseBankPaymentVoucherDetailByGRNNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['grnNo']);
        $grnNo = $makeGetValue[0];
        $grnDate = $makeGetValue[1];

        CommonHelper::companyDatabaseConnection($m);
        $accounts = Account::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $GoodsReceiptNoteDetail = GoodsReceiptNote::where('status','=','1')->where('grn_no','=',$grnNo)->first();
        $GRNDataDetail = GRNData::where('status','=','1')->where('grn_no','=',$grnNo)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.AjaxPages.makeFormPurchaseBankPaymentVoucherDetailByGRNNo',compact('GoodsReceiptNoteDetail','GRNDataDetail', 'accounts'));
    }

    public function loadSaleCashReceiptVoucherDetailByInvoiceNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['invNo']);
        $invNo = $makeGetValue[0];
        $invDate = $makeGetValue[1];

        CommonHelper::companyDatabaseConnection($m);
        $accounts = Account::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $InvoiceDetail = Invoice::where('status','=','1')->where('inv_no','=',$invNo)->first();
        $InvoiceDataDetail = InvoiceData::where('status','=','1')->where('inv_no','=',$invNo)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.AjaxPages.makeFormSaleCashReceiptVoucherDetailByInvoiceNo',compact('InvoiceDetail','InvoiceDataDetail', 'accounts'));
    }

    public function loadSaleBankReceiptVoucherDetailByInvoiceNo(){
        $m = $_GET['m'];
        $makeGetValue = explode('*',$_GET['invNo']);
        $invNo = $makeGetValue[0];
        $invDate = $makeGetValue[1];

        CommonHelper::companyDatabaseConnection($m);
        $accounts = Account::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $InvoiceDetail = Invoice::where('status','=','1')->where('inv_no','=',$invNo)->first();
        $InvoiceDataDetail = InvoiceData::where('status','=','1')->where('inv_no','=',$invNo)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Finance.AjaxPages.makeFormSaleBankReceiptVoucherDetailByInvoiceNo',compact('InvoiceDetail','InvoiceDataDetail', 'accounts'));
    }

    public  function get_current_amount()
    {

        CommonHelper::companyDatabaseConnection(1);
        $id= $_GET['val'];
        $debit_amount = DB::selectOne("select sum(amount)amount from transactions where status=1 and debit_credit=1
        and acc_id='".$id."'")->amount;
        $credit_amount = DB::selectOne("select sum(amount)amount from transactions where status=1 and debit_credit=0
        and acc_id='".$id."'")->amount;
        $amount=$debit_amount-$credit_amount;
        echo round($amount);
        CommonHelper::reconnectMasterDatabase();
    }

    public  function getBranchClientWise(Request $request)
    {
        $AccountId = $request->AccountAndType;
        if($AccountId[1] == 2):

        $Client=new Client();
        $Client=$Client->SetConnection('mysql2')->where('acc_id',$AccountId[0])->select('id')->first();
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2')->where('client_id',$Client->id)->get();
		echo "<option value='0,0'>Select Paid To</option>";
        foreach($Branch as $Fil):

         echo '<option value="'.$Fil->id.',5'.'">'.$Fil->branch_name.' [Branch]'.'</option>';

        endforeach;
        elseif($AccountId[1] == 0):
		?>
		<?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>

			<option value='0,0'>Select Paid To</option>
			<?php foreach($MultiData['Emp'] as $EmpFil):?>
			<option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
			<?php endforeach;?>
			<?php foreach($MultiData['Supp'] as $SuppFil):?>
			<option value='<?php echo $SuppFil->id.","."2";?>'><?php echo $SuppFil->name?></option>
			<?php endforeach;?>
			<?php foreach($MultiData['PaidTo'] as $PaidToFil):?>
			<option value='<?php echo $PaidToFil->id.","."4";?>'><?php echo $PaidToFil->name?></option>
			<?php endforeach;?>
		<?php
		else:
		echo "<option value='0,0'>Select Paid To</option>";
        endif;
    }



    public  function getRegionClusterWise(Request $request)
    {
        $ClusterId = $request->ClusterId;
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2')->where('cluster_id',$ClusterId)->where('status',1)->get();
		echo "<option value=''>Select Region</option>";
        foreach($Region as $Fil):
         echo '<option value="'.$Fil->id.'">'.$Fil->region_name.'</option>';
        endforeach;
    }

    public  function getBranchClientWiseLedger(Request $request)
    {
        $AccountId = $request->AccountAndType;
        if($AccountId[1] == 2):

        $Client=new Client();
        $Client=$Client->SetConnection('mysql2')->where('acc_id',$AccountId[0])->select('id')->first();
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2')->where('client_id',$Client->id)->get();

        foreach($Branch as $Fil):
		echo "<option value='0,0'>Select Paid To</option>";
         echo '<option value="'.$Fil->id.',5'.'">'.$Fil->branch_name.' [Branch]'.'</option>';

        endforeach;
        elseif($AccountId[1] == 0):
        $Employee = new Employee();
        $Employee = $Employee->SetConnection('mysql2')->where('status',1)->get();

		?>


			<option value='0,0'>Select Paid To</option>
			<?php foreach($Employee as $EmpFil):?>
			<option value='<?php echo $EmpFil->id.","."1";?>'><?php echo $EmpFil->emp_name?></option>
			<?php endforeach;?>
		<?php
		else:
		echo "<option value='0,0'>Select Paid To</option>";
        endif;
    }



    public  function getBranchClientWiseSingle(Request $request)
    {
        $AccountId = $request->AccountAndType;
        $PaidToAndType = $request->PaidToAndType;

        if($AccountId[1] == 2):
        $Client=new Client();
        $Client=$Client->SetConnection('mysql2')->where('acc_id',$AccountId[0])->select('id')->first();
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2')->where('client_id',$Client->id)->get();
		$Selected = "";
        foreach($Branch as $Fil):
        if($Fil->id == $PaidToAndType[0] && $PaidToAndType[1] == 5){$Selected = 'selected';}else{$Selected='';}

		echo "<option value='0,0'>Select Paid To</option>";
         echo '<option value="'.$Fil->id.',5'.'"  '.$Selected.' >'.$Fil->branch_name.' [Branch]'.'</option>';

        endforeach;
        elseif($AccountId[1] == 0):
		?>
		<?php $MultiData = CommonHelper::getEmpSuppClientPaidTo();?>

			<option value='0,0'>Select Paid To</option>
			<?php foreach($MultiData['Emp'] as $EmpFil): if($EmpFil->id == $PaidToAndType[0] && $PaidToAndType[1] == 1){$Selected = 'selected';}else{$Selected='';}?>
			<option value='<?php echo $EmpFil->id.","."1";?>' <?php if($EmpFil->id == $PaidToAndType[0] && $PaidToAndType[1] == 1){echo "selected";}?>><?php echo $EmpFil->emp_name?></option>
			<?php endforeach;?>
			<?php foreach($MultiData['Supp'] as $SuppFil): if($SuppFil->id == $PaidToAndType[0] && $PaidToAndType[1] == 2){$Selected = 'selected';}else{$Selected='';}?>
			<option value='<?php echo $SuppFil->id.","."2";?>' <?php echo $Selected?>><?php echo $SuppFil->name?></option>
			<?php endforeach;?>
			<?php foreach($MultiData['PaidTo'] as $PaidToFil): if($PaidToFil->id == $PaidToAndType[0] && $PaidToAndType[1] == 4){$Selected = 'selected';}else{$Selected='';}?>
			<option value='<?php echo $PaidToFil->id.","."4";?>' <?php echo $Selected?>><?php echo $PaidToFil->name?></option>
			<?php endforeach;?>
		<?php
		else:
		echo "<option value='0,0'>Select Paid To</option>";
        endif;
    }




    public  function getAccount(Request $request)
    {
		$Account = new Account();
		$Account = $Account->SetConnection('mysql2');
		$AccId = $request->Id;
		if($AccId == 0)
		{
			$Acc =$Account->where('status',1)->where('operational',1)->orderBy('id', 'ASC')->get();
			?>
			<option value="0,0">Select Account</option>
			<?php foreach($Acc as $Fil):?>
			<option value="<?php echo $Fil->id.','.$Fil->type?>"  ><?php echo $Fil->code .' ---- '. $Fil->name?></option>
			<?php endforeach;?>
			<?php
		}
		else
		{
			$Acc = CommonHelper::get_single_row('accounts','id',$AccId);
			?>
			<option value="0,0">Select Account</option>
			<option value="<?php echo $Acc->id.','.$Acc->type?>"  selected  ><?php echo $Acc->code .' ---- '. $Acc->name?></option>
			<?php
		}
    }
    public  function getEmpOrPaidToData(Request $request)
    {


        if ($request->Type==1):
        $data=   DB::Connection('mysql2')->table('employee')->where('status',1)->select('id','emp_name as name')->get();

        elseif($request->Type==4):
            $data=   DB::Connection('mysql2')->table('paid_to')->where('status',1)->select('id','name')->get();
        endif;
        if($request->Type == 1):
        ?>
        <option value="">Select Employee</option>
		<?php
		else:
		?>
		<option value="">Select Paid To</option>
		<?php
		endif;
        foreach ($data as $Fil)
        {
            ?>
            <option value="<?php echo $Fil->id.','.$request->Type?>"><?php echo  $Fil->name;?></option>
            <?php
        }

    }


	 
}    