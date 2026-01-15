<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <?php     ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Survey List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $data=DB::Connection('mysql2')->select('select count(s.survey_id)countt,c.client_name from survey as s
INNER JOIN
client c
ON
c.id=s.client_id
where s.status=1 GROUP by s.client_id order by countt desc');

                    ?>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row container-fluid">

                        <?php  foreach($data as $row): ?>
                        {{--<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="border: solid 1px #ccc">--}}
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="border: solid 1px #ccc">
                            <p>{{$row->client_name}} <span class="badge badge-primary">&nbsp;{{' '.$row->countt}}</span></p>
                        </div>
                        <?php endforeach;?>
                    </div>

                    <div class="lineHeight">&nbsp;</div>


                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="">Client Name</label>
                            <select name="ClientId" id="ClientId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($Client as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->client_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="">Region</label>
                            <select name="RegionId" id="RegionId" class="form-control select2">
                                <option value="">All</option>
                                <?php foreach($Region as $Fil):?>
                                <option value="<?php echo $Fil->id?>"><?php echo $Fil->region_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="filterByClientAndRegion();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <span id="Loader"></span>
                            <div class="row" id="ShowHide">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Tracking No</th>

                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Branch Name</th>
                                            <th class="text-center">Contact Person</th>
                                            <th class="text-center">Contact Number</th>
                                            <th class="text-center">Survey Date</th>
                                            <th class="text-center">Survery By</th>
                                            <th class="text-center">Surveyor Name</th>
                                            <th class="text-center">Region</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>

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

    <script>
        $(document).ready(function(){
            $('#ClientId').select2();
            $('#RegionId').select2();
        });
        function SurveyDelete(id,url)
        {
            if(confirm("Want TO Delete...? Press ok")) {
                var pageType = '<?php echo $_GET['pageType'] ?>';
                var parentCode = '<?php echo $_GET['parentCode'] ?>';
                var m = '<?php echo $_GET['m'] ?>';
                $.ajax({
                    url: '<?php echo url('/')?>/' + url + '',
                    type: "GET",
                    data: {id: id, m: m, pageType: pageType, parentCode: parentCode},
                    success: function (data) {
                        alert("Successfully Deleted");
                        $("#"+id).remove();
                        //jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
                    }
                });
            } else {
                alert('Delete Cancel');
            }
        }
        function filterByClientAndRegion()
        {

//            var from= $('#from').val();
//            var to= $('#to').val();
            var ClientId = $('#ClientId').val();
            var RegionId = $('#RegionId').val();
            var m = '<?php echo $m?>';
//            if(ClientId !="" || RegionId !="")
//            {
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/sdc/filterByClientAndRegionSurvey',
                type: 'Get',
                data: {ClientId: ClientId,RegionId:RegionId,m:m},

                success: function (response) {
                    //alert(response);
                    $('#data').html(response);


                }
            });
//            }
//            else{
//                $('#FilterError').html('<p class="text-danger">Please Select Client OR Region...!</p>');
//            }



        }

    </script>

@endsection