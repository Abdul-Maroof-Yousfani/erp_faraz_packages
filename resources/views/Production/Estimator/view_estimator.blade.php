<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


use App\Helpers\SalesHelper;

?>


<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Production Estimator </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td>Estimate Date</td>
                            <td class="text-center"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));?></td>
                        </tr>

                        <tr>
                            <td>Estimate User Name</td>
                            <td class="text-center"><?php echo ucfirst(Auth::user()->name);?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>






            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <th class="text-center">S.No</th>
                        <th style="width: 250px" class="text-center">Finish Goods</th>

                        <th class="text-center">Direct Material</th>
                        <th class="text-center">Indirect Material</th>
                        <th class="text-center">Direct Labour</th>
                        <th class="text-center">Die & Mould</th>
                        <th class="text-center">Machine Cost</th>
                        <th class="text-center">FOH</th>
                        <th class="text-center">Cost per Piece</th>
                        <th class="text-center">Remarks</th>
                        </thead>

                        <tbody>
                        <?php
                        $data=Request::get('finish');
                        $count=1;
                        ?>

                        @foreach($data as $key => $row)
                            @if($row!='')
                                <tr class="text-center">
                          <td>{{ $count++ }}</td>
                          <td>{{ CommonHelper::get_item_name($row) }}</td>
                          <td>{{ number_format(Request::get('direct')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('indirect')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('direct_labour')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('die_mould')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('machine')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('foh')[$key],2) }}</td>
                          <td>{{ number_format(Request::get('per_piece')[$key],2) }}</td>
                          <td>{{ Request::get('des')[$key] }}</td>
                                </tr>
                        @endif
                        @endforeach

                        </tbody>

                    </table>



                </div>
            </div>



        </div>
    </div>
</div>

<script>
    function get_vouchers(voucher_no)
    {

        if($('#vouchers').is(':checked'))
        {
            var id=0;
            $('#data').html('<div class="loader"></div>');
            $.ajax({

                url:'{{url('production/get_ledger_data')}}',
                type:'GET',
                data:{voucher_no:voucher_no,id:id},
                success:function(response){
                    $('#data').html(response);
                },
                err:function(err){
                    $('#data').html(err);
                }
            })
        }

    }
</script>



