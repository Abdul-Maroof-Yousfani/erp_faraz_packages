<?php


use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(275);



$data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style>
        .vo-card{
            background:#fff;
            border-radius:14px;
            box-shadow:0 2px 10px rgba(0,0,0,0.06);
            padding:24px 28px;
            margin:20px;
        }
        .vo-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            border-bottom:1px solid #eef0f4;
            padding-bottom:16px;
            margin-bottom:20px;
        }
        .vo-title{
            font-size:20px;
            font-weight:700;
            color:#1b2559;
            margin:0;
        }
        .vo-actions{
            display:flex;
            gap:10px;
        }
        .btn-vo-print{
            background:#1b2559;
            border:none;
            color:#fff;
            font-weight:600;
            padding:9px 18px;
            border-radius:8px;
        }
        .btn-vo-print:hover{ background:#141b45; color:#fff; }
        .btn-vo-export{
            background:#f5a623;
            border:none;
            color:#fff;
            font-weight:600;
            padding:9px 18px;
            border-radius:8px;
        }
        .btn-vo-export:hover{ background:#e0921a; color:#fff; }
        .vo-filters{
            display:flex;
            align-items:flex-end;
            gap:24px;
            flex-wrap:wrap;
        }
        .vo-field label{
            display:block;
            font-size:13px;
            color:#6b7280;
            margin-bottom:6px;
            font-weight:500;
        }
        .vo-field .form-control{
            border-radius:8px;
            border:1px solid #e2e5ec;
            background:#f8f9fc;
            min-width:220px;
            height:42px;
        }
        .vo-field.vo-vendor .form-control{
            min-width:320px;
        }
        .btn-vo-submit{
            background:#1b2559;
            border:none;
            color:#fff;
            font-weight:600;
            padding:10px 26px;
            border-radius:8px;
            height:42px;
        }
        .btn-vo-submit:hover{ background:#141b45; color:#fff; }
        .vo-table-wrap{
            margin-top:24px;
        }
    </style>

    <?php if($export == true):?>
    <a id="dlink" style="display:none;"></a>
    <?php endif;?>

    <div class="vo-card">
        <div class="vo-header">
            <h3 class="vo-title">Vendor Outstanding</h3>
            <div class="vo-actions">
                <button class="btn btn-vo-print" onclick="printView('PrintEmpExitInterviewList','','1')">
                    <span class="glyphicon glyphicon-print"></span> Print
                </button>
                <?php if($export == true):?>
                <button type="button" class="btn btn-vo-export" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                <?php endif;?>
            </div>
        </div>

        <div class="vo-filters">
            <div class="vo-field" style="display:none">
                <label>From Date</label>
                <input type="Date" name="from" id="from" value="<?php echo $data[0];?>" class="form-control" />
            </div>

            <div class="vo-field">
                <label>As On</label>
                <input type="Date" name="to" id="to" value="<?php echo date('Y-m-d')?>" class="form-control" />
            </div>

            <div class="vo-field vo-vendor">
                <label>Vendor Name</label>
                <select onchange="" name="vendor" id="vendor" class="form-control select2">
                    <option value="0">All</option>
                    <?php foreach(CommonHelper::get_all_supplier() as $row):?>
                    <option value="<?php echo $row->id?>"><?php echo $row->name?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="vo-field">
                <input type="button" value="Submit" class="btn btn-vo-submit" onclick="get_data()" />
            </div>
        </div>

        <div class="vo-table-wrap">
            <div class="panel-body" id="PrintEmpExitInterviewList" style="padding:0;">
                <div class="table-responsive" id="data"></div>
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
                    XLSX.writeFile(wb, fn || ('Vendor Outstanding <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
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
                url: '/pdc/vendor_outstanding_data',
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