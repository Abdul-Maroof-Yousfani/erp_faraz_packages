@php
    use  App\Helpers\CommonHelper;
    
    $data = DB::connection('mysql2')->table('maturity_details as m')
    ->join('lc_and_lg_against_po as lc' , 'lc.id' ,'=', 'm.lc_and_lg_against_po_id')
    ->join('bl_details as b' , 'b.lc_and_lg_against_po_id' ,'=', 'lc.id')
    ->join('purchase_request as p' ,'p.id','=','lc.po_id')
    // ->join('purchase_request_data as p_d' ,'p_d.master_id','=','p.id')
    ->select( DB::raw('(SELECT SUM(pd.purchase_approve_qty) from  purchase_request_data as pd where pd.master_id = p.id) as qty'), 
    'p.purchase_request_no' , 'lc.lc_no', 'lc.lc_date', 'b.lot_no' , 'p.supplier_id','lc.description','m.curreny_id','m.bank_doc_amount','m.rate','m.pkr','lc.applicant_bank',
    'b.bl_date','m.days','m.maturity_date','b.receving_date_factory','b.shipment_status' ,'b.port_of_loading', 'm.remarks')
    ->orderBy('lc.id', 'desc')
    ->get();
    // dd($data);
@endphp

@php
$counter = 1;  
@endphp

@foreach ($data as $row) 
<tr>
    <td>{{$counter++}}</td>
    <td>{{$row->purchase_request_no}}</td>
    <td>{{$row->lc_no}}</td>
    <td>{{$row->lc_date}}</td>
    <td>{{$row->lot_no}}</td>
    <td>{{CommonHelper::get_supplier_name($row->supplier_id)}}</td>
    <td>{{$row->description}}</td>
    <td>{{$row->qty}}</td>
    <td>{{CommonHelper::get_curreny_name($row->curreny_id)}}</td>
    <td>{{$row->bank_doc_amount}}</td>
    <td>{{$row->rate}}</td>
    <td>{{$row->pkr}}</td>
    <td>{{CommonHelper::get_applicant_bank_name($row->applicant_bank)}}</td>
    <td>{{$row->bl_date}}</td>
    <td>{{$row->days}}</td>
    <td>{{$row->maturity_date}}</td>
    <td>{{$row->receving_date_factory}}</td>
    <td>{{$row->remarks}}</td>
    <td>{{$row->port_of_loading}}</td>
    <td>{{$row->shipment_status}}</td>
</tr>
@endforeach