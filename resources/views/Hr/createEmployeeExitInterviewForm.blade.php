<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
    <style>
        .the-period li{
            margin: 2px 0px 6px 0px;
        }
        .name-input{
            width: 200px;
            display: inline-block;
        }
        .gudia-gap{
            margin-top: 36px;
            margin-bottom:25px;
        }

        .name-d-d ul li{
            font-size: 17px;
            margin: 10px 0px 22px 0px;
        }
        .name-d-d-input ul li{
            margin: 7px 0px 10px 0px;
        }
        .depart-row .col-lg-3{
            background-color: #080808;
            color: #fff;
            border-left: 1px solid #fff;
        }
        .title-center {
            padding-top: 66px;
        }
        .concerned-department{
            border: 1px solid #9d9d9d94;
            padding-top: 24px;
            padding-bottom: 20px;
        }
        .depart-row-two .col-lg-4{
            background-color: #999;
            color: #fff;
            border-left: 1px solid #fff;
            padding: 7px 0px 2px 0px;
        }
        .depart-row .exit-inter-parts{
            background-color: #999;
            color: #fff;
        }

        .page-section{
            border: 1px solid #9d9d9d94;
            padding-top: 24px;
            padding-bottom: 20px;
        }
        .gudia-radio:checked,
        .gudia-radio:not(:checked) {
            position: absolute;
            left: -9999px;
        }
        .gudia-radio:checked + label,
        .gudia-radio:not(:checked) + label
        {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            line-height: 20px;
            display: inline-block;
            color: #666;
        }
        .gudia-radio:checked + label:before,
        .gudia-radio:not(:checked) + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            height: 22px;
            border: 1px solid #ddd;
            border-radius: 100%;
            background: #fff;
        }
        .gudia-radio-unsatisfied:checked + label:after,
        .gudia-radio-unsatisfied:not(:checked) + label:after {
            content: '';
            width: 12px;
            height: 12px;
            background: #ce2724;
            position: absolute;
            top: 5px;
            left: 5px;
            border-radius: 100%;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        .gudia-radio-satisfied:checked + label:after,
        .gudia-radio-satisfied:not(:checked) + label:after {
            content: '';
            width: 12px;
            height: 12px;
            background: #3c763d;
            position: absolute;
            top: 5px;
            left: 5px;
            border-radius: 100%;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }

        .gudia-radio:not(:checked) + label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        .gudia-radio:checked + label:after {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1);
        }
        .satisfaction-icon i{
            font-size: 12px;
            margin-left: 10px;
            margin-right: 9px;
            color: #a71126;
        }
        .list-of-satisfied{
            background-color: #ddd;
            padding: 3px 0px 4px 0px;
            margin-top: 10px;
        }
    </style>

