<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(298);

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>
@extends('layouts.default')
@section('content')
    @include('select2')


    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Sale Order List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #ccc">


                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Filters</label>
                            <select id="filters" onchange="RadioChange()" class="form-control">
                                <option value="0">Select</option>
                                <option value="2">Search By SO</option>
                                <option value="3">Search By  Order No</option>
                            </select>
                        </div>

                        <span id="ShowHideSoNo" style="display: none">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>So No</label>
                                <input type="text" name="SoNo" id="SoNo" class="form-control" placeholder="SO NO"  />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </span>
                        <span id="ShowHideOrderNo" style="display: none">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>Buyer Order No</label>
                                <input type="text" name="BuyerOrderNo" id="BuyerOrderNo" class="form-control" placeholder="Buyer Order No"  />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </span>

                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">SO No</th>
                                            <th class="text-center col-sm-1">SO Date</th>
                                            <th class="text-center col-sm-1">Buyer Order No</th>
                                            <th class="text-center col-sm-1">Item</th>
                                            <th class="text-center col-sm-1">SO Qty</th>
                                            <th class="text-center col-sm-1">DN Qty</th>
                                            <th class="text-center col-sm-1">Balance Qty</th>
                                            </thead>
                                            <tbody id="data">

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
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('So Tracking Qty <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>

        function RadioChange()
        {
            var   radioValue=$('#filters').val();

            if(radioValue == 2)
            {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideOrderNo').css('display','none');
            }
            else if(radioValue == 3)
            {
                $('#ShowHideOrderNo').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');

            }
            else if (radioValue == 0)
            {

                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideOrderNo').css('display','none');
            }
        }

        function viewRangeWiseDataFilter()
        {


            var SoNo= $('#SoNo').val();
            var BuyerOrderNo= $('#BuyerOrderNo').val();
            var FilterType=$('#filters').val();

            var m ='<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/getSoTrackingQtyAjax',
                type: 'Get',
                data: {m:m,SoNo:SoNo,BuyerOrderNo:BuyerOrderNo,FilterType:FilterType},

                success: function (response)
                {
                    $('#data').html(response);
                }
            });


        }
    </script>

@endsection