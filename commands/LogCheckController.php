<?php
namespace app\commands;

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use yii\elasticsearch\Query;
use app\components\ApplicationHelper;
use app\components\CustomController;

class LogCheckController extends Controller
{	

	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}	
	
	public function actionProcess()
    {
		ApplicationHelper::logger('Start Checking router log...');

		$router_list = ApplicationHelper::getRouters();
		
		$to_email = Yii::$app->db->createCommand( "SELECT accessToken FROM user where role=1 and username='admin'" )->queryScalar();
		
		if(!empty($router_list)){
			foreach($router_list  as $router_ip){
				
				$router_filter = [
							  "match"=> [
								"HOST"=> '.*'.$router_ip.'.*'
							  ]
						];
						
				$match  =	 [
					"bool"=> [
					  "must"=> $router_filter
					]
				];
				
				$query = new Query;
				$query->from('cloud-log-nat');
				$query->query = $match;
				$command = $query->createCommand();
				$response = $command->search();
				
				if(!empty($response)){
					if(isset($response['hits']['hits']) && empty($response['hits']['hits'])){
						self::sendMail($router_ip, $to_email);
					}
				}
			}
		}
		ApplicationHelper::logger('End Checking router log...');
    }
	
	private function sendMail($missing_router, $to_email)
	{
		$license_data = CustomController::getLicenseData();
		$post = [
				'from_name'=>'Cloudhub',
				'to_name'=>'Admin',
				'to'=>$to_email,
				'to_cc'=>'logreport@cloudhub.com.bd',
				'from'=>'support@cloudhub.com.bd',
				'message'=> "<p>Company Name: ".$license_data['registration_name']." </p> <p>License Number: ".$license_data['license_number']." </p> 
				<p>Any log data didn't find in this router (".$missing_router.")</p>",
				'subject'=> 'Log not found Alert',
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