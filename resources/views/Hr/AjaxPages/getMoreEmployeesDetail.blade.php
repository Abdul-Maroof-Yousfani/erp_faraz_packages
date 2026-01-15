<?php

use App\Helpers\HrHelper;
$m = Input::get('m');
$counter = Input::get('counterId');
$counter++;
?>
@foreach($employees as $key => $y)
    <tr class="post-id" id="<?= $y->emp_code; ?>">
        <td class="text-center counterId" id="<?php echo $counter;?>"><?php echo $counter++;?></td>
        <td class="text-center">{{ $y->emp_code}}</td>
        <td>{{ $y->emp_name}}</td>
        <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$y->emp_department_id)}}</td>
        <td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>
        <td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>
        <td class="text-center">{{ $y->emp_contact_no}}</td>
        <td class="text-center">{{ $y->emp_cnic}}</td>
        <td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}</td>
        <td class="text-right"><?php echo number_format($y->emp_salary);?></td>

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
                    @if(in_array('view', $operation_rights2))
                        <li role="presentation">
                            <a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeDetail','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
                                View
                            </a>
                        </li>
                    @endif
                    @if(in_array('edit', $operation_rights2))
                        <li role="presentation">
                            <a  class="delete-modal btn" href="<?= url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")?>">
                                Edit
                            </a>
                        </li>
                    @endif
                    @if(in_array('repost', $operation_rights2))
                        @if($y->status == 2)
                            <li role="presentation">
                                <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
                                    Repost
                                </a>
                            </li>
                        @endif
                    @endif
                    @if(in_array('delete', $operation_rights2))
                        @if($y->status == 1)
                            <li role="presentation">
                                <a class="delete-modal btn" onclick="deleteEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee','<?php echo $y->emp_code ?>')">
                                    Delete
                                </a>
                            </li>
                        @endif
                    @endif

                    @if($y->status == 4)
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
