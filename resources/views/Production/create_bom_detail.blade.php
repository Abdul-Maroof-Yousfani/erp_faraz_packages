<?php

use App\Helpers\CommonHelper;



$currentDate = date('Y-m-d');

$m = Session::get('run_company');
$id = $_GET['id'];

$BomData = DB::Connection('mysql2')->table('production_bom')->where('id',$id)->first();
$SelectedData = DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('main_id',$id)->get();
?>

@include('select2')
@include('modal')

<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Create Bom Detail</span>
                </div>

            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <?php echo Form::open(array('url' => 'prad/insert_bom_detail?m='.$m.'','id'=>'bom_form'));?>

                <input type="hidden" value="<?php echo $id?>" id="main_id" name="main_id">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="buildyourform" class="table table-bordered">
                                            <thead>
                                            <tr class="text-center">
                                                <th colspan="2" class="text-center"><h3>(<?php echo CommonHelper::get_item_name($BomData->finish_goods)?>)</h3> </th>
                                                <th colspan="1" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More Rows" /></th>
                                                <th class="text-center"><span class="badge badge-success" id="span"><?php echo count($SelectedData);?></span></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sr No</th>
                                                <th class="text-center" style="width: 60% !important;">Item</th>
                                                <th class="text-center">Uom<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Remove</th>
                                            </tr>
                                            </thead>
                                            <tbody class="" id="AppendHtml">
                                            <?php
                                            $Counter = 1;
                                            foreach($SelectedData as $fil):?>
                                            <tr class="text-center AutoNo">
                                                <td><?php echo $Counter;?></td>
                                                <td>
                                                    <select name="ItemId[]" id="ItemId<?php echo $Counter?>" class="form-control" disabled>
                                                        <?php foreach(CommonHelper::get_finish_goods(1) as $ItemFil):?>
                                                        <option value="<?php echo $ItemFil->id?>" <?php if($fil->item_id == $ItemFil->id): echo "selected"; endif;?>><?php echo $ItemFil->sub_ic?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </td>
                                                <td id="uom_name<?php echo $Counter?>"><?php echo CommonHelper::only_uom_nam_by_item_id($fil->item_id)?></td>
                                                <td>
                                                    <input type="text" class="form-control" id="Qty<?php echo $Counter?>" name="Qty[]" placeholder="Quantity" disabled value="<?php echo $fil->qty?>">
                                                </td>


                                                <td style="background-color: #ccc"></td>

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


    $('.sam_jass').bind("enterKey",function(e){


        $('#items').modal('show');


    });
    $('.sam_jass').keyup(function(e){
        if(e.keyCode == 13)
        {
            selected_id=this.id;
            $(this).trigger("enterKey");
        alert(this.id);

        }

    });


    $('.stop').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    var x = '<?php echo count($SelectedData)?>';
    var x2=1;
    function AddMorePvs()
    {
        x++;

        $('#AppendHtml').append('<tr class="text-center AutoNo" id="tr'+x+'" >' +
                '<td>'+x+'</td>' +
                '<td>' +
                '<textarea  type="text" class="form-control requiredField sam_jass" name="sub_ic_des[]" id="item_'+x+'" placeholder="ITEM"></textarea>' +
                '<input type="hidden" class="" name="item_id[]" id="sub_'+x+'" >'+
                '</td>' +
                '<td><input readonly="" type="text" class="form-control" name="uom_id[]" id="uom_id'+x+'"></td>' +
                '<td>' +
                '<input type="text" class="form-control requiredField" id="Qty'+x+'" name="Qty[]" placeholder="Quantity">' +
                '</td>'+
                '<td class="text-center"> <input type="button" onclick="RemoveRow('+x+')" value="Remove" class="btn btn-sm btn-danger"> </td>' +
                '</tr>');
        var AutoNo = $(".AutoNo").length;
        $('#span').text(AutoNo);

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



    function get_uom(Row)
    {
        var ItemId = $('#sub_'+Row).val();
        if(ItemId !="")
        {
            $.ajax({
                url:'{{url('/pdc/get_uom_name_by_item_id')}}',
                data: {ItemId: ItemId},
                type:'GET',
                success:function(response)
                {
                    $('#uom_name'+Row).html(response);
                }
            });
        }
        else
        {
            $('#uom_name'+Row).html(response);
        }


    }

    function RemoveRow(x)
    {
        $('#tr'+x).remove();
        var AutoNo = $(".AutoNo").length;
        $('#span').text(AutoNo);

    }
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
            }
        })



    }

</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
