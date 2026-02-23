<?php

namespace App\Http\Controllers;
use App\Helpers\ReuseableCode;
use App\Http\Requests;
use App\Models\Account;
use App\Models\Issuance;
use Illuminate\Http\Request;
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\FinanceHelper;
use Auth;
use DB;
use Config;
use Session;
use App\Models\Supplier;
use App\Models\Countries;
use App\Models\Category;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Subitem;
use App\Models\UOM;
use App\Models\Demand;
use App\Models\DemandData;
use App\Models\GoodsReceiptNote;
use App\Models\GRNData;
use App\Models\PurchaseVoucher;
use App\Models\PurchaseVoucherData;
use App\Models\PurchaseType;
use App\Models\Currency;
use App\Models\FinanceDepartment;
use App\Models\Department;

use App\Models\CostCenter;
use App\Models\CostCenterDepartmentAllocation;
use App\Models\DepartmentAllocation1;
use App\Models\SalesTaxDepartmentAllocation;
use App\Models\Transactions;
use App\Models\Warehouse;
use App\Models\PurchaseVoucherThroughGrn;
use App\Models\PurchaseVoucherThroughGrnData;
use App\Models\BreakupData;

use App\Models\Product;
use App\Models\Conditions;
use App\Models\Type;
use App\Models\Client;
use App\Models\Branch;
use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\JobOrder;
use App\Models\Cluster;
use App\Models\NewPurchaseVoucher;
use App\Models\NewPurchaseVoucherData;

