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
            $('.account_id').fadeOut();
            $('#parent_cost_center').fadeOut();
            $('#first_level').val(1);
        }

        else
        {
            $('.account_id').fadeIn();
            $('#parent_cost_center').fadeIn();
            $('#first_level').val(0);
        }
    }
</script>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
        @include('Purchase.'.$accType.'purchaseMenu')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Add New Cost Center</span>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/editCostCenterForm/'.$cost_center_data->id.'?m='.$m.'','id'=>'addCategoryForm'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <?php $cost_center_data->name ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkbox">
                                    <label>
                                        <input disabled  @if($cost_center_data->first_level==1) checked @endif onclick="check_uncheck()" type="checkbox" name="first_level_chk" value="checked"
                                               id="first_level_chk" /><b>First Level ?</b></label>
                                    <input type="hidden" name="first_level" id="first_level" value=""/>
                                </div>

                                    <div class="form-group">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                            <label>
                                                <input @if($cost_center_data->operational==1)checked @endif type="checkbox" name="operational" value="1" />  <b>Operational</b>
                                            </label>
                                        </div>
                                    </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12  account_id">

                                    @if($cost_center_data->first_level!=1)
                                        <label class="account_id">Parent Cost Center:</label>
                                        <span class="rflabelsteric account_id"><strong>*</strong></span>
                                        <select style="width: 100%"  class="form-control select2" name="parent_cost_center" id="parent_cost_center">
                                            <option value="">Select Account</option>
                                            @foreach($cost_center as $key => $y)
                                                <option @if($cost_center_data->parent_code==$y->code)selected @endif value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <input type="hidden" name="parent_code" value="{{$cost_center_data->parent_code}}"/>


                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    <label>Cost Center Name :</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input autofocus style="text-transform: capitalize" type="text" name="cost_center" id="cost_center"
                                           value="{{$cost_center_data->name}}"    class="form-control requiredField" />
                                </div>
                                <div>&nbsp;</div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                    &nbsp;&nbsp;&nbsp;
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}


                                    <?php
                                    //echo Form::submit('Click Me!');
                                    ?>
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

<script type="text/javascript">
    $(document).ready(function() {
        $(".btn-success").click(function(e){
            var category = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            category.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of category) {

                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
        });
    });
</script>
<script type="text/javascript">

    $('#parent_cost_center').select2();
</script>
