<?php
use App\Helpers\CommonHelper;
$i = 1;
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
                                <form action="{{route('grnStoreQcValues')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                            <div class=" qout-h">
                                                <div class="col-md-12 bor-bo">
                                                    <h1>QC Values Form</h1>
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
                                                                    @foreach (CommonHelper::get_all_category() as $category)
                                                                    <option value="{{ $category->id }}"> {{ $category->main_ic }} </option>
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
                                                                <select name="item_id[]" id="item_id" class="form-control requiredField select2">
                                                                    <option>Select</option>
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
                                                            @foreach($QaTest as $test)
                                                                <tr>
                                                                    <td class="text-center">{{$i}}
                                                                        <input type="hidden" name="test_id[]" id="test_id{{$test->id}}"
                                                                        value="{{$test->id}}">
                                                                    </td>
                                                                    <td>{{$test->name}}</td>
                                                                    <td class="text-center">
                                                                        <input type="text" name="standard_value[]"
                                                                            id="standard_value{{$test->id}}"
                                                                            class="form-control" />
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
                                                    data-dismiss="modal">Save</button>
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
   $('.select2').select2();
</script>

@endsection