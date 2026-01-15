<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <?php
    $clients = DB::Connection('mysql2')->select('SELECT client, COUNT(*) as count FROM `daily_task_data` WHERE status=1 GROUP by client');
    //print_r($clients);
    ?>
    @foreach($clients as $client)
    <?php
    //$pending    = DB::Connection('mysql2')->select('SELECT COUNT(*) as pending FROM `daily_task_data` WHERE action=1 AND client='.$client->client.'');
    //$job_done   = DB::Connection('mysql2')->select('SELECT COUNT(*) as job_done FROM `daily_task_data` WHERE action=2 AND client='.$client->client.'');
    //$hold       = DB::Connection('mysql2')->select('SELECT COUNT(*) as hold FROM `daily_task_data` WHERE action=3 AND client='.$client->client.'');
    //$delay      = DB::Connection('mysql2')->select('SELECT COUNT(*) as delay FROM `daily_task_data` WHERE action=4 AND client='.$client->client.'');
    //die;
    ?>
    <button type="button" class="btn btn-primary">
        {{ CommonHelper::get_client_name_by_id($client->client) }} <span class="badge badge-light">{{$client->count}}</span>

    </button>
    @endforeach

    <br><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group ">
                    <label for="email">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group ">
                    <label for="email">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="">
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="email">Client</label>
                <?php $get_all_clients = CommonHelper::get_all_clients();// print_r($get_all_clients); ?>
                <select class="form-control requiredField select2" name="client" id="client">
                    <option value="">Select Account</option>
                    @foreach($get_all_clients as $val)
                        <option value="<?php echo $val->id; ?>"><?php echo $val->client_name; ?></option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="email">Region</label>
                <select class="form-control requiredField select2" name="region" id="region">
                    <option value="">Select Region</option>
                    @foreach(CommonHelper::get_all_regions() as $row)
                        <option value="{{$row->id}}">{{$row->region_name}}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <button type="button" class="btn btn-default" onclick="Generate();">Submit</button>
        <div>&nbsp;</div>

        <div id="printBankReceiptVoucherList">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                            <?php //echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            <div id="filterBookDayList"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>


    <script>

        function Generate() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var client = $('#client').val();
            var region = $('#region').val();
            var m = '<?php echo $_GET['m']?>';
            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/reports/full_daily_activity_list_ajax',
                method:'GET',
                data:{region:region, from_date:from_date, to_date:to_date, client:client, m:m},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }

    </script>
@endsection