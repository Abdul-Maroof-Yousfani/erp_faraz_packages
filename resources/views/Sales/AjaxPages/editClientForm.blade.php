<?php $m = $_GET['m']; ?>
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <span class="subHeadingLabelClass">Edit Client</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">

                                                <?php echo Form::open(array('url' => 'sad/updateClientForm?m='.$m.'','id'=>'addClientDetail'));?>
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                                    <input type="hidden" name="CompanyId" id="CompanyId" value="<?php echo $m?>">
                                                    <input type="hidden" name="AccId" id="AccId" value="<?php echo $client->acc_id?>">

                                                    <input type="hidden" name="ClientId" id="ClientId" value="<?php echo $client->id?>">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Client Name :</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="client_name" id="client_name" value="<?php echo $client->client_name?>" class="form-control requiredField" placeholder="Client Name" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>NTN:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="ntn" id="ntn" class="form-control requiredField" placeholder="ENTER NTN" value="<?php echo $client->ntn?>" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>STRN:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="strn" id="strn" class="form-control requiredField" placeholder="Enter STRN" value="<?php echo $client->strn?>" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label>Address:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <textarea name="address" id="address" class="form-control requiredField"><?php echo $client->address?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
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
        $(document).ready(function() {

            $(".btn-success").click(function(e){
                var subItem = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                subItem.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of subItem) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //return false;
                    }else{
                        return false;
                    }
                }
            });
        });
    </script>