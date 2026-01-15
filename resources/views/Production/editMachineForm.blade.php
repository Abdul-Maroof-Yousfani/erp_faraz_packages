<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;

$EditId = $_GET['edit_id'];
$Machine = DB::Connection('mysql2')->table('production_machine')->where('status',1)->where('id',$EditId)->first();
$MachineDetail = DB::Connection('mysql2')->table('production_machine_data')->where('status',1)->where('master_id',$EditId)->orderBy('id','ASC')->offset(0)->limit(300)->get();
$time=$Machine->setup_time;
$timesplit=explode(':',$time);
$min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);



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
                        <span class="subHeadingLabelClass">Create Machine Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'production/update_machine?m='.$m.'','id'=>'machine_form','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Machine Name. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Machine Name" name="MachineName" id="MachineName" value="<?php echo $Machine->machine_name?>" />
                                                <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $EditId?>">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Code.</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Code" name="Code" id="Code" value="<?php echo $Machine->code?>" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Setup Time (Minutes)</label>
                                                <input  type="text" class="form-control requiredField" name="setup_time" id="setup_time" placeholder="Minutes"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo $min?>">
                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Total Equipment Cost <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calc()" onblur="calc()"  autofocus type="text" class="form-control requiredField" placeholder="" name="equi_cost" id="equi_cost" value="<?php echo $Machine->equi_cost?>" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Salvage cost</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input onkeyup="calc()" onblur="calc()" autofocus type="text" class="form-control requiredField" placeholder="" name="salvage_cost" id="salvage_cost" value="<?php echo $Machine->salvage_cost?>" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Depreciable Cost</label>
                                                <input readonly type="text" class="form-control requiredField" name="dep_cost" id="dep_cost" placeholder="" value="<?php echo $Machine->dep_cost?>">
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Life</label>
                                                <input onkeyup="calc()" onblur="calc()" value="{{$Machine->life}}" type="text" class="form-control requiredField" name="life" id="life" placeholder="">
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Annual Depression</label>
                                                <input readonly type="text" class="form-control requiredField" name="yearly_cost" id="yearly_cost" placeholder="" value="<?php echo $Machine->yearly_cost?>">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="Description" id="Description" rows="4" cols="50" style="resize:none;" class="form-control requiredField"><?php echo $Machine->description?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                                        <table class="table table-bordered">
                                            <thead>
                                            <tr class="text-center">
                                                <th colspan="2" class="text-center">Machine Detail</th>
                                                <th class="text-center">
                                                    <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                                </th>
                                                <th class="text-center">
                                                    <span class="badge badge-success" id="span"><?php echo count($MachineDetail);?></span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center col-sm-4">Product Name</th>

                                                <th class="text-center col-sm-2">Die</th>
                                                <th class="text-center col-sm-2" >Mould</th>

                                                <th class="text-center col-sm-2" >Pieces Per Hour<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center col-sm-2 " >KW of Electricity Per Hour<span class="rflabelsteric"><strong>*</strong></span></th>
                                            </tr>
                                            </thead>
                                            <tbody id="AppnedHtml">
                                            <?php $Counter = 1;
                                                    $AnotherCounter = 0;
                                            foreach($MachineDetail as $DFil):

                                            ?>
                                            <input type="hidden" name="data_id[]" value="{{$DFil->id}}"/>
                                            <tr id="" class="AutoNo RemoveRows<?php echo $Counter?>">

                                                <td>
                                                    <select name="SubItemId[]" id="SubItemId<?php echo $Counter;?>" class="form-control select2 requiredField MultiSubItem" >
                                                 

                                                        <option value="<?php echo $DFil->finish_good ?>" class="abc EnDis">
                                                            <?php echo CommonHelper::get_item_name($DFil->finish_good)?>
                                                        </option>

                                                    </select>

                                                </td>

                                                <td>
                                                    <select name="DaiId<?php echo $AnotherCounter?>[]" id="DaiId<?php echo $Counter?>" multiple class="form-control select2 requiredField <?php if($Counter > 1):?>die <?php endif;?>">
                                                        <option value="">Select Die</option>
                                                        <?php foreach(CommonHelper::get_dai() as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"  <?php if(in_array($Fil->id,explode(',',$DFil->dai_id))): echo "selected"; endif;?>>
                                                            <?php echo $Fil->dai_name.' ('.$Fil->size.')'?>
                                                        </option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </td>

                                                <td class="mold1">
                                                    <select  name="MoldId<?php echo $AnotherCounter?>[]" id="MoldId<?php echo $Counter?>" multiple class="form-control select2 requiredField <?php if($Counter > 1):?> mould <?php endif;?>">
                                                        <option value="">Select Mould</option>
                                                        <?php foreach(CommonHelper::get_mold() as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>" <?php if(in_array($Fil->id,explode(',',$DFil->mold_id))): echo "selected"; endif;?>>
                                                            <?php echo $Fil->mold_name.' ('.$Fil->size.')'?>
                                                        </option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control requiredField" name="QtyPerHour[]" id="QtyPerHour<?php echo $Counter?>" placeholder="No of Qty Per Hour" value="<?php echo $DFil->qty_per_hour?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control requiredField" name="ElectricityPerHour[]" id="ElectricityPerHour<?php echo $Counter?>" placeholder="No of Electricity Per Hour" value="<?php echo $DFil->electricity_per_hour?>">
                                                </td>
                                                <td class="text-center">
                                                    <?php if($Counter > 1):?>
                                                    <button type="button" class="btn btn-xs btn-danger" id="BtnRemove<?php echo $Counter?>" onclick="RemoveSection('<?php echo $Counter?>')">Remove</button>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                            <?php
                                            $Counter++;
                                            $AnotherCounter++;
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
                        {{ Form::submit('Update', ['class' => 'btn btn-success']) }}

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
        var Counter = '<?php echo count($MachineDetail);?>';
      
        var countt='<?php echo count($MachineDetail)-1;?>';





        function AddMoreDetails()
        {


            Counter++;
            countt++;
            $('#AppnedHtml').append(
                    '<tr class="RemoveRows'+Counter+'  AutoNo">' +
                    '<td>' +
                    '<select name="SubItemId[]" id="SubItemId'+Counter+'" class="form-control select2 requiredField MultiSubItem" >' +
                    '<option value="">Select Finish Good</option>'+
                    <?php foreach(CommonHelper::get_finish_goods(1) as $Fil):?>
                    '<option value="<?php echo $Fil->id?>" class="abc EnDis<?php echo $Fil->id?>"><?php echo $Fil->sub_ic?></option>'+
                    <?php endforeach;?>
                    '</select>'+
                    '</td>' +
                    '<td>' +
                    '<select name="DaiId'+countt+'[]" multiple id="DaiId'+Counter+'" class="form-control select2 die requiredField">' +
                    '<option value="">Select Die</option>'+
                    <?php foreach(CommonHelper::get_dai() as $Fil):?>
                    '<option value="<?php echo $Fil->id?>"><?php echo $Fil->dai_name.' ('.$Fil->size.')'?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<select name="MoldId'+countt+'[]" multiple id="MoldId'+Counter+'" class="form-control select2 requiredField mould">' +
                    '<option value="">Select Mould</option>'+
                    <?php foreach(CommonHelper::get_mold() as $Fil):?>
                    '<option value="<?php echo $Fil->id?>"><?php echo $Fil->mold_name.' ('.$Fil->size.')'?></option>'+
                    <?php endforeach;?>
                    '</select>' +
                    '</td>'+
                    '<td>' +
                    '<input type="text" class="form-control requiredField" name="QtyPerHour[]" id="QtyPerHour'+Counter+'" placeholder="No of Qty Per Hour">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" class="form-control requiredField" name="ElectricityPerHour[]" id="ElectricityPerHour'+Counter+'" placeholder="No of Electricity Per Hour">' +
                    '</td>' +

                    '<td  class="text-center" style="">' +
                    '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveSection('+Counter+')">Remove</button>' +
                    '</td>' +
                    '</tr>' +
                    '</tr><input type="hidden" name="data_id[]" value="0"/>' +
                    '</tbody>' +
                    '</table>');
            $('#SubItemId'+Counter).select2();
            $('#DaiId'+Counter).select2();
            $('#MoldId'+Counter).select2();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });




            $('.sam_jass').bind("enterKey",function(e){


                $('#items').modal('show');
            });
            $('.sam_jass').keyup(function(e){
                if(e.keyCode == 13)
                {
                    selected_id=this.id;
                    $(this).trigger("enterKey");
                }
            });

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

            var count=1;
            $('.mould').each(function (){


                $(this).attr('name', 'MoldId' + count+'[]');
                mould_count++;
            });


            $('.die').each(function (){


                $(this).attr('name', 'DaiId' + count+'[]');
                mould_count++;
            });
        }

        function CheckDuplicate()
        {

            var Code = $('#Code').val();
            $.ajax({
                url:'{{url('/production/machineCodeCheck')}}',
                data:{Code:Code},
                type:'GET',
                success:function(response)
                {
                    if(response == 'duplicate')
                    {
                        $('#DuplicateError').html('<span class="text-danger">Batch Code already Exist in (Machine).</span>');
                        $('.btn-success').prop('disabled',false);
                        return false;
                    }
                    else if(response == 'yes')
                    {

                    }
                    else{}
                }
            });
        }


        function clear_fiel(id)
        {
            $('#'+id).prop('readonly', false);
            $('#'+id).val('');

        }

        $('.sam_jass').bind("enterKey",function(e){


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        $(function() {



            $(".btn-success").click(function(e){
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0)
                    {
                        CheckDuplicate();
                    }
                    else
                    {
                        return false;
                    }
                }

            });
        });








    </script>


    <script>
        function get_detail(id,number)
        {
            var item=$('#'+id).val();


            $.ajax({
                url:'{{url('/pdc/get_data')}}',
                data:{item:item},
                type:'GET',
                success:function(response)
                {

                    var data=response.split(',');
                    $('#uom_id'+number).val(data[0]);
                    $('#last_ordered_qty'+number).val(data[1]);
                    $('#last_received_qty'+number).val(data[2]);
                    $('#closing_stock'+number).val(data[3]);

                }
            })



        }

    </script>

    <script>



        function view_history(id)
        {
            var v= $('#sub_item_id_1_'+id).val();

            if ($('#history_1_' + id).is(":checked"))
            {
                if (v!=null)
                {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem?id='+v);
                }
                else
                {
                    alert('Select Item');
                }

            }
        }

        function add_mould(number)
        {
            $('.mold1').append('<select ondblclick="add_mould(1)" name="MoldId[]" id="MoldId1" class="form-control select2 requiredField">' +
                    '<option value="">Select Mold</option><?php foreach(CommonHelper::get_mold() as $Fil):?><option value="<?php echo $Fil->id?>">'+
                    '<?php echo $Fil->mold_name?></option> <?php endforeach;?></select>'+
                    '<button type="button" class="btn btn-danger btn-xs">Remove</button>')

        }

        function calc()
        {
            var  equi_cost=parseFloat($('#equi_cost').val());
            var  life=parseFloat($('#life').val());
            if (isNaN(equi_cost))
            {
                equi_cost=0;
            }


            if (isNaN(life))
            {
                life=0;
            }



            var salvage_cost=  parseFloat($('#salvage_cost').val());
            if (isNaN(salvage_cost))
            {
                salvage_cost=0;
            }
            var dep_cost=equi_cost-salvage_cost;

            if (dep_cost<0)
            {
                dep_cost='';
            }
            $('#dep_cost').val(dep_cost);
            var yearly=  (dep_cost / life).toFixed(2);
            if (yearly==0)
            {
                yearly='';
            }
            $('#yearly_cost').val(yearly);
        }
    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection