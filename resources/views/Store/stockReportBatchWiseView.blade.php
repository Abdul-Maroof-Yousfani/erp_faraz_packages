
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(241);
?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')

    <style>
        element.style {
            width: 183px;
        }
    </style>
    <div class="container-fluid">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="display: none;">


                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group ">
                                <label for="email">Category <span style="color: red">*</span></label>
                                <select onchange="get_sub_category(this.id,'sub_category')" name="category_id_1_1" id="category_id_1_1"  class="form-control requiredField select2">
                                    <option>Select</option>
                                    @foreach($category as $row)
                                        <option value="{{$row->id}}">{{$row->main_ic}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group ">
                                <label for="email">Sub Category</label>
                                <select name="sub_category" id="sub_category" class="form-control requiredField select2" width="183px;">


                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group ">
                                <label for="email">Location</label>
                                <select name="location" id="location" class="form-control requiredField select2" width="183px;">
                                    <option value="">Select</option>
                                    @foreach(CommonHelper::get_all_warehouse() as $row):
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group ">
                                <label for="email">Item</label>
                              

                                <select onchange="get_sub_item('category_id1')" name="category" id="sub_1"  class="form-control category select2 normal_width">
                                    <option value="select">Select</option>
                                   @foreach (CommonHelper::get_all_subitem() as $row):
                                    <option value="{{ $row->id }}"> {{ $row->sub_ic }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="display: none" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label for="email">Batch Code</label>
                                <select name="batch_code" id="batch_code" class="form-control requiredField select2" width="183px;">
                                    <option value="">Select</option>
                                    @foreach($batch_code as $row_b):
                                    <option value="{{$row_b->batch_code}}">{{$row_b->batch_code}}</option>
                                    @endforeach

                                </select>
                        </div>



                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div>&nbsp;</div>
                            <button type="button" class="btn btn-sm btn-primary" style="margin: 5px 0px 0px 0px;" onclick="BookDayList();">Submit</button>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                            <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            <?php endif;?>
                        </div>

                    </div>

                </div>






                <div id="printBankReceiptVoucherList">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                                    <?php //echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                                    <div id="filterBookDayList"></div>

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
            var elt = document.getElementById('expToExcel');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Stock Report Batch Wise <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function subItemListLoadDepandentCategoryId(id,value) {
            //alert(id+' --- '+value);
            var arr = id.split('_');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }

    </script>


    <script>

        function BookDayList(){
            var location = $('#location').val();
            var batch_code = $('#batch_code').val();
            var category = $('#category_id_1_1').val();
            var sub_category = $('#sub_category').val();
            var sub_1 = $('#sub_1').val();
            var item_des = $('#item_des').val();
            var m = '<?php echo $_GET['m']?>';
            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_region_wise_batch_wise',
                method:'GET',
                data:{location:location, category:category, batch_code:batch_code,sub_category:sub_category, m:m,sub_1:sub_1,item_des:item_des},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
            //}
        }


        $('.sam_jass').bind("enterKey",function(e){


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e){
            if(e.keyCode == 13)
            {
                selected_id=this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });




    </script>
@endsection