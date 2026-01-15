<?php

$m = Session::get('run_company');
use App\Helpers\ReuseableCode;

$add_detail=ReuseableCode::check_rights(342);
$edit=ReuseableCode::check_rights(343);
$delete=ReuseableCode::check_rights(344);

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
                                    <span class="subHeadingLabelClass">View Mould List</span>
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
                                                            <th class="text-center">Mould Name</th>
                                                            <th class="text-center">Size</th>
                                                            <th class="text-center">Username</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $Counter = 1;
                                                            foreach($Mold as $row):
                                                            ?>
                                                                <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                    <td><?php echo $Counter++;?></td>
                                                                    <td><?php echo $row->mold_name?></td>
                                                                    <td><?php echo $row->size?></td>
                                                                    <td><?php echo $row->username?></td>
                                                                    <td>
                                                                        <button onclick="showDetailModelOneParamerter('production/viewMoldDetail?m=<?php echo $m?>','<?php echo $row->id;?>','View Mold Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                        <?php if($add_detail == true):?>
                                                                            <a onclick="showDetailModelOneParamerter('production/add_mould_detail','<?php echo $row->id;?>','','<?php echo $m?>','')" class="btn btn-xs btn-success">Add</a>
                                                                        <?php endif;?>
                                                                        <?php if($edit == true):?>
                                                                            <a href="<?php echo URL::asset('production/editMoldForm?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                        <?php endif;?>
                                                                        <?php if($delete == true):?>
                                                                            <button onclick="DeleteMould('<?php echo $row->id?>','<?php echo $m ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
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
        function DeleteMould(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_mould',
                    type: 'GET',
                    data: {id: id},
                    success: function (response) {
                    alert(response);
                        var ResponseArray = response.split("@#@");
                        if(ResponseArray[1] !="" || null || ResponseArray[0] != "" || null)
                        {
                            if(ResponseArray[1] !="")
                            {
                                alert("You Can't Delete this Recored, "+ResponseArray[1]+" Mould Other Details Created against this Mould....!")
                            }
                            if(ResponseArray[0] !="")
                            {
                                alert("You Can't Delete this Recored, "+ResponseArray[0]+" Machine Created against this Mould....!")
                            }
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