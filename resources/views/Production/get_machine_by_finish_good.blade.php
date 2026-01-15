<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
$LabCat = DB::Connection('mysql2')->select('select * from production_labour_category where status = 1');
?>

@include('select2')
<style>
    .multiselect {
        width: 200px;
    }

    .selectBox {
        position: relative;
    }

    .selectBox select {
        width: 100%;
        font-weight: bold;
    }

    .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    #checkboxes {
        display: none;
        border: 1px #dadada solid;
    }

    #checkboxes label {
        display: block;
    }

    #checkboxes label:hover {
        background-color: #1e90ff;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table id="myTable1" class="table table-bordered table-striped table-condensed tableMargin">
            <thead>
                <tr>

                    <th class="text-center">Machine Name</th>
                    <th class="text-center">Capacity(%)</th>
                    <th class="text-center">Qeue Time (Second)</th>
                    <th class="text-center">Move Time (Second)</th>
                    <th class="text-center">Wait Time (Second)</th>




                </tr>
            </thead>
            <tbody>
                <?php
                $Counter =0;
                        $OtherCOunter = 0;
                foreach($Machine as $Fil):
                        $Counter++;
                ?>
                    <tr class="text-center">
                        <td>
                            <?php echo strtoupper($Fil->machine_name);?>
                                <input type="hidden" id="machine_id" name="machine_id[]" value="<?php echo $Fil->id?>">
                        </td>
                        <td>
                            <input type="number" class="form-control requiredField" name="capacity[]" id="capacity<?php echo $Counter?>">
                        </td>

                        <td>
                            <input  type="number" class="form-control requiredField" name="que_time[]" id="que_time<?php echo $Counter?>"
                                    >
                        </td>
                        <td>
                            <input  type="number" class="form-control requiredField" name="move_time[]" id="move_time<?php echo $Counter?>"
                                    >
                        </td>
                        <td>
                            <input  type="number" class="form-control requiredField" name="wait_time[]" id="wait_time<?php echo $Counter?>"
                                    >
                        </td>




                    </tr>


                    <tbody id="AppendDataHere<?php echo $Counter?>">

                    </tbody>

                <?php
                $OtherCOunter++;
                endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<script>

    var AddMoreCounter = 1;
    function AddMoreLabourCategory(Row)
    {
        AddMoreCounter++;

        $('#AppendDataHere'+Row).append('<tr id="RemoveRow'+AddMoreCounter+'" class="text-center"><td colspan="5"></td>' +
                '<td>' +
                '<select class="form-control requiredField" name="labour_category[][]" id="labour_category'+AddMoreCounter+'">'+
                <?php foreach($LabCat as $Lfil):?>
                '<option value="<?php echo $Lfil->id?>"><?php echo $Lfil->labour_category?></option>'+
                <?php endforeach;?>
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="labour_category_value[][]" id="labour_category_value'+AddMoreCounter+'">' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-xs btn-danger" onclick="RemoveFunc('+AddMoreCounter+')">Remove</button>'+
                '</td>' +
                '</tr>');

    }


    function RemoveFunc(Row)
    {
        $('#RemoveRow'+Row).remove();
    }


    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }




    $(document).ready(function(){
        $('.select2').select2();
    });

    function getCharges(Row) {
        
        var vl = $("#labour_category" + Row).val();
        var base_url = '<?php echo URL::to('/'); ?>';

        if (vl != null) {
            $.ajax({
                url: base_url + '/production/getCharges',
                type: 'GET',
                data: {vl: vl},
                success: function (response) {
                    $('#charges' + Row).val(response);
                    calc(Row)
                }
            });
        }
        else
        {
            $('#charges'+Row).val(0);
            calc(Row)
        }


    }


    function calc(Row)
    {
        var Charges = parseFloat($('#charges'+Row).val());

        var NoLabour = parseFloat($('#no_of_labours'+Row).val());

        $('#total_labour_charges'+Row).val(parseFloat(NoLabour*Charges).toFixed(2));
    }
</script>
