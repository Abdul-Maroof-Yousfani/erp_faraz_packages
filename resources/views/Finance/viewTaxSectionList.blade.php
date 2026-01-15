<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

?>
@extends('layouts.default')

@section('content')
    <?php  $_SERVER['REMOTE_ADDR']; ?>
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Tax Section</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table id="myTable" class="table table-bordered sf-table-list">
                                                    <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Code</th>
                                                    <th class="text-center">Section</th>
                                                    <th class="text-center">Tax Payment Nature</th>
                                                    <th class="text-center">Rate</th>
                                                    <th class="text-center">Tax Payment Section</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($taxSection as $key => $y)
                                                        <tr>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo $y->code;?></td>
                                                            <td class="text-center"><?php echo $y->section;?></td>
                                                            <td class="text-center"><?php echo $y->tax_payment_nature;?></td>
                                                            <td class="text-center"><?php echo $y->rate;?></td>
                                                            <td class="text-center"><?php echo $y->tax_payment_section;?></td>
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

@endsection