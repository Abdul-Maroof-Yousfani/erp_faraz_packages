@extends('layouts.default')
@section('content')

<?php

$company = DB::table('menu_privileges as a')
        ->join('company as b', 'a.compnay_id', '=', 'b.id')
        ->where('a.emp_code',Auth::user()->emp_code)
        ->select('b.name', 'b.id')
        ->groupBy('a.compnay_id')
        ->get();

?>
<div class="well" >
@foreach($company as $row)
    @if(Session::get('run_company')!=$row->id)
  <a  href="{{url('/set_company/'.$row->id)}}">  <h3> <span  class="label label-success">{{$row->name}}</span></h3></a>
@endif
@endforeach
    </div>


@endsection
