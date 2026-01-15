<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Helpers\CommonHelper;
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Employee Transfers </span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        @if(in_array('print', $operation_rights))
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveApplicationRequestList','','1');?>
                        @endif
                        @if(in_array('export', $operation_rights))
                            <?php echo CommonHelper::displayExportButton('LeaveApplicationRequestList','','1')?>
                        @endif
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="show_transfer"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            searchTransferEmployee()
        });

        function searchTransferEmployee(){
            var company_id = '<?=$m?>';
            var rights_url = 'hr/viewEmployeeTransfers';
            var data = {'company_id': company_id, rights_url: rights_url};
            var url = '<?php echo url('/')?>/hdc/viewEmployeeTransferDetail';
            $.get(url, data, function (result) {
                $('.show_transfer').html(result);
            });
        }


    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection