<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(260);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

        $DeliveryNote = DB::Connection('mysql2')->select('select sum(b.amount) net_amount,b.so_id,a.id dn_id,a.* from delivery_note a
                                          INNER JOIN delivery_note_data b ON b.master_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY a.id');
?>

@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                            <?php if($export == true):?>
                            <a id="dlink" style="display:none;"></a>
                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                        <?php endif;?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Sales Order Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                    <input type="button" value="Get Data" class="btn btn-sm btn-primary" onclick="get_data_ajax();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printGoodsReceiptNoteList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">So No</th>
                                                                <th class="text-center">So Date</th>
                                                                <th class="text-center">Dn No</th>
                                                                <th class="text-center">Dn Date</th>
                                                                <th class="text-center">Buyer</th>
                                                                <th class="text-center">DN Amount</th>
                                                                <th class="text-center">DN RETURN Amount</th>
                                                                <th class="text-center">STI Amount</th>
                                                                </thead>
                                                                <tbody id="GetDataAjax">
                                                                <?php
                                                                $Counter = 1;
                                                                 $TotalDnAmount = 0;
                                                                        $TotalDnAmountReturn = 0;
                                                                        $TotalStiAmount = 0;
                                                                foreach($DeliveryNote as $Fil):
                                                                $Customer = CommonHelper::get_single_row('customers','id',$Fil->buyers_id);
                                                                ?>

                                                                    <tr class="text-center">
                                                                        <td><?php echo $Counter++;?></td>
                                                                        <td><?php echo $Fil->so_no?></td>
                                                                        <td><?php echo date_format(date_create($Fil->so_date), 'd-m-Y')?></td>
                                                                        <td><?php echo $Fil->gd_no?></td>
                                                                        <td><?php echo date_format(date_create($Fil->gd_date), 'd-m-Y')?></td>
                                                                        <td><?php echo $Customer->name;?></td>
                                                                        <td><?php echo $Fil->net_amount+$Fil->sales_tax_amount; $TotalDnAmount+=$Fil->net_amount+$Fil->sales_tax_amount;?></td>
                                                                        <td><?php echo SalesHelper::get_sales_return_dn($Fil->dn_id); $TotalDnAmountReturn+=SalesHelper::get_sales_return_dn($Fil->dn_id);?></td>
                                                                        <td><?php echo SalesHelper::get_sales_inv_amount($Fil->so_id); $TotalStiAmount+=SalesHelper::get_sales_inv_amount($Fil->so_id);?></td>

                                                                    </tr>
                                                                <?php endforeach;?>
                                                                    <tr class="text-center">
                                                                        <td colspan="6"><strong>TOTAL</strong></td>
                                                                        <td><?php echo $TotalDnAmount;?></td>
                                                                        <td><?php echo $TotalDnAmountReturn;?></td>
                                                                        <td><?php echo $TotalStiAmount?></td>
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('goodsReceiptNoteList');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('DN Report <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>

        function get_data_ajax()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var m = '<?php echo $_GET['m']?>';

            $('#GetDataAjax').html('<tr><td colspan="10"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
            $('#PoStatusError').html('');
            $.ajax({
                url: '<?php echo url('/')?>/sdc/getDnDetailDateWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,m:m},
                success: function (response)
                {
                    $('#GetDataAjax').html(response);
                }
            });
        }



    </script>

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection