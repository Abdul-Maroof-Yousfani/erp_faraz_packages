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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Resource Assigned List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
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
                                            <th class="text-center col-sm-1">Product Type</th>
                                            <th class="text-center">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/editResourceAssignedForm?m=".$m;
                                            ?>

                                            @foreach($resourceAssign as $row)
                                                <tr id="{{ $row->id }}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{ $row['resource_type'] }}</td>
                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id ?>','Edit Resource Assigned')" type="button" class="btn btn-success btn-sm">EDIT</button>
                                                        <button class="btn btn-sm btn-danger" onclick="delete_record('<?php echo $row->id; ?>')"> DELETE </button>
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
        function delete_record(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deleteResourceAssignedList',
                    type: 'Get',
                    data: {id: id},
                    success: function (response) {
                        alert('Deleted');
                        $('#' + id).remove();
                    }
                });
            }
            else{}
        }
    </script>

@endsection