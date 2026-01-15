<?php
	namespace App\Helpers;
	use DB;
	use Config;
	class RequestHiringHelper{
    	public static function getMasterTableValueById($param1,$param2,$param3,$param4){
			return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
		}
	}
?>