<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <?php echo Form::open(array('url' => 'fad/add_role?m='.$m,'id'=>'createSalesOrder'));?>
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="headquid">
                                    <span class="subHeadingLabelClass">Users Rights Screen</span>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                <select name="users" id="users" class="form-control">
                                                    <option>Select</option>
                                                   @foreach(CommonHelper::get_all_users() as $row)
                                                       <option value="{{$row->emp_code}}">{{$row->name}}</option>
                                                       @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <?php $Comp =  DB::table('company')->where('status',1)->get();?>

                                                <select name="CompanyId" id="CompanyId" class="form-control">
                                                    <option value="0">Select Company</option>
                                                    <?php foreach($Comp as $F):?>
                                                    <option value="<?php echo $F->id?>"><?php echo $F->name?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                            {{--<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">--}}
                                                {{--<select name="MainModuelCode" id="MainModuelCode" class="form-control" multiple>--}}
                                                    {{--<option value="1">Inventory</option>--}}
                                                    {{--<option value="2">Inventory Master</option>--}}
                                                    {{--<option value="3">Inventory Report</option>--}}
                                                    {{--<option value="4">Sales</option>--}}
                                                    {{--<option value="5">Finance</option>--}}
                                                    {{--<option value="6">Reports</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <button type="button" class="btn btn-primary" id="BtnRight" onclick="get_rights()">Get All Menu's</button>
                                            </div>
                                        </div>
                                    </div>

                                    <span id="rights"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php echo Form::close();?>
    <script>
        $(document).ready(function(){
            $('#MainModuelCode').select2();
        });
        function get_rights()
        {

            var users=    $('#users').val();
            var MainModuelCode=    $('#MainModuelCode').val();
            var company_id=    $('#CompanyId').val();
            $('#rights').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
                $.ajax({
                    url: '{{url('fdc/get_rights?m='.$m)}}',
                    type: 'Get',
                    data: {users: users,MainModuelCode:MainModuelCode,company_id:company_id},

                    success: function (response)
                    {
                        $('#rights').html(response);
                    }
                });


        }
    </script>
@endsection