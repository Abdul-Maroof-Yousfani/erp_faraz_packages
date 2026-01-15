<?php

$m = Session::get('run_company');

use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(348);
$edit=ReuseableCode::check_rights(349);
$delete=ReuseableCode::check_rights(350);
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Bill Of Material List</span>
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
                                                            <th class="text-center">Finish Goods</th>
                                                            <th class="text-center">Description</th>
                                                            <th class="text-center">Direct Material</th>
                                                            <th class="text-center">InDirect Material</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $counter = 1;
                                                            foreach(ProductionHelper::get_all_bom() as $row):
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                <td><?php echo $counter++;?></td>
                                                                <td><?php echo CommonHelper::get_item_name($row->finish_goods);?></td>
                                                                <td><?php echo $row->description?></td>
                                                                <td>
                                                                    <?php $Direct = DB::Connection('mysql2')->table('production_bom_data_direct_material')->where('status',1)->where('master_id',$row->id)->select('item_id')->get();
                                                                        $DirectCounter =0;
                                                                    foreach($Direct as $Dfil):
                                                                        $DirectCounter++;
                                                                        echo '<span>'.$DirectCounter.'</span>'.'='.CommonHelper::get_item_name($Dfil->item_id);
                                                                        echo "<br>";
                                                                    endforeach;?>
                                                                </td>
                                                                <td>
                                                                    <?php $InDirect = DB::Connection('mysql2')->table('production_bom_data_indirect_material')->where('status',1)->where('main_id',$row->id)->select('item_id')->get();
                                                                    $InDirectCounter =0;
                                                                    foreach($InDirect as $InDfil):
                                                                        $InDirectCounter++;
                                                                        echo '<span>'.$InDirectCounter.'</span>'.'='.CommonHelper::get_item_name($InDfil->item_id );
                                                                        echo "<br>";
                                                                    endforeach;?>
                                                                </td>
                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/viewBomDetail?m=<?php echo $m?>','<?php echo $row->id;?>','View Bill Of Marterial Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>

                                                                    {{--<a  onclick='showDetailModelOneParamerter("production/create_bom_detail","< ?php echo $row->id;?>","Add Bom Detail","< ?php echo $m?>","")' class="btn btn-xs btn-primary">Add Detail</a>--}}
                                                                    <?php if($edit == true):?>
                                                                        <a href="<?php echo URL::asset('production/edit_bill_of_material?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true):?>
                                                                        <button onclick="DeleteBom('<?php echo $row->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
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
    <script type="text/javascript">
        function DeleteBom(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_bom',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        if (response=='no')
                        {
                            alert('Can not Delete');
                        }
                        else
                        {
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