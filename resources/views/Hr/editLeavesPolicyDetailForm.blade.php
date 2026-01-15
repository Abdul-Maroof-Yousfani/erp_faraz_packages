<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Helpers\HrHelper;
?>
<script src="{{ URL::asset('assets/js/popper.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/css/summernote-bs4.css') }}">
<script type="text/javascript" src="{{ URL::asset('assets/js/summernote-bs4.js') }}"></script>

<script>

    $(function() {
        $('.summernote').summernote({
            height: 200
        });

    });
</script>

<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editLeavesPolicyDetail','id'=>''));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <input type="hidden" name="record_id" value="<?=Input::get('id')?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Leaves Policy Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input name="leaves_policy_name" type="text" value="<?=$leavesPolicy->leaves_policy_name;?>" class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date from:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateFrom" value="<?=$leavesPolicy->policy_date_from?>"  class="form-control" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date till:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateTill" value="<?= $leavesPolicy->policy_date_till?>" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Full Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="full_day_deduction_rate" class="form-control requiredField" name="full_day_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->fullday_deduction_rate == '1') {{ 'selected' }}@endif value="1">1 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Half Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="half_day_deduction_rate" class="form-control requiredField" name="half_day_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->halfday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">0.5 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Per Hour Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="per_hour_deduction_rate" class="form-control requiredField" name="per_hour_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25"> 0.25 (Days)</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered sf-table-list">
                        <thead>
                        <th>Leaves Type</th>
                        <th>No. of Leaves</th>
                        <th><input type="button" class="btn-xs btn-primary addMorePolicySection" value="Add More" /></th>
                        </thead>
                        <tbody id="append_area">
                        <?php $count=0; ?>
                        @foreach($leavesData as $value)
                            <?php $count++; ?>
                            <tr class="get_rows" id=remove_area_<?=$count?>>
                                <td id="get_data" >
                                    <select name="leaves_type_id[]" class="form-control requiredField" required>
                                        <option value="">Select</option>
                                        @foreach($leavesType as $value2)
                                            <option @if($value->leave_type_id ==$value2->id) {{ 'selected' }}@endif value="{{ $value2->id }}">{{ HrHelper::getMasterTableValueById(0,'leave_type','leave_type_name',$value2->id) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="no_of_leaves[]" id="no_of_leaves[]" value="{{ $value->no_of_leaves }}" class="form-control requiredField" required />
                                </td>
                                <td><button onclick="removeEmployeeSection('<?= $count ?>')" type="button" class="btn btn-xs btn-danger">Remove</button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <label class="sf-label">Terms & Condition</label>
                    <textarea name="terms_conditions" class="summernote" id="contents" class="form-control"><?= $leavesPolicy->terms_conditions; ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>
<script>
    $(document).ready(function() {

        var m = "<?= $_GET["m"]; ?>";

        $('.addMorePolicySection').click(function (e){
            var form_rows_count = $(".get_rows").length;
            var total_values = '<?= count($leavesData) ?>';
            if(total_values == form_rows_count)
            {
                return false;
            }
            form_rows_count++;

            var data = $('#get_data').html();
            $('#append_area').append('<tr class="get_rows" id=remove_area_'+form_rows_count+'><td>'+data+'</td><td><input class="form-control" type="number" name="no_of_leaves[]" id="no_of_leaves[]"></td><td><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button></td></tr>');

            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

        });

    });
    function removeEmployeeSection(id){

        $("#remove_area_"+id).remove();

    }
</script>