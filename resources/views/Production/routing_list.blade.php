<?php

use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(368);
$edit=ReuseableCode::check_rights(369);
$delete=ReuseableCode::check_rights(370);
?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Routing List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Route Code</th>
                                                                <th class="text-center">Finish Goods</th>

                                                                <th class="text-center">Username</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $counter = 1;
                                                            foreach(ProductionHelper::get_all_routing() as $row):
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                <td><?php echo $counter++;?></td>
                                                                <td><?php echo $row->voucher_no?></td>
                                                                <td><?php echo CommonHelper::get_item_name($row->finish_goods);?></td>
                                                               
                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/viewRoutingDetail?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Routing Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                        <a href="<?php echo URL::asset('production/edit_routing?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true):?>
                                                                        <button onclick="DeleteRoute('<?php echo $row->id?>','<?php echo Session::get('run_company')?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
                                                                    <?php endif;?>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        function DeleteRoute(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_route',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        if(response == 'no')
                        {
                            alert("You Can't delete this Recored, Plane Created against this Route");
                        }
                        else{
                            alert('Deleted');
                            $('#RemoveTr'+id).remove();
                        }

                    }
                });
            }
            else{}
        }
    </script>

@endsection