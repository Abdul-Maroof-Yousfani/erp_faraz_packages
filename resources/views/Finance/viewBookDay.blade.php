@extends('layouts.default')
@section('content')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="form-inline">
                <div class="form-group">
                    <label for="email">Date</label>
                    <input autofocus type="date" class="form-control" value="{{date('Y-m-d')}}" id="date">
                </div>
                <button type="button" class="btn btn-default" onclick="BookDayList();">Submit</button>

            </div>
            <div>&nbsp;</div>

            <div id="printBankReceiptVoucherList">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" id="bookDayList">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Voucher No.</th>
                                                <th class="text-center">Voucher Date</th>
                                                <th class="text-center">Debit/Credit</th>
                                                <th class="text-center">Ref / Bill No.</th>
                                                <th class="text-center">Voucher Status</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center hidden-print">Action</th>
                                                </thead>
                                                <tbody id="filterBookDayList"></tbody>
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
@endsection

<script !src="">

    function BookDayList(){
        var date = $('#date').val();
        var m = '<?php echo $_GET['m']?>';
        $('#filterBookDayList').html('<tr><td colspan="15"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

        $.ajax({
            url: '<?php echo url('/')?>/fdc/filterBookDayList',
            method:'GET',
            data:{date:date,m:m},
            error: function(){

                alert('error');
            },
            success: function(response){
                $('#filterBookDayList').html(response);
            }
        });
        //}
    }

</script>