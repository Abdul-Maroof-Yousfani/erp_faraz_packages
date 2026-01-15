
<?php
if ($count==1):

    $location=$employe_probationary_period->location;
    $immidaite_supervisor=$employe_probationary_period->immidaite_supervisor;
    $job_knowledge=$employe_probationary_period->job_knowledge;
    $meet_requirements=$employe_probationary_period->meet_requirements;
    $quality_work=$employe_probationary_period->quality_work;
    $quantiry_work=$employe_probationary_period->quantiry_work;
    $initiative=$employe_probationary_period->initiative;
    $dependability=$employe_probationary_period->dependability;
    $conduct=$employe_probationary_period->conduct;
    $tradiness=$employe_probationary_period->tradiness;
    $attendance=$employe_probationary_period->attendance;
    $cooperation=$employe_probationary_period->cooperation;
    $satisfaction=$employe_probationary_period->satisfaction;
    $supervisor_recommendation=$employe_probationary_period->supervisor_recommendation;
    $supervisor_comments=$employe_probationary_period->supervisor_comments;
    $dept_head_comments=$employe_probationary_period->dept_head_comments;
    $hr_comments=$employe_probationary_period->hr_comments;
    $md_comments=$employe_probationary_period->md_comments;


else:



    $location='';
    $immidaite_supervisor='';
    $job_knowledge='';
    $meet_requirements='';
    $quality_work='';
    $quantiry_work='';
    $initiative='';
    $dependability='';
    $conduct='';
    $tradiness='';
    $attendance='';
    $cooperation='';
    $satisfaction='';
    $supervisor_recommendation='';
    $supervisor_comments='';
    $dept_head_comments='';
    $hr_comments='';
    $md_comments='';

endif;

?>



