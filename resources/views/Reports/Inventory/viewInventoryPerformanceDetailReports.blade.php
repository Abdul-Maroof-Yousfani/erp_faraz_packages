<?php
use App\Helpers\CommonHelper;
$current_date = date('Y-m-d');
//$current_month = date('m');
?>
@extends('layouts.default')

@section('content')
    <input type="hidden" name="selectedBranch" id="selectedBranch" value="0">
    <div class="well">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="well">
                            <?php echo CommonHelper::reportBranchAndRangeFilterSection('showBranchInventoryList');?>
                            <div style="line-height: 8px;">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Sub Item</label>
                                    <input type="hidden" readonly name="selectSubItemId" id="selectSubItemId" class="form-control" value="">
                                    <input list="selectSubItem" name="selectSubItem" id="selectSubItemTwo" class="form-control clearable">
                                    <div id="showBranchInventoryList">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <?php echo CommonHelper::reportDateMonthAndYearFilterSection('dateWiseFilterViewInventoryPerformanceDetail','monthWiseFilterViewInventoryPerformanceDetail','yearWiseFilterViewInventoryPerformanceDetail');?>
            </div>
        </div>
    </div>
    <script>
        adminRangeFilter();
        window.onload = function () {
            document.getElementById('selectSubItemTwo').disabled=true;
        }
        function showBranchInventoryList(id,value) {
            $('#selectedBranch').val(value);
            if(value == 0) {
                document.getElementById('selectSubItemTwo').disabled=true;
            }else {
                document.getElementById('selectSubItemTwo').disabled = false;
                $('#selectSubItemId').val('');
                $('#selectSubItemTwo').val('');
                $.ajax({
                    url: '<?php echo url('/')?>/rdc/inventorySelectList',
                    type: "GET",
                    data: {m:value},
                    success:function(data) {
                        $('#showBranchInventoryList').html(data);
                    }
                });
            }
        }
    </script>
    <script src="{{ URL::asset('assets/custom/js/customReportsFunction.js') }}"></script>
@endsection