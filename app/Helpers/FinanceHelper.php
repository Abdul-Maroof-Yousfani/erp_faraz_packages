<?php
	namespace App\Helpers;
	use DB;
	use Config;
	use App\Models\FinanceDepartment;
	use App\Models\Account;
	use App\Models\Transactions;
	use App\Models\Rvs;
	use Auth;
	use Session;

	class FinanceHelper{
    	public static function homePageURL(){
    		return url('/');
    	}

    	public static function test(){
			echo "hello";
		}

		public static function getCompanyName($param1){
			echo $companyName = DB::selectOne('select `name` from `company` where `id` = '.$param1.'')->name;
		}

		public static function dispalyVoucherAmountforEdit($param1,$param2,$param3,$param4,$param5,$param6){
			$dispalyAmountVoucher = DB::selectOne('select `amount` from '.$param2.' where `id` = '.$param6.' and `debit_credit` = '.$param5.'');
			if($dispalyAmountVoucher == ''){
				return '';
			}else{
				return number_format($dispalyAmountVoucher->amount);
			}
		}

		public static function displayApproveDeleteRepostButton($param1,$param2,$param3,$param4,$param5,$param6,$param7){
			if($param5 == 'pv_no'){
				$tableOne = 'pvs';
				$tableTwo = 'pv_data';
			}else if($param5 == 'rv_no'){
				$tableOne = 'rvs';
				$tableTwo = 'rv_data';
            }else if($param5 == 'jv_no'){
                $tableOne = 'jvs';
                $tableTwo = 'jv_data';
            }

			if($param3 == 1 && $param2 == 1){
		?>
			<button class="delete-modal btn btn-xs btn-primary btn-xs" data-dismiss="modal" aria-hidden="true" onclick="approveCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-ok"></span> Approve Voucher
           	</button>
			<button class="delete-modal btn btn-xs btn-danger btn-xs" data-dismiss="modal" aria-hidden="true" onclick="deleteCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-trash"></span> Delete Voucher
           	</button>
		<?php
			}else if($param3 == 2 && $param2 == 1){
		?>
			<button class="delete-modal btn btn-xs btn-warning btn-xs" data-dismiss="modal" aria-hidden="true" onclick="repostCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne;?>','<?php echo $tableTwo;?>')">
                <span class="glyphicon glyphicon-edit"></span> Repost Voucher
           	</button>
		<?php
			}
		}

		public static function getAccountNameByAccId($param1,$param2)
		{
			$accountName = '';
			if ($param1 != 0):
				static::companyDatabaseConnection($param2);
				$accountName = DB::selectOne('select `name` from `accounts` where `id` = '.$param1.'');
				static::reconnectMasterDatabase();
				if(!empty($accountName)) {
					$accountName = $accountName->name;
				} else {
					$accountName = '';
				}
			endif;
			return $accountName;
		}

        public static function getAccountIdByAccName($param1,$param2){
            static::companyDatabaseConnection($param1);
            $accountId = DB::selectOne('select `id` from `accounts` where `name` = "'.$param2.'"')->id;
            static::reconnectMasterDatabase();
            return $accountId;
    	}



		public static function companyDatabaseConnection($param1){
			$d = DB::selectOne('select `dbName` from `company` where `id` = '.Session::get('run_company').'')->dbName;
			Config::set(['database.connections.tenant.database' => $d]);
		//	Config::set(['database.connections.tenant.username' => 'innovative_unison']);
			Config::set('database.default', 'tenant');
			DB::reconnect('tenant');
		}

		public static function reconnectMasterDatabase(){
			Config::set('database.default', 'mysql');
			DB::reconnect('mysql');
		}

		public static function changeDateFormat($param1){
			$date = date_create($param1);
			echo date_format($date,"d-M-Y");
		}

		public static function checkVoucherStatus($param1,$param2){
			if($param1 == 1 && $param2 == 1){
				echo 'Pending';
			}else if($param2 == 2){
				echo 'Deleted';
			}else if($param2 == 3){
				echo 'Decline';
			}else if($param1 == 2 && $param2 == 1){
				echo 'Approved';
			}
		}

		public static function changeActionButtons($param1,$param2,$param3,$param4,$param5,$param6,$param7,$param8,$param9){
			if($param5 == 'pv_no'){
				$tableOne = 'pvs';
				$tableTwo = 'pv_data';
			}else if($param5 == 'rv_no'){
				$tableOne = 'rvs';
				$tableTwo = 'rv_data';
			}else if($param5 == 'jv_no'){
                $tableOne = 'jvs';
                $tableTwo = 'jv_data';
            }
		    if($param3 == 1 && $param2 == 1){
			?><button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"> P</span></button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>')"><span class="glyphicon glyphicon-trash"> P</span></button>
			<?php
				}else if($param3 == 2 && $param2 == 1){
			?><button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyFinanceTwoTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>')"><span class="glyphicon glyphicon-edit"> P</span></button>
			<?php
				}
			?><?php
				if($param3 != 2 && $param2 == 2){
			?><button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('<?php echo $param8;?>','<?php echo $param4 ?>','<?php echo $param9 ?>','<?php echo $param1?>')"><span class="glyphicon glyphicon-edit"> A</span></button> <button class="delete-modal btn btn-xs btn-danger btn-xs" onclick="deleteCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')"><span class="glyphicon glyphicon-trash"> A</span></button>
			<?php
				}else if($param3 == 2 && $param2 == 2){
			?><button class="delete-modal btn btn-xs btn-warning btn-xs" onclick="repostCompanyFinanceThreeTableRecords('<?php echo $param1 ?>','<?php echo $param2;?>','<?php echo $param3 ?>','<?php echo $param4 ?>','<?php echo $param5 ?>','<?php echo $param6 ?>','<?php echo $param7 ?>','<?php echo $tableOne; ?>','<?php echo $tableTwo;?>','transactions')"><span class="glyphicon glyphicon-edit"> A</span></button>
			<?php
				}
			?>
		<?php
		}

        public static function filterLedgerOpeningRow(){
            echo '0000';
        }

        public static function ChartOfAccountCurrentBalance($param1,$param2,$param3){
            static::companyDatabaseConnection($param1);
            $currentBalance = DB::selectOne("select coalesce(sum(`amount`),0)-(select coalesce(sum(`amount`),0) 
							from `transactions` 
							where substring_index(`acc_code`,'-',$param2) = '$param3' and `debit_credit` = 0 
							AND `status` = 1) as bal 
							from `transactions` 
							where substring_index(`acc_code`,'-',$param2) = '$param3' and `debit_credit` = 1 
							AND `status` = 1")->bal;
            static::reconnectMasterDatabase();
            return $currentBalance;
        }

		public static function get_finance_department()
		{
			$department=new FinanceDepartment();
			$department=$department->SetConnection('mysql2');
			$department=$department->where('status',1)->select('id','name')->get();
			return $department;
		}

		public static function get_accounts()
		{
			$accounts = new Account();
			$accounts=$accounts->SetConnection('mysql2');
			$accounts = $accounts->where('status',1)->select('id','code','name')->orderBy('level1', 'ASC')
				->orderBy('level2', 'ASC')
				->orderBy('level3', 'ASC')
				->orderBy('level4', 'ASC')
				->orderBy('level5', 'ASC')
				->orderBy('level6', 'ASC')
				->orderBy('level7', 'ASC')
				->get();
			return $accounts;
		}


		public static function get_accounts_for_purchase_voucher()
		{
			$accounts = new Account();
			$accounts=$accounts->SetConnection('mysql2');
			$accounts = $accounts->where('status',1)->where('code','like', '4%')->select('id','code','name')->orderBy('level1', 'ASC')
				->orderBy('level2', 'ASC')
				->orderBy('level3', 'ASC')
				->orderBy('level4', 'ASC')
				->orderBy('level5', 'ASC')
				->orderBy('level6', 'ASC')
				->orderBy('level7', 'ASC')
				->get();
			return $accounts;
		}

		public static function get_accounts_nature($number)
		{
			$nature='';
		if($number==1):
			$nature='ASSETS';
			endif;
			if($number==2):
				$nature='LIABILITIES';
			endif;
			if($number==3):
				$nature='EQUITY';
			endif;
			if($number==4):
				$nature='EXPS';
			endif;
			if($number==5):
				$nature='REVENUE';
			endif;
			return $nature;
		}

		public static   function getAccountCodeByAccId($id)
		{
			if ($id!=0):	
				$account=new Account();
				$account=$account->SetConnection('mysql2');
				$account=$account->where('status',1)->where('id',$id)->select('code')->first();
				if($account)
				{
					return $account->code;
				}
				else
				{
					return '' ;
				}
			else:
				return '';
			endif;
		}

		public  static	function  get_opening_bal($id)
		{
			$default_value="0,0";
			$trans=new Transactions();
			$trans=$trans->SetConnection('mysql2');
			$trans=$trans->where('status',1)->where('acc_id',$id)->where('opening_bal',1)->select('amount','debit_credit');
			if ($trans->count() >0):
			return $trans->first()->amount.','.$trans->first()->debit_credit;
			else:
			$code=self::getAccountCodeByAccId($id);
			return	self::set_nature($code);

				endif;
		}

	public static	function set_nature($nature)
		{
			$value='0';
			$nature=explode('-',$nature);
			$nature=$nature[0];
			if ($nature==1 || $nature==4):
				$value="0,1";
				else:
					$value="0,0";
				endif;
			return $value;
		}

		public static function indent_Commission($id)
		{
			$exists=0;
			$rvs = new Rvs();
			$rvs = $rvs->SetConnection('mysql2');
			$rvs = $rvs->where('status',1)->where('id',$id)->where('exchange_rate','!=','');
			if ($rvs->count() >0):
				$exists=1;
			endif;
			return $exists;
		}


		public static	function  audit_trail($voucher_no,$voucher_date,$amount,$table,$action)
		{

			date_default_timezone_set("Asia/Karachi");
			$data=array
			(
				'voucher_no'=>$voucher_no,
				'voucher_date'=>$voucher_date,
				'amount'=>$amount,
				'table_name'=>$table,
				'action'=>$action,
				'action_date'=>date('Y-m-d'),
				'username'=>Auth::user()->name,
				'action_time'=>date('h:i:sa'),
			);

			DB::Connection('mysql2')->table('audit_trail')->insert($data);
		}
		
		public static function get_buyer_opening_by_buyer_id($id)
		{
			return DB::Connection('mysql2')->table('customer_opening_balance')->where('buyer_id',$id)->get();
		}


		public static function un_approved_amount($id)
		{
			$debit_amount=static::get_un_approved_debit_credit($id,1);
			$credit_amount=static::get_un_approved_debit_credit($id,0);

			return $debit_amount-$credit_amount;
		}



		public static function get_un_approved_debit_credit($acc_id,$debit_credt)
		{
			$jv=DB::Connection('mysql2')->selectOne('select sum(amount)amount from new_jvs a
			inner join
			new_jv_data b
			on
			a.id=b.master_id
			where a.status=1
			and a.jv_status=1
			and debit_credit="'.$debit_credt.'"
			and acc_id="'.$acc_id.'"')->amount;



			$pv=DB::Connection('mysql2')->selectOne('select sum(amount)amount from new_pv a
			inner join
			new_pv_data b
			on
			a.id=b.master_id
			where a.status=1
			and a.pv_status=1
			and debit_credit="'.$debit_credt.'"
			and acc_id="'.$acc_id.'"')->amount;

			$rv=DB::Connection('mysql2')->selectOne('select sum(amount)amount from new_rvs a
			inner join
			new_rv_data b
			on
			a.id=b.master_id
			where a.status=1
			and a.rv_status=1
			and debit_credit="'.$debit_credt.'"
			and acc_id="'.$acc_id.'"')->amount;


			return $jv+$pv+$rv;
		}
	}
?>