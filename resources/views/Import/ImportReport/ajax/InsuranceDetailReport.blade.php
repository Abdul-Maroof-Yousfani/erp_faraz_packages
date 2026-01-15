@php
    use  App\Helpers\CommonHelper;
    

    $data = DB::connection('mysql2')->table('insurence_details as i')
    ->join('lc_and_lg_against_po as lc' , 'lc.id' ,'=', 'i.lc_and_lg_against_po_id')
    ->join('purchase_request as p' ,'p.id','=','lc.po_id')
    ->select('p.purchase_request_no' ,'i.policy_company','lc.lc_no', 'i.lot_no' ,'lc.refrence_no','i.cover_note','i.cover_note_date','i.tolerance' , 'i.policy_no', 'i.policy_date', 'i.policy_amount' , 'i.remarks','i.policy_status')
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
    <td>{{$row->policy_company}}     </td>
    <td>{{$row->lc_no}}</td>
    <td>{{$row->refrence_no}}  </td>
    <td>{{$row->lot_no}}</td>
    <td>{{$row->cover_note}}</td>
    <td>{{$row->cover_note_date}}  </td>
    <td>{{$row->tolerance}}</td>
    <td>{{$row->policy_no}}</td>
    <td>{{$row->policy_date}} </td>
    <td>{{$row->policy_amount}} </td>
    <td>{{$row->policy_status}}</td>
</tr>
@endforeach