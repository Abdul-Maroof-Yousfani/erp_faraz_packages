<?php
Auth::user()->id;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;
?>
@extends('layouts.default')

@section('content')

    <style>
        @media print {
            a[href]:after {
                content: none !important;
            }
        }

        tr:hover {
            background-color: yellow;
        }
    </style>
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Supplier Wise Summary Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>From Date</label>
                                                    <input type="Date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label>To Date</label>
                                                    <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <input type="button" value="Show" class="btn btn-sm btn-primary" onclick="vendor_summery();" style="margin-top: 32px;" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="row">--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>--}}
                                        {{--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">--}}
                                            {{--<div class="table-responsive">--}}
                                                {{--<table  id="myTable" class="table table-bordered sf-table-list">--}}
                                                    {{--<thead>--}}
                                                    {{--<th style="" class="text-center">S.No</th>--}}
                                                    {{--<th class="text-center">Acc-Id</th>--}}

                                                    {{--<th class="text-left">Supplier Name</th>--}}
                                                    {{--<th class="text-left">Purchase Amount</th>--}}
                                                    {{--<th class="text-left">Paid Amount</th>--}}
                                                    {{--<th class="text-center">Remaining</th>--}}
                                                    {{--</thead>--}}
                                                    {{--<tbody>--}}
                                                    <?php
                                                            /*
                                                    $Counter =1;
                                                    foreach($Supplier as $Fil):
                                                    $amount_data=CommonHelper::get_debit_credit_amount($Fil->acc_code,1,0,$AccYearFrom,$AccYearTo);
                                                    $amount_data=explode(',',$amount_data);

                                                   $debit=$amount_data[0];
                                                   $credit=$amount_data[1];
                                                    $net_amount=$credit-$debit;
                                                    $ledger_amount=$net_amount;
                                                            if ($net_amount<0):
                                                            $net_amount=$net_amount*-1;
                                                            $net_amount=number_format($net_amount,2);
                                                            $net_amount='('.$net_amount.')';
                                                            else:
                                                                $net_amount=number_format($net_amount,2);
                                                            endif;

                                                     if ($ledger_amount!=0):

                                                        */
                                                    ?>
                                                    {{--<tr>--}}
                                                        {{--<td class="text-center"><  ?php echo $Counter++;?></td>--}}
                                                        {{--<td>< ?php echo $Fil->acc_id?></td>--}}

                                                        {{--<td class="text-left"><b style="font-size: large;font-weight: bolder"> <a target="_blank" href="< ?php echo URL('finance/viewLedgerReport?AccId='.$Fil->acc_id.'&&FromDate='.$AccYearFrom.'&&ToDate='.$AccYearTo.'&&m='.$m)?>">< ?php echo $Fil->name?></a></td>--}}
                                                        {{--<td class="text-right">{{number_format($credit,2)}}</td>--}}
                                                        {{--<td class="text-right">{{number_format($debit,2)}}</td>--}}
                                                        {{--<td class="text-right">--}}
                                                            {{--< ?php--}}

                                                        {{--// echo  $net_amount;--}}
                                                            {{--?>--}}
                                                        {{--</td>--}}

                                                    {{--</tr>--}}
                                                    {{--< ?php //endif; endforeach;?>--}}
                                                    {{--</tbody>--}}
                                                {{--</table>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="vendor_summery_append"></div>--}}
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                            </div>
                            <div id="vendor_summery_append"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script !src="">
    function vendor_summery() {
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var m = '<?php echo $_GET['m'];?>';
        $('#vendor_summery_append').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $.ajax({
            url: '<?php echo url('/');?>/fdc/vendor_summery_two',
            method:'GET',
            data:{FromDate:FromDate,ToDate:ToDate,m:m},
            error: function(){
                alert('error');
            },
            success: function(response)
            {

                $('#vendor_summery_append').html(response);

            }
        });
    }

</script>