<div class="panel-body">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Employee Exit Interview Form</span>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="loader_data"></div>
                            <div id="" class="container gudia-gap">
                                <form  id="subm" method="post" action="{{url('had/addEmployeeExitInterviewDetail')}}">
                                    <div id="data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <figure>
                                                    <img src="assets/img/logo.jpg" class="img-responsive" title="" alt="">
                                                </figure>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-center">
                                                <h2>GUDIA PRIVATE LIMITED</h2>
                                                <h4>EXIT INTERVIEW FORM</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label>Interviewee:</label>
                                                    <select  onchange="viewEmployeeDataExitInterview(this.id)" name="emp_id" id="emp_id"  class="form-control requiredField">
                                                        <option value="">Select</option>
                                                        @foreach($employee as $row)
                                                            <option value="{{$row['id']}}">{{$row['emp_name']}}</option>
                                                        @endforeach;
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label>Designation:</label>
                                                    <input readonly type="text" class="form-control " name="" id="designation">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label>Department:</label>
                                                    <input readonly type="text" class="form-control" name="" id="department">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label>Reporting to:</label>
                                                    <input value="" type="text" class="form-control" name="reporting_to" id="reporting_to">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label>Initially appointed as :</label>
                                                    <input readonly type="text" class="form-control" name="" id="initially">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label>Date of Joining :</label>
                                                    <input readonly type="date" class="form-control" name="" id="doj">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group text-center">
                                                    <label>Date of Separation:</label>
                                                    <input type="date" class="form-control" value="" name="dos" id="dos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="depart-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                                                    <h3>PART - A</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>Reasons for leaving:</label>
                                                    <textarea class="form-control requiredField" rows="3" name="reason_for_leaving" id="reason_for_leaving" placeholder="write here..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>Future plans:</label>
                                                    <textarea class="form-control requiredField" rows="3" name="future_plane" id="future_plane" placeholder="write here..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="depart-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                                                    <h3>PART - B</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                <h4>Interviewee's level of satisfaction with:</h4>
                                            </div>
                                            <div class="col-lg-8 col-md-8 text-uppercase">
                                                <h4 class="text-center">Extent of Satisfaction</h4>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <h5 class="text-danger"><strong>Unsatisfied</strong></h5>
                                                    <div class="col-lg-4 col-md-4">0</div>
                                                    <div class="col-lg-4 col-md-4">1</div>
                                                    <div class="col-lg-4 col-md-4">2</div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <h5 class="text-success"><strong>Satisfied</strong></h5>
                                                    <div class="col-lg-4 col-md-4">3</div>
                                                    <div class="col-lg-4 col-md-4">4</div>
                                                    <div class="col-lg-4 col-md-4">5</div>
                                                </div>
                                            </div>
                                            <!--1 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i> Work content</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied requiredField" id="g1" value="0"  name="work_content">
                                                        <label for="g1"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied requiredField" id="g2" value="1"  name="work_content">
                                                        <label for="g2"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied requiredField" id="g3" value="2"  name="work_content">
                                                        <label for="g3"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied requiredField" id="g4" value="3"  name="work-content">
                                                        <label for="g4"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied requiredField" id="g5" value="4"  name="work-content">
                                                        <label for="g5"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied requiredField" id="g6" value="5"  name="work-content">
                                                        <label for="g6"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--1 ROW END-->
                                            <!--2 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i> Working methods</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g7" value="0" name="working_method">
                                                        <label for="g7"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g8" value="1" name="working_method">
                                                        <label for="g8"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g9" value="2" name="working_method">
                                                        <label for="g9"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g10" value="3" name="working_method">
                                                        <label for="g10"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g11" value="4" name="working_method">
                                                        <label for="g11"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g12" value="5" name="working_method">
                                                        <label for="g12"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--2 ROW BEGIN-->
                                            <!--3 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i> Quality of supervision</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g13"  value=0" name="quality_supervison">
                                                        <label for="g13"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g14"  value="1" name="quality_supervison">
                                                        <label for="g14"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g15"  value="2" name="quality_supervison">
                                                        <label for="g15"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g16"  value="3" name="quality_supervison">
                                                        <label for="g16"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g17"  value="4" name="quality_supervison">
                                                        <label for="g17"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g18"  value="5" name="quality_supervison">
                                                        <label for="g18"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--3 ROW BEGIN-->
                                            <!--4 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>Level of empowerment</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g19" value="0" name="level_of_empowerment">
                                                        <label for="g19"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g20" value="1" name="level_of_empowerment">
                                                        <label for="g20"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g21" value="2" name="level_of_empowerment">
                                                        <label for="g21"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g22" value="3" name="level_of_empowerment">
                                                        <label for="g22"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g23" value="4" name="level_of_empowerment">
                                                        <label for="g23"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g24" value="5" name="level_of_empowerment">
                                                        <label for="g24"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--4 ROW BEGIN-->
                                            <!--5 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>HR policies and procedures</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g25" value="0"  name="hr_policies_procedures">
                                                        <label for="g25"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g26" value="1" name="hr_policies_procedures">
                                                        <label for="g26"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g27" value="2" name="hr_policies_procedures">
                                                        <label for="g27"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g28" value="3" name="hr_policies_procedures">
                                                        <label for="g28"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g29" value="4" name="hr_policies_procedures">
                                                        <label for="g29"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g30" value="5" name="hr_policies_procedures">
                                                        <label for="g30"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--5 ROW BEGIN-->
                                            <!--6 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>Attitude of supervisor</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g31" value="0" name="attitude_of_supervisor">
                                                        <label for="g31"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g32" value="1" name="attitude_of_supervisor">
                                                        <label for="g32"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g33" value="2" name="attitude_of_supervisor">
                                                        <label for="g33"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g34" value="3" name="attitude_of_supervisor">
                                                        <label for="g34"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g35" value="4" name="attitude_of_supervisor">
                                                        <label for="g35"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g36" value="5" name="attitude_of_supervisor">
                                                        <label for="g36"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--6 OW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i> Behaviour of colleagues</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g37" value="0" name="behavior_colleagues">
                                                        <label for="G37"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g38" value="1" name="behavior_colleagues">
                                                        <label for="g38"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g39" value="2" name="behavior_colleagues">
                                                        <label for="g39"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g40" value="3" name="behavior_colleagues">
                                                        <label for="g40"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g41" value="4" name="behavior_colleagues">
                                                        <label for="g41"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"class="gudia-radio gudia-radio-satisfied" id="g42" value="5" name="behavior_colleagues">
                                                        <label for="g42"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--7 ROW BEGIN-->

                                            <!--8 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>Opportunity for training and development</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g43" value="0" name="peertunity_for_training_development">
                                                        <label for="g43"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g44" value="1" name="peertunity_for_training_development">
                                                        <label for="g44"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g45" value="2" name="peertunity_for_training_development">
                                                        <label for="g45"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g46" value="3" name="peertunity_for_training_development">
                                                        <label for="g46"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g47" value="4" name="peertunity_for_training_development">
                                                        <label for="g47"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g48" value="5" name="peertunity_for_training_development">
                                                        <label for="g48"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--8 ROW BEGIN-->

                                            <!--9 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>Remuneration</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g49" value="0" name="remuneration">
                                                        <label for="g49"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g50" value="1" name="remuneration">
                                                        <label for="g50"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g51" value="2" name="remuneration">
                                                        <label for="g51"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g52" value="3" name="remuneration">
                                                        <label for="g52"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g53" value="4" name="remuneration">
                                                        <label for="g53"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g54" value="5" name="remuneration">
                                                        <label for="g54"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--9 ROW BEGIN-->

                                            <!--10 ROW BEGIN-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                                                    <h4><i class="glyphicon glyphicon-send"></i>Growth Opportunities</h4>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g55" value="0" name="growth_oppertunities">
                                                        <label for="g55"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g56" value="1" name="growth_oppertunities">
                                                        <label for="g56"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-unsatisfied" id="g57" value="2" name="growth_oppertunities">
                                                        <label for="g57"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g58" value="3" name="growth_oppertunities">
                                                        <label for="g58"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g59" value="4" name="growth_oppertunities">
                                                        <label for="g59"></label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                                        <input type="radio"  class="gudia-radio gudia-radio-satisfied" id="g60" value="5" name="growth_oppertunities">
                                                        <label for="g60"></label>
                                                    </div>

                                                </div>
                                            </div>
                                            <!--10 ROW BEGIN-->
                                        </div>
                                        <div class="row text-center">
                                            <div class="depart-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                                                    <h3>PART - C</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <h4>Interviewee was particularly satisfied with</h4>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="satisfied_with_point1" id="satisfied_with_point1" placeholder="Point number 1">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="satisfied_with_point2" placeholder="Point number 2">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="satisfied_with_point3" placeholder="Point number 3">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <h4>Interviewee was particularly unhappy with</h4>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="unsatisfied_with_point1" placeholder="Point number 1">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="unsatisfied_with_point2" placeholder="Point number 2">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" class="form-control requiredField" name="unsatisfied_with_point3" placeholder="Point number 3">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="depart-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                                                    <h3>PART - D</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>Employee's specific suggestions for improving working environment in the organization:</label>
                                                    <textarea class="form-control requiredField"   rows="3" name="suggestion" id="suggestion" placeholder="write here..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="depart-row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                                                    <h3>PART - E</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>HR Representative remarks:</label>
                                                    <textarea class="form-control" rows="3" name="hr_representative_remarks" id="hr_representative_remarks" placeholder="write here..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label>HR Head remarks	:</label>
                                                    <textarea class="form-control" rows="3" name="hr_head_representative_remarks" id="hr_head_representative_remarks" placeholder="write here..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row page-section">
                                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                                <button type="submit" class="btn btn-success">Submit Form</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <style>

                            </style>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <script>
        function viewEmployeeDataExitInterview(id)
        {


            $('.loader_data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var emp_val= $('#'+id).val();

            if (emp_val=='')
            {
                $('.loader_data').html('');
                alert('You Did`nt Select Any Employee');
                $('.null').val('');
                $('.gudia-radio').attr('checked', false);
                return false;
            }
            $.ajax({
                url: "/hdc/viewEmployeeDataExitInterview",
                type: 'GET',
                data: {emp_val:emp_val},
                success: function(response)
                {


                    $('.loader_data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
                    $('#data').html(response);


                }


            });
        }


        $(document).ready(function(){
            $("form").submit(function(){
                var input = document.getElementsByClassName('requiredField');
                var v= input.length;

                var eror='';
                var radio = ["work_content", "working_method", "quality_supervison","level_of_empowerment", "hr_policies_procedures"
                    ,"attitude_of_supervisor", "behavior_colleagues", "peertunity_for_training_development","remuneration", "growth_oppertunities"];
                for (i=0; i<radio.length; i++)
                {
                    if ($("input:radio[name='"+radio[i]+"']").is(":checked"))
                    {
                        eror='';

                    }
                    else
                    {
                        eror+=radio[i]+' '+'IS REQUIRED'+' '+'\n';


                    }

                }

                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++){
                    var v = input[i].id;
                    if(v == '')
                    {

                    }

                    else{
                        if($('#'+v).val() == '')

                        {

                            $('#'+v).css('border-color', 'red');

                            $('#'+v).focus();
                            return false;
                        }

                        else
                        {
                            $('#'+v).css('border-color', '#ccc');
                        }
                    }
                }

                if (eror!='')

                {
                    alert(eror);
                    return false;
                }
            });
        });

    </script>


@endsection