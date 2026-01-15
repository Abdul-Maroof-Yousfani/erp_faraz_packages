<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$edit=ReuseableCode::check_rights(284);
$delete=ReuseableCode::check_rights(285);
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
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Sub Category List</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Sub Category Name</th>
                                                    <th class="text-center">Transactions</th>
                                                    <th class="text-center">Action </th>

                                                    </thead>
                                                    <tbody id="viewCategoryList">
                                                    <?php
                                                    $Counter = 1;
                                                    foreach($SubCategory as $row):

                                                    // $count= DB::Connection('mysql2')->table('stock as  a')
                                                    //      ->join('subitem as  b','a.sub_item_id','=','b.id')
                                                    //      ->where('b.sub_category_id',$row->id)
                                                    //      ->where('a.status',1)
                                                    //      ->count();
                                                    $count =1;
                                                    $category_name=CommonHelper::get_category_name($row->category_id);
                                                    ?>
                                                    <tr id="remove{{$row->id}}">
                                                        <td><?php echo $Counter++;?></td>
                                                        <td><?php echo  $category_name;?></td>
                                                        <td><?php echo  $row->sub_category_name;?></td>
                                                        <td style="text-align: center"> @if($count>0){{'&#x2714;'}} @else {{'&#x2716;'}} @endif</td>


                                                        <td style="text-align: center">
                                                            <?php if($edit == true):?>
                                                                <button type="button" onclick="showDetailModelMasterTable('<?php Session::get('run_company') ?>','purchase/edit_sub_ca?id=<?php echo $row->id ?>','1','<?php echo $row->id ?>','<?php echo  $row->id ?>', 'category','Edit Sub Category  Form')" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></button>
                                                                <!-- <a onclick="showDetailModelMasterTable('<?php Session::get('run_company') ?>','purchase/edit_sub_ca?id=<?php echo $row->id ?>','1','<?php echo $row->id ?>','<?php echo  $row->id ?>', 'category','Edit Sub Category  Form')"
                                                                    class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a> -->
                                                            <?php endif;?>
                                                        @if ($count==0)
                                                            <?php if($delete == true):?>
                                                        <button type="button" class="btn btn-danger btn-xs" id="" onclick="delete_sub_cate('<?php echo $row->id ?>')"><span class="glyphicon glyphicon-trash"></span></button>
                                                            <?php endif;?>
                                                            @endif

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
    <script type="text/javascript">


    </script>

    <script>
        function delete_sub_cate(id)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                $.ajax({
                    url: '/pdc/delete_sub_cate',
                    type: 'Get',
                    data: {id: id},

                    success: function (response)
                    {


                        $('#remove'+response).remove();
                    }
                });
            }
            else {}
        }

    </script>
@endsection
