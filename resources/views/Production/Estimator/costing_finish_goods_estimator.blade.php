<?php

$m = Session::get('run_company');

use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


?>
@extends('layouts.default')

@section('content')
    @include('select2')

<div class="well_N">
<div class="dp_sdw">    


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Finish Good Cost Estimator</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">


                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

    <?php echo Form::open(array('url' => 'production/add_estimatore?m='.$m.'','id'=>'createSalesOrder','class'=>'stop'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row" id="dataa">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive" id="append">
                                                        <table id="data_table" class="table table-bordered">
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
                                                            <th class="text-center">View</th>
                                                            </thead>
                                                            <tbody>
                                                            <?php $counter=1; ?>
                                                            @for($i=1; $i<=5; $i++)
                                                            <tr class="text-center">
                                                                <span id="data{{ $i }}"></span>
                                                                <td>{{ $counter++ }}</td>
                                                                <td>
                                                                    <?php // $data=DB::Connection('mysql2')->table('production_plane_data')->where('status',1)->groupBy('finish_goods_id')->get();

                                                                    $data = DB::Connection('mysql2')->table('costing_data as a')
                                                                        ->join('production_plane_data as b','a.production_plan_data_id','=','b.id')
                                                                        ->select('b.finish_goods_id')
                                                                        ->where('a.status', 1)
                                                                        ->groupBy('finish_goods_id')
                                                                        ->get();
                                                                    ?>

                                                                        <select style="width: 100%" onchange="data(this.id,'{{ $i }}')" name="finish[]" class="form-control finish" id="finish{{ $i }}">
                                                                            <option value="">Select</option>
                                                                            @foreach($data as $row)
                                                                                <option value="{{ $row->finish_goods_id }}">{{ CommonHelper::get_item_name($row->finish_goods_id) }}</option>
                                                                            @endforeach
                                                                        </select>


                                                                </td>

                                                                <td><input onkeyup="cal('{{ $i }}')" type="text" name="direct[]" class="form-control" id="direct{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="indirect[]" id="indirect{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="direct_labour[]" id="direct_labour{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="die_mould[]" id="die_mould{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="machine[]" id="machine{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="foh[]" id="foh{{ $i }}"/></td>
                                                                <td><input readonly type="text" class="form-control" name="per_piece[]" id="per_piece{{ $i }}"/></td>
                                                                <td><textarea name="des[]"></textarea></td>
                                                                <td onclick="get_bom('{{ $i }}')" style="cursor: pointer">Link</td>
                                                            </tr>

                                                            @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            {{ Form::submit('Print', ['class' => 'btn btn-success hidee']) }}

                                            <img  style="display: none!important;" class="center showww" src="{{url("/storage/app/uploads/Loading-bar.gif")}}">


                                        </div>

                                    </div>

                                </div>
                            </div>
</div>
</div>                            
    <?php echo Form::close();?>



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

        function  data(id,key)
        {
            $('#data'+key).html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var finish_goods= $('#'+id).val();
            $.ajax({
                url: '<?php echo url('/');?>/production/get_data',
                type: 'GET',
                data: {finish_goods: finish_goods},
                success: function (response)
                {
                   var costing_data = response.split('/');

                   var indirect = (costing_data[0] / costing_data[6]).toFixed(2);
                   var direct_labour = (costing_data[1] / costing_data[6]).toFixed(2);
                   var die_mould = (costing_data[2] / costing_data[6]).toFixed(2);

                   var machine = costing_data[3]*costing_data[6];
                   machine = (machine / costing_data[6]).toFixed(2);

                    var foh = (costing_data[4] / costing_data[6]).toFixed(2);



                   $('#indirect'+key).val(indirect);
                   $('#direct_labour'+key).val(direct_labour);
                   $('#die_mould'+key).val(die_mould);
                   $('#machine'+key).val(machine);
                   $('#foh'+key).val(foh);
                   $('#total'+key).val(costing_data[5]);
                    $('#data'+key).html('');
                    cal(key);
                }
            });
        }


        function cal(key)
        {
            var indirect =parseFloat($('#indirect'+key).val());
            var labour =parseFloat($('#direct_labour'+key).val());
            var die_mould =parseFloat($('#die_mould'+key).val());
            var machine =parseFloat($('#machine'+key).val());
            var foh =parseFloat($('#foh'+key).val());
            var direct =parseFloat($('#direct'+key).val());

          var total=(indirect + labour + die_mould + machine + foh + direct).toFixed(2);
          $('#per_piece'+key).val(total);


        }

        $( document ).ready(function() {
            $('.finish').select2();

        });


        $("#createSalesOrder").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');
            $('.hidee').prop('disabled', true);
            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    $(".hidee").fadeIn(500);
                    $('.hidee').prop('disabled', false);
                    $(".showww").fadeOut(500);
                    $('#showDetailModelOneParamerter').modal('show');
                    $('.modal-body').html(data); // show response from the php script.


                },
                error: function(data, errorThrown)
                {
                    $('.hidee').prop('disabled', false);
                    alert('request failed :'+errorThrown);
                }
            });


        });

        function  get_bom(index)
        {
          var finish=  $('#finish'+index).val();
            showDetailModelOneParamerter('production/viewBomDetail?m&&finish=1<?php echo Session::get('run_company')?>',finish,'View Bill Of Marterial Detail');
        }
    </script>

@endsection