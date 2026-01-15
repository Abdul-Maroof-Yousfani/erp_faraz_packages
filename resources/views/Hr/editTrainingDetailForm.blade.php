<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

?>


<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                            <b> Add Custom Participants</b>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <input @if($trainingsData->participant_type == 2) checked @endif style="height: 15px;width: 15px;" id="add_custom" type="checkbox" name="">
                        </div>
                    </div>
                </div>
                <div class="lineHeight"></div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/editTrainingDetail',"enctype"=>"multipart/form-data"));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="employeeSection[]" value="1">
                    <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                    <input type="hidden" name="id" value={{ $trainingsData->id }}>
                    <input type="hidden" name="participant_type" id="participant_type" value="{{ $trainingsData->participant_type }}">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Regions:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="region_id" id="region_id">
                                            <option value="">Select Region</option>
                                            @foreach($employee_regions as $key2 => $y2)
                                                <option @if($trainingsData->region_id == $y2->id) selected @endif value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Category:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                                            <option value="">Select Category</option>
                                            @foreach($employee_category as $key2 => $y2)
                                                <option @if($trainingsData->employee_category_id == $y2->id) selected @endif value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="sf-label">Participants:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right"></div>
                                        @if($trainingsData->participant_type == 1)
                                            <span id="custom_area">
                                                <select class="form-control requiredField" name="participants_name[]" id="participants_name" multiple aria-multiselectable="true" required>
                                                    <?php $emp_Array = explode(',',$trainingsData->participants); ?>
                                                        @foreach($employee as $value1)
                                                            @foreach($emp_Array as $value2)
                                                                <option @if($value1->emr_no == $value2) selected @endif value="{{ $value1->emr_no }}">EMR-No: {{ $value1->emr_no }}.---.{{ $value1->emp_name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                </select>
                                                <div id="emp_loader"></div>
                                            </span>
                                        @endif

                                        @if($trainingsData->participant_type == 2)
                                            <span id="custom_area">
                                                <input class="form-control requiredField" type="text" name="participants_name" id="participants_name" value="{{ $trainingsData->participants }}" placeholder="Write here" required/>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Trainer Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="trainer_name" id="trainer_name" class="form-control requiredField" value="{{$trainingsData->trainer_name}}">
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Certificate Number</label>
                                        <span class="rflabelsteric"><strong></strong></span>
                                        <input type="text" name="certificate_number" id="certificate_number" class="form-control" value="{{$trainingsData->certificate_number}}">
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 9px;">
                                        <label>Certificate uploading</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="file" name="certificate_uploading[]" id="certificate_uploading[]" class="form-control" multiple>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Location</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="location_id" id="location_id" required>
                                            <option value="">Select Location</option>
                                            @foreach($employee_locations as $key2 => $y2)
                                                <option @if($trainingsData->location_id == $y2->id) selected @endif value="{{ $y2->id}}">{{ $y2->employee_location}}</option>
                                            @endforeach
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Training Date</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required class="form-control requiredField" type="date" name="training_date" id="training_date" value="{{ $trainingsData->training_date }}" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Topic Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required class="form-control requiredField" type="text" name="topic_name" id="topic_name" value="{{ $trainingsData->topic_name }}"/>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="submit" class="btn btn-success" value="Update" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="run_loader"></div>
                    <div class="employeePayslipSection"></div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@if($trainingsData->participant_type == 1)
<script>

    $(document).ready(function () {

        $('#participants_name').select2();

    });
</script>
@endif
<script>

    $(document).ready(function () {
        $('#region_id').select2();
        $('#emp_category_id').select2();

        $('#location_id').select2();
    });


    $(function(){
        $('select[name="emp_category_id"]').on('change', function() {
            var emp_category_id = $(this).val();
            var region_id = $("#region_id").val();
            if(region_id == ''){alert('Please Select Region !');return false;}
            var m = '<?= Input::get('m'); ?>';
            if(emp_category_id) {
                $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                    type: "GET",
                    data: { emp_category_id:emp_category_id,region_id:region_id,m:m},
                    success:function(data) {
                        $('#emp_loader').html('');
                        $('select[id="participants_name"]').empty();
                        $('select[id="participants_name"]').html(data);
                    }
                });
            }else{
                $('select[id="participants_name"]').empty();
            }
        });

        $( "#add_custom" ).click(function() {
            if ($('#add_custom').is(':checked')) {
                $("#custom_area").html('<input class="form-control requiredField" type="text" name="participants_name" id="participants_name" placeholder="Write here" required/>');
                $("#participant_type").val(2);
            }
            else{
                $("#custom_area").html('<select class="form-control requiredField" name="participants_name[]" id="participants_name" multiple aria-multiselectable="true" required>' +
                    '</select><div id="emp_loader"></div>');
                $("#participant_type").val(1);
                $("#participants_name").select2();
                var emp_category_id = $("#emp_category_id").val();
                var region_id = $("#region_id").val();
                if(region_id == ''){alert('Please Select Region !');return false;}
                var m = '<?= Input::get('m'); ?>';
                if(emp_category_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                        type: "GET",
                        data: { emp_category_id:emp_category_id,region_id:region_id,m:m},
                        success:function(data) {
                            $('#emp_loader').html('');
                            $('select[id="participants_name"]').empty();
                            $('select[id="participants_name"]').html(data);
                        }
                    });
                }else{
                    $('select[name="emr_no"]').empty();
                }
            }
        });


    });


</script>




