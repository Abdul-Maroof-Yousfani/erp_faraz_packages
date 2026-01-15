<?php
use App\Helpers\CommonHelper;
$current_date = date('Y-m-d');
//$current_month = date('m');
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="well">
                            <?php echo CommonHelper::reportBranchAndRangeFilterSection();?>
                            <div style="line-height: 8px;">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <?php echo CommonHelper::reportDateMonthAndYearFilterSection('','','');?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        adminRangeFilter();
    </script>
@endsection