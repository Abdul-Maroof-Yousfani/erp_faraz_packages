<?php
$m = Session::get('run_company');
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Factory Overhead Category List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php echo Form::open(array('url' => 'prad/inser_over_head_category?m='.$m.'','id'=>'bom_form'));?>
                                            <div class="row">
                                                <div class="">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <input type="text" name="Name" id="Name" class="form-control requiredField" placeholder="Over Head Category Name">
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="Remarks" id="Remarks"  class="form-control requiredField" placeholder="Remarks" />
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                                                        {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo Form::close();?>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    &nbsp;
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Name</th>
                                                                <th class="text-center">Remarks</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $Counter = 1;
                                                            foreach(ProductionHelper::get_all_over_head_category() as $Fil):
                                                            ?>
                                                            <tr class="text-center">
                                                                <td><?php echo $Counter++;?></td>
                                                                <td><?php echo $Fil->name;?></td>
                                                                <td><?php echo $Fil->remarks;?></td>
                                                            </tr>
                                                            <?php endforeach;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

        $(document).ready(function(){


        $(".btn-success").click(function(e){
            var category = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            category.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of category) {

                jqueryValidationCustom();

                if(validate == 0)
                {

                }
                else
                {
                    return false;
                }
            }
        });
        });


    </script>

@endsection