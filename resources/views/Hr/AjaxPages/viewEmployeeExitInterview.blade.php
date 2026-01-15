


<?php
if ($count==1):

    $reporting_to=$exit_interview_employe_data->reporting_to;
    $dos=$exit_interview_employe_data->dos;
    $reason_for_leaving=$exit_interview_employe_data->reason_for_leaving;
    $future_plane=$exit_interview_employe_data->future_plane;
    $work_content=$exit_interview_employe_data->work_content;
    $working_method=$exit_interview_employe_data->working_method;
    $quality_supervison=$exit_interview_employe_data->quality_supervison;
    $level_of_empowerment=$exit_interview_employe_data->level_of_empowerment;
    $hr_policies_procedures=$exit_interview_employe_data->hr_policies_procedures;
    $attitude_of_supervisor=$exit_interview_employe_data->attitude_of_supervisor;
    $behavior_colleagues=$exit_interview_employe_data->behavior_colleagues;
    $peertunity_for_training_development=$exit_interview_employe_data->peertunity_for_training_development;
    $remuneration=$exit_interview_employe_data->remuneration;
    $growth_oppertunities=$exit_interview_employe_data->growth_oppertunities;
    $satisfied_with_point1=$exit_interview_employe_data->satisfied_with_point1;
    $satisfied_with_point2=$exit_interview_employe_data->satisfied_with_point2;
    $satisfied_with_point3=$exit_interview_employe_data->satisfied_with_point3;
    $unsatisfied_with_point1=$exit_interview_employe_data->unsatisfied_with_point1;
    $unsatisfied_with_point2=$exit_interview_employe_data->unsatisfied_with_point2;
    $unsatisfied_with_point3=$exit_interview_employe_data->unsatisfied_with_point3;
    $suggestion=$exit_interview_employe_data->suggestion;
    $hr_representative_remarks=$exit_interview_employe_data->hr_representative_remarks;
    $hr_head_representative_remarks=$exit_interview_employe_data->hr_head_representative_remarks;

else:



    $reporting_to='';
    $dos='';
    $reason_for_leaving='';
    $future_plane='';
    $work_content='null';
    $working_method='';
    $quality_supervison='';
    $level_of_empowerment='';
    $hr_policies_procedures='';
    $attitude_of_supervisor='';
    $behavior_colleagues='';
    $peertunity_for_training_development='';
    $remuneration='';
    $growth_oppertunities='';
    $satisfied_with_point1='';
    $satisfied_with_point2='';
    $satisfied_with_point3='';
    $unsatisfied_with_point1='';
    $unsatisfied_with_point2='';
    $unsatisfied_with_point3='';
    $suggestion='';
    $hr_representative_remarks='';
    $hr_head_representative_remarks='';
endif;

