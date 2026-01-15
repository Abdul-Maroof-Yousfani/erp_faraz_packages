<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Location Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeLocationsDetail?m='.$m.'','id'=>'EmployeeLocation'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="companyId" value="<?php echo $m ?>">
                    <input type="hidden" name="EmployeeLocationSection[]" value="1">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="pointer sf-label">Region</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" id="region_id" name="region_id_1">
                                        <option value="">Select Region</option>
                                        @foreach($region as $key => $val)
                                            <option value="{{ $val->id}}">{{ $val->employee_region}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Location</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="employee_location_1" id="employee_location" value="" class="form-control requiredField" />
                                </div>
                            </div>
                            <div class="EmployeeLocationSection"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                            <input type="button" class="btn btn-primary addMoreEmployeeLocationSection" value="Add More Location" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var employeeLocation = new Array();
                var val;
                $("input[name='EmployeeLocationSection[]']").each(function(){
                    employeeLocation.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employeeLocation) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            $("#region_id").select2();

            var location = 1;
            $('.addMoreEmployeeLocationSection').click(function (e){
                e.preventDefault();
                location++;
                $('.EmployeeLocationSection').append('<div id="sectionEmployeeLocation_'+location+'">' +
                        '<div class="lineHeight">&nbsp;</div>' +
                                '<input type="hidden" name="EmployeeLocationSection[]" value="'+location+'">'+
                         '<div class="row"><a href="#" onclick="removeEmployeeLocationSection('+location+')" class="btn btn-sm btn-danger">Remove</a></div>'+
                        '<div class="row">' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                        '<label class="pointer sf-label">Region</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<select class="form-control requiredField" id="region_id" name="region_id_'+location+'">' +
                        '<option value="">Select Region</option>' +
                        '@foreach($region as $key => $val)' +
                        '<option value="{{ $val->id}}">{{ $val->employee_region}}</option>' +
                        '@endforeach' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Location:</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" name="employee_location_'+location+'" id="employee_location[] " value="" class="form-control requiredField" required/></div>' +
                        '</div>');

            });
        });

        function removeEmployeeLocationSection(id){
            var elem = document.getElementById('sectionEmployeeLocation_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection