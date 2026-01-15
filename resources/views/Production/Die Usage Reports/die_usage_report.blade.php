<?php

$m = Session::get('run_company');

use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


?>
@extends('layouts.default')

@section('content')

    <div class="well">
        <div class="row">

        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Die Usage</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('data','','1');?>

                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div class="panel" id="data">
                                        <div class="panel-body">
                                            <div class="row">


                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>As On </label>
                                                    <input name="to_date" id="to_date" class="form-control" type="date" max="" min="<?php ?>"  required="required" value="{{ date('Y-m-d')  }}" />

                                                </div>



                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label> &nbsp; &nbsp;  </label>
                                                    <button onclick="data()" type="button" class="btn btn-sm btn-primary" style="margin: 30px 0px 0px 0px;">Submit</button>

                                                </div>

                                                <br>
                                                <br>
                                                <br>


                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive" id="append">
                                                        <table id="data_table" class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>

                                                            <th class="text-center">Die</th>
                                                            <th class="text-center">Size</th>
                                                            <th class="text-center">Batch Code</th>
                                                            <th class="text-center">Life in Pieces</th>
                                                            <th class="text-center">Depreciable Cost</th>
                                                            <th class="text-center">Produce Qty</th>
                                                            <th class="text-center">Total Depreciation Amount</th>
                                                            </thead>
                                                            <tbody>
                                 <?php
                                $count=1;
                                 $data=DB::Connection('mysql2')->select('select * from die_usage_report where status=1 group by die_id,batch_code_id');

                                 $total_dep=0;
                                 $total_produce=0;
                                 $total_dep_amount=0;
                                 $total_life=0;
                                 ?>

                                                            @foreach($data as $row)

                                                            <?php
                                                            $die=ProductionHelper::get_die($row->die_id);
                                                            $die_name=$die->dai_name;
                                                            $size=$die->size;

                                                          $detail=  ProductionHelper::get_die_bacth_code($row->batch_code_id);
                                                          $batch_code=$detail->batch_code;
                                                          $life=$detail->life;
                                                          $value=$detail->value;




                                                            $ppc_id=  DB::Connection('mysql2')->table('die_usage_report as a')
                                                                ->join('production_plane as b','a.production_plan_id','=','b.id')
                                                                ->join('transactions as c','b.order_no','=','c.voucher_no')
                                                                ->where('a.die_id',$row->die_id)
                                                                ->where('c.voucher_type',19)
                                                                ->where('c.status',1)
                                                                ->where('b.status',1)
                                                                ->where('a.batch_code_id',$row->batch_code_id)
                                                                ->select(DB::raw('DISTINCT(a.production_plan_data_id)'))
                                                                ->get();



                                                            $total_amount=0;
                                                        foreach ($ppc_id as $row1):
                                                         $total_amount+=$ppc_id=  DB::Connection('mysql2')->table('production_plane_data')->
                                                         where('id',$row1->production_plan_data_id)->value('planned_qty');
                                                         endforeach;

                                                            ?>
                                                             <tr class="text-center">
                                                                 <td>{{ $count++.' '.$row1->production_plan_data_id }}</td>

                                                                 <td><?php echo  $die_name ?></td>
                                                                 <td><?php echo  $size ?></td>
                                                                 <td><?php echo  $batch_code?></td>
                                                                 <td><?php echo  $life?></td>
                                                                 <td><?php echo  number_format($value,2) ?></td>
                                                                 <td><?php echo  number_format($total_amount,2) ?></td>
                                                                 <td><?php echo  number_format(($value / $life)*$total_amount,2) ?></td>
                                                             </tr>

                                                                <?php

                                                              $total=  ($value / $life)*$total_amount;

                                                                $total_dep+=$value;
                                                                $total_produce+=$total_amount;
                                                                $total_dep_amount+=$total;
                                                                $total_life+=$life;

                                                                ?>
                                                            @endforeach
                                                      <tr class="text-center" style="font-size: large;font-weight: bold">
                                                          <td colspan="4"> Total</td>
                                                          <td><?php echo number_format($total_life,2) ?></td>
                                                          <td><?php echo number_format($total_dep,2) ?></td>
                                                          <td><?php echo number_format($total_produce,2) ?></td>
                                                          <td><?php echo number_format($total_dep_amount,2) ?></td>
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
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data_table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Die Usage <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        function DeleteBom(id,m)
        {
            //alert(id); return false;
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/prd/delete_bom',
                    type: 'GET',
                    data: {id: id},
                    success: function (response)
                    {
                        if (response=='no')
                        {
                            alert('Can not Delete');
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

        function  data()
        {
            $('#append').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var to_date=  $('#to_date').val();
            $.ajax({
                url: '<?php echo url('/');?>/production/die_mould_usage_report',
                type: 'GET',
                data: {to_date: to_date},
                success: function (response)
                {
                    $('#append').html(response);
                }
            });
        }


    </script>

@endsection