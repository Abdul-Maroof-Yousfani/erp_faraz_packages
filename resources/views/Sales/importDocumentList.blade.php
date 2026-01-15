<?php

use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
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
                                    <span class="subHeadingLabelClass">View Import PO List</span>
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
                                                            <th class="text-center">Voucher No</th>
                                                            <th class="text-center">Voucher Date</th>
                                                            <th class="text-center">Vendor</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody id="">
                                                            <?php
                                                            $paramOne = "sdc/viewImportPoDetail?m=".$m;
                                                             $Counter =1;
                                                                    foreach($ImportPo as $Fil):
                                                            $edit_url=url('sales/editImportDocument/'.$Fil->id.'?m='.$m);
                                                            ?>
                                                                <tr id="import{{$Fil->id}}" class="text-center">
                                                                    <td><?php echo $Counter++;;?></td>
                                                                    <td><?php echo $Fil->voucher_no?></td>
                                                                    <td><?php echo CommonHelper::changeDateFormat($Fil->voucher_date)?></td>
                                                                    <td><?php echo CommonHelper::get_supplier_name($Fil->vendor);?></td>
                                                                    <td>
                                                                        <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->id;?>','View Import PO Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                        <a href="<?php echo $edit_url?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <button onclick="delete_payment('{{$Fil->id}}',1)" type="button" class="btn btn-danger btn-xs">Delete</button>
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
        function delete_payment(id,type)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sdc/import_delete',
                    type: 'GET',
                    data: {id: id,type:type},
                    success: function (response) {

                        if (response=='no')
                        {
                            alert('can not delete');
                            return false;
                        }
                        alert('Deleted');
                        // alert(response);
                        $('#import' + response).remove();

                    }
                });
            }
            else{}
        }
    </script>
@endsection