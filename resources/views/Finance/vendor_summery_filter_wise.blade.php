<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(252);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}



$data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
$from=$data[0];

$Date = date('d-M-Y');
?>

@extends('layouts.default')

@section('content')
    @include('select2')

    <style>
.vsm-page-wrap{padding:1rem;}
.vsm-card{background:#fff;border-radius:0.75rem;/* rounded-xl */
 box-shadow:0 1px 3px rgba(0,0,0,0.08),0 1px 2px rgba(0,0,0,0.04);padding:1.5rem;}
.vsm-card-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;padding-bottom:1rem;border-bottom:1px solid #E5E7EB;margin-bottom:1.25rem;}
.vsm-title{font-size:1.25rem;font-weight:700;color:#1E1B4B;/* navy */
 margin:0;}
.vsm-btn-export{background:linear-gradient(135deg,#FDBA74,#FB923C);/* orange gradient */
 border:none;color:#fff;font-weight:600;padding:0.6rem 1.25rem;border-radius:0.5rem;display:inline-flex;align-items:center;gap:0.4rem;cursor:pointer;transition:opacity 0.15s ease;}
.vsm-btn-export:hover{opacity:0.9;color:#fff;}
.vsm-filter-row{display:flex;align-items:flex-end;flex-wrap:wrap;gap:1.25rem;}
.vsm-field{display:flex;flex-direction:column;gap:0.35rem;}
.vsm-field label{font-size:0.8rem;font-weight:600;color:#6B7280;margin:0;}
.vsm-field input.form-control{border:1px solid #E5E7EB;border-radius:0.5rem;padding:0.55rem 0.85rem;font-size:0.9rem;min-width:200px;background:#F9FAFB;margin:0 !important;}
.vsm-field input.form-control:focus{outline:none;border-color:#6366F1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,0.15);}
.vsm-btn-submit{background: linear-gradient(90deg, var(--erp-navy-1) 0%, var(--erp-navy-2) 100%) !important;/* indigo-900 */
 border:none;color:#fff;font-weight:600;padding:0.6rem 1.75rem;border-radius:0.5rem;cursor:pointer;transition:background-color 0.15s ease;}
.vsm-btn-submit:hover{background:#4338CA;color:#fff;}

    </style>

<div class="well_N">
     
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="vsm-page-wrap">
                        <div class="vsm-card">
                            <div class="vsm-card-header">
                                <h2 class="vsm-title">Vendor Summary</h2>
                                <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="vsm-btn-export" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>

                            <div class="vsm-filter-row">
                                <div class="vsm-field" style="display:none;">
                                    <label>From Date</label>
                                    <input type="Date" name="FromDate" id="FromDate" max="<?php ?>" value="<?php echo $from;?>" class="form-control" />
                                </div>

                                <div class="vsm-field">
                                    <label>As On</label>
                                    <input type="Date" name="ToDate" id="ToDate" max="<?php  ?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                </div>

                                <div class="vsm-field">
                                    <button type="button" class="vsm-btn-submit" onclick="vendor_summery();">Submit</button>
                                </div>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        <div id="vendor_summery_append"></div>
                    </div>
                </div>
            </div>
        </div>
    
</div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('export_table_to_excel_1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Vendor Summary <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });


        function vendor_summery() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#vendor_summery_append').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/vendor_summery',
                method:'GET',
                data:{FromDate:FromDate,ToDate:ToDate,m:m},
                error: function(){
                    alert('error');
                },
                success: function(response)
                {

                        $('#vendor_summery_append').html(response);

                }
            });
        }

    </script>
@endsection