use Input;
class PurchaseDataCallController extends Controller
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

    public function viewSupplierList()
    {

        $edit=ReuseableCode::check_rights(50);
        $delete=ReuseableCode::check_rights(218);
        $createAccount=ReuseableCode::check_rights(52);

        $m = $_GET['m'];
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        CommonHelper::companyDatabaseConnection($m);
//        $suppliers = new Supplier;
//        $suppliers = $suppliers->SetConnection('mysql2');
//        $suppliers = $suppliers->where('status', 1)->select('name','acc_id' ,'id', 'email', 'resgister_income_tax', 'company_name', 'business_type', 'cnic', 'ntn', 'filer')->get();
        
        if(!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $suppliers = DB::Connection('mysql2')->select('select s.*,s.id s_id from supplier s
                where s.status=1 and (
                    LOWER(COALESCE(s.name, "")) like LOWER(?) or
                    LOWER(COALESCE(s.contact_person, "")) like LOWER(?) or
                    COALESCE(s.mobile_no, "") like ?
                )
                group by s.id
                order by id desc', [$searchTerm, $searchTerm, $searchTerm]);
        } else {
            $suppliers = DB::Connection('mysql2')->select('select s.*,s.id s_id from supplier s
                where s.status=1
                group by s.id
                order by id desc');
        }
        CommonHelper::reconnectMasterDatabase();
        $counter = 1;
        foreach ($suppliers as $row) {




            ?>
            <tr id="<?php echo $row->s_id ?>">
                <td class="text-center"><?php echo $counter++; ?></td>
                <td class="text-center"><?php echo $row->vendor_code; ?></td>
                <td class="text-center"><?php echo strtoupper($row->name); ?></td>
                <td class="text-center"><?php echo $row->address ?></td>
                <td class="text-center"><?php echo  $row->contact_person ?></td>
                <td class="text-center"><?php echo  $row->mobile_no ?></td>
                <td class="text-center">
                    <div class="dropdown">
                        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        <ul class="dropdown-menu">
                            <li>
                                <a onclick="showDetailModelOneParamerter('purchase/viewSupplierFormlist/<?php echo $row->id ?>','<?php echo $row->id ?>','View Supplier Detail')" target="_blank" class="dropdown-item_sale_order_list dropdown-item"> <i class="fa-regular fa-eye"></i> View</a>
                                <?php if($edit == true): ?>
                                    <a href="<?php echo url('/').'/purchase/editSupplierForm/' . $row->s_id.'?m=1'; ?>" type="button" class="dropdown-item_sale_order_list dropdown-item BtnHide">
                                        <i class="fa-solid fa-pencil"></i> Edit
                                    </a>
                                <?php endif; ?>
                                <?php if($delete == true): ?>
                                    <a class="dropdown-item_sale_order_list dropdown-item BtnHide" type="button" onclick="delete_supp('<?php echo $row->s_id ?>')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                <?php endif; ?>

                                <a target="_blank" href="#" class="dropdown-item_sale_order_list dropdown-item">
                                    <i class="fa-solid fa-print"></i> Print
                                </a>
                                <?php if($row->acc_id == 0): ?>
                                    <?php if($createAccount == true): ?>
                                        <span id="ShowHide<?php echo $row->s_id ?>">
                                            <a type="button" class="dropdown-item_sale_order_list dropdown-item BtnHide" id="Btn<?php echo $row->id ?>" onclick="CreateAccount('<?php echo $row->acc_id ?>', '<?php echo $row->name ?>', '<?php echo $row->s_id ?>')">
                                                <i class="fa-solid fa-plus"></i> Create Account
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </td>








                </td>
            </tr>
            <?php

        }
    }
    function set_user_db_id(Request $request)
    {
        $run_company = $request->company;
        $request->session()->put('run_company',$run_company);
        return redirect()->intended('/dClient');;
    }
    function createSupplierAccount()
    {

        $AccId = $_GET['AccId'];
        $SupplierName = $_GET['SupplierName'];
        $SupplierId = $_GET['SupplierId'];
        $Value = $_GET['value'];


        $Accounts = new Account();
        $Accounts = $Accounts->SetConnection('mysql2');
        $Count = $Accounts->where('status', 1)->where('id', $AccId)->count();
        if($Count > 0)
        {
            echo "Account Already Created...!";
        }
        else
        {
            $account_head ='Trade Payable';
            $sent_code= $Value;//'Trade Receivables';
            $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;

            if ($max_id == '') {
                $code = $sent_code . '-1';
            } else {
                $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                $max_code2;
                $max = explode('-', $max_code2);
                $code = $sent_code . '-' . (end($max) + 1);
            }

            $level_array = explode('-', $code);
            $counter = 1;
            foreach ($level_array as $level):
                $data1['level' . $counter] = strip_tags($level);
                $counter++;
            endforeach;
            $data1['code'] = strip_tags($code);
            $data1['name'] = strip_tags($SupplierName);
            $data1['parent_code'] = strip_tags($sent_code);
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['action'] = 'create';
            $data1['type'] = 1;
            $data1['operational'] = 1;
            $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);
            $UpdateSupplier['acc_id'] = $acc_id;
            DB::Connection('mysql2')->table('supplier')->where('id','=',$SupplierId)->update($UpdateSupplier);
            echo "yes";
        }
    }

    public function viewCategoryList()
    {
        $edit=ReuseableCode::check_rights(56);
        $delete=ReuseableCode::check_rights(57);
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $categories = new Category;
        $categories = $categories->where('status',1)->get();
        CommonHelper::reconnectMasterDatabase();
        $counter = 1;
        foreach ($categories as $row) {
            ?>
            <tr id="remove<?php echo $row['id'] ?>">
                <td class="text-center"><?php echo $counter++; ?></td>
                <td><?php echo $row['main_ic']; ?></td>
                <td class="text-center">
                    <?php if($edit == true): ?>
                        <a onclick="showDetailModelMasterTable('<?php Session::get('run_company') ?>','purchase/editCategoryForm?id=<?php echo $row['id'] ?>','1','<?php echo $row['id'] ?>',<?php echo  $row['acc_id'] ?>, 'category','Edit Category Detail Form')"
                           class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>
                    <?php endif;?>
                    <?php if($delete == true):?>
                        <button type="button" class="btn btn-danger btn-xs" id="delete<?php echo $row['id']?>" onclick="delete_cate('<?php echo $row['id'] ?>')"><span class="glyphicon glyphicon-trash"></span></button>
                    <?php endif;?>
                </td>
            </tr>

            <script>
                function delete_cate(id)
                {
                    if (confirm('Are You Sure ? You want to delete this recored...!')) {
                        var m = '<?php echo $m?>';

                        $.ajax({
                            url: '/pdc/delete_cate',
                            type: 'Get',
                            data: {id: id},

                            success: function (response)
                            {


                                $('#remove'+response).remove();
                            }
                        });
                    }
                    else {}
                }

            </script>
            <?php

            if ($row->acc_id==0):
              $acc_id=  ReuseableCode::make_account('1-2-1',$row->main_ic,1);
              $category['acc_id']=$acc_id;
                DB::Connection('mysql2')->table('category')->where('id',$row->id)->update($category);
            endif;
        }
    }

    public function delete_cate(Request $request)
    {

        $id= $request->id;
        $data['status']=0;

        DB::Connection('mysql2')->table('category')->where('id',$id)->update($data);
        echo $id;
    }

    public function delete_sub_cate(Request $request)
    {

        $id= $request->id;
        $data['status']=0;

        DB::Connection('mysql2')->table('sub_category')->where('id',$id)->update($data);
        echo $id;
    }

    public function viewRegionList()
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $region = new Region;
        $region = $region::get();
        CommonHelper::reconnectMasterDatabase();
        $counter = 1;
        $paramOne = "pdc/editRegionDetail?m=".$m;
        foreach ($region as $row) {
            ?>
            <tr>
                <td class="text-center"><?php echo $counter++; ?></td>
                <td class="text-center"><?php echo $row['region_code']; ?></td>
                <td class="text-center">

                    <?php
                    if($row->cluster_id >0 ):
                        $Cluster = CommonHelper::get_single_row('cluster','id',$row->cluster_id);
                        echo $Cluster->cluster_name;
                    endif;
                    ?>

                </td>
                <td class="text-center"><?php echo $row['region_name']; ?></td>
                <td class="text-center">
                    <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','Edit Region Detail')" type="button" class="btn btn-success btn-sm">EDIT</button>
                </td>

            </tr>
            <?php
        }
    }
    function editRegionDetail()
    {

        $EditId = $_GET['id'];
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('id',$EditId)->where('status',1)->first();
        $Cluster = new Cluster();
        $Cluster = $Cluster->SetConnection('mysql2');
        $Cluster = $Cluster->where('status',1)->get();
        return view('Purchase.AjaxPages.editRegionDetail',compact('Region','Cluster'));
    }

    function get_dashboard_info()
    {

        $Type = $_GET['Type'];
        $m = $_GET['m'];
        return view('Purchase.AjaxPages.get_dashboard_info',compact('Type','m'));
    }
    function getOnlineUserAjax()
    {
        $m = $_GET['m'];
        return view('dClient.getOnlineUserAjax',compact('m'));
    }


    function getDetailReportAjax(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        $VoucherType =  Input::get('VoucherType');





        $m = $request->m;
        if ($VoucherType=='work_order_in'):

            $StockData = DB::Connection('mysql2')->select('select voucher_no,voucher_date,sum(amount)amount from stock
           where status = 1 and opening = 0
          and voucher_type = 1
          and voucher_date between "'.$from.'" and "'.$to.'"
         and pos_status=4
         and transfer=0
         and transfer_status=0
         group by voucher_no');

        elseif($VoucherType=='work_order_issuence'):

            $StockData = DB::Connection('mysql2')->select('select voucher_no,voucher_date,sum(amount)amount from stock
           where status = 1 and opening = 0
          and voucher_type = 5
          and voucher_date between "'.$from.'" and "'.$to.'"
         and pos_status=2
         and transfer=0
         and transfer_status=0
         group by voucher_no');

            else:
        $StockData = DB::Connection('mysql2')->select('select voucher_no,voucher_date,sum(amount)amount from stock where status = 1 and opening = 0 and voucher_type ="'.$VoucherType.'"
        and voucher_date between "'.$from.'" and "'.$to.'"
         and pos_status=0
         and transfer=0
         and transfer_status=0
         group by voucher_no');
        endif;
        return view('Purchase.AjaxPages.getDetailReportAjax',compact('VoucherType','m','StockData'));
    }


    function getPendingApporvedMultiList()
    {
        $contion = $_GET['contion'];
        $tab = $_GET['tab'];
        $m = $_GET['m'];
        $ApprovedPendingLable = '';
        if($tab == 'pr' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('demand')->where('status',1)->where('demand_status',1)->get();
            $ApprovedPendingLable = 'Purchase Request Pending';
        }
        elseif($tab == 'pr' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('demand')->where('status',1)->where('demand_status',2)->get();
            $ApprovedPendingLable = 'Purchase Request Approved';
        }
        elseif($tab == 'po' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->where('purchase_request_status', 1)->get();
            $ApprovedPendingLable = 'Purchase Order Pending';
        }
        elseif($tab == 'po' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->whereIn('purchase_request_status', array(2, 3))->get();
            $ApprovedPendingLable = 'Purchase Order Approved';
        }
        elseif($tab == 'po' && $contion == 3)
        {
            $MultiData = DB::Connection('mysql2')->table('purchase_request')->where('status',1)->whereIn('purchase_request_status', array(3))->get();
            $ApprovedPendingLable = 'Purchase Order (GRN Created)';
        }
        elseif($tab == 'grn' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->where('grn_status', 1)->get();
            $ApprovedPendingLable = 'Goods Receipt Note Pending';
        }
        elseif($tab == 'grn' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->whereIn('grn_status', array(2, 3))->get();
            $ApprovedPendingLable = 'Goods Receipt Note Approved';
        }
        elseif($tab == 'grn' && $contion == 3)
        {
            $MultiData = DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->whereIn('grn_status', array(3))->get();
            $ApprovedPendingLable = 'Goods Receipt Note (Invoice Created)';
        }
        elseif($tab == 'pi' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_no','!=','')->where('pv_status',1)->get();
            $ApprovedPendingLable = 'Purchase Invoice Pending';
        }
        elseif($tab == 'pi' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_no','!=','')->where('pv_status',2)->get();
            $ApprovedPendingLable = 'Purchase Invoice Approved';
        }
        elseif($tab == 'st' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('tr_status',1)->get();
            $ApprovedPendingLable = 'Stock Transfer Pending';
        }
        elseif($tab == 'st' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('tr_status',2)->get();
            $ApprovedPendingLable = 'Stock Transfer Approved';
        }
        elseif($tab == 'tdn' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('purchase_return')->where('status',1)->get();
            $ApprovedPendingLable = 'Debit Note';
        }
        else{}

        return view('Purchase.AjaxPages.getPendingApporvedMultiList',compact('contion','tab','m','MultiData','ApprovedPendingLable'));
    }

    function getPendingApporvedMultiListForSales()
    {
        $contion = $_GET['contion'];
        $tab = $_GET['tab'];
        $m = $_GET['m'];
        $SalesLable = '';
        if($tab == 'so')
        {
            $MultiData = DB::Connection('mysql2')->table('sales_order')->where('status',1)->get();
        }
        elseif($tab == 'dn')
        {
            $MultiData = DB::Connection('mysql2')->table('delivery_note')->where('status',1)->get();
        }
        elseif($tab == 'sti')
        {
            $MultiData = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status',1)->get();
        }
        elseif($tab == 'srv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('sales',1)->get();
        }
        elseif($tab == 'srv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('sales',1)->get();
        }
        elseif($tab == 'srt')
        {
            $MultiData = DB::Connection('mysql2')->table('credit_note')->where('status',1)->get();
        }
        return view('Purchase.AjaxPages.getSalesMultiList',compact('contion','tab','m','MultiData','SalesLable'));
    }

    function getPendingApporvedMultiListForFinance()
    {
        $contion = $_GET['contion'];
        $tab = $_GET['tab'];
        $m = $_GET['m'];
        $FinanceLable = '';

        if($tab == 'jv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_jvs')->where('status',1)->where('jv_status',1)->get();
            $FinanceLable = 'Journal Voucher (Pending)';
        }
        elseif($tab == 'jv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_jvs')->where('status',1)->where('jv_status',2)->get();
            $FinanceLable = 'Journal Voucher (Approved)';
        }
        elseif($tab == 'bpv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',1)->where('payment_type',1)->get();
            $FinanceLable = 'Bank Payment Voucher (Pending)';
        }
        elseif($tab == 'bpv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',2)->where('payment_type',1)->get();
            $FinanceLable = 'Bank Payment Voucher (Approved)';
        }
        elseif($tab == 'cpv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',1)->where('payment_type',2)->get();
            $FinanceLable = 'Cash Payment Voucher (Pending)';
        }
        elseif($tab == 'cpv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_pv')->where('status',1)->where('pv_status',2)->where('payment_type',2)->get();
            $FinanceLable = 'Cash Payment Voucher (Approved)';
        }
        elseif($tab == 'brv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('rv_type',1)->where('sales','!=',1)->get();
            $FinanceLable = 'Bank Receipt Voucher (Pending)';
        }
        elseif($tab == 'brv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('rv_type',1)->where('sales','!=',1)->get();
            $FinanceLable = 'Bank Receipt Voucher (Approved)';
        }
        elseif($tab == 'crv' && $contion == 1)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',1)->where('rv_type',2)->where('sales','!=',1)->get();
            $FinanceLable = 'Cash Receipt Voucher (Pending)';
        }
        elseif($tab == 'crv' && $contion == 2)
        {
            $MultiData = DB::Connection('mysql2')->table('new_rvs')->where('status',1)->where('rv_status',2)->where('rv_type',2)->where('sales','!=',1)->get();
            $FinanceLable = 'Cash Receipt Voucher (Approved)';
        }

        return view('Purchase.AjaxPages.getPendingApporvedMultiListForFinance',compact('contion','tab','m','MultiData','FinanceLable'));
    }

    function getPurchaseDetailReportAjax()
    {
        return view('Purchase.AjaxPages.getPurchaseDetailReportAjax');
    }
    function getAgingReportDataAjax(Request $request)
    {
        $data =ReuseableCode::get_account_year_from_to(Session::get('run_company'));
        $from=$data[0];
        $to=$request->as_on;
        if($request->ReportType == 1)
        {
            return view('Purchase.AjaxPages.getAgingReportDataAjaxSummary',compact('from','to'));
        }
        else
        {
            return view('Purchase.AjaxPages.getAgingReportDataAjax',compact('from','to'));
        }

    }

    public function viewSubItemListAjax()
    {
        $m = $_GET['m'];
        $category = $_GET['category'];
        $sub_category  = $_GET['sub_category'];
        $edit=ReuseableCode::check_rights(291);
        $delete=ReuseableCode::check_rights(292);

        $uom = DB::table('uom')->where('status', '=', 1)->get();

        CommonHelper::companyDatabaseConnection($m);
//        $subitems = new Subitem;
//        $subitems = $subitems::get();
        if($category!="" && $sub_category =="") {
            $subitems = Subitem::where('status', '=', 1)->where('main_ic_id', '=', $category)->get();
        }
        elseif($category!="" && $sub_category !="") {
            $subitems = Subitem::where('status', '=', 1)->where('main_ic_id', '=', $category)->where('sub_category_id', '=', $sub_category)->get();
        }

        else {
            $subitems = Subitem::where('status', '=', 1)->get();
        }

        $packaging_type = DB::table('packaging_type')->where('status', '=', 1)->get();
        $packaging_data = [];
        foreach($packaging_type as $val) {
            $packaging_data[$val->id] = $val->type; 
        }

        $uom_data = [];
        foreach($uom as $val) {
            $uom_data[$val->id] = $val->uom_name; 
        }
        CommonHelper::reconnectMasterDatabase();
        $counter = 1;
        foreach ($subitems as $row) {
            $DataCount = DB::Connection('mysql2')->selectOne('select count(*) data_count from stock where sub_item_id = '.$row->id.' and status = 1')->data_count;
            ?>
            <tr id="RemoveTr<?php echo $row['id'] ?>" title="<?php echo $row['id'] ?>">
                <td class="text-center"><?php echo $row['id']; ?></td>
                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'category', 'main_ic', $row['main_ic_id']); ?></td>
                <td><?php echo $row['item_code']; ?></td>
                <td><?php echo $row['sub_ic']; ?></td>
                <td><?php if(array_key_exists($row['primary_pack_type'],$packaging_data)): echo $packaging_data[$row['primary_pack_type']]; endif; ?></td>
                <td><?php echo $row['pack_size']; ?></td>
                <td><?php if(array_key_exists($row['uom'],$uom_data)): echo $uom_data[$row['uom']]; endif; ?></td>
                <td><?php echo $row['color']; ?></td>
                <td><?php echo $row['hs_code_id']; ?></td>
                <td class="text-center col-sm-1">

                    <?php if($edit == true):?>
                        <a type="button" href="<?php echo url('purchase/editSubItemForm?id='.$row->id) ?>" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>
                    <?php endif;?>
                    <?php if($delete == true):?>
                        <a type="button" href="<?php echo url('purchase/deleteSubItemRecord?id='.$row->id.'&m='.Session::get('run_company')) ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    <?php endif;?>
                    <?php //echo CommonHelper::masterTableButtons($m, $row['status'], $row['id'], 'sub_ic', 'acc_id', 'subitem', 'purchase/editSubItemForm', 'Edit Sub-Item Detail Form', 'purchase/viewSubItemDetail', 'View Sub-Item Detail', 'purchase/deleteSubItemRecord', 'purchase/repostSubItemRecord') ?>

                </td>


                <td class="hide">
                    <select name="item[]"><option <?php if ($row['finish_good']==1): ?>selected <?php endif ?> value="1,<?php echo $row['id'] ?>">Yes</option>
                        <option <?php if ($row['finish_good']==0): ?>selected <?php endif ?>  value="0,<?php echo $row['id'] ?>">No</option></select>
                </td>
            </tr>
            <?php

                // if ($row->acc_id==0):

                //     $Category_data = CommonHelper::get_category_row($row->main_ic_id);
                //     $acc_id=$Category_data->value('acc_id');
                //     $acc_code=CommonHelper::get_account_code($acc_id);
                //     $acc_id=  ReuseableCode::make_account($acc_code,$row['sub_ic'],2);
                //     $sub_ic_data['acc_id']=$acc_id;

                //     DB::Connection('mysql2')->table('subitem')->where('id',$row['id'])->update($sub_ic_data);
                // endif;
        }


    }


    public function viewSubItemList()
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
//        $subitems = new Subitem;
//        $subitems = $subitems::get();
        $subitems = Subitem::where('status', '=', 1)->get();
        CommonHelper::reconnectMasterDatabase();
        $counter = 1;
        foreach ($subitems as $row) {
            ?>
            <tr title="<?php echo $row['id'] ?>" class="text-center">
                <td class="text-center"><?php echo $counter++; ?></td>
                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'category', 'main_ic', $row['main_ic_id']); ?></td>
                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'sub_category', 'sub_category_name', $row['sub_category_id']); ?></td>
                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'item_master', 'item_master_code', $row['item_master_id']); ?></td>
                <td><?php echo $row['item_code']; ?></td>
                <td><?php echo $row['sub_ic']; ?></td>
                <td class="text-center"><?php echo CommonHelper::masterTableButtons($m, $row['status'], $row['id'], 'sub_ic', 'acc_id', 'subitem', 'purchase/editSubItemForm', 'Edit Sub-Item Detail Form', 'purchase/viewSubItemDetail', 'View Sub-Item Detail', 'purchase/deleteSubItemRecord', 'purchase/repostSubItemRecord') ?></td>
            </tr>
            <?php
        }

    }

    public function viewUOMList()
    {
        $uoms = new UOM;
        $uoms = $uoms::where('status', '=', '1')->where('company_id', '=', $_GET['m'])->get();

        $counter = 1;
        foreach ($uoms as $row) {
            ?>
            <tr>
                <td class="text-center"><?php echo $counter++; ?></td>
                <td><?php echo $row['uom_name']; ?></td>
                <td></td>
            </tr>
            <?php
        }
    }

    public function filterDemandVoucherList()
    {
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        //$selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if ($selectVoucherStatus == '0' && empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '!=', 0)->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '0' && !empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->whereIn('status', array(1, 2))->where('sub_department_id', '=', $selectSubDepartmentId)->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '1' && !empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '1')->where('demand_status', '=', '1')->where('sub_department_id', '=', $selectSubDepartmentId)->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '1')->where('demand_status', '=', '2')->where('sub_department_id', '=', $selectSubDepartmentId)->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '2')->where('sub_department_id', '=', $selectSubDepartmentId)->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '1' && empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '1')->where('demand_status', '=', '1')->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '2' && empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '1')->where('demand_status', '=', '2')->orderBy('id','desc')->get();
        } else if ($selectVoucherStatus == '3' && empty($selectSubDepartmentId)) {
            $demandDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->where('status', '=', '2')->orderBy('id','desc')->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Purchase.AjaxPages.filterDemandVoucherList', compact('demandDetail'));
    }

    public function deletePurchaseReturn(Request $request)
    {
        $DeleteId = $request->Id;
        //echo "<br>";
        $PrNo = $request->PrNo;
        //die();


        $DeleteData['status'] = 2;

        DB::Connection('mysql2')->table('stock')->where('main_id', $DeleteId)->where('voucher_no', $PrNo)->where('voucher_type',2)->update($DeleteData);
        DB::Connection('mysql2')->table('purchase_return')->where('id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('purchase_return_data')->where('master_id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('transaction_supply_chain')->where('main_id',$DeleteId)->update($DeleteData);

        if ($PrNo!=''):
            DB::Connection('mysql2')->table('transactions')->where('voucher_no', $PrNo)->update($DeleteData);
        endif;
        $Master = DB::Connection('mysql2')->selectOne('select pr_no,pr_date  from purchase_return where id = '.$DeleteId.'');
        $Detail = DB::Connection('mysql2')->selectOne('select SUM(net_amount) net_amount from purchase_return_data where master_id = '.$DeleteId.'');
        CommonHelper::inventory_activity($Master->pr_no,$Master->pr_date,$Detail->net_amount,4,'Delete');
        echo $DeleteId;

    }

    public function deleteStockTransfer(Request $request)
    {
        $DeleteId = $request->Id;
        //echo "<br>";
        $TrNo = $request->TrNo;
        //die();


        $Master = DB::Connection('mysql2')->selectOne('select tr_no,tr_date  from stock_transfer where id = '.$DeleteId.'');
        $Detail = DB::Connection('mysql2')->selectOne('select SUM(amount) amount from stock_transfer_data where master_id = '.$DeleteId.'');


        CommonHelper::inventory_activity($Master->tr_no,$Master->tr_date,$Detail->amount,6,'Delete');


        $DeleteData['status'] = 2;

        DB::Connection('mysql2')->table('stock_transfer')->where('id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('stock_transfer_data')->where('master_id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('stock')->where('voucher_no', $TrNo)->update($DeleteData);

        echo $DeleteId;

    }

    public function DeleteStockReturn(Request $request)
    {
        $issuance_no = $request->issuance_no;
        $Data['status'] = 2;
        DB::Connection('mysql2')->table('stock_return')->where('issuance_no', $issuance_no)->update($Data);
        DB::Connection('mysql2')->table('stock_return_data')->where('issuance_no', $issuance_no)->update($Data);
        DB::Connection('mysql2')->table('stock')->where('voucher_no', $issuance_no)->update($Data);
        echo $issuance_no;

    }

    public function DeleteGrn(Request $request)
    {
        $DeleteId = $request->Id;
        $DeleteData['status'] = 2;
        DB::Connection('mysql2')->table('goods_receipt_note')->where('id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('grn_data')->where('master_id', $DeleteId)->update($DeleteData);
        echo $DeleteId;

    }

    public function MasterDeleteGrn(Request $request)
    {
        $DeleteId = $request->Id;

        $data= DB::Connection('mysql2')->table('grn_data as a')
            ->join('goods_receipt_note as b','a.master_id','=','b.id')
            ->select('a.*')
            ->where('b.status',1)->where('a.master_id',$DeleteId)
            ->where('a.grn_status',2)->get();

        $Grn = DB::Connection('mysql2')->selectOne("SELECT SUM(net_amount) net_amount,grn_no,grn_date FROM grn_data WHERE master_id = '$DeleteId'");

        echo "<pre>"; print_r($Grn); die;
        $GrnExp = DB::Connection('mysql2')->selectOne('select SUM(amount) amount  from addional_expense where main_id = '.$DeleteId.' and voucher_no = "'.$Grn->grn_no.'" ');
        $TotAmount = $Grn->net_amount+$GrnExp->amount;
        CommonHelper::inventory_activity($Grn->grn_no,$Grn->grn_date,$TotAmount,3,'Delete');


        foreach($data as $row):
            $SumQty = DB::Connection('mysql2')->selectOne('SELECT sum(purchase_recived_qty) SumQty FROM grn_data where master_id = '.$DeleteId.'
             and sub_item_id="'.$row->sub_item_id.'" group by sub_item_id');
            $qty=ReuseableCode::get_stock($row->sub_item_id,$row->warehouse_id,$SumQty->SumQty,$row->batch_code);

            if ($qty<0):


                echo "yes";
                die;
            endif;
        endforeach;

        $count= DB::Connection('mysql2')->table('purchase_return')->where('grn_id',$DeleteId)->where('status',1)->count();
        if ($count>0):
            echo 0;
            die;
        endif;


        $grn_no = DB::Connection('mysql2')->selectOne('select grn_no,po_no from goods_receipt_note where id = '.$DeleteId.'');
        $DeleteData['status'] = 2;
        $Revers['purchase_request_status'] = 2;
        DB::Connection('mysql2')->table('goods_receipt_note')->where('id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('grn_data')->where('master_id', $DeleteId)->update($DeleteData);
        DB::Connection('mysql2')->table('stock')->where('main_id', $DeleteId)->where('voucher_type',1)
            ->where('transfer_status',0)->update($DeleteData);
        DB::Connection('mysql2')->table('purchase_request')->where('purchase_request_no', $grn_no->po_no)->update($Revers);
        echo $DeleteId;
    }

    public function viewDemandVoucherDetail()
    {
        return view('Purchase.AjaxPages.viewDemandVoucherDetail');
    }
    public function viewPaymentDetail()
    {
        return view('Purchase.AjaxPages.viewPaymentDetail');
    }


    public function editProductForm()
    {

        $ProductId = $_GET['id'];
        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product=$product->where('product_id',$ProductId)->where('status',1)->select('*')->first();
        print_r($product); die();
        return view('Purchase.AjaxPages.editProductForm',compact('product'));
    }

    public function purchase_order($id)
    {
        return view('Purchase.purchase_order', compact('id'));
    }

    public function  purchase_order_new()
    {

        return view('Purchase.purchase_order_new');
    }


    public function ApprovedGoodIssuance(Request $request)
    {
        $IssuanceId = $request->IssuanceId;
        $ApproveIssuance['issuance_status'] = 2;
        DB::Connection('mysql2')->table('issuance')->where('id', $IssuanceId)->update($ApproveIssuance);
        DB::Connection('mysql2')->table('issuance_data')->where('master_id', $IssuanceId)->update($ApproveIssuance);

        $Iss = DB::Connection('mysql2')->table('issuance')->where('id', $IssuanceId)->first();
        $IssData = DB::Connection('mysql2')->table('issuance_data')->where('master_id', $IssuanceId)->get();
        foreach($IssData as $IssDataFil)
        {
            $data3['main_id']       = $IssDataFil->master_id;
            $data3['master_id']     = $IssDataFil->id;
            $data3['voucher_no']    = $IssDataFil->iss_no;
            $data3['voucher_date']  = $Iss->iss_date;
            $data3['voucher_type']  = 2;
            $data3['category_id']   = $IssDataFil->category_id;
            $data3['sub_item_id']   = $IssDataFil->sub_item_id;
            $data3['region_id']     = $Iss->region;
            $data3['joborder']     = $Iss->joborder;
            $data3['qty']           = $IssDataFil->qty;
            $data3['status']        = 1;
            $data3['created_date']  = $Iss->created_date;
            $data3['username']      = Auth::user()->name;
            DB::Connection('mysql2')->table('stock')->insert($data3);
        }


        echo $IssuanceId;
    }

    public function ApprovedStockReturn(Request $request)
    {
        $stock_return_id = $request->stock_return_id;
        $data['return_status'] = 2;
        DB::Connection('mysql2')->table('stock_return')->where('stock_return_id', $stock_return_id)->update($data);
        DB::Connection('mysql2')->table('stock_return_data')->where('stock_return_id', $stock_return_id)->update($data);

        $Iss = DB::Connection('mysql2')->table('stock_return')->where('stock_return_id', $stock_return_id)->first();
        $IssData = DB::Connection('mysql2')->table('stock_return_data')->where('stock_return_id', $stock_return_id)->get();

        if($Iss->issuance_type==1){
            $data3['joborder'] = $Iss->joborder;
        }

        foreach($IssData as $IssDataFil)
        {
            $data3['main_id']       = $IssDataFil->stock_return_id;
            $data3['master_id']     = $IssDataFil->stock_return_data_id;
            $data3['voucher_no']    = $Iss->issuance_no;
            $data3['voucher_date']  = $Iss->issuance_date;
            $data3['voucher_type']  = 3;
            $data3['category_id']   = $IssDataFil->category;
            $data3['sub_item_id']   = $IssDataFil->subitem;
            $data3['region_id']     = $Iss->region;
            $data3['qty']           = $IssDataFil->qty;
            $data3['status']        = 1;
            $data3['created_date']  = date("Y-m-d");
            $data3['username']      = Auth::user()->name;
            DB::Connection('mysql2')->table('stock')->insert($data3);
        }
        echo $stock_return_id;

    }

    public  function Recieved(Request $request)
    {
        $m = $_GET['m'];
        $IssuanceId = $request->IssuanceId;

        CommonHelper::companyDatabaseConnection($_GET['m']);
        $transfer_status['transfer_status'] = 1;
        DB::Connection('mysql2')->table('issuance')->where('id', $IssuanceId)->update($transfer_status);

        $Iss = DB::table('issuance')->where('id', $IssuanceId)->first();
        $IssData = DB::table('issuance_data')->where('master_id', $IssuanceId)->get();

        // print_r($Iss);
        //print_r($IssData);
        //die;
        $str = DB::selectOne("select max(convert(substr(`grn_no`,4,length(substr(`grn_no`,4))-4),signed integer)) reg from `goods_receipt_note` where substr(`grn_no`,-4,2) = " . date('m') . " and substr(`grn_no`,-2,2) = " . date('y') . "")->reg;
        $grn_no = 'grn' . ($str + 1) . date('my');

        $data1['grn_no'] = $grn_no;
        $data1['grn_date'] = $Iss->iss_date;
        $data1['po_no'] = $Iss->iss_no;
        $data1['po_date'] = $Iss->iss_date;
        $data1['main_description'] = $Iss->description;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['type'] = 3;
        $data1['grn_status'] = 2;
        $data1['username'] = Auth::user()->name;
        $master_id=DB::table('goods_receipt_note')->insertGetId($data1);

        foreach($IssData as $IssDataFil)
        {
            $data2['master_id'] = $master_id;
            $data2['demand_send_type'] = 3;
            $data2['grn_no'] = $grn_no;
            $data2['grn_date'] = $Iss->iss_date;
            $data2['category_id'] = $IssDataFil->category_id;
            $data2['sub_item_id'] = $IssDataFil->sub_item_id;
            $data2['purchase_recived_qty'] = $IssDataFil->qty;
            $data2['region'] = $Iss->region;
            $data2['region_to'] = $Iss->region_to;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['grn_status'] = 2;
            $data2['username'] = Auth::user()->name;
            $main_id = DB::table('grn_data')->insertGetId($data2);

            $data3['main_id']       = $master_id;
            $data3['master_id']     = $main_id;
            $data3['voucher_no']    = $grn_no;
            $data3['voucher_date']  = $Iss->iss_date;
            $data3['voucher_type']  = 1;
            $data3['category_id']   = $IssDataFil->category_id;
            $data3['sub_item_id']   = $IssDataFil->sub_item_id;
            $data3['region_id']     = $Iss->region_to;
            $data3['region_to']     = $Iss->region;
            $data3['qty']           = $IssDataFil->qty;
            $data3['status']        = 1;
            $data3['created_date']  = $Iss->created_date;
            $data3['username']      = Auth::user()->name;
            $data3['transfer']      = 1;
            DB::table('stock')->insert($data3);
        }

        CommonHelper::reconnectMasterDatabase();
        echo $IssuanceId;
    }

    public function viewJobOrderDetail()
    {
        return view('Purchase.AjaxPages.viewJobOrderDetail');
    }

    public function viewEstimateDetail()
    {
        return view('Purchase.AjaxPages.viewEstimateDetail');
    }


    public function viewSurveyImage()
    {
        return view('Purchase.AjaxPages.viewSurveyImage');
    }

    public function viewPurchaseReturnDetail()
    {

        return view('Purchase.AjaxPages.viewPurchaseReturnDetail');
    }

    public function viewStockReturnDetail()
    {

        return view('Purchase.AjaxPages.viewStockReturnDetail');
    }

    public function viewPurchaseVoucherDetail($id)
    {

        $purchase_voucher = new PurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)->where('id', $id)->select('id', 'pv_no', 'current_amount', 'currency', 'currency_rate', 'supplier', 'description', 'pv_date', 'purchase_type', 'due_date', 'slip_no', 'total_net_amount', 'amount_in_words')->first();
        $PurchaseVoucherData = new PurchaseVoucherData();
        $PurchaseVoucherData = $PurchaseVoucherData->SetConnection('mysql2');
        $PurchaseVoucherData = $PurchaseVoucherData->where('master_id', $id)->select('id', 'pv_no', 'category_id', 'sub_item', 'uom', 'qty', 'rate', 'amount', 'sales_tax_per', 'sales_tax_amount', 'net_amount', 'dept_id', 'txt_nature', 'income_txt_nature', 'txt_nature')->get();
        return view('Purchase.AjaxPages.viewPurchaseVoucherDetail', compact('purchase_voucher', 'PurchaseVoucherData'));
    }


    public function viewHistoryOfItem()
    {
        $id = Input::get('id');
        $grn_data=DB::Connection('mysql2')->table('grn_data')
        ->join('goods_receipt_note', 'goods_receipt_note.id', '=', 'grn_data.master_id')
        ->where('grn_data.sub_item_id',$id)
        ->select('grn_data.*', 'goods_receipt_note.supplier_id','goods_receipt_note.grn_date','goods_receipt_note.bill_date','goods_receipt_note.po_no','goods_receipt_note.supplier_invoice_no')
        ->get();

        return view('Purchase.AjaxPages.viewHistoryOfItem', compact('grn_data'));
    }

    public function viewHistoryOfItem_directPo()
    {
        $id = Input::get('id');
        $grn_data = new GRNData();
        $grn_data = $grn_data->SetConnection('mysql2');
        $grn_data = $grn_data->where('status', 1)->where('sub_item_id', $id)->select('grn_no', 'grn_date', 'batch_code','purchase_approved_qty', 'purchase_recived_qty', 'bal_reciable','rate')->get();

        return view('Purchase.AjaxPages.viewHistoryOfItem_directPo', compact('grn_data'));
    }



    public function viewPurchaseVoucherDetailThroughGrn($id)
    {
echo "aa"; die;
        $purchase_voucher = new PurchaseVoucherThroughGrn();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)->where('id', $id)->select('id', 'pv_no', 'grn_no', 'current_amount', 'currency', 'currency_rate', 'supplier', 'description', 'pv_date', 'purchase_type', 'due_date', 'slip_no', 'total_net_amount', 'amount_in_words', 'sales_tax', 'sales_tax_amount', 'pv_status')->first();
        $PurchaseVoucherData = new PurchaseVoucherThroughGrnData();
        $PurchaseVoucherData = $PurchaseVoucherData->SetConnection('mysql2');
        $PurchaseVoucherData = $PurchaseVoucherData->where('master_id', $id)->select('id', 'pv_no', 'category_id', 'sub_item', 'uom', 'qty', 'rate', 'amount', 'net_amount', 'dept_id', 'discount_percent', 'discount_amount', 'total_amount')->get();
        return view('Purchase.AjaxPages.viewPurchaseVoucherDetailThroughGrn', compact('purchase_voucher', 'PurchaseVoucherData'));
    }

    public function filterGoodsReceiptNoteList(Request $request)
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $grn_no = $request->GrnNo ;
        if ($grn_no != ''):
            $goodsReceiptNoteDetail= GoodsReceiptNote::where('grn_no', 'like', '%' . $grn_no . '%')
                ->where('status',1)->get();
            return view('Purchase.AjaxPages.get_grn_by_grn_no', compact('goodsReceiptNoteDetail'));
        else:
            $fromDate = $_GET['fromDate'];
            $toDate = $_GET['toDate'];
            $selectVoucherStatus = $_GET['selectVoucherStatus'];
            $selectSubDepartment = $_GET['selectSubDepartment'];
            $selectSubDepartmentId = $_GET['selectSubDepartmentId'];
            $selectSupplier = $_GET['selectSupplier'];
            $selectSupplierId = $_GET['selectSupplierId'];
            if($selectVoucherStatus != 0) $query_string_second_part[] = "  AND grn_status ='$selectVoucherStatus'";
            if(!empty($selectSubDepartmentId)) $query_string_second_part[] = "  AND sub_department_id ='$selectSubDepartmentId'";
            if(!empty($selectSupplierId)) $query_string_second_part[] = "  AND supplier_id ='$selectSupplierId'";
        
            $query_string_second_part[] = " AND status = 1";
            $query_string_second_part[] = "AND grn_date BETWEEN '$fromDate' AND '$toDate'";
            $query_string_First_Part = "SELECT * FROM goods_receipt_note WHERE";
            $query_string_third_part = ' ORDER BY id DESC';
            $query_string_second_part = implode(" ", $query_string_second_part);
            $query_string_second_part = preg_replace("/AND/", " ", $query_string_second_part, 1);
            $query_string = $query_string_First_Part . $query_string_second_part . $query_string_third_part;
            $goodsReceiptNoteDetail = DB::select(DB::raw($query_string));
        endif;

        
        CommonHelper::reconnectMasterDatabase();
        return view('Purchase.AjaxPages.filterGoodsReceiptNoteList', compact('goodsReceiptNoteDetail'));
    }

    public function filterGoodsForwardOrderVoucherList()
    {
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $selectBranch = $_GET['selectBranch'];
        $selectBranchId = $_GET['selectBranchId'];
        CommonHelper::companyDatabaseConnection($m);
        $goodsForwardOrderDetail = Demand::whereBetween('demand_date', [$fromDate, $toDate])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Purchase.AjaxPages.filterGoodsForwardOrderVoucherList', compact('goodsForwardOrderDetail'));
    }

    public function viewGoodsReceiptNoteDetail()
    {
        return view('Purchase.AjaxPages.viewGoodsReceiptNoteDetail');
    }

    public function qc()
    {
        return view('Purchase.AjaxPages.qc');
    }

    public function viewGoodsReceiptNoteDetailNewTab()
    {
        return view('Purchase.AjaxPages.viewGoodsReceiptNoteDetailNewTab');
    }

    public function viewGoodsReceiptNoteWPODetail()
    {
        return view('Purchase.AjaxPages.viewGoodsReceiptNoteWPODetail');
    }

    public function filterApproveDemandListandCreateGoodsForwardOrder()
    {
        return view('Purchase.AjaxPages.filterApproveDemandListandCreateGoodsForwardOrder');
    }

    public function createGoodsForwardOrderDetailForm(Request $request)
    {
        return view('Purchase.createGoodsForwardOrderDetailForm', compact('request'));
    }


    public function createSupplierFormAjax($PageName = "")
    {
        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        return view('Purchase.AjaxPages.createSupplierFormAjax', compact('countries', 'PageName'));

    }

    public function createWarehouseFormAjax()
    {

        return view('Purchase.AjaxPages.createWarehouseFormAjax');

    }

    public function createPurchaseTypeForm()
    {

        return view('Purchase.AjaxPages.createPurchaseTypeForm');

    }

    public function createCurrencyTypeForm()
    {

        return view('Purchase.AjaxPages.createCurrencyTypeForm');

    }

    public function createDepartmentFormAjax($id)
    {

        return view('Purchase.AjaxPages.createDepartmentFormAjax', compact('id'));

    }

    public function createCostCenterFormAjax($id)
    {

        return view('Purchase.AjaxPages.createCostCenterFormAjax', compact('id'));

    }

    public function createSubItemFormAjax($id = '')
    {
        $uom = new UOM;
        $uom = $uom::where('status', '=', '1')->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();

        return view('Purchase.AjaxPages.createSubItemFormAjax', compact('categories', 'uom', 'id'));

    }

    public function addSupplierDetail()
    {
        //print_r($_POST); die();
        $supplier_name = Input::get('supplier_name');


        DB::transaction(function () {
            $NamePage = Input::get('PageName');
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $account_head = Input::get('account_head');
            $supplier_name = Input::get('supplier_name');
            $country = Input::get('country');
            $state = Input::get('state');
            $city = Input::get('city');
            $email = Input::get('email');
            $o_blnc_trans = Input::get('o_blnc_trans');
            $register_income_tax = Input::get('regd_in_income_tax');
            $business_type = Input::get('optradio');
            $ntn = Input::get('ntn');
            $cnic = Input::get('cnic');
            $regd_in_sales_tax = Input::get('regd_in_sales_tax');
            $strn = Input::get('strn');
            $regd_in_srb = Input::get('regd_in_srb');
            $srb = Input::get('srb');
            $regd_in_pra = Input::get('regd_in_pra');
            $pra = Input::get('pra');

            $print_check_as = Input::get('print_check_as');
            $vendor_type = Input::get('vendor_type');
            $website = Input::get('website');
            $credit_limit = Input::get('credit_limit');
            $acc_no = Input::get('acc_no');
            $bank_name = Input::get('bank_name');
            $bank_address = Input::get('bank_address');
            $branch_name = Input::get('branch_name');
            $swift_code = Input::get('swift_code');
            $open_date = Input::get('open_date');


            $address[] = Input::get('address');
            $o_blnc = Input::get('o_blnc');
            $operational = '1';
            $sent_code = '2-1';

            $max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $account_head . '\'')->id;
            if ($max_id == '') {
                $code = $sent_code . '-1';
            } else {
                $max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                $max_code2;
                $max = explode('-', $max_code2);
                $code = $sent_code . '-' . (end($max) + 1);
            }

            $level_array = explode('-', $code);
            $counter = 1;
            foreach ($level_array as $level):
                $data1['level' . $counter] = strip_tags($level);
                $counter++;
            endforeach;
            $data1['code'] = strip_tags($code);
            $data1['name'] = strip_tags($supplier_name);
            $data1['parent_code'] = strip_tags($account_head);
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['action'] = 'create';
            $data1['operational'] = strip_tags($operational);
            $data1['acc_type'] = 1;
            $acc_id = DB::table('accounts')->insertGetId($data1);


            $data2['acc_id'] = strip_tags($acc_id);
            $data2['resgister_income_tax'] = strip_tags($register_income_tax);
            $data2['business_type'] = strip_tags($business_type);
            $data2['cnic'] = strip_tags($cnic);
            $data2['ntn'] = strip_tags($ntn);
            $data2['register_sales_tax'] = strip_tags($regd_in_sales_tax);
            $data2['strn'] = strip_tags($strn);
            $data2['register_srb'] = strip_tags($regd_in_srb);
            $data2['srb'] = strip_tags($srb);
            $data2['register_pra'] = strip_tags($regd_in_pra);
            $data2['pra'] = strip_tags($pra);
            $data2['name'] = strip_tags($supplier_name);
            $data2['country'] = strip_tags($country);
            $data2['province'] = strip_tags($state);
            $data2['city'] = strip_tags($city);
            $data2['email'] = strip_tags($email);
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['action'] = 'create';
            $data2['company_id'] = $_GET['m'];
            $data2['print_check_as'] = $print_check_as;
            $data2['vendor_type'] = $vendor_type;
            $data2['website'] = $website;
            $data2['credit_limit'] = $credit_limit;
            $data2['acc_no'] = $acc_no;
            $data2['bank_name'] = $bank_name;
            $data2['bank_address'] = $bank_address;
            $data2['swift_code'] = $swift_code;
            $data2['branch_name'] = $branch_name;
            $data2['opening_bal_date'] = $open_date;
            $lastInsertedID = DB::table('supplier')->InsertGetId($data2);

            $count1 = count(Input::get('contact_no'));
            $count2 = count(Input::get('address'));
            if ($count1 == $count2):
                $count = $count1;
            else:

                if ($count1 > $count2):
                    $count = $count1;
                else:
                    $count = $count2;
                endif;
            endif;
            $count = $count - 1;

            for ($i = 0; $i <= $count; $i++):
                $data4['supp_id'] = $lastInsertedID;


                $ii = $i + 1;
                if ($count2 >= $ii):


                    $data4['address'] = Input::get('address')[$i];
                else:
                    $data4['address'] = '';
                endif;

                if ($count1 >= $ii):


                    $data4['contact_no'] = Input::get('contact_no')[$i];
                else:
                    $data4['contact_no'] = '';
                endif;
                DB::table('supplier_info')->insert($data4);
            endfor;

            $data3['acc_id'] = strip_tags($acc_id);
            $data3['acc_code'] = strip_tags($code);
            $data3['debit_credit'] = 0;
            $data3['amount'] = strip_tags($o_blnc);
            $data3['opening_bal'] = 1;
            $data3['username'] = Auth::user()->name;
            $data3['date'] = date("Y-m-d");
            $data3['v_date'] = date("Y-m-d");

            $data3['action'] = 'create';
            DB::table('transactions')->insert($data3);

            if ($NamePage == "jvs") {
                echo $NamePage . '+' . $acc_id . '+' . '1' . '+' . $lastInsertedID . '+' . ucwords($supplier_name);
            } elseif ($NamePage == "pvs") {
                echo $NamePage . '+' . $acc_id . '+' . ucwords($supplier_name);
            } else {
                echo $lastInsertedID . '+' . ucwords($supplier_name) . '+' . Input::get('address')[0] . '+' . $ntn;
            }

        });
        CommonHelper::reconnectMasterDatabase();

    }

    public function addPurchaseType(Request $request)
    {

        $name = $request->purchase_type;
        $purchase_type = new PurchaseType();
        $purchase_type = $purchase_type->SetConnection('mysql2');
        $purchase_type_count = $purchase_type->where('status', 1)->where('name', $name)->count();;

        if ($purchase_type_count > 0):
            echo 0;

        else:

            $purchase_type->name = $name;
            $purchase_type->username = Auth::user()->name;
            $purchase_type->date = date('Y-m-d');
            $purchase_type->save();
            $id = $purchase_type->id;
            echo $id . ',' . $name;
        endif;
    }

    public function addCurrency()
    {
        $currency_name = $_GET['currency'];

        $currecy = new Currency();
        $currecy = $currecy->SetConnection('mysql2');
        $currecy_count = $currecy->where('status', 1)->where('curreny', $currency_name)->count();;

        if ($currecy_count > 0):
            echo 0;

        else:
            $currecy->curreny = $currency_name;
            $currecy->username = Auth::user()->name;;
            $currecy->date = date('Y-m-d');

            $currecy->save();
            $id = $currecy->id;

            echo $id . ',' . '0.00' . '/' . ucfirst($currency_name);
        endif;
    }

    public function addCurrencyForm(Request $request)
    {


        $currency = new Currency();
        $currency = $currency->SetConnection('mysql2');
        $data['rate'] = $request->rate;
        $id = $request->dropdown_currency;
        $id = explode(',', $id);
        $id = $id[0];
        $currency->where('id', array($id))->update($data);
        $request->rate;

        $currency_data = $currency->where('status', 1)->get();
        ?>

        <select>
            <option value="0,1">Select</option>
            <?php foreach ($currency_data as $row): ?>
                <option value="<?php echo $row->id . ',' . $row->rate ?>"><?php echo $row->curreny ?></option><?php
            endforeach; ?></select>
        <?php
    }

    public function addSubItemDetailAjax()
    {


        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $category_name = Input::get('category_name');
        $sub_item_name = Input::get('sub_item_name');
        $opening_qty = Input::get('opening_qty');
        $opening_value = Input::get('opening_value');
        $reorder_level = Input::get('reorder_level');
        $id = Input::get('id');
        //  $rate = Input::get('rate');
        $uom = Input::get('uom_id');

        $pack_size = Input::get('pack_size');
        $desc = Input::get('desc');
        $type = Input::get('type');
        $rate = Input::get('rate');
        $username = '';
        $branch_id = '';
        $o_blnc_trans_form = 1;
        $wip_finish_g_form = 'fara';
        //  $acc_id = DB::selectOne('select `acc_id` from `category` where `id` = '.$category_name.'')->acc_id;
        //  $parent_code = DB::selectOne('select code from `accounts` where `id` = '.$acc_id.'')->code;
        //  $max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \''.$parent_code.'\'')->id;
        //  if($max_id == ''){
        //     $code = $parent_code.'-1';
        //  }else{
        //      $max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \''.$max_id.'\'')->code;
        //     $max_code2;
        //     $max = explode('-',$max_code2);
        //     $code = $parent_code.'-'.(end($max)+1);
        //   }
        //  $level_array = explode('-',$code);
        //  $counter = 1;
        //  foreach($level_array as $level):
        //      $data1['level'.$counter] = strip_tags($level);
        //     $counter++;
        //  endforeach;

        //  $data1['code']              = strip_tags($code);
        //   $data1['name']              = strip_tags($sub_item_name);
        //   $data1['parent_code']       = strip_tags($parent_code);
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['action'] = 'create';
        $data1['operational'] = 1;


        //  $acc_id_new = DB::table('accounts')->insertGetId($data1);

        //  $data2['acc_id']			= strip_tags($acc_id_new);
        $data2['sub_ic'] = strip_tags($sub_item_name);
        $data2['main_ic_id'] = strip_tags($category_name);
        $data2['reorder_level'] = strip_tags($reorder_level);
        $data2['uom'] = strip_tags($uom);
        $data2['rate'] = $rate;
        $data2['username'] = Auth::user()->name;
        $data2['date'] = date("Y-m-d");
        $data2['time'] = date("H:i:s");
        $data2['action'] = 'create';
        $data2['company_id'] = $m;
        $data2['pack_size'] = $pack_size;
        $data2['description'] = $desc;
        $data2['itemType'] = $type;


        $s_id = DB::table('subitem')->insertGetId($data2);
        echo $s_id . ',' . ucfirst($sub_item_name) . ',' . $category_name . ',' . $id;


        die;

        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $category_name = Input::get('category_name');
        $sub_item_name = Input::get('sub_item_name');
        $opening_qty = Input::get('opening_qty');
        $opening_value = Input::get('opening_value');
        $reorder_level = Input::get('reorder_level');
        $rate = Input::get('rate');
        $uom = Input::get('uom_id');
        $username = '';
        $branch_id = '';
        $o_blnc_trans_form = 1;
        $wip_finish_g_form = 'fara';
        $acc_id = DB::selectOne('select `acc_id` from `category` where `id` = ' . $category_name . '')->acc_id;
        $parent_code = DB::selectOne('select code from `accounts` where `id` = ' . $acc_id . '')->code;
        $max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $parent_code . '\'')->id;
        if ($max_id == '') {
            $code = $parent_code . '-1';
        } else {
            $max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
            $max_code2;
            $max = explode('-', $max_code2);
            $code = $parent_code . '-' . (end($max) + 1);
        }
        $level_array = explode('-', $code);
        $counter = 1;
        foreach ($level_array as $level):
            $data1['level' . $counter] = strip_tags($level);
            $counter++;
        endforeach;

        $data1['code'] = strip_tags($code);
        $data1['name'] = strip_tags($sub_item_name);
        $data1['parent_code'] = strip_tags($parent_code);
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['action'] = 'create';
        $data1['operational'] = 1;


        $acc_id_new = DB::table('accounts')->insertGetId($data1);

        $data2['acc_id'] = strip_tags($acc_id_new);
        $data2['sub_ic'] = strip_tags($sub_item_name);
        $data2['main_ic_id'] = strip_tags($category_name);
        $data2['reorder_level'] = strip_tags($reorder_level);
        $data2['uom'] = strip_tags($uom);
        $data2['rate'] = $rate;
        $data2['username'] = Auth::user()->name;
        $data2['date'] = date("Y-m-d");
        $data2['time'] = date("H:i:s");
        $data2['action'] = 'create';
        $data2['company_id'] = $m;

        $s_id = DB::table('subitem')->insertGetId($data2);

        $data3['acc_code'] = strip_tags($code);
        $data3['acc_id'] = strip_tags($acc_id_new);
        $data3['debit_credit'] = 1;
        $data3['opening_bal'] = 1;
        $data3['username'] = Auth::user()->name;
        $data3['v_date'] = date("Y-m-d");
        $data3['date'] = date("Y-m-d");

        $data3['action'] = 'create';
        $data3['status'] = 1;
        $data3['amount'] = $opening_value;

        DB::table('transactions')->insert($data3);

        $data4['main_ic_id'] = strip_tags($category_name);
        $data4['sub_ic_id'] = strip_tags($s_id);
        $data4['value'] = $opening_value;
        $data4['qty'] = strip_tags($opening_qty);
        $data4['date'] = date("Y-m-d");
        $data4['time'] = date("H:i:s");
        $data4['action'] = '1';//1 for opening
        $data4['username'] = Auth::user()->name;
        $data4['date'] = date("Y-m-d");
        $data4['time'] = date("H:i:s");
        $data4['company_id'] = $m;

        DB::table('fara')->insert($data4);

        CommonHelper::reconnectMasterDatabase();

        echo $s_id . ',' . ucfirst($sub_item_name);
        //   return Redirect::to('purchase/viewSubItemList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');

    }


    public function addDepartmentFormAjax(Request $request)
    {
        $name = $request->dept_name;
        $department = new FinanceDepartment();
        $department = $department->SetConnection('mysql2');
        $department_count = $department->where('status', 1)->where('name', $name)->count();
        if ($department_count > 0):
            Session::flash('dataDelete', $name . ' ' . 'Already Exists.');
            return Redirect::to('finance/createDepartmentForm?pageType=add&&parentCode=82&&m=1#SFR');
        else:
            $parent_code = $request->account_id;
            $sent_code = $parent_code;


            if ($request->first_level == 1):
                $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `finance_department` WHERE `first_level`=1')->id;
                $code = $max_id + 1;
                $level = $code;
                $department->level1 = $level;

            else:


                $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `finance_department` WHERE `parent_code` LIKE \'' . $parent_code . '\'')->id;
                if ($max_id == '') {
                    $code = $sent_code . '-1';
                } else {
                    $max_code2 = DB::connection('mysql2')->selectOne('SELECT `code`  FROM `finance_department` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                    $max_code2;
                    $max = explode('-', $max_code2);
                    $code = $sent_code . '-' . (end($max) + 1);
                }


                $level_array = explode('-', $code);
                $counter = 1;
                foreach ($level_array as $level):
                    $levell = 'level' . $counter . '';
                    $department->$levell = $level;

                    $counter++;
                endforeach;


            endif;
            $department->code = $code;
            $department->parent_code = $parent_code;
            $department->first_level = $request->first_level;
            $department->name = $name;
            $department->username = Auth::user()->name;
            $department->date = date('Y-m-d');
            $department->save();
            $id = $department->id;
            //   Session::flash('dataInsert','successfully saved.');
            //  return Redirect::to('finance/createDepartmentForm?pageType=add&&parentCode=82&&m=1#SFR');
        endif;

        echo $id . ',' . ucwords($name) . ',' . $request->id;

    }


    public function addCostCenterFormajax(Request $request)
    {
        $name = $request->cost_center;
        $cost_center = new CostCenter();
        $cost_center = $cost_center->SetConnection('mysql2');
        $cost_center_count = $cost_center->where('status', 1)->where('name', $name)->count();
        if ($cost_center_count > 0):
            echo 0;
        //    Session::flash('dataDelete', $name . ' ' . 'Already Exists.');
        //   return Redirect::to('finance/createCostCenterForm?pageType=add&&parentCode=82&&m=1#SFR');
        else:
            $parent_code = $request->parent_cost_center;
            $sent_code = $parent_code;
            if ($request->first_level == 1):
                $id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `first_level`=1')->id;
                $max_id = DB::connection('mysql2')->selectOne('SELECT code  FROM `cost_center` WHERE `first_level`=1 and id="' . $id . '"')->code;
                $code = $max_id + 1;
                $level = $code;
                $cost_center->level1 = $level;
            else:
                $max_id = DB::connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `cost_center` WHERE `parent_code` LIKE \'' . $parent_code . '\'')->id;
                if ($max_id == '') {
                    $code = $sent_code . '-1';
                } else {
                    $max_code2 = DB::connection('mysql2')->selectOne('SELECT `code`  FROM `cost_center` WHERE `id` LIKE \'' . $max_id . '\'')->code;
                    $max_code2;
                    $max = explode('-', $max_code2);
                    $code = $sent_code . '-' . (end($max) + 1);
                }
                $level_array = explode('-', $code);
                $counter = 1;
                foreach ($level_array as $level):
                    $levell = 'level' . $counter . '';
                    $cost_center->$levell = $level;
                    $counter++;
                endforeach;
            endif;
            $cost_center->code = $code;
            $cost_center->parent_code = $parent_code;
            $cost_center->first_level = $request->first_level;
            $cost_center->name = $name;
            $cost_center->username = Auth::user()->name;
            $cost_center->date = date('Y-m-d');
            $cost_center->save();
            $id = $cost_center->id;

            echo $id . ',' . ucwords($name) . ',' . $request->id;
            //  Session::flash('dataInsert', 'successfully saved.');
            //   return Redirect::to('finance/createCostCenterForm?pageType=add&&parentCode=82&&m=1#SFR');
        endif;
    }

    public function viewPurchaseVoucherDetailAfterSubmit($id)
    {

        $purchase_voucher = new PurchaseVoucher();
        $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
        $purchase_voucher = $purchase_voucher->where('status', 1)->where('id', $id)->select('id', 'pv_no', 'current_amount', 'currency', 'supplier', 'description', 'pv_date', 'purchase_type', 'due_date', 'slip_no', 'total_net_amount', 'amount_in_words')->first();
        $PurchaseVoucherData = new PurchaseVoucherData();
        $PurchaseVoucherData = $PurchaseVoucherData->SetConnection('mysql2');
        $PurchaseVoucherData = $PurchaseVoucherData->where('master_id', $id)->select('id', 'pv_no', 'category_id', 'sub_item', 'uom', 'qty', 'rate', 'amount', 'sales_tax_per', 'sales_tax_amount', 'net_amount', 'dept_id', 'txt_nature')->get();
        return view('Purchase.viewPurchaseVoucherDetailAfterSubmit', compact('purchase_voucher', 'PurchaseVoucherData'));
    }

    public function deletepurchasevoucher()
    {

        $id = $_GET['id'];
        $GrnNo = $_GET['grnno'];
        $PvNo = $_GET['pvno'];
        $UpdateData['status'] = 0;
        $UpdateData2['staus'] = 0;
        $UpdateGrn['grn_status'] = 2;
        DB::Connection('mysql2')->beginTransaction();
        try
        {
            $purchase_voucher = new NewPurchaseVoucher();
            $purchase_voucher = $purchase_voucher->SetConnection('mysql2');
            $purchase_voucher->where('id', $id)->update($UpdateData);

            $purchase_voucher_data = new NewPurchaseVoucherData();
            $purchase_voucher_data = $purchase_voucher_data->SetConnection('mysql2');
            $purchase_voucher_data->where('master_id', $id)->update($UpdateData2);

            $Grn = new GoodsReceiptNote();
            $Grn = $Grn->SetConnection('mysql2');
            $Grn->where('grn_no', $GrnNo)->update($UpdateGrn);

            $tran = new Transactions();
            $tran = $tran->SetConnection('mysql2');
            $tran->where('voucher_no', $PvNo)->update($UpdateData);
            //CommonHelper::inventory_activity($pv_no,$purchase_date,$TotAmount,5,'Insert');
            DB::Connection('mysql2')->table('transaction_supply_chain')->where('main_id',$id)->update($UpdateData);
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
    }

    public function fetchDataOfMr(){
        $mr_id = $_GET['mr_id'];
        $material_requests = DB::connection('mysql2')->table('material_requests')->where([ ['id',$mr_id] , ['status', 1] ])->first();
        $material_request_data = DB::connection('mysql2')->table('material_request_datas')->where([ ['material_request_no',$material_requests->material_request_no] , ['status', 1] ])->get();
        $departments = new Department;
        $departments = $departments::where([['status', '=', '1'], ])->select('id','department_name')->orderBy('id')->get();
        return view('Purchase.AjaxPages.fetchDataOfMr', compact('material_requests','departments','material_request_data'));
    }

    public function purchase_voucher_list_ajax(Request $request)
    {


        $fromDate = $_GET['from'];
        $to = $_GET['to'];
        $SupplierId = $_GET['SupplierId'];
        $ref_no = $_GET['ref_no']??'';
        $pi_no = $_GET['pi_no']??'';
        $RadioVal = $_GET['RadioVal'] ?? 9;

        $m = $_GET['m'];
        $purchase_voucher=new NewPurchaseVoucher();
        $purchase_voucher=$purchase_voucher->SetConnection('mysql2');
        if($SupplierId == 'all'):
            if($RadioVal == 1):
            $purchase_voucher=$purchase_voucher->where('status',1)
                ->whereBetween('pv_date', [$fromDate, $to])
                ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
            else:
                $purchase_voucher=$purchase_voucher->where('status',1)->where('work_order_id','!=','0')
                    ->whereBetween('pv_date', [$fromDate, $to])
                    ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
            endif;

        else:
            if($RadioVal == 1):
            $purchase_voucher=$purchase_voucher->where('status',1)
                ->whereBetween('pv_date', [$fromDate, $to])->where('supplier',$SupplierId)->where('grn_id','!=','0')
                ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
            else:
                $purchase_voucher=$purchase_voucher->where('status',1)
                    ->whereBetween('pv_date', [$fromDate, $to])->where('supplier',$SupplierId)->where('work_order_id','!=','0')
                    ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
            endif;
        endif;

        $purchase_voucher=new NewPurchaseVoucher();
        $purchase_voucher=$purchase_voucher->SetConnection('mysql2');
        $purchase_voucher=$purchase_voucher->where('status',1)
        ->whereBetween('pv_date', [$fromDate, $to])
        ->when($SupplierId!='all',function ($query) use ($SupplierId){
            $query->where('supplier',$SupplierId);
        })
        ->when($ref_no!='',function ($query) use ($ref_no){
            $query->where('slip_no','Like',"%".$ref_no."%");
        })
        ->when($pi_no!='',function ($query) use ($pi_no){
            $query->where('pv_no','Like',"%".$pi_no."%");
        })
        ->orderBy('pv_date','ASC')->orderBy('id', 'desc')->get();
        return view('Purchase.AjaxPages.purchase_voucher_list_ajax', compact('purchase_voucher','m'));

    }


    public function get_data_debit_note_ajax()
    {

        $fromDate = $_GET['from'];
        $to = $_GET['to'];
        $SupplierId = $_GET['SupplierId'];
        $VoucherType = $_GET['VoucherType'];

        $m = $_GET['m'];
        return view('Purchase.AjaxPages.get_data_debit_note_ajax', compact('fromDate','to','SupplierId','VoucherType','m'));

    }


    public function filterByClientAndRegionJobOrder()
    {

        $ClientId = $_GET['ClientId'];
        $RegionId = $_GET['RegionId'];
        $ClientJobId = $_GET['ClientJobId'];

        $m = $_GET['m'];

        if($ClientId != "" && $RegionId == "" && $ClientJobId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->select('*')->get();
        }
        elseif($ClientId == "" && $ClientJobId == "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId == "" && $RegionId == "" && $ClientJobId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId != "" && $RegionId != "" && $ClientJobId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId != "" && $ClientJobId != "" && $RegionId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId == "" && $ClientJobId != "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('region_id',$RegionId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId != "" && $ClientJobId != "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('region_id',$RegionId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        else
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->select('*')->get();
        }

        return view('Purchase.AjaxPages.filterByClientAndRegion', compact('joborder','m'));

    }

    public function filterByCategoryAndRegionWiseStockOpening()
    {
        return view('Purchase.AjaxPages.filterByCategoryAndRegionWiseStockOpening');
    }

    public function ItemWiseReportAjax()
    {
        return view('Purchase.AjaxPages.ItemWiseReportAjax');
    }

    public function filterByClientAndRegionJobOrderTwo()
    {

        $ClientId = $_GET['ClientId'];
        $RegionId = $_GET['RegionId'];
        $ClientJobId = $_GET['ClientJobId'];

        $m = $_GET['m'];

        if($ClientId != "" && $RegionId == "" && $ClientJobId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('branch_id',0)->select('*')->get();
        }
        elseif($ClientId == "" && $ClientJobId == "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId == "" && $RegionId == "" && $ClientJobId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId != "" && $RegionId != "" && $ClientJobId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('region_id',$RegionId)->select('*')->get();
        }
        elseif($ClientId != "" && $ClientJobId != "" && $RegionId == "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId == "" && $ClientJobId != "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('region_id',$RegionId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        elseif($ClientId != "" && $ClientJobId != "" && $RegionId != "")
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->where('client_name',$ClientId)->where('region_id',$RegionId)->where('client_job',$ClientJobId)->select('*')->get();
        }
        else
        {
            $joborder=new JobOrder();
            $joborder=$joborder->SetConnection('mysql2');
            $joborder=$joborder->where('status',1)->select('*')->get();
        }

        if($ClientId != "")
        {
            $Branch=new Branch();
            $Branch=$Branch->SetConnection('mysql2');
            $Branch=$Branch->where('status',1)->where('client_id',$ClientId)->select('*')->get();
        }
        else
        {
            $Branch=new Branch();
            $Branch=$Branch->SetConnection('mysql2');
            $Branch=$Branch->where('status',1)->select('*')->get();
        }



        return view('Purchase.AjaxPages.filterByClientAndRegionJobOrderTwo', compact('joborder','Branch','m'));

    }



    public function issuanceDataFilter()
    {

        $FromDate = $_GET['FromDate'];
        $ToDate = $_GET['ToDate'];
        $IssuanceType = $_GET['IssuanceType'];
        $m = $_GET['m'];

        if($FromDate != "" && $ToDate!= "" && $IssuanceType == "all")
        {
            $Issuance=new Issuance();
            $Issuance=$Issuance->SetConnection('mysql2');
            $Issuance=$Issuance->where('status',1)->whereBetween('iss_date', [$FromDate, $ToDate])->select('*')->get();
        }
        else
        {
            $Issuance=new Issuance();
            $Issuance=$Issuance->SetConnection('mysql2');
            $Issuance=$Issuance->where('status',1)->where('issuance_type',$IssuanceType)->whereBetween('iss_date', [$FromDate, $ToDate])->select('*')->get();
        }

        return view('Purchase.AjaxPages.issuanceDataFilter', compact('Issuance','m'));

    }


    public function createCategoryFormAjax()
    {

        return view('Purchase.AjaxPages.createCategoryFormAjax');

    }

    public function addCategoryDetailAjax()
    {
        $m = $_GET['m'];
        $category_name = Input::get('category_name');
        $wip_finish_g_form = 'fara';
        $branch_id = '';
        $username = '';
        $o_blnc_trans = 1;
        $o_blnc = 0;
        $data2['main_ic'] = strip_tags($category_name);
        $data2['type'] = 2;
        $data2['username'] = Auth::user()->name;
        $data2['date'] = date("Y-m-d");
        $data2['time'] = date("H:i:s");
        $data2['action'] = 'create';
        $data2['company_id'] = $m;
        $m_id = DB::connection('mysql2')->table('category')->insertGetId($data2);
        CommonHelper::reconnectMasterDatabase();
        echo $m_id . ',' . ucwords($category_name);
        // return Redirect::to('purchase/viewCategoryList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function addWarehouseDetailAjax(Request $request)
    {
        $name = $request->warehouse;
        $warehouse = new Warehouse();
        $warehouse = $warehouse->SetConnection('mysql2');
        $warehouse_count = $warehouse->where('status', 1)->where('name', $name)->count();
        if ($warehouse_count > 0):
            echo 0;
        //   Session::flash('dataDelete', $name . ' ' . 'Already Exists.');
        //   return Redirect::to('purchase/createWarehouseForm?pageType=add&&parentCode=82&&m=1#SFR');
        else:

            $warehouse->name = $name;
            $warehouse->username = Auth::user()->name;
            $warehouse->date = date('Y-m-d');
            $warehouse->save();
            $id = $warehouse->id;
            //   Session::flash('dataInsert', 'successfully saved.');
            echo $id . ',' . ucwords($name);
            //   return Redirect::to('purchase/createWarehouseForm?pageType=add&&parentCode=82&&m=1#SFR');
        endif;

    }

    function approve_grn()
    {

        DB::Connection('mysql2')->beginTransaction();
        try
        {
            $id = $_GET['id'];
            $data['grn_status'] = 3;
            $data['approve_username'] = Auth::user()->name;

            $grn=DB::Connection('mysql2')->table('grn_data')
                ->join('goods_receipt_note', 'goods_receipt_note.id', '=', 'grn_data.master_id')
                ->where('grn_data.master_id',$id)
                ->select('grn_data.*', 'goods_receipt_note.supplier_id','goods_receipt_note.grn_date','goods_receipt_note.bill_date','goods_receipt_note.po_no','goods_receipt_note.supplier_invoice_no','goods_receipt_note.grn_no')->get();

            // Check if purchase return exists for this GRN
            $purchase_return = DB::Connection('mysql2')->table('purchase_return')
                ->where('grn_id', $id)
                ->where('status', 1)
                ->first();
            
            // Get purchase return data items if purchase return exists
            $return_items = [];
            if ($purchase_return) {
                $return_data = DB::Connection('mysql2')->table('purchase_return_data')
                    ->where('master_id', $purchase_return->id)
                    ->where('status', 1)
                    ->get();
                
                // Create array with grn_data_id as key and return_qty as value
                foreach ($return_data as $return_item) {
                    $return_items[$return_item->grn_data_id] = floatval($return_item->return_qty ?? 0);
                }
            }

            // Stock entries - exclude returned items
            foreach($grn as $row):
                // Check if this item has a return quantity
                $return_qty = isset($return_items[$row->id]) ? $return_items[$row->id] : 0;
                $received_qty = floatval($row->purchase_recived_qty ?? 0);
                
                // Only add to stock if there's accepted quantity (received - returned)
                $accepted_qty = $received_qty - $return_qty;
                
                if ($accepted_qty > 0) {
                    // Calculate amounts based on accepted quantity
                    $rate = floatval($row->rate ?? 0);
                    $proportional_amount = ($accepted_qty / $received_qty) * floatval($row->amount ?? 0);
                    $proportional_discount_amount = ($accepted_qty / $received_qty) * floatval($row->discount_amount ?? 0);
                    $proportional_net_amount = ($accepted_qty / $received_qty) * floatval($row->net_amount ?? 0);
                    
                    $stock['voucher_no']=$row->grn_no;
                    $stock['main_id']=$id;
                    $stock['master_id']=$row->id;
                    $stock['supplier_id']=$row->supplier_id;
                    $stock['voucher_date']=$row->grn_date;
                    $stock['voucher_type']=1;
                    $stock['sub_item_id']=$row->sub_item_id;
                    $stock['qty']=$accepted_qty; // Use accepted quantity instead of received
                    $stock['rate']=$rate;
                    $stock['amount_before_discount']=$proportional_amount;
                    $stock['discount_percent']=$row->discount_percent;
                    $stock['discount_amount']=$proportional_discount_amount;
                    $stock['amount']=$proportional_net_amount;
                    $stock['warehouse_id']=$row->warehouse_id;
                    $stock['description']=$row->description;
                    $stock['batch_code']=$row->batch_code;
                    $stock['status']=1;
                    $stock['created_date']=date('Y-m-d');
                    $stock['username']=Auth::user()->name;
                    DB::Connection('mysql2')->table('stock')->insert($stock);
                }
            endforeach;
            DB::Connection('mysql2')->table('goods_receipt_note')->where('id', $id)->update($data);
            DB::Connection('mysql2')->table('grn_data')->where('master_id', $id)->update($data);


            $goods_rece= DB::Connection('mysql2')->table('goods_receipt_note')->where('id',$id)->first();
            $supp_acc_id=CommonHelper::get_supplier_acc_id($goods_rece->supplier_id);


            $terms_of_payment= DB::Connection('mysql2')->table('supplier')->where('id',$goods_rece->supplier_id)->select('terms_of_payment')->value('terms_of_payment');
            $due_date= date('Y-m-d', strtotime($goods_rece->grn_date. ' + '.$terms_of_payment.' days'));
            $pv_no=CommonHelper::uniqe_no_for_purcahseVoucher(date('y'),date('m'));



            $data1=array
            (
                'pv_no'=>$pv_no,
                'pv_date'=>$goods_rece->grn_date,
                'grn_no'=>$goods_rece->grn_no,
                'grn_id'=>$id,
                'slip_no'=>$goods_rece->supplier_invoice_no,
                'bill_date'=>$goods_rece->grn_date,
                'due_date'=>$due_date,
                'supplier'=>$goods_rece->supplier_id,
                'description'=>$goods_rece->po_no.'---'.$goods_rece->po_date,
                'username'=>Auth::user()->name,
                'status'=>1,
                'pv_status'=>2,
                'date'=>date('Y-m-d'),
            );

            $master_id=DB::Connection('mysql2')->table('new_purchase_voucher')->insertGetId($data1);

            $poNo = $goods_rece->po_no;
            $getPurchaseRequestDetail = DB::Connection('mysql2')->table('purchase_request')->where('purchase_request_no', $poNo)->first();
            if($getPurchaseRequestDetail->advanced_paid_amount == 2){
                $getAdvancedPaidVouchers = DB::Connection('mysql2')->table('new_pv')->where('po_id',$getPurchaseRequestDetail->id)->get();
                foreach($getAdvancedPaidVouchers as $gapvRow){
                    $gapvData['new_purchase_voucher_id'] = $master_id;
                    $gapvData['pv_no'] = $pv_no;
                    $gapvData['pv_date'] = $goods_rece->grn_date;

                    DB::Connection('mysql2')->table('new_purchase_voucher_payment')->where('new_pv_no', $gapvRow->pv_no)->update($gapvData);
                }
            }

            $grn_data= DB::Connection('mysql2')->table('grn_data')->where('master_id',$id)->get();

            // Purchase voucher data - exclude returned items
            foreach($grn_data as $row):
                // Check if this item has a return quantity
                $return_qty = isset($return_items[$row->id]) ? $return_items[$row->id] : 0;
                $received_qty = floatval($row->purchase_recived_qty ?? 0);
                
                // Only add to purchase voucher if there's accepted quantity
                $accepted_qty = $received_qty - $return_qty;
                
                if ($accepted_qty > 0) {
                    // Calculate proportional amounts
                    $proportional_amount = ($accepted_qty / $received_qty) * floatval($row->amount ?? 0);
                    $proportional_discount_amount = ($accepted_qty / $received_qty) * floatval($row->discount_amount ?? 0);
                    $proportional_net_amount = ($accepted_qty / $received_qty) * floatval($row->net_amount ?? 0);
                    
                    $data2=array
                    (
                        'master_id'=>$master_id,
                        'pv_no'=>$pv_no,
                        'slip_no'=>'',
                        'grn_data_id'=>$row->id,
                        'category_id'=>97,
                        'sub_item'=>$row->sub_item_id,
                        'uom'=>0,
                        'qty'=>$accepted_qty, // Use accepted quantity
                        'rate'=>$row->rate,
                        'amount'=>$proportional_amount,
                        'discount_amount'=>$proportional_discount_amount,
                        'net_amount'=>$proportional_net_amount,
                        'staus'=>1,
                        'pv_status'=>2,
                        'username'=>Auth::user()->name,
                        'date'=>date('Y-m-d'),
                        'additional_exp'=>0
                    );
                    DB::Connection('mysql2')->table('new_purchase_voucher_data')->insertGetId($data2);
                }
            endforeach;


            $additional_exp= DB::Connection('mysql2')->table('addional_expense')->where('main_id',$id);

            if ($additional_exp->count()>0):

                $data3=array
                (
                    'master_id'=>$master_id,
                    'pv_no'=>$pv_no,
                    'slip_no'=>'',
                    'grn_data_id'=>$row->id,
                    'category_id'=>$additional_exp->first()->acc_id,
                    'sub_item'=>0,
                    'uom'=>0,
                    'qty'=>0,
                    'rate'=>0,
                    'amount'=>$additional_exp->first()->amount,
                    'discount_amount'=>0,
                    'net_amount'=>$additional_exp->first()->amount,
                    'staus'=>1,
                    'pv_status'=>2,
                    'username'=>Auth::user()->name,
                    'date'=>date('Y-m-d'),
                    'additional_exp'=>1
                );
                DB::Connection('mysql2')->table('new_purchase_voucher_data')->insertGetId($data3);

            endif;



            $new_pv_data= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id',$master_id)->get();
            $total_amount=0;
            foreach($new_pv_data as $row):

                $data4=array
                (
                    'master_id'=>$row->id,
                    'acc_id'=>$row->category_id,
                    'acc_code'=>FinanceHelper::getAccountCodeByAccId($row->category_id),
                    'paid_to'=>0,
                    'paid_to_type'=>0,
                    'particulars'=>$goods_rece->po_no.'---'.$goods_rece->po_date,
                    'opening_bal'=>0,
                    'debit_credit'=>1,
                    'amount'=>$row->net_amount,
                    'voucher_no'=>$pv_no,
                    'voucher_type'=>4,
                    'v_date'=>$goods_rece->grn_date,
                    'date'=>date('Y-m-d'),
                    'action'=>'insert',
                    'username'=>Auth::user()->name,
                    'status'=>1
                );
                DB::Connection('mysql2')->table('transactions')->insertGetId($data4);
                $total_amount+=$row->net_amount;
            endforeach;

            $data5=array
            (
                'master_id'=>$row->id,
                'acc_id'=>$supp_acc_id,
                'acc_code'=>FinanceHelper::getAccountCodeByAccId($supp_acc_id),
                'paid_to'=>0,
                'paid_to_type'=>0,
                'particulars'=>$goods_rece->po_no.'---'.$goods_rece->po_date,
                'opening_bal'=>0,
                'debit_credit'=>0,
                'amount'=>$total_amount,
                'voucher_no'=>$pv_no,
                'voucher_type'=>4,
                'v_date'=>$goods_rece->grn_date,
                'date'=>date('Y-m-d'),
                'action'=>'insert',
                'username'=>Auth::user()->name,
                'status'=>1
            );
            DB::Connection('mysql2')->table('transactions')->insertGetId($data5);



            // DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();

        }
    }

    function services(Request $request)
    {



        $value=$request->value;
        $id=$request->id;
        $value=str_replace('-','',$value);

        $resp= file_get_contents("http://taxchecker.applicationformz.com/index2.php?s=".$value);
        echo $resp;
        die;


//        ini_set('memory_limit', '3000M');
//
//        $csv_file = 'ActiveTaxpayerList.csv';
//
//        if (($handle = fopen($csv_file, "r")) !== FALSE) {
//            fgetcsv($handle);
//            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//                $num = count($data);
//                for ($c = 0; $c < $num; $c++) {
//                    $col[$c] = $data[$c];
//                }
//
//                $col1[] = $col[0];
//
//
//            }
//
//
//        }
//        $varb = "0";
//        if (in_array($_GET['value'], $col1)) {
//            $varb = "1";
//        } else {
//            $varb = "0";
//        }
//
//        $value = $request->value;
//        $id = $request->id;
//        $value = str_replace('-', '', $value);
//
//        $supplier = new Supplier();
//        $supplier = $supplier->SetConnection('mysql2');
//        $supplier = $supplier->find($id);
//        if ($varb == 0):
//            $supplier->filer = 1;
//
//        else:
//            $supplier->filer = 2;
//        endif;
//        $supplier->save();
//
//        echo $varb;




    }


    function  get_filer_status(Request $request)
    {
        $endpoint = "http://taxchecker.applicationformz.com/index2.php";
        $client = new Client();

        $value=$request->value;
        $id=$request->id;
        $value=str_replace('-','',$value);

        $response = $client->request('GET', $endpoint, ['query' => [
            's' => $id,
            'key2' => $value,
        ]]);

// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        echo   $content = $response->getBody();

// or when your server returns json
// $content = json_decode($response->getBody(), true);
    }

    function approve_purchase()
    {

        $id = $_GET['id'];


        $data3['pv_status'] = 2;
        $data3['approve_date'] = date('Y-m-d');
        $data3['approve_username'] = Auth::user()->name;
        DB::connection('mysql2')->table('purchase_voucher_through_grn')->where('id', $id)->update($data3);
        echo $id;

    }

    public function get_stock_region_wise(Request $request)
    {
        $category_id = $request->category;
        $location = $request->location;
        $category_name = CommonHelper::get_category_name($category_id);
        $sub_category = $request->sub_category_id;
        $sub_item = $request->sub_1;
        $item_des = $request->item_des;


        $sub_item_des_clause = '';
        $sub_item_des_heading = '';


        $category_join = '';
        $category_heading = '';
        $category_clause = '';
        $category_column = '';

        if ($category_id != 'select'):

            $category_join = 'inner join
            category c
            ON
            c.id=b.main_ic_id';
            $category_clause = 'and c.id = ' . $category_id;
            $category_heading = 'Category :' . $category_name;
            $category_column = ',c.id,c.main_ic';
        endif;

        if ($category_id != 'select'):

            $category_join = 'inner join
            category c
            ON
            c.id=b.main_ic_id';
            $category_clause = 'and c.id = ' . $category_id;
            $category_heading = 'Category :' . $category_name;
            $category_column = ',c.id,c.main_ic';
        endif;



        if ($item_des != ''):
            $sub_item_des_clause = "and a.description like '%" . $item_des . "%'";

            $sub_item_des_heading = 'Description :' . $item_des;
        endif;


        $sub_item_clause = '';
        $sub_item_heading = '';

        if ($sub_item != 'Select'):
            $sub_item = explode('@', $sub_item);
            $sub_item = $sub_item[0];
            $sub_item_clause = "and a.sub_item_id=" . $sub_item;
            $generic = CommonHelper::generic('subitem', array('id' => $sub_item), array('sub_ic'))->first();

            $sub_item_heading = 'Item :' . $generic->sub_ic;
        endif;



        $item_clause = '';
        $item_join = '';
        $item_heading = '';

        $location_caluse = '';
        $location_heading = '';
        if ($location != ''):
            $location_caluse = "and warehouse_id=" . $location;
            $generic = CommonHelper::generic('warehouse', array('id' => $location), array('name'))->first();
            $location_heading = 'Location :' . $generic->name;
        endif;



        if ($sub_category != 'Select'):
            $item_join = "inner join sub_category d on d.id=b.sub_category_id";
            $item_clause = "and d.id=" . $sub_category;
            $generic = CommonHelper::generic('sub_category', array('id' => $sub_category), array('sub_category_name'))->first();
            $item_heading = 'Sub Category :' . $generic->sub_category_name;
        endif;


        $category = DB::Connection('mysql2')->select('select a.* ' . $category_column . ',b.uom,b.main_ic_id, b.pack_size, b.primary_pack_type,b.hs_code_id, b.color,b.item_code, b.sub_category_id from stock a
            inner JOIN
            subitem b
            ON
            b.id=a.sub_item_id
            ' . $category_join . '
            ' . $item_join . '
            where b.status=1
            and a.status=1
           ' . $category_clause . '
            ' . $item_clause . '
            ' . $location_caluse . '
            ' . $sub_item_clause . '
             group by a.sub_item_id, a.warehouse_id');


        $quarintine = DB::Connection('mysql2')->table('grn_data as a')
            ->join('goods_receipt_note as b', 'a.master_id', '=', 'b.id')
            ->join('subitem as c', 'a.sub_item_id', '=', 'c.id')
            ->where('c.type', '!=', 2)
            ->where('b.status', 1)
            ->where('b.grn_status', 1);

        if ($category_id != 'select'):
            $quarintine = $quarintine->where('c.main_ic_id', $category_id);
        endif;

        if ($sub_item != 'Select'):
            $quarintine = $quarintine->where('c.id', $sub_item);
        endif;

        $quarintine = $quarintine
            ->select(
                'c.sub_ic',
                DB::Connection('mysql2')->raw('SUM(a.purchase_recived_qty) As qty'),
                'c.uom'
            )
            ->groupBy('c.id')
            ->get();

        $packaging_type = DB::Connection('mysql2')->table('packaging_type')->where('status', '=', 1)->get();
        $packaging_data = [];
        foreach ($packaging_type as $val) {
            $packaging_data[$val->id] = $val->type;
        }
        $uom = DB::table('uom')->where('status', '=', 1)->get();
        $uom_data = [];
        foreach ($uom as $val) {
            $uom_data[$val->id] = $val->uom_name;
        }

        return view('Store.AjaxPages.stock_report', compact('category', 'category_name', 'item_heading', 'location_heading', 'sub_item_heading', 'sub_item_des_heading', 'category_heading', 'quarintine', 'uom_data', 'packaging_data'));

    }

    public function getReportMultiItems(Request $request)
    {
        $ItemIds = $request->ItemIds;

        $sub_item_clause="and a.sub_item_id in(".$ItemIds.")";



        $category=  DB::Connection('mysql2')->select('select a.* from stock a
        inner JOIN
        subitem b
        ON
        b.id=a.sub_item_id

        where b.status=1
        and a.status=1
        '.$sub_item_clause.'
        group by a.sub_item_id, a.warehouse_id');

        return view('Store.AjaxPages.getReportMultiItems', compact('category'));
    }

    public function get_stock_region_wise_batch_wise(Request $request)
    {


        $batch_code = $request->batch_code;
        echo $item_des=$request->sub_1;
        $BatchClause = "";


        $category = DB::Connection('mysql2')->select('select * from stock where
        sub_item_id = '.$item_des.'
        and status=1
        group by batch_code,sub_item_id,warehouse_id');
        return view('Store.AjaxPages.stock_report_batch_wise', compact('category'));

    }

    public static function get_stock(Request $request)
    {
        $item=$request->subitemid;
        $region=$request->region;
        $grn_data =  DB::Connection('mysql2')->table('stock')->where('sub_item_id',$item)->where('status',1)->where('region_id',$region)->whereIn('voucher_type',[1,3])->sum('qty');
        $issuence =  DB::Connection('mysql2')->table('stock')->where('sub_item_id',$item)->where('region_id',$region)->where('status',1)->where('voucher_type',2)->sum('qty');

        //$grn_data=  DB::Connection('mysql2')->table('grn_data')->where('sub_item_id',$item)->where('region',$region)->sum('purchase_approved_qty');
        //$issuence=  DB::Connection('mysql2')->table('issuance_data')->where('sub_item_id',$item)->where('region',$region)->sum('qty');

        echo $grn_data-$issuence;
    }

    public static function get_uom(Request $request)
    {
        echo $Item = $request->item;
    }

    public static function deleteProductDetail(Request $request)
    {
        $id = $request->id;
        if($id != "")
        {
            $product = new Product();
            $product = $product->SetConnection('mysql2');
            $data['p_status'] = '0';
            $product->where('product_id', $id)->update($data);
        }

        $acc_id=DB::Connection('mysql2')->table('product')->where('product_id','=',$id)->select('acc_id')->first();
        $acc_id=$acc_id->acc_id;

        $data1['status']=0;
        $acc_id=DB::Connection('mysql2')->table('accounts')->where('id','=',$acc_id)->update($data1);
    }

    public  function UpdateDpdn(Request $request){

        $EditId = $request->id;
        $ClientJobId = $request->val;

        $data['client_job'] = $ClientJobId;
        DB::Connection('mysql2')->table('job_order')->where('job_order_id', $EditId)->update($data);
        echo $ClientJobId;
    }
    public  function UpdateBranchId(Request $request){

        $BranchId = $request->BranchId;
        $JobOrderId = $request->JobOrderId;

        $data['branch_id'] = $BranchId;
        DB::Connection('mysql2')->table('job_order')->where('job_order_id', $JobOrderId)->update($data);
        echo $JobOrderId;
    }




    public static function deleteCondition(Request $request)
    {
        $id = $request->id;
        if($id != "") {
            $Conditions = new Conditions();
            $Conditions = $Conditions->SetConnection('mysql2');
            $data['status'] = '0';
            $Conditions->where('condition_id', $id)->update($data);
        }
    }

    public static function deleteTypeList(Request $request)
    {
        $id = $request->id;
        if($id != "") {
            $Type = new Type();
            $Type = $Type->SetConnection('mysql2');
            $data['status'] = '0';
            $Type->where('type_id', $id)->update($data);
        }
    }
    public function get_job_order(Request $request)
    {
        $uom = new UOM;
        $uom = $uom::where('status', '=', '1')->get();

        $type=$request->type;
        $id=$request->id;

        if ($type==1):

            $master = new Quotation();
            $master = $master->SetConnection('mysql2');
            $master = $master->where('status',1)->where('id',$id)->first();


            $detail = new Quotation_Data();
            $detail = $detail->SetConnection('mysql2');
            $detail = $detail->where('status',1)->where('master_id',$id)->get();
        endif;
        $Branch = new Branch();
        $Branch= $Branch->SetConnection('mysql2');
        $Branch = $Branch->where('status',1)->get();

        return view('Purchase.AjaxPages.get_job_order',compact('master','type','id','detail','uom','Branch'));
    }

    public static function deleteClientList(Request $request)
    {
        $id = $request->id;
        if($id != "") {
            $Client = new Client();
            $Client = $Client->SetConnection('mysql2');
            $data['status'] = '0';
            $Client->where('id', $id)->update($data);
        }
    }

    public static function deleteProductTypeList(Request $request)
    {
        $id = $request->id;
        if($id != "") {
            $ProductType = new ProductType();
            $ProductType = $ProductType->SetConnection('mysql2');
            $data['status'] = '0';
            $ProductType->where('product_type_id', $id)->update($data);
        }
    }


    public static function deleteResourceAssignedList(Request $request)
    {
        $id = $request->id;
        if($id != "") {
            $ResourceAssigned = new ResourceAssigned();
            $ResourceAssigned = $ResourceAssigned->SetConnection('mysql2');
            $data['status'] = '0';
            $ResourceAssigned->where('id', $id)->update($data);
        }
    }

    public  static function getStockDataWithItemWise(Request $request)
    {
        $SubItemId = $request->SubItemId;
        $m = $request->m;

        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->get();
        return view('Store.AjaxPages.getStockDataWithItemWise',compact('Region','SubItemId','m'));
    }
    public  static function insertOrUpdateOpeningStock(Request $request)
    {


        $Command = $request->Command;
        $CategoryId = $request->CategoryId;
        $SubItemId = $request->SubItemId;
        $RegionId = $request->RegionId;
        $Rate = $request->Rate;
        $Qty = $request->Qty;
        if($Qty == ""){$Qty = 0;}else{$Qty = $request->Qty;}
        $Amount = $request->Amount;
        if($Amount == ""){$Amount = 0;}else{$Amount = $request->Amount;}

        $Stock = new Stock();
        $Stock = $Stock->SetConnection('mysql2');
        $Stock = $Stock->where('category_id',$CategoryId)->where('sub_item_id',$SubItemId)->where('region_id',$RegionId)->where('status',1)->where('opening',1)->first();
        //print_r($Stock);

        if(count($Stock) > 0)
        {
            $UpdateId = $Stock->id;
            $Stock = new Stock();
            $Stock = $Stock->SetConnection('mysql2');
            $UpdateData['qty'] = $Qty;
            $UpdateData['amount'] = $Amount;
            $UpdateData['voucher_date'] = "2020-07-01";
            $UpdateData['voucher_type'] = 1;
            $UpdateData['opening'] = 1;
            $UpdateData['status'] = 1;
            $UpdateData['created_date'] = date('Y-m-d');
            $UpdateData['username'] = Auth::user()->name;
            $Stock->where('id', $UpdateId)->update($UpdateData);
            $RateUpdate['rate'] = $Rate;
            $Subitem = new Subitem();
            $Subitem = $Subitem->SetConnection('mysql2');
            $Subitem->where('id', $SubItemId)->update($RateUpdate);
        }
        else
        {
            $Stock = new Stock();
            $Stock = $Stock->SetConnection('mysql2');
            $Stock->category_id = $CategoryId;
            $Stock->sub_item_id = $SubItemId;
            $Stock->region_id = $RegionId;
            $Stock->qty = $Qty;
            $Stock->amount = $Amount;
            $Stock->voucher_date = "2020-07-01";
            $Stock->voucher_type = 1;
            $Stock->opening = 1;
            $Stock->status = 1;
            $Stock->created_date = date('Y-m-d');
            $Stock->username = Auth::user()->name;
            $Stock->save();
            $RateUpdate['rate'] = $Rate;
            $Subitem = new Subitem();
            $Subitem = $Subitem->SetConnection('mysql2');
            $Subitem->where('id', $SubItemId)->update($RateUpdate);
        }

        echo $Command;
    }

    public function stockReturnDataFilter()
    {
        $FromDate = $_GET['FromDate'];
        $ToDate = $_GET['ToDate'];
        $IssuanceType = $_GET['IssuanceType'];
        $m = $_GET['m'];
        if($FromDate != "" && $ToDate!= "" && $IssuanceType == "all")
        {
            $stock_return = DB::Connection('mysql2')->table('stock_return')->where('status', 1)->whereBetween('issuance_date', [$FromDate, $ToDate])->get();
        }
        else
        {
            $stock_return = DB::Connection('mysql2')->table('stock_return')->where('status', 1)->where('issuance_type',$IssuanceType)->whereBetween('issuance_date', [$FromDate, $ToDate])->get();
        }

        return view('Purchase.AjaxPages.stockReturnDataFilter', compact('stock_return','m'));
    }

    public function getSubCategoryList(Request $request)
    {
        $category_id = $_GET['category']; //$request->category;
        $data = DB::Connection('mysql2')->table('sub_category as s')
            ->where('s.category_id', $category_id)
            ->where('s.status', 1)
            ->select('s.id', 's.sub_category_name')
            ->get();
        return response()->json($data);
    }

    public function getItemBySubCategory()
    {
        $sub_category_id = $_GET['sub_category']; //$request->category;
        $data = DB::Connection('mysql2')->table('subitem as a')
            ->join(env('DB_DATABASE') . '.uom as b', 'a.uom', '=', 'b.id')
            ->where('a.sub_category_id', $sub_category_id)
            ->where('a.status', 1)
            ->select('a.id', 'a.sub_ic', 'a.item_code', 'b.uom_name', 'a.sub_ic')
            ->get();
        return response()->json($data);
    }

    public function get_sub_category(Request $request)
    {
        $category_id = $_GET['category']; //$request->category;
        $data=DB::Connection('mysql2')->table('subitem as a')
        ->join(env('DB_DATABASE').'.uom as b','a.uom','=','b.id')
        ->leftJoin(env('DB_DATABASE').'.uom as c','a.uom2','=','c.id')
        ->where('a.main_ic_id',$category_id)
        ->where('a.status',1)
        ->select('a.id','a.sub_ic','a.item_code','b.uom_name','c.uom_name as uom_name2','a.sub_ic', 'a.pack_size', 'a.secondary_pack_size')
        ->get();
        return response()->json($data);
    }

    public function get_item_master(Request $request)
    {
        $category_id=$request->category;
        $sub_category_id=$request->sub_category;
        $data=DB::Connection('mysql2')->table('item_master')->where('category_id',$category_id)->where('sub_category_id',$sub_category_id)->select('id','item_master_code')->get();
        echo '<option value="">Select Item Master</option>';
        foreach ($data as $row):?>
            <option value="<?php echo $row->id ?>">
                <?php echo $row->item_master_code ?>
            </option>
        <?php endforeach;
    }

    
    public function get_sub_category_by_id(Request $request)
    {
        $category_id=$request->category;
        $data=DB::Connection('mysql2')->table('sub_category')->where('category_id',$category_id)->select('id','sub_category_name')->get();
        echo '<option value="">Select Sub Category </option>';
        foreach ($data as $row):?>
            <option value="<?php echo $row->id ?>">
                <?php echo $row->sub_category_name ?>
            </option>
        <?php endforeach;
    }
    public function getSubItemByCategory(Request $request)
    {
        $item_id = $request->item_id;

        if ($item_id) {
            $product = Subitem::where(['status'=>1,'id'=>$item_id])->first();
            // dd($item_id , $products);
            $html = '<option selected value="'.$product->id.'">'.$product->sub_ic.'</option>';
        }
        else{
            $html = '<option value="">Select</option>';
            $products = Subitem::where(['status'=>1,'sub_category_id'=>$request->category])->get();
            foreach($products as $product)
            {
                $html.= '<option value="'.$product->id.'">'.$product->sub_ic.'</option>';
            }
        }

        return  $html;

    }
    public function getSubItemByMainCategory(Request $request)
    {

        $html = '<option value="">Select</option>';

        $products = Subitem::where(['status'=>1,'main_ic_id'=>$request->category])->get();
        foreach($products as $product)
        {
            $html.= '<option value="'.$product->id.'">'.$product->sub_ic.'</option>';
        }

        return  $html;

    }

    public function get_sub_item_code(Request $request)
    {
        $category_id=$request->category;
        $sub_category_id=$request->sub_category;
        $item_master_id=$request->item_master_id;
        $MaxNo = DB::Connection('mysql2')->selectOne('SELECT max(`item_code`) as item_code  FROM `subitem` WHERE `main_ic_id` = '.$category_id.'
        AND sub_category_id = '.$sub_category_id.' AND item_master_id = '.$item_master_id.'')->item_code;
        $ItemMasterCode = DB::Connection('mysql2')->selectOne('SELECT item_master_code  FROM `item_master` WHERE `id` = '.$item_master_id.'')->item_master_code;

        $Condition = '';

        if($MaxNo !="")
        {
            $MaxNo = explode('-',$MaxNo);
            $MaxNo = $MaxNo[4];
        }

        $str = $MaxNo + 1;
        $str = sprintf("%'03d", $str);
        echo $ItemMasterCode.'-'.$str;
        die;




    }

    public function search(Request $request)
    {
        $param=$request->param;
        $m=$request->session()->get('run_company');
        $data=DB::Connection('mysql2')->table('subitem')
            ->where('status',1)
            ->where('sub_ic', 'like', '%' . $request->item_id . '%')
            ->orWhere('item_code', 'like', '%' . $request->item_id . '%')
            ->where('status',1)
            ->orderBy('item_master_code','ASC')->get()->take(20);
        return view('Purchase.AjaxPages.item_data', compact('data','m','param'));
    }
    public function get_data(Request $request)
    {
        $item_id=$request->item;

        $item_data=DB::Connection('mysql2')->table('subitem')->where('id',$item_id)->select('uom');

        if ($item_data->count()>0):

            $max_data = DB::Connection('mysql2')->table('grn_data')->where('status',1)->where('sub_item_id',$item_id);

            $stock = DB::Connection('mysql2')->table('stock')->where('status',1)->where('sub_item_id',$item_id)->where('voucher_type',1)->sum('qty');
            $uom_name=CommonHelper::get_uom_name($item_data->first()->uom);
            echo $uom_name.','.$max_data->max('purchase_approved_qty').','.$max_data->max('purchase_recived_qty').','.$stock;
        endif;
    }

    public function get_uom_name_by_item_id(Request $request)
    {
        $item_id=$request->ItemId;
        $item_data=DB::Connection('mysql2')->table('subitem')->where('id',$item_id)->select('uom');
        echo $uom_name=CommonHelper::get_uom_name($item_data->first()->uom);
    }

    public function get_batch_code(Request $request)
    {
        $item_id=$request->item;
        $location=$request->location_id;
        $data = DB::Connection('mysql2')->select('select batch_code from stock
                                where status = 1 and sub_item_id = '.$item_id.' and warehouse_id = '.$location.' group by warehouse_id,batch_code');

        echo '<option value="">Select Batch Code</option>';

        foreach($data as $row):

            echo '<option value="'.$row->batch_code.'">'.$row->batch_code.'</option>';

        endforeach;
    }

    public function checkDuplicateSubCategory(Request $request)
    {
        $SubCategoryName = $request->SubCategoryName;
        $CategoryId = $request->CategoryId;

        $Count = DB::Connection('mysql2')->selectOne('SELECT COUNT(sub_category_name) as data_count FROM sub_category
        WHERE category_id = '.$CategoryId.' AND sub_category_name collate latin1_swedish_ci = "'.$SubCategoryName.'"')->data_count;
        echo $Count;

    }

    public function getDupicate(Request $request)
    {

      return  DB::Connection('mysql2')->table('new_purchase_voucher')
        ->where('status',1)
        ->where('supplier',$request->supplier_id)
        ->where('slip_no',$request->input_slip_no)
        ->count();
    }
    public function get_po_status_data(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $PoStatus = $request->PoStatus;
        $m = $request->m;
        if($PoStatus == 1)
        {
        //     $Data =  DB::Connection('mysql2')->select('select a.purchase_request_no,a.purchase_request_date,b.warehouse_id,a.purchase_approve_qty,a.sub_item_id,sum(b.purchase_recived_qty) as purchase_recived_qty from purchase_request_data a
        //   LEFT JOIN grn_data b ON a.id=b.po_data_id
        //   where a.status=1
        //   and b.status=1
        //   and a.purchase_request_date BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"
        //   GROUP by a.id HAVING a.purchase_approve_qty > sum(b.purchase_recived_qty)');

          $Data = DB::connection('mysql2')->select('
                SELECT 
                    a.purchase_request_no,
                    a.purchase_request_date,
                    b.warehouse_id,
                    a.purchase_approve_qty,
                    a.sub_item_id,
                    IFNULL(SUM(b.purchase_recived_qty), 0) AS purchase_recived_qty
                FROM 
                    purchase_request_data a
                INNER JOIN
                    purchase_request pr
                    ON pr.id = a.master_id
                LEFT JOIN 
                    grn_data b 
                    ON a.id = b.po_data_id AND b.status = 1
                WHERE 
                    a.status = 1
                    AND pr.purchase_request_status != 4
                    AND a.purchase_request_date BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"
                GROUP BY 
                    a.id
                HAVING 
                    a.purchase_approve_qty > IFNULL(SUM(b.purchase_recived_qty), 0)
            ');
        }
        elseif($PoStatus == 2)
        {
            $Data =  DB::Connection('mysql2')->select('select a.purchase_request_no,a.purchase_request_date,b.warehouse_id,a.purchase_approve_qty,a.sub_item_id,sum(b.purchase_recived_qty) as purchase_recived_qty from purchase_request_data a
          inner JOIN grn_data b ON a.id=b.po_data_id
          where a.status=1
          and b.status=1
          and a.purchase_request_date BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"
          GROUP by a.id HAVING a.purchase_approve_qty <= sum(b.purchase_recived_qty)');
        }
        elseif($PoStatus == 3)
        {
            $Data =  DB::Connection('mysql2')->select('select a.purchase_request_no,a.purchase_request_date,a.sub_item_id,a.purchase_approve_qty,sum(a.purchase_approve_qty) as purchase_recived_qty from purchase_request_data a

          where a.status=1
          and a.purchase_request_date BETWEEN "'.$FromDate.'" AND "'.$ToDate.'"
          and purchase_request_status = 4
          GROUP by a.purchase_request_no');
        }

        return view('Purchase.AjaxPages.get_po_status_data_ajax', compact('Data','m','PoStatus'));


    }
    public function get_stock_location_wise(Request $request)
    {
        $warehouse = $request->warehouse;
        $item = $request->item;
        $bacth_code = $request->batch_code;
    
        // dd($request);
        if ($bacth_code == ''):
            $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->where('sub_item_id', $item)
                // ->where('warehouse_id', $warehouse)
                ->where('batch_code', '!=', '')
                ->where('batch_code', '!=', '0')
                // ->where('batch_code','is not',null)
                ->select('batch_code')
                ->groupBy('batch_code')
                ->get();
            if (count($in) > 0):

                ?>

                <option value="">Select</option>
                <?php foreach ($in as $row): ?>
                    <option value="<?php echo $row->batch_code ?>"><?php echo $row->batch_code ?></option><?php
                endforeach; ?>
                <?php
            endif;
        else:
            $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->whereIn('voucher_type', [1, 4, 6, 3, 10, 11])
                ->where('sub_item_id', $item)
                // ->where('warehouse_id', $warehouse)
                ->where('batch_code', $bacth_code)
                ->select('stock.*', DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                ->first();

            $out = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->whereIn('voucher_type', [2, 5, 9, 8])
                ->where('sub_item_id', $item)
                // ->where('warehouse_id', $warehouse)
                ->where('batch_code', $bacth_code)
                ->select('stock.*',DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                ->first();

            $qty = $in->qty - $out->qty;
            $amount = $in->amount;
            $rate = 0;
            if ($qty > 0):

                $rate = number_format($amount / $in->qty, 2, '.', '');
                echo number_format((float) $qty, 2, '.', '') . '/' . $rate;
            else:
                $qty = 0;
                $amount = 0;
                echo $qty . '/' . $rate;
            endif;
        endif;

    }

    public function get_stock_location_wise2(Request $request)
    {
        $warehouse = $request->warehouse;
        $item = $request->item;
        $bacth_code = $request->batch_code;
    
        // dd($request);
        if ($bacth_code == ''):
            $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->where('sub_item_id', $item)
                ->where('warehouse_id', $warehouse)
                ->where('batch_code', '!=', '')
                ->where('batch_code', '!=', '0')
                // ->where('batch_code','is not',null)
                ->select('batch_code')
                ->groupBy('batch_code')
                ->get();
            return $in;
        else:
            $in = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->whereIn('voucher_type', [1, 4, 6, 3, 10, 11])
                ->where('sub_item_id', $item)
                ->where('warehouse_id', $warehouse)
                ->where('batch_code', $bacth_code)
                ->select('stock.*', DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                ->first();

            $out = DB::Connection('mysql2')->table('stock')->where('status', 1)
                ->whereIn('voucher_type', [2, 5, 9, 8])
                ->where('sub_item_id', $item)
                ->where('warehouse_id', $warehouse)
                ->where('batch_code', $bacth_code)
                ->select('stock.*',DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
                ->first();

            $qty = $in->qty - $out->qty;
            $amount = $in->amount;
            $rate = 0;
            if ($qty > 0):

                $rate = number_format($amount / $in->qty, 2, '.', '');
                echo number_format((float) $qty, 2, '.', '') . '/' . $rate;
            else:
                $qty = 0;
                $amount = 0;
                echo $qty . '/' . $rate;
            endif;
        endif;

    }

    public  function get_data_opening(Request $request)
    {
        $item_id=$request->item;
        $year_wise_dataa=DB::Connection('mysql2')->table('year_wise_opening')->where('item_id',$item_id)->get();
        $stock=DB::Connection('mysql2')->table('stock')->where('sub_item_id',$item_id)->where('opening',1)->get()->where('amount','>',0);
        $yearwise_ope=DB::Connection('mysql2')->table('year_wise_opening')->where('item_id',$item_id)->get();
        return view('Store.AjaxPages.year_wise_data',compact('year_wise_dataa','item_id','stock','yearwise_ope'));
    }

    public function getDataAjaxSupplierWise(Request $request)
    {
        $SupplierId = $request->SupplierId;
        $m = $request->m;
        if($SupplierId == 'all')
        {
            $good_recipt_note= new GoodsReceiptNote();
            $good_recipt_note=$good_recipt_note->SetConnection('mysql2');
            $good_recipt_note=$good_recipt_note->where('status',1)->where('grn_status',2)->where('type','!=',3)->select('id','grn_no','grn_date','po_no','po_date','supplier_invoice_no'
                ,'sub_department_id','supplier_id','type','po_no')->orderBy('grn_date','ASC')->get();
        }
        else{
            $good_recipt_note= new GoodsReceiptNote();
            $good_recipt_note=$good_recipt_note->SetConnection('mysql2');
            $good_recipt_note=$good_recipt_note->where('status',1)->where('grn_status',2)->where('type','!=',3)->where('supplier_id',$SupplierId)->select('id','grn_no','grn_date','po_no','po_date','supplier_invoice_no'
                ,'sub_department_id','supplier_id','type','po_no')->orderBy('grn_date','ASC')->get();
        }
        return view('Purchase.AjaxPages.getDataAjaxSupplierWise',compact('good_recipt_note','m'));

    }

    public function getPoDetailDateWise(Request $request)
    {
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $m = $request->m;
        $PurchaseRequestData = DB::Connection('mysql2')->select('select a.sales_tax,a.sales_tax_amount,a.supplier_id,b.* from purchase_request a Inner join purchase_request_data b on b.master_id = a.id
        where a.status = 1
        and a.purchase_request_date between "'.$FromDate.'" and "'.$ToDate.'"');

        return view('Purchase.AjaxPages.getPoDetailDateWise',compact('PurchaseRequestData','m'));
    }

    public function delete_supp(Request $request)
    {
        $id = $request->id;

        $data['status']=0;

        DB::Connection('mysql2')->table('supplier')->where('id',$id)->update($data);

        $acc_id=  DB::Connection('mysql2')->table('supplier')->where('id',$id)->select('acc_id')->value('acc_id');

        if ($acc_id!=0):

            DB::Connection('mysql2')->table('accounts')->where('id',$acc_id)->update($data);
        endif;

        echo $id;

    }


    public  function vendor_outstanding_data(Request $request)
    {

        $from= $request->from;
        $to= $request->to;
        $vendor= $request->vendor;
        return view('Purchase.AjaxPages.vendor_outstanding_data',compact('from','to','vendor'));
    }
    public  function vendor_balance_ajax_data(Request $request)
    {

        $from= $request->from;
        $to= $request->to;
        $vendor= $request->vendor;
        return view('Purchase.AjaxPages.vendor_balance_ajax_data',compact('from','to','vendor'));
    }


    public function get_category_acc_id($id)
    {
         return   DB::Connection('mysql2')->table('subitem as a')
            ->join('category as b','a.main_ic_id','=','b.id')
            ->where('a.id',$id)
            ->select('b.acc_id')
            ->value('acc_id');
    }
    function qc_submit(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try
        {
            $id = $request->grn_id;
            $data['grn_status'] = 2;
            $data['approve_username'] = Auth::user()->name;


            $grn_data = $request->grn_data_id;

            foreach ($grn_data as $key => $row):
            $data1 = array
            (
               'qc_qty'=>$request->input('reject_qty')[$key] ?? null,
               'remarks'=>$request->input('remarks')[$key],

            );
                DB::Connection('mysql2')->table('grn_data')->where('id', $row)->update($data1);
            endforeach;


            $grn= DB::Connection('mysql2')->table('grn_data')
                ->join('goods_receipt_note', 'goods_receipt_note.id', '=', 'grn_data.master_id')
                ->join('subitem as c','c.id','grn_data.sub_item_id')
                ->where('grn_data.master_id',$id)
                ->select('grn_data.*', 'goods_receipt_note.supplier_id','goods_receipt_note.grn_date','goods_receipt_note.bill_date'
                ,'goods_receipt_note.po_no','goods_receipt_note.supplier_invoice_no',
                'goods_receipt_note.sub_department_id','grn_data.po_data_id','goods_receipt_note.p_type','c.type')
                ->get();

            // $po_detail=   CommonHelper::get_po($grn->first()->po_no);


            foreach($grn as $row):

                $status=1;
                if ($row->type==2):
                 $status = 1;
                endif;
                $stock['voucher_no']=$row->grn_no;
                $stock['main_id']=$id;
                $stock['master_id']=$row->id;
                $stock['supplier_id']=$row->supplier_id;
                $stock['voucher_date']=$row->grn_date;
                $stock['voucher_type']=1;
                $stock['sub_item_id']=$row->sub_item_id;
                if ($row->qc_qty>0):
                $qty = $row->purchase_recived_qty -	$row->qc_qty;
                else:
                 $qty = $row->purchase_recived_qty;
                endif;
                $amount =$row->rate * $qty;
                if ($row->discount_percent>0):
                    $discount_amount= ($amount / 100) * $row->discount_percent;
                else:
                    $discount_amount=0;
                endif;
                $net_amount = $amount -$discount_amount;

                $stock['qty']=$qty;
                $stock['rate']=$row->rate;
                $stock['amount_before_discount']=$amount;
                $stock['discount_percent']=$row->discount_percent;
                $stock['discount_amount']=$discount_amount ;
                $stock['amount']=$net_amount;
                $stock['warehouse_id']=$row->warehouse_id;
                $stock['description']=$row->description;
                $stock['batch_code']=$row->batch_code;
                $stock['status']=$status;
                $stock['created_date']=date('Y-m-d');
                $stock['username']=Auth::user()->name;
                DB::Connection('mysql2')->table('stock')->insert($stock);

                $po_data_id = $row->po_data_id;

             $subDepartmentId=$row->sub_department_id;
             $p_type=$row->p_type;
            endforeach;
          $grn_no = $row->grn_no;
          $goods_rece=  DB::Connection('mysql2')->table('goods_receipt_note')->where('id', $id);
          $goods_rece->update($data);
            DB::Connection('mysql2')->table('grn_data')->where('master_id', $id)->update($data);


        //  $t_data=   DB::Connection('mysql2')->table('stock as a')
        //            ->join('subitem as b','a.sub_item_id','=','b.id')
        //            ->join('category as c','c.id','=','b.main_ic_id')
        //           ->select('amount','sub_item_id','a.voucher_date','c.acc_id','a.supplier_id')
        //           ->where('voucher_no',$row->grn_no)
        //           ->whereIn('a.status',array(1,3))
        //           ->get();

        //     $total_amount=0;
        //     foreach($t_data as $row1):

        //         $data4=array
        //         (
        //             'master_id'=>$id,
        //             'acc_id'=>$row1->acc_id,
        //             'acc_code'=>FinanceHelper::getAccountCodeByAccId($row1->acc_id),
        //             'cost_center'=>$row1->sub_item_id,
        //             'particulars'=>$goods_rece->value('po_no'),
        //             'opening_bal'=>0,
        //             'debit_credit'=>1,
        //             'amount'=>$row1->amount,
        //             'voucher_no'=>$row->grn_no,
        //             'voucher_type'=>5,
        //             'v_date'=>$row1->voucher_date,
        //             'date'=>date('Y-m-d'),
        //             'action'=>'insert',
        //             'username'=>Auth::user()->name,
        //             'status'=>1
        //         );
        //         DB::Connection('mysql2')->table('transactions')->insertGetId($data4);
        //         $total_amount+=$row1->amount;
        //     endforeach;

        //     $control_account = ReuseableCode::get_control_account(1);
        //     $data5=array
        //     (
        //         'master_id'=>$id,
        //         'acc_id'=>$control_account,
        //         'acc_code'=>FinanceHelper::getAccountCodeByAccId($control_account),
        //         'cost_center'=>$row1->supplier_id,
        //         'particulars'=>$goods_rece->value('po_no'),
        //         'opening_bal'=>0,
        //         'debit_credit'=>0,
        //         'amount'=>$total_amount,
        //         'voucher_no'=>$row->grn_no,
        //         'voucher_type'=>5,
        //         'v_date'=>$row1->voucher_date,
        //         'date'=>date('Y-m-d'),
        //         'action'=>'insert',
        //         'username'=>Auth::user()->name,
        //         'status'=>1
        //     );
        //     DB::Connection('mysql2')->table('transactions')->insertGetId($data5);

        //     $pr_no = DB::Connection('mysql2')->table('purchase_request_data')->where('status',1)->where('id',$po_data_id)->value('demand_no');
        //     $voucher_no = $grn_no;
        //     $subject = 'Inspection Crated For '.$pr_no;
        //     NotificationHelper::send_email('Creation inspection QC','Create',$subDepartmentId,$voucher_no,$subject,$p_type);
            DB::Connection('mysql2')->commit();


          //  DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();
            print_r($goods_rece);
        }

        return redirect()->back()->with('message', 'Quotation Successfully Saved');
    }


}

