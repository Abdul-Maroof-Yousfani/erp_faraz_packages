<?php

$m = Session::get('run_company');
use App\Helpers\ReuseableCode;

$add_detail=ReuseableCode::check_rights(339);
$edit=ReuseableCode::check_rights(340);
$delete=ReuseableCode::check_rights(341);

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
                                    <span class="subHeadingLabelClass">View Die List</span>
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
                                                            <th class="text-center">Die Name</th>
                                                            {{--<th class="text-center">Batch Code</th>--}}
                                                            <th class="text-center">Size</th>
                                                            {{--<th class="text-center">Life</th>--}}
                                                            <th class="text-center">Username</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $Counter = 1;
                                                            foreach($Dai as $Fil):
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
                                                                <td><?php echo $Counter++;?></td>
                                                                <td><?php echo $Fil->dai_name?></td>
                                                                {{--<td>< ?php echo $Fil->batch_code?></td>--}}
                                                                <td><?php echo $Fil->size?></td>
                                                                {{--<td>< ?php echo $Fil->life?></td>--}}
                                                                <td><?php echo $Fil->username?></td>
                                                                <td>

                                                                    <button onclick="showDetailModelOneParamerter('production/viewDieDetail?m=<?php echo $m?>','<?php echo $Fil->id;?>','View Die Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php if($add_detail == true):?>
                                                                    <a onclick="showDetailModelOneParamerter('production/create_die_detail','<?php echo $Fil->id;?>','','<?php echo $m?>','')" class="btn btn-xs btn-success">Add</a>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                    <a href="<?php echo URL::asset('production/editDaiForm?edit_id='.$Fil->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true):?>
                                                                    <button onclick="DeleteDie('<?php echo $Fil->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
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
        function DeleteDie(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_die',
                    type: 'GET',
                    data: {id: id},
                    success: function (response) {

                        var ResponseArray = response.split("@#@");
                        if(ResponseArray[1] !="" || ResponseArray[0] != "")
                        {
                            if(ResponseArray[1] !="")
                            {
                                alert("You Can't Delete this Recored, "+ResponseArray[1]+" Die Other Details Created against this Die....!")
                            }
                            if(ResponseArray[0] !="")
                            {
                                alert("You Can't Delete this Recored, "+ResponseArray[0]+" Machine Created against this Die....!")
                            }
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