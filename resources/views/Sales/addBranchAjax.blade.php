<?php
$m = $_GET['m'];

?>

<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span class="subHeadingLabelClass">Add New Branch</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Client Name :</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select name="client_id" id="client_id" class="form-control select2 requiredField" required>
                                                        <option value="">Select Client</option>
                                                        <?php foreach($Client as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <span id="ErrorOne"></span>

                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Branch Name:</label>
                                                    <input type="text" name="branch_name" id="branch_name" value="" class="form-control requiredField" placeholder="Branch Name" required/>
                                                    <span id="ErrorTwo"></span>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>NTN:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="ntn" id="ntn" class="form-control requiredField" placeholder="ENTER NTN" required />
                                                    <span id="ErrorThree"></span>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>STRN:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="strn" id="strn" class="form-control requiredField" placeholder="Enter STRN" required />
                                                    <span id="ErrorFour"></span>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Address:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input required name="branch_address" id="branch_address" class="form-control requiredField" style="height: 80px;" />
                                                    <span id="ErrorFive"></span>
                                                </div>
                                            </div>
                                                <input type="hidden" name="ajax" id="ajax" value="ajax">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <button type="button" onclick="AddBranchRequest()" class="btn btn-sm btn-success" id="BtnBranchSubmit">Submit</button>
                                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
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
<script !src="">


</script>