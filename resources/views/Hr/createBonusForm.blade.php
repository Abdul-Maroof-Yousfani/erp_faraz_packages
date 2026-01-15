<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Bonus Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addBonusDetail','id'=>'EOBIform'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Bonus Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="Bonus_name[]" id="Bonus_name" value="" class="form-control requiredField" required />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>% of Salary:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="number" name="percent_of_salary[]" id="percent_of_salary" value="" class="form-control requiredField" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="BonusSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <input type="button" class="btn btn-sm btn-primary addMoreBonusSection" value="Add More Bonus Section" />
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

            $(".btn-success").click(function (e) {
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function () {
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                    } else {
                        return false;
                    }
                }

            });

            var bonus = 1;
            $('.addMoreBonusSection').click(function (e) {
                e.preventDefault();
                bonus++;
                $('.BonusSection').append('<div class="row myloader_' + bonus + '"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormBonusDetail',
                    type: "GET",
                    data: {id: bonus},
                    success: function (data) {

                        $('.BonusSection').append('<div id="sectionBonus' + bonus + '"><a style="cursor:pointer;" onclick="removeBonusSection(' + bonus + ')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' + data + '</div></div></div>');
                        $('.myloader_' + bonus).remove();
                    }
                });
            });

        });

        function removeBonusSection(id){
            var elem = document.getElementById('sectionBonus'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection