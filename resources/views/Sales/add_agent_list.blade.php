<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Credit Customer List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <th class="text-center col-sm-1">S.No</th>

                                                                <th class="text-center col-sm-3">Customer Name</th>
                                                                <th class="text-center col-sm-6">Agent</th>
                                                                <th class="text-center">Action</th>
                                                            </thead>

                                                            <tbody id="viewCreditCustomerList">
                                                                <?php

                                                                $customers = DB::Connection('mysql2')->table('customers')->where('status', '=', '1')->where('customer_type', '=', '3')->get();
                                                                        $Agent = DB::Connection('mysql2')->table('sales_agent')->get();

                                                                $counter = 1;
                                                                foreach($customers as $row){
                                                                ?>
                                                                <tr id="<?php echo $row->id?>">
                                                                    <td class="text-center"><?php echo $counter++;?></td>

                                                                    <td><?php echo $row->name;?></td>
                                                                    <td>
                                                                        <select name="AgentIds" id="AgentIds<?php echo $row->id?>" class="form-control select2" multiple>
                                                                            <?php foreach($Agent as $ag):?>
                                                                            <option value="<?php echo $ag->id?>" <?php
                                                                                    //if($row->agent_ids !=""):
                                                                                        if($row->sale_agent_id == $ag->id):
                                                                                            echo "selected";
                                                                                        endif;
                                                                                    if($row->purchase_agent_id == $ag->id):
                                                                                        echo "selected";
                                                                                    endif;
                                                                                    //endif;
                                                                                    ?>
                                                                                    ><?php echo $ag->agent_name?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                        <span id="ScNoError<?php echo $row->id?>"></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-primary" onclick="updateAgent('<?php echo $row->id?>')" id="BtnUpdate<?php echo $row->id?>"> Update</button>
                                                                    </td>

                                                                </tr>
                                                            <?php }?>
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
        });

        function updateAgent(Id)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            var AgentIds = $('#AgentIds'+Id).val();

//            if(AgentIds !=null)
//            {
                $('#ScNoError'+Id).html('');
                $.ajax({
                    url: base_url+'/sdc/update_agent_in_customer',
                    type: 'GET',
                    data: {Id: Id,AgentIds:AgentIds},
                    success: function (response) {
                        alert(response);
                    }
                });
//            }
//            else
//            {
//                $('#ScNoError'+Id).html('<p class="text-danger">Enter Sc No</p>');
//            }

        }
    </script>
@endsection