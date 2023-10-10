<?php
namespace app\commands;

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use yii\elasticsearch\Query;
use app\components\ApplicationHelper;

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
						self::sendMail($router_ip);
					}
				}
			}
		}
		
		ApplicationHelper::logger('End Checking router log...');
    }
	
	private function sendMail($missing_router)
	{
		$post = [
				'from_name'=>'Cloudhub',
				'to_name'=>'Rahul',
				'to'=>'engrahuldeb@gmail.com',
				'from'=>'sales@cloudhub.com.bd',
				'message'=> "Any log data didn't find in this router (".$missing_router.")",
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