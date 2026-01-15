<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>



@include('select2')

<script>
    function	check_uncheck()
    {
        if ($("#first_level_chk").is(":checked"))
        {
            $('#account_id').fadeOut();
        }

        else
        {
            $('#account_id').fadeIn();
        }
    }
</script>
<div class="well">





    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Add Chart of Account</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <?php echo Form::open(array('url' => 'fdc/addChartOfAccount?m='.$m.'','id'=>'chartofaccountForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="chartofaccountSection[]" class="form-control" id="chartofaccountSection" value="1" />
                                    </div>
                                    <div class="form-group">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                            <label>
                                                <input type="checkbox" name="operational" value="1" checked="checked" />  <b>Operational</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Parent Account Head:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width: 100%" autofocus="autofocus" onchange="get_nature_type()" class="form-control select2" name="account_id" id="account_id">
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $key => $y)
                                                <option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="acc_name">New Account </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input style="text-transform: capitalize;" type="text"  placeholder="New Account" class="form-control requiredFields" name="acc_name" id="acc_name" value="" autocomplete="off" >
                                        <input type="hidden" name="PageName" id="PageName" value="<?php echo $PageName?>">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="o_blnc" >Opening Balance </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="any"  name="o_blnc" maxlength="15" min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="0" autocomplete="off"/>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="o_blnc_trans">Transaction </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
                                                    <option value="">select</option>
                                                    <option value="1"><strong>Debit</strong></option>
                                                    <option value="0"><strong>Credit</strong></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$id}}">
                                    <div>&nbsp;</div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success chart_of_account']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                    <?php
                                    echo Form::close();
                                    ?>
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


        $('#chartofaccountForm').submit(function(e)
        { e.preventDefault();






            var me=$(this);


            $.ajax({
                url:me.attr('action'),
                type:'post',
                data:me.serialize(),

                success: function(response)
                {
                    //alert(response); return false;

                    var result=response.split(',');
                    if(result[0] == "jvs")
                    {
                        for(i=1; i<=x; i++)
                        {
                            $('#account_id_1_'+i+'').append($('<option>', {
                                value: result[1]+'~~',
                                text: result[2]
                            }));
                        }
                    }

                    for(i=1; i<=x; i++)
                    {
                        $('#account_id_1_'+i+'').append($('<option>', {
                            value: result[1],
                            text: result[0]
                        }));

                        $('#category_id_1_'+i+'').append($('<option>', {
                            value: result[1],
                            text: result[0]
                        }));

                        $('#accounts_1_'+i+'').append($('<option>', {
                            value: result[1],
                            text: result[0]
                        }));


                    }

                    $('#sales_taxx').append($('<option>', {
                        value: result[1],
                        text: result[0]
                    }));
                    $('#'+result[2]).focus();
                    $("#FormOpen").select2("val", "");
                    $("[data-dismiss=modal]").trigger({ type: "click" });


                }
            });
        });




    });



</script>
<script type="text/javascript">

    $('#account_id').select2();
    $('#account_id').focus();
</script>


<script>
    function get_nature_type()
    {
        var nature=  $("#account_id option:selected").text();
        nature=nature.split('-');
        nature=nature[0];
        if (nature==1 ||  nature==4)
        {
            $('#o_blnc_trans').val(1);
        }

        else
        {
            $('#o_blnc_trans').val(0);
        }
    }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

