@php
    use App\Helpers\CommonHelper;
@endphp

<div class="row" id="printRecipeDetail">


    {{ Form::open(array('url' =>'recipe/addChangeFormulationStatusDetail?m=' . $m ,'id' => 'formulation')) }}
    <input type="hidden" name="formSection[]" id="formSection" value="1">
    <input type="hidden" name="id" id="id" value="{{ $id }}">
    <input type="hidden" name="status" id="status" value="{{ $status }}">
    <input type="hidden" name="m" id="m" value="{{ $m }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 style="text-align: center;">Enable And Disable Formulation</h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <label>Disable Date</label>
            <input type="date" name="disable_date" id="disable_date" value="{{ date('Y-m-d') }}" class="form-control requiredField" />
        </div>

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <label>Disable Remarks</label>
            <textarea name="disable_remarks" id="disable_remarks" class="form-control requiredField" ></textarea>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
            <button type="submit"  class="btn btn-success"> Submit </button>
        </div>
    </div>
    {{ Form::close() }}
</div>

<script>

  $(".btn-success").click(function(e){
      var formSection = new Array();
      var val;
      $("input[name='formSection[]']").each(function(){
        formSection.push($(this).val());
    });
      var _token = $("input[name='_token']").val();
      for (val of formSection) {
          jqueryValidationCustom();
          if(validate == 0){
              $('#formulation').submit();
          }  else {
              return false;
          }
      }
  });
  </script>
