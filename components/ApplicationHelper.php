<?php
//work
namespace app\components;

use Yii;
use app\models\LoginHistory;
use app\models\Users;

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
}