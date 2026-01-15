<?php
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;
$Agent = DB::Connection('mysql2')->table('sales_agent')->where('id',Request::get('agent'))->first();
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive">
        <h5 style="text-align: center" id="h3"></h5>
        <?php echo Form::open(array('url' => 'fad/commision_form','id'=>'cashReceiptVoucherForm'));?>
        <table class="table table-bordered sf-table-list" id="TableExport">
            <thead>
                <th colspan="12" class="text-center">(<?php echo FinanceHelper::getCompanyName(Session::get('run_company'));?>)</th>
            </thead>
            <thead>
                <th colspan="12" class="text-center">Commission</th>
            </thead>
            <thead>
                <th colspan="12" class="text-center">From Date: (<?php echo date_format(date_create(Request::get('from')), 'd-m-Y');?>)==========To Date: (<?php echo date_format(date_create(Request::get('to')), 'd-m-Y');?> ==== <?php echo $Agent->agent_name?>)</th>
            </thead>
            <thead>
            {{--<th class="text-center">Check/Uncheck</th>--}}
            <th class="text-center">S.No</th>
            <th class="text-center">Rv no</th>
            <th class="text-center">SI No</th>
            <th class="text-center">Customer Name</th>
            <th class="text-center">SI Amount</th>
            <th class="text-center">Return Amount</th>
            <th class="text-center">Net Amount</th>
            <th class="text-center">PL Percentage</th>
            <th class="text-center">Received Amount</th>
            <th class="text-center">No Of Days</th>
            <th class="text-center">Commision Amount</th>
            <th class="text-center">Commision Pay</th>


            </thead>

            <td>
                @php $count=1; @endphp
                @foreach($data as $row)
                <tr>
                    <td class="text-center">{{$count++}}</td>
                    <td style="cursor: pointer" onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','{{$row->id}}','View Bank Reciept Voucher Detail','1','')" class="text-center">{{strtoupper($row->rv_no)}}</td>
                    <td style="cursor: pointer" onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->si_id ?>','View Sales Tax Invoice')" class="text-center">{{strtoupper($row->gi_no)}}</td>
                    <td>{{$row->customer_name}}</td>
                    <?php

                    $si_amount=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('status',1)->where('master_id',$row->si_id)->sum('amount');


                    $return_data=DB::Connection('mysql2')->table('credit_note as a')->
                    join('credit_note_data as b','a.id','=','b.master_id')
                    ->select(DB::raw('SUM(b.net_amount) As return_amount'))
                    ->where('a.status',1)
                    ->where('a.si_id',$row->si_id)
                    ->first();

                    $return_amount= $return_data->return_amount;
                    ;
                    ?>
                    <td  class="text-center">{{number_format($si_amount,2)}}</td>
                    <?php  //$return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice($row->si_id);?>
                    <td class="text-center">{{number_format($return_amount,2)}}</td>

                    <?php $net_amount=$si_amount-$return_amount; ?>
                    <td class="text-center">{{number_format($net_amount,2)}}</td>

                    <?php
                    $cost=DB::Connection('mysql2')->table('transactions')->where('status',1)->where('debit_credit',1)->
                    where('voucher_no',$row->gi_no)->where('voucher_type',8)->sum('amount');
                    ?>
                    <?php
                    if ($cost>0):
                    $pl_percent=(($net_amount - $cost)/$net_amount)*100;
                        else:
                            $pl_percent=0;
                            endif;
                        ?>
                    <td class="text-center">{{number_format($pl_percent,2).'%'}}</td>


                    <td class="text-center">{{number_format($row->received_amount,2)}}</td>

                    <?php
                    $date1 = new DateTime($row->gi_date);
                    $date2 = new DateTime($row->rv_date);
                    $interval = $date1->diff($date2);
                    $no_of_day=$row->model_terms_of_payment-$interval->days ;
                    ?>
                    <td class="text-center" @if($no_of_day<=0) style="background-color: lightcoral" @endif>{{$no_of_day}}
                    </td>
                    <?php

                    $commision=0;
                    ?>
                     @if ($pl_percent>0)
                        @if($pl_percent>=5 && $pl_percent<11)
                            <?php $commision=$net_amount/100;


                            ?>
                            @elseif($pl_percent>11)
                                <?php $commision= ($net_amount/100)*2?>
                            @endif

                            @endif
            <?php $val=''; ?>
                    @if ($pl_percent>0 && $pl_percent>=5 && $no_of_day<=0)
                    @if ($no_of_day==0 || $no_of_day>=-15)
                        <?php $commision=$commision; $val='COND: 1'; ?>
                    @elseif ($no_of_day==-16 || $no_of_day>=-30)
                        <?php $commision=$commision*0.75; $val='COND: 2'; ?>
                    @elseif ($no_of_day==-30 || $no_of_day>=-45)
                        <?php $commision=$commision*0.50; $val='COND: 3'; ?>


                    @elseif ($no_of_day==-45 || $no_of_day>=-60)
                        <?php $commision=$commision*0.25; $val='COND: 4'; ?>

                            @elseif ($no_of_day<=-61)
                                <?php $commision=0; $val='COND: 5';?>
                    @endif
                    @endif

                    <?php $countt=DB::Connection('mysql2')->table('commision_data')->where('brigde_id',$row->brigde_id)->where('status',1)->count(); ?>
                    @if($commision>0 && $countt==0)
                    <input type="hidden" name="brigde_id[]" value="{{$row->brigde_id}}"/>
                    <input type="hidden" name="percent[]" value="{{$pl_percent}}"/>
                    <input type="hidden" name="amount[]" value="{{$net_amount}}"/>
                    <input type="hidden" name="from" value="{{Request::get('from')}}"/>
                    <input type="hidden" name="to" value="{{Request::get('to')}}"/>
                    <input type="hidden" name="agent" value="{{Request::get('agent')}}"/>
               
                    <input type="hidden" name="no_days[]" value="{{$no_of_day}}"/>
                    <input type="hidden" name="cond[]" value="{{$val}}"/>
                    @endif

                    <td class="text-center">{{number_format($commision,2)}}</td>
                    <td><input class="form-control" @if($commision==0 || $countt>0) disabled @endif type="text" name="commision[]" value="{{$commision}}" /> </td>
                </tr>
                @endforeach
            </td>

        </table>
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
              <input style="width: 100%" type="submit" class="btn btn-success" value="Submit">

            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>