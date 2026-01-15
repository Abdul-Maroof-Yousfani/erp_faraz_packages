<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">View Tax List</span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintTaxesList','','1');?>
                        <?php echo CommonHelper::displayExportButton('TaxesList','','1')?>
                    </div>
                    <div class="panel">
                        <div class="panel-body" id="PrintTaxesList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="TaxesList">
                                            <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Emp Name</th>
                                                <th class="text-center">Tax Name</th>
                                                <th class="text-center">Percent % </th>

                                                <th class="text-center col-sm-2 hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($employeeTax as  $value)
                                                <tr>
                                                    <td class="text-center"><?php echo $counter++;?></td>
                                                    <td class="text-center">{{ $value->emp_name }}</td>
                                                    <td class="text-center">
                                                        <?php
                                                        if($value->tax_id == 0):
                                                            echo "<span style='color:red'>Tax Not Assigned</span>";
                                                        else:
                                                            echo HrHelper::getMasterTableValueById(Input::get('m'),'tax','tax_name',$value->tax_id);
                                                        endif;
                                                        ?>
                                                    </td>
                                                    <td class="text-center"><?php
                                                         if($value->tax_id == 0):
                                                            echo "<span style='color:red'>Tax Not Assigned</span>";
                                                        else:
                                                            echo HrHelper::getMasterTableValueById(Input::get('m'),'tax','tax_percent',$value->tax_id);
                                                         endif;
                                                        ?>
                                                    </td>

                                                    <td class="text-center hidden-print">
                                                        <button class="edit-modal btn btn-info btn-xs" onclick="showMasterTableEditModel('finance/editEmployeeTaxDetailForm','<?php echo $value->id ?>','Taxes Edit Detail Form','<?php echo $m?>')">
                                                            <span class="glyphicon glyphicon-edit"></span>
                                                        </button>

                                                            <button class="delete-modal btn btn-danger btn-xs" onclick="deleteEmployeeTax('<?php echo $value->id ?>')">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </button>

                                                    </td>
                                                </tr>
                                            @endforeach
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
    <script>
        function deleteEmployeeTax(emp_id) {

            var m = '<?= Input::get('m')?>';
            $.ajax({
                url: '<?php echo url('/')?>/fd/deleteEmployeeTax',
                type: "GET",
                data: { emp_id:emp_id ,m : m},
                success:function(data) {
                       location.reload();

                }
            });
        }
    </script>
@endsection