<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
use App\Helpers\FinanceHelper;
$id = $_GET['id'];
$m = $_GET['m'];

$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$invoice = DB::table('invoice')->where('id','=',$id)->first();
$inv_data = DB::table('inv_data')->where('master_id','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
$Cdata = CommonHelper::get_single_row('client','id',$invoice->bill_to_client_id);
use App\Helpers\ReuseableCode;
?>
<style>

    .signature_bor {
        border-top:solid 1px #CCC;
        padding-top:7px;
    }
    .tra
    {
        display: none;
    }
    /*.table-bordered > thead > tr > th, .table-bordered > thead > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {*/
    /*border: 1px solid #000;*/
    /*}*/

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

        <button type="button" class="btn btn-sm btn-primary" onclick="PrintFunc('invoice')">Print</button>
        <?php if($invoice->inv_status == 1):?>
        <button type="button" class="btn btn-sm btn-primary" onclick="approve_invoice('{{$id}}')">Approve</button>
        <?php endif;?>
        <?php //echo Auth::user()->id;?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="invoice">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">

                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <img style="text-align: center; width: 100%" src="{{url('/storage/app/uploads/sn-logo.png')}}">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <h3 class=" text-center">Invoice</h3>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label>
                        10C, Mezzannine Floor, 5th St. Badar Commercial Area, Phase V-Ext DHA, KARACHI, 021-35344584-6 NTN # 1427996-7
                    </label>

                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 signature_bor"></div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:45%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>

                            <tr>
                                <td style="width: 30% !important">Invoice No</td>
                                <td>{{$invoice->inv_no}}</td>
                            </tr>

                            <tr>
                                <td style="width: 30% !important">Client Invoice No</td>
                                <td>{{$invoice->client_ref}}</td>
                            </tr>

                            <tr>
                                <td>Invoice Date</td>
                                <td><?php echo CommonHelper::changeDateFormat($invoice->inv_date);?></td>
                            </tr>
                            <tr>
                                <td style="width: 30% !important">Ship To</td>
                                <td>{{$invoice->ship_to}}</td>
                            </tr>

                            <tr>
                                <td style="width: 30% !important">Bill To</td>
                                <td>{{ CommonHelper::get_client_name_by_id($invoice->bill_to_client_id)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 30% !important">PO No.</td>
                                <td>{{ $invoice->po_no }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="width:45%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:60%;">Client Job</td>
                                <td style="width:40%;">{{$invoice->ship_to}}</td>
                            </tr>
                            <tr>
                                <td>Client Name</td>
                                <td>{{ CommonHelper::get_client_name_by_id($invoice->bill_to_client_id)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 30% !important">Ntn No</td>
                                <td><?php echo $Cdata->ntn;?></td>
                            </tr>
                            <tr>
                                <td style="width: 30% !important">Strn No</td>
                                <td><?php echo $Cdata->strn;?></td>
                            </tr>
                            <tr>
                                <td style="width: 30% !important">Address</td>
                                <td><?php echo $Cdata->address;?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:40%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:60%;">Job Order No</td>
                                <td style="width:40%;"><?php echo $invoice->job_order_no;?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="buildyourform"  class="table table-bordered">
                            <thead>
                            <tr>

                                <th class="text-center">Product Name</th>
                                <th class="text-center">Branch Name</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Sales Tax</th>
                                <th class="text-center">Sales Tax Amount</th>
                                <th class="text-center">Net Amount</th>
                                {{--@if (Auth::user()->id)--}}
                                {{--<th>16</th>--}}
                                {{--<th>13</th>--}}
                                    {{--@endif--}}
                            </tr>
                            </thead>
                            <tbody id="TrAppend">
                            <?php
                            $Counter=1;
                            $total_cost=0;
                            $total_net_amount=0;
                            $sales_tax_total=0;
                            foreach($inv_data as $row):?>
                            <tr id="<?= $row->id; ?>" ondblclick="">

                                <td><?php
                                    if ($row->product_id!=0):
                                    $produc_data=  CommonHelper::get_product_name_by_id($row->product_id);
                                    echo $produc_data->p_name;
                                        endif;
                                    ?></td>
                                <td>
                                    <?php
                                        if($row->branch_id > 0):
                                    $Branch = CommonHelper::get_single_row('branch','id',$row->branch_id);
                                    echo $Branch->branch_name;
                                        endif;
                                    ?>
                                </td>
                                <?php $branch_data = CommonHelper::get_single_row('branch','id',$row->branch_id);  $branch_data->branch_name; ?>

                                <td><textarea style="width: 100%" id="desc"><?php echo $row->description;?></textarea></td>

                                <td><?php echo $row->qty;?></td>

                                <td><?php echo number_format($row->rate,2);?></td>
                                <td class="text-right"><?php echo number_format($row->amount,2);
                                    $total_cost+=$row->amount;?></td>

                                <td class="text-right"><?php
                                    if ($row->txt_acc_id!=0):

                                    $data=ReuseableCode::pget_tax($row->txt_acc_id);

                                        $invoice_tax= DB::Connection('mysql2')->table('invoice_tax')->where('acc_id',$row->txt_acc_id)->select('name','tax_rate')->first();
                                    echo $invoice_tax->name;
                                        $tax_rate=$invoice_tax->tax_rate;

                                        endif;
                                    ?></td>

                                <td class="text-right">{{number_format($row->txt_amount,2)}}</td>
                                <?php $sales_tax_total+=$row->txt_amount; ?>
                                <td class="text-right">{{number_format($row->net_amount,2)}}</td>
                                {{--@if(Auth::user()->id)--}}
                                    {{--<?php $sixten= ($row->amount/100)*16 ?>--}}
                                    {{--<td @if($sixten==$row->txt_amount) style=" @endif">{{($row->amount/100)*16}}</td>--}}
                                        {{--<?php $thirteen= ($row->amount/100)*13 ?>--}}
                                    {{--<td @if($thirteen==$row->txt_amount) style=" @endif">{{($row->amount/100)*13}}</td>--}}
                                    {{--@endif--}}

                                <?php $total_net_amount+=$row->net_amount; ?>
                            </tr>
                            <?php endforeach;?>
                            <tr style="background-color: darkgray">
                                <td class="text-center" colspan="5"><b>Total</b></td>
                                <td class="text-right" colspan="1"><b>{{number_format($total_cost,2)}}</b></td>
                                <td></td>
                                <td class="text-right" colspan="1"><b>{{number_format($sales_tax_total,2)}}</b></td>
                                <td class="text-right" colspan="1"><b>{{number_format($total_net_amount,2)}}</b></td>
                            </tr>

                            <?php $data=ReuseableCode::get_inv_totals($id); ?>
                            @if ($data->discount_amount_after_tax>0)
                                <tr>
                                    <td class="text-center" colspan="3">Discount</td>
                                    <td class="text-right">{{$data->discount_percntage.' %'}}</td>
                                    <td class="text-right">{{$data->discount_amount}}</td>
                                    <td></td>
                                    <td class="text-right">{{$data->discount_amount_tax}}</td>
                                    <td class="text-right">{{$data->discount_amount_after_tax}}</td>
                                </tr>

                                <tr style="background-color: darkgray;font-weight: bold">
                                    <td class="text-center" colspan="5">Total Amount After Dicount</td>

                                    <td class="text-right">{{$data->total_amount_after_dicount_before_tax}}</td>
                                    <td></td>
                                    <td class="text-right">{{$data->total_sales_tax_after_tax_dicount}}</td>

                                    <td class="text-right">{{$data->total_amount_after_dicount}}</td>

                                </tr>
                            @endif
                                @if ($data->advance_amount_after_tax>0)
                                    <tr>
                                        <td class="text-center" colspan="4">Advance From Customer</td>
                                        <td class="text-right">{{$data->advance_percntage.' %'}}</td>
                                        <td class="text-right">{{number_format($data->advance_amount,2)}}</td>
                                        <td></td>
                                        <td class="text-right">{{number_format($data->advance_amount_tax,2)}}</td>f
                                        <td class="text-right">{{number_format($data->advance_amount_after_tax,2)}}</td>
                                    </tr>
                                    </tr>


                            @endif
                            <tr style="background-color: darkgray;font-weight: bold">
                                <td class="text-center" colspan="5">Grand Total</td>

                                <td class="text-right">{{$data->net_value_before_tax}}</td>
                                <td></td>
                                <td class="text-right">{{$data->net_tax_value}}</td>

                                <td class="text-right">{{$data->net_value}}</td>

                            </tr>

                            </tbody>



                            <?php
                            FinanceHelper::companyDatabaseConnection($m);
                            $rvsDetail = DB::table('transactions')->where('voucher_no','=',$invoice->inv_no)->where('status',1)->orderby('debit_credit','1')->get();
                            $costing_data=$rvsDetail;
                            $type = 5;
                            FinanceHelper::reconnectMasterDatabase();
                            $counter = 1;
                            $g_t_debit = 0;
                            $g_t_credit = 0;

                            ?>
                        </table>
                        <thead>

                        <table style="display: none;"  id=""  class="table table-bordered tra">
                            <tr class="">
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">Account</th>

                                <th class="text-center">Cost Center</th>

                                <th class="text-center">Description</th>
                                <th class="text-center" style="width:150px;">Debit</th>
                                <th class="text-center" style="width:150px;">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rvsDetail as $row2) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $counter++;?></td>
                            <td><?php echo FinanceHelper::getAccountNameByAccId($row2->acc_id,$m);?></td>

                            <td>{{CommonHelper::get_paid_to_type($row2->paid_to,$row2->paid_to_type)}}</td>
                            <td><?php echo $row2->particulars; ?></td>
                            <td class="debit_amount text-right">

                                <?php
                                if($row2->debit_credit == 1)
                                {
                                    $g_t_credit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>
                            <td class="credit_amount text-right">
                                <?php
                                if($row2->debit_credit == 0)
                                {
                                    $g_t_debit += $row2->amount;
                                    echo number_format($row2->amount,2);
                                }
                                ?>
                            </td>

                        </tr>
                        <?php
                        }
                        ?>
                        <tr class="sf-table-total">
                            <td colspan="4">
                                <label for="field-1" class="sf-label"><b>Total</b></label>
                            </td>
                            <td class="text-right"><b><?php echo number_format($g_t_credit,2);?></b></td>
                            <td class="text-right"><b><?php echo number_format($g_t_debit,2);?></b></td>
                        </tr>
                        </tbody>
                        </table>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="sf-label">Description<span class="rflabelsteric"><strong>*</strong></span></label>
                            <textarea name="inv_desc" id="inv_desc" cols="30" rows="3" placeholder="Description" class="form-control" >{{$invoice->description}}</textarea>
                        </div>

                        <label class="check">
                            Show Voucher
                            <input id="check"  type="checkbox" onclick="checkk()" class="check">
                        </label>
                    </div>
                </div>
                <div style="line-height:8px;">&nbsp;</div>

            </div>
        </div>
    </div>


</div>
<script !src="">

    $( document ).ready(function() {
        textAreaAdjust();
    });

    function hidesystem(id){
        $('#'+id).remove();
    }

    function PrintFunc(Id) {
        $('.check').css('display','none');
        $(".remHref").attr("href", "");
        $( ".qrCodeDiv" ).removeClass( "hidden" );
        var printContents = document.getElementById(Id).innerHTML;
        //alert(printContents);
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        //if(param3 == 0){
        location.reload();
        //}
    }
    function textAreaAdjust(element) {


    }

    function approve_invoice(id)
    {

        $.ajax({
            url: '{{url('/sdc/approve_invoice')}}',
            type: "GET",
            data: { id:id},
            success:function(data)
            {
                alert('Approved');
                $("#showDetailModelOneParamerter").modal("hide");
                $('.hide'+data).css('display','none');
            }
        });
    }

    function checkk()
    {

        if ($("#check").is(":checked"))
        {
            alert();

            $('.tra').css('display','block');
        }

        else
        {
            $('.tra').css('display','none');
        }
    }

</script>
