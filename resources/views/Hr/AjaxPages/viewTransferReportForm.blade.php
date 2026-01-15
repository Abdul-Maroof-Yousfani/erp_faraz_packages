<?php
use \App\Models\Employee;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><b><u>Transfer Report Form</u></b></h3>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Search By Region:</label>
                <select class="form-control requiredField" name="region_id" id="region_id" >
                    <option value="">Select Region</option>
                    @foreach($regions as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label class="sf-label">Search By Category:</label>
                <select class="form-control requiredField" name="employee_category_id" id="employee_category_id">
                    <option value="">Select </option>
                    @foreach($employee_category as $key2 => $y2)
                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>Employee Project</label>
                <select class="form-control" name="employee_project_id" id="employee_project_id">
                    <option value="0">Select Project</option>
                    @foreach($Employee_projects as $value)
                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label class="sf-label">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date">
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12"></div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 text-right">
                <label>Show All
                    <input type="checkbox" class="checkbox" id="show_all" name="show_all" value="1" style="margin-top: -2px;">
                </label>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                <button class="btn btn-info" id="search">Search</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){

        $('.checkbox').change(function () {
            if ($(this).is(':checked')) {
                $('#employee_category_id').attr('disabled', true);
                $('#employee_project_id').attr('disabled', true);
                $('#region_id').attr('disabled', true);
                $('#from_date').attr('disabled', true);
                $('#to_date').attr('disabled', true);
                $('#employee_category_id').removeClass('requiredField').removeAttr('required');
                $('#region_id').removeClass('requiredField').removeAttr('required');
                $('#from_date').removeClass('requiredField').removeAttr('required');
                $('#to_date').removeClass('requiredField').removeAttr('required');
            } else {
                $('#employee_category_id').attr('disabled', false);
                $('#employee_project_id').attr('disabled', false);
                $('#from_date').attr('disabled', false);
                $('#to_date').attr('disabled', false);
                $('#region_id').attr('disabled', false);
                $('#employee_category_id').addClass('requiredField').attr('required');
                $('#region_id').addClass('requiredField').attr('required');
                $('#from_date').addClass('requiredField').attr('required');
                $('#to_date').addClass('requiredField').attr('required');
            }
        });

        $(".btn-info").click(function(e){
            var degreeType = new Array();
            var val;
            $("input[name='HrReports[]']").each(function(){
                degreeType.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of degreeType) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });


        $('#search').click(function() {
            var employee_category_id = $('#employee_category_id').val();
            var employee_project_id = $('#employee_project_id').val();
            var region_id = $('#region_id').val();
            var show_all = $("input[name='show_all']:checked"). val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var m = '<?php echo Input::get('m'); ?>';
            var data = '';
            if(show_all == '1'){
                 data = {m: m,show_all: show_all};
            }
            else{
                data = { m: m,employee_category_id: employee_category_id,region_id: region_id,show_all: show_all,employee_project_id: employee_project_id,from_date: from_date,to_date:to_date}
            }

                if (validate == 0) {
                    $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    $.ajax({
                        url: "/HrReports/viewTransferReport",
                        type: 'GET',
                        data: data,
                        success: function (response) {
                            $('#report-area').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                            $('#report-area').html(response);

                        }
                    });
                } else {
                    $('#report-area').html('');
                    return false;
                }
        });

        $('#employee_category_id').select2();
        $('#region_id').select2();
        $('#employee_project_id').select2();

    });


</script>