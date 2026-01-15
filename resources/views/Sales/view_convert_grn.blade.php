@extends('layouts.default')
@section('content')
<?php
use App\Helpers\CommonHelper;
$ImportPo = DB::Connection('mysql2')->table('import_po')->where('id',$id)->first();
$warehouse = DB::Connection('mysql2')->table('warehouse')->where('status',1)->get();
?>
<div class="row">
    <div class="">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            <?php echo CommonHelper::displayPrintButtonInBlade('convert_to_grn','','1');?>

            <a id="dlink"></a>
            <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

        </div>
    </div>
</div>
<div style="" id="convert_to_grn" class="row">

    <h3 style="text-align: center">Convert To GRN</h3>
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="convert">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Item</th>
                <th class="text-center">QTY</th>
                <th class="text-center">FCY Unit Price</th>
                <th class="text-center">FCY Amount</th>
                <th class="text-center">FCY Average Rate</th>
                <th class="text-center">Rate Per KG</th>
                <th class="text-center">Landed Cost Per KG</th>
                <th class="text-center">Total Rate Per KG</th>
                <th class="text-center">Total Amount After Expense</th>
                <th class="text-center">Total Weight</th>
                <th class="text-center">Total Weight Per Length</th>


                </thead>

                <?php $data=DB::Connection('mysql2')->table('import_po_data as a')
                        ->select('a.*',DB::raw('SUM(b.amount_in_pkr) as pkr_lum_sum'),DB::raw('SUM(b.foreign_amount) as forign_lum_sum'),
                                DB::raw('SUM(c.duty) as total_exp'))
                        ->join('import_payment as b','a.master_id','=','b.import_id')
                        ->leftJoin('import_expense as c','a.master_id','=','c.import_id')
                        ->where('a.master_id',$id)
                        ->where('a.status',1)
                        ->where('b.status',1)
                        ->where('c.status',1)
                        ->groupBy('a.id')
                        ->groupby('b.import_id')
                        ->get();


                ?>
                <tbody id="data">


                @php

                $total_landed_cost=0;
                $total_amount_toal=0;
                $grand_total=0;
                $counter=1; @endphp
                @foreach($data as $row)

                    @php
                    $average=$row->pkr_lum_sum / $row->forign_lum_sum;
                    $fyc_amount=$row->qty * $row->foreign_currency_price;
                    $total_amount=$average*$fyc_amount;

                    $landed_cost=DB::Connection('mysql2')->table('import_po_data')->where('master_id',$row->master_id)->where('status',1)->sum('amount');
                    $landed_costt=$landed_cost*$average;
                    $expense_data=DB::Connection('mysql2')->table('import_expense')->where('import_id',$row->master_id)->where('status',1);
                    $total_exp=$expense_data->sum('duty')+$expense_data->sum('eto')+$expense_data->sum('do')+$expense_data->sum('appraisal')+$expense_data->sum('fright')+$expense_data->sum('insurance')
                    +$expense_data->sum('expense')+$expense_data->sum('other_expense');

                    $landed_cost=($total_amount / $landed_costt)*$total_exp;
                    $total_landed_cost+=$landed_cost;
                    $total_amount_toal+=$total_amount;


                    @endphp
                    <tr id="{{ $row->id }}">
                        <td class="text-center counter">{{$counter}}</td>
                        <td class="text-center counter <?php echo $row->item_id?>"><?php echo CommonHelper::get_item_name($row->item_id);?></td>
                        <td class="text-center main_{{$counter}}" id="GetQty<?php echo $row->id?>">{{ $row->qty }}</td>
                        <td class="text-center" id="Getfcprice<?php echo $row->id?>">{{ $row->foreign_currency_price }}</td>
                        <td class="text-center" id="Getfcamount<?php echo $row->id?>">{{ $fyc_amount }}</td>
                        <td class="text-center" id="Getaverage<?php echo $row->id?>">{{ number_format($average,2)}}</td>
                        <td class="text-center" >{{ number_format($total_amount / $row->total_weight,2) }}</td>
                        <?php
                              $la=$landed_cost/$row->total_weight;
                              $total_amount_kg=$total_amount / $row->total_weight;
                            $total=$la+$total_amount_kg;

                            $total_weight = $row->total_weight;
                            
                        ?>
                        <td class="text-center" title="<?php echo (number_format($total_amount,2) .'/' .number_format($landed_costt,2)).'*'.number_format($total_exp,2); ?>"> {{number_format($landed_cost/$row->total_weight,2)}}</td>

                            <td class="text-center">{{number_format($total,2)}}</td>
                        <td class="text-center" title="<?php echo number_format($landed_cost,2).'+'.number_format($total_amount,2) ?>">{{number_format($landed_cost+$total_amount,2)}}</td>

                        <td class="text-center">{{number_format($row->total_weight,2)}}</td>
                        <td class="text-center">{{number_format($row->total_weight/$row->qty,2)}}</td>

                        <?php $grand_total+=$landed_cost+$total_amount; ?>

                    </tr>
                    <input type="hidden" id="id{{$counter}}" value="{{$row->id}}"/>



                <?php $counter++ ?>    @endforeach
                <input type="hidden" id="count" value="{{$counter-1}}"/>
                <tr style="font-weight: bolder">
                    <td colspan="5">Total</td>
                    <td>{{number_format($total_amount_toal,2)}}</td>
                    <td>{{number_format($total_landed_cost,2)}}</td>
                    <td>{{number_format($grand_total,2)}}</td>

                </tr>

                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="count" id="count" value="{{$counter-1}}"/>


</div>



<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('convert');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Convert To GRN <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
