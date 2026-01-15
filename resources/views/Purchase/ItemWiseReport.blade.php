<?php
use App\Helpers\CommonHelper;
$m = $_GET['m'];
//echo "dsadsa";
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="row">

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label for="">From Date</label>
            <input type="date" name="from" id="from" value="{{date('2020-07-01')}}" class="form-control">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label for="">to Date</label>
            <input type="date" name="to" id="to" value="{{date('Y-m-d')}}" class="form-control">
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="filterByCategoryAndRegion();" style="margin-top: 32px;" />
        </div>
    </div>
    <table id="buildyourform" class="table table-bordered">
        <thead>
        <tr>
            <th>Sub Item</th>
            <th class="text-center" >Total Qty</th>
            <th class="text-center">Total Amount</th>
        </tr>
        </thead>
        <tbody id="data">

        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });


        function filterByCategoryAndRegion()
        {
            var from = $('#from').val();
            var to = $('#to').val();

            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            //return false;
            $.ajax({
                url: '/pdc/ItemWiseReportAjax',
                type: 'Get',
                data: {from: from,to:to,m:m},
                success: function (response) {
                    //alert(response);
                    $('#data').html(response);
                }
            });
        }

    </script>
@endsection

