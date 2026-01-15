<?php

$m = Session::get('run_company');

use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well">
        <div class="row">

        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well_N">
                        <div class="dp_sdw">    
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Finish Good Cost History</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('data','','1');?>

                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div class="panel" id="data">
                                        <div class="panel-body">
                                            <div class="row">

                <?php $data=DB::Connection('mysql2')->table('production_plane_data')->where('status',1)->groupBy('finish_goods_id')->get(); ?>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label>Finish Goods </label>

                                                    <select onchange="data()" class="form-control" id="finish">
                                                        <option value="">Select</option>
                                                        @foreach($data as $row)
                                                            <option value="{{ $row->finish_goods_id }}">{{ CommonHelper::get_item_name($row->finish_goods_id) }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

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

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data_table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Finish Goods History <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
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
            var finish=  $('#finish').val();
            $.ajax({
                url: '<?php echo url('/');?>/production/get_finish_goods_history',
                type: 'GET',
                data: {finish:finish,to_date:to_date},
                success: function (response)
                {
                    $('#append').html(response);
                }
            });
        }
        $( document ).ready(function() {
            $('#finish').select2();
            data();
        });
    </script>

@endsection