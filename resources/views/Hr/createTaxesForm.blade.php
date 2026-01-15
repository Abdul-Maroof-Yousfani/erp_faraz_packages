<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Taxes Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Tax Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="tax_name[]" id="tax_name" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Salary Range From:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="salary_range_from[]" id="salary_range_from" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Salary Range To:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="salary_range_to[]" id="salary_range_to" value="" class="form-control requiredField" />
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Percentange % of Salary
                                            <input type="radio" name="tax_mode[]" value="Percentage">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Amount
                                            <input type="radio" name="tax_mode[]" value="Amount">
                                        </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="tax_percent[]" id="tax_percent[]" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tax Month & Year :</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="month" name="tax_month_year[]" id="tax_month_year[]" value="" class="form-control requiredField" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="TaxesSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <!--<input type="button" class="btn btn-sm btn-primary addMoreTaxesSection" value="Add More Taxes Section" />-->
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                            </div>
                        </div>
                        <?php echo Form::close();?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var lifeInsurance = new Array();
                var val;
                $("input[name='TaxesSection[]']").each(function(){
                    lifeInsurance.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of lifeInsurance) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            var EOBI = 1;
            $('.addMoreTaxesSection').click(function (e){
                e.preventDefault();
                EOBI++;
                $('.TaxesSection').append('<div class="row myloader_'+EOBI+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormTaxesDetail',
                    type: "GET",
                    data: { id:EOBI},
                    success:function(data) {

                        $('.TaxesSection').append('<div id="sectionTaxes_'+EOBI+'"><a style="cursor:pointer;" onclick="removeTaxesSection('+EOBI+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        $('.myloader_'+EOBI).remove();
                    }
                });
            });

        });

        function removeTaxesSection(id){
            var elem = document.getElementById('sectionTaxes_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection