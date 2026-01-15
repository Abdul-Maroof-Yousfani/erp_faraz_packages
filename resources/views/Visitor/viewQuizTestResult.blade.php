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


        .glyphicon {  margin-bottom: 10px;margin-right: 10px;}

        small {
            display: block;
            line-height: 1.428571429;
            color: #999;
        }

        {box-sizing: border-box}

        .box {
            width: 100%;
            background-color: #ddd;
        }

        .skills {
            text-align: right;
            padding-right: 20px;
            line-height: 40px;
            color: white;
        }

        .html {width: 90%; background-color: #4CAF50;}
        .css {width: 80%; background-color: #2196F3;}
        .js {width: 65%; background-color: #f44336;}
        .php {width: 60%; background-color: #808080;}
    </style>
<?php
$anser=$quiztest->emp_answer;
$answers=explode(',',$anser);
        $count=0;
$quusitions='question';
      foreach ($answers as $row):

            $question[]=$row;
              $count++;

          endforeach;

 $answer1=$correct_ansers->right_answer;
$answers1=explode(',',$answer1);
$count1=0;
$right_answers='right_answer';
foreach ($answers1 as $row1):

    $right[]=$row1;
    $count1++;

endforeach;

        $result=0;
?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="well well-sm">
                    <div class="row">

                        <div class="col-sm-6 col-md-8">
                            <h4>
                                {{strtoupper($employe->emp_name.' '.$employe->emp_father_name)}}</h4>

                            <p>
                                <i class="glyphicon glyphicon-envelope"></i>{{$employe->emp_email}}
                                <br />
                                <i class="glyphicon glyphicon-phone"></i><a href="http://www.jquery2dotnet.com">{{$employe->emp_contact_no}}</a>
                                <br />
                            </p>
                            <!-- Split button -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="well">
        <div class="panel">
            <div class="panel-body">
<div id="" class="row text-center test_section">
    <div class="depart-row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 exit-inter-parts">
            <h3>TEST SECTION</h3>
        </div>
    </div>
</div>


<div  class="row page-section test_section">
    <!--1 ROW STARTING-->
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h4>1-	Which number should come next in the pattern? 37, 34, 31, 28</h4>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-xs-12">
                <input value="{{$question[0]}}" type="number" class="form-control"   name="question1">
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[0]==$question[0])
                    <p style="color: darkgreen">&#10004; Correct<p>
                       <?php    $result++; ?>
                    @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                    @endif
            </div>
        </div>
    </div>
    <!--1 ROW END-->

    <!--2 ROW STARTING-->
    <div class="col-lg-12 col-md-12 col-sm-12 page-section-iq">
        <h4>2-	Find the answer that best completes the analogy: Book is to Reading as Fork is to:</h4>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Drawing</h5>
                <input @if($question[1]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g200" value="1" name="question2">
                <label for="g200"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Writing</h5>
                <input @if($question[1]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g201" value="2" name="question2">
                <label for="g201"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Stirring</h5>
                <input @if($question[1]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g202" value="3" name="question2">
                <label for="g202"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Eating</h5>
                <input @if($question[1]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g203" value="4" name="question2">
                <label for="g203"></label>


            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[1]==$question[1])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[2]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g204" value="1" name="question3">
                <label for="g204"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Job and angry</h5>
                <input @if($question[2]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g205" value="2" name="question3">
                <label for="g205"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Talkative and loquacious</h5>
                <input @if($question[2]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g206" value="3" name="question3">
                <label for="g206"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Ecstatic and angry</h5>
                <input @if($question[2]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g207" value="4" name="question3">
                <label for="g207"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[2]==$question[2])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[3]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g208" value="1" name="question4">
                <label for="g208"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>R I L S A </h5>
                <input @if($question[3]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g209" value="2" name="question4">
                <label for="g209"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>T O O M T</h5>
                <input @if($question[3]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g210" value="3" name="question4">
                <label for="g210"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>W Q R G S</h5>
                <input @if($question[3]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g211" value="4" name="question4">
                <label for="g211"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[3]==$question[3])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[4]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g212" value="1" name="question5">
                <label for="g212"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>7</h5>
                <input @if($question[4]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g213" value="2" name="question5">
                <label for="g213"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>24</h5>
                <input @if($question[4]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g214" value="3" name="question5">
                <label for="g214"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>5</h5>
                <input @if($question[4]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g215" value="4" name="question5">
                <label for="g215"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[4]==$question[4])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[5]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g216" value="1" name="question6">
                <label for="g216"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Hat </h5>
                <input @if($question[5]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g217" value="2" name="question6">
                <label for="g217"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Hat</h5>
                <input @if($question[5]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g218" value="3" name="question6">
                <label for="g218"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Earrings</h5>
                <input @if($question[5]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g219" value="4" name="question6">
                <label for="g219"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Hairpins</h5>
                <input @if($question[5]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g220" value="5" name="question6">
                <label for="g220"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[5]==$question[5])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[6]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g221" value="1" name="question7">
                <label for="g221"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Valid</h5>
                <input @if($question[6]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g222" value="2" name="question7">
                <label for="g222"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>False</h5>
                <input @if($question[6]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g223" value="3" name="question7">
                <label for="g223"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Necessary</h5>
                <input @if($question[6]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g224" value="4" name="question7">
                <label for="g224"></label>
            </div>
            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[6]==$question[6])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input  @if($question[7]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g225" value="1" name="question8">
                <label for="g225"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Value</h5>
                <input  @if($question[7]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g226" value="2" name="question8">
                <label for="g226"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Meaning</h5>
                <input  @if($question[7]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g227" value="3" name="question8">
                <label for="g227"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Color</h5>
                <input  @if($question[7]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g228" value="4" name="question8">
                <label for="g228"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Quality</h5>
                <input  @if($question[7]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g229" value="5" name="question8">
                <label for="g229"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[7]==$question[7])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[8]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g230" value="1" name="question9">
                <label for="g230"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Be on the ball</h5>
                <input @if($question[8]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g231" value="2" name="question9">
                <label for="g231"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Lay down the law</h5>
                <input @if($question[8]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g232" value="3" name="question9">
                <label for="g232"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Point the Finger</h5>
                <input @if($question[8]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g233" value="4" name="question9">
                <label for="g233"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Sing Praises</h5>
                <input @if($question[8]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g234" value="5" name="question9">
                <label for="g234"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[8]==$question[8])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[9]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g235" value="1"  name="question10">
                <label for="g235"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>132</h5>
                <input @if($question[9]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g236" value="2" name="question10">
                <label for="g236"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>145</h5>
                <input @if($question[9]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g237" value="3" name="question10">
                <label for="g237"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>144</h5>
                <input @if($question[9]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g238" value="4" name="question10">
                <label for="g238"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>121</h5>
                <input @if($question[9]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g239" value="5" name="question10">
                <label for="g239"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[9]==$question[9])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[10]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g240" value="1" name="question11">
                <label for="g240"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>6</h5>
                <input @if($question[10]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g241" value="2" name="question11">
                <label for="g241"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>8</h5>
                <input @if($question[10]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g242" value="3" name="question11">
                <label for="g242"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>9</h5>
                <input @if($question[10]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g243" value="4" name="question11">
                <label for="g243"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>10</h5>
                <input @if($question[10]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g244" value="5" name="question11">
                <label for="g244"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[10]==$question[10])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[11]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g245" value="1" name="question12">
                <label for="g245"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>L</h5>
                <input @if($question[11]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g246" value="2" name="question12">
                <label for="g246"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>A</h5>
                <input @if($question[11]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g247" value="3"  name="question12">
                <label for="g247"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Y</h5>
                <input @if($question[11]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g248" value="4" name="question12">
                <label for="g248"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[11]==$question[11])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[12]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g249" value="1" name="question13">
                <label for="g249"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Deep</h5>
                <input @if($question[12]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g250" value="2" name="question13">
                <label for="g250"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Skillful</h5>
                <input @if($question[12]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g251" value="3" name="question13">
                <label for="g251"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Sad</h5>
                <input @if($question[12]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g252" value="4" name="question13">
                <label for="g252"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>Happy</h5>
                <input @if($question[12]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g253" value="5" name="question13">
                <label for="g253"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[12]==$question[12])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[13]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g254" value="1" name="question14">
                <label for="g254"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>36</h5>
                <input @if($question[13]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g255" value="2" name="question14">
                <label for="g255"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>56</h5>
                <input @if($question[13]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g256" value="3" name="question14">
                <label for="g256"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>58</h5>
                <input @if($question[13]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g257" value="4" name="question14">
                <label for="g257"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>62</h5>
                <input @if($question[13]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g258" value="5" name="question14">
                <label for="g258"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[13]==$question[13])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
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
                <input @if($question[14]==1)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g259" value="1" name="question15">
                <label for="g259"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>75</h5>
                <input @if($question[14]==2)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g260" value="2" name="question15">
                <label for="g260"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>99</h5>
                <input @if($question[14]==3)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g261" value="3" name="question15">
                <label for="g261"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>100</h5>
                <input  @if($question[14]==4)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g262" value="4" name="question15">
                <label for="g262"></label>
            </div>
            <div class="col-lg-2 col-md-2 col-xs-12 text-center">
                <h5>101</h5>
                <input @if($question[14]==5)checked @endif type="radio" class="gudia-radio gudia-radio-satisfied" id="g263" value="5" name="question15">
                <label for="g263"></label>
            </div>

            <div  class="col-lg-2 col-md-2 col-xs-12">

                @if($right[14]==$question[14])
                    <p style="color: darkgreen">&#10004; Correct<p>
                    <?php    $result++; ?>
                @else
                    <p style="color: crimson"> &#x2716;Wrong</p>
                @endif
            </div>
        </div>
    </div>
    <!--15 ROW END-->
    <div class="col-lg-12"><br/></div>

</div>
<input type="hidden" id="emp_id" name="emp_id"/>
    </div></div></div>
<?php  $final=$result/15*100; ?>
    <h3 style="text-align: center">{{number_format($final,2).'%'.' '.'Achived'}}</h3>
    <div class="col-lg-12"><br/></div>
    @endsection
