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
        .gudia-radio-avr-satisfied:checked + label:after,
        .gudia-radio-avr-satisfied:not(:checked) + label:after{
            content: '';
            width: 12px;
            height: 12px;
            background:#ffb409;
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

        .gudia-radio-gray:checked + label:after,
        .gudia-radio-gray:not(:checked) + label:after {
            content: '';
            width: 12px;
            height: 12px;
            background: #555;
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
                <div class="loader_data"></div>
                <div class="container gudia-gap">
                    <div id="data">
                    <form action="" method="post">
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
                                    <select  onchange="viewEmployeeDataProbationaryPeriod(this.id)" name="emp_id" id="emp_id"  class="form-control requiredField">
                                        <option value="">Select</option>
                                        @foreach($employee as $row)
                                            <option value="{{$row['id']}}">{{$row['emp_name']}}</option>
                                        @endforeach;
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Designation:</label>
                                    <input readonly type="text" class="form-control " name="" id="designation">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Department:</label>
                                    <input readonly type="text" class="form-control" name="" id="department">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Location:</label>
                                    <input type="text" class="form-control requiredField" name="location" id="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Immediate Supervisor:</label>
                                    <input type="text" class="form-control requiredField" name="immidaite_supervisor" id="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Date of Joining :</label>
                                    <input readonly type="date" class="form-control" name="" id="doj">
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
                                      <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g4" name="job_knowledge">                                     <label for="g4"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g5" name="job_knowledge">
                                        <label for="g5"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g6" name="job_knowledge">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g101" name="meet_requirements
">
                                        <label for="g101"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g102" name="meet_requirements
">
                                        <label for="g102"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g103" name="meet_requirements
">
                                        <label for="g103"></label>
                                    </div>

                                </div>
                            </div>
                            <!--2 ROW BEGIN-->

                            <!--3 ROW BEGIN-->
                            <div class="col-lg-12 col-md-12 col-sm-12 list-of-satisfied">
                                <div class="col-lg-8 col-md-6 col-sm-4 satisfaction-icon">
                                    <h5><i class="glyphicon glyphicon-send"></i> Quality of work: Is quality of employee's work satisfactory?</h5>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g104" name="quality_work
">
                                        <label for="g104"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g105" name="quality_work
">
                                        <label for="g105"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g106" name="quality_work
">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g107" name="quantiry_work
">
                                        <label for="g107"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g108" name="quantiry_work
">
                                        <label for="g108"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g109" name="quantiry_work
">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g110" name="initiative">
                                        <label for="g110"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g111" name="initiative">
                                        <label for="g111"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g112" name="initiative">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g113" name="dependability">
                                        <label for="g113"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g114" name="dependability">
                                        <label for="g114"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g115" name="dependability">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g116" name="conduct">
                                        <label for="g116"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g117" name="conduct">
                                        <label for="g117"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g118" name="conduct">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g119" name="tradiness">
                                        <label for="g119"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g120" name="tradiness">
                                        <label for="g120"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g121" name="tradiness">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g122" name="attendance
">
                                        <label for="g122"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g123" name="attendance
">
                                        <label for="g123"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g124" name="attendance
">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g125" name="cooperation">
                                        <label for="g125"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g126" name="cooperation">
                                        <label for="g126"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g127" name="cooperation">
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
                                        <input type="radio" class="gudia-radio gudia-radio-unsatisfied" id="g128" name="satisfaction">
                                        <label for="g128"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-avr-satisfied" id="g129" name="satisfaction">
                                        <label for="g129"></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-2">
                                        <input type="radio" class="gudia-radio gudia-radio-satisfied" id="g130" name="satisfaction">
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
                                <input type="radio" class="gudia-radio gudia-radio-gray" id="g131" name="supervisor_recommendation
">
                                <label for="g131"></label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                <h5>Extend Probationary Period :</h5>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
                                <input type="radio" class="gudia-radio gudia-radio-gray" id="g133" name="supervisor_recommendation
">
                                <label for="g133"></label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                <h5>Terminate</h5>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12">
                                <input type="radio" class="gudia-radio gudia-radio-gray" id="g134" name="supervisor_recommendation
">
                                <label for="g134"></label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Supervisor's Comments:</label>
                                    <textarea class="form-control" rows="3" name="supervisor_comments" id="" placeholder="write here..."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Dept. Head Comments:</label>
                                    <textarea class="form-control" rows="3" name="dept_head_comments" id="" placeholder="write here..."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>HR Comments:</label>
                                     <textarea class="form-control" rows="3" name="hr_comments" id="" placeholder="write here..."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>MD Comments (If any):</label>
                                    <textarea class="form-control" rows="3" name="md_comments" id="" placeholder="write here..."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button type="submit" class="btn btn-success">Submit Form</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function viewEmployeeDataProbationaryPeriod(id)
    {



    var emp_val= $('#'+id).val();

    if (emp_val=='')
    {
    $('.loader_data').html('');
    alert('You Did`nt Select Any Employee');
    $('.null').val('');
    $('.gudia-radio').attr('checked', false);
    return false;
    }
        $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
    $.ajax({
    url: "/hdc/viewEmployeeDataProbationaryPeriod",
    type: 'GET',
    data: {emp_val:emp_val},
    success: function(response)
    {


    //$('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class=""></div></div></div>');
    $('#data').html(response);


    }


    });
    }


    $(document).ready(function(){
        $("form").submit(function()
        {

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