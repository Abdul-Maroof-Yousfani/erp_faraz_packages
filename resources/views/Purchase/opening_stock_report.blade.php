<?php
use App\Helpers\CommonHelper;
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="row">

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label for="">Client Name</label>
            <select name="CategoryId" id="CategoryId" class="form-control select2">
                <option value="">All</option>
                <?php foreach($Category as $Fil):?>
                <option value="<?php echo $Fil->id?>"><?php echo $Fil->main_ic?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <label for="">Region</label>
            <select name="RegionId" id="RegionId" class="form-control select2">
                <option value="">All</option>
                <?php foreach($Region as $Fil):?>
                <option value="<?php echo $Fil->id?>"><?php echo $Fil->region_name?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="filterByCategoryAndRegion();" style="margin-top: 32px;" />
        </div>
    </div>
<table id="buildyourform" class="table table-bordered">
    <thead>
    <tr>
        <th>Category</a>

        </th>
        <th class="text-center">Item </th>
        <th class="text-center">Region</th>
        <th class="text-center" >Qty</th>
        <th class="text-center">Amount</th>
    </tr>
    </thead>
    <tbody id="data">
    <?php $data= DB::Connection('mysql2')->select('select * from stock where status=1 and opening=1');


    ?>

    <?php
    $total_amount=0;
            $total_qty=0;
    foreach ($data as $row):?>

    <?php

      if ($row->qty>0 || $row->amount>0):
    $sub_item_id=$row->sub_item_id;
    $sub_item_data=CommonHelper::get_subitem_detail($sub_item_id);
    $sub_item_data=explode(',',$sub_item_data);
    $sub_item_name=$sub_item_data[4];
    $category_id=$sub_item_data[5];
    ?>
    <tr>
        <td><?php

          echo   $catgory_data=CommonHelper::get_category_name($category_id);


            ?></td>
        <td title="<?php echo $sub_item_id ?>"><?php echo $sub_item_name ?></td>
        <td><?php    $rgion_data= CommonHelper::get_rgion_name_by_id($row->region_id);
              echo  $rgion_data->region_name;
            ?></td>
        <td><?php echo $row->qty ?></td>
        <th class="text-center" ><?php echo number_format($row->amount,2) ;
            $total_amount+=$row->amount;
            $total_qty+=$row->qty;
            ?></th>
    </tr>
    <?php endif; endforeach ?>
    <tr style="background-color: darkgrey">
        <td colspan="3">Total</td>
        <td style="font-size: large;font-weight: bolder" colspan="1">{{number_format($total_qty,2)}}</td>
        <td style="font-size: large;font-weight: bolder" colspan="1">{{number_format($total_amount,2)}}</td>
    </tr>
    </tbody>
</table>
    <script !src="">
        $(document).ready(function(){
            $('.select2').select2();
        });


        function filterByCategoryAndRegion()
        {
            var CategoryId = $('#CategoryId').val();
            var RegionId = $('#RegionId').val();

            var m = '<?php echo $m?>';


            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            //return false;
            $.ajax({
                url: '/pdc/filterByCategoryAndRegionWiseStockOpening',
                type: 'Get',
                data: {CategoryId: CategoryId,RegionId:RegionId,m:m},

                success: function (response) {
                    //alert(response);
                    $('#data').html(response);
                }
            });
        }

    </script>
    @endsection

