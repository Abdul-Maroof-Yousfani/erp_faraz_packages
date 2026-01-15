<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="">Sub Item</label>
                                    <select name="SubItemId" id="SubItemId" class="form-control select2">
                                        <option value="">Select Sub Item</option>
                                        <?php foreach($SubItem as $SubItemFil):?>
                                        <option value="<?php echo $SubItemFil->id?>"><?php echo $SubItemFil->sub_ic?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span id="SubItemError"></span>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="BtnGetData" style="margin: 30px 0px 0px 0px;" onclick="checkPurchasingData();">Get Data</button>
                                </div>
                            </div>
                            <div class="row"><div class="col-sm-12">&nbsp;</div></div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Sub Item</th>
                                            <th class="text-center">Sub Item Id</th>
                                            <th class="text-center">Region</th>
                                            <th class="text-center">Voucher No</th>
                                            <th class="text-center">Voucher Date</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Rate</th>
                                            <th class="text-center hidden-print">Amount</th>
                                            </thead>
                                            <tbody id="AjaxData">
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

    <script !src="">
        $(document).ready(function(){
            $('.select2').select2();
        });

        function checkPurchasingData()
        {
            var SubItemId = $('#SubItemId').val();
            //alert(SubItemId); return false;
            if(SubItemId != "")
            {
                $('#SubItemError').html('');
                $('#AjaxData').html('<tr><td colspan="12" class="loader"></td></tr>');
                $.ajax({
                    url: '<?php echo url('/')?>/store/getCheckPurchasingDataAjax',
                    method:'GET',
                    data:{SubItemId:SubItemId},
                    error: function(){
                        alert('error');
                    },
                    success: function(response){
                        $('#AjaxData').html(response);
                    }
                });
            }
            else
            {
                $('#SubItemError').html('<p class="text-danger">Select Sub Item.</p>');
            }
        }
    </script>

@endsection