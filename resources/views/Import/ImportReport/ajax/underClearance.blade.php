@php
    use App\Helpers\CommonHelper;
    // dd($request);
@endphp
@php
    $data = DB::connection('mysql2')
        ->table('clearing_agents as c')
        ->join('lc_and_lg_against_po as lc', 'lc.id', '=', 'c.lc_and_lg_against_po_id')
        ->join('bl_details as b', 'b.lc_and_lg_against_po_id', '=', 'lc.id')
        ->join('purchase_request as p', 'p.id', '=', 'lc.po_id')
        ->select(
            'p.supplier_id',
            'p.purchase_request_no',
            'lc.lc_no',
            'lc.lc_date',
            'lc.refrence_no',
            'lc.pi_no',
            'lc.description',
            'b.lot_no',
            'lc.origin',
            'b.bl_no',
            'b.line',
            'b.fwd',
            'c.clearing_agent_no',
            'lc.latest_shipment_date',
            'lc.expirty_date',
            'b.bl_date',
            'b.eta',
            'b.container',
            'b.lcl',
            'b.ft_20',
            'b.ft_40',
            'b.packege',
            'b.new',
            'b.gw',
            'b.shipment_status',
            'b.bl_remarks',
        );
    if ($request->from_date) {
        $data = $data->where('b.bl_date', '>=', $request->from_date);
    }
    if ($request->to_date) {
        $data = $data->where('b.bl_date', '<=', $request->to_date);
    }
    if ($request->shipment_status) {
        $data = $data->where('b.shipment_status', $request->shipment_status);
    }
    $data = $data->orderBy('b.eta', 'asc')->get();
    // dd($data);
@endphp
<style>
    th{
        background: #dfe5ec !important;
    }
</style>
<div class="table-responsive" style="overflow: auto;">
    <table class="userlittab table table-bordered sf-table-list tab-cen">
        <thead>
            <tr>
                <th class="text-center">Supplier</th>
                <th class="text-center" style="width: 300px !important;">File # - PO # Lc Number - PI Number - Date</th>
                <th class="text-center">Description Lot #</th>
                <th class="text-center">Origin</th>
                <th class="text-center" style="width: 200px !important;">BL number / AWB Number - Line - Forwarder -
                    Clearing Agent</th>
                <th class="text-center" style="width: 200px !important;">As per Lc</th>
                <th class="text-center">BL / AWB ETD Date</th>
                <th class="text-center">ETA Karachi</th>
                <th class="text-center">No Of Container</th>
                <th class="text-center">Packages</th>
                <th class="text-center">Gross and Net Weight</th>
                <th class="text-center">Shipment status / Remarks</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($data as $row)
                <tr>
                    <td>{{ CommonHelper::get_supplier_name($row->supplier_id) }}</td>
                    <td> FILE : {{ $row->refrence_no }} <br> PO : {{ $row->purchase_request_no }} <br> PI :
                        {{ $row->pi_no }} <br> LC : {{ $row->lc_no }} <br> Date : {{ $row->lc_date }}  <br>  {{ $row->new }} KG</td>
                    <td>{{ $row->description }} <br> LOT : {{ $row->lot_no }}</td>
                    <td>{{ $row->origin }}</td>
                    <td>BL : {{ $row->bl_no }} <br> Line : {{ $row->line }} <br> Fwd : {{ $row->line }} <br>
                        CA : {{ $row->clearing_agent_no ?? 'Not Decided' }}</td>
                    <td>LSD : {{ $row->latest_shipment_date }} <br> ED : {{ $row->expirty_date }}</td>
                    {{-- <td>Latest Shipment : {{$row->latest_shipment_date}} <br> Expiry Date : {{$row->expirty_date}}</td> --}}
                    <td>{{ $row->bl_date }}</td>
                    <td style="color: red; font-family: system-ui;"><strong> {{ $row->eta }} </strong></td>
                    <td>{{ $row->lcl? $row->lcl.'XLCL' : '' }} <br> {{$row->ft_20?$row->ft_20.'X20' : ''}} <br> {{$row->ft_40? $row->ft_40.'X40' :'' }}</td>
                    {{-- <td>{{ $row->container }}</td> --}}
                    <td>{{ $row->packege }}</td>
                    <td>NW : {{ $row->new }} <br> GW : {{ $row->gw }}</td>
                    <td>{{ $row->shipment_status }} {{ $row->bl_remarks ? '/ ' . $row->bl_remarks : '' }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
<script>
    var table = $('.table').DataTable({
        "paging": false,
        "ordering": true,
        "info": false,
        "searching": false, // Enable search feature
        // Add more configurations as needed
    });
</script>
