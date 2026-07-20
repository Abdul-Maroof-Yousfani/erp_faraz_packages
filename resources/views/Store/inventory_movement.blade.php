<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(243);

 $financial_year=ReuseableCode::get_account_year_from_to($_GET['m']);

        if (isset($_GET['type'])):
            $type=$_GET['type'];
        else:
        $type=0;
        endif;
?>
@extends('layouts.default')
@section('content')
@include('select2')
<?php
?>
<style>
.stockRptWrap{font-family:'Inter',sans-serif;}
.stockRptWrap .dp_sdw{background:#fff;border:1px solid #EEF0F7 !important;border-radius:18px !important;box-shadow:0 8px 26px rgba(20,38,92,0.06) !important;padding:26px 28px !important;}
.stockRptWrap .form-group label,.stockRptWrap > .row > div > label{font-size:12.5px !important;font-weight:700 !important;color:#4A5268 !important;text-transform:uppercase;letter-spacing:.3px;margin-bottom:6px !important;display:block;}
.stockRptWrap input.form-control,.stockRptWrap input[type="date"],.stockRptWrap select.form-control,.stockRptWrap .select2-container .select2-selection--single{border-radius:10px !important;border:1.5px solid #E7EAF3 !important;background:#FBFCFE !important;height:40px !important;font-size:13.5px !important;color:#1B2333 !important;box-shadow:none !important;}
.stockRptWrap .select2-selection__rendered{line-height:38px !important;color:#1B2333 !important;}
.stockRptWrap .select2-selection__arrow{height:38px !important;}
.stockRptWrap input.form-control:focus,.stockRptWrap input[type="date"]:focus,.stockRptWrap select.form-control:focus,.stockRptWrap .select2-container--focus .select2-selection--single{border-color:#7C5CFC !important;background:#fff !important;}
/* Purchase / Sales checkboxes -> pill toggles */
.stockRptWrap .toggle-chip-row{display:flex;gap:10px;margin-top:26px;}
.stockRptWrap .toggle-chip-row label{display:inline-flex !important;align-items:center;gap:7px;background:#F7F9FD;border:1.5px solid #E7EAF3;border-radius:999px;padding:8px 16px;font-size:12.5px !important;font-weight:600 !important;text-transform:none !important;color:#4A5268 !important;cursor:pointer;margin:0 !important;transition:all .15s ease;}
.stockRptWrap .toggle-chip-row label:has(input:checked){background:linear-gradient(135deg,#FF7A45,#7C5CFC);border-color:transparent;color:#fff !important;}
.stockRptWrap .toggle-chip-row input{accent-color:#fff;margin:0;}
#printBankReceiptVoucherList .panel{border-radius:18px !important;border:1px solid #EEF0F7 !important;box-shadow:0 8px 26px rgba(20,38,92,0.06) !important;overflow:hidden;margin-top:20px;}
#printBankReceiptVoucherList .panel-body{padding:22px 24px !important;}

.stockRpt-toolbar{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;}
.stockRpt-toolbar-title{margin:0 !important;font-size:18px !important;font-weight:800 !important;color:#1B2333 !important;}
.stockRpt-toolbar-actions{display:flex !important;align-items:center !important;gap:10px !important;}
.stockRpt-toolbar-actions .btn{height:42px !important;display:inline-flex !important;align-items:center !important;gap:8px !important;margin:0 !important;}
</style>
<div class="stockRptWrap">
    <div class="well_N">
        <div class="">    
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dp_sdw">
                <div class="row">
                    <div  @if ($type==1) style="display: none" @endif class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="email">  From Date</label>
                        <input id="from_date"  required="required" min="{{$financial_year[0]}}" name="from_date" max="{{$financial_year[1]}}" class="date1 form-control" type="date" value="<?php echo $financial_year[0] ?>" />
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="email">  @if ($type==1) As On @else To Date @endif</label>
                        <input id="to_date" required="required" min="{{$financial_year[0]}}" max="{{$financial_year[1]}}" name="from_date" class="date1 form-control" type="date" value="{{$financial_year[1]}}" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Items</label>
                        <select name="ItemId" id="ItemId" class="form-control">
                            <option value="all">ALL</option>
                            <?php foreach($SubItem as $ItemFil):?>
                                <option value="<?php echo $ItemFil->id?>"><?php echo $ItemFil->sub_ic?></option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="toggle-chip-row">
                            <label><input id="purchase" type="checkbox" value="1"> Purchase</label>
                            <label><input id="sales" type="checkbox" value="2"> Sales</label>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <button type="button" class="btn btn-sm btn-primary" onclick="stockReportItemWise()" style="margin: 30px 0px 0px 0px;">Submit</button>
                    </div>
                    <input type="hidden" id="accyearfrom" value="{{$financial_year[0]}}"/>
                </div>

                <div>&nbsp;</div>

                <div id="printBankReceiptVoucherList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="stockRpt-toolbar">
                                <h3 class="stockRpt-toolbar-title">Stock Movement Report</h3>
                                <div class="stockRpt-toolbar-actions">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div id="filterBookDayList"></div>
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
                XLSX.writeFile(wb, fn || ('Inventory Movement <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
    }
</script>
    <script>

        $(document).ready(function(){
            $('#ItemId').select2();
            stockReportItemWise();
        });
        function stockReportItemWise(){
            var ReportType =1;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var ItemId = $('#ItemId').val();
            var accyearfrom = $('#accyearfrom').val();
            var purchase =0;
            if ($('#purchase').is(":checked"))
            {
                purchase=1;
            }

            var sales =0;
            if ($('#sales').is(":checked"))
            {
                sales=1;
            }


            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/stock_movemnet',
                method:'GET',
                data:{from_date:from_date,to_date:to_date,accyearfrom:accyearfrom,ItemId:ItemId,ReportType:ReportType,purchase:purchase,sales:sales},
                error: function()
                {
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }
    </script>
@endsection