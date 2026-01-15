<?php
        use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>
@extends('layouts.default')

@section('content')
    <?php
    $currentDate = date('Y-m-d');
    ?>
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintCarPolicyCriteria','','1');?>
                        <?php echo CommonHelper::displayExportButton('CarPolicyCriteria','','1')?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        @include('Hr.'.$accType.'hrMenu')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Car Policy Criteria</span>
                                </div>
                            </div>
                            <div class="lineHeight"></div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Department:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $key => $y)
                                                            <optgroup label="All Employees" value="all"> <option selected value="all">All Employees</option></optgroup>
                                                            <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                <?php
                                                                $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                                ?>
                                                                @foreach($subdepartments as $key2 => $y2)
                                                                    <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <input type="button" class="btn btn-sm btn-primary" onclick="viewCarPolicyCriteria()" value="Check" style="margin-top: 32px;" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Car Policies List:</label>

                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="policy_id" id="policy_id">
                                                        <option value="">Select</option>
                                                        @foreach($carPolicy as $key => $value)
                                                            <option value="{{ $value['id'] }}">{{ $value['policy_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <input type="button" class="btn btn-sm btn-primary" onclick="viewCarPolicy($('#policy_id').val())" value="View Policy" style="margin-top: 32px;" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div id="PrintCarPolicyCriteria">
                                        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>

                                        <div class="viewCarPolicyArea" id="CarPolicyCriteria"></div>
                                    </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function viewCarPolicyCriteria()
        {
            $('.viewCarPolicyArea').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?= Input::get('m'); ?>';
            var sub_department_id = $('#sub_department_id').val();
            var url= '<?php echo url('/')?>/hdc/viewCarPolicyCriteria';
            $.getJSON(url, { sub_department_id:sub_department_id,m:m} ,function(result){
                $.each(result, function(i, field){

                    $('.viewCarPolicyArea').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+field+'</div>');

                });
        })
        }
        function viewCarPolicy(policy_id)
        {
            var m = '<?= Input::get('m'); ?>';
            var policy_id = policy_id;
            showDetailModelTwoParamerterJson('hdc/viewCarPolicy',policy_id,'View Car Policy ',m)

        }


        $(function(){
            $('select[name="sub_department_id"]').on('change', function() {
                var sub_department_id = $(this).val();
                var m = '<?= Input::get('m'); ?>';
                if(sub_department_id) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                        type: "GET",
                        data: { sub_department_id:sub_department_id,m:m},
                        success:function(data) {
                            $('select[name="employee_id"]').empty();
                            $('select[name="employee_id"]').html(data);
                        }
                    });
                }else{
                    $('select[name="employee_id"]').empty();
                }
            });
        });


    </script>
@endsection