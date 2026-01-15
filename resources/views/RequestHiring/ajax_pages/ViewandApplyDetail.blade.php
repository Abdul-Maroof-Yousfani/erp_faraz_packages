<?php 
use App\Helpers\RequestHiringHelper;
//print_r($requestHiring);




?>
							<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                                <?php
                                switch($requestHiring->RequestHiringStatus)
                                {
                                case  "1":?>
								<button title='Approve' class="delete-modal btn btn-xs btn-primary btn-xs" onclick="approveOneTableRecords('<?php echo $m;?>','<?php echo $requestHiring->id ?>','requesthiring','RequestHiringStatus')"><span class="glyphicon glyphicon-font"></span></button>
                                <?php  break;
                                }?>

                                <?php if($requestHiring->status == 1): ?>
								<button title='Edit' class="edit-modal btn btn-xs btn-info" onclick="showDetailModelTwoParamerter('RequestHiring/editRequestHiringForm','<?php echo $requestHiring->id;?>','Request Hiring Edit Detail Form','<?php  echo $m;?>')">
									<span class="glyphicon glyphicon-edit"></span>
								</button>

								<button  title='Delete' class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteRowCompanyHRRecords('<?php echo $m;?>','<?php echo $requestHiring->id ?>','RequestHiring')">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
                                <?php else: ?>
								<button title='Repost' class="delete-modal btn btn-xs btn-primary btn-xs" onclick="repostOneTableRecords('<?php echo $m;?>','<?php echo $requestHiring->id ?>','RequestHiring','status')">
									<span class="glyphicon glyphicon-repeat"></span>
								</button>
                                <?php endif; ?>
							</div>
							
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="table-responsive">
												<table class="table table-bordered sf-table-list">
   													<thead>
														<th class="text-center col-sm-1">Request Hiring .No</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->RequestHiringNo;?></td>   
                                        			</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Title</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->RequestHiringTitle?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1"> Department</th>   
														<td class="text-center col-sm-1"><?php 
														 $dept_id = RequestHiringHelper::getMasterTableValueById($m,'sub_department','department_id',$requestHiring->sub_department_id);
														 echo  RequestHiringHelper::getMasterTableValueById($m,'department','department_name',$dept_id);
														 ?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1"> Subdepartment</th>   
														<td class="text-center col-sm-1">
														<?php 
														 echo RequestHiringHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$requestHiring->sub_department_id);
														
														?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Type</th>   
														<td class="text-center col-sm-1"><?php 
														 echo RequestHiringHelper::getMasterTableValueById($m,'job_type','job_type_name',$requestHiring->job_type_id);
														
														 ?></td>   
                                        			</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Designation</th>   
														<td class="text-center col-sm-1"><?php 
														 echo RequestHiringHelper::getMasterTableValueById($m,'designation','designation_name',$requestHiring->designation_id);
													
														?>
														</td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Qualification</th>   
														<td class="text-center col-sm-1"><?php 
														
														echo RequestHiringHelper::getMasterTableValueById($m,'qualification','qualification_name',$requestHiring->qualification_id);
														
														?></td>   
                                        			</thead>
													
													<thead>
														<th class="text-center col-sm-1">Request Hiring Shift </th>   
														<td class="text-center col-sm-1"><?php 
														
														echo RequestHiringHelper::getMasterTableValueById($m,'shift_type','shift_type_name',$requestHiring->shift_type_id);
													
														?></td>   
                                        			</thead>
													
													
												</table>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										
											<div class="table-responsive">
												<table class="table table-bordered sf-table-list">
   													<thead>
														<th class="text-center col-sm-1">Request Hiring Gender</th>   
														<td class="text-center col-sm-1"><?php 
															if($requestHiring->RequestHiringGender == '1'):
															echo "Male";
															else:
																echo "Female";
															endif;
														?></td>   
                                        			</thead>
													<thead>
														<th class="text-center col-sm-1">Starting Salary</th>   
														<td class="text-center col-sm-1"><?= $requestHiring->RequestHiringSalaryStart?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1"> End Salary</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->RequestHiringSalaryEnd?></td>   
                                        			</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Age</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->RequestHiringAge?></td>   
													</thead>
												
													<thead>
														<th class="text-center col-sm-1">Created On</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->date?></td>   
                                        			</thead>
													<thead>
														<th class="text-center col-sm-1">Created At</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->time?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1">Created By</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->username?></td>   
													</thead>
													<thead>
														<th class="text-center col-sm-1">Request Hiring Detail</th>   
														<td class="text-center col-sm-1"><?=$requestHiring->RequestHiringDescription?></td>   
													</thead>
													
												</table>
											</div>
										</div>
									</div>