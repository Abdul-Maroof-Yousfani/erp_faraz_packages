<a class="list-group-item top-margin" onclick="viewUpcomingBirthdaysDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i>
	<b>Upcoming Birthdays Alert &nbsp;(<?=$upcoming_birthday_count[0]->upcoming_birthday_count?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewEmployeeCnicExpireDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>CNIC Expire Alert &nbsp;(<?=$cnic_expiry_date_count?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>


<a class="list-group-item top-margin" onclick="viewEmployeeOverAgeDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Overage Emp Alert &nbsp;(<?=$over_age_employee_count[0]->over_age_employee_count?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewNonVerifiedNadraEmployeeDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Non-Verified Nadra Emp Alerts&nbsp;(<?=$non_verified_nadra_count?>)</b>
	<span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewNonVerifiedPoliceEmployeeDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Non-Verified Police Emp Alerts&nbsp;(<?=$non_verified_police_count?>)</b>
	<span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewEmployeeMissingImageDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Emp Missing Image Alerts &nbsp;(<?=$employee_missing_images?>)</b>
	<span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewEmployeeWarningLetterDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Warning Letters Alert &nbsp;(<?=$warning_letter?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>
<a class="list-group-item top-margin" onclick="viewDemiseEmployeeDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Demise Emp Alert&nbsp;(<?=$demiseEmployees?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>

<a class="list-group-item top-margin" onclick="viewEmployeeProbationPeriodOverDetail('<?=Input::get('m')?>')">
	<i class="fa fa-tasks fa-fw" style="color:red;"></i> <b>Emp Probation Period Over Alert&nbsp;(<?=$employeeProbationPeriodOverDetail[0]->totalOverProbationEmp?>)</b>
    <span class="pull-right text-muted small ">
		<em>
			<img class="text-right" height="20" width="20" src="<?= url('assets/img/alert.gif') ?>">
		</em>
	</span>
</a>


