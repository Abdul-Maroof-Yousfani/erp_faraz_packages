<?php
$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
?>
@extends('layouts.default')
@section('content')
@include('select2')

<div class="container-fluid">
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Production Order </h2>
                    </div>
                </div>
            </div>
            {{ Form::open(array('url' => 'far_prod/addProductionOrderDetail?m=' . $m . '', 'id' => 'productionOrderDetail' )) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="pageType" value="{{ $_GET['pageType'] }}">
            <input type="hidden" name="parentCode" value="{{ $_GET['parentCode'] }}">
            <input type="hidden" name="demandsSection[]" id="demandsSection" value="1" />
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">PO NO. </label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input readonly type="text" class="form-control requiredField" name="pr_no"
                                id="pr_no" value="{{strtoupper($pr_no)}}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">PO Date.</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" class="form-control requiredField"
                                 name="request_date" id="request_date"
                                value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Ref No. </label>
                            <input type="text" class="form-control" name="ref_no" id="ref_no" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="sf-label">Description</label>
                            <textarea name="description" id="description" rows="4" cols="50"
                                style="resize:none;" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="headquid">
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <h2 class="subHeadingLabelClass">Item Details</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive" id="">
                                <table class="userlittab table table-bordered sf-table-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center col-sm-2">Sub Category</th>
                                            {{-- <th class="text-center">UOM</th> --}}
                                            {{-- <th class="text-center">Color</th> --}}
                                            {{-- <th class="text-center">QTY (KG)</th> --}}
                                            <th class="text-center">Reason</th>
                                            <th class="text-center col-sm-2">When Required</th>
                                            {{-- <th class="text-center">History</th> --}}
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="AppnedHtml">
                                        <tr>
                                            <td>
                                                <select style="width: 100% !important;"
                                                    name="sub_category[]"
                                                    id="sub_category1"
                                                    class="form-control requiredField select2">
                                                    <option value="">Select</option>
                                                    @foreach($sub_categories as $val)
                                                        <option
                                                            value="{{$val->id}}">
                                                            {{ $val->sub_category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            {{-- <td>
                                                <input readonly type="text" class="form-control"
                                                    name="uom_id[]" id="uom_id1">
                                            </td> --}}
                                            {{-- <td>
                                                <select style="width: 100% !important;" class="form-control select2 requiredField" name="color[]" id="color1">
                                                    <option value="">Select Color</option>
                                                    @foreach ($color as $key => $value)
                                                    <option value="{{ $value->color }}"> {{ $value->color }} </option>
                                                    @endforeach
                                                </select>
                                            </td> --}}
                                            {{-- <td>
                                                <input type="text" class="form-control requiredField"
                                                    name="quantity[]" id="quantity1">
                                            </td> --}}
                                            <td>
                                                <input type="text" class="form-control" name="purpose[]"
                                                    id="purpose1">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control" name="required_date[]"
                                                    id="required_date1">
                                            </td>
                                            {{-- <td class="text-center"><input onclick="view_history(1)"
                                                    type="checkbox" id="view_history1">
                                            </td> --}}
                                            <td> <a href="#" class="btn btn-sm btn-primary"
                                                    onclick="AddMoreDetails()"><span
                                                        class="glyphicon glyphicon-plus-sign"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mp-20 text-right">
                {{ Form::submit('Submit', ['class' => 'btnn btn-success']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    var Counter = 1;
    function AddMoreDetails() {
        Counter++;
        $('#AppnedHtml').append(`
            <tr class="RemoveRows${Counter}">
                <td>
                    <select style="width: 100% !important;"
                        name="sub_category[]"
                        id="sub_category${Counter}"
                        class="form-control requiredField select2">
                        <option>Select</option>
                        @foreach($sub_categories as $val)
                            <option
                                value="{{ $val->id }}">
                                {{ $val->sub_category_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                {{-- <td>
                    <input readonly type="text" class="form-control"
                        name="uom_id[]" id="uom_id${Counter}">
                </td>
                
                <td>
                    <input type="text" class="form-control requiredField"
                        name="quantity[]" id="quantity${Counter}">
                </td> --}}
                <td>
                    <input type="text" class="form-control" name="purpose[]"
                        id="purpose${Counter}">
                </td>
                <td>
                    <input type="date"
                        class="form-control" name="required_date[]"
                        id="required_date${Counter}">
                </td>
                {{-- <td class="text-center"><input onclick="view_history(1)"
                        type="checkbox" id="view_history${Counter}">
                </td> --}}
                <td> <a href="#" class="btn btn-sm btn-danger"
                        onclick="RemoveSection(${Counter})"><span
                            class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
            </tr>`);

        $('#sub_category' + Counter).select2();
        $('#color' + Counter).select2();
    }

    function RemoveSection(Row) {
        $('.RemoveRows' + Row).remove();
    }

    $(function () {
        $('.select2').select2();
        $(".btn-success").click(function (e) {
            var purchaseRequest = new Array();
            var val;
            purchaseRequest.push($(this).val());
            var _token = $("input[name='_token']").val();
            for (val of purchaseRequest) {
                jqueryValidationCustom();
                if (validate == 0) {

                    $('#productionOrderDetail').submit();
                }
                else {
                    return false;
                }
            }
        });
    });

    // function get_detail(id, number) {
    //     var item = $('#' + id).val();
    //     $.ajax({
    //         url: '{{url('/pdc/get_data')}}',
    //         data: { item: item },
    //         type: 'GET',
    //         success: function (response) {

    //             var data = response.split(',');
    //             $('#uom_id' + number).val(data[0]);
    //             $('#last_ordered_qty' + number).val(data[1]);
    //             $('#last_received_qty' + number).val(data[2]);
    //             $('#closing_stock' + number).val(data[3]);

    //         }
    //     });
    // }

    function view_history(id) {
        var v = $('#item_id' + id).val();
        if ($('#view_history' + id).is(":checked")) {

            if (v != 'Select') {
                showDetailModelOneParamerter('pdc/viewHistoryOfItem?id=' + v);
            }
        }
    }

    function get_item_name(index) {

        var item = $('#item_id' + index).val();
        var uom = item.split('@');
        $('#uom_id' + index).val(uom[1]);
        $('#item_code' + index).val(uom[2]);
    }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection