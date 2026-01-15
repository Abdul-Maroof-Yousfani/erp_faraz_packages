<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


use App\Helpers\SalesHelper;
global $direct_labour;
$so_no='';
$customer='';
if($data->sales_order_id!=''):
    $so_data=SalesHelper::get_sales_tax_by_sales_order_id($data->sales_order_id);
    $so_no=$so_data->so_no;
    $customer=SalesHelper::get_customer_name($so_data->buyers_id);
endif;


$conversion=  ProductionHelper::check_conversion($data->id);

$spoilage=[];
if ($conversion>0):
    $conversion_data=  ProductionHelper::get_conversion_id($data->id);
    $conversion_id=$conversion_data->id;

    $conversion_data= ProductionHelper::get_conversion_data($conversion_id);

    foreach($conversion_data as $conver_data)
        $spoilage[]=$conver_data->spoilage;
endif;
?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div style="display: none" id="main" class="alert alert-warning">
    <strong>Warning!</strong> Without Issuence Materials, Conversion Not Allowed
</div>
<div class="row main" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Create Conversion</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Order Name</td>
                            <td class="text-center"><?php echo strtoupper($data->order_no);?></td>
                        </tr>
                        <tr>
                            <td>Order Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->order_date);?></td>
                        </tr>

                        <tr>
                            <td>Due Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->due_date);?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Type</td>
                            <td class="text-center">@if($data->type==1) Standard @else Make To Order @endif</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="text-center">@if($data->status==1) Planned @else Release @endif</td>
                        </tr>

                        <tr>
                            <td>Sales Order</td>
                            <td class="text-center"><?php echo strtoupper($so_no);?></td>
                        </tr>

                        <tr>
                            <td>Customer</td>
                            <td class="text-center"><?php strtoupper($customer) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>

            <?php echo Form::open(array('url' => 'production/cost_insert','id'=>'cost','class'=>'stop'));?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <input type="hidden" name="production_plan_id" value="{{$data->id}}"/>
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product</th>
                            <th class="text-center ">Direct Material</th>
                            <th class="text-center">Indirect Material</th>
                            <th class="text-center">Direct Labour</th>
                            <th class="text-center">Die & Mould</th>
                            <th class="text-center">Machine Cost</th>
                            <th class="text-center">FOH</th>
                            <th class="text-center">Total FOH</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                       $direct_material_counter=1;
                       $indirect_material_counter=1;
                       $direct_labour_counter=1;
                       $die_mould_counter=1;
                       $machine_counter=1;
                      $foh_counter=1;
                        foreach ($master_data as $keys => $row):


                        if (!empty($spoilage)):
                            $spoilage_val=$spoilage[$keys];
                        else:
                            $spoilage_val=0;
                        endif;
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td  class="text-center bold">
                                <?php echo CommonHelper::get_item_name($row->finish_goods_id);?>
                            </td>

                            <td class="text-center bold active">
                                {{--<li class=""><a data-toggle="tab" href="#home">Direct Material</a></li>--}}
                                {{--<li><a data-toggle="tab" href="#menu1">Indrect Material</a></li>--}}
                                <a id="d_cost_{{$direct_material_counter}}" data-toggle="tab" href="#direct_material<?php echo $counter?>" onclick="disConectTab('direct','<?php echo $counter?>')">
                                    Direct Mat

                                </a>
                                <input type="hidden" id="db_direct_m_cost{{$direct_material_counter}}" name="db_direct_m_cost[]" />
                            </td>
                            <td class="text-center bold">
                                <a id="ind_cost_{{$indirect_material_counter}}" data-toggle="tab" href="#indirect_material<?php echo $counter?>" onclick="disConectTab('indirect','<?php echo $counter?>')">
                                  Indirect Mat

                                </a>
                                <input type="hidden" id="db_in_direct_m_cost{{$indirect_material_counter}}" name="db_in_direct_m_cost[]" />
                            </td>
                            <td class="text-center bold">
                                <a id="direct_lab_cost{{$direct_labour_counter}}" data-toggle="tab" href="#direct_labour<?php echo $counter?>" onclick="disConectTab('dl','<?php echo $counter?>')">
                                    Direct Labour

                                </a>
                                <input type="hidden" id="db_direct_labour{{$direct_labour_counter}}" name="db_direct_labour[]" />
                            </td>
                            <td class="text-center bold">
                                <a id="die_mould_{{$die_mould_counter}}" data-toggle="tab" href="#die_mould<?php echo $counter?>" onclick="disConectTab('die_mould','<?php echo $counter?>')">
                                   Die & Mould

                                </a>
                                <input type="hidden" id="db_die_mould{{$die_mould_counter}}" name="db_die_mould[]" />
                            </td>

                            <td class="text-center bold">
                                <a id="machine_{{$machine_counter}}" data-toggle="tab" href="#machine<?php echo $counter?>" onclick="disConectTab('machine','<?php echo $counter?>')">
                                   Machine Cost

                                </a>
                                <input type="hidden" id="db_machine{{$machine_counter}}" name="db_machine[]" />
                            </td>

                            <?php $foh_data=ProductionHelper::get_foh_amount(); ?>
                            <td class="text-center bold"><a onclick="disConectTab('foh','<?php echo $counter?>')" id="foh_cost{{$foh_counter}}" target=""  data-toggle="tab" href="{{url('production/factory_overhead_list?type=0')}}">{{number_format($foh_data->amount,2)}}</a>

                                <input type="hidden" id="db_foh{{$foh_counter}}" name="db_foh[]" />
                            </td>

                            <td class="text-center bold"><a id="total_foh{{$machine_counter}}" target=""  data-toggle="tab" href="#"></a>
                                <input type="hidden" id="total_foh{{$counter}}" name="total_foh[]" />

                            </td>
                            <input type="hidden" value="{{$row->id}}" name="production_plan_data_id[]" id="production_plan_data_id{{$counter}}" />

                            <input type="hidden" name="" value="{{$row->planned_qty}}" id="planned_qty{{$counter}}"/>
                        </tr>

                        <tr class="">
                            <td colspan="2"></td>
                            <td colspan="4">
                                <div class=""></div>

                                @include('Production.direct_material_cost')
                                @include('Production.indirect_material_cost')
                                @include('Production.direct_labour_cost')
                                @include('Production.die_mould_cost')
                                @include('Production.machine_cost')
                                @include('Production.foh_cost')


                            </td>
                        </tr>

                        <?php
                        $direct_material_counter++;
                        $indirect_material_counter++;
                        $direct_labour_counter++;
                        $die_mould_counter++;
                        $machine_counter++;
                        $foh_counter++;
                        endforeach
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <input class="form-control" type="submit" value="submit" />
            <?php echo Form::close();?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.allTab').css('display','none');
     $('form').submit();
    });

    function disConectTab(TabId,Counter)
    {
        alert(Counter);
        if(TabId == 'direct')
        {
            $('#direct'+Counter).fadeIn();
            $('#indirect'+Counter).css('display','none');
            $('#labour'+Counter).css('display','none');
            $('#die_mould'+Counter).css('display','none');
            $('#foh_page'+Counter).css('display','none');
        }
        else if(TabId == 'indirect')
        {
            $('#direct'+Counter).css('display','none');
            $('#labour_id'+Counter).css('display','none');
            $('#die_mouldd'+Counter).css('display','none');
            $('#machine'+Counter).css('display','none');
            $('#foh_page'+Counter).css('display','none');
            $('#indirect'+Counter).fadeIn();
        }

        else if(TabId == 'dl')
        {
            $('#direct'+Counter).css('display','none');
            $('#indirect'+Counter).css('display','none');
            $('#die_mouldd'+Counter).css('display','none');
            $('#machine'+Counter).css('display','none');
            $('#foh_page'+Counter).css('display','none');
            $('#labour_id'+Counter).fadeIn();
        }


        else if(TabId == 'die_mould')
        {
            $('#direct'+Counter).css('display','none');
            $('#indirect'+Counter).css('display','none');
            $('#labour_id'+Counter).css('display','none');
            $('#machine'+Counter).css('display','none');
            $('#foh_page'+Counter).css('display','none');
            $('#die_mouldd'+Counter).fadeIn();
        }
        else if(TabId == 'machine')
        {
            $('#direct'+Counter).css('display','none');
            $('#indirect'+Counter).css('display','none');
            $('#labour_id'+Counter).css('display','none');
            $('#die_mouldd'+Counter).css('display','none');
            $('#foh_page'+Counter).css('display','none');
          //  $('#machine'+Counter).fadeIn();
            $('#machine'+Counter).css('display','block');
        }

        else if(TabId == 'foh')
        {

            $('#direct'+Counter).css('display','none');
            $('#indirect'+Counter).css('display','none');
            $('#labour_id'+Counter).css('display','none');
            $('#die_mouldd'+Counter).css('display','none');
            $('#machine'+Counter).css('display','none');
       //     $('#foh_page'+Counter).fadeIn();

            $('#foh_page'+Counter).css('display','block');
        }
        else{}
    }
    function calc(number)
    {
        var planned_qty=parseFloat($('#planned_qty'+number).val());
        var spoilage=parseFloat($('#spoilage'+number).val());

        if (spoilage >planned_qty)
        {
            spoilage=0;
            $('#spoilage').val(spoilage);
        }
        if (isNaN(spoilage))
        {
            spoilage=0;
        }
        var total=planned_qty-spoilage;
        $('#produce_qty'+number).val(total);
    }


    $("#xQform").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var validate=validate_sam();
        if (validate!=false)
        {

            $('#subm').prop("disabled", true);
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(response)
                {

                    $("#data").removeClass("loader");
                    $('#data').html(response);
                    $('.subHeadingLabelClass').text('Plan Detail (Step 2)');
                    $('#so_no').prop("disabled", true);
                    $('#subm').prop("disabled", true);
                    $("#showDetailModelOneParamerter").modal('hide');
                },
                error: function(data, errorThrown)
                {
                    //   $('.hidee').prop('disabled', false);



                }
            });

        }
        else
        {
            e.preventDefault();
        }


    });
</script>


