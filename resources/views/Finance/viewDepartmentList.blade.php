<?php
use App\Helpers\FinanceHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
<div class="well_N">
    <div class="dp_sdw">  
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Department List</span>
                                </div>
                            </div>
                            <hr>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Code</th>
                                                            <th class="text-center">Department</th>
                                                            <th class="text-center">Date</th>


                                                            </thead>


                                                            <tbody id="">
                                                            <?php $count=1; ?>
                                                        @foreach($department as $row)

                                                            <?php $array = explode('-',$row->code);
                                                            $level = count($array);
                                                            $nature = $array[0]; ?>
                                                            <tr>
                                                                <td class="text-center">{{$count++}}</td>
                                                               <td class="text-center">{{$row->code}}</td>
                                                                <td  style="text-transform: capitalize">
                                                                    @if($level == 1)
                                                                        {{ $row->name}}
                                                                    @elseif($level == 2)
                                                                        {{ '&emsp;&emsp;'. $row->name}}
                                                                    @elseif($level == 3)
                                                                        {{ '&emsp;&emsp;&emsp;&emsp;'. $row->name}}
                                                                    @elseif($level == 4)
                                                                        {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name}}
                                                                    @elseif($level == 5)
                                                                        {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name}}
                                                                    @elseif($level == 6)
                                                                        {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name}}
                                                                    @elseif($level == 7)
                                                                        {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name}}
                                                                    @endif


                                                                </td>
                                                                <td class="text-center">{{FinanceHelper::changeDateFormat($row->date)}}</td>



                                                            </tr>

                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection