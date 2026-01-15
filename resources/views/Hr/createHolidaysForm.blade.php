<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$month_year = explode('-', date('Y-m-d'));
$currentDate = date('Y-m-d');
$m = Input::get('m');
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Calender Year:</label>
                                <select name="year" id="year" class="form-control requiredField" onchange="viewHolidayCalender();">
                                    <?php
                                    for($i = date("Y")-1; $i <=date("Y")+3; $i++){ ?>
                                    <option @if($month_year[0] == $i) selected @endif value={{ $i }}>Year {{ $i }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <div id="calender"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#year').select2();
            viewHolidayCalender();
        });

        function viewHolidayCalender()
        {
            var month_year = $('#year').val();
            var m = '{{ $m }}';
            $('#calender').html('<div class="loading"></div>');
            $.ajax({
                url: '<?php echo url('/')?>/hdc/viewHolidayCalender',
                type: "GET",
                data: {month_year:month_year, m:m},
                success: function (data) {
                    $('#calender').html(data);
                },
                error: function () {
                    console.log("error");
                }
            });
        }

        function functionModal(param1)
        {
            showDetailModelTwoParamerter("hdc/viewHolidaysDetail",param1,"View Holiday Detail","{{ $m }}");
        }
        function addHolidaysDetail(day, month, year)
        {
            var holiday_date = year+'-'+month+'-'+day;
            var holiday_reason = prompt('Holiday Reason');
            var company_id = '{{ $m }}';
            if(!holiday_reason)
            {
                alert('Holiday Reason Required !');
                return false;
            }
            else if(holiday_reason != '')
            {
                $.ajax({
                    url : ''+baseUrl+'/had/addHolidaysDetail',
                    type: "GET",
                    data: {day:day, month:month, year:year, holiday_reason:holiday_reason, holiday_date:holiday_date, company_id:company_id},
                    success: function (data) {
                        location.reload();
                    },
                    error: function () {
                        console.log("error");
                    }
                });
            }
        }
    </script>
@endsection

