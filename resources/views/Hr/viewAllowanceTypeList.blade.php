<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Allowance Type List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAllownceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AllowanceList','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-body" id="PrintAllownceList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover table-striped" id="AllowanceList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Allowance Type</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center col-sm-1 hidden-print">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @if($allowance_type->count() > 0)
                                                    @foreach($allowance_type->get() as $key => $value)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td>{{ $value->allowance_type }}</td>
                                                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                            <td class="text-center hidden-print">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                        @if(in_array('edit', $operation_rights))
                                                                            <li role="presentation">
                                                                                <a class="edit-modal btn" onclick="showMasterTableEditModel('hr/editAllowanceTypeForm','<?php echo $value->id;?>','Edit Allowance Type Detail','<?php echo $m; ?>')">
                                                                                    Edit
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('delete', $operation_rights))
                                                                            @if($value->status == 1)
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="deleteRowMasterTable('<?php echo $value->id ?>','allowance_type')">
                                                                                        Delete
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr><td class="text-center" colspan="4" style="color: red"> No Record Found !</td></tr>
                                                @endif
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
    </div>


    <script>

        $(document).ready(function() {
            $("#employee_project_id").select2();
            var table = $('#AllowanceList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('#emp_code').keyup( function() {
                table.columns(1).search(this.value).draw();
            });

            $('#emp_name').keyup( function() {
                table.columns(2).search(this.value).draw();
            });



        });

    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection

