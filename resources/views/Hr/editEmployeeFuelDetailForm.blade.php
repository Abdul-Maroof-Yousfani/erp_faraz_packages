<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = Input::get('id');

?>

<style>

    input[type="radio"], input[type="checkbox"]
    { width:30px;
        height:20px;
    }
    hr{border-top: 1px solid cadetblue}
    

    .name-d-d ul li {
        font-size: 17px;
        margin: 10px 0px 22px 0px;
    }

    .name-d-d-input ul li {
        margin: 7px 0px 10px 0px;
    }

    .depart-row .col-lg-3 {
        background-color: #080808;
        color: #fff;
        border-left: 1px solid #fff;
    }

    input[type="radio"]{ width:30px;
        height:20px;
    }

    .depart-row-two .col-lg-4 {
        background-color: #999;
        color: #fff;
        border-left: 1px solid #fff;
        padding: 7px 0px 2px 0px;
    }

</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <form method="post" action="{{url('had/editEmployeeFuelDetail')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                            <div class="gudia-gap">

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Date:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input name="fuel_date" id="fuel_date" type="date" value="{{ $employeeFuelData->fuel_date }}" class="form-control requiredField" required>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label> From:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input name="from" id="from" type="text" value="{{ $employeeFuelData->from }}" class="form-control requiredField" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label> To:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input name="to" id="to" type="text" value="{{ $employeeFuelData->to }}" class="form-control requiredField" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label> KM:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input name="km" id="km" type="number" value="{{ $employeeFuelData->km }}" class="form-control requiredField" required>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br>
                            <div style="float: right;">
                                <button style="text-align: center" class="btn btn-success" type="submit" value="Submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

