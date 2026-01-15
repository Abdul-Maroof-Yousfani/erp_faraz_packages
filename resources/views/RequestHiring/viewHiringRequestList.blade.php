@extends('layouts.default')
@section('content')
<?php 
    use App\Helpers\FinanceHelper;
    use App\Helpers\RequestHiringHelper;
    
?>
<br />
<div class="container-fluid">
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Jobs List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list">
                                                    <thead>
                                                        <th class="text-center">S.No</th>
                                                        <th class="text-center">Job Title</th>
                                                        <th class="text-center">Job Type</th>
                                                        <th class="text-center">Designation</th>
                                                        <th class="text-center">Qualification</th>
                                                        <th class="text-center">Shift Type</th>
                                                        <th class="text-center">Active</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php $counter = 1;?>
                                                        <?php
                                                            $companyList = DB::select('select `name`,`dbName`,`id` from `company` where `status` = 1');
                                                            foreach($companyList as $row1){

                                                        ?>
                                                            <tr>
                                                                <td colspan="4"><?php echo $row1->name;?></td>
                                                                <td colspan="5"><?php echo $row1->dbName;?></td>
                                                            </tr>
                                                            <?php 
                                                                FinanceHelper::companyDatabaseConnection($row1->id);
                                                                $viewJobsList = DB::select('select * from `requesthiring`');
                                                                FinanceHelper::reconnectMasterDatabase();
                                                                $counter2 = 1;
                                                                foreach ($viewJobsList as $row2) {
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $counter2++;?></td>
                                                                    <td>
                                                                        <?php echo $row2->RequestHiringTitle;?>
                                                                    </td>
                                                                    <td>
                                                                    <?php echo RequestHiringHelper::getMasterTableValueById($row1->id,'job_type','job_type_name',$row2->job_type_id);?>
                                                                    </td>
                                                                    <td>
                                                                    <?php echo RequestHiringHelper::getMasterTableValueById($row1->id,'designation','designation_name',$row2->designation_id);?>
                                                                    </td>
                                                                    <td>
                                                                    <?php echo RequestHiringHelper::getMasterTableValueById($row1->id,'qualification','qualification_name',$row2->qualification_id);?>
                                                                    </td>
                                                                    <td>
                                                                    <?php echo RequestHiringHelper::getMasterTableValueById($row1->id,'shift_type','shift_type_name',$row2->shift_type_id);?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                    <?php 
                                                                        switch($row2->status)
                                                                        {
                                                                            case  "1":
                                                                            
                                                                            echo '<b>Active</b>';
                                                                            break;
                                                                            case  "2":
                                                                           echo '<b>InActive</b>';
                                                                            break;

                                                                        }
                                                                    ?></td>
                                                                    <td>
                                                                      <?php 
                                                                        switch($row2->RequestHiringStatus)
                                                                        {
                                                                            case  "1":
                                                                            echo '<span class="label label-warning">Pending</span>';
                                                                            break;
                                                                            case  "2":
                                                                            echo '<span class="label label-success">Approved</span>';
                                                                            break;
                                                                        }
                                                                    ?>  
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php
                                                                        switch($row2->RequestHiringStatus)
                                                                        {
                                                                            case  "1":?>
                                                                           <button title='Approve' class="delete-modal btn btn-xs btn-primary btn-xs" onclick="approveOneTableRecords('<?php echo $row1->id?>','<?php echo $row2->id ?>','requesthiring','RequestHiringStatus')"><span class="glyphicon glyphicon-font"></span></button>
                                                                          <?php  break;
                                                                      }?>
                                                                    
                                                                     <button title='View' onclick="showDetailModelTwoParamerter('RequestHiring/ViewandApplyDetail','<?php echo $row2->id;?>','View and Apply Job Detail','<?php echo $row1->id;?>')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></button>
                                                                      <?php if($row2->status == 1): ?>
                                                                     <button title='Edit' class="edit-modal btn btn-xs btn-info" onclick="showDetailModelTwoParamerter('RequestHiring/editRequestHiringForm','<?php echo $row2->id;?>','Request Hiring Edit Detail Form','<?php  echo $row1->id;?>')">
                    													<span class="glyphicon glyphicon-edit"></span>
                												 	 </button>
                                                                     
                                                                     <button  title='Delete' class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteRowCompanyHRRecords('<?php echo $row1->id?>','<?php echo $row2->id ?>','RequestHiring')">
                    													<span class="glyphicon glyphicon-trash"></span>
                													 </button>
                                                                    <?php else: ?>
                                                                     <button title='Repost' class="delete-modal btn btn-xs btn-primary btn-xs" onclick="repostOneTableRecords('<?php echo $row1->id?>','<?php echo $row2->id ?>','RequestHiring','status')">
                                                                        <span class="glyphicon glyphicon-repeat"></span>
                                                                     </button>
                                                                 <?php endif; ?>

                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                            ?>
                                                        <?php 
                                                            }
                                                        ?>
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
    </div>
</div>
@endsection