<form  id="subm" method="post" action="{{url('had/addEmployeeProbationaryPeriodDetail')}}">
    <input type="hidden" name="save_update" value="{{$count}}"/>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <figure>
                <img src="assets/img/logo.jpg" class="img-responsive" title="" alt="">
            </figure>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-center">
            <h2>GUDIA PRIVATE LIMITED</h2>
            <h4>PROBATIONARY PERIOD REPORT</h4>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Interviewee:</label>
                <select  onchange="viewEmployeeDataProbationaryPeriod(this.id)" name="emp_id" id="emp_id"  class="form-control requiredField null">
                    <option value="">Select</option>
                    @foreach($employee as $row)
                        <option @if($id==$row['id'])selected @endif value="{{$row['id']}}">{{$row['emp_name']}}</option>
                    @endforeach;
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Designation:</label>
                <input readonly type="text" class="form-control null" name="" value="{{$designation}}" id="designation">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Department:</label>
                <input readonly type="text" class="form-control null" name="" value="{{$department}}" id="department">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Location:</label>
                <input type="text"  class="form-control requiredField null"  value="@if($location!=''){{$location}}@endif" name="location" id="location">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Immediate Supervisor:</label>
                <input type="text" class="form-control requiredField null" value="@if($immidaite_supervisor!=''){{$immidaite_supervisor}}@endif" name="immidaite_supervisor" id="immidaite_supervisor">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label>Date of Joining :</label>
                <input readonly type="date" class="form-control null" name="" value="{{$joining_date}}" id="doj">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>Indicate employee performance for the following job activities: </h4>
        </div>
    </div>
    <div class="row text-center">
        <div class="depart-row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                <h3>JOB AVCTIVITIES:</h3>
            </div>
        </div>
    </div>
    <div class="row page-section">
        <div class="col-lg-8 col-md-8 col-sm-6 text-uppercase">
            `	</div>
        <div class="col-lg-4 col-md-4 col-sm-12  text-uppercase text-center">
            <div class="col-lg-4 col-md-4 col-sm-6"><h6>Below Expectation</h6></div>
            <div class="col-lg-4 col-md-4 col-sm-6"><h6>Achieved Expectation</h6></div>
            <div class="col-lg-4 col-md-4 col-sm-6"><h6>Above Expectation</h6></div>
        </div>
        <!--1 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i> Job Knowledge: Does employee understand his/Her job requirements?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($job_knowledge==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g4" name="job_knowledge">
                    <label for="g4"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($job_knowledge==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g5" name="job_knowledge">
                    <label for="g5"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($job_knowledge==3)checked @endif class="gudia-radio gudia-radio-satisfied" id="g6" value="3" name="job_knowledge">
                    <label for="g6"></label>
                </div>

            </div>
        </div>
        <!--1 ROW END-->

        <!--2 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i> Does employee meet job requirements?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($meet_requirements==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g101" name="meet_requirements">
                    <label for="g101"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($meet_requirements==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g102" name="meet_requirements">
                    <label for="g102"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($meet_requirements==3)checked @endif class="gudia-radio gudia-radio-satisfied"  value="3" id="g103" name="meet_requirements">
                    <label for="g103"></label>
                </div>

            </div>
        </div>
        <!--2 ROW BEGIN-->
<?php  $quality_work; ?>
        <!--3 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i> Quality of work: Is quality of employee's work satisfactory?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quality_work==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g104" name="quality_work">
                    <label for="g104"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quality_work==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g105" name="quality_work">
                    <label for="g105"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quality_work==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g106" name="quality_work">
                    <label for="g106"></label>
                </div>

            </div>
        </div>
        <!--3 ROW BEGIN-->

        <!--4 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i> Quantity of work: Is employee sufficiently productive?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quantiry_work==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g107" name="quantiry_work">
                    <label for="g107"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quantiry_work==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g108" name="quantiry_work">
                    <label for="g108"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($quantiry_work==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g109" name="quantiry_work">
                    <label for="g109"></label>
                </div>
            </div>
        </div>
        <!--4 ROW BEGIN-->

        <!--5 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Initiative: Is employee a self starter who does not need prompting?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($initiative==1)checked @endif  class="gudia-radio gudia-radio-unsatisfied" value="1" id="g110" name="initiative">
                    <label for="g110"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($initiative==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g111" name="initiative">
                    <label for="g111"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($initiative==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g112" name="initiative">
                    <label for="g112"></label>
                </div>
            </div>
        </div>
        <!--5 ROW BEGIN-->

        <!--6 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Dependability: Can you count on employee to do as instructed without constant follow-up?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($dependability==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g113" name="dependability">
                    <label for="g113"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($dependability==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g114" name="dependability">
                    <label for="g114"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($dependability==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g115" name="dependability">
                    <label for="g115"></label>
                </div>
            </div>
        </div>
        <!--6 ROW BEGIN-->

        <!--7 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Conduct: Does employee follow rules of conduct?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($conduct==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1"  id="g116" name="conduct">
                    <label for="g116"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($conduct==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g117" name="conduct">
                    <label for="g117"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($conduct==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g118" name="conduct">
                    <label for="g118"></label>
                </div>
            </div>
        </div>
        <!--7 ROW BEGIN-->

        <!--8 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Tardiness: Are you satisfied with his/her punctuality during this period?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($tradiness==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g119" name="tradiness">
                    <label for="g119"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($tradiness==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g120" name="tradiness">
                    <label for="g120"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($tradiness==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g121" name="tradiness">
                    <label for="g121"></label>
                </div>
            </div>
        </div>
        <!--8 ROW BEGIN-->

        <!--9 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Attendance: Are you satisfied with his/her attendance record to date?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($attendance==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g122" name="attendance">
                    <label for="g122"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($attendance==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g123" name="attendance">
                    <label for="g123"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($attendance==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g124" name="attendance">
                    <label for="g124"></label>
                </div>
            </div>
        </div>
        <!--9 ROW BEGIN-->

        <!--10 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>Cooperation: Does employee work as a team member?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($cooperation==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g125" name="cooperation">
                    <label for="g125"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($cooperation==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g126" name="cooperation">
                    <label for="g126"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio" @if($cooperation==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g127" name="cooperation">
                    <label for="g127"></label>
                </div>
            </div>
        </div>
        <!--10 ROW BEGIN-->

        <!--11 ROW BEGIN-->
        <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
            <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                <h5><i class="glyphicon glyphicon-send"></i>How satisfied are you with the employee's progress to date?</h5>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($satisfaction==1)checked @endif class="gudia-radio gudia-radio-unsatisfied" value="1" id="g128" name="satisfaction">
                    <label for="g128"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($satisfaction==2)checked @endif class="gudia-radio gudia-radio-avr-satisfied" value="2" id="g129" name="satisfaction">
                    <label for="g129"></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-2">
                    <input type="radio"  @if($satisfaction==3)checked @endif class="gudia-radio gudia-radio-satisfied" value="3" id="g130" name="satisfaction">
                    <label for="g130"></label>
                </div>
            </div>
        </div>
        <!--11 ROW BEGIN-->
    </div>
    <div class="row page-section">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>Supervisor's Recommendation</h4>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <h5>Confirm Employment :</h5>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
            <input type="radio" @if($supervisor_recommendation==1)checked @endif class="gudia-radio gudia-radio-gray" value="1" id="g131" name="supervisor_recommendation">
            <label for="g131"></label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <h5>Extend Probationary Period :</h5>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
            <input type="radio" @if($supervisor_recommendation==2)checked @endif class="gudia-radio gudia-radio-gray" value="2" id="g133" name="supervisor_recommendation">
            <label for="g133"></label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <h5>Terminate</h5>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
            <input type="radio" @if($supervisor_recommendation==3)checked @endif class="gudia-radio gudia-radio-gray" value="3" id="g134" name="supervisor_recommendation">
            <label for="g134"></label>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Supervisor's Comments:</label>
                <textarea class="form-control null" rows="3"  name="supervisor_comments" id="" placeholder="write here...">@if($supervisor_comments!=''){{trim($supervisor_comments)}}@endif</textarea>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Dept. Head Comments:</label>
                <textarea class="form-control null" rows="3" name="dept_head_comments" id="" placeholder="write here...">@if($dept_head_comments!=''){{trim($dept_head_comments)}}@endif</textarea>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>HR Comments:</label>
                    <textarea class="form-control null" rows="3" name="hr_comments
" id="" placeholder="write here...">@if($hr_comments!=''){{trim($hr_comments)}}@endif</textarea>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>MD Comments (If any):</label>
                <textarea class="form-control null" rows="3" name="md_comments" id="" placeholder="write here...">@if($md_comments!=''){{trim($md_comments)}}@endif</textarea>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
            <button type="submit" class="btn btn-success">Submit Form</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $("form").submit(function()
        {

            var input = document.getElementsByClassName('requiredField');
            var v= input.length;

            var eror='';
            var radio = ["job_knowledge", "meet_requirements", "quality_work","quantiry_work", "initiative"
                ,"dependability", "conduct", "tradiness","attendance", "cooperation", "satisfaction"];
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