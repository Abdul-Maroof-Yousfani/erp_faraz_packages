<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}?>
@extends('layouts.default')
@section('content')
    <style>
        .the-period li {
            margin: 2px 0px 6px 0px;
        }
        .name-input {
            width: 200px;
            display: inline-block;
        }
    </style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Employee History Varification</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                        <div class="panel-body">
                                <form id="subm" method="post" action="{{url('had/addEmployeeHistoryVerification')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="container">
                                        <div class="row text-center">
                                            <div class="col-lg-12 col-md-12 col-xs-12- col-xs-12">
                                                <h2><strong>EMPLOYMENT HISTORY VERIFICATION</strong></h2>
                                                <h4><strong>(ALL INFORMATION GIVEN SHALL BE KEPT STRICTLY CONFIDENTIAL)</strong></h4>
                                            </div>
                                        </div>
                                        <div  style="color: crimson;text-align: center" class="row loader_data">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <h3>Dear Sir /Madam,</h3>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <p>Please take a few moments to provide us some information about Mr./Mrs./Ms.
                                                    <select onchange="viewEmployeeDataForHistoryVerification(this.id)" name="emp_id" id="emp_id"
                                                            class="form-control name-input requiredField">
                                                        <option value="">SELECT</option>
                                                        @foreach($employee as $row)
                                                            <option value="{{$row['id']}}">{{$row['emp_name']}}</option>
                                                        @endforeach;
                                                    </select>
                                                    who has applied at Gudia Private Limited for the post of
                                                    <input readonly type="text" class="form-control name-input position" name="position"
                                                           id="position">
                                                </p>
                                                <ul class="list-unstyled the-period">
                                                    <li>1. The period he/she worked at your Organization:From
                                                        <input readonly type="date" class="form-control name-input" name="from" id="from"> To <input
                                                                readonly type="date" class="form-control name-input" name="to" id="to">
                                                    </li>
                                                    <li>2. What was the applicant�s position/title:
                                                        <input readonly type="" class="form-control name-input position" name="position"
                                                               id="position">
                                                    </li>
                                                    <li>3. What information can you give concerning:</li>
                                                    <br/>
                                                    <li>Quality of work?</li>
                                                    <li>
                                                        <textarea name="quality_work" id="quality_work" class="form-control requiredField" rows="5"></textarea>
                                                    </li>

                                                    <br/>
                                                    <li>Quantity of work?</li>
                                                    <li>
                                                        <textarea name="quantity_work" id="quantity_work" class="form-control requiredField" rows="5"></textarea>
                                                    </li>
                                                    <br/>
                                                    <li>Attendance?</li>
                                                    <li>
                                                        <textarea name="attendance" id="attendance" class="form-control requiredField" rows="5"></textarea>
                                                    </li>
                                                    <br/>
                                                    <li>4. Did this person get along well with others?</li>
                                                    <div class="radio">
                                                        <label><input checked type="radio" id="behavior1" value="1" name="behavior">Yes</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input type="radio" value="2" id="behavior2" name="behavior">No</label>
                                                    </div>
                                                    <br/>
                                                    <li>5. Why did he/she left your company?</li>
                                                    <li><textarea name="leave_reson" id="leave_reson" class="form-control requiredField" rows="5"></textarea>
                                                    </li>
                                                    <br/>
                                                    <li>6. Is he/she eligible for rehire with your company?</li>


                                                    <div class="radio">
                                                        <label><input onclick="readonly(this.id)" id="eligible1" checked type="radio" value="1"
                                                                      name="eligible">Yes</label>
                                                    </div>
                                                    <div class="radio">
                                                        <label><input onclick="readonly(this.id)" type="radio" id="eligible2" value="2"
                                                                      name="eligible">No</label>
                                                    </div>
                                                    <br>
                                                    <li>If not, why?</li>
                                                    <li><textarea readonly name="eligible_reason" id="eligible_reason" class="form-control" rows="5"></textarea>
                                                    </li>
                                                    <br/>
                                                    <li>Additional Comments:</li>
                                                    <li><textarea name="additional_comments" id="additional_comments" class="form-control requiredField" rows="5"></textarea>
                                                    </li>

                                                    <br/>
                                                    <li>Information from : <input type="text" class="form-control name-input requiredField"
                                                                                  name="inform_from" id="inform_from">
                                                        Position Title : <input type="text" class="form-control name-input requiredField"
                                                                                name="position_titke" id="position_titke"></li>
                                                    <br/>
                                                    <li>Date : <input type="date" class="form-control name-input" name="" id=""
                                                                      value="{{date('Y-m-d')}}"></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div style="text-align: center">
                                            <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        function viewEmployeeDataForHistoryVerification(id) {
            var emp_val = $('#' + id).val();

            if (emp_val=='')
            {
                document.getElementById("subm").reset();
                return false;

            }
            $('.loader_data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: "/hdc/viewEmployeeDataForHistoryVerification",
                type: 'GET',
                data: {emp_val: emp_val},
                success: function (response) {
                    $('.loader_data').html('');
                    var result = response.split(",");
                    $('.position').val(result[0]);
                    $('#from').val(result[1]);
                    $('#to').val(result[2]);
                    if (result[3] > 0) {
                        var id = $('#emp_id').val();
                        form = document.getElementById('subm');
                        form.id = 'subm';
                        form.action = "{{url('had/editEmployeeHistoryVerification/')}}" + '/' + id;
                        form.method = 'POST';
                        $('textarea#quality_work').val(result[4]);
                        $('textarea#quantity_work').val(result[5]);
                        $('textarea#attendance').val(result[6]);
                        if (result[7] == 1) {
                            $("#behavior1").prop("checked", true);
                        }
                        else {
                            $("#behavior2").prop("checked", true);
                        }
                        $('textarea#leave_reson').val(result[8]);
                        if (result[9] == 1) {
                            $("#eligible1").prop("checked", true);
                        }
                        else {
                            $("#eligible2").prop("checked", true);
                            $("#eligible_reason").prop('readonly', false);
                            $("#eligible_reason").removeClass("requiredField");
                        }
                        $('textarea#eligible_reason').val(result[10]);
                        $('textarea#additional_comments').val(result[11]);
                        $('#inform_from').val(result[12]);
                        $('#position_titke').val(result[13]);
                    }
                    else{$('textarea').val('');$('#inform_from').val('');$('#position_titke').val('')
                        form = document.getElementById('subm');
                        form.id = 'subm';
                        form.action = "{{url('had/addEmployeeHistoryVerification/')}}";
                        form.method = 'POST';}
                }
            });
        }
        $(document).ready(function () {
            $("form").submit(function () {
                var message = $('textarea#quality_work').val();
                var input = document.getElementsByClassName('requiredField');
                var v = input.length;
                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++) {
                    var v = input[i].id;
                    if (v == '') { }
                    else {
                        if ($('#' + v).val() == '' || $('textarea' + '#' + v).val() == '') {

                            $('#' + v).css('border-color', 'red');
                            $('#' + v).focus();
                            return false;
                        }
                        else {
                            $('#' + v).css('border-color', '#ccc');
                        }
                    }
                }

            });
        });

        function readonly() {
            var v = $("input[name='eligible']:checked").val();
            if (v == 2) {
                $("#eligible_reason").prop('readonly', false);
                $("#eligible_reason").addClass("requiredField");
            }
            else {
                $("#eligible_reason").prop('readonly', true);
                $("#eligible_reason").removeClass("requiredField");
            }
        }
    </script>
@endsection