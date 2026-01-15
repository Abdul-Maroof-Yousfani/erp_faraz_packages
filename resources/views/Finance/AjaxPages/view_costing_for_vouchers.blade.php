<?php

use App\Helpers\CommonHelper;
$costing_counter=1; ?>
@foreach ($costing_data as $row2)
    <?php $dept_total=0;
    $cost_total=0;
    ?>

    <div style="display: none" id="costing{{$costing_counter}}" class="row costing">
        <p style="color: #e2a0a0;text-align: center" id="paragraph{{$costing_counter}}"><?php echo 'This Allocation Against '.CommonHelper::get_account_name($row2->acc_id); ?> </p>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">

            <table  id="cost_center_table" class="table table-bordered">

                <tbody class="cost_center_addrows" id="">
                <input type="hidden" name="" class="form-control requiredField" id="demandDataSection_" value="1" />

                @foreach(CommonHelper::department_allocation_data($row2->id,$type) as $row1)

                    <tr>
                        <td>
                            {{CommonHelper::get_dept_name(ucfirst($row1->dept_id))}}
                        </td>
                        <td>
                            {{number_format($row1->percent)}}
                        </td>
                        <td class="text-right">

                            {{number_format($row1->amount,2)}}
                            <?php $dept_total+=$row1->amount ?>
                        </td><!-->
                    </tr>
                @endforeach

                </tbody>
                <?php   $check= CommonHelper::department_allocation_data($row2->id,$type); ?>
                @if (!empty($check))
                    <tr>
                        <td colspan="1">Total</td>
                        <td   style="text-align: right" id="cost_center_dept_amount"></td>
                        <td style="background-color: #f3f3b9;" class="text-right" colspan="1">{{number_format($dept_total,2)}}</td>
                    </tr>
                @endif
            </table>

        </div>


        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">

            <table  id="cost_center_table" class="table table-bordered">

                <tbody class="cost_center_addrows" id="">
                <input type="hidden" name="" class="form-control requiredField" id="demandDataSection_" value="1" />

                @foreach(CommonHelper::cost_center_allocation_data($row2->id,$type) as $row1)
                    <tr>
                        <td>
                            {{CommonHelper::get_cost_name($row1->dept_id)}}
                        </td>
                        <td>
                            {{number_format($row1->percent)}}
                        </td>
                        <td class="text-right">

                            {{number_format($row1->amount,2)}}
                            <?php $cost_total+=$row1->amount ?>
                        </td><!-->
                    </tr>
                @endforeach

                </tbody>
                <?php $check= CommonHelper::cost_center_allocation_data($row2->id,$type); ?>
                @if (!empty($check))
                    <tr>
                        <td colspan="1">Total</td>
                        <td   style="text-align: right" id="cost_center_dept_amount">0</td>
                        <td style="background-color: #f3f3b9;" class="text-right" colspan="1">{{number_format($cost_total,2)}}</td>
                    </tr>
                @endif
            </table>

        </div>
    </div>

@endforeach
<script>
    function show_costing()
    {

        if($("#costing").prop('checked') == true)
        {
            $('.costing').fadeIn(500);
        }
        else
        {
            $('.costing').fadeOut(500);
        }
    }
</script>