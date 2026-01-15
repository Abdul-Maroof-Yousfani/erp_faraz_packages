<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use Session;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Subitem;
use App\Models\UOM;
use App\Models\Demand;
use App\Models\DemandData;
use App\Models\StoreChallan;
use App\Models\StoreChallanData;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\StoreChallanReturn;
use App\Models\StoreChallanReturnData;
class StoreDataCallController extends Controller
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

    public function filterDemandVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        $counter = 1;
        CommonHelper::companyDatabaseConnection($m);
        $demandDetail = Demand::whereBetween('demand_date',[$fromDate,$toDate])->where('demand_status','=','2')->get();
        CommonHelper::reconnectMasterDatabase();
        ?>
        <tr>
            <td colspan="8">
                <?php if(Session::has('dataDelete')){?>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">&nbsp;</div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="alert alert-danger"><span class="glyphicon glyphicon-ok"></span><em><?php echo session('dataDelete')?></em>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <?php if(Session::has('dataRepost')){?>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">&nbsp;</div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="alert alert-warning"><span class="glyphicon glyphicon-ok"></span><em><?php echo session('dataRepost')?></em>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <?php if(Session::has('dataApprove')){?>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">&nbsp;</div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em><?php echo session('dataApprove')?></em>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <?php if(Session::has('dataEdit')){?>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">&nbsp;</div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="alert alert-info"><span class="glyphicon glyphicon-ok"></span><em><?php echo session('dataEdit')?></em>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </td>
        </tr>
        <?php
        foreach ($demandDetail as $row){
            ?>
            <tr>
                <td class="text-center"><?php echo $counter++;?></td>
                <td class="text-center"><?php echo $row->demand_no?></td>
                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->demand_date);?></td>
                <td class="text-center"><?php echo $row->slip_no?></td>
                <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                <td class="text-center"><?php echo StoreHelper::checkVoucherStatus($row->demand_status,$row->status);?></td>
                <td class="text-center">
                    <a onclick="showDetailModelOneParamerter('pdc/viewDemandVoucherDetail','<?php echo $row['demand_no'];?>','View Demand Detail')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
            </tr>
            <?php
        }
        ?>
        <script type="text/javascript">
            setTimeout(function() {
                $('.alert-danger').fadeOut('fast');
            }, 500);

            setTimeout(function() {
                $('.alert-warning').fadeOut('fast');
            }, 500);

            setTimeout(function() {
                $('.alert-success').fadeOut('fast');
            }, 500);

            setTimeout(function() {
                $('.alert-info').fadeOut('fast');
            }, 500);
        </script>
        <?php
    }

    public function viewDemandVoucherDetail(){
        return view('Purchase.AjaxPages.viewDemandVoucherDetail');
    }

    public function filterApproveDemandListandCreateStoreChallan(){
        return view('Store.AjaxPages.filterApproveDemandListandCreateStoreChallan');
    }

    public function createStoreChallanDetailForm(Request $request){
        return view('Store.createStoreChallanDetailForm',compact('request'));
    }

    public function filterStoreChallanVoucherList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $storeChallanDetail = StoreChallan::whereBetween('store_challan_date',[$fromDate,$toDate])->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.filterStoreChallanVoucherList',compact('storeChallanDetail'));
    }

    public function viewStoreChallanVoucherDetail(){
        return view('Store.AjaxPages.viewStoreChallanVoucherDetail');
    }

    public function filterApproveDemandListandCreatePurchaseRequest(){
        return view('Store.AjaxPages.filterApproveDemandListandCreatePurchaseRequest');
    }

    public function createPurchaseRequestDetailForm(Request $request){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $supplierList = Supplier::select('name','id','acc_id')->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.createPurchaseRequestDetailForm',compact('request','supplierList'));
    }

    public function filterPurchaseRequestVoucherList(){

        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);

        $purchaseRequestDetail = PurchaseRequest::whereBetween('purchase_request_date',[$fromDate,$toDate])->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.AjaxPages.filterPurchaseRequestVoucherList',compact('purchaseRequestDetail'));
    }

    public function viewPurchaseRequestVoucherDetail(){
        return view('Store.AjaxPages.viewPurchaseRequestVoucherDetail');
    }

    public function filterApproveStoreChallanandCreateStoreChallanReturn(){
        return view('Store.AjaxPages.filterApproveStoreChallanandCreateStoreChallanReturn');
    }

    public function createStoreChallanReturnDetailForm(Request $request){
        return view('Store.createStoreChallanReturnDetailForm',compact('request'));
    }

    public function filterStoreChallanReturnList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($m);
        $storeChallanReturnDetail = StoreChallanReturn::whereBetween('store_challan_return_date',[$fromDate,$toDate])->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        echo json_encode(array('data' => $storeChallanReturnDetail));
        //return view('Store.AjaxPages.filterStoreChallanReturnList',compact('storeChallanReturnDetail'));
    }

}