<?php
    $accType = Auth::user()->acc_type;
    $m = session()->get('run_company');
    $current_date = date('Y-m-d');
    $currentMonthStartDate = date('Y-m-01');
    $currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    <script src="{{ URL::asset('assets/select2/select2.full.min.js') }}"></script>
    <link href="{{ URL::asset('assets/select2/select2.css') }}" rel="stylesheet">

    <style type="text/css">
        a {cursor: pointer;}
    </style>
    <div class="well_N">
	    <div class="boking-wrp dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid">

                                    <h2 class="subHeadingLabelClass">View Store Challan List </h2>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <input type="hidden" name="functionName" id="functionName" value="stdc/filterMaterialRequestVoucherList" readonly="readonly" class="form-control" />
                        <input type="hidden" name="tbodyId" id="tbodyId" value="filterMaterialRequestVoucherList" readonly="readonly" class="form-control" />
                        <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                        <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                        <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
                        <input type="hidden" name="filterType" id="filterType" value="materialRequestList" readonly="readonly" class="form-control" />
                        
                        <div class="lineHeight">&nbsp;</div>
                        <div id="printMaterialRequestVoucherList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>From Date</label>
                                                    <input type="Date" name="fromDate" id="fromDate" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>To Date</label>
                                                    <input type="Date" name="toDate" id="toDate" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right printListBtn">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="destroyTable('storeChallanVoucherList'),filterData();" style="margin-top: 32px;" >Filter Data</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive scrollme">
                                                        <table class="userlittab table table-bordered customTable" id="storeChallanVoucherList">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">S.C.No.</th>
                                                                    <th class="text-center">S.C.Date</th>
                                                                    <th class="text-center">M.R.No.</th>
                                                                    <th class="text-center">M.R.Date</th>
                                                                    <th class="text-center">Job Card No</th>
                                                                    <th class="text-center">Machine No</th>
                                                                    <th class="text-center">Remarks</th>
                                                                    <th class="text-center">S.C.Status</th>
                                                                    <th class="text-center hidden-print">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="filterStoreChallanVoucherList"></tbody>
                                                        </table>
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
    <script>
        var baseUrl = $('#baseUrl').val();
        function filterData(){
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#storeChallanVoucherList').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "<?php echo url('/')?>/store/viewStoreChallanList",
                    "type": "GET",
                    "data": {
                        fromDate: fromDate, 
                        toDate: toDate
                    }
                },
                columns: [                
                    {data: 'DT_RowIndex', searchable: false, orderable: false,class: 'text-center'},
                    {data: 'store_challan_no', name: 'store_challan_no',class: 'text-center'},
                    {data: 'store_challan_date', name: 'store_challan_date',class: 'text-center'},
                    {data: 'material_request_no', name: 'material_request_no',class: 'text-center'},
                    {data: 'material_request_date', name: 'material_request_date',class: 'text-center'},
                    {data: 'job_card_no', name:'job_card_no',class: 'text-center'},
                    {data: 'machine_no', name:'machine_no',class: 'text-center'},
                    {data: 'description', name: 'description'},
                    {data: 'store_challan_status', name: 'store_challan_status',class: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
                ]
            });
        }
        $(function () {
            filterData();
            $(document).on('keyup', '#selectAFilterValue', function(e){
                if(e.keyCode == 13)
                {                    
                    viewAdvanceSearchFilterAgainstPurchaseOrder();
                    
                }
            });
        });
        
        function updateRecordLimitMaterialRequestList(paramOne,paramTwo){
			$('#startRecordNo').val(paramOne);
			$('#endRecordNo').val(paramTwo);
			viewRangeWiseDataFilter();
		}
        function optionEnableAndDisableAdvanceFilterField(){
            console.log('optionEnableAndDisableAdvanceFilterField');
				$('#selectAFilterValueDiv').html();
				var selectAFilterType = $('#selectAFilterType').val();
				if(selectAFilterType == 'material_request_no'){
					var field = '<label>M.R. No</label><input type="text" name="selectAFilterValue" id="selectAFilterValue" value="" placeholder="M.R. No" class="form-control" />';
				}else if(selectAFilterType == 'material_request_date'){
					var field = '<label>M.R. Date</label><input type="date" name="selectAFilterValue" id="selectAFilterValue" value="<?php echo date('Y-m-d')?>" placeholder="M.R. Date" class="form-control" />';
				}else if(selectAFilterType == 'username'){
					var field = '<label>Username</label><input type="text" name="selectAFilterValue" id="selectAFilterValue" value="" placeholder="username" class="form-control" />';
				}
				$('#selectAFilterValueDiv').html(field);
				// $("select").select2();
			}
			optionEnableAndDisableAdvanceFilterField();
			
			function viewAdvanceSearchFilterAgainstPurchaseOrder(){
				var selectAFilterValue = $('#selectAFilterValue').val();
				var selectAFilterType  = $('#selectAFilterType').val();
				if(selectAFilterValue == ''){
					alert('Something Wrong! Please Select or Fill Input Field and Filter Data.');
					return false;
				}else{
					data = selectAFilterType+'<*>'+selectAFilterValue;
					showDetailModelOneParamerter('stdc/viewAdvanceSearchFilterAgainstMaterialRequest',data,'View Advance Search Filter Data Against Purchase Order Detail');
				}
			}
    </script>
    {{-- <script src="{{ URL::asset('assets/custom/js/customStoreFunction.js') }}"></script> --}}
@endsection