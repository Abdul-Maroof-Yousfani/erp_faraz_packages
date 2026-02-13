<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Illuminate\Support\Facades\Session;

$view = ReuseableCode::check_rights(124);
$edit = ReuseableCode::check_rights(125);
$export = ReuseableCode::check_rights(258);
$this->m = Session::get('run_company');
?>
@extends('layouts.default')
@section('content')

    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="headquid">
                                    <h2 class="subHeadingLabelClass">Production Roll Printing List</h2>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <div class="headquid">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1'); ?>
                                    <?php if ($export == true): ?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')"><i
                                            class="fa fa-external-link" aria-hidden="true"></i> Export </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintEmpExitInterviewList">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m); ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="userlittab table table-bordered sf-table-list"
                                                id="EmpExitInterviewList">
                                                <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Printed Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Brand</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Remarks</th>
                                                    <th class="text-center">Prod. Order No.</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </thead>
                                                <?php $count = 0; ?>
                                                <tbody id="data">
                                                    @foreach ($rollPricingList as $Fil)
                                                        <tr id="remove<?php    echo $Fil['id'] ?>">
                                                            <td>{{++$count}}</td>
                                                            <td>{{CommonHelper::get_item_name($Fil->item_id)}}</td>
                                                            <td> {{$Fil->no_of_roll}} </td>
                                                            <td> {{$Fil->type}} </td>
                                                            <td> {{$Fil->brand->name ?? '-' }} </td>
                                                            <td> {{$Fil->color->name ?? '-' }} </td>
                                                            <td> {{$Fil->date }} </td>
                                                            <td> {{$Fil->remarks}} </td>
                                                            <td>{{ optional($Fil->productionRoll->productionOrder)->pr_no }} </td>
                                                            <td>
                                                                @if($Fil->status == 1)
                                                                    Active
                                                                @elseif($Fil->status == 2)
                                                                    Disabled
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="dropdown">
                                                                    <button class="drop-bt dropdown-toggle" type="button"
                                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                                            class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                          
                                                                                 <a href="cuttingAndSealing?id=<?php    echo $Fil['id'] ?>&&m=<?php    echo $this->m?>">Process Sealing & Cutting
                                                                            </a>
                                                                            <a href="mixtureEdit?id=<?php    echo $Fil['id'] ?>&&m=<?php    echo $this->m?>">
                                                                            </a>
                                                                           

                                                                            {{-- @if($Fil->status == 1)
                                                                                <a
                                                                                    onclick="showDetailModelOneParamerter('recipe/changeFormulationStatus','{{$Fil->id . '--' . '2'}}','','{{$this->m}}')">Disable</a>
                                                                            @elseif($Fil->status == 2)
                                                                                <a
                                                                                    onclick="changeFormulationStatus('{{$Fil->id}}','1','{{$this->m}}')">Enable</a>
                                                                            @endif --}}
                                                                        </li>
                                                                    </ul>
                                                                </div>
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
                var m = '<?php echo $this->m ?>';
                var url = '<?php echo url('/') ?>';
                $.ajax({
                    url: url + '/recipe/recipeDelete',
                    type: 'Get',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        $('#remove' + response).remove();
                        console.log(response)
                    }
                });
            } else { }
        }

        function changeFormulationStatus(id, status, m) {
            $.ajax({
                url: '{{ url('/') }}/recipe/addChangeFormulationStatusDetail',
                type: 'POST',
                data: {
                    id: id, m: m, status: status
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection