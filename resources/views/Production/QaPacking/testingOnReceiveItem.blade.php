@extends('layouts.default')

@section('content')
@include('select2')
<?php
    use App\Helpers\CommonHelper;
    $i=1;
?>
<style>
    .my-lab label {
    padding-top:0px; 
}
</style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Test on Item</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
        
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="{{route('QaPacking.storeTestResult')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="packing_id" value="{{$qc_packings->packing_id}}">
                                    <input type="hidden" name="qc_packing_id" value="{{$qc_packings->qc_packing_id}}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class=" qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>QC PIPE TESTING</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Sale Order #</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name"  value="{{$qc_packings->sale_order_no}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Production Plan #</label>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name" value="{{$qc_packings->order_no}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Packing List #</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name"  value="{{$qc_packings->packing_list_no}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                </div>
 
                                                <div class="col-md-6">
 
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Customer Name</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name"  value="{{$qc_packings->customer_name}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Qc Packing Date</label>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name"  value="{{$qc_packings->qc_packing_date}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>QC by</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled id="customer_name"  value="{{$qc_packings->qc_by}}" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                               
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12" style="overflow-x: scroll;">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Bundle No.</th>
                                                                <th>Length</th>
                                                                @foreach($test_column as $key => $value)
                                                                    <th>
                                                                        <table>

                                                                            <tr>
                                                                                <th class="text-center">{{$value->name}}</th>
                                                                            </tr>

                                                                            <tr>
                                                                                <th>Req. Value</th>
                                                                                <th>Test Value</th>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                @endforeach   
                                                                <th>Qc status</th> 
                                                            </tr>
                                                            <tbody id="more_details">
                                                                @foreach($items as $key => $value)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="packing_data_id[]" value="{{ $value->id }}">
                                                                            {{ $i++ }}
                                                                        </td>
                                                                        
                                                                        <td>
                                                                            {{ $value->bundle_no }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $value->qty }}
                                                                        </td>
                                                                        @foreach($test_column as $test_column_key => $test_column_value)
                                                                        <td>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>{{ $test_column_value->test_value }}</td>
                                                                                    <td>
                                                                                        <input type="hidden" name="qc_test_id{{ $value->id }}[]" value="{{ $test_column_value->id }}">
                                                                                        <input type="text" name="test_result{{ $value->id }}[]">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                        @endforeach    
                                                                        <td>
                                                                            <select name="qc_test_status{{ $value->id }}" id="">
                                                                                <option value="1">Pending</option>
                                                                                <option value="2">Pass</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach   

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" id="btn" data-dismiss="modal">Save</button>
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
        </div>
    </div>


@endsection