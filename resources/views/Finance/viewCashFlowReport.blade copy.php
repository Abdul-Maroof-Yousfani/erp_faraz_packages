<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(248);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m =Session::get('run_company');
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>


<script>

    function show()
    {
        var from = $('#from_datee').val();
        var acc_id = $('#acc_id').val();


        var  m = '<?php echo $company_id;?>';
        //alert(m);
        var to=	$('#to_date').val();
        //var url='< ?php echo base_url('finance_data_call/trial'); ?>';

        if(from > to )
        {
            alert('please select correct date');
            return
        }

        if(from !="" && to != "" ) {


            $('#trial_bal').html('<div class="loader"></div>');
            $('#Error').html("");

            $.ajax({
                url: '<?php echo url('/');?>/finance/viewCashFlow',
                type: 'GET',
                data: {from: from, to: to, acc_id: acc_id,m:m},
                success: function (response) {

                    var v = $.trim(response);

                    $('#trial_bal').html(response);
                    $('#OtherArea').css('display','block');
                }
            });
        }else{
            $('#Error').html('<p class="text-danger">Select From And To Date</p>')
        }
    }

    function newTabOpen(FromDate,ToDate,AccCode)
    {
        var  m = '<?php echo $company_id;?>';
        var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
        window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode+'&&m='+m, '_blank');
    }





</script>
<script>
    $(document).ready(function(e) {
        $('#print').click(function(){
          
            $("div").removeClass("table-responsive");
            $("div").removeClass("well");
            var content = $("#includes").html()+$("#header").html()+$("#trial_bal").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;
            window.print();
            location.reload();
        })
    });
</script>
@extends('layouts.default')
@section('content')

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">--}}
                    {{--@include('Finance.'.$accType.'financeMenu')--}}
                    {{--</div>--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well_N">
                        <div class="dp_sdw">    
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <span class="subHeadingLabelClass">View Cash Flow Report</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                    <?php // echo CommonHelper::displayExportButton('trial_bal','','1')?>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::open(array('url' => 'fad/addAccountDetail?m='.$m.'','id'=>'chartofaccountForm'));?>
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">

                                                <div class="wrapper1" style="margin-top:5px;">
                                                    <input

                                                            id="from_datee"
                                                            min="<?php echo $AccYearFrom?>"
                                                            max="<?php echo $AccYearTo?>"
                                                            required="required"
                                                            onchange="valid_date('from_date','to_date');"
                                                            name="from"
                                                            class="form-control"
                                                            type="date"
                                                            value="<?php echo $currentMonthStartDate?>"
                                                            />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <date-util format="dd-mm-yyyy"></date-util>
                                                <input name="to"
                                                    class="form-control"
                                                    type="date"
                                                    min="<?php echo $AccYearFrom?>"
                                                    max="<?php echo $AccYearTo?>"
                                                    id="to_date"
                                                    required="required"
                                                    value="<?php echo $currentMonthEndDate?>"
                                                        />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <select class="form-control" name="acc_id" id="acc_id">

                                                        <option value="">Select Account</option>
                                                            @foreach($accounts as $key => $y)
                                                                <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <input type="button" onclick="show()" class="btn btn-sm btn-primary" value="Submit"/>
                                            </div>
                                        </div>
                                        <?php echo Form::close();?>
                                        <span id="Error"></span>
                                    </div>
                                </div>
                                <div class="row">


                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                         
                                        {{--<button class="btn btn-xs btn-primary" id="print">PRINT</button>--}}


                                    </div>

                                </div>
                                <span id="trial_bal">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="view_flow">
                                                <div class="view_header">
                                                    <h2>Zahabiya Chemicals Industries (Pvt.) Ltd.</h2>
                                                    <h3>CASH FLOW</h3>
                                                    <h4>From July XXXX-2024 to XXXX-2024</h4>
                                                </div>
                                                <div class="view_header2">
                                                    <h2>CASH FLOW ACTIVITIES</h2>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement viewcashflow_report"style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">

                                                        <thead>
                                                            <tr>
                                                                <th>Particulars</th>
                                                                <th>Amount</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Opening Balance</td>
                                                                <td></td>
                                                                <td>2000000</td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th>CASH IN-FLOWS (Receipts)</th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Received From Zahabiya Chemicalss account</td>
                                                                <td>2,222</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Received From Client against Sale</td>
                                                                <td>3,333</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Profit on Saving account</td>
                                                                <td>3,333</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Previous Liability by PCPL</td>
                                                                <td>4,444</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Other Income</td>
                                                                <td>5,555</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Equity and Investment</td>
                                                                <td>6,666</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">TOTAL IN-FLOWS</td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">25,553</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Cash And Cash Equivalents Available For Use</td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">2,025,553</td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th>CASH OUT-FLOWS (PAYMENTS)</th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Assets:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Assets /Cars/ Security Deposit</td>
                                                                <td>10,824</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Software</td>
                                                                <td>10,685</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Investment / Stock Exchange</td>
                                                                <td>10,406</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Loan To Employee</td>
                                                                <td>10,225</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Purchases:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Local Purchases </td>
                                                                <td>10,277</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Import Raw Material (Duties + PAD)</td>
                                                                <td>10,332</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">COGS:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Factory Wages and Benefits</td>
                                                                <td>10,955</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Factory R/m and Overall Gen Expenses / Utility / Diesel</td>
                                                                <td>10,190</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Admin Expenses:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Staff Salaries and Benefits</td>
                                                                <td>10,657</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Director's Salary and Benefits</td>
                                                                <td>10,264</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Donation / Zkt / Goat Sadqa</td>
                                                                <td>10,112</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Legal / Subscription / Audit Fee / Insurance</td>
                                                                <td>10,578</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Computer / Internet</td>
                                                                <td>10,320</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Printing and Stationery</td>
                                                                <td>10,769</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Head office Rm /Gen Expenses / Utility/Vehcl Rm</td>
                                                                <td>10,704</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Taxation:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">I.Tax on Purchases</td>
                                                                <td>10,539</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Sales Tax </td>
                                                                <td>10,603</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Salary Tax </td>
                                                                <td>10,109</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Selling and Marketing:</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Exhibition / Seminar/ Awards / Promotional Item </td>
                                                                <td>10,210</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Business Promotions</td>
                                                                <td>10,485</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Travelling and Business Promotion</td>
                                                                <td>10,875</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Miscellaneous</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Miscellaneous + (Bank Charges)</td>
                                                                <td>10,229</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">TOTAL OUT-FLOWS</td>
                                                                <td></td>
                                                                <td>11.4%</td>
                                                                <td>(231,473)</td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th>Ending Balance [Surplus/(Deficit)]</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>1,794,080</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:yellow;">Breakup of Ending Balance</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row">MEEZAN BANK- SA(0759)</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">BANK AL HABIB- CA(30501)</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">HABIB METRO- CA(7281)</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">UNITED BANK LTD (CA 6765)</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">HABIB METRO SA(7281)</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row">Cash In Hand</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>xxxxxx</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align:right;">Total</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>1,794,386</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
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

            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('.SpacesCls').html('');

            }

            var elt = document.getElementById('header-fixed1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });

            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Trial Balance 6th Column <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));

        }




    </script>

@endsection






