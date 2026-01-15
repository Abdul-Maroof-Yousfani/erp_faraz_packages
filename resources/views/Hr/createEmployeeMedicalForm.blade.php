<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
@include('select2')
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="panel">
                    <div class="panel-body">
                        <?php echo Form::open(array('url' => 'had/addEmployeeMedicalDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                            <input type="hidden" name="medicalSection[]" value="1">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Regions:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                                        <option value="">Select Region</option>
                                        @foreach($employee_regions as $key2 => $y2)
                                            <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Department:</label>
                                    <select class="form-control" name="department_id" id="department_id" onchange="filterEmployee()">
                                        <option value="">Select Department</option>
                                        @foreach($employee_department as $key2 => $y2)
                                            <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Employee:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" ></select>
                                    <span id="emp_loader"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Disease Type:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="disease_type_id" id="disease_type_id" required>
                                        <option value="">Select Disease</option>
                                        @foreach($disease as $key => $y)
                                            <option value="{{ $y->id}}">{{ $y->disease_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Date:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" name="disease_date" id="disease_date" class="form-control requiredField" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Amount:</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Cheque Number:</label>
                                    <input type="text" name="cheque_number" id="cheque_number" class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">File Upload:</label>
                                    <input type="file" name="medical_file_path[]" id="medical_file_path" class="form-control" multiple>
                                </div>
                            </div>
                            <br>
                            <div style="float: right">
                                <button class="btn btn-success">Submit</button>
                            </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='medicalSection[]']").each(function(){
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

        $('.emp_code').select2();
        $('#region_id').select2();
        $('#disease_type_id').select2();
        $('#department_id').select2();
    });

    function filterEmployee(){
        var region_id = $("#region_id").val();
        var department_id = $("#department_id").val();
        var m = "{{ Input::get('m') }}";
        var url = '{{ url('/') }}/slal/getEmployeeRegionList';
        var data;

        if(region_id != ''){
            data = {region_id:region_id,m:m};
        }
        if(department_id != '' && region_id != ''){
            data = {department_id:department_id,region_id:region_id,m:m};
        }

        if(region_id != ''){
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                type:'GET',
                url:url,
                data:data,
                success:function(res){
                    $('#emp_loader').html('');
                    $('select[name="emp_code"]').empty();
                    $('select[name="emp_code"]').html(res);
                    $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                }
            });
        }
        else{
            $("#department_id").val('');
        }
    }
</script>