<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');


use App\Helpers\PurchaseHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="stage2">  </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <span class="subHeadingLabelClass">JOB TRACKING SHEETS </span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader">
                                </div>
                            </div>

                            <div class="row" id="showhide">
                                <?php echo Form::open(array('url' => 'pad/addJobTrackingDetails?m='.$m.'','id'=>'formid'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Customer. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select class="form-control select2" name="customer" id="customer">
                                                                <option value="">Select Customer</option>
                                                                <?php foreach($customer as $Filer):?>
                                                                <option value="<?php echo $Filer['id']?>"><?php echo $Filer['name']?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Customer Job.</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" class="form-control requiredField"  name="customer_job" id="customer_job" placeholder="Customer Job" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Region.</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select class="form-control select2" name="region" id="region">
                                                                <option value="">Select Region</option>
                                                                <?php foreach($region as $Filter1):?>
                                                                <option value="<?php echo $Filter1['id']?>"><?php echo $Filter1['region_name']?></option>
                                                                <?php endforeach;?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Job Tracking #</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select class="form-control select2" name="job_tracking_no" id="job_tracking_no">
                                                                <option value="">Select Tracking No</option>
                                                                <?php foreach($survey    as $Filter2):?>
                                                                <option value="<?php echo $Filter2['tracking_no']?>"><?php echo $Filter2['tracking_no']?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Job Tracking Date</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="date" name="job_tracking_date" id="job_tracking_date" class="form-control" value="<?php //echo date('Y-m-d')?>">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">City.</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select class="form-control select2" name="city" id="city">
                                                                <option value="">Select City</option>
                                                                <?php foreach($cities as $Filter3):?>
                                                                <option value="<?php echo $Filter3['id']?>"><?php echo $Filter3['name']?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label class="sf-label">Job Description</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <textarea name="job_desc" id="job_desc" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="well">
                                                <div class="">
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive">
                                                                    <table id="buildyourform" class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center" >Task<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Task Assigned<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Task Target Date<span class="rflabelsteric"></span></th>
                                                                            <th class="text-center">Task Completed Date<span class="rflabelsteric"></span></th>
                                                                            <th class="text-center">Resource Assigned <br>Internal/External  <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Remarks <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="TrAppend">
                                                                        <?php
                                                                        $one="";
                                                                        for($i=1; $i<=17; $i++):

                                                                        if($i==1){ $one = "Site Survey";  }
                                                                        elseif($i==2){ $one = "Artwork Shared";  }
                                                                        elseif($i==3){ $one = "Approve Artwork";  }
                                                                        elseif($i==4){ $one = "Approve Quote";  }
                                                                        elseif($i==5){ $one = "Generate Job Order";  }
                                                                        elseif($i==6){ $one = "Estimate Quantities";  }
                                                                        elseif($i==7){ $one = "Raised Requisition";  }
                                                                        elseif($i==8){ $one = "Requisition Approved";  }
                                                                        elseif($i==9){ $one = "Procure Material";  }
                                                                        elseif($i==10){ $one = "Cut Vinyl";  }
                                                                        elseif($i==11){ $one = "Pasting";  }
                                                                        elseif($i==12){ $one = "Print Skin";  }
                                                                        elseif($i==13){ $one = "Fabrication";  }
                                                                        elseif($i==14){ $one = "Assemble Sign";  }
                                                                        elseif($i==15){ $one = "Installed Sign";  }
                                                                        elseif($i==16){ $one = "Invoice";  }
                                                                        elseif($i==17){ $one = "Recovery";  }
                                                                        else { $one = "";  }
                                                                        ?>
                                                                        <tr>
                                                                            <td>  <input type="text" name="task_<?= $i; ?>" id="task_<?= $i; ?>" class="form-control"  value="<?= $one ?>" readonly>  </td>
                                                                            <td>  <input type="date" name="taskAssigned_<?= $i; ?>" id="taskAssigned_<?= $i; ?>" value="<?php echo date('Y-m-d')?>" class="form-control"> </td>
                                                                            <td>  <input type="date" name="taskTarget_<?= $i; ?>" id="taskTarget_<?= $i; ?>" value="<?php echo date('Y-m-d')?>" class="form-control"> </td>
                                                                            <td>  <input type="date" name="taskCompeleted_<?= $i; ?>" id="taskCompeleted_<?= $i; ?>" value="<?php echo date('Y-m-d')?>" class="form-control"> </td>
                                                                            <td>  <input type="text"  name="resourcAssign_<?= $i; ?>"  id="resourcAssign_<?= $i; ?>" class="form-control text-center requiredField" placeholder="Resource Assigned"> </td>
                                                                            <td>  <textarea name="remarks_<?= $i; ?>" id="remarks_<?= $i; ?>" cols="30" rows="2" style="resize: none;" class="form-control">  </textarea> </td>
                                                                        </tr>

                                                                        <?php
                                                                        endfor;
                                                                        ?>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="demandsSection"></div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <!-- <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" /> -->
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        $("#formid").submit(function(event){
            event.preventDefault();
            if (confirm("Want to Add Data...?")) {
                $("#showhide").hide();
                $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                var post_url = $(this).attr("action"); //get form action url
                var form_data = $(this).serialize(); //Encode form elements for submission
                $.ajax({
                    url: post_url,
                    type: "POST",
                    data: form_data
                }).done(function (response) {
                    $("#loader").html('');
                    $("#stage2").html("<h3 class='text-center' style='color: red'>DATA ADDED SUCCESSFULLY</h3>");
                    $("#showhide").show();
                });
            } else {
                alert("You didn't submit the form");
            }
        });


    </script>
@endsection