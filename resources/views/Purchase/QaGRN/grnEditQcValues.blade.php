<?php
use App\Helpers\CommonHelper;
$i = 1;
$m = Session::get('run_company');
?>
@extends('layouts.default')
@section('content')
@include('select2')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="{{route('grnUpdateQcValues')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{ $qcValue->id }}">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                            <div class=" qout-h">
                                                <div class="col-md-12 bor-bo">
                                                    <h1>Edit GRN QC Values Form</h1>
                                                </div>

                                                <div class="col-md-12 padt pos-r">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Category</label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <select onchange="get_sub_item('category_id')" name="category" id="category_id"  class="form-control category select2 requiredField">
                                                                    <option value="">Select</option>
                                                                    @foreach (CommonHelper::get_all_category() as $cat)
                                                                        <option value="{{ $cat->id }}"
                                                                            {{ $cat->id == $item->main_ic_id ? 'selected' : '' }}>
                                                                            {{ $cat->main_ic }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-4">
                                                                <label>Finish Good Item</label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <select class="form-control select2 requiredField"
                                                                    name="item_id[]" id="item_id" multiple>
                                                                    <option selected data-item-code="{{ $item->item_code }}"
                                                                        value="{{ $item->id }}">
                                                                        {{ $item->item_code . ' - ' . $item->sub_ic }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                
                                            </div>
                                        </div>

                                        <div class="col-md-12 padt">
                                            <div class="col-md-12 padt">
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Test Name</th>
                                                            <th class="text-center">ZCI Standard Value</th>
                                                        </tr>
                                                        <tbody id="more_details">
                                                            @foreach ($qcValuesData as $index => $value)
                                                                <tr>
                                                                    <td class="text-center">{{$i}}</td>
                                                                    <td>{{ $value->test_name }}</td>
                                                                    <td>
                                                                        <input type="hidden" name="test_id[]"
                                                                            value="{{ $value->test_id }}">
                                                                        <input type="text" name="standard_value[]"
                                                                            value="{{ $value->standard_value }}"
                                                                            class="form-control">
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $i++;   
                                                                @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 padtb text-right">
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3 my-lab">
                                                <button type="submit" class="btn btn-primary mr-1" id="btn"
                                                    data-dismiss="modal">Update</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#item_id').select2();


</script>

@endsection