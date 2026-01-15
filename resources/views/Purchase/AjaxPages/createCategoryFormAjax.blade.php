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

                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Add New Category</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php echo Form::open(array('url' => 'pdc/addCategoryDetailAjax?m='.$m.'','id'=>'addCategoryForm'));?>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">

                                            <!--
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Account Head</label>
                                                    <select name="account_head" id="account_head" class="form-control select2">
                                                        <option value="">Select Account</option>
                                                        @ foreach($accounts as $key => $y)
                                                            <option value="{ { $y-> code}}">{ { $y->code .' ---- '. $y->name}}</option>
                                                        @ endforeach
                                                    </select>
                                                </div>
                                                <!-->
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Category Name :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="category_name" id="category_name" value="" class="form-control" />
                                            </div>
                                            <div>&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
</div>






<script type="text/javascript">




    $(document).ready(function(){


        $('#addCategoryForm').submit(function(e)
        { e.preventDefault();




            var category=$('#category_name').val();

            if (category=='' )
            {
                alert('Required All Fields');
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

                
                    for(i=1; i<=x; i++)
                    {


                        $('#category_id_1_' + i + '').append($('<option>', {
                            value: result[0],
                            text: result[1]
                        }));
                    }




                    window.scrollBy(0,180);
                    $("[data-dismiss=modal]").trigger({ type: "click" });


                }
            });
        });




    });


</script>
