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

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$view=ReuseableCode::check_rights(118);
$edit=ReuseableCode::check_rights(119);
$delete=ReuseableCode::check_rights(120);
$export=ReuseableCode::check_rights(257);


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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Sales  Invoice List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            <?php endif;?>
                        </div>
                    </div>

                    <hr style="border-color: #ccc">

                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Filters</label>
                            <select id="filters" onchange="FilterSelection()" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">Search By Date</option>
                                <option value="2">Search By Voucher No</option>
                                <option value="3">Search By Buyer</option>
                            </select>
                        </div>

                        {{--<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search by Date) <input type="radio" class="form-control" name="SelectType" value="1" onclick="FilterSelection()"></label>--}}
                            {{--<label for="">OR</label>--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search By Voucher No) <input type="radio" class="form-control" name="SelectType" value="2" onclick="FilterSelection()"></label>--}}
                            {{--<label for="">OR</label>--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search By Buyer) <input type="radio" class="form-control" name="SelectType" value="3" onclick="FilterSelection()"></label>--}}
                        {{--</div>--}}
                        <span id="ShowHideDate" style="display: none">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>From Date</label>
                                <input type="Date" name="from" id="from"  value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>To Date</label>
                                <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                            </div>
                            <div style="margin-top: 40px" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="radio-inline"><input value="1" type="radio" name="optradio">Open</label>
                                <label class="radio-inline"><input value="2" type="radio" name="optradio">Partial</label>
                                <label class="radio-inline"><input value="3" type="radio" name="optradio">Complete</label>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>

                        </span>
                        <span id="ShowHideSoNo" style="display: none">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>(SO NO) <input type="radio" class="form-control" name="FilterType" value="1" onclick="RadioChange()"></label>
                                <label for=""> OR </label>
                                <label>(SI NO) <input type="radio" class="form-control" name="FilterType" value="2" onclick="RadioChange()"></label>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="">Search By <span id="ChangeType"></span></label>
                                <input type="text" class="form-control" id="SearchText" name="SearchText" disabled>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <!-- <button type="button" class="btn btn-xs btn-primary" style="margin-top: 32px;" id="BtnReset" onclick="ResetFields()">Reset</button> -->
                                <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </span>
                        <span id="ShowHideBuyer" style="display: none">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Buyer</label>
                                <select name="BuyerId" id="BuyerId" class="form-control">
                                    <option value="">Select Buyer</option>
                                    <?php foreach($Customer as $Fil):?>
                                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div style="margin-top: 40px" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="radio-inline"><input value="1" type="radio" name="optradio">Open</label>
                                <label class="radio-inline"><input value="2" type="radio" name="optradio">Partial</label>
                                <label class="radio-inline"><input value="3" type="radio" name="optradio">Complete</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
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
                                        <table class="userlittab table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">SO No</th>
                                                <th class="text-center">SI No</th>
                                                <th class="text-center hide">ST No</th>
                                                <th class="text-center">Buyer's Unit</th>
                                                <th class="text-center">Order No</th>
                                                <th class="text-center">SI Date</th>
                                                <th class="text-center hide">Model Terms Of Payment</th>
                                                <th class="text-center">Order Date</th>
                                                <th class="text-center">Customer</th>
                                                <th class="text-center">Total Amount</th>
                                                <th class="text-center">SI Status</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                                {{--<th class="text-center">Delete</th>--}}
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
                    XLSX.writeFile(wb, fn || ('Sales Tax Invoice <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>

        $(document).ready(function(){
            $('#BuyerId').select2();
            $('.select2-container--default').css('width','100%');
            viewRangeWiseDataFilter();
        });

        function UpdateValue(Id)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            var ScNo = $('#ScNo'+Id).val();
            if(ScNo !="")
            {
                $('#ScNoError'+Id).html('');
                $.ajax({
                    url: base_url+'/sdc/updateScNo',
                    type: 'GET',
                    data: {Id: Id,ScNo:ScNo},
                    success: function (response) {
                        alert(response);
                    }
                });
            }
            else
            {
                $('#ScNoError'+Id).html('<p class="text-danger">Enter Sc No</p>');
            }

        }

        function FilterSelection()
        {
            var   radioValue=$('#filters').val();

            if(radioValue == 1)
            {
                $('#ShowHideDate').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
            }
            else if(radioValue == 2)
            {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideDate').css('display','none');
                $('#ShowHideBuyer').css('display','none');
            }
            else if(radioValue == 3)
            {
                $('#ShowHideBuyer').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideDate').css('display','none');
            }
            else
            {
                $('#ShowHideBuyer').css('display','none');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
            }
        }

        function sales_tax_delete(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sad/sales_tax_delete',
                    type: 'GET',
                    data: {id: id, m:m},
                    success: function (response) {
                        alert('Deleted');
                       // alert(response);
                        $('#' + id).remove();

                    }
                });
            }
            else{}
        }


        function RadioChange()
        {
            var radioValue = $("input[name='FilterType']:checked").val();

            if(radioValue == 1)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('SO NO');
                $('#SearchText').prop('placeholder','Enter SO NO');
            }
            else if(radioValue == 2)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('SI NO');
                $('#SearchText').prop('placeholder','Enter SI NO');
            }
            else
            {
                $('#ChangeType').html('');
                $('#SearchText').prop('placeholder','');
                $('#SearchText').prop('disabled',true);
            }
        }

        function ResetFields()
        {
            $('input[name="FilterType"]').attr('checked', false);
            $('#ChangeType').html('');
            $('#SearchText').prop('placeholder','');
            $('#SearchText').val('');
            $('#SearchText').prop('disabled',true);
        }

        function viewRangeWiseDataFilter()
        {
            var radioValue = $("input[name='FilterType']:checked").val();
            var FilterType = $('#filters').val();
            var SearchText = $('#SearchText').val();
            var BuyerId = $('#BuyerId').val();
            var from= $('#from').val();
            var to= $('#to').val();
            var m = '<?php echo $m;?>';
            var radio= $('input[name="optradio"]:checked').val();
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '{{ url('/sdc/getSalesTaxInvoiceeFilterWise') }}',
                type: 'Get',
                data: {from: from,to:to,m:m,radioValue:radioValue,SearchText:SearchText,FilterType:FilterType,BuyerId:BuyerId,radio:radio},

                success: function (response) {
                    $('#data').html(response);
                }
            });

        }

        function sales_tax(sales_order_id,m)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            window.location.href = base_url+'/sales/EditSalesTaxInvoice?sales_order_id='+sales_order_id+'&&'+'m='+m;
        }
    </script>

@endsection
