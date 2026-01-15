<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');


?>

<div class="panel">
    <div class="panel-body" id="PrintEmployeeList">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover" id="EmployeeList">

                        <thead>
                        <th class="text-center">S.No</th>
                        <th class="text-center">Emp Code</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">DOB</th>
                        <th class="text-center">Joining Date</th>
                        <th class="text-center">Contact No</th>
                        <th class="text-center">CNIC</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Current Salary</th>
                        <!--<th class="text-center">GSSP Documents</th>
                        <th class="text-center">Documents</th>-->
                        <th class="text-center">Status</th>
                        <th class="text-center hidden-print">Action</th>
                        </thead>
                        <tbody>
                        <?php $counter = 1;?>
                        @foreach($employees as $key => $y)
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center">{{ $y->emp_code}}</td>
                                <td class="text-center">{{ $y->emp_name}}</td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$y->emp_department_id)}}</td>
                                <td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>
                                <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>
                                <td class="text-center">{{ $y->emp_contact_no}}</td>
                                <td class="text-center">{{ $y->emp_cnic}}</td>
                                <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}</td>
                                <td class="text-center"><?php echo number_format($y->emp_salary);?></td>

                                <!--<?php $gsspCheck =  HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee_gssp_documents','counter',$y->emp_code, 'emp_code'); ?>
                                @if($gsspCheck > 0)
                                    <td class="text-center">
                                        <a onclick="showMasterTableEditModel('hdc/viewEmployeeGsspVeriDetail','<?php echo $y->id;?>','View Employee GSSP Documents','<?php echo $m; ?>')" class=" btn btn-info btn-xs">View</a>
                                    </td>
                                @else
                                    <td class="text-center"> -- </td>
                                @endif

                                <?php $documentsCheck =  HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee_documents','documents_upload_check',$y->emp_code, 'emp_code'); ?>
                                @if($documentsCheck == 1)
                                    <td class="text-center">
                                        <a onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','<?php echo $y->id;?>','View Employee Documents','<?php echo $m; ?>')" class=" btn btn-info btn-xs">View</a>
                                    </td>
                                @else
                                    <td class="text-center"> -- </td>
                                @endif-->

                                <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>
                                <td class="text-center hidden-print">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            <li role="presentation">
                                                <a class="delete-modal btn" onclick="showMasterTableEditModel('hdc/viewEmployeeDetail','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
                                                    View
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a  class="delete-modal btn" href="<?= url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")?>">
                                                    Edit
                                                </a>
                                            </li>
                                            @if($y->status == 2)
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
                                                        Repost
                                                    </a>
                                                </li>
                                            @else
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="deleteEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee','<?php echo $y->emp_code ?>')">
                                                        Delete
                                                    </a>
                                                </li>
                                            @endif
                                            @if($y->status == 4 || $y->status == 3)
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="restoreEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
                                                        Active
                                                    </a>
                                                </li>
                                            @else
                                                <li role="presentation">
                                                    <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hmfal/makeFormEmployeeInActive','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
                                                        InActive
                                                    </a>
                                                </li>
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