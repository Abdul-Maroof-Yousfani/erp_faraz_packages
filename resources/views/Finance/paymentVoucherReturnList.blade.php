<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">View  Payment Return List</span>
                                    </div>
                                    {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">--}}
                                        {{--<button class="btn btn-sm btn-info" onclick="printView('PrintPanel','','1')" style="">--}}
                                            {{--<span class="glyphicon glyphicon-print"> Print</span>--}}
                                        {{--</button>--}}
                                        {{--< ?php if($export == true):?>--}}
                                        {{--<button class="btn btn-sm btn-warning" onclick="exportView('TableExportToCsv','','1')" style="">--}}
                                            {{--<span class="glyphicon glyphicon-print"> Export to CSV</span>--}}
                                        {{--</button>--}}
                                        {{--< ?php endif;?>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            {{--<div class="row">--}}

                                {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                                    {{--<label>From Date</label>--}}
                                    {{--<input type="Date" name="FromDate" id="FromDate" min="< ?php echo $AccYearFrom?>" max="< ?php echo $AccYearTo;?>" value="< ?php echo $currentMonthStartDate;?>" class="form-control" />--}}
                                {{--</div>--}}

                                {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                                    {{--<label>To Date</label>--}}
                                    {{--<input type="Date" name="ToDate" id="ToDate" min="< ?php echo $AccYearFrom?>" max="< ?php echo $AccYearTo;?>" value="< ?php echo $currentMonthEndDate;?>" class="form-control" />--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
                                    {{--<label>Account Head</label>--}}
                                    {{--<select name="AccountId" id="AccountId" class="form-control select2">--}}
                                        {{--<option value="">Select Account</option>--}}
                                        {{--< ?php foreach($accounts as $Fil):?>--}}
                                        {{--<option value="< ?php echo $Fil->id?>">< ?php echo $Fil->code.'=='.$Fil->name?></option>--}}
                                        {{--< ?php endforeach;?>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                                    {{--<label>Voucher Status</label>--}}
                                    {{--<select name="VoucherStatus" id="VoucherStatus" class="form-control">--}}
                                        {{--<option value="">All</option>--}}
                                        {{--<option value="1">Pending</option>--}}
                                        {{--<option value="2">Approved</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">--}}
                                    {{--<input type="button" value="View Range Wise Data Filter" class="btn btn-sm btn-danger" onclick="GetOutstandingpvsDateAndAccontWise();" style="margin-top: 32px;" />--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body" id="PrintPanel">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 20px; font-style: oblique; display: none;" id="ShowTitle">
                                                        <b>Cash Payment Voucher List From :<span id="FromShow" style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span> Between To <span style="color: red" id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span> </b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="table table-bordered sf-table-list" id="TableExportToCsv">
                                                                <thead>

                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">P.V. No.</th>
                                                                <th class="text-center">P.V. Date</th>
                                                                <th class="text-center">Cheque No</th>
                                                                <th class="text-center">Debit/Credit</th>

                                                                <th class="text-center">Return Detail</th>

                                                                <th class="text-center hidden-print">Action</th>

                                                                </thead>
                                                                <tbody id="data">
                                                                <?php
                                                                $counter = 1;
                                                                $makeTotalAmount = 0;

                                                                foreach ($pvs as $row1) {
                                                                ?>
                                                                <tr>

                                                                    <td class="text-center"><?php echo $counter++;?></td>
                                                                    <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
                                                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
                                                                    <td class="text-center"><?php if ($row1->payment_type==1): echo strtoupper($row1->cheque_no); else: echo  'Cash'; endif?></td>
                                                                    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
                                                                    <td class="text-center">
                                                                        <?php $ReturnDetail = CommonHelper::get_single_row('payment_return','master_id',$row1->id);
                                                                        echo $ReturnDetail->description;
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center hidden-print">
                                                                        <?php //if($view == true):?>
                                                                        <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetailInDetail','<?php echo $row1->id;?>','View Cash P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"> View</a>
                                                                        <?php //endif;?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <th colspan="8" class="text-center">xxxxx</th>
                                                                </tr>
                                                                </tbody>
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
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
        });

        function DeletePvActivity(pv_id,pv_no,pv_date,pv_amount)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this Voucher...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeletePVoucherActivity',
                    type: "GET",
                    data: {
                        pv_id:pv_id,
                        pv_no:pv_no,
                        pv_date:pv_date,
                        pv_amount:pv_amount
                    },
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr"+pv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }
        function VoucherReturn(pv_id,pv_no,pv_date,pv_amount,cheque_no)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to return this payment...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/payment_return',
                    type: "GET",
                    data: {
                        pv_id:pv_id,
                        pv_no:pv_no,
                        pv_date:pv_date,
                        pv_amount:pv_amount,
                        cheque_no:cheque_no
                    },
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Return');
                        $(".tr"+pv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }
    </script>

    <script>

        function checkUncheck(chkbox,rowid){

            if ($('#'+chkbox).is(':checked'))
            {
                $('#'+chkbox).prop('checked',false);
                $('#'+rowid).removeClass("bg-info");
            } else {
                $('#'+chkbox).prop('checked',true);
                $('#'+rowid).addClass("bg-info");
            }
            var Len = $('input[name="checkbox[]"]:checked').length;
            if(Len>0)
            {$('#BtnApproved').prop('disabled',false);}
            else{$('#BtnApproved').prop('disabled',true);}


        }

        function GetOutstandingpvsDateAndAccontWise()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var VoucherStatus = $('#VoucherStatus').val();

            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/getOutstandingpvsDateAndAccontWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,AccountId:AccountId,VoucherStatus:VoucherStatus,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }
    </script>
@endsection
