<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


//$view=ReuseableCode::check_rights(174);


?>

@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">    
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="headquid">
                                            <span class="subHeadingLabelClass">View Users List</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div id="printBankPaymentVoucherList">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel">
                                                <div class="panel-body" id="PrintPanel">
                                                    <div class="row" id="ShowHide">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <h5 style="text-align: center" id="h3"></h5>
                                                                <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                                                    <thead>
                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">Profile</th>
                                                                    <th class="text-center">Name</th>
                                                                    <th class="text-center">User Email</th>
                                                                    
                                                                    <th class="text-center">Company</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Action</th>
                                                                    </thead>
                                                                    <tbody id="data">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function(){
            filter_user_list();
        });

        function EyeSlash(Id)
        {
            $('.toggle-password'+Id).toggleClass("fa-eye fa-eye-slash");

            var input = $("#Password"+Id);
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        }

        function FieldOpen(Id)
        {
            $('#Password'+Id).attr('readonly',false);
            $('#BtnUpdate'+Id).css('display','block');
            $('#BtnEdit'+Id).css('display','none');
        }
        function UpdatePassword(Id)
        {
            var Password = $('#Password'+Id).val();
            if(Password !="")
            {
                $('#Password'+Id).css('border-color','#ccc');
                $('#Loading'+Id).html('<img src="<?php echo url('/');?>/assets/img/loading.gif">');
                $.ajax({
                    url: '/finance/update_user_password',
                    type: 'Get',
                    data: {Password: Password,Id:Id},

                    success: function (response)
                    {
                        if(response == 'yes')
                        {
                            $('#Loading'+Id).html('');
                            $('#Password'+Id).attr('readonly',true);
                            $('#BtnUpdate'+Id).css('display','none');
                            $('#BtnEdit'+Id).css('display','block');
                        }
                    }
                });
            }
            else
            {
                $('#Password'+Id).css('border-color','#c76969');
            }

        }

        function filter_user_list()
        {
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $.ajax({
                url: '<?php echo url('/')?>/finance/filter_user_list',
                type: 'Get',
                data: {},

                success: function (response)
                {
                    $('#data').html(response);
                }
            });
        }

        function ActiveInActiveUser(Id,Val)
        {

            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/finance/activeInActiveUser',
                type: 'Get',
                data: {UserId: Id,statusVal:Val},

                success: function (response)
                {
                    filter_user_list();
                }
            });

        }
    </script>

@endsection
