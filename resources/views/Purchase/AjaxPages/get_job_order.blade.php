<?php
use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
?>
<div class="lineHeight">&nbsp;</div>

<div class="row" style="" id="DirectJobOrder">

    <?php echo Form::open(array('url' => 'pad/addJobOrder','id'=>'JobOrderForm', 'enctype' => 'multipart/form-data'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pageType" value="add">
    <input type="hidden" name="parentCode" value="000">
    <input type="hidden" name="m" value="<?php echo Input::get('m');?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
            <div class="">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Job Order No.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <?php $job_order_no=SalesHelper::get_unique_no_job_order(date('y'),date('m')) ?>
                                            <input type="text" class="form-control requiredField"  name="job_order_no" id="job_order_no" value="<?php echo $job_order_no?>" readonly />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">JOB Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input type="date" class="form-control requiredField"  placeholder="" name="date_ordered" id="date_ordered" value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Client Name: <span class="rflabelsteric"><strong>*</strong></span></label>

                                            @if($type==0)

                                                <select style="width: 100%" class="form-control requiredField select2" name="client_name" id="client_name" onchange="GetBranch('')">
                                                    <option value="">---Select---</option>
                                                    @foreach(SalesHelper::get_all_client() as $row)
                                                        <option value="{{$row->id}}">{{ucwords($row->client_name)}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" id="type" name="type" value="{{$type}}"/>
                                            @else

                                                <?php


                                                $client_data=CommonHelper::get_client_data_by_id($master->client_id);

                                                ?>
                                                <input type="hidden" id="client_name" name="client_name" value="{{$master->client_id}}"/>
                                                <input type="hidden" id="type" name="type" value="{{$type}}"/>
                                                <input class="form-control" readonly type="text" name="" id="" value="{{$client_data->client_name}}"/>
                                                <script !src="">GetBranch('<?php echo $master->client_id?>')</script>
                                            @endif
                                        </div>
                                        @if($type==0)
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"><a href="#" onclick="branchDetail('sales/addBranchAjax','<?= $m ?>')" class=""> Branch: </a>
                                                    <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <select name="BranchId" id="BranchId" class="form-control select2 requiredField"></select>
                                            </div>
                                        @else
                                            <?php $dataBranch = CommonHelper::get_single_row('branch','id',$master->branch_id); ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> Branch Name: </label>
                                                <input type="text" readonly name="" id="" class="form-control" value="{{ $dataBranch->branch_name }}">
                                                <input type="hidden" name="BranchId" id="BranchId" class="form-control" value="{{$master->branch_id}}">
                                            </div>
                                        @endif
                                        <input type="hidden" name="main_id" value="{{$id}}"/>
                                        <?php
                                        if ($type==1):
                                            $data=  CommonHelper::get_data_from_survey_tracking($id,$type);
                                            $data=explode(',',$data);
                                            $client_job=$data[0];
                                            $contact_person=$data[1];
                                            $branch_name=$data[2];
                                            $client_address=$client_data->address;
                                        else:
                                            $client_job='';
                                            $contact_person='';
                                            $branch_name='';
                                            $client_address='';
                                        endif;
                                        ?>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"><a href="#" onclick="ClientJobDetail('sales/addClientJobAjax','<?= $m ?>')" class=""> Client Job: </a>
                                                <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <select style="width: 100%" class="form-control requiredField  select2" name="client_job" id="client_job" >
                                                <option value="">---Select---</option>
                                                @foreach(CommonHelper::get_all_client_job() as $row)
                                                    <option value="{{$row->id}}">{{ucwords($row->client_job)}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <input type="hidden" name="CompanyId" id="CompanyId" value="<?php  echo Input::get('m');?>">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Client Address: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <textarea class="form-control requiredField"   name="client_address" id="client_address" style="resize: none;">{{$client_address}}</textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Job Location: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <textarea autofocus type="text" class="form-control requiredField" name="job_location" id="job_location"  style="resize: none;">{{$branch_name}}</textarea>
                                        </div>

                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                        {{--<label class="sf-label">Inovice No: <span class="rflabelsteric"><strong>*</strong></span></label>--}}
                                        {{--<input autofocus type="text" class="form-control requiredField" placeholder="Ref / Bill No" name="invoice_no" id="invoice_no" value="" />--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                        {{--<label class="sf-label">Completion Date.</label>--}}
                                        {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                        {{--<input type="date" class="form-control requiredField"  name="completion_date" id="completion_date"  />--}}
                                        {{--</div>--}}
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Contact Number: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input autofocus type="text" class="form-control requiredField" name="contact_no" id="contact_no" value="" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Approval Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input type="date" class="form-control requiredField"  placeholder="" name="approval_date" id="approval_date" value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Contact Person: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input autofocus type="text" class="form-control requiredField" name="contact_person" id="contact_person" value="{{$contact_person}}" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Ordered By: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <input type="text" class="form-control requiredField" placeholder="Ref / Bill No" name="ordered_by" id="ordered_by" value="" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Address: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <textarea autofocus type="text" class="form-control requiredField" name="address" id="address" value="" style="resize: none"></textarea>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Job Description: <span class="rflabelsteric"><strong>*</strong></span></label>
                                            <textarea class="form-control requiredField"  name="job_description" id="job_description" style="resize: none"></textarea>
                                        </div>

                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Date due.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" name="due_date" id="due_date" value="<?php echo date('Y-m-d') ?>" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                                            <label class="sf-label">Region <span class="rflabelsteric"><strong>*</strong></span></label>

                                            @if ($type==0):
                                            <select class="form-control select2" id="region_id" name="region_id">
                                                <option>Select</option>
                                                @foreach(CommonHelper::get_all_regions() as $row)
                                                    <option value="{{$row->id}}">{{$row->region_name}}</option>
                                                @endforeach

                                            </select>
                                            @else
                                                <?php  $region_data=CommonHelper::get_rgion_name_by_id($master->region_id);
                                                $region_name=$region_data->region_name;
                                                ?>
                                                <input type="hidden" name="region_id" value="{{$master->region_id}}"/>
                                                <input readonly type="text" name="region" id="region" value="{{$region_name}}" class="form-control">
                                            @endif

                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Installed by</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" name="installed" id="installed" value="" />
                                        </div>

                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                        {{--<label class="sf-label">Prepared By.</label>--}}
                                        {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                        {{--<input type="text" class="form-control requiredField" name="prepared" id="prepared" value="" />--}}
                                        {{--</div>--}}

                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                        {{--<label class="sf-label">Checked By.</label>--}}
                                        {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                        {{--<input type="text" class="form-control requiredField" name="checked" id="checked" value="" />--}}
                                        {{--</div>--}}

                                        {{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                        {{--<label class="sf-label">Approved By.</label>--}}
                                        {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                        {{--<input type="text" class="form-control requiredField" name="approved" id="approved" value="" />--}}
                                        {{--</div>--}}

                                    </div>
                                </div>


                                <input type="hidden" name="m" id="m" value="{{Input::get('m')}}">




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
    </div>
    <div class="lineHeight">&nbsp;</div>
    <h4 style="text-align: center">Job Detail</h4>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div  class="table-responsive">
                <div  class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                    @if($type==1 || $type==2)
                        <table  id="" class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 50px" class="text-center">Sr No <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 100px" class="text-center">Product <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 100px" class="text-center">Type <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 100px" class="text-center">Uom <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 200px;" class="text-center">Width. <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 200px;" class="text-center">Height. <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 200px;" class="text-center">Depth. <span class="rflabelsteric"><strong>*</strong></span></th>
                                <th style="width: 150px;" class="text-center" style="">Quantity <span class="rflabelsteric"><strong>*</strong></span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $counter=1; ?>

                            @foreach($detail as $row)
                                <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="{{$counter}}" />
                                <input type="hidden" name="q_data_id_1_{{$counter}}" value="{{$row->id}}"/>
                                <input type="hidden" name="survery_data_id{{$counter}}" value="{{$row->survey_data_id}}"/>
                                <tr>

                                    <td>{{$counter}}</td>
                                    <td>
                                        {{--<select  style="width: 200px;font-size: 10px;" class="form-control requiredField select2"  id="product_1_1" name="product_1_1" >--}}
                                        {{--<option value="">---Select---</option>--}}
                                        {{--@foreach(CommonHelper::get_all_products() as $row)--}}
                                        {{--<option value="{{$row->product_id}}">{{ucwords($row->p_name)}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}

                                        <input type="hidden" name="product_1_{{$counter}}" id="product_1_{{$counter}}" value="{{$row->product_id}}" />
                                        <?php
                                        $prduct_data=   CommonHelper::get_product_name_by_id($row->product_id);
                                        $product_name=$prduct_data->p_name
                                        ?>

                                        <input class="form-control" type="text" readonly value="{{$product_name}}"/>
                                    </td>
                                    <td>
                                        <select style="width: 100%;"  class="form-control requiredField select2"  id="type_1_<?php echo $counter?>" name="type_1_<?php echo $counter?>" onchange="">
                                            <option value="">---Select---</option>
                                            <?php foreach(CommonHelper::get_all_type() as $rowFil): ?>
                                            <option value="<?php echo $rowFil->type_id ?>"> <?php echo ucwords($rowFil->name) ?> </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td>
                                        {{--<select  style="width: 200px;font-size: 10px;" class="form-control requiredField select2"  id="product_1_1" name="product_1_1" >--}}
                                        {{--<option value="">---Select---</option>--}}
                                        {{--@foreach(CommonHelper::get_all_products() as $row)--}}
                                        {{--<option value="{{$row->product_id}}">{{ucwords($row->p_name)}}</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}

                                        <input type="hidden" name="uom_1_{{$counter}}" id="uom_1_{{$counter}}" value="{{$row->uom_id}}" />
                                        <?php
                                        $UnitofMeasure=   CommonHelper::get_single_row_uom('uom','id',$row->uom_id);
                                        $UomName=$UnitofMeasure->uom_name;
                                        ?>

                                        <input class="form-control" type="text" readonly value="<?php echo $UomName?>"/>
                                    </td>

                                    <td>
                                        <input type="text" name="width_1_{{$counter}}" value="{{$row->width}}" id="width_1_{{$counter}}" class="form-control requiredField" readonly />
                                    </td>

                                    <td>
                                        <input type="text" name="height_1_{{$counter}}" value="{{$row->height}}" id="height_1_{{$counter}}" class="form-control requiredField" />
                                    </td>

                                    <?php $depth=0; ?>


                                    @if($type==1):
                                    @if($row->survey_data_id!=0)

                                        <?php
                                        $depth=   CommonHelper::get_depth_from_survey($row->survey_data_id);
                                        ?>
                                    @endif
                                    @endif
                                    <td>
                                        <input type="text" name="depth_1_{{$counter}}" value="{{$depth}}" id="depth_1_{{$counter}}" class="form-control requiredField" />
                                    </td>

                                    <td>
                                        <input type="number" step="0.01" value="{{$row->qty}}" name="qty_1_{{$counter}}" id="qty_1_{{$counter}}" class="form-control qty requiredField" />
                                    </td>

                                </tr>


                                <tr>
                                    <td colspan="7"><label class="sf-label">Description</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="description_1_{{$counter}}" id="description_1_{{$counter}}" rows="3" cols="50" style="resize:none;" class="form-control requiredField" style="resize: none"></textarea>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                            @endforeach


                            </tbody>
                        </table>
                    @else
                        <?php $counter=1; ?>

                    @endif


                    <div class="lineHeight">&nbsp;</div>

                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                        <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">

                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>

            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="panel panel-default" style="border: solid 1px #ccc;">
                <div class="panel-heading">Attachment Art Work</div>
                <div class="panel-body">
                    <input type="hidden" name="ImageCounter[]" value="1" />
                    <div class="addMoreImagesRows_1" id="addMoreImagesRows_1">
                        <label for="imageInput">SELECT IMAGE</label>
                        <input data-preview="#preview" name="input_img_1" type="file" id="imageInput" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <input type="button" class="btn btn-sm btn-primary" onclick="addMoreImagesRows('1')" value="Add More Image" />
            <input type="button" onclick="removeImageRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove Image">

        </div>
    </div>

    <div class="demandsSection"></div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
            {{--<b> Estimate Form Skip </b><input type="checkbox" name="estimate" value="1">--}}
                    <!--
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                        <!-->
        </div>
    </div>
    <?php echo Form::close();?>
</div>




<script type="text/javascript">

    $( document ).ready(function() {

        $('.select2').select2();

        $('#demandDataSection_1').val('{{$counter-1}}');
        $(".btn-success").click(function(e){
            jqueryValidationCustom();
            if(validate == 0){
                $('#BtnSubmit').css('display','none');
                //return false;
            }else{
                return false;
            }
        });
    });

    y=1;
    function addMoreImagesRows(){
        y++;
        $('.addMoreImagesRows_1').append('<div id="removeimage'+y+'">'
                +'<input type="hidden" name="ImageCounter[]" value="'+y+'" />'
                +'<label for="imageInput">SELECT IMAGE</label>'
                +'<input data-preview="#preview" name="input_img_'+y+'" type="file" id="imageInput" class="form-control">'
                +'</div>');
    }

    function removeImageRows(){
        if (y > 1)
        {
            $('#removeimage'+y).remove();
        }
        y--;
    }

    $("#JobOrderFormm").submit(function(event){
        event.preventDefault(); //prevent default action
        var post_url = $(this).attr("action"); //get form action url
        var request_method = $(this).attr("method"); //get form GET/POST method
        var form_data = $(this).serialize(); //Encode form elements for submission

        $.ajax({
            url : post_url,
            type: request_method,
            data : form_data,
            beforeSend: function() {
                $('#DirectJobOrderArea').css('display','none');
                $('#job_order_next_step').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');


            },
        }).done(function(response){ //


            $("#job_order_next_step").html(response);
        });
    });



    function ChangeForm(Value)
    {
        if(Value == 1)
        {
            $('#DirectJobOrder').css('display','block');
        }
        else{
            $('#DirectJobOrder').css('display','none');
        }
//                $('#element').click(function() {
//                    if($('#radio_button').is(':checked')) { alert("it's checked"); }
//                });
    }






    var x = '{{$counter-1}}';

    function addMoreDemandsDetailRows(id){

        x++;

        //alert(id+' ---- '+x);

        $('.addMoreDemandsDetailRows_' + id + '').append('<table  id="removeDemandsRows_1_'+x+'" class="table table-bordered">'+
                "<thead>"+
                '<tr>'+
                '<th style="width: 50px" class="text-center">Sr No <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 100px" class="text-center">Product <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 100px" class="text-center">Type <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 100px" class="text-center">Uom <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 200px;" class="text-center">Width. <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 200px;" class="text-center">Height. <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 200px;" class="text-center">Depth. <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '<th style="width: 150px;" class="text-center" style="">Quantity <span class="rflabelsteric"><strong>*</strong></span></th>'+
                '</tr>'+
                '</thead>'+
                '<tbody class="" id="">'+
                '<tr id="">'+
                '<input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="'+x+'" />'+
                '<td>'+
                +x+
                '       </td>'+
                '<td>'+
                '<select style="width: 100%;"  class="form-control requiredField select2"  id="product_1_'+x+'" name="product_1_'+x+'" onchange="">'+
                '<option value="">---Select---</option>'+
                '<?php foreach(CommonHelper::get_all_products() as $row): ?>'+
                '<option value="<?= $row->product_id ?>"> <?= ucwords($row->p_name) ?> </option>'+
                '<?php endforeach; ?>'+
                '</select>'+
                '</td>'+

                '<td>'+
                '<select style="width: 100%;"  class="form-control requiredField select2"  id="type_1_'+x+'" name="type_1_'+x+'" onchange="">'+
                '<option value="">---Select---</option>'+
                '<?php foreach(CommonHelper::get_all_type() as $row): ?>'+
                '<option value="<?= $row->type_id ?>"> <?= ucwords($row->name) ?> </option>'+
                '<?php endforeach; ?>'+
                '</select>'+
                '</td>'+

                "<td>"+
                '<select style="width: 100%;"  class="form-control requiredField select2"  id="uom_1_'+x+'" name="uom_1_'+x+'" onchange="">'+
                '<option value="">---Select---</option>'+
                '<?php foreach($uom as $fil): ?>'+
                "<option value='<?= $fil->id ?>'> <?= ucwords($fil->uom_name) ?> </option>"+
                '<?php endforeach; ?>'+
                '</select>'+
                '</td>'+
                '<td>'+
                '<input type="text" name="width_1_'+x+'" id="width_1_'+x+'" class="form-control requiredField" />'+
                '</td>'+
                '<td>'+
                '<input type="text" name="height_1_'+x+'" id="height_1_'+x+'" class="form-control requiredField" />'+
                '</td>'+
                '<td>'+
                '<input type="text" name="depth_1_'+x+'" id="depth_1_'+x+'" class="form-control requiredField" />'+
                '</td>'+
                '<td>'+
                '<input type="number" step="0.01" name="qty_1_'+x+'" id="qty_1_'+x+'" class="form-control qty requiredField" />'+
                '</td>'+
                '</tr>'+

                '<tr>'+
                '<td colspan="7"><label class="sf-label">Description</label>'+
                '<span class="rflabelsteric"><strong>*</strong></span>'+
                '<textarea name="description_1_'+x+'" id="description_1_'+x+'" rows="3" cols="50" style="resize:none;" class="form-control requiredField"></textarea>'+
                '</td>'+
                '</tr>'+
                '</tbody>'+
                '</table>'+
                '<div class="lineHeight">&nbsp;</div>');

        $('#product_1_'+x).select2();
        $('#type_1_'+x).select2();
        $('#uom_1_'+x).select2();
    }

    function removeDemandsRows(){
        var id=1;
        if (x > 1)
        {
            //  var elem = document.getElementById('removeDemandsRows_'+id+'_'+x+'');
            //   elem.parentNode.removeChild(elem);

            $('#removeDemandsRows_'+id+'_'+x+'').remove();

            $('.removeDemandsRows_dept_'+id+'_'+x+'').remove();

            x--;
            net_amount_func();
        }
    }

    function ClientJobDetail(url,m){
        $.ajax({
            url: '<?php echo url('/')?>/'+url+'',
            type: "GET",
            data: {m:m},
            success:function(data) {
                //alert(data);
                jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                jQuery('#showDetailModelOneParamerter .modal-body').html(data);
            }
        });
    }

    $('#client_name').on('change', function() {
        id = this.value;
        var m = $('#m').val();
        if(id!=''){
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/ClientInfo',
                type: "GET",
                data: {id: id, m: m},
                success: function (data) {
                    //alert("Successfully requested address");
                    $("#client_address").val(data);

                }
            });
        }
    });

    function branchDetail(url,m){
        $.ajax({
            url: '<?php echo url('/')?>/'+url+'',
            type: "GET",
            data: {m:m},
            success:function(data) {
                //alert(data);
                jQuery('#showDetailModelOneParamerter').modal('show', {backdrop: 'false'});
                //jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
                //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                jQuery('#showDetailModelOneParamerter .modal-body').html(data);
            }
        });
    }


    function GetBranch()
    {
        var ClientName = $('#client_name').val();
        if(ClientName !="")
        {
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/GetBranch',
                type: "GET",
                data: {ClientName: ClientName,Selected:''},
                success: function (data) {
                    //alert("Successfully requested address");
                    $("#BranchId").html(data);

                }
            });
        }


    }

    function AddClientRequest(){
        var ClientJob = $("#ClientJob").val();
        var m = '<?php echo $_GET['m'] ?>';
        url='sad/addClientJobGET';
        $.ajax({
            url: '<?php echo url('/')?>/'+url+'',
            type: "GET",
            data: {ClientJob:ClientJob, m: m},
            success:function(data) {
                setTimeout(function(){
                    $('#showDetailModelOneParamerter').modal("hide");
                }, 500);
                var res = data.split(",");
                // alert(res[0]+' '+res[1]);
                $('#client_job').append('<option value="'+res[0]+'" selected>'+res[1]+'</option>');
            }
        });
    }



    function AddBranchRequest()
    {

        var client_id = $("#client_id").val();
        var branch_name = $("#branch_name").val();
        var ntn = $("#ntn").val();
        var strn = $("#strn").val();
        //var address = $("textarea#address").html();
        var branch_address = $("#branch_address").val();

        var m = '<?= $_GET['m']?>';

        url='sad/insertBranchAjax';
        if(client_id == ""){$('#ErrorOne').html('<p class="text-danger">Field Required.</p>'); return false;}
        else{$('#ErrorOne').html('');}
        if(branch_name == ""){$('#ErrorTwo').html('<p class="text-danger">Field Required.</p>'); return false;}
        else{$('#ErrorTwo').html('');}
        if(ntn == ""){$('#ErrorThree').html('<p class="text-danger">Field Required.</p>'); return false;}
        else{$('#ErrorThree').html('');}
        if(strn == ""){$('#ErrorFour').html('<p class="text-danger">Field Required.</p>'); return false;}
        else{$('#ErrorFour').html('');}
        if(branch_address == ""){$('#ErrorFive').html('<p class="text-danger">Field Required.</p>'); return false;}
        else{$('#ErrorFive').html(''); }
        $('#BtnBranchSubmit').prop('disbaled',true);
        $.ajax({
            url: '<?php echo url('/')?>/' + url + '',
            type: "GET",
            data: {client_id:client_id,branch_name:branch_name,ntn:ntn,strn:strn,address:branch_address},
            success: function (data) {
                setTimeout(function () {
                    $('#showDetailModelOneParamerter').modal("hide");
                }, 500);
            }
        });

    }
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>