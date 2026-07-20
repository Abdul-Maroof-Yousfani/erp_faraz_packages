<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$show_detail=ReuseableCode::check_rights(283);
?>

<style>
 @media print{a.HrefHide:after{content:"" !important;}
}
.sfReportBox{font-family:'Inter',sans-serif;padding:26px 28px 30px;}
.sfReportBox .print-header-block{background:linear-gradient(150deg,#F7F9FD,#F0F1FA);border-radius:14px;padding:22px 24px;margin-bottom:20px;text-align:center;}
.sfReportBox .print-header-block h2,.sfReportBox .print-header-block h3,.sfReportBox .print-header-block h4{font-family:'Sora',sans-serif;color:#1B2333;}
#CheckUnCheck{accent-color:#7C5CFC;margin-left:6px;}
label[for="CheckUnCheck"]{font-weight:700;font-size:13px;color:#4A5268;}
.sf-report-print-table{border:none !important;border-collapse:separate !important;border-spacing:0 !important;width:100%;font-size:13px;margin-top:16px;}
.sf-report-print-table thead th{background:#131B2E !important;color:#fff !important;font-weight:700 !important;font-size:11.5px !important;text-transform:uppercase;letter-spacing:.4px;padding:12px 14px !important;border:none !important;white-space:nowrap;}
.sf-report-print-table thead th:first-child{border-top-left-radius:10px;}
.sf-report-print-table thead th:last-child{border-top-right-radius:10px;}
.sf-report-print-table tbody td{padding:11px 14px !important;border:none !important;border-bottom:1px solid #EEF0F7 !important;color:#2A3145;}
.sf-report-print-table tbody tr:nth-child(even){background:#FAFBFE;}
.sf-report-print-table tbody tr:hover{background:#F3F0FF;}
.sf-report-print-table tbody tr td:first-child{font-weight:700;color:#7C5CFC;}
.sf-report-print-table a.HrefHide{color:#1B2333 !important;font-weight:600;text-decoration:none !important;}
.sf-report-print-table a.HrefHide:hover{color:#7C5CFC !important;text-decoration:underline !important;}
.table-responsive{height:auto !important;}
</style>

<div class="sfReportBox">

    <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'), 'Stock Summary Report (Batch Wise)'); ?>

    <?php if($show_detail == true):?>
    <label for="CheckUnCheck">Show Detail</label>
    <input type="checkbox" id="CheckUnCheck" onclick="ShowHideDetail()">
    <?php endif;?>

    <div class="table-responsive">
        <table class="table table-bordered sf-report-print-table" id="expToExcel">
        <thead>
            <tr>
                <th class="text-center">S.No</th>
                <th class="text-center">Item</th>
                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center ShowHideTd" style="display: none">Average Cost</th>

                <th class="text-center ShowHideTd" style="display: none">Purchase QTY.</th>
                <th class="text-center ShowHideTd" style="display: none">Produce QTY.</th>
                <th class="text-center ShowHideTd" style="display: none">Purchase Return.</th>
                <th class="text-center ShowHideTd" style="display: none">Transferd QTY.</th>
                <th class="text-center ShowHideTd" style="display: none">Consumption  QTY.</th>
                <th class="text-center ShowHideTd" style="display: none">Issuence Against Production Plan</th>
                <th class="text-center ShowHideTd" style="display: none">Return Against Production Plan</th>
                <th class="text-center ShowHideTd" style="display: none">Sales QTY.</th>
                <th class="text-center ShowHideTd" style="display: none">Sales Return QTY.</th>
                <th class="text-center">In Stock</th>
            </tr>
            </thead>
            <tbody id="filterDemandVoucherList">
            <?php
            $counter=1;
            ?>
            @foreach($category as $data)
                <?php


                $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                $sub_ic_data=explode(',',$sub_ic_data);
                $uom=$sub_ic_data[0];
                $sub_item_id=$sub_ic_data[4];


                $purchase_amount= CommonHelper::get_amount_from_stock_batch_wise(1,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $produce_qty= CommonHelper::get_amount_from_stock_batch_wise(11,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $purchase_return= CommonHelper::get_amount_from_stock_batch_wise(2,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $stock_tarnsfer= CommonHelper::get_amount_from_stock_batch_wise(3,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $stock_consumption= CommonHelper::get_amount_from_stock_batch_wise(8,$data->sub_item_id,$data->warehouse_id,$data->batch_code);

                $issuence_plane= CommonHelper::get_amount_from_stock_batch_wise(9,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $return_plane= CommonHelper::get_amount_from_stock_batch_wise(10,$data->sub_item_id,$data->warehouse_id,$data->batch_code);


                $stock_received= CommonHelper::get_amount_from_stock_batch_wise(4,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $sales_qty= CommonHelper::get_amount_from_stock_batch_wise(5,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                $sales_return_qty= CommonHelper::get_amount_from_stock_batch_wise(6,$data->sub_item_id,$data->warehouse_id,$data->batch_code);
                if ($purchase_amount >0 || $purchase_return>0 || $stock_tarnsfer>0 || $sales_qty>0 || $produce_qty >0 || $issuence_plane>0 || $return_plane >0):

                ?>
                <tr>
                    <td class="text-center">{{$counter++}}</td>
                    <td class="text-center">
                        <a class="HrefHide" href="<?php echo url('/') ?>/store/fullstockReportViewBatch?pageType=&&parentCode=97&&m=<?php echo $_GET['m'] ?>&&sub_item_id=<?php echo $data->sub_item_id ?>&&warehouse_id=<?php echo $data->warehouse_id ?>&&batch_code=<?php echo $data->batch_code ?>#SFR" target="_blank">{{$sub_item_id}}</a>
                    </td>
                    <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                    <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>
                    <td title="{{$data->voucher_no}}" class="text-center">{{$data->batch_code}}</td>

                    <td class="text-center ShowHideTd" style="display: none;">{{number_format(ReuseableCode::average_cost_sales($data->sub_item_id,$data->warehouse_id,$data->batch_code),2)}}</td>
                    <td class="text-center ShowHideTd" style="display: none;">{{number_format($purchase_amount,2)}}</td>
                    <td class="text-center ShowHideTd" style="display: none;">{{number_format($produce_qty,2)}}</td>
                    <td class="text-center ShowHideTd" style="display: none;">{{number_format($purchase_return,2)}}</td>
                    <td class="text-center ShowHideTd" style="display: none;">{{number_format($stock_tarnsfer,2)}}</td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($stock_consumption,2); ?></td>

                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($issuence_plane,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($return_plane,2); ?></td>



                    

                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_qty,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_return_qty,2); ?></td>

                    <td class="text-center"> <?php $in_stock=$purchase_amount-$purchase_return-$stock_tarnsfer-$stock_consumption-$sales_qty+$sales_return_qty+$produce_qty+$return_plane-$issuence_plane;
                        echo number_format($in_stock,2);
                        ?></td>
                </tr>
                <?php endif; ?>
            @endforeach

            </tbody>
        </table>
    </div> 
</div>
<script !src="">
    function ShowHideDetail()
    {
        //alert(); return false;
        if($('#CheckUnCheck').is(":checked"))
        {
            $('.ShowHideTd').fadeIn();
        }
        else{
            $('.ShowHideTd').fadeOut();
        }
    }
</script>

