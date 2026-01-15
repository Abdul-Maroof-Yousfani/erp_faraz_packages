<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');

use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Ot & Fuel Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id">
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Category:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select readonly="readonly" class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                                                    <option value="">Select Category</option>
                                                    @foreach($employee_category as $key2 => $y2)
                                                        <option @if($y2->id == 3) selected @endif value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Month</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <input type="button" class="btn btn-sm btn-primary" id="viewS2bReport" onclick="viewReport()" value="Search" style="margin-top: 32px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loader"></div>
                                <div class="S2BSection" id="PrintEmployeeAttendanceList"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewReport(){
            $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var month_year = $('#month_year').val();
            var region_id = $('#region_id').val();
            var emp_category_id = $('#emp_category_id').val();
            var m = '<?php echo Input::get('m'); ?>';
            jqueryValidationCustom();
            if(validate == 0){
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewOtAndFuelReport',
                    type: "GET",
                    data: { emp_category_id:emp_category_id,region_id:region_id,month_year:month_year,m:m},
                    success:function(data) {
                        $('#loader').html('');
                        $('.S2BSection').empty();
                        $('.S2BSection').append('<div class="">'+data+'</div>');
                    }
                });
            }else{
                return false;
            }
        }

        $(document).ready(function(){
            $('#emp_category_id').select2();
            $('#region_id').select2();
        });

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection