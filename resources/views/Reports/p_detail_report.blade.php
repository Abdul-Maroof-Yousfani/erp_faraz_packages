
<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style>
        element.style {
            width: 183px;
        }
    </style>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label for="email">From Date</label>
                <input id="from_date" required="required" name="from_date" class="date1 form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group ">
                    <label for="email">To Date</label>
                    <input id="to_date" required="required" name="to_date" class="date1 form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group ">
                    <button type="button" class="btn btn-success" onclick="BookDayList();">Submit</button>
                </div>
            </div>

        </div>


        <div>&nbsp;</div>

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
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/stad/p_detail_report',
                method:'GET',
                data:{from_date:from_date, to_date:to_date},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }

    </script>
@endsection