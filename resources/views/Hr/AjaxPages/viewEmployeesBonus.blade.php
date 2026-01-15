<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Bonus;
use App\Models\BonusIssue;
$counter = 1;
$data1 ='';

?>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list table-hover table-striped">
                        <thead>
                        <th class="text-center col-sm-1">S.No</th>
                        <th class="text-center">Emp Code</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Emp Salary</th>
                        <th class="text-center">Bonus</th>
                        <th class="text-center">Bonus Month & Year</th>
                        <th class="text-center hidden-print"><input id="check_all" type="checkbox"></th>
                        </thead>
                        <tbody>
                        @foreach($employees as $key => $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $get_bonus_data = BonusIssue::where([['status','=',1],['bonus_id','=',Input::get('bonus_id')],['emp_code','=',$value->emp_code],['bonus_month','=',$month_year[1]],['bonus_year','=',$month_year[0]]]);
                            CommonHelper::reconnectMasterDatabase();
                            ?>

                            <tr id="bonusId{{ $value->id }}">
                                <td class="text-center">{{ $counter++ }}</td>
                                <td class="text-center">{{ $value->emp_code }}</td>
                                <td class="text-center">{{ $value->emp_name }}</td>
                                <td class="text-right">{{ number_format($value->emp_salary,0) }}</td>
                                <td class="text-right">{{ number_format((($get_percent->percent_of_salary/100)*$value->emp_salary),0) }}</td>
                                <td class="text-center">{{ Input::get('bonus_month_year') }}</td>
                                <td class="text-center hidden-print">
                                    @if($get_bonus_data->count() > 0)
                                        <span class="label label-success">Assigned</span>&nbsp;&nbsp;
                                        <span onclick="removeBonus('{{  $get_bonus_data->value('id') }}')" style="cursor: pointer;" class="label label-danger">Remove</span>
                                    @else
                                        <input type="checkbox" class="ads_Checkbox " name="check_list[]" value='<?=$value->emp_code.'_'.(($get_percent->percent_of_salary/100)*$value->emp_salary)?>'>
                                    @endif
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
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
    <button class="btn btn-success" type="submit">Submit</button>
</div>

<script>
    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true)
            {
                $(".ads_Checkbox").prop("checked",true);
            }
            else
            {
                $(".ads_Checkbox").prop("checked",false);
            }


        });
    });

    function removeBonus(id){
        var id;
        var m = '<?=Input::get('m')?>';
        var _token = $("meta[name=csrf-token]").attr("content");

        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url : ""+baseUrl+"/cdOne/deleteEmployeeBonus",
                type: "GET",
                data: {id:id, m:m, _token:_token},
                success: function (data) {
                    $("#bonusId"+id).fadeOut();
                },
                error: function () {
                    console.log("error");
                }
            });
        }
    }


</script>

