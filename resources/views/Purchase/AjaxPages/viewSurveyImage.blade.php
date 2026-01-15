<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$companyList = DB::table('company')->where('status','=','1')->where('id','!=',$m)->get();
CommonHelper::companyDatabaseConnection($m);

$SurveyDoc = DB::table('survey_document')->where('survey_document_id','=',$id)->first();


CommonHelper::reconnectMasterDatabase();
?>






        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6 text-center">
                <a href="{{url('/storage/app/').'/'.$SurveyDoc->image_file}}" target="_blank"><img style="text-align: center; width: 30%" src="{{url('/storage/app/').'/'.$SurveyDoc->image_file}}"></a>
            </div>
        </div>




