<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m = Input::get('m');
use App\Helpers\CommonHelper;
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Buildings Form</span>
                    </div>
                </div>
                <div class="row">
                    {{ Form::open(array('url' => 'had/addBuildingsDetail','id'=>'employeeForm')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="m" value="{{ $m }}">
                    <input type="hidden" name="assetsSection[]" class="form-control" id="assetsSection" value="1" />
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Project Name / Code</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="project_id" id="project_id" class="form-control requiredField">
                                            <option value="">Select Project</option>
                                            @foreach($employee_project as $key1 => $val1)
                                                <option value="{{ $val1->id }}">{{ $val1->project_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Region</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select name="region_id" id="region_id" class="form-control requiredField">
                                            <option value="">Select Region</option>
                                            @foreach($regions as $key1 => $val1)
                                                <option value="{{ $val1->id }}">{{ $val1->employee_region }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Premise Name</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="building_name[]" id="building_name" value="" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Premise Code</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" name="building_code[]" id="building_code" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="employeeSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <button class="btn btn-primary addMoreBuildingsSection">Add More Buildings</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {

            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='assetsSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }


            });
            var counter = 1;
            $('.addMoreBuildingsSection').click(function (e){
                e.preventDefault();
                counter++;
                $('.employeeSection').append('<div id="sectionAssets_'+counter+'">' +
                        '<a href="#" onclick="removeAssetsSection('+counter+')" class="btn btn-xs btn-danger">Remove</a>' +
                        '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                        '<div class="row">' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Building Name</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" name="building_name[]" id="building_name" value="" /></div>' +
                        '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="sf-label">Building Code</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" class="form-control requiredField" name="building_code[]" id="building_code" value="" />' +
                        '</div></div></div></div></div>');

            });

            $('#region_id').select2();
            $('#project_id').select2();

        });

        function removeAssetsSection(id){
            var elem = document.getElementById('sectionAssets_'+id+'');
            elem.parentNode.removeChild(elem);
        }

    </script>

    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection