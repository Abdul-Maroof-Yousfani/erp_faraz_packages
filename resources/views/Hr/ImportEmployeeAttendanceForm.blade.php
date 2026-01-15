<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
?>

@extends('layouts.default')
@section('content')
    <style>
        .upload {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
        }

        .upload + label {
            width: 200px;
            height:30px;
            font-size: 1em;
            font-weight: 500;
            color: white;
            line-height: 2.6em;
            text-transform: uppercase;
            text-align: center;
            background-color: #ef494f;
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0px 3px 0px #a73337;
            transition: 150ms;
        }

        .upload:focus + label,
        .upload + label:hover {
            background-color: #ff6c71;
            box-shadow: 0px 5px 0px #d03338;
            cursor:pointer;
        }

        .upload:focus + label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

    </style>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Import Employee Attendance</span>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php echo Form::open(array('class'=>'form-horizontal','url' => 'had/ImportEmployeeAttendanceDetail','id'=>'','enctype'=>'multipart/form-data'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="m" value="<?= Input::get('m')?>">
                                    <fieldset>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                                            <div class="form-group">
                                                <label>CSV File</label>
                                                <input onChange='getoutput()' type="file" name="file" id="file" class="upload">
                                                <label for="file">Choose a file</label>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="submit" name="Import" class="btn btn-success button-loading" data-loading-text="Loading...">Import</button>
                                                <p id="extension_err_messg" style="color:red;"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    </fieldset>
                                <?php echo Form::close();?>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        function getoutput(){
          var file_extension = file.value.split('.')[1];
          if(file_extension == 'csv')
          {

              $("#extension_err_messg").html('');
              $("#submit").removeAttr('disabled');
          }
          else
              {

                  $("#submit").attr('disabled','disabled');
                  $("#extension_err_messg").html('Please Select Csv File !');
              }

        }
    </script>

@endsection