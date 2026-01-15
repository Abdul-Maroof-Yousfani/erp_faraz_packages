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
    @include('select2')
    <script>
        function	check_uncheck()
        {
            if ($("#first_level_chk").is(":checked"))
            {
                $('.account_id').fadeOut();
                $('#account_id').fadeOut();
                $('#first_level').val(1);
            }

            else
            {
                $('.account_id').fadeIn();
                $('#account_id').fadeIn();
                $('#first_level').val(0);
            }
        }
    </script>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Department</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'fad/addDepartmentForm?m='.$m.'','id'=>'addCategoryForm'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox">
                                                        <label>
                                                            <input onclick="check_uncheck()" type="checkbox" name="first_level_chk" value="checked"
                                                                   id="first_level_chk" /><b>First Level ?</b></label>
                                                        <input type="hidden" name="first_level" id="first_level" value=""/>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12  account_id">
                                                        <label class="account_id">Parent Department Head:</label>
                                                        <span class="rflabelsteric account_id"><strong>*</strong></span>
                                                        <select  class="form-control select2" name="account_id" id="account_id">
                                                            <option value="">Select Account</option>
                                                            @foreach($department as $key => $y)
                                                                <option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                    <label>Department Name :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input autofocus style="text-transform: capitalize" type="text" name="dept_name" id="dept_name" value="" class="form-control requiredField" />
                                                </div>
                                                <div>&nbsp;</div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        &nbsp;&nbsp;&nbsp;
                                                        </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}


                                                    <?php
                                                    //echo Form::submit('Click Me!');
                                                    ?>
                                                </div>
                                                <?php
                                                echo Form::close();
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".btn-success").click(function(e){
                var category = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                category.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of category) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //return false;
                    }else{
                        return false;
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">

        $('#account_id').select2();
    </script>
@endsection