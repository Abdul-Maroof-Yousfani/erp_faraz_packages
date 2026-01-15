<?php
$m = $_GET['m'];
?>


    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Remarks Form</span>
                    </div>
                </div>

                <div class="lineHeight">&nbsp;</div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label for="">Remarks</label>
                                        <textarea class="form-control requiredField" name="remarks" id="remarks" cols="60" rows="5"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                   	<button onclick="UpdateRemarks()" class="btn btn-primary"> Submit </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function UpdateRemarks(id)
        {
            if(confirm("Want To Submit...? Press ok")) {
                var m = '<?php echo $_GET['m'] ?>';
                var id = $("#id").val();
                var remarks = $("#remarks").val();
                $.ajax({
                    url: '<?php echo url('/')?>/reports/UpdateRemarks',
                    type: "GET",
                    data: {id:id, m:m, remarks:remarks },
                    success: function (data) {
                        alert("Successfully Reamrks Added");
                        $("#remarks"+id).text(remarks);
                        $("#showDetailModelOneParamerter").modal("hide");
                    }
                });
            } else {
                alert('Cancel');
            }
        }
    </script>


