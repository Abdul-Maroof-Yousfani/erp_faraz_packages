<?php

        use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        <!--
                       include('Purchase.'.$accType.'purchaseMenu')
                                <!-->
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Currency</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pdc/addCurrencyForm?m='.$m.'','id'=>'currency_type'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input  type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                                    <label>Currency Type :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select onchange="get_rate()"   name="dropdown_currency" id="dropdown_currency"  class="form-control requiredFields">

                                                <option>Select</option>

                                                        @foreach(CommonHelper::get_all_currency() as $key => $y)
                                                        <option   value="{{ $y->id.','.$y->rate}}">{{ $y->curreny}}</option>
                                                        @endforeach
                                                    </select>

                                                      <input  type="text" name="cureency_id" id="cureency_id" class="form-control" style="display: none"/>
                                                  <div id="add_currency" style="text-align: center;display: none">
                                                      <button  onclick="add_cuurency_name()" type="button" class="btn btn-success btn-xs text-center">Submit</button></div>
                                                    <button onclick="add_new(1)" style="float: right" type="button" class="btn btn-primary btn-xs">Add New Currency</button>
                                                    <button id="remove"  onclick="add_new(2)" style="float: right;display: none" type="button" class="btn btn-danger btn-xs">Remove</button>
                                                </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label>Currency Rate :</label>
                                                        <input step="0.01" class="form-control" type="number" name="rate" id="rate"/>
                                                        </div>


                                                <div>&nbsp;</div>   <div>&nbsp;</div>   <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

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
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#currency_type").submit(function(){




            var currency=$('#dropdown_currency').val();
              var rate=$('#rate').val();
                if (currency=='' || rate=='' || rate < 1  )
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


                      $('#curren').html(response);
                            $("[data-dismiss=modal]").trigger({ type: "click" });




                    }
                });


                return false;

            });
            return false;
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


        function add_cuurency_name()
        {
           var currency=$('#cureency_id').val();


            if (currency=='')
            {
                alert('Required Currency Name');
                return false;
            }
            $.ajax({
                url:'{{URL('pdc/addCurrency')}}',
                type:'Get',
                data:{currency:currency},

                success: function(response)
                {


                    if (response==0)
                    {
                        alert('This name Already Exists');
                    }
                    else
                    {
                        var result=response.split('/');
                        $('#dropdown_currency').append($('<option>', {
                            value: result[0],
                            text: result[1]
                        }));
                        $("#cureency_id").css("display", "none");
                        $("#remove").css("display", "none");
                        $("#add_currency").css("display", "none");


                    }



                }
            });
        }


        function get_rate()
        {
           var rate= $('#dropdown_currency').val();
            rate=rate.split(',');
            rate=rate[1];
            $('#rate').val(rate);
        }
    </script>
