<?php
use App\Helpers\CommonHelper;


$m = Session::get('run_company');


use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(365);
$edit=ReuseableCode::check_rights(366);
$delete=ReuseableCode::check_rights(367);
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
                                    <span class="subHeadingLabelClass">View Operaion List</span>
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
                                                            <th class="text-center">Operation Name</th>
                                                            {{--<th class="text-center">Machine Name</th>--}}
                                                            <th class="text-center">Username</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $Counter = 1;
                                                            foreach($Operation as $Fil):
                                                                    $FinishGood = CommonHelper::get_single_row('subitem','id',$Fil->finish_good_id);
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
                                                                <td><?php echo $Counter++;?></td>
                                                                <td><?php echo $FinishGood->sub_ic?></td>
                                                                <td><?php echo $Fil->username?></td>
                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <a onclick="showDetailModelOneParamerter('production/viewOperationDetail','<?php echo $Fil->id;?>','View Operation Detail','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success">View</a>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                        <a href="<?php echo URL::asset('production/edit_operation?edit_id='.$Fil->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true):?>
                                                                        <button onclick="DeleteOperation('<?php echo $Fil->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
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
        function DeleteOperation(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_operation',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        if(response == 'no')
                        {
                            alert("You Can't Delete this Recored, Route Created against this Operation");
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