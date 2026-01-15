<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


use App\Helpers\SalesHelper;

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
                            <h3 style="text-align: center;">Conversion Quantity</h3>
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
                            <td>Start Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->start_date);?></td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat($data->end_date);?></td>
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
            @if($conversion==0)
            <?php echo Form::open(array('url' => 'prad/insert_conversion'));?>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <input type="hidden" name="production_plan_id" value="{{$data->id}}"/>
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">Product Name</th>
                            <th class="text-center">Route</th>
                            <th class="text-center">Planned Quantity</th>
                            <th class="text-center">Spoilage QTY</th>
                            <th class="text-center">Produce QTY</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        foreach ($master_data as $key => $row):


                                if (!empty($spoilage)):
                                    $spoilage_val=$spoilage[$key];
                                else:
                                    $spoilage_val=0;
                                  endif;
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td  class="text-center bold">
                                <?php echo CommonHelper::get_item_name($row->finish_goods_id);?>
                            </td>

                            <td class="text-center bold"><?php echo  ProductionHelper::get_route_code($row->route) ?></td>
                            <td class="text-center bold"><?php echo $row->planned_qty?></td>
                            <td><input  onkeyup="calc('{{$counter}}')" value="{{$spoilage_val}}" onblur="calc('{{$row->planned_qty}}')" type="text" name="spoilage[]" id="spoilage{{$counter}}" class="form-control required_sam"></td>
                            <td><input readonly  type="text" name="produce_qty[]" id="produce_qty{{$counter}}" class="form-control" value="{{$row->planned_qty-$spoilage_val}}"></td>
                            <input type="hidden" value="{{$row->id}}" name="production_plan_data_id[]" id="production_plan_data_id{{$counter}}" />

                            <input type="hidden" name="" value="{{$row->planned_qty}}" id="planned_qty{{$counter}}"/>
                        </tr>

                        <tr class="InnerDetail" style="; background: radial-gradient(black, transparent)">
                            <td colspan="7">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Requested QTY</th>
                                        <th class="text-center">Net Issued QTY</th>
                                        <th class="text-center">Wastage KG per Piece</th>
                                        <th class="text-center">Chip</th>
                                        <th class="text-center">Turning Scrap</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @include('Production.bom_data_conversion')

                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <?php
                        endforeach
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($conversion==0)
            <div class="text-center">
                <input type="submit" id="subm" class="btn btn-success"/>
            </div>

            <?php echo Form::close();?>
                @endif

        </div>
    </div>
</div>
<script>
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


    $("form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var validate=validate_sam();
        if (validate!=false)
        {

            $('#subm').prop("disabled", true);
            var form = $(this);
            var url = form.attr('action');
            var company='{{Session::get('run_company')}}';
            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(response)
                {
             
                    $('#printMachineDetail').html(response);
                    return false;
                    var url='{{url('production/conversion_cost?id=')}}'+response;

                    $("#data").removeClass("loader");
                    $('#data').html(response);
                    $('.subHeadingLabelClass').text('Plan Detail (Step 2)');
                    $('#so_no').prop("disabled", true);
                    $('#subm').prop("disabled", true);
                    window.location.href=url;
                  //  $("#showDetailModelOneParamerter").modal('hide');

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


