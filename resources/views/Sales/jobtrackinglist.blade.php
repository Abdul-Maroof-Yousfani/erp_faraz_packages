<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Job Tracking List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo date('Y-m-d');?>" class="form-control" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">Customer</th>
                                            <th class="text-center">Customer Job</th>
                                            <th class="text-center">Region</th>
                                            <th class="text-center">Job Description</th>
                                            <th class="text-center">Job Tracking</th>
                                            <th class="text-center">Tracking Date</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php
                                            $counter = 1;$total=0;
                                            $paramOne = "sdc/viewJobTrackingListDetail?m=".$m;
                                            ?>

                                            @foreach($jobtracking as $row)
                                                <?php
                                                $client_name=CommonHelper::get_client_name_by_id($row->customer);
                                                $region_data=CommonHelper::get_rgion_name_by_id($row->region);
                                                $region_name=$region_data->region_name;
                                                $city_data=CommonHelper::get_all_cities_by_id($row->city);
                                                $city_name=$city_data->name;
                                                ?>
                                                <tr>
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{ $client_name }}</td>
                                                    <td class="text-center">{{ $row->customer_job }}</td>
                                                    <td class="text-center">{{ $region_name }}</td>
                                                    <td class="text-center">{{ $row->job_description }}</td>
                                                    <td class="text-center">{{ $row->job_tracking_no }}</td>
                                                    <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->job_tracking_date); ?></td>
                                                    <td class="text-center">{{ $city_name }}</td>
                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->job_tracking_id ?>','Job Tracking Detail')" type="button" class="btn btn-success btn-xs">View</button>
                                                        <button onclick="TrackingDelete('<?= $row->job_tracking_id ?>')" id="delete<?= $row->job_tracking_id ?>" type="button" class="btn btn-success btn-xs">Delete</button>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function TrackingDelete(id)
        {
            if(id !="")
            {
                $.ajax({
                    url: '<?php echo url('/')?>/sdc/TrackingDelete',
                    type: "GET",
                    data: {id:id},
                    success: function (data) {
                        alert("Successfully Delete");
                        $("#delete"+id).hide(data);
                    }
                });
            }
        }
    </script>

@endsection