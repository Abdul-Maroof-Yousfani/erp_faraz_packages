@php
    use App\Helpers\CommonHelper;
    use App\Helpers\ImportHelper;

    $lc_detail = DB::connection('mysql2')
        ->table('lc_and_lg_against_po as lc')
        ->join('purchase_request as p', 'p.id', '=', 'lc.po_id')
        ->select('p.supplier_id', 'p.id as p_id', 'p.purchase_request_no', 'lc.lc_no', 'lc.lc_date', 'lc.id as lc_id')
        ->where('p.status', 1);

    $lc_detail = $lc_detail->get();

    $grand_total_invoice = 0;
    $grand_total_duties = 0;
@endphp
@foreach ($lc_detail as $value)
    @php

        $data = DB::connection('mysql2')
            ->table('lc_and_lg_against_po as lc')
            ->join('purchase_request as p', 'p.id', 'lc.po_id')
            ->join('purchase_request_data as p_data', 'p_data.master_id', 'p.id')
            // ->join('lc_and_lg_against_po_data as lc_data' , 'lc_data.master_id' , 'lc.id')
            ->join('bl_details as b', 'b.lc_and_lg_against_po_id', 'lc.id')
            ->select('p.*', 'p_data.*', 'p_data.rate  as po_rate', 'b.*', 'lc.*')
            ->where('lc.id', $value->lc_id)
            ->where('lc.status', 1)
            ->get();
        // dd($data);
    @endphp

    @php
        $counter = 1;
        $sub_total_invoice = 0;
        $sub_total_duties = 0;
    @endphp
    @if ($data)
        @foreach ($data as $row)
            @php
                $hs_code_data = ImportHelper::get_hs_code_data($row->sub_item_id);

                $acd = $hs_code_data->additional_custom_duty;
                $cd = $hs_code_data->custom_duty;
                $rd = $hs_code_data->regulatory_duty;
                $fed = $hs_code_data->federal_excise_duty;
                $st = $hs_code_data->sales_tax;
                $ast = $hs_code_data->additional_sales_tax;
                $it = $hs_code_data->income_tax;
                $ca = $hs_code_data->clearing_expense;

                $amount = $row->purchase_approve_qty * $row->po_rate;
                $item = CommonHelper::get_item_by_id($row->sub_item_id);
                $exchange_rate = $row->currency_rate;
                $convert_amount = $exchange_rate * $amount;
                $assessed_amount = ($convert_amount * 2.01) / 100 + $convert_amount;
                $cd_amount = ($assessed_amount * $cd) / 100;
                $acd_amount = ($assessed_amount * $acd) / 100;
                $rd_amount = ($assessed_amount * $rd) / 100;
                $fed_amount = (($assessed_amount + $cd_amount + $acd_amount + $rd_amount) * $fed) / 100;
                $st_amount = (($assessed_amount + $cd_amount + $acd_amount + $rd_amount + $fed_amount) * $st) / 100;
                $ast_amount = (($assessed_amount + $cd_amount + $acd_amount + $rd_amount + $fed_amount) * $ast) / 100;
                $it_amount =
                    (($assessed_amount +
                        $cd_amount +
                        $acd_amount +
                        $rd_amount +
                        $fed_amount +
                        $st_amount +
                        $ast_amount) *
                        $it) /
                    100;
                $ca_amount = ($assessed_amount * $ca) / 100;
                $total = $cd_amount + $acd_amount + $rd_amount + $fed_amount + $st_amount + $ast_amount + $it_amount;

                $sub_total_invoice += $assessed_amount;
                $sub_total_duties += $total;

            @endphp
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ CommonHelper::get_supplier_name($value->supplier_id) }}</td>
                <td>{{ $value->purchase_request_no }} </td>
                <td>{{ $row->lot_no }} </td>
                <td><strong>LC # : </strong> {{ $row->lc_no }} <br> <strong>DT : </strong> {{ $row->lc_date }}
                    <strong> HsCode : </strong> {{ $hs_code_data->hs_code }} </td>
                <td>{{ $item->sub_ic }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $row->eta }}</td>
                <td>{{ $row->lcl ? $row->lcl . ' LCL' : '' }} <br> {{ $row->ft_20 ? $row->ft_20 . 'X20' : '' }} <br>
                    {{ $row->ft_40 ? $row->ft_40 . 'X40' : '' }}</td>
                <td>{{ $row->Currency }}</td>
                <td>{{ $assessed_amount }}</td>
                <td>{{ $total }}</td>


            </tr>
        @endforeach
        @php
            $grand_total_invoice += $sub_total_invoice;
            $grand_total_duties += $sub_total_duties;
        @endphp
        <tr>
            <th colspan="8"></th>
            <th colspan="2">Sub Total</th>
            <th colspan="1">{{ $sub_total_invoice }}</th>
            <th colspan="1">{{ $sub_total_duties }}</th>
        </tr>
    @endif
@endforeach
<tr>
    <th colspan="8">
    <th colspan="2">Grand Total</th>
    <th colspan="1">{{ $grand_total_invoice }}</th>
    <th colspan="1">{{ $grand_total_duties }}</th>
    </th>
</tr>
