<?php
$accType = Auth::user()->acc_type;

if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class='headquidd'>
                       <span class="subHeadingLabelClass">Upload Journal Voucher</span>
                       </div>
                    </div>
                </div>


                <div class="row">
                    {{ Form::open(['url' => '/uploadJournalVoucher?m='.$m.'', 'id' => 'bankPaymentVoucherForm', 'files' => true]) }}

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">




                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="sf-label">Upload JV Excel file </label>
                                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                            <input type="file"  name="jvexcel" id="jvexcel" style="resize:none;" class="form-control requiredField">

                                            <p style="margin-top: 10px; font-size: 14px;">Please download the sample Excel file to see the required format:</p>
                                            <a href="{{ url('/public/format.xlsx') }}" class="btn btn-primary" download>Download Sample Excel File</a>

                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="
                                            display: flex;
                                            flex-direction: row;
                                            flex-wrap: nowrap;
                                            align-items: center;
                                            height: 102px;
                                        ">
                                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{ Form::close() }}

            </div>
            </div>
        </div>
    </div>

@endsection
