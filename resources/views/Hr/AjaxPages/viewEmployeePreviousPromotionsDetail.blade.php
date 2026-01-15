<?php use App\Helpers\HrHelper; ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
  <h3 style="font-weight: bold;text-decoration: underline">Previous Designation & Salary</h3>
</div>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
        <input type="hidden" name="previousSalary" id="previousSalary" value="{{ $salary }}">
        <thead>
            <th class="text-center">Designation</th>
            <th class="text-center">Salary</th>
            <th class="text-center">Date</th>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$designation_id)}}</td>
                <td class="text-right">{{ number_format($salary) }}</td>
                <td class="text-center">{{HrHelper::date_format($date)}}</td>
            </tr>
        </tbody>
    </table>
</div>