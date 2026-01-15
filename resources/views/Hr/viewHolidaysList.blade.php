<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
//print_r($holidays); die();
?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>


@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Holidays List</span>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>
                    <!--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Month & Year</label>
                        <input type="month" name="monthYear" id="monthYear"  value="<?php echo date('Y-m');?>" class="form-control" style="float: right" />
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                        <input type="button" value="Search" class="btn btn-sm btn-danger" onclick="viewHolidaysMonthWise();" style="margin-top: 32px; float: right"/>
                    </div>-->
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loader"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="MonthlyData">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list table-hover" id="EOBIList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Holiday Name</th>
                                        <th class="text-center">Holiday Date</th>
                                        <th class="text-center">Holiday Month-Year</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;

                                        ?>
                                        @foreach($holidays as $key => $value)
                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td>{{ $value->holiday_name }}</td>
                                                <td class="text-center">{{ HrHelper::date_format($value->holiday_date) }}</td>
                                                <td class="text-center">{{ $value->month."-".$value->year }}</td>
                                                <td class="text-center">{{ $value->username }}</td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>

                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('view', $operation_rights))
                                                                <li role="presentation">
                                                                    <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewHolidaysDetail','<?=$value->id?>','View Holiday Detail','<?=$m?>')">
                                                                        View
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation">
                                                                    <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editHolidaysDetailForm','<?=$value->id?>','Edit Holiday Detail','<?=$m?>')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($value->status == 2)
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="repostOneTableRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
                                                                            Repost
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyRecords('<?=$m?>','<?php echo $value->id ?>','holidays')">
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



    <script>

    function viewHolidaysMonthWise()
    {
        $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        var  monthYear = $('#monthYear').val();
        var m = '<?php echo $m?>';
         $.ajax({
            url: '<?php echo url('/')?>/hdc/viewHolidaysMonthWise',
            type: "GET",
            data: { monthYear:monthYear,m:m},
            success:function(data) {
                $('#loader').html('');
                $('#MonthlyData').empty();
                $('#MonthlyData').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
            }
        })

    }

    $(function(){
        $('select[name="department_id"]').on('change', function() {

            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            var department_id = $(this).val();
            var m = '<?= Input::get('m'); ?>';
            if(department_id) {
                $.ajax({
                    url: '<?php echo url('/')?>/slal/MachineEmployeeListDeptWise',
                    type: "GET",
                    data: { department_id:department_id,m:m},
                    success:function(data) {

                        $('#emp_loader').html('');
                        $('select[name="employee_id"]').empty();
                        $('select[name="employee_id"]').html(data);
                        $('#employee_id').find('option').get(0).remove();


                    }
                });
            }else{
                $('select[name="employee_id"]').empty();
            }
        });
    });

</script>

@endsection