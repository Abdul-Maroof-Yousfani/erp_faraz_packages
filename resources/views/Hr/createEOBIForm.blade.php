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
                                <span class="subHeadingLabelClass">Create EOBI Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addEOBIDetail','id'=>'EOBIform'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="EOBISection[]" class="form-control" id="sectionEOBI" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>EOBI Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required type="text" name="EOBI_name[]" id="EOBI_name" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>EOBI Amount:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required  type="text" name="EOBI_amount[]" id="EOBI_amount" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Month & Year:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required type="month" name="month_year[]" id="month_year" value="" class="form-control requiredField" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="EOBISection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                <input type="button" class="btn btn-primary addMoreEOBISection" value="Add More EOBI Section" />
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
                var EOBISection = new Array();
                var val;
                $("input[name='EOBISection[]']").each(function(){
                    EOBISection.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of EOBISection) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            var EOBI = 1;
            $('.addMoreEOBISection').click(function (e){
                e.preventDefault();
                EOBI++;
                $('.EOBISection').append('<div class="row myloader_'+EOBI+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormEOBIDetail',
                    type: "GET",
                    data: { id:EOBI},
                    success:function(data) {

                        $('.EOBISection').append('<div id="sectionEOBI_'+EOBI+'"><a style="cursor:pointer;" onclick="removeEOBISection('+EOBI+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        $('.myloader_'+EOBI).remove();
                    }
                });
            });
        });

        function removeEOBISection(id){
            var elem = document.getElementById('sectionEOBI_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection