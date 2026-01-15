<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
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

    <div class="container-fluid">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Machine Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'production/insert_machine?m='.$m.'','id'=>'machine_form','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Machine Name <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Machine Name" name="MachineName" id="MachineName" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Code</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input autofocus type="text" class="form-control requiredField" placeholder="Code" name="Code" id="Code" value="" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Setup Time (Minutes)</label>
                                                <input  type="text" class="form-control requiredField" name="setup_time" id="setup_time" placeholder="Minutes"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label"> Equipment Cost <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calc()" onblur="calc()"  autofocus type="text" class="form-control requiredField" placeholder="" name="equi_cost" id="equi_cost" value="" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Salvage Cost</label>
                                                <span class="rflabelsteric"><strong>*</strong> <span id="DuplicateError" style="float: right"></span></span>
                                                <input onkeyup="calc()" onblur="calc()" autofocus type="text" class="form-control requiredField" placeholder="" name="salvage_cost" id="salvage_cost" value="" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Depreciable Cost</label>
                                                <input readonly type="text" class="form-control requiredField" name="dep_cost" id="dep_cost" placeholder="">
                                            </div>


                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Life in Pieces</label>
                                                <input onkeyup="calc()" onblur="calc()" type="text" class="form-control requiredField" name="life" id="life" placeholder="">
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Depreciation per Piece</label>
                                                <input readonly type="text" class="form-control requiredField" name="yearly_cost" id="yearly_cost" placeholder="">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Description</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="Description" id="Description" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
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
                                                    <th colspan="4" class="text-center">List Of Finish Goods Processed </th>
                                                    <th class="text-center">
                                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />    <span class="badge badge-success" id="span">1</span>
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
                                                <tr id="" class="AutoNo">

                                                    <td>
                                                        <select name="SubItemId[]" id="SubItemId1" class="form-control select2 requiredField MultiSubItem" >
                                                            <option value="">Select Finish Good</option>
                                                            <?php foreach(CommonHelper::get_finish_goods(1) as $Fil):?>
                                                            <option value="<?php echo $Fil->id?>" class="abc EnDis<?php echo $Fil->id?>"><?php echo $Fil->sub_ic?></option>
                                                            <?php endforeach;?>
                                                        </select>

                                                    </td>

                                                    <td>
                                                        <select name="DaiId0[]" id="DaiId1" multiple class="form-control select2 requiredField">
                                                            <option value="">Select Die</option>
                                                            <?php foreach(CommonHelper::get_dai() as $Fil):?>
                                                            <option value="<?php echo $Fil->id?>" ><?php echo $Fil->dai_name.' ('.$Fil->size.')'?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>

                                                    <td class="mold1">
                                                        <select  name="MoldId0[]" id="MoldId1" multiple class="form-control select2 requiredField">
                                                            <option value="">Select Mould</option>
                                                            <?php foreach(CommonHelper::get_mold() as $Fil):?>
                                                            <option value="<?php echo $Fil->id?>"><?php echo $Fil->mold_name.' ('.$Fil->size.')'?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control requiredField" name="QtyPerHour[]" id="QtyPerHour1" placeholder="No of Qty Per Hour">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control requiredField" name="ElectricityPerHour[]" id="ElectricityPerHour1" placeholder="No of Electricity Per Hour">
                                                    </td>
                                                </tr>
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
    </div>

    <script>




        $(document).ready(function(){
            $('.select2').select2();
        });
        var Counter = 1;
        var countt=0;


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
                    '</tr>' +
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

   ;
                $(this).attr('name', 'MoldId' + count+'[]');
                mould_count++;
            });


            $('.die').each(function (){

                alert($(this).attr('name'));
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
            console.log(life);
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