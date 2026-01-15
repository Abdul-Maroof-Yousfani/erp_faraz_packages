<?php

        $max_tracking_no= DB::Connection('mysql2')->table('survey')->select('tracking_no')->max('tracking_no');
        $max_tracking_no=$max_tracking_no+1;
        $max_tracking_no = sprintf("%'03d", $max_tracking_no);
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }
$m = Input::get('m');


use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')





    <div class="well">
        <div class="">
            <div class="">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <span class="subHeadingLabelClass">Survey Form </span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'pad/addSurveyDetail?m='.$m.'','id'=>'cashPaymentVoucherForm', 'enctype' => 'multipart/form-data'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                    <input type="hidden" name="m" value="<?php echo $_GET['m']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Tracking No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <input type="text" readonly class="form-control requiredField"  name="tracking_no" id="tracking_no" value="{{$max_tracking_no}}" placeholder="" />

                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Client. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select class="form-control requiredField select2" id="client" name="client" onchange="GetBranch('')">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_clients() as $row)
                                                                    <option value="{{$row->id}}">{{$row->client_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label for="">Branch</label>
                                                            <select name="BranchId" id="BranchId" class="form-control select2 requiredField"></select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Branch Name. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <input type="text" class="form-control requiredField" placeholder="" name="branch" id="branch" value="" />
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Name Of Contact Person.</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" class="form-control requiredField"  name="contactPerson" id="contactPerson" placeholder="Name Of Contact Person" />
                                                        </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Contact Number. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input type="text" class="form-control requiredField" placeholder="Contact Number" name="contactNumber" id="contactNumber" value="" />
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Survey Date</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="date" class="form-control requiredField"  name="surveyDate" id="surveyDate" value="<?php echo date('Y-m-d')?>" />
                                                    </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Survey By  <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select class="form-control select2" id="surveryby" name="surveryby">
                                                                <option>Select</option>
                                                                    @foreach(CommonHelper::get_all_surveryBy() as $row)
                                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                                        @endforeach

                                                            </select>
                                                        </div>

                                                </div>
                                            </div>
                                            </div>

                                            <div class="row">
                                                <div class="">

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Surveyor Name  <span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input type="text" class="form-control requiredField"  name="surveyor_name" id="surveyor_name" value="" />
                                                    </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Region <span class="rflabelsteric"><strong>*</strong></span></label>
                                                            <select class="form-control select2" id="region_id" name="region_id">
                                                                <option>Select</option>
                                                                @foreach(CommonHelper::get_all_regions() as $row)
                                                                    <option value="{{$row->id}}">{{$row->region_name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">City <span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <select class="form-control select2" id="city_id" name="city_id">
                                                            <option>Select</option>
                                                            @foreach(CommonHelper::get_all_cities() as $row)
                                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach

                                                        </select>
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
                                                                            <th class="text-center">Product<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" >Type<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" >Qty<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Height<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Width<span class="rflabelsteric"></span></th>
                                                                            <th class="text-center">Depth <span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center" style="width:100px;">Condition<span class="rflabelsteric"><strong>*</strong></span></th>
                                                                            <th class="text-center">Remarks <span class="rflabelsteric"><strong>*</strong></span></th>

                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="TrAppend">
                                                                        <tr id="Row_1">
                                                                            <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                                                            <td>

                                                                                <select name="product_1" id="product_1" class="form-control requiredField select2" style="width: 200px;" required>
                                                                                    <option value="">Select Product</option>
                                                                                    <?php
                                                                                        foreach($product as $FilPro):
                                                                                    ?>
                                                                                    <option value="<?php echo $FilPro['product_id']?>"><?php echo $FilPro['p_name']?></option>
                                                                                    <?php endforeach;?>

                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <select name="type_1" id="type_1" class="form-control requiredField select2" required>
                                                                                    <option value="">Select Type</option>
                                                                                    <?php
                                                                                    foreach($type as $FilType):
                                                                                    ?>
                                                                                    <option value="<?php echo $FilType['type_id']?>"><?php echo $FilType['name']?></option>
                                                                                    <?php endforeach;?>
                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <input type="text"  name="qty_1"
                                                                                       id="qty_1" class="form-control text-center requiredField" placeholder="Qty">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"  name="height_1"
                                                                                       id="height_1" class="form-control text-center requiredField" placeholder="Height">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"  name="width_1"
                                                                                       id="width_1" class="form-control text-center requiredField" placeholder="Width">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"  name="depth_1"
                                                                                       id="depth_1" class="form-control text-center requiredField" placeholder="Depth">
                                                                            </td>
                                                                            <td>
                                                                                <select name="condition_1[]" id="condition_1" class="form-control select2 requiredField" multiple style="width: 300px" required>
                                                                                    <?php
                                                                                    foreach($conditions as $FilCon):
                                                                                    ?>
                                                                                        <option value="<?php echo $FilCon['condition_id']?>"><?php echo $FilCon['name']?></option>
                                                                                    <?php endforeach;?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <textarea name="remarks_1" id="remarks_1" cols="30"
                                                                                          rows="2" style="resize: none;width: 200px" class="form-control"></textarea>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreRows()" value="Add More Demand's Rows" />
                                                                <button type="button" class="btn btn-sm btn-danger" id="BtnRemove" onclick="RemoveRows()">Remove Rows</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="panel panel-default" style="border: solid 1px #ccc;">
                                                        <div class="panel-heading">Survey Images</div>
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


                                        </div>
                                    </div>
                                </div>
                                <div class="demandsSection"></div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <button type="submit" class="btn btn-success" id="BtnSubmit" onclick="SubmitDisabled()">Submit</button>

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
//        $(document).ready(function() {
//            $("#txtEditor").Editor();
//        });

    function GetBranch()
    {
        var ClientName = $('#client').val();
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

    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

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

        var CounterRow = 1;


        function AddMoreRows()
        {
            CounterRow++;
            $('#TrAppend').append('<tr id="Row_'+CounterRow+'"><td><input type="hidden" name="demandDataSection_1[]" id="demandDataSection_1" value="+CounterRow+" /><select name="product_'+CounterRow+'" id="product_'+CounterRow+'" onclass="form-control requiredField" style="width: 200px;"><option value="">Select Product</option><?php
            foreach($product as $FilPro):?><option value="<?php echo $FilPro['product_id']?>"><?php echo $FilPro['p_name']?></option><?php endforeach;?></select></td><td><select name="type_'+CounterRow+'" id="type_'+CounterRow+'" onclass="form-control requiredField"><option value="">Select Type</option><?php foreach($type as $FilType):?>
        <option value="<?php echo $FilType['type_id']?>"><?php echo $FilType['name']?></option><?php endforeach;?></select></td>'+
                            '<td><input type="text"  name="qty_'+CounterRow+'" id="qty_'+CounterRow+'" class="form-control text-center requiredField" placeholder="Qty"></td>'+
                    '<td><input type="text"  name="height_'+CounterRow+'" id="height_'+CounterRow+'" class="form-control text-center requiredField" placeholder="Height"></select></td>' +
                    '<td><input type="text"  name="width_'+CounterRow+'" id="width_'+CounterRow+'" class="form-control text-center requiredField" placeholder="Width"></td><td><input type="text"  name="depth_'+CounterRow+'" id="depth_'+CounterRow+'" class="form-control text-center requiredField" placeholder="Depth"></td><td><select name="condition_'+CounterRow+'[]" id="condition_'+CounterRow+'" class="form-control requiredField" multiple style="width: 300px"><?php foreach($conditions as $FilCon):?><option value="<?php echo $FilCon['condition_id']?>"><?php echo $FilCon['name']?></option><?php endforeach;?></select></td><td><textarea name="remarks_'+CounterRow+'" id="remarks_'+CounterRow+'" cols="30" rows="2" style="resize: none;width: 200px" class="form-control"></textarea></td></tr>');
            $('#product_'+CounterRow).select2();
            $('#condition_'+CounterRow).select2();
            $('#type_'+CounterRow).select2();
        }

        function RemoveRows()
        {
            if(CounterRow>1)
            {
                $('#Row_'+CounterRow).remove();
                CounterRow--;
            }

        }

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

        function SubmitDisabled()
        {

        }

    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection