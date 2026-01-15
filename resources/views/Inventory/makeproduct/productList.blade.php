<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

use Illuminate\Support\Facades\Session;

$view = ReuseableCode::check_rights(124);
$edit = ReuseableCode::check_rights(125);
$export = ReuseableCode::check_rights(258);
?>
@extends('layouts.default')
@section('content')
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Product List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1'); ?>
                                <?php if ($export == true) : ?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView(''); ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center col-sm-2">Product</th>
                                                <th class="text-center col-sm-2">Quantity</th>
                                                <th class="text-center col-sm-2">Cost</th>
                                                <th class="text-center col-sm-2">Action</th>
                                            </thead>
                                            <tbody id="data" class="text-center">
                                                @foreach ($makeProduct as $key => $Fil)
                                                <tr id="remove{{ $Fil['id'] }}">
                                                    <td>{{++$key}}</td>
                                                    <td>{{$Fil->recipe->subItem->sub_ic}}</td>
                                                    <td>{{$Fil->quantity}}</td>
                                                    <td>{{$Fil->average_cost}}</td>
                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('makeProduct/viewProductList','{{$Fil->id}}','View Make Product Detail')" type="button" class="btn btn-success btn-xs">View</button>
                                                        <button class='btn btn-danger delete' onclick="delete_cate('{{ $Fil['id'] }}')" value='"+row.id+"' style='margin-left:20px;'>Delete</button>
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
</div>
<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('EmpExitInterviewList');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Sales Return <?php echo date('d-M-Y') ?>.' + (type || 'xlsx')));
    }

    function delete_cate(id) {
        if (confirm('Are You Sure ? You want to delete this recored...!')) {
            
            var url = '<?php echo url('/') ?>';
            $.ajax({
                url: url + '/recipe/recipeDelete',
                type: 'Get',
                data: {
                    id: id
                },
                success: function(response) {
                    $('#remove' + response).remove();
                }
            });
        } else {}
    }
</script>

@endsection