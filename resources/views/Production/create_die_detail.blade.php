<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

        $id = $_GET['id'];
$DaiData = DB::Connection('mysql2')->table('production_dai')->where('id',$id)->first();
        $SelectedData = DB::Connection('mysql2')->table('production_dai_detail')->where('main_id',$id)->get();
?>


    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Die Detail</span>
                    </div>

                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'prad/insert_dai_detail?m='.$m.'','id'=>'dup_form'));?>

                        <input type="hidden" value="<?php echo $DaiData->id?>" id="main_id" name="main_id">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="table table-bordered">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="2" class="text-center"><h3>(<?php echo $DaiData->dai_name?>)</h3> </th>
                                                    <th colspan="1" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More Rows" /></th>
                                                    <th class="text-center"><span class="badge badge-success" id="span"><?php echo count($SelectedData);?></span></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Sr No</th>
                                                    <th class="text-center">Batch Code</th>
                                                    <th class="text-center">Life in (Pieces)<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Depreciable Cost<span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Depreciation per Piece <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody class="" id="AppendHtml">
                                                <?php
                                                $Counter = 1;
                                                foreach($SelectedData as $fil):?>
                                                <tr class="text-center AutoNo">

                                                    <td><?php echo $Counter++;?></td>
                                                    <td><input onkeyup="" type="text" class="form-control requiredField dup" id="batch_code_<?php echo $Counter?>" name="batch_code[]" placeholder="Batch Code" value="<?php echo $fil->batch_code?>" disabled></td>
                                                    <td><input type="number" onkeyup="calc('{{$Counter}}')" onblur="calc('{{$Counter}}')" class="form-control requiredField Life" id="life_<?php echo $Counter?>" name="life[]" placeholder="Life" value="<?php echo $fil->life?>" disabled min="1"></td>
                                                    <td><input type="number" type="number" onkeyup="calc('{{$Counter}}')" onblur="calc('{{$Counter}}')" class="form-control requiredField value" id="value<?php echo $Counter?>" name="value[]" placeholder="value" value="<?php echo $fil->value?>" disabled min="1"></td>
                                                    <td><input type="number" readonly class="form-control requiredField cost" id="cost<?php echo $Counter?>" name="cost[]" placeholder="cost" value="<?php echo $fil->cost?>" disabled min="1"></td>
                                                    <td style="background-color: #ccc"><input type="button" class="btn btn-danger" value="Delete" onblur="delete_detail('{{$fil->id}}')"/>  </td>

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
                    <div class="pvsSection"></div>
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






        var x = '{{$Counter}}';
        var x2=1;
        function AddMorePvs()
        {
            x++;

            $('#AppendHtml').append('<tr class="text-center AutoNo" id="tr'+x+'" >' +
                    '<td class="auto_span"></td>' +
                    '<td><input onkeyup="" type="text" class="form-control requiredField dup" required id="batch_code_'+x+'" name="batch_code[]" placeholder="Batch Code" ></td>'+
                    '<td><input onkeyup="calc('+x+')" onblur="calc('+x+')" type="number" class="form-control requiredField Life" required id="life_'+x+'" name="life[]" placeholder="Life" min="1"></td>'+
                    '<td><input onkeyup="calc('+x+')" onblur="calc('+x+')" type="number" class="form-control requiredField value" required id="value'+x+'" name="value[]" placeholder="value" min="1"></td>'+
                    '<td><input readonly  type="number" class="form-control requiredField cost" required id="cost'+x+'" name="cost[]" placeholder="cost" min="1"></td>'+
                    '<td class="text-center"> <input type="button" onclick="RemoveRow('+x+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
                    '</tr>');
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            $('#auto_span').text(AutoNo);


            // $('.d_amount_1_3').number(true,2);
        }

        function RemoveRow(x)
        {
            $('#tr'+x).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

        }
        $( "#dup_form" ).submit(function( event ) {
            var array=[];
            var validate=true;
            var val='';
            $(".dup").each(function( index ) {

                if(jQuery.inArray($(this).val(), array) !== -1)
                {

                    validate=false;
                    val=$(this).val();
                    event.preventDefault();
                }
                else
                {
                    array.push($(this).val());

                }

            });

            if (validate==false)
            {
                alert(val +' Duplicate');
            }
            else
            {
                $('#dup_form').submit();
            }

        });


        function calc(number)
        {
            var life = parseFloat($('#life_'+number).val());
            var value = parseFloat($('#value'+number).val());

            if(isNaN(value))
            {
                value = 0;
            }
            var total = parseFloat(value/life).toFixed(2);
            $('#cost'+number).val(total);
        }

    </script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
