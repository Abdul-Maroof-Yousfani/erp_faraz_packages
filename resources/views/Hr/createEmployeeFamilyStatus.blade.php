<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
    <style>
        .fileContainer {
            overflow: hidden;
            position: relative;
        }

        .fileContainer [type=file] {
            cursor: inherit;
            display: block;
            font-size: 999px;
            filter: alpha(opacity=0);
            min-height: 100%;
            min-width: 100%;
            opacity: 0;
            position: absolute;
            right: 0;
            text-align: right;
            top: 0;
        }


        .fileContainer {
            color:#fff;
            background: #3071a9;
            border-radius: .5em;
            float: left;
            padding: 2px;
        }

        .fileContainer [type=file] {
            cursor: pointer;
        }

        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
    </style>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Create Employee Family Status</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/addEmployeeFamilyStatusDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Employee Name:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select  onchange="viewEmployeeDataFamilyStatus(this.id)" name="emp_id" id="emp_name" class="form-control requiredField">
                                            <option value="">Select</option>
                                            @foreach($employee as $row)
                                            <option value="{{$row['id']}}">{{$row['emp_name']}}</option>
                                                @endforeach;
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Employee NO:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Employe No" name="emp_no" id="emo_no" value="" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Designation:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input readonly type="text" class="form-control requiredField" placeholder="Designation" name="designation" id="designation" value="" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="sf-label">Department:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input  readonly type="text" class="form-control requiredField" placeholder="Department" name="department" id="department" value="" />
                                    </div>
                                </div>
                                </br>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: gainsboro">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h4 style="text-decoration: underline; ">Marital Status</h4>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label style="font-size: 15px;padding-top: 5px;" class="radio-inline"><input id="married" onclick="hide()" type="radio" value="1" name="optradio">Married</label>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label style="font-size: 15px;padding-top: 5px;" class="radio-inline"><input id="single" checked onclick="hide()" type="radio" value="2" name="optradio">Single </label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="text-decoration: underline;">If married, give detail of the spouse & Children:</h4>
                                    </div>
                                </div>
                                <div  style="color: crimson;text-align: center" class="row loader_data">&nbsp;</div>
                                <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;">
                                    <div id="hide" style="display: none;"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table   class="table table-bordered input_fields_wrap">
                                            <thead>
                                            <tr>
                                            <tr>
                                                <th>Sr.No</th>
                                                <th>Name</th>
                                                <th>Male / Female</th>
                                                <th>Relation</th>
                                                <th>Date Of Birth</th>
                                              <th>  <button type="button" style="" class="add_field_button btn btn-xs  btn-success">Add More Fields</button></th>
                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody id="data" class="">
                                                <tr class="remove">
                                                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center"><input value=""  class="form-control requiredField" type="text" name="name[]"></td>
                                                    <td class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                                                        <select name="gender[]" class="form-control requiredField">
                                                            <option value="1">Male</option>
                                                            <option value="2">Female</option>
                                                        </select>
                                                    </td>
                                                    <td class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                                                        <select name="relation[]" class="form-control">
                                                            <option value="1">Spouse</option>
                                                            <option value="2">Chidren</option>
                                                        </select>
                                                    </td>

                                                    <td class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-center"><input value="" type="date" name="dob[]" class="form-control requiredField"></td>
                                                    <td class="text-center">---</td>
                                                 </tr>
                                           </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="employeeSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeSection" value="Add More Employee's Section" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            var employee = 1;

            var m = "<?= $_GET["m"]; ?>";

            $('.addMoreEmployeeSection').click(function (e){

                e.preventDefault();
                employee++;
                $('.employeeSection').append('<div class="row myloader_'+employee+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>')

                $.ajax({
                    url: '<?php echo url('/')?>/hmfal/makeFormEmployeeDetail',
                    type: "GET",
                    data: { id:employee ,m : m},
                    success:function(data) {
                        $('.employeeSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionEmployee_'+employee+'"><a href="#" onclick="removeEmployeeSection('+employee+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                        $('.myloader_'+employee).remove();
                    }
                });
            });

            // Wait for the DOM to be ready
            $(".btn-successs").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

        });
        var count = 1;
        function removeEmployeeSection(id){
            var elem = document.getElementById('sectionEmployee_'+id+'');
            elem.parentNode.removeChild(elem);
        }

        $(document).ready(function() {
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper         = $(".input_fields_wrap");
            var remove         = $(".input_fields_wrap");//Fields wrapper
            var add_button      = $(".add_field_button"); //Add button ID

          //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
               //max input box allowed
                   //text box increment
                count++;
                    $(wrapper).append('<tr class="remove'+count+'  all_remove"><td class="text-center"><span class="badge badge-pill badge-secondary">'+count+'</span></td>' +
                            '<td class="text-center"><input value="" class="form-control requiredField" type="text" name="name[]"></td>' +
                            '<td class="text-center"><select name="gender[]" class="form-control"><option value="1">Male</option><option value="2">Female</option></select></td>' +
                            ' <td class="text-center"><select name="relation[]" class="form-control"> <option value="1">Spouse</option><option value="2">Chidren</option></select></td>' +
                            '<td class="text-center"><input value="" type="date" name="dob[]" class="form-control requiredField"></td>' +
                            '<td class="text-center"><button type="button" onclick="remove('+count+')"  class="btn btn-xs  btn-danger">Remove</button></td></tr>'); //add input box

            });


        });
function remove(number)
{


    $('.remove'+number).remove();


}


        function hide()
        {
          var v=  $("input[name='optradio']:checked").val();

          if (v==1)
          {
              $("#hide").css("display", "block");
              $('.loader_data').html("");
              $(".amir").addClass("requiredField");


          }
        else
          {
              $("#hide").css("display", "none");
              $(".amir").removeClass("requiredField");

          }
        }



        function viewEmployeeDataFamilyStatus(id)
        {
            var ajaxTime= new Date().getTime();

            $('.loader_data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var emp_val= $('#'+id).val();
            $.ajax({
                url: "/hdc/viewEmployeeDataFamilyStatus",
                type: 'GET',
                data: {emp_val:emp_val},
                success: function(response)
                {
                    $('.loader_data').html('');
                    var result = response.split(",");
                    $('#emo_no').val(result[0]);
                    $('#designation').val(result[1]);
                    $('#department').val(result[2]);

                    if (result[3]==1)
                    {
                        $("#hide").css("display", "block");
                        $('#married').prop('checked',true);
                    }
                    else
                    {

                        $("#hide").css("display", "none");
                        $('#single').prop('checked',true);
                        $('.loader_data').append("<h3>No Data!!!</h3>");
                    }
                    $('#data').html(result[4]);
                    if (typeof(result[5]) == 'undefined') {

                        $('.all_remove').remove();
                        $('#name1').val('');

                  count=1;
                    }
                    else{
                        count=result[5];
                    }



                    var totalTime = new Date().getTime()-ajaxTime;
                    totalTime=totalTime/1000;
               

                }


            });
        }
        $(document).ready(function(){
            $("form").submit(function(){
                var input = document.getElementsByClassName('requiredField');
                var v= input.length;


                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++){
                    var v = input[i].id;
                    if(v == '')
                    {

                    }

                    else{
                        if($('#'+v).val() == '')

                        {

                            $('#'+v).css('border-color', 'red');

                            return false;
                        }

                        else
                        {
                            $('#'+v).css('border-color', '#ccc');
                        }
                    }
                }
            });
        });

    </script>
@endsection