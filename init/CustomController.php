<?php

namespace app\init; 
use Yii; use yii\web\Controller; 
use yii\web\NotFoundHttpException; 

class CustomController extends Controller {

   public function init(){
      parent::init();
	  $license_data = self::getLicenseData(); 
	  if(!empty($license_data)){
		 $today_date = date('Y-m-d');
		 if(isset($license_data['license_expire']) && $license_data['license_expire'] < $today_date){
			 die('The license has expired. Please contact the technical support. Call: 01617622600, Email: support@cloudhub.com.bd'); 
		 }
	 }else{ 
		 die('You are not registered to this system!'); 
	 }
   }
   
   public static function getLicenseData()
   {
       return [
	        "registration_name"=> "Cloud Hub",
	        "license_number"=> 20231112,
			"license_nic"=> "",
			"license_expire"=> "2024-12-31",//hint: 2023-12-06
			"upgradable_till"=> "2024-12-31",
			"maximum_number_of_account"=> "unlimited",
			"maximum_number_of_router"=> "unlimited",
			"maximum_number_of_user_allow"=> 1000,
			"maximum_number_of_user_allow_alert_perchantage"=> 80
	   ];
   }	   
}
?>