?>


        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="save_update" value="{{ $count }}">
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
                    <select  onchange="viewEmployeeDataExitInterview(this.id)" name="emp_id" id="emp_name"  class="form-control requiredField">
                        <option value="">Select</option>
                        @foreach($employee as $row)
                            <option @if($id==$row['id'])selected @endif value="{{$row['id']}}">{{$row['emp_name']}}</option>
                        @endforeach;
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <label>Designation:</label>
                    <input readonly type="text" class="form-control requiredField null" name="" value="{{$designation}}" id="designation">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <label>Department:</label>
                    <input readonly type="text" class="form-control requiredField null" name="" value="{{$department}}" id="department">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">



                <div class="form-group">
                    <label>Reporting to:</label>
                    <input value="{{$reporting_to}}" type="text" class="form-control requiredField null"  name="reporting_to" id="reporting_to">
                </div>
            </div>

        </div>
        <div class="row">


            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label>Initially appointed as :</label>
                    <input readonly type="text" class="form-control requiredField null" value="{{$designation}}" name="" id="initially">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label>Date of Joining :</label>
                    <input readonly type="date" class="form-control requiredField null"  value="{{$joining_date}}" name="" id="doj">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group text-center">
                    <label>Date of Separation:</label>
                    <input type="date" class="form-control requiredField null" value="{{$dos}}" name="dos" id="dos">
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
                    <textarea class="form-control requiredField null" rows="3" name="reason_for_leaving" id="reason_for_leaving" placeholder="write here...">{{trim($reason_for_leaving)}}</textarea>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Future plans:</label>
                    <textarea class="form-control requiredField null" rows="3" name="future_plane" id="future_plane" placeholder="write here...">{{trim( $future_plane)}}</textarea>
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
                        <input type="radio" @if($work_content=='0') checked @endif class="gudia-radio gudia-radio-unsatisfied requiredField" id="g1" value="0"  name="work_content">
                        <label for="g1"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($work_content==1)checked @endif class="gudia-radio gudia-radio-unsatisfied requiredField" id="g2" value="1"  name="work_content">
                        <label for="g2"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($work_content==2)checked @endif class="gudia-radio gudia-radio-unsatisfied requiredField" id="g3" value="2"  name="work_content">
                        <label for="g3"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($work_content==3)checked @endif class="gudia-radio gudia-radio-satisfied requiredField" id="g4" value="3"  name="work_content">
                        <label for="g4"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($work_content==4)checked @endif class="gudia-radio gudia-radio-satisfied requiredField" id="g5" value="4"  name="work_content">
                        <label for="g5"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($work_content==5)checked @endif class="gudia-radio gudia-radio-satisfied requiredField" id="g6" value="5"  name="work_content">
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
                        <input type="radio" @if($working_method=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g7" value="0" name="working_method">
                        <label for="g7"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($working_method==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g8" value="1" name="working_method">
                        <label for="g8"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($working_method==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g9" value="2" name="working_method">
                        <label for="g9"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($working_method==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g10" value="3" name="working_method">
                        <label for="g10"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($working_method==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g11" value="4" name="working_method">
                        <label for="g11"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($working_method==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g12" value="5" name="working_method">
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
                        <input type="radio" @if($quality_supervison=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g13"  value=0" name="quality_supervison">
                        <label for="g13"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($quality_supervison==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g14"  value="1" name="quality_supervison">
                        <label for="g14"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($quality_supervison==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g15"  value="2" name="quality_supervison">
                        <label for="g15"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($quality_supervison==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g16"  value="3" name="quality_supervison">
                        <label for="g16"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($quality_supervison==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g17"  value="4" name="quality_supervison">
                        <label for="g17"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($quality_supervison==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g18"  value="5" name="quality_supervison">
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
                        <input type="radio" @if($level_of_empowerment=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g19" value="0" name="level_of_empowerment">
                        <label for="g19"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio"  @if($level_of_empowerment==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g20" value="1" name="level_of_empowerment">
                        <label for="g20"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($level_of_empowerment==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g21" value="2" name="level_of_empowerment">
                        <label for="g21"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($level_of_empowerment==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g22" value="3" name="level_of_empowerment">
                        <label for="g22"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($level_of_empowerment==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g23" value="4" name="level_of_empowerment">
                        <label for="g23"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($level_of_empowerment==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g24" value="5" name="level_of_empowerment">
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
                        <input type="radio" @if($hr_policies_procedures=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g25" value="0"  name="hr_policies_procedures">
                        <label for="g25"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($hr_policies_procedures==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g26" value="1" name="hr_policies_procedures">
                        <label for="g26"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($hr_policies_procedures==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g27" value="2" name="hr_policies_procedures">
                        <label for="g27"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($hr_policies_procedures==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g28" value="3" name="hr_policies_procedures">
                        <label for="g28"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($hr_policies_procedures==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g29" value="4" name="hr_policies_procedures">
                        <label for="g29"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($hr_policies_procedures==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g30" value="5" name="hr_policies_procedures">
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
                        <input type="radio" @if($attitude_of_supervisor=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g31" value="0" name="attitude_of_supervisor">
                        <label for="g31"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($attitude_of_supervisor==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g32" value="1" name="attitude_of_supervisor">
                        <label for="g32"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($attitude_of_supervisor==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g33" value="2" name="attitude_of_supervisor">
                        <label for="g33"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($attitude_of_supervisor==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g34" value="3" name="attitude_of_supervisor">
                        <label for="g34"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($attitude_of_supervisor==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g35" value="4" name="attitude_of_supervisor">
                        <label for="g35"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($attitude_of_supervisor==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g36" value="5" name="attitude_of_supervisor">
                        <label for="g36"></label>
                    </div>

                </div>
            </div>
            <!--6 ROW BEGIN-->

            <!--7 ROW BEGIN-->
            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                <div class="col-lg-4 col-md-4 col-sm-4 satisfaction-icon">
                    <h4><i class="glyphicon glyphicon-send"></i> Behaviour of colleagues</h4>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g37" value="0" name="behavior_colleagues">
                        <label for="G37"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g38" value="1" name="behavior_colleagues">
                        <label for="g38"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g39" value="2" name="behavior_colleagues">
                        <label for="g39"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g40" value="3" name="behavior_colleagues">
                        <label for="g40"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g41" value="4" name="behavior_colleagues">
                        <label for="g41"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($behavior_colleagues==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g42" value="5" name="behavior_colleagues">
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
                        <input type="radio" @if($peertunity_for_training_development=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g43" value="0" name="peertunity_for_training_development">
                        <label for="g43"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($peertunity_for_training_development==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g44" value="1" name="peertunity_for_training_development">
                        <label for="g44"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($peertunity_for_training_development==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g45" value="2" name="peertunity_for_training_development">
                        <label for="g45"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($peertunity_for_training_development==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g46" value="3" name="peertunity_for_training_development">
                        <label for="g46"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($peertunity_for_training_development==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g47" value="4" name="peertunity_for_training_development">
                        <label for="g47"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($peertunity_for_training_development==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g48" value="5" name="peertunity_for_training_development">
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
                        <input type="radio" @if($remuneration=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g49" value="0" name="remuneration">
                        <label for="g49"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($remuneration==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g50" value="1" name="remuneration">
                        <label for="g50"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($remuneration==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g51" value="2" name="remuneration">
                        <label for="g51"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($remuneration==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g52" value="3" name="remuneration">
                        <label for="g52"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($remuneration==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g53" value="4" name="remuneration">
                        <label for="g53"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($remuneration==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g54" value="5" name="remuneration">
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
                        <input type="radio" @if($growth_oppertunities=='0')checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g55" value="0" name="growth_oppertunities">
                        <label for="g55"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($growth_oppertunities==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g56" value="1" name="growth_oppertunities">
                        <label for="g56"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($growth_oppertunities==2)checked @endif class="gudia-radio gudia-radio-unsatisfied" id="g57" value="2" name="growth_oppertunities">
                        <label for="g57"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($growth_oppertunities==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g58" value="3" name="growth_oppertunities">
                        <label for="g58"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($growth_oppertunities==4)checked @endif class="gudia-radio gudia-radio-satisfied" id="g59" value="4" name="growth_oppertunities">
                        <label for="g59"></label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <input type="radio" @if($growth_oppertunities==5)checked @endif class="gudia-radio gudia-radio-satisfied" id="g60" value="5" name="growth_oppertunities">
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
                    <input type="text" value="{{$satisfied_with_point1}}" class="form-control requiredField null" name="satisfied_with_point1" id="satisfied_with_point1" placeholder="Point number 1">
                </div>
                <div class="form-group">
                    <input type="text" value="{{$satisfied_with_point2}}" class="form-control requiredField null" name="satisfied_with_point2" placeholder="Point number 2">
                </div>
                <div class="form-group">
                    <input type="text" value="{{$satisfied_with_point3}}" class="form-control requiredField null" name="satisfied_with_point3" placeholder="Point number 3">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h4>Interviewee was particularly unhappy with</h4>
                <div class="form-group">
                    <input type="text" value="{{$unsatisfied_with_point1}}" class="form-control requiredField null" name="unsatisfied_with_point1" placeholder="Point number 1">
                </div>
                <div class="form-group">
                    <input type="text" value="{{$unsatisfied_with_point2}}" class="form-control requiredField null" name="unsatisfied_with_point2" placeholder="Point number 2">
                </div>
                <div class="form-group">
                    <input type="text" value="{{$unsatisfied_with_point3}}" class="form-control requiredField null" name="unsatisfied_with_point3" placeholder="Point number 3">
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
                    <textarea class="form-control requiredField null"   rows="3" name="suggestion" id="suggestion" placeholder="write here...">{{trim($suggestion)}}</textarea>
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
                    <textarea class="form-control null" rows="3" name="hr_representative_remarks" id="hr_representative_remarks" placeholder="write here...">{{trim($hr_representative_remarks)}}</textarea>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    <label>HR Head remarks	:</label>
                    <textarea class="form-control null" rows="3" name="hr_head_representative_remarks" id="hr_head_representative_remarks" placeholder="write here...">{{trim($hr_head_representative_remarks)}}</textarea>
                </div>
            </div>
        </div>
        <div class="row page-section">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                <button type="submit" class="btn btn-success">Submit Form</button>
            </div>
        </div>

