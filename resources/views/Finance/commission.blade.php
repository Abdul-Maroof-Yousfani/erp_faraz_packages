<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


$current_date = date('Y-m-d');
$from = date('Y-m-01');
$to   = date('Y-m-t');


?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">

                            <div class="lineHeight">&nbsp;</div>

                            <div class="row">
                                <div  class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From </label>
                                    <input class="form-control" type="date" name="from" id="from" value="{{$from}}" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To </label>
                                    <input class="form-control" type="date" name="from" id="to" value="{{$to}}" />
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Sales Agent</label>
                                    <select id="agent" class="form-control select2" onchange="get_commision_data()">
                                            <option>Select</option>
                                        @foreach(CommonHelper::get_all_agent() as $row)
                                            <option value=" {{$row->id.','.$row->type}}">{{$row->agent_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')" style="margin: 30px 0px 0px 0px;">Export <b>(xlsx)</b></button>
                                </div>

                                <span id="data"></span>
                            </div>

                            <div class="lineHeight">&nbsp;

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
            var elt = document.getElementById('TableExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Commission <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>

    <script>
        $( document ).ready(function() {
            $('.select2').select2();
        });
        $(document).on('keypress',function(e) {


            if(e.which == 13) {
                get_commision_data();
            }
        });
        function get_commision_data()
        {
            var agent=$('#agent').val();
            var from=$('#from').val();
        
            var to =$('#to').val();
            $('#data').html('<div class="loader"></div>');
            var me=$(this);
            $.ajax({
                url: '{{url('finance/get_commision_data')}}',
                type: 'get',
                data: {agent:agent,from:from,to:to},
                success: function (response)
                {
                    $('#data').html(response);
                }

            })
        }
    </script>
@endsection
