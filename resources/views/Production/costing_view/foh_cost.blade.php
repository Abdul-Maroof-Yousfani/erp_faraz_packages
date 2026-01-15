<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab  fade routeTab<?php ?>  in active" id="foh_page<?php ?>">
    <tbody>

    <?php
    $data=  DB::Connection('mysql2')->table('foh_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->first();
    $counter=1;
    $total=0;
    ?>
    <tr style="font-weight: bold" class="text-center">
        <td colspan="2">FOH</td>
        <td id="yearly_wages_for_foh1" class="">{{number_format($data->foh,2)}}</td>

    </tr>

    <tr style="font-weight: bold" class="text-center">
        <td colspan="2">Yearly Wages</td>
        <td id="yearly_wages_for_foh1" class="">{{number_format($data->yearly_wages,2)}}</td>

    </tr>


    <tr style="font-weight: bold" class="text-center">
        <td colspan="2">Direct Labour Cost	</td>
        <td id="yearly_wages_for_foh1" class="">{{number_format($data->direct_labour_for_machine_foh_hidden,2)}}</td>

    </tr>


    <tr style="font-weight: bold" class="text-center">
        <td colspan="2">FOH Applied Machine Cost	(FOH / Yearly Wages) * Direct Labour Cost	</td>
        <td id="yearly_wages_for_foh1" class="">{{number_format($data->foh_applied_amount_foh_hidden,2)}}</td>

    </tr>
    </tbody>
</table>


