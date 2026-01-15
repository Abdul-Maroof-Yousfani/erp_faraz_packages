<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Goods Forward Order</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <input type="hidden" name="functionName" id="functionName" value="pdc/filterApproveDemandListandCreateGoodsForwardOrder" readonly="readonly" class="form-control" />
                            <input type="hidden" name="divId" id="divId" value="filterApproveDemandListandCreateGoodsForwardOrder" readonly="readonly" class="form-control" />
                            <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="2" readonly="readonly" class="form-control" />
                            <input type="hidden" name="parentCode" id="parentCode" value="<?php echo $_GET['parentCode'];?>" readonly="readonly" class="form-control" />

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>Branches</label>
                                    <input type="hidden" readonly name="selectBranchId" id="selectBranchId" class="form-control" value="">
                                    <input list="selectBranch" name="selectBranch" id="selectBranchTwo" class="form-control">
                                    <?php echo CommonHelper::branchSelectList();?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>From Date :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>To Date :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                    <input type="button" value="Search" class="btn btn-sm btn-danger" onclick="viewDataFilterTwoParameter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="filterApproveDemandListandCreateGoodsForwardOrder"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection