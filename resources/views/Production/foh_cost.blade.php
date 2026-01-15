<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab  fade routeTab<?php echo $counter?>  in active" id="foh_page<?php echo $counter?>">
   <tbody>

   <?php $foh_data=ProductionHelper::get_foh_amount(); ?>
   <tr style="font-weight: bold" class="text-center">
       <td colspan="2">FOH</td>
       <td class="foh" id="foh{{$foh_counter}}" colspan="">{{$foh_data->amount}}</td>

       <input type="hidden" name="foh{{$keys}}[]" value="{{$foh_data->amount}}"/>

   </tr>



   <tr style="font-weight: bold" class="text-center">
       <td colspan="2">Yearly Wages</td>
       <?php
       $labour_data=ProductionHelper::get_labour_rate_rows();
       $yearly_wages=$labour_data->wages;


       ?>
       <td id="yearly_wages_for_foh{{$foh_counter}}" class="">{{number_format($yearly_wages,2)}}


       </td>
       <input type="hidden" name="yearly_wages{{$keys}}[]" value="{{$yearly_wages}}"/>
   </tr>



   <tr style="font-weight: bold" class="text-center">
       <td colspan="2">Direct Labour Cost</td>
       <td id="direct_labour_cost_for_machine_foh{{$foh_counter}}" class=""><?php  ?></td>
       <input type="hidden" id="direct_labour_for_machine_foh_hidden{{$foh_counter}}" name="direct_labour_for_machine_foh_hidden{{$keys}}[]" value=""/>
   </tr>

   <tr style="font-weight: bold" class="text-center">
       <td colspan="2">FOH Applied Machine Cost</td>
       <td id="foh_applied_amount_foh{{$foh_counter}}" class=""><?php  ?></td>

       <input type="hidden" id="foh_applied_amount_foh_hidden{{$foh_counter}}" name="foh_applied_amount_foh_hidden{{$keys}}[]" value=""/>
   </tr>
   <?php  $foh_counter++?>
    </tbody>
</table>
<script>
    $( document ).ready(function() {

        $('.foh').each(function(i, obj) {
            var value= $(this).val();
            var id=  $(this).attr("id");
            var number=id.replace('foh','');
            var foh_cost=parseFloat($('#foh'+number).html());



            // start


            // for direct labour

            var direct_labour_cost=  $('#direct_lab_cost'+number).html();

            $('#direct_labour_cost_for_machine_foh'+number).html(direct_labour_cost);
            $('#direct_labour_for_machine_foh_hidden'+number).val(direct_labour_cost);
            //end

            // yearly wages
            var yearly_wages=$('#yearly_wages_for_foh'+number).text().replace(/,/g, '');
            yearly_wages=parseFloat(yearly_wages);
            //


            var direct_labour_for_machine=parseFloat($('#direct_labour_cost_for_machine_foh'+number).html());



            var total=((foh_cost / yearly_wages ) * direct_labour_for_machine).toFixed(2);
            $('#foh_applied_amount_foh'+number).html('(FOH / Yearly Wages) * Direct Labour Cost ='+total);
            $('#foh_applied_amount_foh_hidden'+number).val(total);

            // end

            $('#foh_cost'+number).html(total);
            $('#db_foh'+number).val(total);


            // total foh


// total foh

            var direct_material=parseFloat($('#d_cost_'+number).html());
            var indirect_material=parseFloat($('#ind_cost_'+number).html());
            var labour_cost=parseFloat($('#direct_lab_cost'+number).html());
            var die_mould=parseFloat($('#die_mould_'+number).html());
            var machine=parseFloat($('#machine_'+number).html());
            var foh=$('#foh_cost'+number).text().replace(/,/g, '');
            foh=parseFloat(foh);
            console.log(foh);

            var total=(direct_material + indirect_material + labour_cost + die_mould + machine + foh).toFixed(2);
            $('#total_foh'+number).html(total);



            //endf

            //endf

        });
    })
</script>

