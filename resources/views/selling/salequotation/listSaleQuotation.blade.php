@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">View Sale Quotation List</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Quotation No</th>
                                                        <th class="text-center">Quotation Date</th>
                                                        <th class="text-center">Valid Up To</th>
                                                        <th class="text-center">Revision</th>
                                                        <!-- <th class="text-center">Customer/Prospect</th> -->
                                                        <th class="text-center">Sale Order Status</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="data"></tbody>
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
    </div>

    <script>
        $(document).ready(function(){
            viewRangeWiseDataFilter();
        });
        function viewRangeWiseDataFilter()
        {
            var Filter=$('#search').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/listSaleQuotation',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }
        
        function removeDraft(id)
        {
    
             $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/removeDraft',
                type: 'Get',
                data: {id:id},
                success: function (response) {
                  
                    if (response.catchError) {
                        $(".alert-danger").removeClass("hide");
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display', 'block');
                        $(".print-error-msg").find("ul").append('<li>' + response.catchError + '</li>');

                    }
                    if ($.isEmptyObject(response.error)) {

                        var successMessage = $('.alert-success');
                        successMessage.removeClass('hide');
                        successMessage.html(response.success);
                        viewRangeWiseDataFilter();
                    

                    } else {

                        printErrorMsg(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    $('.loader-container').hide();
                    console.log(error); // Log the error message for debugging

                }
            });
        }
    </script>

@endsection