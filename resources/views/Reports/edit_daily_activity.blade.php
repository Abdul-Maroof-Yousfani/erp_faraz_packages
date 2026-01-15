<?php

use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//print_r($DailyTask);
 //       echo "<pre>";
//print_r($DailyTaskData);
   //     die();
//print_r(CommonHelper::get_all_clients()); die();
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
                        <span class="subHeadingLabelClass">Create Bank Payment Voucher Form</span>
                    </div>
                </div>

                <div class="lineHeight">&nbsp;</div>

                <div class="row">
                    <?php echo Form::open(array('url' => 'reports/updateDailyTask?m='.$m.'','id'=>'bankPaymentVoucherForm')); ?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
                                        <input type="hidden" name="id" id="id" value="<?php echo $DailyTask->id; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label for="">Task Date</label>
                                        <input type="date" class="form-control requiredField" name="task_date" id="task_date" value="<?php echo $DailyTask->task_date; ?>">
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
                                                <?php $counter=0; ?>
                                                @foreach($DailyTaskData as $row)
                                                    <?php $counter++; ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="Rowcount[]" class="form-control" value="<?= $counter; ?>" />
                                                        <?php $get_all_clients = CommonHelper::get_all_clients();// print_r($get_all_clients);
                                                        ?>

                                                        <select class="form-control requiredField select2" name="account_id<?= $counter; ?>" id="account_id<?= $counter; ?>">
                                                            <option value="">Select Account</option>
                                                            @foreach($get_all_clients as $val)
                                                                <option value="<?php echo $val->id; ?>" @if($val->id==$row->client) {{ "selected" }} @endif ><?php echo $val->client_name; ?></option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php $Region = CommonHelper::get_single_row('region','id',$row->region);
                                                            $ClusterId = $Region->cluster_id;
                                                        ?>

                                                        <select class="form-control requiredField select2" name="cluster_id<?php echo $counter?>" id="cluster_id<?php echo $counter?>" onchange="getRegionClusterWise(this.value,'<?php echo $counter?>');">
                                                            <option value="">Select Cluster</option>
                                                            @foreach($Cluster as $val)
                                                                <option value="<?php echo $val->id; ?>" <?php if($ClusterId == $val->id){echo "selected";}?>><?php echo $val->cluster_name; ?></option>
                                                            @endforeach
                                                        </select>
                                                    </td>


                                                    <td>
                                                        <?php $get_regions = CommonHelper::get_single_row('region','id',$row->region); ?>
                                                        <span id="Loader<?php echo $counter?>"></span>
                                                        <select class="form-control requiredField select2" name="region_id<?= $counter; ?>" id="region_id<?= $counter; ?>">
                                                            <option value="">Select Region</option>

                                                                <option value="<?php echo $get_regions->id; ?>" @if($get_regions->id==$row->region) {{ "selected" }} @endif ><?php echo $get_regions->region_name; ?></option>
                                                        </select>
                                                    </td>
                                                    <td><textarea name="desc<?= $counter; ?>" id="desc<?= $counter; ?>" class="form-control requiredField">{{$row->description}}</textarea></td>
                                                    <td>
                                                        <select class="form-control requiredField select2" name="acc_officer<?= $counter; ?>" id="acc_officer<?= $counter; ?>">
                                                            <option value="">Select Region</option>
                                                            @foreach(CommonHelper::AllEmployee() as $val)
                                                                <option value="{{$val->id}}" @if($val->id == $row->acc_officer) {{ "selected" }} @endif >{{$val->emp_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control requiredField" name="vendor<?= $counter; ?>" id="vendor<?= $counter; ?>" value="{{$row->vendor}}" /> </td>
                                                    <td class="text-center">---</td>
                                                </tr>
                                                @endforeach

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

        var x = '<?= $counter; ?>';
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