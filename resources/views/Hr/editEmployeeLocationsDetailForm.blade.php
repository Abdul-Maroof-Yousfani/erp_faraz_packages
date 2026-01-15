<?php
$currentDate = date('Y-m-d');
$m = Input::get('m');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editEmployeeLocationsDetail?m='.$m.''));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="<?php echo $location->id?>" />
                        <input type="hidden" name="employeeLocationsSection[]" class="form-control" id="employeeLocationsSection" value="1" />
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="pointer sf-label">Region</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" id="region_id" name="region_id">
                                            <option value="">Select Region</option>
                                            @foreach($region as $key => $val)
                                                <option @if($val->id == $location->region_id) selected @endif value="{{ $val->id}}">{{ $val->employee_region}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Location</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="employee_location" id="employee_location" value="{{ $location->employee_location }}" class="form-control requiredField" />
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
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript">

    $(function(){

        $(".btn-success").click(function(e){
            var employeeLocations = new Array();
            var val;
            $("input[name='employeeLocationsSection[]']").each(function(){
                employeeLocations.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employeeLocations) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

        $("#region_id").select2();
        $("#employee_project_id").select2();
    });

</script>