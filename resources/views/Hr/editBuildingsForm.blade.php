<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m = Input::get('m');

?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<div class="well">
    <div class="row">
        {{ Form::open(array('url' => 'had/editBuildingsDetail?m='.$m.'')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" id="id" value="{{ $buildings->id }}" />
        <input type="hidden" name="m" value="{{ $m }}">
        <input type="hidden" name="assetsSection[]" id="assetsSection" value="1" />
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Project Name / Code</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <select name="project_id" id="project_id" class="form-control requiredField">
                            <option value="">Select Project</option>
                            @foreach($employee_project as $key1 => $val)
                                <option @if($buildings->project_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->project_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Region</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <select name="region_id" id="region_id" class="form-control requiredField">
                            <option value="">Select Region</option>
                            @foreach($regions as $key1 => $val)
                                <option @if($buildings->region_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->employee_region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Premise Name</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" class="form-control requiredField" name="building_name" id="building_name" value="{{ $buildings->building_name }}" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Premise Code</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" class="form-control requiredField" name="building_code" id="building_code" value="{{ $buildings->building_code }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">

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

        $('#project_id').select2();
        $('#region_id').select2();
    });

</script>
