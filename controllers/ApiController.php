<?php
namespace app\controllers;

ini_set('max_execution_time', '300');

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

class ApiController extends Controller
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
						    $config = (new Config())
								->set('timeout', 1)
								->set('host', $router['ip'])
								->set('user', $router['api_username'])
								->set('pass', $router['api_password']);

							// Initiate client with config object
							$client = new Client($config);

							// Get list of all available profiles with name Block
							$query = new Query('/ppp/active/print');
							$query->where('service', 'pppoe');
							$secrets = $client->query($query)->read();
							
							if(!empty($secrets)){
								$active_user_count += count($secrets);
							}
						}

						catch(Exception $e) {
						  //echo $e->getMessage();
						}

					}
				}
				
			}
		}
		
		$is_alert_show = false;
		
		$data = file_get_contents('../web/license.json');
		$license_data = json_decode($data, 1);
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
		}
		
		$status = 'success';

		die(json_encode(['status'=>$status, 'active_user_count'=>$active_user_count, 'alert'=>$is_alert_show, 'alert_msg'=>$alert_msg]));

		
    }
}
