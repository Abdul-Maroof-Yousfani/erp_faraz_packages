<?php


        use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

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

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
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
                                    <?php echo Form::open(array('url' => 'pdc/addCostCenterFormajax?m='.$m.'','id'=>'CostCenterForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox">
                                        <label>
                                            <input onclick="check_uncheck()" type="checkbox" name="first_level_chk" value="checked"
                                                   id="first_level_chk" /><b>First Level ?</b></label>
                                        <input type="hidden" name="first_level" id="first_level" value=""/>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12  account_id">
                                        <label class="account_id">Parent Cost Center:</label>
                                        <span class="rflabelsteric account_id"><strong>*</strong></span>
                                        <select style="width: 100%"  class="form-control select2" name="parent_cost_center" id="parent_cost_center">
                                            <option value="">Select Account</option>
                                            @foreach(CommonHelper::get_all_cost_center() as $row)
                                                <option value="{{ $row->code}}">{{  ucwords($row->name)}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <label>Cost Center Name :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input autofocus style="text-transform: capitalize" type="text" name="cost_center" id="cost_center" value="" class="form-control" />
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
<input type="hidden" name="id" value="{{$id}}">
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

    <script type="text/javascript">
        $(document).ready(function(){


            $('#CostCenterForm').submit(function(e)
            { e.preventDefault();





                var acc_id=$('#account_id').val();
                var dept_name=$('#dept_name').val();

                //   if (acc_id=='')
                //  {
                //   alert('Required All Fields And Rate Should Be Greaterc Than 0');
                //  return false;
                //    }

                var me=$(this);


                $.ajax({
                    url:me.attr('action'),
                    type:'post',
                    data:me.serialize(),

                    success: function(response)
                    {


                        if (response==0)
                        {
                            alert('This Cost Center Already Exists');
                            return false;
                        }
                        var result=response.split(',');


                        $('.CostCenter').append($('<option>', {
                            value: result[0],
                            text: result[1]
                        }));

                        SelectValCostCenter.push(result[0]);
                        SelecttxtCostCenter.push(result[1]);
                        ajaxformdeptCostCenter=1;

$('#'+result[2]).focus();
                        window.scrollBy(0,180);
                        $("[data-dismiss=modal]").trigger({ type: "click" });


                    }
                });
            });




        });

    </script>
    <script type="text/javascript">

        $('#parent_cost_center').select2();
    </script>
