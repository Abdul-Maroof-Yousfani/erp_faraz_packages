<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(381);
$edit=ReuseableCode::check_rights(382);
$delete=ReuseableCode::check_rights(383);
$issuence=ReuseableCode::check_rights(384);
$return=ReuseableCode::check_rights(385);
$conversion_rights=ReuseableCode::check_rights(386);
$from = date('Y-m-01');
$to   = date('Y-m-t');

//$acc_year_data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
//$from=$acc_year_data[0];

?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Production Plan List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">



                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>From </label>
                                    <input name="from" id="from" class="form-control" type="date" max="" min="<?php ?>"  required="required" value="{{ $from  }}" />

                                </div>


                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>To </label>
                                    <input name="to_date" id="to_date" class="form-control" type="date" max="" min="<?php ?>"  required="required" value="{{ $to  }}" />

                                </div>


                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label> Status </label>
                                    <select class="form-control" id="status">
                                        <option value="0">All</option>
                                        <option value="Planned">Planned</option>
                                        <option value="Verified">Verified</option>
                                        <option value="Release">Release</option>
                                        <option value="Complete">Complete</option>
                                        <option value="Approved">Approved</option>
                                    </select>

                                </div>



                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label> &nbsp; &nbsp;  </label>
                                    <button onclick="data()" type="button" class="btn btn-sm btn-primary" style="margin: 30px 0px 0px 0px;">Submit</button>

                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">PPC No</th>
                                                            <th class="text-center">Voucher Date</th>
                                                            <th class="text-center">Start Date</th>
                                                            <th class="text-center">End Date</th>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">User</th>
                                                            <th class="text-center">View</th>
                                                            </thead>
                                                            <tbody id="res">
                                                            <?php
                                                            $count = 1;

                                                            foreach($data as $row):
                                                                  $status=  ProductionHelper::check_product_id('production_plane_issuence',$row->id,'main_id');
                                                                  $conversion=       ProductionHelper::check_conversion($row->id);
                                                                  $cost=       ProductionHelper::check_cost($row->id);
                                                                  $issuence_data=       ProductionHelper::check_production_plan_issuence($row->id);
                                                                  $ppc_completion_data=ProductionHelper::get_completion_date($row->order_no);



                                                            ?>
                                                            <tr class="text-center" id="RemoveTr<?php echo $row->id?>">
                                                                <td><?php echo $count++;?></td>
                                                                <td><?php echo strtoupper($row->order_no)?></td>
                                                                <td>{{CommonHelper::changeDateFormat($row->order_date)}}</td>
                                                                <td>{{CommonHelper::changeDateFormat($row->start_date)}}</td>
                                                                <td>{{CommonHelper::changeDateFormat($row->end_date)}}</td>

                                                                <td>@if($row->type==1) Standard @else Make To Order @endif</td>
                                                                <td>
                                                                   @if(!empty($ppc_completion_data->v_date))
                                                                        Approved
                                                                        <br> <b> {{CommonHelper::changeDateFormat($ppc_completion_data->v_date)}} </b>

                                                                       @else
                                                                    @if($status==0) Planned @else @if($conversion>0) @if($cost>0) Verified @else Complete @endif @else Release @endif @endif
                                                                    @endif
                                                                </td>
                                                                <td>{{ucfirst($row->usernmae)}}</td>

                                                                <td>
                                                                    <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/view_plan?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Production Plan')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                    <?php endif;?>


                                                                        <?php if($view == true):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/view_issuence?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','View Production Plan')"   type="button" class="btn btn-success btn-xs">View Issuence</button>
                                                                        <?php endif;?>

                                                                    <?php if($edit == true && $conversion==0 && $issuence_data==false):?>
                                                                        <a href="<?php echo URL::asset('production/edit_production_plane?edit_id='.$row->id);?>" class="btn btn-xs btn-primary">Edit</a>
                                                                    <?php endif;?>
                                                                    <?php if($delete == true  && $conversion==0 && $issuence_data==false):?>
                                                                        <button onclick="DeletePlane('<?php echo $row->id?>','<?php echo Session::get('run_company') ?>')" type="button" class="btn btn-danger btn-xs">Delete</button>
                                                                    <?php endif;?>
                                                                    <?php if($issuence == true && $conversion==0):?>
                                                                        <button onclick="showDetailModelOneParamerter('production/ppc_issue_item?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Issue Item')"   type="button" class="btn btn-primary btn-xs">Issuance</button>
                                                                    <?php endif;?>
                                                                    <?php if($return == true && $conversion==0):?>
                                                                       <!-- <button onclick="showDetailModelOneParamerter('production/material_return?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Material Return')"   type="button" class="btn btn-primary btn-xs">Return</button> -->
                                                                    <?php endif;?>
                                                                    <button onclick="showDetailModelOneParamerter('production/make_product_issue_items?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Make Product')"   type="button" class="btn btn-primary btn-xs">Make Product</button>


                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>
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
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        function DeletePlane(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_production_plan',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        if (response=='no')
                        {
                            alert('Can Not Delete');
                        }
                        else
                        {
                            alert('Deleted');
                            $('#RemoveTr'+id).remove();
                        }

                    }
                });
            }
            else{}
        }


        function data()
        {


            var from = $('#from').val();
            var to = $('#to_date').val();
            var status = $('#status').val();
            $('#res').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '{{ url('production/get_production_plan_list') }}',
                    type: 'GET',
                    data: {
                        from: from ,
                        to:to ,
                        status :status
                    },
                    success: function (response)
                    {
                        $('#res').html(response);
                    }
                });


        }
    </script>
@endsection
