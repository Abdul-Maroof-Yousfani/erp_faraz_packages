<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;

$EditId = $_GET['edit_id']; //die();

$Master = DB::Connection('mysql2')->table('production_factory_overhead')->where('id',$EditId)->first();
$Detail = DB::Connection('mysql2')->table('production_factory_overhead_data')->where('master_id',$EditId)->get();
        //echo "<pre>";
        //print_r($Detail);
        //die();

?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    <style>
        * {
            font-size: 12px!important;
            font-family: Arial;
        }
        .select2 {
            width: 100%;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Factory Over Head </span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'prad/update_factory_over_head?m='.$m.'','id'=>'bom_form','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Over Head Name. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="text" class="form-control required" id="name" name="name" placeholder="Over Head Name" value="<?php echo $Master->name?>">
                                                <input type="hidden" name="EditId" id="EditId" value="<?php echo $EditId?>">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Over Head Category. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select name="over_head_category_id" id="over_head_category_id" class="form-control required select2">
                                                    <option value="">Select Category</option>
                                                    <?php foreach(ProductionHelper::get_all_over_head_category() as $Fil):?>
                                                    <option value="<?php echo $Fil->id?>" <?php if($Master->over_head_category_id == $Fil->id): echo "selected"; endif;?>><?php echo $Fil->name;?></option>
                                                    <?php endforeach;?>
                                                </select>

                                            </div>


                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea  name="desc" id="desc" rows="3" cols="40" style="resize:none;" class="form-control required"><?php echo $Master->desc?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" id="">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="4" class="text-center">Factory Over Head Detail</th>
                                                    <th class="text-center">
                                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="badge badge-success" id="span"><?php echo count($Detail);?></span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Sr No.</th>
                                                    <th class="text-center" style="width: 40% !important;">Account Head</th>
                                                    <th style="" class="text-center">Amount</th>
                                                    <th style="" class="text-center">No of Piece</th>
                                                    <th style="" class="text-center">Cost Per Piece</th>
                                                    <th style="" class="text-center">Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                <?php $Counter = 1;
                                                foreach($Detail as $Dfil):
                                                ?>
                                                <tr id="" class="AutoNo RemoveRows<?php echo $Counter?>">
                                                    <td class="text-center"><?php echo $Counter?></td>
                                                    <td>
                                                        <select name="acc_id[]" id="acc_id1" class="form-control select2 required">
                                                            <option value="">Select Account</option>
                                                            <?php foreach(CommonHelper::get_all_account() as $row):?>
                                                            <option value="<?php echo $row->id?>" <?php if($Dfil->acc_id == $row->id): echo "selected"; endif;?>><?php echo $row->code.'--'.$row->name;?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="amount<?php echo $Counter?>" name="amount[]" step="any" placeholder="Amount" onkeyup="CostCalc('<?php echo $Counter?>')" value="<?php echo $Dfil->amount?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="no_of_piece<?php echo $Counter?>" name="no_of_piece[]" step="any" placeholder="No Of Piece" onkeyup="CostCalc('<?php echo $Counter?>')" value="<?php echo $Dfil->no_of_piece?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control required" id="cost<?php echo $Counter?>" name="cost[]" step="any" placeholder="" readonly value="<?php echo $Dfil->cost?>">
                                                    </td>
                                                    <td>
                                                        <?php if($Counter > 1):?>
                                                            <button type='button' onclick='RemoveSection("<?php echo $Counter?>")' class='btn btn-danger btn-xs'>Remove</button>
                                                        <?php endif;?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $Counter++;
                                                endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="demandsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>




        $(document).ready(function(){
            $('.select2').select2();
        });
        var Counter = 1;
        var countt='<?php echo $Counter-1?>';


        function AddMoreDetails()
        {

            countt++;
            $('#AppnedHtml').append(
                    "<tr id='' class='AutoNo RemoveRows"+countt+"'>" +
                    "<td class='text-center'>" +countt+"</td>"+
                    "<td>" +
                    "<select style='width: 100%' class='form-control required select2' name='acc_id[]' id='acc_id"+countt+"'><option value=''>Select Account</option><?php foreach(CommonHelper::get_all_account() as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>" +
                    "<td>" +
                    "<input type='number' class='form-control required' id='amount"+countt+"' name='amount[]' step='any' placeholder='Amount' onkeyup='CostCalc("+countt+")'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='number' class='form-control required' id='no_of_piece"+countt+"' name='no_of_piece[]' step='any' placeholder='No Of Piece' onkeyup='CostCalc("+countt+")'>" +
                    "</td>" +
                    "<td>" +
                    "<input type='number' class='form-control required' id='cost"+countt+"' name='cost[]' step='any' placeholder='' readonly>" +
                    "</td>" +
                    "<td><button type='button' onclick='RemoveSection("+countt+")' class='btn btn-danger btn-xs'>Remove</button></td>"+
                    "</tr>");

            $('#acc_id'+countt).select2();

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });

        }

        function CostCalc(Row)
        {
            var Amount = $('#amount'+Row).val();
            var NoOfPiece = $('#no_of_piece'+Row).val();
            if(isNaN(NoOfPiece))
            {
                NoOfPiece = 0;
            }
            var Result = parseFloat(Amount/NoOfPiece).toFixed(2);
            $('#cost'+Row).val(Result);
        }

        function RemoveSection(Row) {
//            alert(Row);
            $('.RemoveRows' + Row).remove();
            $(".AutoCounter").html('');
            var AutoCount = 1;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).html(AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

        }

        $( "form" ).submit(function( event ) {

            var required = document.getElementsByClassName('required');

            for (i = 0; i < required.length; i++) {
                var rf = required[i].id;


                if ($('#' + rf).val() == '') {
                    $('#' + rf).css('border-color', 'red');
                    $('#' + rf).focus();
                    validate = 1;
                    alert(rf + ' ' + 'Required');
                    event.preventDefault();
                    return false;

                }
                else {

                    $('#' + rf).css('border-color', '#ccc');
                    validate = 0;
                }
            }

            if (validate==1)
            {
                event.preventDefault();
            }
            else
            {
                $('from').submit();
            }
        });

    </script>



    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection