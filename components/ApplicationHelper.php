<?php
//work
namespace app\components;

use Yii;
use app\models\LoginHistory;
use app\models\Users;
use app\models\ReportBackup;

class ApplicationHelper
{
    public static function _setTrace($message, $stop_processing = 1)
    {
        echo "<pre>";
        if (is_array($message) || is_object($message)) {
            print_r($message);
        } else {
            print($message);
        }
        echo "</pre>";
        if ($stop_processing) {
            die(1);
        }
    }
    
    public static function loginHistoryStore($isCustomer=true)
    {
        $customer_id = Yii::$app->user->id;

        if($customer_id)
        {
			if($customer_id != 999){
				$user_name = '';
				if($isCustomer){
					$Customers = Users::findOne(['id' => $customer_id]);
					$user_name = isset($Customers->username) && $Customers->username?$Customers->username:'User-'.$customer_id;
				}

				
				$current_date = new \DateTime('Asia/Dhaka');

				$model = new LoginHistory();

				$model->user_id = $customer_id;
				$model->username = $user_name;
				$model->ip = @$_SERVER['REMOTE_ADDR'];
				$model->session_id = session_id();
				$model->checkin = $current_date->format('Y-m-d H:i:s');
				
				if($model->validate()){
					
				}else{
					print_r($model->getErrors());die;
				}

				$model->save();
			}
        }
    }
	
	
	
	public static function getCompanyName($field='company_name')
    {
        $settings = Yii::$app->db->createCommand( 'SELECT '.$field.' FROM settings where id > 0 limit 1' )->queryScalar();
		return $settings;
    }
	
	public static function getLoginUserInfo($field='authKey')
    {
		$customer_id = Yii::$app->user->id;
		
		if($customer_id){

			$user_data = Yii::$app->db->createCommand( 'SELECT '.$field.' FROM user where id='.$customer_id )->queryScalar();
			return $user_data;
		}
		
		return '';
    }
	
    public static function isAdmin()
    {
		if(Yii::$app->session->get('user_role')){
			return Yii::$app->session->get('user_role') == 1?true:false;
		}else{
			$customer_id = Yii::$app->user->id;
			if($customer_id){
				$role = Yii::$app->db->createCommand( 'SELECT role FROM user where id='.$customer_id )->queryScalar();
				return $role == 1?true:false;
			}
		}
		return false;
    }
	
	
	public static function getRole()
    {
		if(Yii::$app->session->get('user_role')){
			return Yii::$app->session->get('user_role');
		}else{
			$customer_id = Yii::$app->user->id;
			if($customer_id){
				$role = Yii::$app->db->createCommand( 'SELECT role FROM user where id='.$customer_id )->queryScalar();
				return $role;
			}
		}
		return 1;
    }
	
	
	public static function getRouterList()
    {
        $router_list = Yii::$app->db->createCommand( 'SELECT id, name, ip, type FROM router where status=1 and (type="nat" or type="nat_pppoe")' )->queryAll();
		return $router_list;
    }
	
	public static function getRouters()
    {
        $router_list = Yii::$app->db->createCommand( 'SELECT ip FROM router where status=1 and (type="nat" or type="nat_pppoe")' )->queryColumn();
		return $router_list;
    }
	
	public static function storeReportGenerateRecord($data, $count = 0)
	{
		$company_name = self::getCompanyName();
		$file_name = $company_name.'_LogReport_'.date('Y-m-d').'-'.rand(0,99999).'.'.$data['report_type'];
		$file_name = str_replace(' ','__', $file_name);
		
		$size = self::getTotalPossibleData($count);
		
		$model = new ReportBackup();
		$model->from_date = $data['from_date'];
		$model->to_date = $data['to_date'];
		$model->match1 = $data['match1'];
		$model->match2 = $data['match2'];
		$model->match_type = $data['match_type'];
		$model->report_type = $data['report_type'];
		$model->file_name = $file_name;
		$model->total_possible_data = $count;
		$model->total_possible_size = $size;
		$model->date = date('Y-m-d');
		
		if($model->validate()){
			if($model->save()){
				return true;
			}else{
				return false;
			}
		}else{
			ApplicationHelper::_setTrace($model->getErrors());
		}
	}
	
	public static function getTotalPossibleData($count)
    {
		$size = 0.121875 * $count;
		if($size > 1000){
			$size = $size/1000;
			$size = round($size, 2).' MB';
		}else{
			$size = round($size, 2).' KB';
		}
		return $size;
	}
	
	public static function logger($logmsg)
    {        
        $mainDir = '../logs/report/';
        $mainDirPath = $mainDir.date("Y.n.j").'.log';

        if (!is_dir($mainDir)) { //  Creating directory if not exist
            mkdir($mainDir,  0777, true);
        }
        
    	try {
    	    $logmsg = is_array($logmsg)?json_encode($logmsg):$logmsg;
    		$logmsg = "\n".date("Y.n.j H:i:s")." # ".$logmsg;
    		file_put_contents($mainDirPath,$logmsg,FILE_APPEND);
    	} catch(Exception $e) {
    		file_put_contents($mainDirPath,$e->getMessage(),FILE_APPEND);
    	}
    }

	// Function to get all the dates in given range 
	public static function getDatesFromRange($start, $end) { 
		
		// Declare an empty array 
		$array = array(); 
		  
		// Use strtotime function 
		$Variable1 = strtotime($start); 
		$Variable2 = strtotime($end); 
		  
		// Use for loop to store dates into array 
		// 86400 sec = 24 hrs = 60*60*24 = 1 day 
		for ($currentDate = $Variable1; $currentDate <= $Variable2;  
										$currentDate += (86400)) {
											  
			$Store = date('Y-m-d', $currentDate); 
			$array[] = $Store; 
		}
		
		return $array;
	}
}