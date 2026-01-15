<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(279);
$edit=ReuseableCode::check_rights(280);
$delete=ReuseableCode::check_rights(281);
$export=ReuseableCode::check_rights(282);


$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$first_day_this_month = date('Y-m-01');
$last_day_this_month  = date('Y-m-t');

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
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">View STP List</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <button class="btn btn-primary" onclick="printView('printBankPaymentVoucherList','','1')" style="">
                                        <span class="glyphicon glyphicon-print"></span>  Print
                                    </button>
                                    <?php if($export == true):?>
                                    <?php echo CommonHelper::displayExportButton('ExpvoucherList','','1')?>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  value="<?php echo $first_day_this_month;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $last_day_this_month;?>" class="form-control" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php $Suppliers =DB::Connection('mysql2')->select('select a.id,a.name from supplier a
                                                                                          INNER JOIN accounts b on b.id = a.acc_id
                                                                                          where a.status = 1 and b.parent_code = "2-2-8"');?>
                                    <label for="">Supplier</label>
                                    <select name="SupplierId" id="SupplierId" class="form-control select2">
                                        <option value="all">ALL</option>
                                        <?php foreach($Suppliers  as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>


                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="table table-bordered sf-table-list" id="ExpvoucherList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Pv No</th>
                                                                <th class="text-center">Pv Date</th>
                                                                <th class="text-center">Supplier Invoice No</th>
                                                                <th class="text-center">Supplier Invoice date</th>
                                                                <th class="text-center">Supplier</th>
                                                                <th class="text-center">Gross Amount</th>
                                                                <th class="text-center">Tax Amount</th>
                                                                <th class="text-center">Total Amount</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center hidden-print">Action</th>
                                                                </thead>
                                                                <tbody id="data">
                                                                <?php
                                                                $Counter = 1;
                                                                $total_amount=0;
                                                                foreach ($Data as $Fil) {
                                                                $edit_url= url('/finance/edit_new_pv/'.$Fil->id.'?m='.$m);
                                                                ?>
                                                                <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
                                                                    <td class="text-center"><?php echo $Counter++;?></td>
                                                                    <td class="text-center"><?php echo strtoupper($Fil->pv_no);?></td>
                                                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($Fil->pv_date);?></td>
                                                                    <td class="text-center"><?php echo $Fil->supplier_invoice_no?></td>
                                                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($Fil->supplier_invoice_date);?></td>
                                                                    <td class="text-center"><?php echo CommonHelper::get_supplier_name($Fil->supplier_id);?></td>
                                                                    <td class="text-center"><?php

                                                                        $gros= DB::Connection('mysql2')->table('new_pvv_data')->where('master_id',$Fil->id)->sum('amount');
                                                                            echo number_format($gros,2);
                                                                        ?></td>

                                                                    <td class="text-center"><?php

                                                                        $tax=$Fil->sales_tax_amount;
                                                                        echo number_format($tax,2);
                                                                        ?></td>

                                                                    <td>{{number_format($tax+$gros,2)}}</td>
                                                                    <?php $total_amount+=$tax+$gros; ?>

                                                                    <td id="status{{$Fil->id}}"  class="text-center"><?php if ($Fil->pv_status==1):  echo  'pending'; else: echo 'Approved'; endif?></td>
                                                                    <td class="text-center hidden-print">
                                                                        <?php if($view == true):?>
                                                                        <a onclick="showDetailModelOneParamerter('finance/view_new_pv_detail','<?php echo $Fil->id;?>','View New PV Detail','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success">View</a>
                                                                        <?php endif;?>
                                                                        <?php if($Fil->pv_status == 1):?>
                                                                            <?php if($edit == true):?>
                                                                                <a href="<?php echo $edit_url?>" type="button" class="btn btn-xs btn-primary">Edit</a>
                                                                            <?php endif;?>
                                                                        <?php endif;?>
                                                                        <?php if($delete == true):?>
                                                                            <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $Fil->id?>" onclick="DeleteNewPv('<?php echo $Fil->id?>','<?php echo $Fil->pv_no?>')">Delete</button>
                                                                        <?php endif;?>

                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <th colspan="8" class="text-center">Total</th>
                                                                    <th colspan="1" class="text-center">{{number_format($total_amount,2)}}</th>

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
    <script !src="">
        $(document).ready(function(){
            $('.select2').select2();
        });


        function DeleteNewPv(Id,PvNo)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                $.ajax({
                    url: '/fdc/deleteNewPv',
                    type: 'Get',
                    data: {Id: Id,PvNo:PvNo,m:m},

                    success: function (response)
                    {
                        $('#RemoveTr'+response).remove();
                    }
                });
            }
            else {}
        }



        function viewRangeWiseDataFilter()
        {
            var from= $('#from').val();
            var to= $('#to').val();
            var SupplierId= $('#SupplierId').val();
            var m  = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/get_new_pvs_list_ajax',
                type: 'Get',
                data: {from: from,to:to,m:m,SupplierId:SupplierId},

                success: function (response) {

                    $('#data').html(response);


                }
            });

        }

    </script>
@endsection
