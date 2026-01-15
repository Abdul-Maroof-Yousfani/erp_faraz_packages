<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(357);
$edit=ReuseableCode::check_rights(358);
$delete=ReuseableCode::check_rights(359);


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
                                    <span class="subHeadingLabelClass">Factory Overhead List</span>
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
                                                            <th class="text-center">Factory Overhead</th>
                                                            <th class="text-center">Factory Overhead Category</th>
                                                            <th class="text-center">Accounts</th>
                                                            <th class="text-center">Total Amount</th>
                                                            <th class="text-center">Total No Of Pieces</th>
                                                            <th class="text-center">FOH Cost per Unit</th>
                                                            <th class="text-center">View</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $count = 1;
                                                           $total_amount=0;
                                                           $total_cost=0;
                                                           $total_no_of_piece=0;
                                                          $data=  DB::Connection('mysql2')->table('production_factory_overhead')->where('status',1)->get();
                                                            foreach($data as $row):
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                <td><?php echo $count++;?></td>
                                                                <td><?php echo $row->name?></td>
                                                                <td>
                                                                    <?php
                                                                        if($row->over_head_category_id > 0):
                                                                    echo ProductionHelper::get_over_head_cagegory_name($row->over_head_category_id);
                                                                        endif;
                                                                    ?>
                                                                </td>
                                                               <?php $master_data=DB::Connection('mysql2')->table('production_factory_overhead_data')->where('master_id',$row->id); ?>
                                                                <td>
                                                                    <?php
                                                                    $account_count=1;
                                                                    foreach($master_data->get() as $row1):
                                                                       echo  '('.$account_count++.') '.CommonHelper::get_account_name($row1->acc_id).'</br>';
                                                                    endforeach;
                                                                    ?></td>

                                                                <td><?php echo  number_format($master_data->sum('amount'),2); $total_amount+=$master_data->sum('amount')?></td>
                                                                <td><?php echo  number_format($master_data->sum('no_of_piece'),2);$total_no_of_piece+=$master_data->sum('no_of_piece')?></td>
                                                                <td><?php echo  number_format($master_data->sum('cost'),2); $total_cost+=$master_data->sum('cost')?></td>

                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/view_factory_overhead_detail?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Factory Overhead Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                        <a href="<?php echo URL::asset('production/edit_factory_over_head?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true):?>
                                                                        <button onclick="DeleteFactoryOverhead('<?php echo $row->id?>','<?php echo Session::get('run_company') ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
                                                                    <?php endif;?>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>

                                                            <tr class="text-center" style="font-size: large;font-weight: bold;background-color: lightgrey">
                                                                <td colspan="4">Total</td>
                                                                <td>{{number_format($total_amount,2)}}</td>
                                                                <td>{{number_format($total_no_of_piece,2)}}</td>
                                                                <td>{{number_format($total_cost,2)}}</td>
                                                            </tr>
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
        function DeleteFactoryOverhead(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_factory_over_head',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        alert('Deleted');
                        $('#RemoveTr'+id).remove();
                    }
                });
            }
            else{}
        }
    </script>

@endsection