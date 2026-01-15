<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$letterTypeArray[1]= 'Warning Letter';
$letterTypeArray[2]= 'MFM South Increment Letter';
$letterTypeArray[3]= 'MFM South Without Increment Letter';
$letterTypeArray[4]= 'Contract Conclusion Letter';
$letterTypeArray[5]= 'Termination Letter Format 1';
$letterTypeArray[6]= 'Termination Letter Format 2';
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <style>
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
    </style>
    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <span class="subHeadingLabelClass">Upload Letters File</span>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <?php echo Form::open(array('url' => 'had/AddLettersFile','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?=$m?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Regions:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="region_id" id="region_id">
                                    <option value="">Select Region</option>
                                    @foreach($employee_regions as $key2 => $y2)
                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Category:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id"  onchange="empCategory()">
                                    <option value="">Select Category</option>
                                    @foreach($employee_category as $key2 => $y2)
                                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Employee Project</label>
                                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                    <option value="0">Select Project</option>
                                    @foreach($Employee_projects as $value)
                                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emr_no" id="emr_no" required ></select>
                                <div id="emp_loader"></div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Letter:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="letter_type" id="letter_type" required>
                                    <option value="">Select Letter</option>
                                    <option value="1">Warning Letter</option>
                                    <option value="2">MFM South Increment Letter</option>
                                    <option value="3">MFM South Without Increment Letter</option>
                                    <option value="4">Contract Conclusion Letter</option>
                                    <option value="5">Termination Letter Format 1</option>
                                    <option value="6">Termination Letter Format 2</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">File:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input class="form-control requiredField" type="file" name="letter_file" id="letter_file" required>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="margin-top: 30px">
                                <button id="do" type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>

                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="row text-center">
                                    <h3><b><u>Uploaded Letters List</u></b></h3>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list table-hover" id="TaxesList">
                                        <thead>
                                        <th class="text-center col-sm-1">S.No</th>
                                        <th class="text-center">Emr no</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">letter Type</th>
                                        <th class="text-center">File Type</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center col-sm-2 hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter =1 ; ?>
                                        @foreach($uploaded_letters_list as $value)
                                            <tr>
                                                <td class="text-center">{{$counter++}}</td>
                                                <td class="text-center">{{$value['emr_no']}}</td>
                                                <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $value['emr_no'], 'emr_no') }} </td>
                                                <td class="text-center">{{$letterTypeArray[$value['letter_type']]}}</td>
                                                <td class="text-center">{{$value['file_type']}}</td>
                                                <td class="text-center">{{HrHelper::date_format($value['date'])}}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('view', $operation_rights))
                                                                <li role="presentation">
                                                                    <a class="delete-modal btn" onclick="showMasterTableEditModel('hdc/viewHrLetterFiles','<?=$value['id']?>','View Hr Letters Documents','<?=$m?>')">View</a>
                                                                </li>
                                                            @endif

                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value['status']== 1)
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value['id'] ?>','letter_files')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close();?>
    </div>

    <script>

        $(function () {

            $('select[name="employee_project_id"]').on('change', function() {
                var region_id = $('#region_id').val();
                var project_id = $(this).val();
                var m = '{{ Input::get('m') }}';
                if(region_id) {
                    $('.building_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getBuildingsList',
                        type: "GET",
                        data: { region_id:region_id,project_id:project_id,m:m},
                        success:function(data) {
                            $('.building_loader').html('');
                            $('select[name="building_id"]').empty();
                            $('select[name="building_id"]').html(data);
                        }
                    });
                }else{
                    alert('Select Region');
                    $('.building_loader').html('');
                    $('select[name="building_id"]').empty();
                }
            });
        });


        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function (e) {
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function () {
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                    } else if (validate == 1) {
                        return false;
                    }
                }

            });
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#emr_no').select2();
            $('#letter_type').select2();
            $('#employee_project_id').select2();
            $("#building_id").select2();
        });


        function employeeProject() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var employee_project_id = $("#employee_project_id").val();
            var building_id = $("#building_id").val();
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
                            building_id:building_id,
                            m: m
                        },
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
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
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        
    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection