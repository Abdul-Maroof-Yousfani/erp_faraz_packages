<?php

use App\Helpers\CommonHelper;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$pv_no=CommonHelper::uniqe_no_for_pv(date('y'),date('m'),1);



?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
    @include('select2')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Daily Activity</span>
                    </div>




                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'reports/insertDailyTask?m='.$m.'','id'=>'bankPaymentVoucherForm')); ?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label for="">Task Date</label>
                                        <input type="date" class="form-control requiredField" name="task_date" id="task_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="buildyourform" class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="text-center"> Client </th>
                                                    <th class="text-center"> Cluster </th>
                                                    <th class="text-center"> Region </th>
                                                    <th class="text-center" style="width:400px;">Description</th>
                                                    <th class="text-center">Account Officer</th>
                                                    <th class="text-center">Vendor </th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="Rowcount[]" class="form-control" value="1" />
                                                        <?php $get_all_clients = CommonHelper::get_all_clients();// print_r($get_all_clients); ?>
                                                        <select class="form-control requiredField select2" name="account_id1" id="account_id1">
                                                            <option value="">Select Account</option>
                                                            @foreach($get_all_clients as $val)
                                                                <option value="<?php echo $val->id; ?>"><?php echo $val->client_name; ?></option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control requiredField select2" name="cluster_id1" id="cluster_id1" onchange="getRegionClusterWise(this.value,'1');">
                                                            <option value="">Select Cluster</option>
                                                            @foreach($Cluster as $val)
                                                                <option value="<?php echo $val->id; ?>"><?php echo $val->cluster_name; ?></option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php //$get_all_regions = CommonHelper::get_all_regions(); ?>
                                                        <span id="Loader1"></span>
                                                        <select class="form-control requiredField select2" name="region_id1" id="region_id1">
                                                            <option value="">Select Region</option>
                                                        </select>
                                                    </td>
                                                    <td><textarea name="desc1" id="desc1" class="form-control requiredField"></textarea></td>
                                                    <td>
                                                        <select class="form-control requiredField select2" name="acc_officer1" id="acc_officer1">
                                                            <option value="">Select Region</option>
                                                            @foreach(CommonHelper::AllEmployee() as $val)
                                                                <option value="{{$val->id}}">{{$val->emp_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control requiredField" name="vendor1" id="vendor1" value="" /></td>
                                                    <td class="text-center">---</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="pvsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <!--
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

										<input type="button" class="btn btn-sm btn-primary addMorePvs" value="Add More PV's Section" />
										<!-->
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });
    </script>

    <script>

        function getRegionClusterWise(Id,Row)
        {

            var ClusterId = $('#cluster_id'+Row).val();

            if(ClusterId != "")
            {
                $('#Loader' + Row).html('<img src="/assets/img/loading.gif" alt="">');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/getRegionClusterWise',
                    type: "GET",
                    data: {ClusterId:ClusterId},
                    success: function (data) {
                        $('#region_id'+Row).html('');
                        $('#region_id'+Row).html(data);
                        $('#Loader'+Row).html('');
                    }
                });
            }
            else
            {
                $('#region_id'+Row).html('');
            }
        }

        var x = 2;
        function AddMorePvs()
        {
            x++;
            $('#addMorePvsDetailRows_1').append("<tr id='tr"+x+"' >"+
                    "<td>"+
                    "<input type='hidden' name='Rowcount[]' class='form-control' value="+x+" /><select class='form-control requiredField select2' name='account_id"+x+"' id='account_id"+x+"'><option value=''>Select Account</option><?php foreach($get_all_clients as $val){?><option value='<?php echo $val->id ?>'><?php echo $val->client_name; ?></option><?php }?></select>"+
                    "</td>"+
                    "<td>"+
                    "<select class='form-control requiredField select2' name='cluster_id"+x+"' id='cluster_id"+x+"' onchange='getRegionClusterWise(this.value,"+x+");'>"+
                    "<option value=''>Select Cluster</option>"+
                    @foreach($Cluster as $val)
                    "<option value='<?php echo $val->id; ?>'><?php echo $val->cluster_name; ?></option>"+
                    @endforeach
                    "</select>"+
                    "</td>"+
                    "<td>"+
                    "<span id='Loader"+x+"'></span>"+
                    "<select class='form-control requiredField select2' name='region_id"+x+"' id='region_id"+x+"'><option value=''>Select Region</option></select>"+
                    "</td>"+
                    "<td>"+
                    '<textarea class="form-control requiredField" name="desc'+x+'" id="desc'+x+'"/></textarea>'+
                    "</td>"+
                    "<td>"+
                    '<select class="form-control requiredField select2" name="acc_officer'+x+'" id="acc_officer'+x+'"><option value="">Select Region</option>@foreach(CommonHelper::AllEmployee() as $val)<option value="{{$val->id}}">{{$val->emp_name}}</option>@endforeach</select>'+
                    "</td>"+
                    "<td>"+
                    '<input type="text" class="form-control requiredField" name="vendor'+x+'" id="vendor'+x+'" />'+
                    "</td>"+
                    "<td class='text-center'> <input type='button' onclick='RemoveRow("+x+")' value='Remove' class='btn btn-sm btn-danger'> </td></tr>");
            $('.select2').select2();
        }

        function RemoveRow(x)
        {
            $('#tr'+x).remove();
        }
    </script>

    <script>
        $(".btn-success").click(function(e){
            var rvs = new Array();
            var val;
            $("input[name='pvsSection[]']").each(function(){
                rvs.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of rvs) {
                jqueryValidationCustom();
                if(validate == 0)
                {
                    //alert(response);
                }else{
                    return false;
                }
            }
        });



    </script>


    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>



@endsection