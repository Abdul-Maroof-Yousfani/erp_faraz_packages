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
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Store.'.$accType.'storeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Get Opening Stock Data</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <input type="hidden" name="functionName" id="functionName" value="stdc/filterStoreChallanReturnList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="tbodyId" id="tbodyId" value="filterStoreChallanReturnList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
                            <input type="hidden" name="filterType" id="filterType" value="1" readonly="readonly" class="form-control" />
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label>Sub Itme</label>
                                    <select name="SubItemId" id="SubItemId" class="form-control select2">
                                        <option value="">Select Item</option>
                                        <?php foreach($Subitem as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->id.'----'.$Fil->sub_ic?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span id="SubItemError"></span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="getStockDataWithItemWise()" style="margin-top: 32px;">Show</button>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printStoreChallanReturnList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                                                        <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($current_date);?></label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                                                 style="font-size: 30px !important; font-style: inherit;
font-family: -webkit-body; font-weight: bold;">
                                                                <?php echo CommonHelper::getCompanyName($m);?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                                                        <?php $nameOfDay = date('l', strtotime($current_date)); ?>
                                                        <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                                                    </div>
                                                </div>
                                                <div style="line-height:5px;">&nbsp;</div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive" id="GetData">

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
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/customStoreFunction.js') }}"></script>
    <script !src="">
        $(document).ready(function(){
            $('.select2').select2();
        });
        function getStockDataWithItemWise()
        {
            var SubItemId = $('#SubItemId').val();
            var m = '<?php echo $_GET['m']?>';
            if(SubItemId !="") {
                $('#SubItemError').html('');
                $('#GetData').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/pdc/getStockDataWithItemWise',
                    method: 'GET',
                    data: {SubItemId: SubItemId, m: m},
                    error: function () {
                        alert('error');
                    },
                    success: function (response) {
                        $('#GetData').html(response);
                    }
                });
            }
            else
            {
                $('#SubItemError').html('<p class="text-danger">Please Select Sub Item.</p>');
            }
        }

    </script>
@endsection
