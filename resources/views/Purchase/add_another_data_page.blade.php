<?php

$m = Session::get('run_company');

?>


@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {{--<span class="subHeadingLabelClass">JOB ORDER</span>--}}
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <?php
                                                    $Counter = 1;
                                                    foreach($Data as $Fil):?>
                                                        <?php $Array = explode(',',$Fil->dn_data_ids);?>
                                                        <?php if(count($Array) == 1):?>
                                                        <tr class="text-center">
                                                            <td><?php echo $Counter;?></td>
                                                            <?php $Delivery =  DB::Connection('mysql2')->table('delivery_note')->where('id',$Fil->dn_data_ids)->select('gd_no')->first();?>
                                                            <td><input type="text" class="form-control" name="SiId" id="SiId<?php echo $Counter?>" value="<?php echo $Fil->master_id?>" readonly></td>
                                                            <td><input type="text" class="form-control" name="SiNo" id="SiNo<?php echo $Counter?>" value="<?php echo $Fil->gi_no?>" readonly></td>
                                                            <td><input type="text" class="form-control" name="DnId" id="DnId<?php echo $Counter?>" value="<?php echo $Fil->dn_data_ids?>" readonly></td>
                                                            <td><input type="text" class="form-control" name="DnNo" id="DnNo<?php echo $Counter?>" value="<?php echo $Delivery->gd_no?>" readonly></td>
                                                            <td id="HideBtn<?php echo $Counter?>">
                                                                <button type="button" class="btn btn-sm btn-primary" id="BtnSave<?php echo $Counter?>" onclick="SaveData('<?php echo $Counter?>')">Save</button>
                                                            </td>

                                                            <td><?php echo $Fil->dn_data_ids;?></td>
                                                        </tr>
                                                        <?php
                                                        $Counter++;
                                                        else:?>
                                                            <?php foreach($Array as $Af):?>
                                                                <tr bgcolor="#deb887">
                                                                    <?php $DeliverySC =  DB::Connection('mysql2')->table('delivery_note')->where('id',$Af)->select('gd_no')->first();?>
                                                                    <td><?php echo $Counter;?></td>
                                                                    <td><input type="text" class="form-control" name="SiId" id="SiId<?php echo $Counter?>" value="<?php echo $Fil->master_id?>" readonly></td>
                                                                    <td><input type="text" class="form-control" name="SiNo" id="SiNo<?php echo $Counter?>" value="<?php echo $Fil->gi_no?>" readonly></td>
                                                                    <td><input type="text" class="form-control" name="DnId" id="DnId<?php echo $Counter?>" value="<?php echo $Af?>" readonly></td>
                                                                    <td><input type="text" class="form-control" name="DnNo" id="DnNo<?php echo $Counter?>" value="<?php echo $DeliverySC->gd_no?>" readonly></td>
                                                                        <td id="HideBtn<?php echo $Counter?>">
                                                                            <button type="button" class="btn btn-sm btn-primary" id="BtnSave<?php echo $Counter?>" onclick="SaveData('<?php echo $Counter?>')">Save</button>
                                                                        </td>
                                                                </tr>
                                                            <?php
                                                            $Counter++;
                                                            endforeach;?>
                                                        <?php endif;?>
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

    <script>
        function SaveData(Counter)
        {
            var SiId = $('#SiId'+Counter).val();
            var SiNo = $('#SiNo'+Counter).val();
            var DnId = $('#DnId'+Counter).val();
            var DnNo = $('#DnNo'+Counter).val();


            $.ajax({
                url:'{{url('/pdc/get_data')}}',
                data:{SiId:SiId,SiNo:SiNo,DnId:DnId,DnNo:DnNo},
                type:'GET',
                success:function(response)
                {
                    if(response == 'yes')
                    {
                        $('#')
                    }
                }
            });
        }
    </script>

@endsection