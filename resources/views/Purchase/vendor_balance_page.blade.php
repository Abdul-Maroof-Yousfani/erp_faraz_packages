<?php


use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(302);



$data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style>
 .vb-wrapper{padding:20px;}
.vb-card{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,0.06);padding:26px 30px;margin-bottom:24px;}
.vb-top-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.vb-page-title{font-weight:700;color:#1f2a5c;margin:0;font-size:22px;}
.vb-btn-row{display:flex;align-items:center;gap:10px;}
.vb-btn-row .btn{border-radius:6px;font-weight:600;}
.vb-divider{border:none;border-top:1px solid #edeef2;margin:0 0 22px 0;}
.vb-filter-row{display:flex;flex-wrap:wrap;align-items:flex-end;gap:24px;}
.vb-filter-group{display:flex;flex-direction:column;min-width:150px;}
.vb-filter-group.wide{min-width:260px;flex:1;}
.vb-filter-group label{font-size:13px;font-weight:500;color:#6b7080;margin-bottom:8px;}
.vb-filter-group .form-control{border-radius:8px;border:1px solid #e2e4ea;background:#fafbfc;height:42px;margin:0;}
.vb-submit-btn{border-radius:8px;background:#1f2a5c;border-color:#1f2a5c;font-weight:600;height:42px;padding:0 24px;}

    </style>
<div class="well_N">
    <div class="vb-wrapper">

        <div class="vb-card">

            <div class="vb-top-row">
                <h3 class="vb-page-title">Vendor Balance</h3>
                <div class="vb-btn-row">
                    <button class="btn btn-primary" onclick="printView('PrintEmpExitInterviewList','','1')">
                        <span class="glyphicon glyphicon-print"></span> Print
                    </button>
                    <?php if($export == true):?>
                    <a id="dlink"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                    <?php endif;?>
                </div>
            </div>

            <hr class="vb-divider">

            <div class="vb-filter-row">
                <div class="vb-filter-group">
                    <label for="from">From Date</label>
                    <input type="Date" name="from" id="from" value="<?php echo '2017-07-01';?>" class="form-control"/>
                </div>

                <div class="vb-filter-group">
                    <label for="to">As On</label>
                    <input type="Date" name="to" id="to" value="<?php echo date('Y-m-d')?>" class="form-control"/>
                </div>

                <div class="vb-filter-group wide">
                    <label for="vendor">Vendor Name</label>
                    <select onchange="" name="vendor" id="vendor" class="form-control select2">
                        <option value="0">All</option>
                        <?php foreach(CommonHelper::get_all_supplier() as $row):?>
                        <option value="<?php echo $row->id?>"><?php echo $row->name?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="vb-filter-group">
                    <label>&nbsp;</label>
                    <input type="button" value="Submit" class="btn btn-primary vb-submit-btn" onclick="get_data()" />
                </div>
            </div>

        </div>

        <div class="panel">
            <div class="panel-body" id="PrintEmpExitInterviewList">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive" id="data">

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
            var elt = document.getElementById('MultiExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Vendor Balance <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });




        function get_data()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            var vendor= $('#vendor').val();
            $('#data').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/pdc/vendor_balance_ajax_data',
                type: 'Get',
                data: {from: from,to:to,vendor:vendor},

                success: function (response)
                {

                    $('#data').html(response);


                }
            });


        }


    </script>

@endsection