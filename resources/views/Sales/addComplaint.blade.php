<?php

$m = $_GET['m'];


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
//if($accType == 'client'){
//   $m = $_GET['m'];
//}else{
//   $m = Auth::user()->company_id;
//}



use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$InvNo=SalesHelper::get_unique_no_inv(date('y'),date('m'));
?>
@extends('layouts.default')

@section('content')
    @include('select2')

    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class="subHeadingLabelClass">Complaint / Maintenance</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'sad/addComplaintDetail?m='.$m.'','id'=>'addInvoiceDetail', 'enctype' => 'multipart/form-data'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <input type="hidden" name="m" value="<?php echo $_GET['m']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body" style="border: solid 1px;">

                                    <div class="row" >
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Client Name</label>
                                                    <select name="ClientId" id="ClientId" class="form-control select2">
                                                        <option value="">Select Client</option>
                                                        <?php foreach($client as $Fil):?>
                                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Region <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <select class="form-control select2" id="RegionId" name="RegionId">
                                                        <option>Select</option>
                                                        @foreach(CommonHelper::get_all_regions() as $row)
                                                            <option value="{{$row->id}}">{{$row->region_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label"> Branch: </label>
                                                    <select name="BranchId" id="BranchId" class="form-control select2 requiredField"></select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Branch Code</label>
                                                    <input type="text" name="BranchCode" id="BranchCode" class="form-control" placeholder="Branch Code">
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Date</label>
                                                    <input type="date" class="form-control" name="ComplaintDate" id="ComplaintDate" >
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Contact Person Name</label>
                                                    <input type="text" class="form-control" name="ContactPersonName" id="ContactPersonName" placeholder="Contact Person Name">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Designation</label>
                                                    <input type="text" name="Designation" id="Designation" class="form-control" placeholder="Designation">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Phone#</label>
                                                    <input type="text" name="Phone" id="Phone" class="form-control" placeholder="Phone #">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Designation</label>
                                                    <textarea name="Address" id="Address" cols="30" rows="2" class="form-control" style="resize: none" placeholder="Address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="border: solid 1px;">

                                    <div class="row" >
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Maintenance Type</label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Monthly <input type="checkbox" class="form-control" name="Monthly" id="Monthly" value="1"></label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Quaterly <input type="checkbox" class="form-control" name="Quaterly" id="Quaterly" value="1"></label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Semi Annually <input type="checkbox" class="form-control" name="SemiAnnually" id="SemiAnnually" value="1"></label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Annually <input type="checkbox" class="form-control" name="Annually" id="Annually" value="1"></label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">On Call <input type="checkbox" class="form-control" name="OnCall" id="OnCall" value="1"></label>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="border: solid 1px;">

                                    <div class="row" >
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="DivAppend">
                                            <div class="row">

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Product</label>
                                                    <select name="ProductId[]" id="ProductId1" class="form-control">
                                                        <option value="">Select Product</option>
                                                        <?php foreach($product as $Fil):?>
                                                        <option value="<?php echo $Fil->product_id?>"><?php echo $Fil->p_name?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Front </label>
                                                    <input type="text" class="form-control" name="Front[]" id="Front1" placeholder="Front">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Left </label>
                                                    <input type="text" class="form-control" name="Left[]" id="Left1" placeholder="Left">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Right </label>
                                                    <input type="text" class="form-control" name="Right[]" id="Right1" placeholder="Right">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Back </label>
                                                    <input type="text" class="form-control" name="Back[]" id="Back1" placeholder="Back">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                                                    <label class="sf-label">____________________</label>
                                                    <button type="button" class="btn btn xs btn-primary" onclick="AddMoreRows()">Add More Row's</button>
                                                </div>

                                            </div>



                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Board Maintenance</div>
                                    <div class="panel-body">
                                        <div class="panel-body" style="border: solid 1px;">

                                            <div class="row" >
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    Alucobond Sign Report
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-body" style="border: solid 1px;">

                                            <div class="row" >
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="DivAppend">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Board Cleaning</label>
                                                            <input type="text" class="form-control" name="BoardCleaning" id="BoardCleaning" placeholder="Board Cleaning">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Led Stip</label>
                                                            <input type="text" class="form-control" name="LedStrip" id="LedStrip" placeholder="Led Strip">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Led Wiring</label>
                                                            <input type="text" class="form-control" name="LedWiring" id="LedWiring" placeholder="Led Wiring">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Led Rope</label>
                                                            <input type="text" class="form-control" name="LedRope" id="LedRope" placeholder="Led Rope">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Power Supply</label>
                                                            <input type="text" class="form-control" name="PowerSupply" id="PowerSupply" placeholder="Power Supply">
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label class="sf-label">Note:</label>
                                                            <input type="text" class="form-control" name="sign_note" id="sign_note" placeholder="Note">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body" style="border: solid 1px;">

                                            <div class="row" >
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    Sun Swith DB Box Report
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-body" style="border: solid 1px;">

                                            <div class="row" >
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Auto/Manual Selector</label>
                                                            <input type="text" class="form-control" name="AutoManualSelector" id="AutoManualSelector" placeholder="Auto/Manual Selector">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Contractor</label>
                                                            <input type="text" class="form-control" name="Contractor" id="Contractor" placeholder="Contractor">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Breaker</label>
                                                            <input type="text" class="form-control" name="Breaker" id="Breaker" placeholder="Breaker">
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Sun Switch</label>
                                                            <input type="text" class="form-control" name="SunSwitch" id="SunSwitch" placeholder="Sun Switch">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Volt Led</label>
                                                            <input type="text" class="form-control" name="VoltLed" id="VoltLed" placeholder="Volt Led">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Stabilizer/Lighting Device</label>
                                                            <input type="text" class="form-control" name="StabilizerLightingDevice" id="StabilizerLightingDevice" placeholder="Stabilizer/Lighting Device">
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label class="sf-label">Note:</label>
                                                            <input type="text" class="form-control" name="Note" id="Note" placeholder="Note">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Inside Branch Electric Work</div>
                                    <div class="panel-body">
                                        <div class="panel-body" style="border: solid 1px;">

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label class="sf-label">Timer Connection Disconnect & Connect With Breaker</label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Yes <input type="radio" class="form-control" name="timer_connection" value="1" id="Yes"></label>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">No <input type="radio" class="form-control" name="timer_connection" value="2" id="No"></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Breaker Replaced</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Yes <input type="radio" class="form-control" name="breaker_replaced" value="1" id="Yes"></label>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">No <input type="radio" class="form-control" name="breaker_replaced" value="2" id="No"></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">&nbsp;</div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label class="sf-label">Wiring Addional Installed</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">Yes <input type="radio" class="form-control" name="wiring_additional" value="1" id="Yes"></label>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="sf-label">No <input type="radio" class="form-control" name="wiring_additional" value="2" id="No"></label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label class="sf-label">RFT</label>
                                                            <input type="text" name="Rft" id="Rft" class="form-control">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="border: solid 1px;">

                                    <div class="row" >
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Comments</label>
                                            <input type="text" name="comments" id="comments" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            {{--Image Code--}}
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
                                {{--Image Code--}}


                            </div>




                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        </div>

                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('.select2').select2();

        });

        var CounterRow = 1;


        function AddMoreRows()
        {
            CounterRow++;
            $('#DivAppend').append('<div class="row" id="Row_'+CounterRow+'">'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">'+
                    '<label class="sf-label">Product</label>'+
                    '<select name="ProductId[]" id="ProductId'+CounterRow+'" class="form-control">'+
                    '<option value="">Select Product</option>'+
                    <?php foreach($product as $Fil):?>
                    '<option value="<?php echo $Fil->product_id?>"><?php echo $Fil->p_name?></option>'+
                    <?php endforeach;?>
                    '</select>'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">'+
                    '<label class="sf-label">Front </label>'+
                    '<input type="text" class="form-control" name="Front[]" id="Front'+CounterRow+'" placeholder="Front">'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">'+
                    '<label class="sf-label">Left </label>'+
                    '<input type="text" class="form-control" name="Left[]" id="Left'+CounterRow+'" placeholder="Left">'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">'+
                    '<label class="sf-label">Right </label>'+
                    '<input type="text" class="form-control" name="Right[]" id="Right'+CounterRow+'" placeholder="Right">'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">'+
                    '<label class="sf-label">Back </label>'+
                    '<input type="text" class="form-control" name="Back[]" id="Back'+CounterRow+'" placeholder="Back">'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">'+
                    '<label class="sf-label">____________________</label>'+
                    '<button type="button" class="btn btn xs btn-danger" onclick="RemoveRows()">Remove Row</button></div></div>');
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

        function GetBranch()
        {
            var ClientName = $('#ClientId').val();
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

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
