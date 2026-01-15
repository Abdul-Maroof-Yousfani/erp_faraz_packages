<?php
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
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Add New Purchase Type</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pdc/addPurchaseType?m='.$m.'','id'=>'purchase_typee'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                <input  type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Purchase Type :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input autofocus  type="text" name="purchase_type" id="purchase_type" value="" class="form-control" />
                                                </div>
                                                <div>&nbsp;</div>
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
            $("#purchase_typee").submit(function(){




            var type=$('#purchase_type').val();

                if (type=='')
                {
                    alert('Required Purchase Type');
                    return false;
                }
                var me=$(this);
                $.ajax({
                    url:me.attr('action'),
                    type:'post',
                    data:me.serialize(),

                    success: function(response)
                    {

                        if (response==0)
                        {
                            alert('This name Already Exists');
                        }
                        else
                        {
                            var result=response.split(',');
                            $('#p_type').append($('<option>', {
                                value: result[0],
                                text: result[1]
                            }));
                            $("[data-dismiss=modal]").trigger({ type: "click" });
                        }

                        return false;

                    }
                });


                return false;

            });
            return false;
        });

    </script>
