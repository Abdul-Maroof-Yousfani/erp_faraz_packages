<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\SalesHelper;

if($quotation->currency_id == 3):
    $cur = 'PKR';
elseif($quotation->currency_id == 4):
    $cur = 'USD';
endif;
?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>
<?php 


?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
        <?php if($quotation->quotation_status==1): ?>
            <button onclick="approve('<?php echo e($id); ?>','<?php echo e($quotation->pr_id); ?>')" type="button" class="btn btn-success">Approve</button>
            <?php endif; ?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Quotation Detail </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>PR NO</td>
                            <td class="text-center"><?php echo strtoupper($quotation->pr_no);?></td>
                        </tr>

                        <tr>
                            <td>PR Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($quotation->start_date);?></td>
                        </tr>
                        <tr>
                            <td>Quotation No</td>
                            <td class="text-center"><?php echo e(strtoupper($quotation->voucher_no)); ?></td>
                        </tr>
                        <tr>
                            <td>Quotation Date</td>
                            <td class="text-center"> <?php echo e(CommonHelper::changeDateFormat($quotation_data[0]->demand_date)); ?> </td>
                        </tr>
                        

                        </tbody>
                    </table>
                </div>



                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Vendor</td>
                            <td class="text-center"><?php echo CommonHelper::get_supplier_name($quotation->vendor_id)?></td>
                        </tr>
                        <tr>
                            <td>Ref No</td>
                            <td class="text-center"><?php echo $quotation->ref_no ?></td>
                        </tr>
                        <tr>
                            <td>Currency</td>
                            <td class="text-center"><?php echo e(CommonHelper::get_curreny_name($quotation->currency_id)); ?></td>
                        </tr>
                        <tr>
                            <td>Currency Rate</td>
                            <td class="text-center"><?php echo e($quotation->currency_rate); ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>


            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        $total_amount=0;
                        foreach ($quotation_data as $row):
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td class="text-center"> <?php echo CommonHelper::get_item_name($row->sub_item_id);?></td>
                            <td class="text-center"><?php echo  CommonHelper::get_uom($row->sub_item_id) ?></td>
                            <td style="text-align: center"><?php echo number_format($row->qty,2)?></td>
                            <td style="text-align: center"><?php echo e($cur); ?> <?php echo e(number_format($row->rate,3)); ?></td>
                            <td style="text-align: center"><?php echo e($cur); ?> <?php echo e(number_format($row->amount,3)); ?></td>
                        </tr>

                        <?php
                        $total_amount+=$row->amount;
                        endforeach
                        ?>
                        <tr class="text-center">
                            <td class="bold" colspan="5">Total</td>
                            <td style="text-align: center" colspan="1"><?php echo e($cur); ?> <?php echo e(number_format($total_amount,2)); ?></td>
                        </tr>
                       <?php if($quotation->gst_amount > 0): ?>
                        <tr class="text-center">
                            <td class="bold" colspan="5">Sales Tax <?php echo e(number_format($quotation->gst).' %'); ?></td>
                            <td style="text-align: center" colspan="1"><?php echo e($cur); ?> <?php echo e(number_format($quotation->gst_amount,2)); ?></td>
                        </tr>

                        <tr class="text-center">
                            <td class="bold" colspan="5">Total Amount With Tax</td>
                            <td style="text-align: center" colspan="1"><?php echo e($cur); ?> <?php echo e(number_format($quotation->gst_amount + $total_amount,2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style=""><?php  ?></div>
            <div style="line-height:8px;">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <style>
                    .signature_bor {
                        border-top:solid 1px #CCC;
                        padding-top:7px;
                    }
                </style>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Prepared By: </h6>
                                <b>   <p><?php echo strtoupper($quotation->username);  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Checked By:</h6>
                                <b>   <p><?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Approved By:</h6>
                                <b>  <p><?php echo strtoupper($quotation->approve_username);  ?></p></b>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function approve(id,pr_id)
    {



            $.ajax({

                url:'<?php echo e(url('quotation/approve')); ?>',
                type:'GET',
                data:{id:id,id,pr_id:pr_id},
                success:function(response)
                {

                    if (response=='no')
                    {
                        alert('Quotation Againts This PR Alreday Approved');
                        return false;
                    }
                    $('#'+id).html('Approved');
                    $('#showDetailModelOneParamerter').modal('hide');
                    get_data();
                },
                err:function(err)
                {
                    $('#data').html(err);
                }
            })

    }
</script>


