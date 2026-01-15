
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$m = $_GET['m']
?>



<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">View Mould Detail</span>
                </div>

            </div>


            <?php   $name=DB::Connection('mysql2')->table('production_mold')->where('id',Request::get('id'))->select('mold_name')->value('mold_name'); ?>


            <div class="">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list">
                        <thead>

                        <tr class="text-center">
                            <th colspan="4" class="text-center"><h3>(<?php echo $name?>)</h3> </th>
                        </tr>


                        <tr>
                            <th class="text-center" style="">Bacth Code</th>
                            <th class="text-center" style="">Life in (Pieces)</th>
                            <th class="text-center">Depreciable Cost<span class="rflabelsteric"><strong>*</strong></span></th>
                            <th class="text-center">Depreciation per Piece<span class="rflabelsteric"><strong>*</strong></span></th>
                        </tr>
                        </thead>
                        <input type="hidden" name="mould_id" value="{{Request::get('id')}}"/>
                        <tbody id="append">

                        <?php $data=DB::Connection('mysql2')->table('mould_detail')->where('mould_id',Request::get('id')); ?>
                        <?php $count=1; ?>
                        @if ($data->count()>0)

                            @foreach($data->get() as $row)

                                <tr class="text-center AutoNo">
                                    <td>{{$row->batch_code}}</td>
                                    <td>{{$row->life}}</td>
                                    <td><?php echo $row->value?></td>
                                    <td><?php echo $row->cost?></td>
                                </tr>
                                <?php $count++; ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

