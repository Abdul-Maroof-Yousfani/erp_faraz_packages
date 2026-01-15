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


?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <?php echo Form::open(array('url' => 'had/editEmployeeMedicalDetail',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                            <input type="hidden" name="id" id="id" value="<?php echo $employeeMedical->id ?>">
                            <input type="hidden" name="emr_no" id="emr_no" value="<?php echo $employeeMedical->emr_no ?>">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Disease Type:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="disease_type_id" id="disease_type_id" required>
                                        <option value="">Select Disease</option>
                                        @foreach($disease as $key => $y)
                                            <option @if($employeeMedical->disease_type_id == $y->id) selected @endif value="{{ $y->id}}">{{ $y->disease_type}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="sf-label">Date:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="date" name="disease_date" id="disease_date" value="{{ $employeeMedical->disease_date }}" class="form-control requiredField" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Amount:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="number" name="amount" id="amount" value="{{ $employeeMedical->amount }}" class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Cheque Number:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="cheque_number" id="cheque_number" value="{{ $employeeMedical->cheque_number }}" class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">File Upload:</label>
                                    <input type="file" name="medical_file_path[]" id="medical_file_path" class="form-control" multiple>
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
