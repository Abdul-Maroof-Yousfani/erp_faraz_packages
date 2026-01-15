<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(214);
$edit=ReuseableCode::check_rights(215);
$delete=ReuseableCode::check_rights(216);
$export=ReuseableCode::check_rights(231);
$return=ReuseableCode::check_rights(294);



$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

$count = 1;

$supplier = DB::connection('mysql2')->table('supplier')->where('status',1)->get();
?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">WithHolding Tax</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <button class="btn btn-primary" onclick="printView('PrintPanel','','1')" style="">
                                            <span class="glyphicon glyphicon-print"></span> &nbsp; Print
                                        </button>
                                        <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                                    <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="row align-items-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-sm-3">
                                        <label for="">Supplier</label>
                                        <select  class="form-control" id="supplier">
                                            <option value="">Select Supplier</option>
                                            
                                            @foreach($supplier as $key => $value )
                                                <option value="{{ $value->id}}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">From date</label>
                                        <input id="from_date" value="{{date('Y-m-d')}}" class="form-control" type="date">

                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">To date</label>
                                        <input id="to_date" value="{{date('Y-m-d')}}" class="form-control" type="date">

                                    </div>
                                    
                                    <div class="col-sm-3">
                                            <div class="submit_oldding">
                                                <button class="btn btn-primary" onclick="WithHoldingTax()" id="btn" data-dismiss="modal">Search </button>
                                            </div>
                                    </div>
                                </div>             
                             
                            </div>

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php // Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                        <div class="panel">
                                            <div class="panel-body" id="PrintPanel">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 20px; font-style: oblique; display: none;" id="ShowTitle">
                                                        <b>Cash Payment Voucher List From :<span id="FromShow" style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span> Between To <span style="color: red" id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span> </b>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="table table-bordered sf-table-list" id="TableExportToCsv">
                                                                <thead>

                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Taxpayer NTN</th>
                                                                <th class="text-center">Payment Date</th>
                                                                <th class="text-center">Taxpayer CNIC</th>
                                                                <th class="text-center">Taxpayer Name</th>
                                                                <th class="text-center">Taxpayer City</th>
                                                                <th class="text-center">Taxpayer Address</th>
                                                                <th class="text-center">Taxpayer Status</th>
                                                                <th class="text-center">Taxpayer Business Name</th>
                                                                <th class="text-center">Taxable Amount</th>
                                                                <th class="text-center">Tax Amount</th>

                                                                </thead>
                                                                <tbody id="data">
                                                                   
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                
                                                </div>
                                            </div>
                                        </div>
                                        <?php // Form::close();?>
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
            var elt = document.getElementById('TableExportToCsv');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Payment <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
        });

        function DeletePvActivity(pv_id,pv_no,pv_date,pv_amount)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this Voucher...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeletePVoucherActivity',
                    type: "GET",
                    data: {
                        pv_id:pv_id,
                        pv_no:pv_no,
                        pv_date:pv_date,
                        pv_amount:pv_amount
                    },
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr"+pv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }
        function VoucherReturn(pv_id,pv_no,pv_date,pv_amount,cheque_no)
        {
            swal({
                title: "Payment Return",
                text: "Return Detail:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputAttributes: {
                    id: "return_detail",
                },
                inputPlaceholder: "Type Return Detail"
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }

                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/payment_return',
                    type: "GET",
                    data: {
                        pv_id: pv_id,
                        pv_no: pv_no,
                        pv_date: pv_date,
                        pv_amount: pv_amount,
                        cheque_no: cheque_no,
                        Description: inputValue
                    },
                    success: function (data) {
                        swal("Payment Return Successfully", "You wrote: " + inputValue, "success");
                        $(".tr" + pv_id).remove();

                    }
                });

            });

            return false;
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
//            if (confirm('Are you sure you want to return this payment...?'))
//            {
//
//                var Description = window.prompt("Return Detail Here", "");
//
//                if(Description == null || Description == ''){
//                    alert('YES');
//                    return false;
//
//
//                }
//            }


        }
    </script>

    <script>

        function checkUncheck(chkbox,rowid){

            if ($('#'+chkbox).is(':checked'))
            {
                $('#'+chkbox).prop('checked',false);
                $('#'+rowid).removeClass("bg-info");
            } else {
                $('#'+chkbox).prop('checked',true);
                $('#'+rowid).addClass("bg-info");
            }
            var Len = $('input[name="checkbox[]"]:checked').length;
            if(Len>0)
            {$('#BtnApproved').prop('disabled',false);}
            else{$('#BtnApproved').prop('disabled',true);}


        }

        function GetOutstandingpvsDateAndAccontWise()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var VoucherStatus = $('#VoucherStatus').val();

            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/fdc/getOutstandingpvsDateAndAccontWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,AccountId:AccountId,VoucherStatus:VoucherStatus,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }
        GetOutstandingpvsDateAndAccontWise();


        

        $(document).ready(function(){
            WithHoldingTax();
        });
         function WithHoldingTax()
        {

            let supplier = $('#supplier').val();
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/finance/WithHoldingTax/',
                type: 'Get',
                data: {
                        supplier:supplier,
                        from_date:from_date,
                        to_date:to_date
                    },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>
@endsection
