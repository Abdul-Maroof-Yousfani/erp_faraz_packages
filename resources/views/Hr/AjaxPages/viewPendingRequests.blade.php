<a class="list-group-item" href="{{ url('hr/viewLoanRequestList?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>Loan Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary loans" style="font-size: 16px;" ><?=$loan?></span>
    </div>
</a>

<a class="list-group-item top-margin" href="{{ url('hr/viewAdvanceSalaryList?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>Advance Salary Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary advance_salary" style="font-size: 16px;"><?=$advance_salary?></span>
    </div>
</a>

<a class="list-group-item top-margin" href="{{ url('hr/viewLeaveApplicationRequestList?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>Leave Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary" style="font-size: 16px"><?=$leaves?></span>
    </div>
</a>

<a class="list-group-item top-margin" href="{{ url('hr/viewEmployeePromotions?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>Promotion Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary promotions" style="font-size: 16px"><?=$pomotion?></span>
    </div>
</a>

<a class="list-group-item top-margin" href="{{ url('hr/viewEmployeeExitClearanceList?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>Exit Clearance Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary exit_clearance" style="font-size: 16px"><?=$exit_clearance?></span>
    </div>
</a>

<a class="list-group-item top-margin" href="{{ url('hr/viewEmployeeIdCardRequestList?m='.Input::get('m')) }}" target="_blank">
    <i class="fa fa-tasks fa-fw" style="color:rgb(240,173,78)"></i>
    <b>ID Card Requests</b>
    <div class="pull-right">
        <span class="badge badge-pill badge-secondary id_card" style="font-size: 16px"><?=$id_card?></span>
    </div>
</a>