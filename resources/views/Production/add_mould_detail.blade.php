
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
        $m = $_GET['m']
?>



<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Create Mould Detail</span>
                </div>

            </div>




      <?php   $name=DB::Connection('mysql2')->table('production_mold')->where('id',Request::get('id'))->select('mold_name')->value('mold_name'); ?>

        <?php echo Form::open(array('url' => 'production/insert_mould_detail','id'=>'dup_form','class'=>'stop'));?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table style="width: 90%;margin: auto" class="table table-bordered sf-table-list">
                        <thead>




                        <tr class="text-center">
                            <th colspan="2" class="text-center"><h3>(<?php echo $name?>)</h3> </th>
                            <th colspan="1" class="text-center"><input  type="button" class="btn btn-sm btn-primary" onclick="AddMoreRows()" value="Add More Rows" /></th>
                            <th class="text-center"><span class="badge badge-success" id="span"><?php ;?></span></th>
                        </tr>


                        <tr>
                            <th class="text-center" style="">Bacth Code</th>
                            <th class="text-center" style="">Life in (Pieces)</th>
                            <th class="text-center">Depreciable Cost<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center">Depreciation per Piece<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center">-</th>
                        </tr>
                        </thead>
                        <input type="hidden" name="mould_id" value="{{Request::get('id')}}"/>
                        <tbody id="append">

                        <?php $data=DB::Connection('mysql2')->table('mould_detail')->where('mould_id',Request::get('id')); ?>
                        <?php $count=1; ?>
                                @if ($data->count()>0)

                                    @foreach($data->get() as $row)

                                <tr class="text-center AutoNo">
                                    <td><input type="text" value="{{$row->batch_code}}" disabled class="form-control" name="bacth_code[]" id="bacth_code{{$count}}"/> </td>
                                    <td><input type="text" onkeyup="calc('{{$count}}')" onblur="calc('{{$count}}')" value="{{$row->life}}" disabled class="form-control" name="life[]" id="life{{$count}}"/></td>
                                    <td><input type="number" type="number" onkeyup="calc('{{$count}}')" onblur="calc('{{$count}}')" class="form-control requiredField value" id="value<?php echo $count?>" name="value[]" placeholder="value" value="<?php echo $row->value?>" disabled min="1"></td>
                                    <td><input type="number" readonly class="form-control requiredField cost" id="cost<?php echo $count?>" name="cost[]" placeholder="cost" value="<?php echo $row->cost?>" disabled min="1"></td>
                                    <td style="background-color: #ccc"><input type="button" class="btn btn-danger" value="Delete" onblur="delete_detail('{{$row->id}}')"/>  </td>

                                </tr>
                                        <?php $count++; ?>
                                @endforeach

                                    @else
                                    <tr class="text-center AutoNo">
                                        <td><input type="text" value="" class="form-control requiredField dup" name="bacth_code[]" id="bacth_code1" required/> </td>
                                        <td><input type="number" onkeyup="calc('{{$count}}')" onblur="calc('{{$count}}')" min="1" value="" class="form-control requiredField" name="life[]" id="life1" required/></td>
                                        <td><input type="number" onkeyup="calc('{{$count}}')" onblur="calc('{{$count}}')" type="number" onkeyup="calc('{{$count}}')" onblur="calc('{{$count}}')" class="form-control requiredField value" id="value<?php echo $count?>" name="value[]" placeholder="value" min="1"></td>
                                        <td><input type="number" readonly class="form-control requiredField cost" id="cost<?php echo $count?>" name="cost[]" placeholder="cost"  min="1"></td>
                                    </tr>
                                    <?php  ?>
                                    @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <input type="hidden" value="<?php echo $m?>" name="m">
            <?php ?>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

            <button type="submit" id="" class="btn btn-success">Submit</button>

        </div>

        <?php echo Form::close();?>

</div>
        </div>
    </div>







    <script>






        var count = '{{$count}}';
        function AddMoreRows(append,index)
        {
            count++;
            $('#append').append('<tr class="text-center AutoNo" id="RemoveRow'+count+'">' +

                    '<td><input type="text" class="form-control dup" name="bacth_code[]" id="bacth_code'+count+'"/> </td>' +
                    '<td><input type="number" onkeyup="calc('+count+')" onblur="calc('+count+')" class="form-control" name="life[]" id="life'+count+'"/></td>'+
                     '<td><input type="number" type="number" onkeyup="calc('+count+')" onblur="calc('+count+')" class="form-control requiredField value" id="value'+count+'" name="value[]" placeholder="value" min="1"></td>'+
                    '<td><input type="number"  readonly class="form-control requiredField cost" id="cost'+count+'" name="cost[]" placeholder="cost" min="1"></td>'+
                    '<td>' +
                    '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+count+'" onclick="RemoveRows('+count+')">-</button>' +
                    '</td>'+
                    '</tr><input type="hidden" name="update_id'+index+'" value="{{0}}"/>');


        }

        function RemoveRows(Rows)
        {
            $('#RemoveRow'+Rows).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }


        $(function() {
            $(".btn-success").click(function(e){
                var purchaseRequest = new Array();
                var val;
                purchaseRequest.push($(this).val());
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if(validate == 0)
                    {
                        vala = 0;
                        var flag = false;
                        $('.SendQty').each(function(){
                            vala = parseFloat($(this).val());
                            if(vala == 0)
                            {
                                alert('Please Enter Correct Transfer Qty....!');
                                $(this).css('border-color','red');
                                flag = true;
                                return false;
                            }
                            else
                            {
                                $(this).css('border-color','#ccc');
                            }
                        });
                        if(flag == true)
                        {return false;}
                    }
                    else
                    {
                        return false;
                    }
                }
            });
        });


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

            var life = parseFloat($('#life'+number).val());
            var value = parseFloat($('#value'+number).val());

            if(isNaN(value))
            {
                value = 0;
            }
            var total = parseFloat(value/life).toFixed(2);
            $('#cost'+number).val(total);
        }

    </script>