<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Upload Overtime And Fuel File</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/uploadOvertimeAndFuelFile','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="{{ $m }}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id">
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Category:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select readonly="readonly" class="form-control requiredField" name="emp_category_id" id="emp_category_id">
                                                    <option value="">Select Category</option>
                                                    @foreach($employee_category as $key2 => $y2)
                                                        <option @if($y2->id == 3) selected @endif value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label>Month</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" name="month_year" id="month_year" class="form-control requiredField" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label>File</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="file" name="overtimeAndFuelFile" id="overtimeAndFuelFile" value="" class="form-control requiredField" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 32px">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                                                <h3>Sheet Sequence => OT ,Fuel ,Driver</h3>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="text-center">
                                                    <h2><a  href="<?=url('/')?>/assets/sample_images/ot_and_fuel_sample_file.xlsx">Download Sample / Format </a></h2>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="lineHeight"></div>
                                        <div class="employeeSection"></div>
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

        $(document).ready(function() {

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

            $('#emp_category_id').select2();
            $('#region_id').select2();

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
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $('select[name="emr_no"]').find('option').get(0).remove();
                            $('select[name="emr_no"]').prepend("<option value='0'>Select Employee</option>").val('');

                        }
                    });
                }else{
                    $('select[name="emr_no"]').empty();
                }
            });
        });




    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection