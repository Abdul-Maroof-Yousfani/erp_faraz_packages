
@include('includes._normalUserNavigation')
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

        .page-section-iq{
            padding-bottom: 29px;
            padding-top: 7px;
            background-color: #eeeeee8c;
        }

        .success {
            background-color: #ddffdd;
            border-left: 6px solid #4CAF50;
        }
        #demo {
            text-align: center;
            font-size: 60px;
            margin-top:0px;
        }
    </style>

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <span class="subHeadingLabelClass">Quiz Test</span>
                    <div style="display: none;" class="test_section">
                        <h3 class="alert alert-success" id="test_time"><time>05:60</time></h3>
                    </div>
                    <div class="success unhide">
                        <p><strong>Note: </strong> Please Enter Your Email And Mobile No.
                            After Clicking on Start Button that Quiz Test Will be Start. The Time Limit will be Only 5 minutes</p>
                    </div>
                </div>

                <div class="loader_data"></div>
                <div class="row unhide">
                    <div class="col-lg-offset-3 col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Candidate Email:</label>
                            <input type="email" id="email" class="form-control" name="email" value="test@gmail.com">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Mobile No:</label>
                            <input type="number" id="number"  class="form-control" name="number" value="03212129422">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group unhide">
                            <button style="margin-top:30px;" onclick="startTest()" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
                <div style="display: none;text-align: center" class="alert alert-danger unhide">
                    <strong></strong>
                </div>

                <div id="" style="display: none;" class="row text-center test_section">
                    <div class="depart-row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
                            <h3>TEST SECTION</h3>
                        </div>
                    </div>
                </div>
                <form action="{{url('had/addEmployeeQuizTest')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div style="display: none;" class="row page-section test_section">
                        <!--1 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>1-	Which number should come next in the pattern? 37, 34, 31, 28</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12">
                                    <input type="number" class="form-control"  name="question1">
                                </div>
                            </div>
                        </div>
                        <!--1 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--2 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>2-	Find the answer that best completes the analogy: Book is to Reading as Fork is to:</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Drawing</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g200" name="question2">
                                    <label for="g200"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Writing</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g201" name="question2">
                                    <label for="g201"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Stirring</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g202" name="question2">
                                    <label for="g202"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Eating</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g203" name="question2">
                                    <label for="g203"></label>
                                </div>
                            </div>
                        </div>
                        <!--2 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--3 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>3-	Find two words, one from each group, that are the closest in meaning:</h4>
                            <h4>Group A</h4>
                            <h5>Talkative, Job, Ecstatic</h5>
                            <h4>Group B</h4>
                            <h5>Angry, Wind, Loquacious</h5>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Talkative and wind</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g204" name="question3">
                                    <label for="g204"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Job and angry</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g205" name="question3">
                                    <label for="g205"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Talkative and loquacious</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g206" name="question3">
                                    <label for="g206"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Ecstatic and angry</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g207" name="question3">
                                    <label for="g207"></label>
                                </div>
                            </div>
                        </div>
                        <!--3 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--4 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>4-	Which of the following can be arranged into a 5-letter English word?</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>H R G S T</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g208" name="question4">
                                    <label for="g208"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>R I L S A </h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g209" name="question4">
                                    <label for="g209"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>T O O M T</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g210" name="question4">
                                    <label for="g210"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>W Q R G S</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g211" name="question4">
                                    <label for="g211"></label>
                                </div>
                            </div>
                        </div>
                        <!--4 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--5 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>5-	What number best completes the analogy:</h4>
                            <h4>8:4 as 10:</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>3</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g212" name="question5">
                                    <label for="g212"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>7</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g213" name="question5">
                                    <label for="g213"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>24</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g214" name="question5">
                                    <label for="g214"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>5</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g215" name="question5">
                                    <label for="g215"></label>
                                </div>
                            </div>
                        </div>
                        <!--5 ROW STARTING-->
                        <div class="col-lg-12"><br/></div>
                        <!--6 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>6-	HAND is to Glove as HEAD is to</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Hair</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g216" name="question6">
                                    <label for="g216"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Hat </h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g217" name="question6">
                                    <label for="g217"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Hat</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g218" name="question6">
                                    <label for="g218"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Earrings</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g219" name="question6">
                                    <label for="g219"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Hairpins</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g220" name="question6">
                                    <label for="g220"></label>
                                </div>
                            </div>
                        </div>
                        <!--6 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--7 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>7-	A fallacious argument is:</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Disturbing</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g221" name="question7">
                                    <label for="g221"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Valid</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g222" name="question7">
                                    <label for="g222"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>False</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g223" name="question7">
                                    <label for="g223"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Necessary</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g224" name="question7">
                                    <label for="g224"></label>
                                </div>
                            </div>
                        </div>
                        <!--7 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--8 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>8-	A cynic is one who knows the price of everything and the ________ of nothing.</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Emotion</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g225" name="question8">
                                    <label for="g225"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Value</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g226" name="question8">
                                    <label for="g226"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Meaning</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g227" name="question8">
                                    <label for="g227"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Color</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g228" name="question8">
                                    <label for="g228"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Quality</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g229" name="question8">
                                    <label for="g229"></label>
                                </div>
                            </div>
                        </div>
                        <!--8 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--9 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>9-	It is easier to _______________ than to offer a helping hand.</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Raise a flag</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g230" name="question9">
                                    <label for="g230"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Be on the ball</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g231" name="question9">
                                    <label for="g231"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Lay down the law</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g232" name="question9">
                                    <label for="g232"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Point the Finger</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g233" name="question9">
                                    <label for="g233"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Sing Praises</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g234" name="question9">
                                    <label for="g234"></label>
                                </div>
                            </div>
                        </div>
                        <!--9 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--10 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>10-	At a conference, 12 members shook hands with each other before & after the meeting. How many total number of handshakes occurred?</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>100</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g235" name="question10">
                                    <label for="g235"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>132</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g236" name="question10">
                                    <label for="g236"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>145</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g237" name="question10">
                                    <label for="g237"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>144</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g238" name="question10">
                                    <label for="g238"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>121</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g239" name="question10">
                                    <label for="g239"></label>
                                </div>
                            </div>
                        </div>
                        <!--10 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--11 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4>Please choose the correct answer 12,7,10,5,8,3, ?</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>0</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g240" name="question11">
                                    <label for="g240"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>6</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g241" name="question11">
                                    <label for="g241"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>8</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g242" name="question11">
                                    <label for="g242"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>9</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g243" name="question11">
                                    <label for="g243"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>10</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g244" name="question11">
                                    <label for="g244"></label>
                                </div>
                            </div>
                        </div>
                        <!--11 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--12 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>12-	Choose the correct answer</h4>
                            <h4>A    | Z    | B    |?    | C    | X   </h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>K</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g245" name="question12">
                                    <label for="g245"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>L</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g246" name="question12">
                                    <label for="g246"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>A</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g247" name="question12">
                                    <label for="g247"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Y</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g248" name="question12">
                                    <label for="g248"></label>
                                </div>
                            </div>
                        </div>
                        <!--12 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--13 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>13-	Inept is the opposite of:</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Healthy</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g249" name="question13">
                                    <label for="g249"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Deep</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g250" name="question13">
                                    <label for="g250"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Skillful</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g251" name="question13">
                                    <label for="g251"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Sad</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g252" name="question13">
                                    <label for="g252"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>Happy</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g253" name="question13">
                                    <label for="g253"></label>
                                </div>
                            </div>
                        </div>
                        <!--13 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--14 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>14-	A car traveled 28 miles in 30 minutes. How many miles per hour was it traveling?</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>28</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g254" name="question13">
                                    <label for="g254"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>36</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g255" name="question14">
                                    <label for="g255"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>56</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g256" name="question14">
                                    <label for="g256"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>58</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g257" name="question14">
                                    <label for="g257"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>62</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g258" name="question14">
                                    <label for="g258"></label>
                                </div>
                            </div>
                        </div>
                        <!--14 ROW END-->
                        <div class="col-lg-12"><br/></div>
                        <!--15 ROW STARTING-->
                        <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
                            <h4>15-	Sue is both the 50th best and the 50th worst student at her school. How many students attend her school?</h4>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>50</h5>
                                    <input value="1" type="radio" class="gudia-radio gudia-radio-satisfied" id="g259" name="question15">
                                    <label for="g259"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>75</h5>
                                    <input value="2" type="radio" class="gudia-radio gudia-radio-satisfied" id="g260" name="question15">
                                    <label for="g260"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>99</h5>
                                    <input value="3" type="radio" class="gudia-radio gudia-radio-satisfied" id="g261" name="question15">
                                    <label for="g261"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>100</h5>
                                    <input value="4" type="radio" class="gudia-radio gudia-radio-satisfied" id="g262" name="question15">
                                    <label for="g262"></label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                                    <h5>101</h5>
                                    <input value="5" type="radio" class="gudia-radio gudia-radio-satisfied" id="g263" name="question15">
                                    <label for="g263"></label>
                                </div>
                            </div>
                        </div>
                        <!--15 ROW END-->
                        <input type="hidden" id="emp_id" name="emp_id">
                        <div class="col-lg-12"><br/></div>
                        <div class="col-lg-12 col-md-12 text-center">
                            <button class="btn btn-success">Submit Test</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        function startTest()
        {

            var email=$('#email').val();
            var number=$('#number').val();

            if (email=='')
            {    $('#number').css('border-color', '');
                $('#email').css('border-color', 'red');
                return false;
            }
            if (number=='')
            {
                $('#number').css('border-color', 'red');
                $('#email').css('border-color', '');
                return false;
            }
            $('.loader_data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: "/hdc/viewEmployeeDataQuizTest",
                type: 'GET',
                data: {email:email,number:number},
                success: function(response)
                {

                    if (response=='0')
                    {
                        $('.alert').text('Email Or Mobile No# Must Be correct !!!!');
                        $(".alert").fadeIn();
                        $('.loader_data').html('');

                    }
                    else
                    {
                        $('#emp_id').val(response);
                        $('.loader_data').html('');
                        stopwatch();
                    }




                }


            });






        }


        function stopwatch()
        {
            $(".test_section").fadeIn();
            $(".unhide").fadeOut();

            var h1 =  document.getElementById('test_time'),
                    start = document.getElementById('start'),
                    stop = document.getElementById('stop'),
                    clear = document.getElementById('clear'),
                    seconds = 10, minutes = 0,
                    t;

            function add() {
                seconds--;
                if (seconds == 0) {
                    seconds = 60;
                    minutes--;

                }

                h1.textContent = ('Time: ')+(minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

                timer();
            }
            function timer() {


                t = setTimeout(add, 1000);

                if (minutes <0 && seconds==60)
                {

                    $('form').submit();
                }
            }
            timer();


        }
    </script>

                                                                                                                                                                                                                                                  