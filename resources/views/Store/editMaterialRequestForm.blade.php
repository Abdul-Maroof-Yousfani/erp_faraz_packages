<?php
    use App\Helpers\CommonHelper;
    $accType = Auth::user()->acc_type;
    $m = session()->get('run_company');

?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
	    <div class="boking-wrp dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid">
                                <span class="subHeadingLabelClass">Edit Material Request Form</span>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'stad/editMaterialRequestDetail?m='.$m.'', 'id' => 'addMaterialRequestDetail', 'method' => 'PUT')); ?>
 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="materialRequestsSection[]" class="form-control requiredField" id="materialRequestsSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Material Request Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField fromDateDatePicker" name="material_request_date_1" id="material_request_date_1" value="{{ $material_requests->material_request_date }}" />
                                                
                                                <input type="hidden" class="form-control requiredField" name="material_request_no_1" id="material_request_no_1" value="{{ $material_requests->material_request_no }}" />
                                                <input type="hidden" class="form-control requiredField" name="id_1" id="id_1" value="{{ $material_requests->id }}" />

                                            </div>
                                            
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Department / Sub Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField select2" name="sub_department_id_1" id="sub_department_id_1">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $key => $y)
                                            <option @if($material_requests->sub_department_id == $y->id) selected @endif value="{{ $y->id}}">
                                                {{ $y->department_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="sf-label">Material Request No.</label>
                                            <input readonly type="text" class="form-control" name="material_request_no"
                                                id="material_request_no" value="{{$material_requests->material_request_no}}" />
                                            
                                        </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Remarks</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description_1" id="description_1" rows="3" cols="50" style="resize:none;" class="form-control requiredField">{{ $material_requests->description }}</textarea>
                                            </div>
                                           
                                        </div>
                                        <div class="row">
                                            
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>
                                        
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="itemList" class="table-responsive">
                                                    <table id="buildyourform" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">Item <span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center">Qty.<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th class="text-center">Description</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="addMoreMaterialRequestsDetailRows_1" id="addMoreMaterialRequestsDetailRows_1">
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="materialRequestDataSection[]" class="form-control requiredField materialRequestDataSection_1" id="materialRequestDataSection_1" value="1" />
                                                                    <select style="width:100%;" name="sub_item_id[]" id="item_1"
                                                                        class="form-control select2 requiredField" required>
                                                                        <option value="">Select</option>
                                                                        @foreach (CommonHelper::get_item_by_category(7) as $item)
                                                                            <option value="{{ $item->id }}" data-uom="{{$item->uom_name}}">
                                                                                {{ $item->sub_ic }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                <td>
                                                                    <input type="number" name="qty[]" id="qty_1_1" step="0.0001" class="form-control requiredField" value="1" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="sub_description[]" id="sub_description_1_1" value="-" class="form-control requiredField" />
                                                                </td>
                                                                <td class="text-center">---</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreMaterialRequestsDetailRows('1')" value="Add More" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="materialRequestsSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success btnSubmit']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    <input type="button" style="display: none;" class="btn btn-sm btn-primary addMoreMaterialRequests" value="Add More Material Material's Section" />
                                </div>
                            </div>
                        <?php echo Form::close();?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadJobCardDetail(id){
            var jobCardDetail = $('#jobCardDetail').val();
            if(jobCardDetail == ''){
                $('#job_card_no').val('');
                $('#job_card_id').val('');
                $('#addMoreMaterialRequestsDetailRows_'+id+'').html('');
            }else{
                var explodeJobCardDetail = jobCardDetail.split('<*>');
                var jobCardId = explodeJobCardDetail[0];
                var jobCardNo = explodeJobCardDetail[1];

                $('#job_card_no').val(jobCardNo);
                $('#job_card_id').val(jobCardId);

                $('#addMoreMaterialRequestsDetailRows_'+id+'').html('');

                var m = '<?php echo $m;?>';

                let material_request_no = '<?php echo $material_requests->material_request_no;?>';
                let material_request_date = '<?php echo $material_requests->material_request_date;?>';
                $.ajax({
                    url: '<?php echo url('/')?>/store/loadMaterialRequestRowsAgainstJobCardEdit',
                    type: "GET",
                    data: { 
                            jobCardId:jobCardId,
                            jobCardNo:jobCardNo,
                            m:m,
                            id:id,
                            material_request_no:material_request_no,
                            material_request_date:material_request_date
                        },
                    success:function(data) {
                        $('#addMoreMaterialRequestsDetailRows_'+id+'').html(data);
                        $('.select2').select2();
                    }
                });

            }
        }
        loadJobCardDetail();
        $(document).ready(function(){
            $('.select2').select2();
        });
        $(document).ready(function() {
            $(".btn-success").click(function(e){
                var materialRequests = new Array();
                var val;
                $("input[name='materialRequestsSection[]']").each(function(){
                    materialRequests.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of materialRequests) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                        $(".btnSubmit").val('Sending, please wait...');
                        setTimeout(function(){
                            $(".btnSubmit").prop("type", "button");
                        },50);
                    }else{
                        return false;
                    }
                }

            });
        });
        var x = 9999;
        function addMoreMaterialRequestsDetailRows(id){
            //console.log($('.materialRequestDataSection_1:last').val());
            if ($('.addMoreMaterialRequestsDetailRows_'+id+':last').val()) {
                x = $('.addMoreMaterialRequestsDetailRows_'+id+':last').val()
            }
            x++;
            var m = '<?php echo $m;?>';
            $.ajax({
                url: '<?php echo url('/')?>/store/addMoreMaterialRequestsDetailRows',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {
                    $('.addMoreMaterialRequestsDetailRows_'+id+'').append(data);
                }
            });
        }

        function removeMaterialRequestsRows(id,counter){
            var elem = document.getElementById('removeMaterialRequestsRows_'+id+'_'+counter+'');
            elem.parentNode.removeChild(elem);
        }
        function subItemListLoadDepandentCategoryId(id,value) {
            var arr = id.split('_');
            var m = '<?php echo $m;?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

        function triggerOnload() {
            var selectBoxes = document.getElementsByTagName('select');

            for (var i = 0; i < selectBoxes.length; i++) {
                var selectBox = selectBoxes[i];
                selectBox.onchange(); // Trigger the onchange event
            }
        }

        // Trigger the function when the window has loaded
        window.onload = function () {
            triggerOnload();
        };
    </script>
@endsection