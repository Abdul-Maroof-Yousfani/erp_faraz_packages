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

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Degree Type Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <?php echo Form::open(array('url' => 'had/addDegreeTypeDetail','id'=>'EOBIform'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Degree Type Name:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" name="degree_type_name[]" id="degree_type_name[]" value="" class="form-control requiredField" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="DegreeTypeSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <input type="button" class="btn btn-sm btn-primary addMoreDegreeTypeSection" value="Add More Degree Type Section" />
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
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
            var DegreeType = 1;
            $('.addMoreDegreeTypeSection').click(function (e){
                e.preventDefault();
                DegreeType++;
                $('.DegreeTypeSection').append('<div class="row myloader_'+DegreeType+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')
                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormDegreeTypeDetail',
                    type: "GET",
                    data: { id:DegreeType},
                    success:function(data) {

                        $('.DegreeTypeSection').append('<div id="sectionDegreeType_'+DegreeType+'"><a style="cursor:pointer;" onclick="removeDegreeTypeSection('+DegreeType+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        $('.myloader_'+DegreeType).remove();
                    }
                });
            });

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var lifeInsurance = new Array();
                var val;
                $("input[name='lifeInsuranceSection[]']").each(function(){
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

        });

        function removeDegreeTypeSection(id){
            var elem = document.getElementById('sectionDegreeType_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection