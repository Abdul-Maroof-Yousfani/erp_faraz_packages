<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


use App\Helpers\HrHelper;
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <?php echo Form::open(array('url' => '','id'=>''));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?=$m?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <label class="sf-label">Leaves Policy Name:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input disabled type="text" value="<?=$leavesPolicy->leaves_policy_name;?>" class="form-control">
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Policy Month & Year from:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input disabled value="<?=$leavesPolicy->policy_date_from?>" type="text" class="form-control">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Policy Month & Year till:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input disabled type="text" value="<?= $leavesPolicy->policy_date_till?>" class="form-control">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Full Day Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>

                                            <select disabled class="form-control requiredField" name="full_day_deduction_rate" required>
                                                <option value="">select</option>
                                                <option @if($leavesPolicy->fullday_deduction_rate == '1') {{ 'selected' }}@endif value="1">1 (Day)</option>
                                            <!-- <option @if($leavesPolicy->fullday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                                            <option @if($leavesPolicy->fullday_deduction_rate == '0.33333333333') {{ 'selected' }}@endif value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                                            <option @if($leavesPolicy->fullday_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>
                                                                       --></select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Half Day Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>

                                            <select disabled class="form-control requiredField" name="half_day_deduction_rate" required>
                                                <option value="">select</option>
                                                <option @if($leavesPolicy->halfday_deduction_rate == '0.50') {{ 'selected' }}@endif value="0.50">0.5 (Day)</option>
                                            <!--  <option @if($leavesPolicy->halfday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                                            <option @if($leavesPolicy->halfday_deduction_rate == '0.33333333333') {{ 'selected' }}@endif value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                                            <option @if($leavesPolicy->halfday_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>-->
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Per Hour Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select disabled class="form-control requiredField" name="per_hour_deduction_rate" required>
                                                <option value="">select</option>
                                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25">0.25 (Day)</option>
                                            <!--<option @if($leavesPolicy->per_hour_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.2">1/2&nbsp;&nbsp;(Equivalent to 2 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.3') {{ 'selected' }}@endif value="0.3">1/3&nbsp;&nbsp;(Equivalent to 3 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.4') {{ 'selected' }}@endif value="0.4">1/4&nbsp;&nbsp;(Equivalent to 4 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">1/5&nbsp;&nbsp;(Equivalent to 5 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.6') {{ 'selected' }}@endif value="0.6">1/6&nbsp;&nbsp;(Equivalent to 6 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.7') {{ 'selected' }}@endif value="0.7">1/7&nbsp;&nbsp;(Equivalent to 7 Hour)</option>
                                                                            <option @if($leavesPolicy->per_hour_deduction_rate == '0.8') {{ 'selected' }}@endif value="0.8">1/8&nbsp;&nbsp;(Equivalent to 8 Hour)</option>-->
                                            </select>
                                        </div>
                                    </div>
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                        <th>Leaves Type</th>
                                        <th>No. of Leaves</th>
                                        </thead>
                                        <tbody>
                                        @foreach($leavesData as $value)
                                            <?php echo $value->leave_type_id; ?>
                                            <tr>
                                                <td>{{ HrHelper::getMasterTableValueById(0,'leave_type','leave_type_name',$value->leave_type_id) }}</td>
                                                <td>{{ $value->no_of_leaves }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
