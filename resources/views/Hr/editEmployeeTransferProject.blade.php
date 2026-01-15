<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Models\Employee;
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <style>
        input[type="radio"], input[type="checkbox"]
        { width:30px;
            height:20px;
        }

        hr{border-top: 1px solid cadetblue}
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}

    </style>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Edit Employee Project Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/ediTransferProject','id'=>'ediTransferProject',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">
                            <input type="hidden" name="employeeSection[]">
                            <input type="hidden" name="transfer_id" id="transfer_id" value="<?php echo $TransferEmployeeProject->id ?>">
                            <input type="hidden" name="emr_no_id" id="emr_no_id" value="<?php echo $TransferEmployeeProject->emr_no ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id" required>
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option <?php if($y2->id == $TransferEmployeeProject->emp_region_id){ echo 'selected';} ?>  value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Category:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id"  onchange="empCategory()" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($employee_category as $key2 => $y2)
                                                        <option <?php if($y2->id == $TransferEmployeeProject->emp_categoery_id){ echo 'selected';} ?> value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label>Employee Project</label>
                                                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()" required>
                                                    <option value="0">Select Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option <?php if($value->id == $TransferEmployeeProject->employee_project_id){ echo 'selected';} ?>  value="{{$value->id}}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emr_no" id="emr_no" required>
                                                <option value="<?php echo $employee->emr_no ?>" selected="selected"><?php echo $employee->emp_name ?></option>
                                                </select>
                                                <div id="emp_loader"></div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Transfer Project:</label>
                                                <select class="form-control requiredField" name="transfer_project_id" id="transfer_project_id" required>
                                                    <option value=""> Select Transfer Project</option>
                                                    @foreach($Employee_projects as $value)
                                                        <option value="{{$value->id}}}">{{$value->project_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>&nbsp;</div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="emp_data"></div>
                                        <div>&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Letter upload</label>
                                                <input type="file" name="letter_uploading[]" id="letter_uploading[]" class="form-control" multiple>
                                            </div>&nbsp;
                                        </div>
                                        <div id="emp_data_loader">&nbsp;</div>
                                        <div class="employeeSection"></div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <?php echo Form::close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>


        function employeeProject() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var employee_project_id = $("#employee_project_id").val();
            if(employee_project_id == '0'){
                empCategory()
            }
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else if (emp_category_id == '') {
                alert('Please Select Cateogery !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (employee_project_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeProjectList',
                        type: "GET",
                        data: {
                            emp_category_id: emp_category_id,
                            region_id: region_id,
                            employee_project_id: employee_project_id,
                            m: m
                        },
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        function empCategory() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (emp_category_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                        type: "GET",
                        data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        $(document).ready(function () {
            $('#location_id').select2();
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#emr_no').select2();
            $('#employee_project_id').select2();
            $("#transfer_project_id").select2();
        });


        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
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

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
