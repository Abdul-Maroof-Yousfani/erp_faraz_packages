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
                                <span class="subHeadingLabelClass">Create Delivery Challan</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


                    <hr style="border-color: #ccc">


                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-radius: 7px;background-color: #000;padding: 10px 17px;color: #fff;box-shadow: 2px 2px 7px 0px #000;border: #0000;">(Search by Date) <input type="radio" class="" name="FilterType" value="1" onclick="RadioChange()"></label>
                            <label for="" style="margin-left: 18px;">OR</label>
                            <label style="border-radius: 7px;background-color: #000;padding: 10px 17px;color: #fff;box-shadow: 2px 2px 7px 0px #000;border: #0000;margin-left: 23px;">(Search By SO No) <input type="radio" class="" name="FilterType" value="2" onclick="RadioChange()"></label>
                            <label for="" style="margin-left: 18px;">OR</label>
                            <label style="border-radius: 7px;background-color: #000;padding: 10px 17px;color: #fff;box-shadow: 2px 2px 7px 0px #000;border: #0000;margin-left: 23px;">(Search By Buyer) <input type="radio" class="" name="FilterType" value="3" onclick="RadioChange()"></label>
                        </div>


                        <span id="ShowHideDate" style="display: none">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  value="<?php echo $AccYearFrom;?>" class="form-control" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>"/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php echo $AccYearTo?>" value="<?php echo $AccYearTo?>" class="form-control" min="<?php echo $AccYearFrom?>"  />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="ShowHideBuyer" style="display: none">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label>Buyer</label>
                                <select name="BuyerId" id="BuyerId" class="form-control">
                                    <option value="">Select Buyer</option>
                                    <?php foreach($Customer as $Fil):?>
                                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
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
                                            <th class="text-center col-sm-1">Packing List No</th>                                            
                                            <th class="text-center col-sm-1">SO Date</th>
                                            <th class="text-center col-sm-1">Model Terms Of Payment</th>
                                            <th class="text-center">Customer</th>

                                            <th class="text-center">View</th>
                                            <th class="text-center">Create Delivery Note</th>
                                            {{--<th class="text-center">Edit</th>--}}
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;?>

                                            @foreach($sale_order as $row)
                                                <?php $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->id); ?>
                                                <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
                                                <tr @if ($row->so_type==1) style="background-color: lightyellow" @endif title="{{$row->id}}" id="{{$row->id}}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td title="{{$row->id}}" class="text-center">@if ($row->so_type==0) {{strtoupper($row->so_no)}} @else {{strtoupper($row->so_no.' ('.$row->description.')')}}@endif</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
                                                    <td class="text-center">{{$row['model_terms_of_payment']}}</td>
                                                    <td class="text-center"><?php echo $row->order_no?></td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                                                    <td class="text-center">{{$customer->name}}</td>
                                                  


                                                    <td class="text-center"><button
                                                                onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $row->id ?>','View Sales Order')"
                                                                type="button" class="btn btn-success btn-xs">View</button></td>

                                                    <td class="text-center"><button
                                                                onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"
                                                                type="button" class="btn btn-primery btn-xs">Create Delivery Note</button></td>
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

    <script>
        $(document).ready(function(){
            $('#BuyerId').select2();
            $('.select2-container--default').css('width','100%');
        });
        function RadioChange()
        {
            var radioValue = $("input[name='FilterType']:checked").val();

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
        function delete_record(id)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id},

                    success: function (response) {

                        alert('Deleted');
                        $('#' + id).remove();

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
            var FilterType= $("input[name='FilterType']:checked").val();

            var m ='<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/sdc/getSalesOrderDateWiseForDeliveryChallan',
                type: 'Get',
                data: {from: from,to:to,m:m,SoNo:SoNo,FilterType:FilterType,BuyerId:BuyerId},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

        function delivery_challan(id,packing_id,qc_packing_id,m)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            window.location.href = base_url+'/sales/CreateDeliveryChallan?id='+id+'&&packing_id='+packing_id+'&&qc_packing_id='+qc_packing_id+'&&'+'m='+m;
        }
    </script>

@endsection