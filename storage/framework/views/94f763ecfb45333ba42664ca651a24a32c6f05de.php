<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row hide" style="margin-top:10px">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">From date</label>
                                                <div class="col-sm-8">
                                                    <input name="rate_date" id="rate_date" value="" class="form-control"
                                                        type="date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">To date</label>
                                                <div class="col-sm-8">
                                                    <input name="to_date" id="to_date" value="" class="form-control"
                                                        type="date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                style="margin: 5px 0px 0px px;" onclick="viewPacking();">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="headquid">
                                            <h2 class="subHeadingLabelClass">View QC List </h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <table class="userlittab table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">PP No</th>
                                                                <th class="text-center">QC Date</th>
                                                                <th class="text-center">QC by</th>
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
    </div>

    <script>
        $(document).ready(function () {
            viewPacking();
        });
        function viewPacking() {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo e(url('/')); ?>/Production/QaPacking',
                type: 'Get',
                data: {
                    rate_date: rate_date,
                    to_date: to_date
                },
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }

    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>