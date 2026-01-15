<?php

$m = Session::get('run_company');

$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$export=ReuseableCode::check_rights(231);


$view=ReuseableCode::check_rights(104);
$edit=ReuseableCode::check_rights(105);
$delete=ReuseableCode::check_rights(106);
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <?php
    $data=DB::Connection('mysql2')->select('select username,COUNT(username)countt from sales_order where status=1 GROUP by username order by countt desc');

    ?>
    <div class="lineHeight">&nbsp;</div>
    <div class="row container-fluid">

        <?php  foreach($data as $row): ?>
        {{--<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="border: solid 1px #ccc">--}}
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="border: solid 1px #ccc">
            <p>{{strtoupper($row->username)}} <span class="badge badge-primary">&nbsp;{{' '.$row->countt}}</span></p>
        </div>
        <?php endforeach;?>
    </div>
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
                            <option value="1">Search By Date</option>
                            <option value="2">Search By SO</option>
                            <option value="3">Search By Buyer</option>
                        </select>
                        </div>
                        {{--<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search by Date) <input type="radio" class="form-control" name="FilterType" value="1" onclick="RadioChange()"></label>--}}
                            {{--<label for="">OR</label>--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search By SO No) <input type="radio" class="form-control" name="FilterType" value="2" onclick="RadioChange()"></label>--}}
                            {{--<label for="">OR</label>--}}
                            {{--<label style="border: solid 1px; border-radius: 10px;">(Search By Buyer) <input type="radio" class="form-control" name="FilterType" value="3" onclick="RadioChange()"></label>--}}
                        {{--</div>--}}


                        <span id="ShowHideDate" style="display: none">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  value="<?php echo $currentMonthStartDate;?>" class="form-control" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>"/>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php echo $AccYearTo?>" value="<?php echo $currentMonthEndDate?>" class="form-control" min="<?php echo $AccYearFrom?>"  />
                                </div>

                                <div style="margin-top: 40px" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="radio-inline"><input value="1" type="radio" name="optradio">Open</label>
                                <label class="radio-inline"><input value="2" type="radio" name="optradio">Partial</label>
                                <label class="radio-inline"><input value="3" type="radio" name="optradio">Complete</label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                        </span>
                        <span id="ShowHideSoNo" style="display: none">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>So No</label>
                                <input type="text" name="SoNo" id="SoNo" class="form-control" placeholder="SO NO"  />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </span>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" id="ShowHideBuyer" style="display: none">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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


                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </div>



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
                                            <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
                                            <th class="text-center col-sm-1">Buyer's Order NO </th>
                                            <th class="text-center col-sm-1">Order Date</th>
                                            <th class="text-center">Customer</th>

                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">SO Status</th>
                                            <th class="text-center">Approval Status</th>
                                            <th class="text-center">Action</th>
                                            {{--<th class="text-center">Edit</th>--}}
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $open=0;
                                            $parttial=0;
                                            $complete=0;
                                            ?>

                                            @foreach($sale_order as $row)
                                                <?php $customer=CommonHelper::byers_name($row->buyers_id);
                                                $data=SalesHelper::get_so_amount($row->id);
                                                $dn_data=SalesHelper::get_dn_amount_by_so_id($row->id);

                                                $dn_qty=0;
                                                if (!empty($dn_data->qty)):
                                                    $dn_qty=$dn_data->qty;
                                                endif;
                                                $status='';
                                                $diffrence=round($data->qty-$dn_qty);

                                                 if ($dn_qty==''):
                                                    $status='Open';
                                                     $open++;
                                                        elseif($dn_qty!='' && $diffrence!=0):
                                                        $status='partial';
                                                            $parttial++;
                                                        elseif($diffrence==0):
                                                        $status='Complete';
                                                            $complete++;
                                                        endif;

                                                $Am = DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$row->id)->where('status',1)->sum('amount');
                                                ?>
                                                <tr @if($status=='Open') style="background-color: #fdc8c8" @endif title="{{$row->id}}" id="{{$row->id}}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
                                                    <td class="text-center">{{$row['model_terms_of_payment']}}</td>
                                                    <td class="text-center">{{$row['order_no']}}</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                                                    <td class="text-center">{{$customer->name}}</td>
                                                    <td class="text-right"><?php echo number_format($Am+$row->sales_tax,2);// $total += $data->amount+$data->sales_tax;?></td>

                                                    <td>{{$status}}</td>
                                                    <td id="stat{{ $row->id }}" class="text-center"><?php echo SalesHelper::approval_status($row->so_status)?></td>

                                                    <td class="text-center">
                                                        @if ($view==true)
                                                        <button onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $row->id ?>','View Sales Order')"
                                                                type="button" class="btn btn-success btn-xs">View</button>
                                                        @endif
                                                            @if ($edit==true)
                                                        <a href="{{ URL::asset('sales/EditSalesOrder/'.$row->id.'?m='.$_GET['m']) }}" class="btn btn-warning btn-xs">Edit </a>
                                                            @endif
                                                                @if ($delete==true)
                                                        <button onclick="sale_order_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                                                type="button" class="btn btn-primery btn-xs">Delete</button>
                                                            @endif
                                                    </td>

                                                    {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
                                                </tr>


                                            @endforeach


                                            <tr>
                                                <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
                                                <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
                                                <td class="text-center" colspan="2" style="font-size: 20px;"></td>

                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td colspan="2" style="font-size: 18px;"><strong>Open</strong></td>
                                                <td style="font-size: 18px;"><strong><?php echo $open?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td colspan="2" style="font-size: 18px;"><strong>Partial</strong></td>
                                                <td style="font-size: 18px;"><strong><?php echo $parttial?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td colspan="2" style="font-size: 18px;"><strong>Complete</strong></td>
                                                <td style="font-size: 18px;"><strong><?php echo $complete?></strong></td>
                                            </tr>
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
                    XLSX.writeFile(wb, fn || ('Sales Order <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>

        $(document).ready(function(){
            $('#BuyerId').select2();
            $('.select2-container--default').css('width','100%');
        });
        function RadioChange()
        {
         //   var radioValue = $("input[name='FilterType']:checked").val();
              var   radioValue=$('#filters').val();



            if(radioValue == 1)
            {
                $('#ShowHideDate').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else if(radioValue == 2)
            {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideDate').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else if(radioValue == 3)
            {
                $('#ShowHideBuyer').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideDate').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else if (radioValue == 0)
            {

                $('#ShowHideDate').css('display','none');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }
        }
        function sale_order_delete(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sad/sale_order_delete',
                    type: 'GET',
                    data: {id: id, m:m},
                    success: function (response) {

                        if (response=='0')
                        {
                            alert('Can not Deleted')
                        }

                        else {
                            alert('Deleted');
                            // alert(response);
                            $('#' + id).remove();
                        }



                    }
                });
            }
            else{}
        }


        function delete_record(id)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id},

                    success: function (response) {


                    }
                });
            }
            else{}
        }


        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            var SoNo= $('#SoNo').val();
            var BuyerId= $('#BuyerId').val();
            var radio= $('input[name="optradio"]:checked').val();

            var FilterType=$('#filters').val();

            var m ='<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/getSalesOrderDateWise',
                type: 'Get',
                data: {from: from,to:to,m:m,SoNo:SoNo,FilterType:FilterType,BuyerId:BuyerId,radio:radio},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>

@endsection