@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
?>
                                <form action="{{route('GeneralMachineProccessToSoStore')}}" method="post">

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Pipe Machine</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <!-- <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
            </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="for_disabled_btn">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <div class="head_machine">
                                                    <h1>Attached General production to</h1>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                 
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>General Production Plan</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="g_mr_id" onchange="mr_change(this)" class="form-control form-required" id="mr_id" required>
                                                                <option value="">Select Production Plan</option>   
                                                                @foreach($material_requisition_general as $item)
                                                                    <option value="{{ $item->id}}"  data-value="{{ $item->pp_id}}">
                                                                        {{ $item->order_no." -- ".$item->order_date." -- ".$item->sub_ic }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="g_pp_id" id="g_pp_id">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Created machine process</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="cmp" onchange="machineProcessAttachedToSoProduction()" class="form-control form-required" id="cmp" required>
                                                                 
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>bundle range</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input id="range_1" name="range_1" class="form-control" type="text">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input id="range_2" name="range_2" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <a onclick="machineProcessAttachedToSoProduction()" class="btn btn-primary mr-1"> Search</a>
                                                    
                                                </div>
 
                                                <!-- <div class="col-md-"></div> -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>So Production Plan</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="so_mr_id" onchange="getAndSetPPid()" class="form-control" id="so_mr_id" required>
                                                                <option value="">Select Production Plan</option>   
                                                                @foreach($material_requisition_so as $item)
                                                                    <option value="{{ $item->id}}"  data-value="{{ $item->pp_id}}">
                                                                        {{ $item->order_no." -- ".$item->order_date." -- ".$item->sub_ic }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="so_pp_id" id="so_pp_id">
                                                            
                                                        </div>
                                                    </div>   
                                                    <div class="form-group  hide">
                                                            <div class="col-md-4">
                                                                <label>Sale Order Qty</label>
                                                                <!-- <label>Purchase Request No.</label> -->
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <input disabled class="form-control" id="so_qty" type="text">

                                                            </div>
                                                    </div>
                                                    
                                                    <div class="form-group hide">
                                                            <div class="col-md-4">
                                                                <label>Qty Produced</label>
                                                                <!-- <label>Purchase Request No.</label> -->
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <input disabled class="form-control" id="produced" type="text">

                                                            </div>
                                                        </div>
                                                
                                                    <div class="form-group hide" >
                                                            <div class="col-md-4">
                                                                <label>Qty Remaining</label>
                                                                <!-- <label>Purchase Request No.</label> -->
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <input disabled class="form-control" id="remaining" type="text">

                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>


                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Product Name</th>
                                                                <th>Total Qty Issued</th>
                                                            </tr>
                                                            <tbody id="more_details">
                                                              
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" id="save" disabled class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            <div class="row borderBtmMnd pTB40">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="printBankPaymentVoucherList">
                            <div class="panel">
                                <div id="PrintPanel">
                                    <div id="ShowHide">
                                        <div class="table-responsive">
                                            <h5 style="text-align: center" id="h3"></h5>
                                            <table class="userlittab table table-bordered sf-table-list"
                                                id="TableExportToCsv">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"> 
                                                            <input type="checkbox" id="checkAll" onclick="checkAlla(event)">
                                                        </th>
                                                        <th class="text-center">S No</th>
                                                        <!-- <th class="text-center">Job Card No</th> -->
                                                    
                                                        <th class="text-center" >Out Roll No</th>
                                                        <th class="text-center" >Machine</th>
                                                        <th class="text-center" >Operator</th>
                                                        <th class="text-center" >Shift</th>
                                                        <th class="text-center" >Machine Process date</th>
                                                        <th>Ready Length</th>
                                                        <th class="text-center">machine process stage</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data_1">
                                                  
                                              
                                                   
                                            
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
    </form>

<script>


function checkAlla(e)
{
    let eventElement = e.target;

    let checkElement = document.querySelectorAll('.check');

    if(eventElement.checked)
    {
        checkElement.forEach((element,index) => {
            let elementValue = element.value
            element.checked = true;
            document.querySelector('.check_condition_'+elementValue).value = 1;
        })
    }
    else
    {
        checkElement.forEach((element,index) => {
            let elementValue = element.value
            element.checked = false;
            document.querySelector('.check_condition_'+elementValue).value = 0;

        })
    }
    checkBoxValidation()

}

function singleChecked(e)
{
    let eventElement = e.target;

    if(eventElement.checked)
    {
        document.querySelector('.check_condition_'+eventElement.value).value = 1;
    }
    else
    {
        document.querySelector('.check_condition_'+eventElement.value).value = 0;
    }
    checkBoxValidation()
}


function checkBoxValidation()
{
    let checkedFlag = false
    let checkElement = document.querySelectorAll('.check');


    checkElement.forEach((element,index) => {
            
            if(element.checked)
            {
                checkedFlag = true;
                return;
            } 
            
            
        })

    if(checkedFlag)    
    {
        $('#save').removeAttr('disabled')
    }
    else
    {
        $('#save').attr('disabled','disabled')
    }
}

    
function mr_change(datas)
{

    getAndSetPPid();
    var id = datas.value;
    $('#data_1').html('');
    $.ajax({
            // url: '<?php echo url('/')?>/selling/getMrData',
            url: '<?php echo url('/')?>/selling/getMrDataWithProductionPlanId',
            type: 'Get',
            data: {id:id},
            success: function (data) 
            {
                if(data)
                {
                 
                    $('#for_disabled_btn').val(1)
                    $('#more_details').empty();
                    $('#more_details').append(data);
                }
                else
                {
                    $('#for_disabled_btn').val(0)
                }
            }
        });
        RemainingQtyOfSaleOrder()
        var pp_id = $('#mr_id option:selected').attr('data-value');

        getMachineProcessAgainstPP(pp_id)

}

function RemainingQtyOfSaleOrder()
{

    var so_id = $('#so_id').val();
    var mr_id = $('#mr_id').val();
    var pp_id = $('#mr_id option:selected').attr('data-value');
    $.ajax({
            // url: '<?php echo url('/')?>/selling/getMrData',
            url: '<?php echo url('/')?>/selling/RemainingQtyOfSaleOrder',
            type: 'Get',
            data: {
                    so_id:so_id,
                    mr_id:mr_id,
                    pp_id:pp_id
                },
            success: function (data) {
                $('#produced').val(data[0])
                $('#so_qty').val(data[1])
                $('#remaining').val(data[2])
            }
        });

}



function productionPlanAgainstSo(datas)
{
    $('#mr_id').empty();
    var id = datas.value;

    $.ajax({
        // url: '<?php echo url('/')?>/selling/getMrData',
            url: '<?php echo url('/')?>/selling/productionPlanAgainstSo',
            type: 'Get',
            data:   {
                        id:id,
                        type: 2
                    },
            success: function (data) {
                
            $('#mr_id').append(data);

            }
        });

        RemainingQtyOfSaleOrder()
}


function getMachineProcessAgainstPP(pp_id)
{
    $('#cmp').empty();

    $.ajax({
        // url: '<?php echo url('/')?>/selling/getMrData',
            url: '<?php echo url('/')?>/selling/getMachineProcessAgainstPP',
            type: 'Get',
            data: {pp_id:pp_id},
            success: function (data) {
                if(data.length)
                {

                    var select = document.getElementById("cmp");
                    select.innerHTML = "";

                    var defaultOption = document.createElement("option");
                    defaultOption.text = "Select Machine Process";
                    defaultOption.value = "";
                    select.appendChild(defaultOption);

                    // Loop through the array and create options
                    data.forEach(function(dt) {
                        var option = document.createElement("option");
                        option.value = dt.id;
                        option.text = dt.serial_no + " - " + dt.machine_process_date;
                        select.appendChild(option);
                    });

                }
            }
        });
}
    function machineProcessAttachedToSoProduction()
    {
        $('#data_1').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

        document.querySelector('#checkAll').checked = false
            var machine_proccess_id = $('#cmp').val();
            let range_1 = $('#range_1').val();
            let range_2 = $('#range_2').val();
            var mr_id = $('#mr_id').val();
            var error = false;
            var error_value;
            $('.form-required').each(function(){
            //    console.log($(this).val() , $(this).attr('name'));
               if ($(this).val() == null || $(this).val() == '') {
                error = true;
                error_value = $(this).attr('name');
                return false;
               }
            });
            if (error == true) {
                alert(error_value + ' field is required');
                return false;
            }
            console.log('check');

            if($('#for_disabled_btn').val() == 1)
            {
                $('#save').removeAttr('disabled')
            }
            else
            {
                (machine_proccess_id)? $('#save').removeAttr('disabled') : $('#save').attr('disabled','disabled') ;
            }

            var Filter=$('#search').val();

           
            
            $.ajax({
                url: '<?php echo url('/')?>/selling/machineProcessAttachedToSoProduction',
                type: 'Get',
                data:   {
                            Filter:Filter,
                            machine_proccess_id:machine_proccess_id,
                            range_1:range_1,
                            range_2:range_2,

                        },
                success: function (response) {

                    $('#data_1').html(response);


                }
            });


    }

        // $(document).ready(function(){
        //     // viewProductInProccess();
        //     machineProcessAttachedToSoProduction();
        // });


   

    function getAndSetPPid()
    {


        let g_pp_id = $('#mr_id option:selected').attr('data-value');
        let so_pp_id = $('#so_mr_id option:selected').attr('data-value');

        $('#g_pp_id').val(g_pp_id)
        $('#so_pp_id').val(so_pp_id)
    }


</script>

@endsection