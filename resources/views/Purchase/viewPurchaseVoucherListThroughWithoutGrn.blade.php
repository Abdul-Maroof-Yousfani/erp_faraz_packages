<?php



$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(37);
$edit=ReuseableCode::check_rights(211);
$delete=ReuseableCode::check_rights(38);
$export=ReuseableCode::check_rights(236);




?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">
                   <div class="headquid">
                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h2 class="subHeadingLabelClass">Billing Voucher List</h2>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                        <?php if($export == true):?>
                                    <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>

                   </div>

                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo $first_day_this_month;?>" class="form-control" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $last_day_this_month;?>" class="form-control" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="">Supplier</label>
                            <select name="SupplierId" id="SupplierId" class="form-control select2">
                                <option value="all">ALL</option>
                                <?php foreach($Supplier as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="">Ref No</label>
                                <input type="text" value="" id="ref_no" class="form-control"/>
                        </div>
    
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="">PI No</label>
                                <input type="text" value="" id="pi_no" class="form-control"/>
                        </div>
    
                        <div class="col-lg-2 col-md-2 col-sm-2 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                        </div>

                    </div>
                    <div class="row">
                    </div>

                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list view_purchase_tab " id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">PV No</th>
                                            <th class="text-center col-sm-1">PV Date</th>
                                            <!-- <th class="text-center col-sm-1">GRN No</th> -->
                                            <th class="text-center col-sm-1">Ref  No</th>
                                            <th class="text-center col-sm-1">Bill Date</th>
                                            <th class="text-center col-sm-1">Pv Status</th>
                                            <th class="text-center">Supplier</th>


                                            <th class="text-center">Amount</th>
                                            <!-- <th class="text-center">GRN Amount</th> -->
                                            <th class="text-center">Action</th>

                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;?>

                                            @foreach($purchase_voucher as $row)
                                                <?php
                                               $net_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id',$row->id)->sum('net_amount');
                                                $net_amount_grn= DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->grn_id)->sum('net_amount');
                                                $grn_date= DB::Connection('mysql2')->table('goods_receipt_note')->where('id',$row->grn_id)->value('grn_date');
                                                $t_amount= DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->pv_no)
                                                ->where('debit_credit',1)->sum('amount');
                                                $total+=$net_amount?>
                                                <tr @if($t_amount!=$net_amount) @elseif($net_amount!=$net_amount_grn)  @endif id="{{$row->id}}">
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->pv_no)}}</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->pv_date);?></td>
                                                    <!-- <td title="{{$row->id}}" class="text-center">{{strtoupper($row->grn_no)}}
                                                    </br>
                                                        {{$grn_date}} -->
                                                    </td>
                                                    <td class="text-center">{{$row['slip_no']}}</td>
                                                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                                                    <td id="app{{ $row->id }}" class="text-center text-danger">@if($row->pv_status==1) Pending @elseif($row->pv_status==3) 1st Approve  @else Approved @endif </td>
                                                    <td class="text-center">{{CommonHelper::get_supplier_name($row->supplier)}}</td>

                                                    <td class="text-right">{{number_format($net_amount,2)}}</td>
                                                    <!-- <td class="text-right hide">{{number_format($net_amount_grn,2)}}</td> -->
                                                    <?php $total+=$row['total_net_amount']; ?>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                @if (!$row->tso)
                                                            

                                                            @can('User_List_Edit')
                                                            <a href="{{ route('users.edit', $row->id) }}" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                                                            @endcan
                                                            @can('User_List_Delete')
                                                            <a href="{{ route('users.destroy', $row->id) }}" id="delete-user" class="" href="#"><i class="fa-solid fa-trash"></i> Delete</a>
                                                            @endcan
                                                        
                                                        
                                                            @endif
                                                            @if($view==true)
                                                                
                                                                    @if($row->grn_no == 0)
                                                                            <a onclick="showDetailModelOneParamerter('fdc/viewDirectPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"type="button" class=""><i class="fa-regular fa-eye"></i> View</a>
                                                                    @else
                                                                            <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"  type="button" class=""><i class="fa-regular fa-eye"></i> View</a>
                                                                    @endif
                                                                   
                                                                    <?php if($row->pv_status == 1):?>    
                                                                    @if($edit==true )
                                                                        @if($row->grn_no == '0')
                                                                            <a  href="{{ URL::asset('finance/editDirectPurchaseVoucherForm/'.$row->id.'?m='.$m) }}" class="{{ $row->id }}"><i class="fa-solid fa-pencil"></i> Edit </a>
                                                                        @else
                                                                            <a  href="{{ URL::asset('finance/editPurchaseVoucherFormNew/'.$row->id.'?m='.$m) }}" class=" {{ $row->id }}"><i class="fa-solid fa-pencil"></i> Edit </a>
                                                                        @endif
                                                                    @endif
                                                                    <?php endif;?>
                                                                  
                                                                    <?php if($row->pv_status == 1):?>    
                                                                    @if($delete==true && $row->pv_status != 2)
                                                                        <a  onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" type="button" class="{{ $row->id }}"><i class="fa-solid fa-trash"></i> Delete</a>
                                                                        @else
                                                                        <a  onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" type="button" class=" {{ $row->id }}"><i class="fa-solid fa-trash"></i> Delete</a>
                                                                    @endif
                                                                    <?php endif;?>
                                                                    
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <?php if($row->pv_status == 1):?>
                                                        <?php else:?>
                                                        <?php endif;?>
                                                    </td>
                                                </tr>


                                            @endforeach
                                            <tr>
                                                <td colspan="7">Total</td>
                                                <td class="text-right" >{{number_format($total,2)}}</td>
                                                <td class="text-right" ></td>
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

    <script>
        $(document).ready(function(){
            $('#SupplierId').select2();
        });
        function delete_record(id,grnno,pvno)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id,grnno:grnno,pvno:pvno},

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

            var ref_no= $('#ref_no').val();
            var pi_no= $('#pi_no').val();

            var SupplierId= $('#SupplierId').val();
            var RadioVal = $("input[name='VoucherType']:checked").val()
            var m  = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '{{ url('/pdc/purchase_voucher_list_ajax') }}',
                type: 'Get',
                data: {from: from,to:to,m:m,SupplierId:SupplierId,RadioVal:RadioVal,ref_no:ref_no,pi_no:pi_no},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>

@endsection
