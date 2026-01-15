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
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Quotation List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>


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
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Quotation No.</th>
                                            <th class="text-center">Tracking No.</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Quotation To</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Quotation Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $paramOne = "sdc/viewQuotationDetail?m=".$m;
                                            $paramTwo = "sdc/viewQuotationDetailTwo?m=".$m;
                                            $edit_url= url('/sales/editQuotation/');
                                            ?>

                                            @foreach($quotation as $row)
                                                <tr id="<?= $row->id ?>" >
                                                    <td class="text-center">{{$counter++}}</td>
                                                    <td class="text-center">{{ $row->quotation_no }}</td>
                                                    <td class="text-center">{{ $row->tracking_no }}</td>
                                                    <?php  ?>
                                                    <td class="text-center">{{ CommonHelper::get_client_name_by_id($row->client_id)}}</td>
                                                    <td class="text-center">{{ $row->quotation_to }}</td>
                                                    <td class="text-center">{{ $row->designation }}</td>
                                                    <td class="text-center">{{ CommonHelper::changeDateFormat($row->quotation_date) }}</td>
                                                    <td id="{{$row->id}}" class="">
                                                        <?php if($row->quotation_status == 1):?>
                                                        <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
                                                        <?php else:?>
                                                        <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
                                                        <?php endif;?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Quotation Detail')" type="button" class="btn btn-success btn-xs">View</button>
                                                    </td>
                                                    <?php //if($row->quotation_status == 1):?>
                                                    <td>
                                                        <a href='<?php echo  $edit_url.'/'.$row->id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs">Edit</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <button onclick="QuotationDelete('<?= $row->id ?>','smfal/QuotationDelete')" class="btn btn-success btn-xs">Delete</button>
                                                    </td>
                                                    <?php //else:?>
                                                    {{--<td class="text-center">--}}
                                                        {{--<i class="fa fa-ban" aria-hidden="true" style="color: red"></i>--}}
                                                    {{--</td>--}}
                                                    {{--<td class="text-center">--}}
                                                        {{--<i class="fa fa-ban" aria-hidden="true" style="color: red"></i>--}}
                                                    {{--</td>--}}
                                                    <?php //endif;?>

                                                </tr>

                                            @endforeach
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
                url: '/sdc/filterByClientAndRegionQuotation',
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
        function QuotationDelete(id,url)
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

        function delete_record(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deleteClientList',
                    type: 'Get',
                    data: {id: id},
                    success: function (response) {
                        alert('Deleted');
                        $('#' + id).remove();
                    }
                });
            }
            else{}
        }


        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/purchase_voucher_list_ajax',
                type: 'Get',
                data: {from: from,to:to},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>

@endsection