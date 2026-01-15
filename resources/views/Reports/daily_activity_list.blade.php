<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style>
        element.style {
            width: 183px;
        }
    </style>
    <h3 class="text-center">Daily Activity List</h3>
    <table class="table table-bordered sf-table-list">
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">Task Date</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="GetData">

        <?php
        // print_r($DailyTask); die;
        $Counter=1;
        foreach($DailyTask as $key => $value):
        ?>
        <tr class="text-center">
            <td> <?php echo $Counter++; ?> </td>
            <td> <?php echo date('d-m-Y',strtotime($value->task_date)); ?> </td>
            <td>
                <button type="button" class="btn btn-default" onclick="showDetailModelOneParamerter('reports/get_daily_task?m=<?php echo $m; ?>','<?php echo $value->id; ?>','View Daily Activity List');"> View </button>
                <a href="<?php echo url('/')."/reports/edit_daily_activity?id=".$value->id."&&m=".$m; ?>">Update List</a>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    
@endsection