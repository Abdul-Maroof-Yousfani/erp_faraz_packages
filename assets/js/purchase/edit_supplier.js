$(document).ready(function() {



    var countryID = $('#country').val();

    if(countryID) {
        $.ajax({
            url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
            type: "GET",
            data: { id:countryID},
            success:function(data) {

                var state='<?php echo $supplierDetail->province ?>';
                var city='<?php echo $supplierDetail->city ?>';
                $('select[name="city"]').empty();
                $('select[name="state"]').empty();

                $('select[name="state"]').html(data);
                $('#state').val(state);
                var stateID = state;
                if(stateID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                        type: "GET",
                        data: { id:stateID},
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').html(data);
                            $('#city').val(city);
                        }
                    });
                }else{
                    $('select[name="city"]').empty();

                }


            }
        });
    }else{
        $('select[name="state"]').empty();
        $('select[name="city"]').empty();
    }





    if ($('#regd_in_income_tax').is(':checked'))
    {

        $('.income').prop('checked', false);
        document.getElementById("income_tax_div").style.display = "block";
    } else {

        document.getElementById("income_tax_div").style.display = "none";
        $("#cnic").css("display", "none");
        $("#ntn").css("display", "none");
        $('#ntn').val("");
    }

    var ntn_cnic_param='<?php echo $supplierDetail->business_type; ?>';
    if (ntn_cnic_param!=0)
    {
        ntn_cnic(ntn_cnic_param);
    }


    if ($('#regd_in_sales_tax').is(':checked'))
    {
        document.getElementById("sales_tax_div").style.display = "block";
        $("#strn").addClass("requiredField");
    } else {
        document.getElementById("sales_tax_div").style.display = "none";
        $('#strn').val("");
        $("#strn").removeClass("requiredField");
    }


    if ($('#regd_in_pra').is(':checked'))
    {
        document.getElementById("sales_tax_pra").style.display = "block";
        $("#pra").addClass("requiredField");
    } else {
        document.getElementById("sales_tax_pra").style.display = "none";
        $('#pra').val("");
        $("#pra").removeClass("requiredField");
    }


    $(".btn-success-edit").click(function(e){
        var supplier = new Array();
        var val;
        supplier.push($(this).val());
        var _token = $("input[name='_token']").val();
        for (val of supplier) {
            jqueryValidationCustom();
            if(validate == 0){
                //return false;
            }else{
                return false;
            }
        }
        formSubmitOne(e);
    });


    function formSubmitOne(e){

        var postData = $('#editSupplierForm').serializeArray();
        var formURL = $('#editSupplierForm').attr("action");
        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data){
                $('#showMasterTableEditModel').modal('toggle');
                viewSupplierList();
            }
        });
    }

    $('select[name="country"]').on('change', function() {
        var countryID = $(this).val();
        if(countryID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                type: "GET",
                data: { id:countryID},
                success:function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="state"]').empty();
                    $('select[name="state"]').html(data);
                }
            });
        }else{
            $('select[name="state"]').empty();
            $('select[name="city"]').empty();
        }
    });

    $('select[name="state"]').on('change', function() {
        var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                type: "GET",
                data: { id:stateID},
                success:function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="city"]').html(data);
                }
            });
        }else{
            $('select[name="city"]').empty();
        }
    });
});

function ntn_cnic(id)
{
    if(id==1)
    {

        $(this).prop('checked', false);
        $("#ntn").fadeIn(500);
        $("#cnic").fadeIn(500);
        $("#amir").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
        $("#amir").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        $("#ntn").addClass("requiredField");
        $("#cnic").addClass("requiredField");
    }

    else
    {

        $("#ntn").fadeIn(500);
        $("#ntn").addClass("requiredField");
        $("#cnic").css("display", "none");
        $("#cnic").removeClass("requiredField");
        $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");

    }
}


$('#regd_in_income_tax').change(function(){
    if ($(this).is(':checked'))
    {
        var ntn_cnic_param='<?php echo $supplierDetail->business_type; ?>';
        if (ntn_cnic_param!=0) {
            ntn_cnic(ntn_cnic_param);
        }

        $('.income').prop('checked', false);
        document.getElementById("income_tax_div").style.display = "block";
    } else {
        document.getElementById("income_tax_div").style.display = "none";
        $("#cnic").css("display", "none");
        $("#ntn").css("display", "none");
        //  $('#ntn').val("");
    }
});


$('#regd_in_sales_tax').change(function(){
    if ($(this).is(':checked'))
    {

        document.getElementById("sales_tax_div").style.display = "block";
        $("#strn").addClass("requiredField");
    } else
    {
        document.getElementById("sales_tax_div").style.display = "none";
        //  $('#strn').val("");
        $("#strn").removeClass("requiredField");
    }
});

$('#regd_in_srb').change(function(){
    if ($(this).is(':checked'))
    {
        document.getElementById("sales_tax_srb").style.display = "block";
        $("#srb").addClass("requiredField");
    } else {
        document.getElementById("sales_tax_srb").style.display = "none";
        //  $('#srb').val("");
        $("#srb").removeClass("requiredField");
    }
});


$('#regd_in_pra').change(function(){
    if ($(this).is(':checked'))
    {
        document.getElementById("sales_tax_pra").style.display = "block";
        $("#pra").addClass("requiredField");
    } else {
        document.getElementById("sales_tax_pra").style.display = "none";
        //   $('#pra').val("");
        $("#pra").removeClass("requiredField");
    }
});