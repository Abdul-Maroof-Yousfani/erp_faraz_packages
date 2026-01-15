<?php

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
                                    <span class="subHeadingLabelClass">View Labour Category List</span>
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
                                                            <th class="text-center">Labour Category</th>
                                                            {{--<th class="text-center">Batch Code</th>--}}
                                                            <th class="text-center">Charges</th>
                                                            {{--<th class="text-center">Life</th>--}}
                                                            <th class="text-center">Username</th>
                                                            <th class="text-center">Action</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $Counter = 1;
                                                            foreach($LabCat as $Fil):
                                                            ?>
                                                            <tr class="text-center">
                                                                <td><?php echo $Counter++;?></td>
                                                                <td><?php echo $Fil->labour_category?></td>
                                                                {{--<td>< ?php echo $Fil->batch_code?></td>--}}
                                                                <td><?php echo $Fil->charges?></td>
                                                                {{--<td>< ?php echo $Fil->life?></td>--}}
                                                                <td><?php echo $Fil->username?></td>
                                                                <td></td>
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
        function viewCategoryList(){
            $('#viewCategoryList').html('<tr><td colspan="4"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pdc/viewCategoryList',
                type: "GET",
                data:{m:m},
                success:function(data) {
                    setTimeout(function(){
                        $('#viewCategoryList').html(data);
                    },1000);
                }
            });
        }
        viewCategoryList();
    </script>
@endsection