<?php

$m = Session::get('run_company');


use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;


$view=ReuseableCode::check_rights(345);
$edit=ReuseableCode::check_rights(346);
$delete=ReuseableCode::check_rights(347);
$FinishGood = DB::Connection('mysql2')->select('select finish_good from production_machine_data GROUP BY  finish_good');
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Machine List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <select onchange="GetData()" name="FinishGoodId" id="FinishGoodId" class="form-control">
                                                        <option value="">Select Finish Good</option>
                                                        <?php foreach($FinishGood as $Fil):?>
                                                        <option value="<?php echo $Fil->finish_good?>"><?php echo CommonHelper::get_item_name($Fil->finish_good);?></option>
                                                        <?php endforeach;?>
                                                    </select>

                                                    <span id="FinishGoodError"></span>
                                                </div>

                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Name</th>
                                                            <th class="text-center">Code</th>
                                                            <th class="text-center">Depreciable Cost</th>
                                                            <th class="text-center">Life in Piece</th>
                                                            <th class="text-center">Depreciation per Piece</th>
                                                            <th class="text-center">Description</th>

                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody id="AjaxDataHere">
                                                            <?php
                                                            $counter = 1;
                                                            foreach(ProductionHelper::get_all_machine() as $row):
                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                <td><?php echo $counter++;?></td>
                                                                <td><?php echo $row->machine_name.'('.$row->id.')'?></td>
                                                                <td><?php echo $row->code?></td>
                                                    
                                                                <td><?php echo number_format($row->dep_cost,2)?></td>
                                                                <td><?php echo number_format($row->life,2)?></td>
                                                                <td><?php echo number_format($row->yearly_cost,2)?></td>
                                                                <td><?php echo $row->description?></td>

                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/viewMachineDetail?m=<?php echo $m?>','<?php echo $row->id;?>','View Machine Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>
                                                                    <?php if($edit == true):?>
                                                                        <a href="<?php echo URL::asset('production/editMachineForm?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete== true):?>
                                                                        <button onclick="DeleteMachine('<?php echo $row->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
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

    $(document).ready(function(){
        $('#FinishGoodId').select2();
    });


    function GetData()
    {
        var FinishGoodId = $('#FinishGoodId').val();
        if(FinishGoodId !="")
        {
            $('#FinishGoodError').html('');
            $('#AjaxDataHere').html('<tr><td colspan="8"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>')
            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/production/get_machine_data_by_finish_good',
                type: 'GET',
                data: {FinishGoodId: FinishGoodId},
                success: function (response)
                {
                    $('#AjaxDataHere').html(response);
                }
            });
        }
        else
        {
            $('#FinishGoodError').html('<p class="text-danger">Select Finish Good...!</p>');
        }


    }
    function DeleteMachine(id,m)
    {
        if (confirm('Are you sure you want to delete this request')) {
            var base_url='<?php echo URL::to('/'); ?>';
            $.ajax({
                url: base_url+'/prd/delete_machine',
                type: 'GET',
                data: {id: id},
                success: function (response) {

                    if (response!="")
                    {
                        alert("You Can't Delete this Recored, Operation Exist!")
                    }

                    else {
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