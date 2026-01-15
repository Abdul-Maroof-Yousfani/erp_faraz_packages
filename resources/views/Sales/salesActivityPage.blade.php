<?php
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
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Audit Trial Report</span>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>From Date</label>
                                                            <input type="Date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>To Date</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Voucher No</label>
                                                            <input type="text" name="VoucherNo" id="VoucherNo" max="<?php echo $current_date;?>" value="" class="form-control" placeholder="Voucher No" />
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Show" class="btn btn-sm btn-primary" onclick="AuditTrialReport();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AuditTrialReport"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });


        function AuditTrialReport() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var VoucherNo = $('#VoucherNo').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#AuditTrialReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/sales/salesActivityAjax',
                method:'GET',
                data:{FromDate:FromDate,ToDate:ToDate,m:m,VoucherNo:VoucherNo},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#AuditTrialReport').html(response);
//                    setTimeout(function(){
//
//                    },1000);
                }
            });
        }
    </script>
@endsection