<?php
namespace app\controllers;

ini_set('max_execution_time', '300');

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;
use app\components\CustomController;

class ApiController extends CustomController
{	

	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
	
	public function actionUser()
    {
		$routers = Yii::$app->db->createCommand( 'SELECT * FROM router ORDER BY id desc' )->queryAll();
		
		$active_user_count = 0;
		
		if(!empty($routers)){
			
			foreach($routers as $router){
				
				if($router['status'] == 1){
					if($router['ip'] && $router['api_username'] && $router['api_password']){	
						try {
							$client = new Client([
								'host' => $router['ip'].':'.$router['api_port'],
								'user' => $router['api_username'],
								'pass' => $router['api_password']
							]);
							
							$query = new Query('/ppp/active/print');
							$query->where('service', 'pppoe');
							$secrets = $client->query($query)->read();
							
							if(!empty($secrets)){
								$active_user_count += count($secrets);
							}
						} catch (\Throwable $th) {
							//var_dump($th);
						}

					}
				}
				
			}
		}
		
		$is_alert_show = false;

		$license_data = CustomController::getLicenseData();
		$max_user_allow = @$license_data['maximum_number_of_user_allow'];
		$max_user_allow_perchantage = @$license_data['maximum_number_of_user_allow_alert_perchantage'];
		
		$max_user_first_allow = ($max_user_allow_perchantage * $max_user_allow)/100;
		
		$alert = 0;
		$alert_msg = '';
		if(($active_user_count > $max_user_first_allow) || ($active_user_count > $max_user_allow)){
			$alert_msg = 'Currently you are  using Cloud Hub log software.  We are requested to increase your user limit very soon.
                        </br> Call: +8801617622600, +8809610203060
                        </br> Email: sales@cloudhub.com.bd';
			if($active_user_count > $max_user_allow){
				$alert_msg = 'Currently you are  using Cloud Hub log software. You have already exceed your limit. We are requested to increase your user limit with in 48 hours.
                        </br> Call: +8801617622600, +8809610203060
                        </br> Email: sales@cloudhub.com.bd';
			}
			$is_alert_show = true;
			
			self::sendMail($alert_msg);
		}
		
		$status = 'success';

		die(json_encode(['status'=>$status, 'max_user_allow'=>$max_user_allow, 'max_user_first_allow'=>$max_user_first_allow, 'active_user_count'=>$active_user_count, 'alert'=>$is_alert_show, 'alert_msg'=>$alert_msg]));

		
    }
	
	
	private function sendMail($body)
	{
		if(isset(Yii::$app->user->id) && Yii::$app->user->id){
		    $id = Yii::$app->user->id;
			$user = Yii::$app->db->createCommand( 'SELECT username, accessToken FROM user where id='.$id )->queryOne();
			$name = $user['username'];
			$email = $user['accessToken'];
			
			if($email && $name){
				$post = [
						'from_name'=>'Cloudhub',
						'to_name'=>$name,
						'to'=>$email,
						'from'=>'sales@cloudhub.com.bd',
						'message'=> $body,
						'subject'=> 'Max User Limit cross Alert',
				];
					
				$url = 'https://www.travellersguru.com.bd/rest-api/send-alert-mail';
				$ch = curl_init();
				$params = http_build_query($post);
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,
							$params);
				
				// Receive server response ...
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				$server_output = curl_exec($ch);
				
				curl_close ($ch);
			
			}
			
		}
	}
}
