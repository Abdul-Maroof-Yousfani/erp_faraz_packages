@php
use App\Helpers\CommonHelper;

    $clearing_agent = DB::connection('mysql2')->table('clearing_agent_member')->where('status', 1);
    if ($request->agent_id) {
        $clearing_agent = $clearing_agent->where('id', $request->agent_id);
    }
    $clearing_agent = $clearing_agent->get();
@endphp
@foreach ($clearing_agent as $value)
    @php
        

        $data = DB::connection('mysql2')
            ->table('bl_details as b')
            ->join('lc_and_lg_against_po as lc', 'lc.id', '=', 'b.lc_and_lg_against_po_id')
            ->join('shipping_expenses as s', 's.lc_and_lg_against_po_id', '=', 'lc.id')
            ->join('clearing_agents as c', 'c.lc_and_lg_against_po_id', '=', 'lc.id')
            ->join('purchase_request as p', 'p.id', '=', 'lc.po_id')
            ->select(
                'lc.refrence_no',
                'b.fwd',
                'b.bl_no',
                'b.bl_date',
                'b.receving_date_factory',
                's.amount',
                'b.line',
                'b.lot_no',
                'c.clearing_agent_no',
                'c.shipment_clearing_days',
            )
            ->where('c.clearing_agent_no', $value->id)
            ->orderBy('lc.id', 'desc')
            ->get();
        // dd($data);

        $grand_total = 0;
    @endphp

    @php
        $counter = 1;
    @endphp
    @if ($data)
        
        @foreach ($data as $row)
            <tr>
                <td>{{ $row->refrence_no }}</td>
                <td>{{ $row->line }} </td>
                <td>{{ $row->fwd }} </td>
                <td>{{ $row->lot_no }} </td>
                <td>{{ $row->bl_no }}</td>
                <td>{{ $row->bl_date }}</td>
                <td>{{ $row->receving_date_factory }}</td>
                @php
                    $datetime1 = new DateTime($row->receving_date_factory);
                    $datetime2 = new DateTime();
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                    // dd($interval->format('%a'));
                @endphp
                {{-- <td>{{ $days }}</td> --}}
                
                <td>{{ $row->shipment_clearing_days }}</td>
                <td>{{ number_format($row->amount , 2) }}</td>
                @php
                    $grand_total += $row->amount;
                @endphp
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Clearing Agent</th>
            <th colspan="5">{{$value->agent_name}}</th>
            <th colspan="1">Grand Total</th>
            <th colspan="1">{{$grand_total}}</th>
        </tr>
    @endif
@endforeach
