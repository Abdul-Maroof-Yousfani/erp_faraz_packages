<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>

@extends('layouts.default')

@section('content')
  

    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Budget List</span>
                            </div>

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printPurchaseRequestVoucherList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered sf-table-list" id="data_table">
                                                            <thead>
                                                            <th class="text-center">SNO.</th>
                                                            
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center">Created By</th>
                                                            </thead>
                                                            <?php $Counter = 1;?>
                                                        @foreach ($data as $budgetData)
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $Counter++;?></td>
                                                                    
                                                                    <td class="text-center">{{ date('d-m-Y', strtotime($budgetData->date))}}</td>
                                                                    <td class="text-center">{{ $budgetData->amount}}</td>
                                                                    <td class="text-center">{{ $budgetData->user}}</td>
            
                                                                </tr>
                                                            </tbody>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request List (Office Use)'))!!} ">
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