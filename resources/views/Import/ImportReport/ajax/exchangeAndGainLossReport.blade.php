@php
    use App\Helpers\CommonHelper;
    use App\Helpers\ImportHelper;

    $data = DB::connection('mysql2')
        ->table('bl_details as b')

        ->join('lc_and_lg_against_po as lc', 'lc.id', '=', 'b.lc_and_lg_against_po_id')
        // ->join('lc_and_lg_against_po_data as lc_d' ,'lc.id'  , 'lc_d.master_id' )
        ->leftjoin('purchase_request as p', 'p.id', 'lc.po_id')

        // ->leftjoin('purchase_request_data as pd','pd.master_id','p.id')
        // ->leftJoin('lc_and_lg_against_po as c','a.id','c.po_id')
        ->join('shipping_expenses as sh', 'sh.lc_and_lg_against_po_id', '=', 'lc.id')
        ->join('clearing_agents as cl', 'cl.lc_and_lg_against_po_id', '=', 'lc.id')
        ->join('g_d_details as gd', 'gd.lc_and_lg_against_po_id', '=', 'lc.id')
        ->join('maturity_details as m', 'm.lc_and_lg_against_po_id', '=', 'lc.id')
        // ->join('maturity_details as m' , 'm.lc_and_lg_against_po_id' ,'=' ,'lc.id')
        ->select(
            'lc.beneficiary_id',
            'p.supplier_id',
            'lc.description',
            'lc.lc_no',
            'lc.lc_date',
            'lc.sub_description',
            'lc.Currency',
            'lc.Currency_id',
            'b.bl_date',
            'b.bl_nbp',
            'm.maturity_date',
            'm.rate as maturity_rate',
            'gd.gd_no',
            'gd.date as gd_date',
            'gd.gd_rate',
            'b.ioco',
            'b.efs',
            'gd.assessed_value',
            'gd.custome_duty_percent',
            'gd.custome_duty_amount',
            'gd.acd_percent',
            'gd.acd_amount',
            'gd.rd_percent',
            'gd.rd_amount',
            'gd.st_percent',
            'gd.st_amount',
            'gd.it_percent',
            'gd.it_amount',
            'gd.fed_percent',
            'gd.fed_amount',
            'gd.ast_percent',
            'gd.ast_amount',
            'gd.eto_percent',
            'gd.eto_amount',
            // 'lc_d.item_id','lc_d.total_amount',
            'cl.amount as total_amount',
            'gd.gd_cfr_value',
            'gd.gd_new',
            // 'pd.sub_item_id',
            'sh.do_charges',
            'sh.lolo',
            'sh.port_charges',
            'cl.bill_no as cl_bill_no',
        )
        ->orderBy('lc.id', 'desc')
        ->get();

    // dd($data);


    $counter = 1;

@endphp
@foreach ($data as $row)
    @php
        $bl_amount = $row->total_amount * $row->bl_nbp;
        $maturity_amount = $row->total_amount * $row->maturity_rate;
        $gain_loss_amount = $bl_amount - $maturity_amount;
    @endphp
    <tr>
        <td>{{ $counter++ }}</td>
        <td>{{ CommonHelper::get_supplier_name($row->supplier_id) }}</td>
        <td>{{ $row->description }}</td>
        <td>{{ $row->sub_description }}</td>
        <td>{{ $row->lc_no }}</td>
        <td>{{ $row->lc_date }}</td>
        <td>{{ $row->Currency }}</td>
        <td>{{ $row->total_amount ?? 0 }}</td>
        <td>{{ $row->gd_cfr_value }}</td>
        <td>{{ $row->bl_date }}</td>
        <td>{{ $row->bl_nbp }}</td>
        <td>{{ $row->maturity_rate }}</td>
        <td>{{ $row->maturity_date }}</td>
        <td>{{ $bl_amount }}</td>
        <td>{{ $maturity_amount }}</td>
        <td>{{ $gain_loss_amount }}</td>
        <td>{{ $gain_loss_amount > 0 ? 'GAIN' : 'LOSS' }}</td>
        <td>{{ $row->gd_no }}</td>
        <td>{{ $row->gd_date }}</td>
        <td>{{ $row->gd_rate }}</td>
        <td>{{ $row->gd_new }}</td>
        <td>{{ $row->ioco }}</td>
        <td>{{ $row->efs }}</td>
        <td>{{ $row->assessed_value }}</td>
        <td>{{ $row->custome_duty_percent }} %</td>
        <td>{{ $row->custome_duty_amount }}</td>
        <td>{{ $row->rd_percent }} %</td>
        <td>{{ $row->rd_amount }}</td>
        <td>{{ $row->assessed_value + $row->rd_amount + $row->custome_duty_amount + $row->acd_amount }}</td>
        <td>{{ $row->st_percent }}%</td>
        <td>{{ $row->rd_amount }}</td>
        <td>{{ $row->acd_percent }} %</td>
        <td>{{ $row->acd_amount }}</td>
        <td>{{ $row->it_percent }} %</td>
        <td>{{ $row->it_amount }}</td>
        <td>{{ $row->fed_percent }} %</td>
        <td>{{ $row->fed_amount }}</td>
        <td>{{ $row->ast_percent }} %</td>
        <td>{{ $row->ast_amount }}</td>
        <td>-</td> <!-- CD % IN BOND -->
        <td>-</td> <!-- CD Amount -->
        <td>{{ $row->eto_percent }} %</td>
        <td>{{ $row->do_charges }}</td>
        <td>{{ $row->lolo }}</td>
        <td>{{ $row->port_charges }}</td>
        <td>-</td> <!-- Aviation / Wharfage Chargest -->
        <td>{{ $row->cl_bill_no }}</td>
        <td>-</td> <!-- stamping charge -->
        <td>-</td> <!-- Total -->
        <td>-</td> <!-- Expense -->

    </tr>
@endforeach
