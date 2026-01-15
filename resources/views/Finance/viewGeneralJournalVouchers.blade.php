<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(153);
$edit=ReuseableCode::check_rights(154);
$delete=ReuseableCode::check_rights(155);
$export=ReuseableCode::check_rights(224);



$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Session::get('run_company');
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>

@extends('layouts.default')

@section('content')



    <?php

            /*
    $data=DB::Connection('mysql2')->table('stock_inventory')->get();

            foreach($data as $row):
            $count=DB::Connection('mysql2')->table('subitem')->where('item_code',$row->item_code);
            if ($count->count()==0):

            //echo $row->item_code;
            //echo '</br>';
            else:
               $subitem_id= $count->first()->id;
                $amount=str_replace(",","",$row->amount);

                $stock=array
                (
                        'voucher_date'=>date('Y-m-d'),
                        'supplier_id'=>0,
                        'sub_item_id'=>$subitem_id,
                        'batch_code'=>0,
                        'qty'=>$row->qty,
                        'amount'=>$amount,
                        'status'=>1,
                        'warehouse_id'=>$row->location_id,
                        'username'=>'innovative',
                        'created_date'=>date('Y-m-d'),
                        'created_date'=>date('Y-m-d'),
                        'opening'=>1,
                );
                //DB::Connection('mysql2')->table('stock')->insert($stock);

            endif; endforeach
            */

    ?>



    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                        <div class='headquid'>
                        <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">View General Journal List</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintPanel','','1');?>
                                            <?php if($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="FromDate" id="FromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="ToDate" id="ToDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Account Head</label>
                                    <select name="AccountId" id="AccountId" class="form-control select2">
                                        <option value="">Select Account</option>
                                        <?php foreach($accounts as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->code.'=='.$Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Status</label>
                                    <select name="VoucherStatus" id="VoucherStatus" class="form-control">
                                        <option value="">All</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Approved</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Range Wise Data Filter" class="btn btn-sm btn-primary" onclick="GetJvsDateAndAccontWise();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                        <div class="panel">
                                            <div class="panel-body" id="PrintPanel">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 20px; font-style: oblique; display: none;" id="ShowTitle">
                                                        <b>General Journal Voucher List From :<span id="FromShow" style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span> Between To <span style="color: red" id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span> </b>
                                                    </div>
                                                </div>
                                                    <tr>
                                                        <td colspan="7" class="text-center" style="font-size: 20px; font-style: oblique;"></td>
                                                    </tr>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="userlittab table table-bordered sf-table-list" id="journalVoucherList1">
                                                                <thead>

                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Voucher No.</th>
                                                                <th class="text-center">Voucher Date</th>
                                                                <th class="text-center">Debit/Credit</th>
                                                                {{--<th class="text-center">Ref / Bill No.</th>--}}
                                                                <th class="text-center">Description</th>
                                                                <th class="text-center">CHQ No</th>
                                                                <th class="text-center">CHQ Date</th>
                                                                <th class="text-center">Voucher Status</th>
                                                                
                                                                </thead>
                                                                <tbody id="data">
                                                                
                                                                <!-- <tr>
                                                                    <th colspan="8" class="text-center">xxxxx</th>
                                                                </tr> -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        {{--<button type="submit" class="btn btn-sm btn-success" id="BtnApproved" disabled>Approved</button>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo Form::close();?>
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
            var elt = document.getElementById('journalVoucherList1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('J.V <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">


        $(document).ready(function(){
            $('.select2').select2();
        });
        // function DeleteJvActivity(jv_id,jv_no,jv_date,jv_amount)
        // {
        //     //alert(pv_id+pv_no+pv_date+pv_amount); return false;
        //     if (confirm('Are you sure you want to delete this Voucher...?'))
        //     {
        //         var m = '<?php echo $_GET['m'];?>';
        //         $.ajax({
        //             url: '<?php echo url('/')?>/DeleteJVoucherActivity',
        //             type: "GET",
        //             data: {
        //                 jv_id:jv_id,
        //                 jv_no:jv_no,
        //                 jv_date:jv_date,
        //                 jv_amount:jv_amount
        //             },
        //             success:function(data) {
        //                 //alert(data); return false;
        //                 alert('Successfully Deleted');
        //                 $(".tr"+jv_id).remove();
        //                 //return false;
        //                 //    filterVoucherList();
        //             }
        //         });
        //     }


        // }
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

        function GetJvsDateAndAccontWise()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var VoucherStatus = $('#VoucherStatus').val();
            
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/getGJVDateAndAccontWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,VoucherStatus:VoucherStatus,AccountId:AccountId,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }

    </script>
@endsection
