@php
    use  App\Helpers\CommonHelper;
    

    $data = DB::connection('mysql2')->table('clearing_agents as c')
    ->join('lc_and_lg_against_po as lc' , 'lc.id' ,'=', 'c.lc_and_lg_against_po_id')
    ->join('purchase_request as p' ,'p.id','=','lc.po_id')
    ->select('p.purchase_request_no'  , 'c.clearing_agent_no','lc.lc_no', 'c.lot_no' ,'lc.refrence_no','c.bill_no','c.bill_date' , 'c.amount' , 'c.deduction' , 'c.paid_amount' , 'c.remarks')
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
    <td>{{$row->clearing_agent_no}}     </td>
    <td>{{$row->lc_no}}</td>
    <td>{{$row->refrence_no}}    </td>
    <td>{{$row->lot_no}}</td>
    <td>{{$row->bill_no}}</td>
    <td>{{$row->bill_date}}</td>
    <td>{{$row->amount}}</td>
    <td>{{$row->deduction}}</td>
    <td>{{$row->paid_amount}}</td>
    <td>{{$row->remarks}}</td>
</tr>
@endforeach