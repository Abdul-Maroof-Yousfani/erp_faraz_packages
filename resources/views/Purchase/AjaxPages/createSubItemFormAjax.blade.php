<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\CommonHelper;
?>

@include('select2');
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                    @include('Purchase.'.$accType.'purchaseMenu')
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Add New Sub Item</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">

                                            <?php echo Form::open(array('url' => 'pdc/addSubItemDetailAjax?m='.$m.'','id'=>'addSubItemDetailajax'));?>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Main Category :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_sub_category()"  style="width: 100%" autofocus  name="category_name" id="CategoryId" class="form-control  select2">
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $key => $y)
                                                        <option value="{{ $y->id}}">{{ $y->main_ic}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Sub Item Name :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="sub_item_name" id="sub_category" value="" class="form-control" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>UOM :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%"  name="uom_id" id="uom_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach($uom as $key => $i)
                                                        <option value="{{ $i->id}}">{{ $i->uom_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
</div>

                                                <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label>Pack Size  :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control text-right" type="number" name="pack_size" id="pack_size">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label>Rate :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input step="0.01" class="form-control text-right" type="number" name="rate" id="rate">
                                            </div>

                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label>Description :</label>

                                                <textarea class="form-control" type="text" name="desc" id="desc"></textarea>
                                            </div>
                                                </div>

                                                <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Type</label>
                                                <select style="width: 100%" name="type" id="type" class="form-control">
                                                    <option value="">Select </option>
                                                    @foreach(CommonHelper::get_all_demand_type() as $row)
                                                        <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                                    @endforeach

                                                </select>

                                            </div>



                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Opening Quantity :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="opening_qty" id="opening_qty" value="" class="form-control" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Opening Value :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="opening_value" id="opening_value" value="" class="form-control" />
                                            </div>

                                            <input type="hidden" name="id" value="{{$id}}"/>
                                                    </div>
                                            <!--
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Re Order Level :</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="number" name="reorder_level" id="reorder_level" value="" class="form-control requiredField" />
                                        </div>
                                        <!-->
                                            <div>&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success text-right']) }}

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
</div>
<script type="text/javascript">




    $(document).ready(function(){


        $('#addSubItemDetailajax').submit(function(e)
        { e.preventDefault();




            var category=$('#category_name').val();
            var subitem=$('#sub_item_name').val();
            var uomid=$('#uom_id').val();
            var rate=$('#rate').val();
            var open_qty=$('#opening_qty').val();
            var open_value=$('#opening_value').val();
            if (category=='' || subitem=='' || uomid == '' || rate=='' || open_qty == '' || open_value == '' )
            {
                alert('Required All Fields And Rate Should Be Greaterc Than 0');
                return false;
            }

            var me=$(this);


            $.ajax({
                url:me.attr('action'),
                type:'post',
                data:me.serialize(),

                success: function(response)
                {


                    var result=response.split(',');

                    if (result[3]=='0')
                    {


                        for(i=1; i<=x; i++)
                        {

                            if ($('#category_id_1_'+i+'').val()==result[2])
                            {
                               
                                $('#sub_item_id_1_' + i + '').append($('<option>', {
                                    value: result[0],
                                    text: result[1]
                                }));
                            }

                            else
                            {

                            }

                        }


                    }

                    else
                    {
                      
                        for(i=1; i<=x; i++)
                        {

                            $('#sub_item_id_1_'+i+'').append($('<option>', {
                                value: result[0],
                                text: result[1]
                            }));

                        }
                    }


                    $("[data-dismiss=modal]").trigger({ type: "click" });


                }
            });
        });




    });
    function add_new(number)
    {
        if (number==1)
        {
            $("#cureency_id").css("display", "block");
            $("#remove").css("display", "block");
            $("#add_currency").css("display", "block");
        }

        else
        {
            $("#cureency_id").css("display", "none");
            $("#remove").css("display", "none");
            $("#add_currency").css("display", "none");
        }

    }

</script>
<script type="text/javascript">

    $('#category_name').select2();
    $('#uom_id').select2();
    $('#type').select2();

</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

