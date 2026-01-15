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
use App\Models\Employee;

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
                                <span class="subHeadingLabelClass">View Loan Requests List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanRequestList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LoanRequestList','','1')?>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintLoanRequestList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover" id="LoanRequestList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Emp Name</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Deposit Name</th>
                                            <th class="text-center">Deposit Amount</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?> <?php
                                            foreach($employeeDeposit as $y):
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $empp_data = Employee::select('emp_name','emp_sub_department_id')->where([['acc_no','=',$y['acc_no']]])->get()->toArray();
                                            CommonHelper::reconnectMasterDatabase();

                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center"><?php echo $empp_data[0]['emp_name'];?></td>
                                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$empp_data[0]['emp_sub_department_id']) }}</td>
                                                <td class="text-center">{{ $y['deposit_name'] }}</td>
                                                <td class="text-center">{{ $y['deposit_amount'] }}</td>
                                                <td class="text-center">{{ $y['deduction_month'] }}</td>
                                                <td class="text-center">{{ $y['deduction_year'] }}</td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($y['status']) }}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            <li role="presentation">
                                                                <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeDepositDetail','<?php echo $y['id'];?>','View Employee Deposit Detail','<?php echo $m; ?>')">
                                                                    View
                                                                </a>
                                                            </li>

                                                            <li role="presentation">
                                                                <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeDepositDetail','<?php echo $y['id'];?>','Edit Employee Deposit Detail','<?php echo $m; ?>')">
                                                                    Edit
                                                                </a>
                                                            </li>

                                                            @if($y->status == 2)
                                                                <li role="presentation">
                                                                    <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee_deposit')">
                                                                       Repost
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li role="presentation">
                                                                    <a class="delete-modal btn" onclick="deleteRowCompanyRecords('<?=$m ?>','<?php echo $y->id ?>','employee_deposit')">
                                                                       Delete
                                                                    </a>
                                                                </li>
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                            <?php endforeach;  ?>

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

    <script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection