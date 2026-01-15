<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

?>


<?php echo Form::open(array('url' => 'fad/addEmployeeEOBIDetail?m='.$m.'','id'=>'EmployeeTax'));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>EOBI List:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select name="eobi_id" class="form-control requiredField" id="eobi_id" required>
                    <option value="">Select</option>
                    @foreach($eobiList as $value1)
                        <option @if($employeeEobi->eobi_id == $value1->id) selected @endif value="{{ $value1->id }}">{{ $value1->EOBI_name }}&nbsp;||&nbsp;{{ $value1->EOBI_amount }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Department:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="department_id" id="department_id" required>
                    <option value="">Select Department</option>
                    @foreach($departmentList as $key => $y)
                        <option @if($employeeEobi->emp_department_id == $y->id) selected @endif value="{{ $y->id }}">{{ $y->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="sf-label">Employee:</label>
                <span class="rflabelsteric"><strong>*</strong></span>
                <select class="form-control requiredField" name="employee_id" id="employee_id" required>
                    <option value="">Select</option>
                    @foreach($employeeList as $value)
                        <option  @if($employeeEobi->id == $value->id) selected @endif value="{{ $value->id }}" >{{ $value->emp_name}}</option>
                    @endforeach
                </select>
                <div id="emp_loader"></div>
            </div>
        </div>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div class="departmentSection"></div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
    </div>
</div>
<?php echo Form::close();?>

<script>
    $(document).ready(function() {
        var department = 1;
        $('.addMoreDepartmentSection').click(function (e){
            e.preventDefault();
            department++;

            $.ajax({
                url: '<?php echo url('/')?>/hmfal/makeFormDepartmentDetail',
                type: "GET",
                data: { id:department},
                success:function(data) {
                    $('.departmentSection').append('<div id="sectionDepartment_'+department+'"><a href="#" onclick="removeDepartmentSection('+department+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                }
            });
        });

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var department = new Array();
            var val;
            $("input[name='departmentSection[]']").each(function(){
                department.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of department) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

    });

    function removeDepartmentSection(id){
        var elem = document.getElementById('sectionDepartment_'+id+'');
        elem.parentNode.removeChild(elem);
    }

    $(function(){
        $('select[name="department_id"]').on('change', function() {
            $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            var department_id = $(this).val();
            var m = '<?= Input::get('m'); ?>';
            if(department_id) {
                $.ajax({
                    url: '<?php echo url('/')?>/slal/employeeListDeptWise',
                    type: "GET",
                    data: { department_id:department_id,m:m},
                    success:function(data) {
                        $('#emp_loader').html('');

                        $('select[name="employee_id"]').empty();
                        $('select[name="employee_id"]').html(data);
                        $('select[name="employee_id"]').find('option').get(0).remove();
                    }
                });
            }else{
                $('select[name="employee_id"]').empty();
            }
        });
    });

</script>
