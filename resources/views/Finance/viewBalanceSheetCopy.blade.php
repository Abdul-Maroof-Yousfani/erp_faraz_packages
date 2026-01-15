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
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$company_id)->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;
?>

@extends('layouts.default')
@section('content')

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                        <input type="button" class="btn btn-xs btn-danger" onclick="tableToExcel('header-fixed', 'Purchase Request')" value="Export to Excel">
                        <?php // echo CommonHelper::displayExportButton('trial_bal','','1')?>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Balance Sheet</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <input name="from_date" id="from_date" max="<?php echo $AccYearTo ?>" required="required"  class="form-control" type="date" min="<?php echo $AccYearFrom?>" value="<?php echo $currentMonthStartDate?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <input name="to_date" id="to_date" class="form-control" type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>"  required="required" value="<?php echo $currentMonthEndDate?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <select name="" id="type" class="form-control">
                                                <option value="0">Summary</option>
                                                <option value="1">Detaild</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <button onclick="Generate()" disabled >Generate Report</button>
                                        </div>

                                    </div>
                                    <h5 class="text-center topp">Balance Sheet</h5>
                                    <b>	<h5 class="text-center"><?php echo  "FROM".' '.date_format(date_create($AccYearFrom),'d - M -Y').' '."TO".' '.date_format(date_create($AccYearTo),'y- M -Y') ?></h5></b>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <table id="header-fixed" class="table table-bordered sf-table-th sf-table-list" style="background:#FFF;">
                                                        <thead>
                                                        <tr>
                                                            <th  class="text-center">Acc_code</th>
                                                            <th  class="text-center">Acc. Name</th>
                                                            <th  class="text-center ">Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center"></td>
                                                                <td class=""></td>
                                                                <td class="text-center"></td>
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

    <script>
        function Generate()
        {
            $('#trial_bal').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            from_date = $("#from_date").val();
            to_date = $("#to_date").val();
            var type=$('#type').val();
            if(from_date!="" && to_date!="") {
                m = '<?= $_GET['m']; ?>';
                $.ajax({
                    url: '<?php echo url('/');?>/fdc/trialBalanceSheet',
                    type: 'GET',
                    data: {from_date: from_date, to_date: to_date, m: m,type:type},
                    success: function (response) {
                        //var v = $.trim(response);
                        $('#trial_bal').html(response);
                        //alert(response);
                    }
                });
            }
        }
    </script>
@endsection
