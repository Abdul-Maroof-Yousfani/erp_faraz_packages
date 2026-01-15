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
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">AQMS STOCK REPORT</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnOne" onclick="GetReport()">CI GATE VALVE (RISING OS&Y) F/T PN16</button>
                                <input type="hidden" id="ValueOne" value="31,32,33,34,35,5,37,4,3,73,72,41,42,43">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnTwo">CI GLOBAL VALVE BELLOW SEAL F/T PN16</button>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnThree">CI GLOBAL VALVE BELLOW SEAL F/T PN40</button>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnFour">CI Y STRAINER VALVE F/T PN16</button>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnFive">CI Y CHECK VALVE (SWING TYPE) F/T PN16</button>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnSix">CI BUTTERFLY VALVE (LEVER) SS DISC</button>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnSeven">CI BUTTERFLY VALVE (GEAR) SS DISC</button>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnEight">CI Y CHECK VALVE (DUAL PLATE) SS DISC F/T PN16</button>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary" id="BtnNine">RUBBER BELLOW MS FLANGE GALVANISED</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AjaxData">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script !src="">
        function GetReport()
        {


                var ItemIds = $('#ValueOne').val();
                var m = '<?php echo $_GET['m']?>';
                $('#AjaxData').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/pdc/getReportMultiItems',
                    method:'GET',
                    data:{ItemIds:ItemIds,m:m},
                    error: function(){
                        alert('error');
                    },
                    success: function(response){
                        $('#AjaxData').html(response);
                    }
                });


        }
    </script>
@endsection