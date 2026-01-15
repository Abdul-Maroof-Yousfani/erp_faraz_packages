<?php

$m = Session::get('run_company');
use App\Helpers\NotificationHelper;
use App\Helpers\Commonhelper;
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <form method="post" action="{{ url('users/insert_notifications') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row well_N">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">Add Notifications Department Wise </span>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <input type="hidden" name="employeeSection[]">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Steps</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="get_all_step();get_data()" class="form-control requiredField" name="setps" id="setps">
                                            <option value="">Select</option>
                                            @foreach(NotificationHelper::get_all_steps() as $key2 => $row)
                                                <option value="{{ $row->id}}">{{ $row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Behavior</label>
                                        <select onchange="get_data()" class="form-control"  name="behavior" id="behavior">
                                         
                                       
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="get_data()"  class="form-control requiredField" name="dept" id="dept">
                                            <option value="">Select</option>
                                            @foreach(Commonhelper::get_all_sub_department() as $key2 => $row)
                                                <option value="{{ $row->id}}">{{ $row->sub_department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Type</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="get_data()"  class="form-control requiredField" name="v_type" id="v_type">
                                            <option value="">Select</option>
                                            @foreach(NotificationHelper::get_all_type() as $key2 => $row)
                                                <option value="{{ $row->id}}">{{ $row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                               
                                   
                                </div>
                            </div>
                        </div>
                        <div id="data"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <script>

        $(document).ready(function() {

            $(".btn-primary").click(function(e){
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
            $('.emp_code').select2();
            $('#region_id').select2();
            $('#department_id').select2();

        });


        function get_data(){
            var steps  = $('#setps').val();
            var behavior  = $('#behavior').val();
            var dept  = $('#dept').val();
            var v_type  = $('#v_type').val();
           
           if (steps=='' || behavior=='' || dept=='' || v_type=='')
           {
            return false;
           }
                 
        
            $('#data').html('<div class="loading"></div>');
         
            $.ajax({
                type:'GET',
                url:'{{ url('users/get_notification_data') }}',
                data:{steps:steps,behavior:behavior, dept:dept,v_type:v_type},
                success:function(res)
                {
                    $('#data').html(res);

                }
            });
         
        }

        function get_all_step(){
            var steps = $("#setps").val();
          
            if (steps=='')
            {
                return false;
            }

                $('#behavior').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    type:'GET',
                    url:'{{ url('/users/get_behavior') }}',
                    data:{steps:steps},
                    success:function(res){
                 
                        $('#behavior').html('');
                      //  $('select[name="emp_code"]').empty();
                        $('#behavior').html(res);
                    //    $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
                    }
                });
            }
          
        

    </script>

@endsection