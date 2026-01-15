@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
?>
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
                                <form action="{{route('storeGeneralMachineProccess')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="for_disabled_btn">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <div class="head_machine">
                                                    <h1>Create General Pipe Machine</h1>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                 
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Production Plan</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="mr_id" onchange="mr_change(this)" class="form-control" id="mr_id">
                                                                <option value="">Select Production Plan</option>   
                                                                @foreach($material_requisition as $item)
                                                                    <option value="{{ $item->id}}"  data-value="{{ $item->pp_id}}">
                                                                        {{ $item->order_no." -- ".$item->order_date." -- ".$item->sub_ic }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Created machine process</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="cmp" onchange="viewProductProccessComplete()" class="form-control" id="cmp">
                                                                 
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Finish Good type</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="finish_good_type" class="form-control" id="finish_good_type">
                                                                <option value="complete_finish_good"> Complete finish good </option>
                                                                <option value="semi_finish_good"> Semi finish good </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Machine Process Date</label>
                                                        <div class="col-sm-8">
                                                            <input name="machine_process_date" value="{{date('Y-m-d')}}" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Machine</label>
                                                        <div class="col-sm-8">
                                                        <select name="machine_id" id="machine_id" class="form-control" id="">
                                                            <option value="">Select Machine</option>
                                                            
                                                            @foreach($Machine as $key => $value )
                                                                <option value="{{ $value->id}}">{{ $value->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Operator</label>
                                                        <div class="col-sm-8">
                                                        <select name="operator_id" id="operator_id" class="form-control" id="">
                                                            <option value="">Select Operator</option>
                                                            
                                                            @foreach($Operator as $key => $value )
                                                                <option value="{{ $value->id}}">{{ $value->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Shift</label>
                                                        <div class="col-sm-8">
                                                        <select name="shift" id="shift" class="form-control" id="">
                                                                <option value="">Select Shift</option>
                                                                <option value="A">A</option>
                                                                <option value="B">B</option>
                                                        </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <input type="hidden" value="" name="machine_proccess_id" id="machine_proccess_id">
                                                        <label  class="col-sm-4 control-label" for="exampleDropdownFormEmail1">pieces</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" placeholder="" class="form-control requiredField" name="received_length" id="received_length" value="100">
                                                        </div>
                                                    </div>
                                                    <div class="form-group hide">
                                                        <label  class="col-sm-4 control-label" for="exampleDropdownFormEmail1">Bundle</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" placeholder="" class="form-control requiredField" name="Bundle" id="Bundle" min="1" value="1">
                                                        </div>
                                                    </div>
                                                    <div class="form-group hide">
                                                        <label  class="col-sm-4 control-label" for="exampleDropdownFormEmail1">Color Line</label>
                                                        <div class="col-sm-8">                                                    
                                                            <input type="text" placeholder="" value="" class="form-control requiredField" name="color" id="color" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label  class="col-sm-4 control-label" for="exampleDropdownFormEmail1">Remarks</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" placeholder=""  value="" class="form-control requiredField" name="remarks" id="remarks"  >
                                                        </div>
                                                    </div>

                                                  
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Serial No</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input name="serial_no" class="form-control" type="text">

                                                            
                                                        </div>
                                                    </div>
                                                    
                                                </div>
 
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                <div class="form-group  hide">
                                                        <div class="col-md-4">
                                                            <label>Sale Order Qty</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input disabled class="form-control" id="so_qty" type="text">

                                                        </div>
                                                </div>
                                                
                                                <div class="form-group">
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
                            </form>
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
                                                            <input type="checkbox" id="checkAll" onclick="checkAll(event)">
                                                        </th>
                                                        <th class="text-center">S No</th>
                                                        <!-- <th class="text-center">Job Card No</th> -->
                                                    
                                                        <th class="text-center" >Out Roll No</th>
                                                        <th class="text-center" >Machine</th>
                                                        <th class="text-center" >Operator</th>
                                                        <th class="text-center" >Shift</th>
                                                        <th class="text-center" >Machine Process date</th>
                                                        <th>Ready Length</th>
                                                        {{-- <th class="text-center">After Printing Weight</th> --}}
                                                        {{-- <th class="text-center">Transfer</th> --}}
                                                        <th class="text-center">machine process stage</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data_1">
                                                  
                                              
                                                   
                                            
                                                </tbody>
                                            </table>
                                            <a onclick="delete_all()" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
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
<script>

function mr_change(datas)
{
    var id = datas.value;
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
                    $('#save').removeAttr('disabled')
                }
                else
                {
                    $('#for_disabled_btn').val(0)
                    $('#save').attr('disabled','disabled')
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
        
    function viewProductProccessComplete()
    {
        document.querySelector('#checkAll').checked = false
            var machine_proccess_id = $('#cmp').val();

            if($('#for_disabled_btn').val() == 1)
            {
                $('#save').removeAttr('disabled')
            }
            else
            {
                (machine_proccess_id)? $('#save').removeAttr('disabled') : $('#save').attr('disabled','disabled') ;
            }

            var Filter=$('#search').val();

           
            $('#data_1').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/selling/viewProductProccessComplete',
                type: 'Get',
                data:   {
                            Filter:Filter,
                            machine_proccess_id:machine_proccess_id
                        },
                success: function (response) {

                    $('#data_1').html(response);


                }
            });


    }

        $(document).ready(function(){
            // viewProductInProccess();
            viewProductProccessComplete();
        });


        function checkAll(e)
        {
            let eventElement = e.target;

            let checkElement = document.querySelectorAll('.check');

            if(eventElement.checked)
            {
                checkElement.forEach((value,index) => {
                    value.checked = true
                })
            }
            else
            {
                checkElement.forEach((value,index) => {
                    value.checked = false
                })
            }
        }


        function delete_all()
        {
            let flag = false;

            let id = [];
            let machine_proccess_id = [];
            let batch_no = [];
            document.querySelectorAll('.check').forEach((element)=>{


                if(element.checked == true)
                {
                    flag = true;
                    id.push($('.id'+element.value).val());
                    machine_proccess_id.push($('.machine_proccess_id'+element.value).val());
                    batch_no.push($('.batch_no'+element.value).val());
                }
                
            })

            if(flag)
            {

                $.ajax({
                        url: '<?php echo url('/')?>/selling/delete_all',
                        type: 'POST',
                        data:   {
                                    id:id,
                                    machine_proccess_id:machine_proccess_id,
                                    batch_no:batch_no
                                },
                        success: function (response) {

                            if(response == 2)
                            {
                                window.location.reload()

                            }

                        }
                });

            }

           
        }



</script>

@endsection