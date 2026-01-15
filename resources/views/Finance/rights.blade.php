<?php
    use App\Helpers\CommonHelper;
    use App\Helpers\ReuseableCode;

    $data = ReuseableCode::get_main_sub_rights($user_id,$company_id);

    // echo "<pre>";
    // print_r($userData);
    // exit();
?>
<style>
    p{
        font-weight: bold;
    }

    .wrapper{
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 400px;
        margin: 50vh auto 0;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .switch_box{
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        max-width: 200px;
        /*min-width: 200px;*/
        /*height: 200px;*/
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }


    /* Switch 4 Specific Style Start */

    .box_4{
        /*background: #eee;*/
    }

    .input_wrapper{
        width: 80px;
        height: 40px;
        position: relative;
        cursor: pointer;
    }

    .input_wrapper input[type="checkbox"]{
        width: 80px;
        height: 40px;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: red;
        border-radius: 2px;
        position: relative;
        outline: 0;
        -webkit-transition: all .2s;
        transition: all .2s;
    }

    .input_wrapper input[type="checkbox"]:after{
        position: absolute;
        content: "";
        top: 3px;
        left: 3px;
        width: 34px;
        height: 34px;
        background: #dfeaec00;
        z-index: 2;
        border-radius: 2px;
        -webkit-transition: all .35s;
        transition: all .35s;
    }

    .input_wrapper svg{
        position: absolute;
        top: 50%;
        -webkit-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
        fill: #fff;
        -webkit-transition: all .35s;
        transition: all .35s;
        z-index: 1;
    }

    .input_wrapper .is_checked{
        width: 18px;
        left: 18%;
        -webkit-transform: translateX(190%) translateY(-30%) scale(0);
        transform: translateX(190%) translateY(-30%) scale(0);
    }

    .input_wrapper .is_unchecked{
        width: 15px;
        right: 10%;
        -webkit-transform: translateX(0) translateY(-30%) scale(1);
        transform: translateX(0) translateY(-30%) scale(1);
    }

    /* Checked State */
    .input_wrapper input[type="checkbox"]:checked{
        background: #23da87;
    }

    .input_wrapper input[type="checkbox"]:checked:after{
        left: calc(100% - 37px);
    }

    .input_wrapper input[type="checkbox"]:checked + .is_checked{
        -webkit-transform: translateX(0) translateY(-30%) scale(1);
        transform: translateX(0) translateY(-30%) scale(1);
    }

    .input_wrapper input[type="checkbox"]:checked ~ .is_unchecked{
        -webkit-transform: translateX(-190%) translateY(-30%) scale(0);
        transform: translateX(-190%) translateY(-30%) scale(0);
    }

    /* Switch 4 Specific Style End */
</style>



<?php $UserData = DB::selectOne('select a.*,b.name company_name from users a INNER JOIN company b ON b.id = a.company_id where a.emp_code = '.$user_id.'');

    $company_name=DB::table('company')->where('id',$company_id)->select('name')->value('name');
?>

<script !src="">
    $(document).ready(function(){
        $('.MainCheckBox1').prop('disabled',true);
        var Size1 = $('.End_Dis_1:checked').size();

        if(Size1 == 0)
        {$('.MainCheckBox1').prop('disabled',false);}
        else
        {$('.MainCheckBox1').prop('disabled',true);}


        $('.MainCheckBox2').prop('disabled',true);
        var Size2 = $('.End_Dis_2:checked').size();
        if(Size2 == 0)
        {$('.MainCheckBox2').prop('disabled',false);}
        else
        {$('.MainCheckBox2').prop('disabled',true);}

        $('.MainCheckBox3').prop('disabled',true);
        var Size3 = $('.End_Dis_3:checked').size();
        if(Size3 == 0)
        {$('.MainCheckBox3').prop('disabled',false);}
        else
        {$('.MainCheckBox3').prop('disabled',true);}

        $('.MainCheckBox4').prop('disabled',true);
        var Size4 = $('.End_Dis_4:checked').size();
        if(Size4 == 0)
        {$('.MainCheckBox4').prop('disabled',false);}
        else
        {$('.MainCheckBox4').prop('disabled',true);}

        $('.MainCheckBox5').prop('disabled',true);
        var Size5 = $('.End_Dis_5:checked').size();
        if(Size5 == 0)
        {$('.MainCheckBox5').prop('disabled',false);}
        else
        {$('.MainCheckBox5').prop('disabled',true);}

        $('.MainCheckBox6').prop('disabled',true);
        var Size6 = $('.End_Dis_6:checked').size();
        if(Size6 == 0)
        {$('.MainCheckBox6').prop('disabled',false);}
        else
        {$('.MainCheckBox6').prop('disabled',true);}

        $('.MainCheckBox7').prop('disabled',true);
        var Size7 = $('.End_Dis_7:checked').size();
        if(Size7 == 0)
        {$('.MainCheckBox7').prop('disabled',false);}
        else
        {$('.MainCheckBox7').prop('disabled',true);}

        EnabledDisabled(1);
        EnabledDisabled(2);
        EnabledDisabled(3);
        EnabledDisabled(4);
        EnabledDisabled(5);
        EnabledDisabled(6);
        EnabledDisabled(7);



    });

    function setOnOffText(Id,Code)
    {
        if ($('#OnOffId'+Id).is(':checked'))
        {
            $('#SetText'+Id).html('<i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i>');
        }
        else{
            $('#SetText'+Id).html('<i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>');

        }

        var Size = $('.End_Dis_'+Code+':checked').size();
        if(Size == 0)
        {
            $('.MainCheckBox'+Code).prop('disabled',false);
        }
        else
        {
            $('.MainCheckBox'+Code).prop('disabled',true);
        }


    }
    function EnabledDisabled(Code)
    {
        if ($('.MainCheckBox'+Code).is(':checked'))
        {
            $(".End_Dis_"+Code).prop('disabled',false);
        }
        else{
            $(".End_Dis_"+Code).prop('disabled',true);

        }



    }

</script>

<div class="" style="background-color: #fff;">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#dashboard_category" data-toggle="tab"> Dashboard / Category </a></li>
                <li><a href="#inventory" data-toggle="tab">Inventory</a></li>
                <li><a href="#inventory_master" data-toggle="tab">Inventory Master</a></li>
                {{--<li><a href="#inventory_reports" data-toggle="tab">Inventory Reports</a></li>--}}
                <li><a href="#sales" data-toggle="tab">Sales</a></li>
                <li><a href="#finance" data-toggle="tab">Finance</a></li>
                <li><a href="#reports" data-toggle="tab">Reports</a></li>
                <li><a href="#production" data-toggle="tab">Production</a></li>
                <li><a href="#import" data-toggle="tab">Import</a></li>
            </ul>
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 well">
                            <label for="">Name  <h3><?php echo $UserData->name?></h3></label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 well">
                            <label for="">Company   <h3><?php echo $company_name?></h3></label>
                        </div>

                    </div>
                </div>
                <div class="tab-pane active" id="dashboard_category">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">
                                @php
                                    $dashboard_access = explode(',',$userData->dashboard_access);
                                @endphp
                                <tbody id="">

                                    <tr id="">

                                        <td style="font-weight: bold">
                                           Dashboard Access
                                        </td>
                                        <td class="text-center">

                                           
                                        </td>
                                        <td id="SetText">
                                          
                                        </td>
                                        <td>
                                        
                                        </td>
                                    </tr>
                                    
                                    <tr id="">

                                        <td style="font-weight: bold">
                                             &emsp;&emsp;&emsp;&emsp; Dashboard
                                        </td>
                                        <td class="text-center">
                                            <input id="checkboxDash1" @if(in_array("dashboard", $dashboard_access)) checked @endif type="checkbox" name="dashboard_access[]" value="dashboard">
                                           
                                        </td>
                                        <td id="SetText">
                                          
                                        </td>
                                        <td>
                                        
                                        </td>
                                    </tr>
                                    <tr id="">

                                        <td style="font-weight: bold">
                                             &emsp;&emsp;&emsp;&emsp; Production Dashboard
                                        </td>
                                        <td class="text-center">
                                            <input id="checkboxDash2" @if(in_array("dashboard_production", $dashboard_access)) checked @endif type="checkbox" name="dashboard_access[]" value="dashboard_production">
                                           
                                        </td>
                                        <td id="SetText">
                                          
                                        </td>
                                        <td>
                                        
                                        </td>
                                    </tr>
                                    <tr id="">

                                        <td style="font-weight: bold">
                                             &emsp;&emsp;&emsp;&emsp; Management Dashboard
                                        </td>
                                        <td class="text-center">
                                            <input id="checkboxDash3" @if(in_array("dashboard_management", $dashboard_access)) checked @endif type="checkbox" name="dashboard_access[]" value="dashboard_management">
                                           
                                        </td>
                                        <td id="SetText">
                                          
                                        </td>
                                        <td>
                                        
                                        </td>
                                    </tr>
                                    
                                    @php
                                        $userCategory = explode(',',$userData->categories_id)
                                    @endphp
                                    
                                    <tr id="">

                                        <td style="font-weight: bold">
                                            Categories Access
                                        </td>
                                        <td class="text-center">

                                           
                                        </td>
                                        <td id="SetText">
                                          
                                        </td>
                                        <td>
                                        
                                        </td>
                                    </tr>

                                    @foreach($category as $key => $value)

                                        <tr id="">
                                            <td style="font-weight: bold">
                                                &emsp;&emsp;&emsp;&emsp; {{$value->main_ic}}
                                            </td>
                                            <td class="text-center">
                                            <input 
                                                @foreach($userCategory as $userCategorykey => $userCategoryvalue)
                                            
                                                @if($value->id == $userCategoryvalue ) checked @endif  
                                            
                                                    @endforeach 
                                                id="checkbox{{$value->id}}" type="checkbox" name="category[]" value="{{$value->id}}">
                                            
                                            </td>
                                            <td id="SetText">
                                            
                                            </td>
                                            <td>
                                            
                                            </td>
                                        </tr>
                                    @endforeach 
                                    

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="inventory">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(1) as $row)
                                    <?php






                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1) style="font-weight: bold" @endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">
                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_1 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox1 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','1');<?php if($row->first_level == 1):?> EnabledDisabled('1') <?php endif;?>"
                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>

                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="inventory_master">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(2) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);




                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">
                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_2 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox2 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','2');<?php if($row->first_level == 1):?> EnabledDisabled('2') <?php endif;?>"
                                                            @if($row->menu_type==1)

                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />


                                                </div>
                                            </div>


                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="inventory_reports">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(3) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_3 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox3 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','3');<?php if($row->first_level == 1):?> EnabledDisabled('3') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"
                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>
                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="sales">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(4) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_4 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox4 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','4');<?php if($row->first_level == 1):?> EnabledDisabled('4') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />
                                                </div>
                                            </div>

                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="finance">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(5) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_5 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox5 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','5');<?php if($row->first_level == 1):?> EnabledDisabled('5') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>


                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="reports">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(6) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_6 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox6 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','6');<?php if($row->first_level == 1):?> EnabledDisabled('6') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>


                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="production">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(405) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_7 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox7 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','7');<?php if($row->first_level == 1):?> EnabledDisabled('7') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>


                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane " id="import">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">

                                <tbody id="">
                                <?php
                                $count=1;

                                ?>
                                @foreach(CommonHelper::get_all_cost_center(195) as $row)
                                    <?php

                                    $array = explode('-',$row->code);
                                    $level = count($array);
                                    ?>
                                    <tr id="{{$row->id}}">

                                        <td @if($row->first_level==1)style="font-weight: bold"@endif>
                                            @if($level == 1)
                                                <p>    {{ ucwords($row->name)}}</p>
                                            @elseif($level == 2)
                                                <p>   {{ '&emsp;&emsp;'. ucwords($row->name)}}</p>
                                            @elseif($level == 3)
                                                {{ '&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @elseif($level == 4)
                                                {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. ucwords($row->name)}}
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <?php if($level != 2):?>
                                            <div class="switch_box box_4">
                                                <div class="input_wrapper">

                                                    <input  type="checkbox" class="switch_4 <?php if($row->first_level != 1):?> End_Dis_8 <?php endif;?> <?php if($row->first_level == 1):?> MainCheckBox8 <?php endif;?>" id="OnOffId<?php echo $row->id?>" onchange="setOnOffText('<?php echo $row->id?>','8');<?php if($row->first_level == 1):?> EnabledDisabled('8') <?php endif;?>"

                                                            @if($row->menu_type==1)
                                                            @if(in_array($row->main_menu_id,$data[0])) checked @endif
                                                            name="main[]"
                                                            value="{{$row->main_menu_id}}"

                                                            @elseif($row->menu_type==2)
                                                            @if(in_array($row->sub_menu_id,$data[1])) checked @endif
                                                            name="sub[]"
                                                            value="{{$row->sub_menu_id}}"

                                                            @elseif($row->menu_type==3)
                                                            @if(in_array($row->id,$data[2])) checked @endif
                                                            name="rights[]"
                                                            value="{{$row->id}}"
                                                            @endif
                                                            />

                                                </div>
                                            </div>


                                            <?php endif;?>
                                        </td>
                                        <td id="SetText<?php echo $row->id?>">
                                            @if($level != 2)
                                                @if($row->menu_type==1)
                                                    @if(in_array($row->main_menu_id,$data[0]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==2)
                                                    @if(in_array($row->sub_menu_id,$data[1]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif

                                                @elseif($row->menu_type==3)
                                                    @if(in_array($row->id,$data[2]))
                                                        <i class="fa fa-check text-success" aria-hidden="true" style="font-size: 25px;"></i> @else <i class="fa fa-ban text-danger" aria-hidden="true" style="font-size: 25px;"></i>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if($level != 2)
                                                {{$row->id}}
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<div class='ctbtn'>
<input class='btnn btn-secondary' type="submit" value="submit">
</div>

<script>
    $( "form" ).submit(function( event ) {
        $("input").attr("disabled", false);
    });
</script>
