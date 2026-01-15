<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
<style>
    .table > thead:first-child > tr:first-child > th {
        background-color: #133875;
        color: white;
    }
</style>

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Upload Employee File Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/uploadEmployeeFileDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>File</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required type="file" name="employeeFile" id="employeeFile" value="" class="form-control requiredField" />

                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center" style="margin-top: 32px">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                </div>
                                <br>
                                @if(Session::has('errorMsg'))
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;
                                            <div class="alert-danger" style="font-size: 18px"><span class="glyphicon glyphicon-warning-sign"></span><em> {!! session('errorMsg') !!}</em></div>
                                        </div>
                                    </div>
                                @endif
                                <br>
                                <div class="row">
                                    <div class="text-center">
                                        <h2><a  href="<?=url('/')?>/assets/sample_images/employee_sample_file.xlsx">Download Sample / Format </a></h2>
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="TaxesList">
                                                    <thead>
                                                    <th class="text-center">S.no</th>
                                                    <th class="text-center">Employee Name </th>
                                                    <th class="text-center">Father Name </th>
                                                    <th class="text-center">Address</th>
                                                    <th class="text-center">Phone No</th>
                                                    <th class="text-center">Designation</th>
                                                    <th class="text-center">Location/Site </th>
                                                    <th class="text-center">Salary </th>
                                                    <th class="text-center">Emp Code</th>
                                                    <th class="text-center">CNIC</th>
                                                    <th class="text-center">DOB </th>
                                                    <th class="text-center">DOJ </th>
                                                    <th class="text-center">Region </th>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">Noman Hashmat  </td>
                                                        <td class="text-center">Hashmat </td>
                                                        <td class="text-center">House# 664, Sector 12-L, Orangi Town, Karachi  </td>
                                                        <td class="text-center">0332-3284946</td>
                                                        <td class="text-center">HVAC Technician </td>
                                                        <td class="text-center">DR Hafiz + Shahbaz</td>
                                                        <td class="text-center">22000</td>
                                                        <td class="text-center">270</td>
                                                        <td class="text-center">42401-1994217-3</td>
                                                        <td class="text-center">31-10-1991 </td>
                                                        <td class="text-center">2-05-16</td>
                                                        <td class="text-center">South</td>
                                                    </tr>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::close();?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection