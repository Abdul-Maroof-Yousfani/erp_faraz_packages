<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')

    <style>
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
    </style>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/summernote-bs4.css') }}">
    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <span class="subHeadingLabelClass">Create Hr Letters</span>
            </div>
        </div>

        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <?php echo Form::open(array('url' => 'had/addHrLetters','id'=>'HrLetters', 'method' => 'post'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?=$m?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Regions:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="region_id" id="region_id" >
                                    <option value="">Select Region</option>
                                    @foreach($employee_regions as $key2 => $y2)
                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Category:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_category_id" id="emp_category_id" onchange="empCategory()">
                                    <option value="">Select Category</option>
                                    @foreach($employee_category as $key2 => $y2)
                                        <option value="{{ $y2->id}}">{{ $y2->employee_category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Employee Project</label>
                                <select class="form-control" name="employee_project_id" id="employee_project_id" onchange="employeeProject()">
                                    <option value="0">Select Project</option>
                                    @foreach($Employee_projects as $value)
                                        <option value="{{$value->id}}">{{$value->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emr_no" id="emr_no" required></select>
                                <div id="emp_loader"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Letter:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="letter_id" id="letter_id" required>
                                    <option value="">Select Letter</option>
                                    <option value="1">Warning Letter</option>
                                    <option value="2">MFM South Increment Letter</option>
                                    <option value="3">MFM South Without Increment Letter</option>
                                    <option value="4">Contract Conclusion Letter</option>
                                    <option value="5">Termination Letter Format 1</option>
                                    <option value="6">Termination Letter Format 2</option>
                                    <option value="7">Transfer Letter</option>
                                </select>
                            </div>
                            <span id="details"></span>
                            <div class="row" id="error_messages">&nbsp;</div>
                            <div class="row">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content1">
                                <label>Letter Head Content</label>
                                <textarea rows="10" class="form-control summernote1"></textarea>
                                <input type="hidden" id="letter_content1" name="letter_content1">
                            </div>
                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content2">
                                <label>Other Page Content</label>
                                <textarea rows="10" class="form-control summernote2"></textarea>
                                <input type="hidden" id="letter_content2" name="letter_content2">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Note</label>
                                <textarea class="form-control" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <button id="do" type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>

    <script>


        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
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
                    }else if(validate == 1){
                        return false;
                    }
                }

            });


            $(".btn-success").click(function(e){
                $("#letter_content1").val($("#content1").find('.note-editable').html());
                $("#letter_content2").val($("#content2").find('.note-editable').html());

            });
            $('#emp_category_id').select2();
            $('#region_id').select2();
            $('#designation_id').select2();
            $('#emr_no').select2();
            $('#letter_id').select2();
            $('#employee_project_id').select2();
        });


        function employeeProject() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            var employee_project_id = $("#employee_project_id").val();
            if(employee_project_id == ''){
                empCategory()
            }
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else if (emp_category_id == '') {
                alert('Please Select Cateogery !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (employee_project_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeProjectList',
                        type: "GET",
                        data: {
                            emp_category_id: emp_category_id,
                            region_id: region_id,
                            employee_project_id: employee_project_id,
                            m: m
                        },
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }

        function empCategory() {
            var emp_category_id = $("#emp_category_id").val();
            var region_id = $("#region_id").val();
            if (region_id == '') {
                alert('Please Select Region !');
                return false;
            } else {
                var m = '<?= Input::get('m'); ?>';
                if (emp_category_id) {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    $.ajax({
                        url: '<?php echo url('/')?>/slal/getEmployeeCategoriesList',
                        type: "GET",
                        data: {emp_category_id: emp_category_id, region_id: region_id, m: m},
                        success: function (data) {
                            $('#emp_loader').html('');
                            $('select[name="emr_no"]').empty();
                            $('select[name="emr_no"]').html(data);
                            $("#emr_no option[value='All']").remove();
                            $("#emr_no").prepend("<option value='' selected='selected'>Select Employee</option>");
                        }
                    });
                } else {
                    $('select[name="emr_no"]').empty();
                }
            }
        }


        $('#letter_id').on('change', function() {
            var newLine = "\r\n";
            var emr_no = $('#emr_no').val();
            var letter_id = $('#letter_id').val();


            if(letter_id == 2)
            {
                $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Confirmation Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getIncrementLettersDetails()" type="date" required class="form-control requiredField" value="" name="confirmation_from" id="confirmation_from"/></div>');

                $("#content1").find(".note-editable").html('Consequent to the review of your performance during <span class="performance_from"></span> ' +
                    ' to <span class="performance_to"></span>, we have the pleasure in informing you that, your services are being confirmed ' +
                    ' as <span class="designation"></span> w.e.f <span class="confirmation_from"></span> with' +
                    ' the salary increment of PKR: <span class="salary"></span> based on your satisfactory performance. Your revised package will be: ');
            }

            else if(letter_id == 3)
            {
                $('#details').html(
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Performance From:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="performance_from" id="performance_from"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Performance To:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="performance_to" id="performance_to"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Confirmation Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="getWithoutIncrementLettersDate()" type="date" class="form-control requiredField" required value="" name="confirmation_from" id="confirmation_from"/></div>');

                $("#content1").find(".note-editable").html('Consequent to the review of your performance during <span class="performance_from"></span> ' +
                        'to <span class="performance_to"></span> ' +
                        'your probation period, we have the pleasure in informing you that, your services are being confirmed as Lock Smith  w.e.f <span class="confirmation_from"></span>.' +
                        '<br><br>All other terms and conditions as detailed in your appointment letter shall remain unchanged.' +
                        '<br><br>We look forward to your valuable contributions and wish you all the very best for a rewarding career with the organization.');
            }

            else if(letter_id == 4)
            {
                $('#details').html(
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Conclude Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="date" class="form-control requiredField" onchange="getConclusionLettersDate()" value="" name="conclude_date" id="conclude_date"/></div>' +
                        '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Final Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="date" class="form-control requiredField" onchange="getConclusionLettersDate()" value="" name="settlement_date" id="settlement_date"/></div>');
                $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span>. In accordance with clause B-(2) of' +
                        ' the appointment letter, we hereby give you advance notice to conclude the employment contract on <span class="conclude_date"></span>,' +
                        ' Your employment with MIMA Facility Management as Tea Boy will cease on close of business on <span class="conclude_date"></span>.<br><br> ' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the Company or its customers, ' +
                        'and will not divulge any of Company’s or client’s affairs or trade secrets that you may have obtained while in the service of the Company. ' +
                        'You are also required to return any of the company’s material, documents, ID card, Insurance Card or any under your possession.' +
                        '<br><br>Your final settlement will be released by <span class="settlement_date"></span> after clearance from your immediate supervisor');

            }
            else if(letter_id == 1)
            {


                $('#details').html('');
                $("#content1").find(".note-editable").html('Please be advised that we are receiving continuous complaints against your behavior at the' +
                        'work place from your seniors. Verbal warnings have already been given to you by Branch Operation Manager' +
                        'of our client but ' +'unfortunately you are not bringing any improvement in your behavior. <br><br>' +
                        'Therefore, you are hereby advised to immediately bring positive improvement in your behavior ' +
                        'towards your seniors, peers and customers or else Management will have no alternative other than to' +
                        'initiate stern action against you which may lead up to the termination of your services');

            }
            else if(letter_id == 5)
            {

                $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="insertEmployeeDates()" type="date" class="form-control requiredField" value="" required name="settlement_date" id="settlement_date"/></div>');

                $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span> In accordance with clause B-(2)' +
                        ' of the appointment letter, we hereby terminate your employment with immediate effect i.e. <span id="settlement_date_area"></span>' +
                        ' You have committed a serious misconduct which has been proved through inquiry and afterwards admitted by you in writing, hence your services are being terminated with immediate effect.<br><br>' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the Company or its customers, and will not divulge any of Company’s or client’s affairs or trade secrets that you may have obtained while in the service of the Company. ' +
                        'You are also required to return any of the company’s material, documents, ID card, Insurance Card' +
                        ' or any under your possession.  ');
            }
            else if(letter_id == 6)
            {
                $("#content1").find(".note-editable").html('This has reference to the letter of appointment dated <span id="date_of_joining"></span> In accordance with clause B-(2) of ' +
                        'the appointment letter, we hereby terminate your employment with immediate effect due to continuous absence from work without proper notice and approvals<br><br>' +
                        'As stated in the clause of B-(9); you are bound not to disclose any information relating to the ' +
                        'Company or its customers, and will not divulge any of Company’s or client’s affairs or trade secrets ' +
                        'that you may have obtained while in the service of the Company.' +
                        ' You are also required to return any of the company’s material, documents, ID card, ' +
                        'Insurance Card or any belongings under your possession to get final clearance' +
                        '');
                $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                        '<label class="sf-label">Settlement Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input onchange="insertEmployeeDates()" type="date" class="form-control requiredField" value="" required name="settlement_date" id="settlement_date"/></div>');


            }

            else if(letter_id == 7)
            {
                $('#details').html('<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
                    '<label class="sf-label">Transfer Date:</label><span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input onchange="getTransferLettersDetails()" type="date" required class="form-control requiredField" value="" name="transfer_date" id="transfer_date"/></div>');

                $("#content1").find(".note-editable").html('This is to inform you that as per the Management decision, you have been transferred from <span class="transfer_from"></span>' +
                    ' to <span class="transfer_to"></span> as <span class="designation"></span> w.e.f <span class="transfer_date"></span> . ');
            }


        });

        function insertEmployeeDates() {
            var settlement_date = $("#settlement_date").val();
            var emr_no = $("#emr_no").val();
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            const d = new Date();
            var date = (monthNames[d.getMonth()]) +' '+ d.getDate() +', '+ d.getFullYear();
            if(emr_no == null){alert("Select Employee First !"); return false;}
            var m = "<?=Input::get('m')?>";

            $.get("<?=url('/')?>/hdc/getEmployeeDateOfJoining",{'settlement_date':settlement_date,'emr_no':emr_no,'m':m},
                function(data){
                $("#settlement_date_area").html(date);
                $("#date_of_joining").html(data[1]);

            });
        }

        function getWithoutIncrementLettersDate()
        {
            var performance_from = $("#performance_from").val();
            var performance_to = $("#performance_to").val();
            var confirmation_from = $("#confirmation_from").val();
            var emr_no = $("#emr_no").val();
            if(emr_no == null){alert("Select Employee First !"); return false;}
            var m = "<?=Input::get('m')?>";

            $.get("<?=url('/')?>/hdc/getWithoutIncrementLettersDate",{'confirmation_from':confirmation_from,'performance_from':performance_from,'performance_to':performance_to,'emr_no':emr_no,'m':m}, function(data){
                $(".performance_from").html(data[0]);
                $(".performance_to").html(data[1]);
                $(".confirmation_from").html(data[2]);

            });
        }

        function getIncrementLettersDetails()
        {
            var confirmation_from = $("#confirmation_from").val();
            var emr_no = $("#emr_no").val();
            if(emr_no == null){alert("Select Employee First !"); return false;}
            var m = "<?=Input::get('m')?>";

            $.get("<?=url('/')?>/hdc/getIncrementLettersDetails",{'confirmation_from':confirmation_from,'emr_no':emr_no,'m':m}, function(data){
              
                if(data == 1)
                {
                    $("#error_messages").html('<div class="row">&nbsp;</div><div class="text-center" style="color: red"><h3>Increment Record not found !</h3></div>');

                    $("#do").attr("disabled","disabled");
                }
                else
                    {
                        $("#error_messages").html("");
                        $("#do").removeAttr("disabled");
                        $(".performance_from").html(data[0]);
                        $(".performance_to").html(data[1]);
                        $(".confirmation_from").html(data[2]);
                        $(".designation").html(data[3]);
                        $(".salary").html(data[4]);
                    }


            });
        }

        function getConclusionLettersDate()
        {
            var conclude_date = $("#conclude_date").val();
            var settlement_date = $("#settlement_date").val();
            var emr_no = $("#emr_no").val();
            if(emr_no == null){alert("Select Employee First !"); return false;}
            var m = "<?=Input::get('m')?>";

            $.get("<?=url('/')?>/hdc/getConclusionLettersDate",{'conclude_date':conclude_date,'settlement_date':settlement_date,'emr_no':emr_no,'m':m}, function(data){
                $("#date_of_joining").html(data[0]);
                $(".conclude_date").html(data[1]);
                $(".settlement_date").html(data[2]);

            });
        }

        function getTransferLettersDetails()
        {
            var transfer_date = $("#transfer_date").val();
            var emr_no = $("#emr_no").val();
            if(emr_no == null){alert("Select Employee First !"); return false;}
            var m = "<?=Input::get('m')?>";

            $.get("<?=url('/')?>/hdc/getTransferLettersDetails",{'transfer_date':transfer_date,'emr_no':emr_no,'m':m}, function(data){

                if(data == 1)
                {
                    $("#error_messages").html('<div class="row">&nbsp;</div><div class="text-center" style="color: red"><h3>Transfer Record not found !</h3></div>');

                    $("#do").attr("disabled","disabled");
                }
                else
                {
                    $("#error_messages").html("");
                    $("#do").removeAttr("disabled");
                    $(".transfer_from").html(data[0]);
                    $(".transfer_to").html(data[1]);
                    $(".transfer_date").html(data[2]);
                    $(".designation").html(data[3]);
                }


            });
        }

    </script>

    <script>

        $(function() {
            $('.summernote1').summernote({
                height: 200
            });

            $('.summernote2').summernote({
                height: 200
            });
        });
    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/summernote-bs4.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popper.js') }}"></script>



@endsection