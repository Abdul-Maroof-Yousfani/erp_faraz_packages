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
                $('#parent_cost_center').fadeOut();
                $('#first_level').val(1);
            }

            else
            {
                $('.account_id').fadeIn();
                $('#parent_cost_center').fadeIn();
                $('#first_level').val(0);
            }
        }
    </script>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
            @include('Purchase.'.$accType.'purchaseMenu')
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Add New Cost Center</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <?php echo Form::open(array('url' => 'fad/addCostCenterForm?m='.$m.'','id'=>'addCategoryForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox">
                                        <label>
                                            <input onclick="check_uncheck()" type="checkbox" name="first_level_chk" value="checked"
                                                   id="first_level_chk" /><b>First Level ?</b></label>
                                        <input type="hidden" name="first_level" id="first_level" value=""/>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  account_id">
                                        <label class="account_id">Parent Cost Center:</label>
                                        <span class="rflabelsteric account_id"><strong>*</strong></span>
                                        <select  class="form-control select2" name="parent_cost_center" id="parent_cost_center">
                                            <option value="">Select Account</option>
                                            @foreach($cost_center as $key => $y)
                                                <option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <?php

                                        $MainMenu = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['status','=',1],['main_menu_id','!=','HR'],['main_menu_id','!=','HR Master']])->groupBy('main_menu_id')->get();
                                        $SubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['status', '=', 1]])->get();

                                        ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="">Main Menu</label>
                                        <select name="MainMenuId" id="MainMenuId" class="form-control">
                                            <option value="0">Select Main Menu</option>
                                            <?php foreach($MainMenu as $MainFil):?>
                                            <option value="<?php echo $MainFil->id?>"><?php echo $MainFil->main_menu_id?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="">Sub Menu</label>
                                        <select name="SubMenuId" id="SubMenuId" class="form-control">
                                            <option value="0">Select Main Menu</option>
                                            <?php foreach($SubMenu as $SubFil):?>
                                            <option value="<?php echo $SubFil->id?>"><?php echo $SubFil->name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>


                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Cost Center Name :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input autofocus style="text-transform: capitalize" type="text" name="cost_center" id="cost_center" value="" class="form-control requiredField" />
                                    </div>
                                        <input type="text" name="MenuType" id="MenuType" value="0">
                                    <div>&nbsp;</div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

        $('#parent_cost_center').select2();
        $('#MainMenuId').select2();
        $('#SubMenuId').select2();
    </script>
@endsection