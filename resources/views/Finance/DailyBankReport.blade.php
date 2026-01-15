<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//  $m = $_GET['m'];
//}else{
//  $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">CASH IN HAND REPORT</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">


                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label for="email">Date</label>
                                                    <input autofocus type="date" class="form-control" value="{{date('Y-m-d')}}" id="date">
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                                    <button type="button" class="btn btn-default" onclick="CashReportDateWise();" style="margin: 30px 0px 0px 0px;">Submit</button>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>

                                                <span id="CashReportDateWiseData"></span>




                                            </div>

                                            <div>&nbsp;</div>

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

    <script type="text/javascript">

        function CashReportDateWise(){
            var date = $('#date').val();
            var m = '<?php echo $_GET['m']?>';
            $('#CashReportDateWiseData').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');

            $.ajax({
                url: '<?php echo url('/')?>/fdc/CashReportDateWise',
                method:'GET',
                data:{date:date,m:m},
                error: function(){

                    alert('error');
                },
                success: function(response){
                    $('#CashReportDateWiseData').html(response);
                }
            });
            //}
        }

        $(document).ready(function() {
            $(".select2").select2();
        });

    </script>
@endsection

