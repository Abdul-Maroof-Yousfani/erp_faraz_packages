<?php
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Budget Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <form action="{{route('Budgetdata')}}"  method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Date</label>
                                                <input class="form-control" max="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d') ?>" name="b_date" id="b_date" type="date"  />
                                                </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Amount</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" id="amount" name="amount" class="form-control requiredField" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">User</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                   <input class="form-control requiredField" name="user"  id="user" disabled value="{{Auth::user()->name}}" />
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadGoodsReceiptNoteDetailSection"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                        <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                    </div>
                                </div>
                               
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection