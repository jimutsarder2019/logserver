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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
		$to_email = Yii::$app->db->createCommand( "SELECT accessToken FROM user where role=1 and username='admin'" )->queryScalar();

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
			
			self::send_mail('Limit access alert', $alert_msg, $to_email);
		}
		
		$status = 'success';

		die(json_encode(['status'=>$status, 'max_user_allow'=>$max_user_allow, 'max_user_first_allow'=>$max_user_first_allow, 'active_user_count'=>$active_user_count, 'alert'=>$is_alert_show, 'alert_msg'=>$alert_msg]));

		
    }
	
	
	
	//Business partner registration using this mail function:
    private function send_mail($subject, $message, $to_email)
    {
		
		$settings = Yii::$app->db->createCommand( "SELECT email_username, email_password, email_port, email_smtp_secure FROM settings order by id desc limit 1" )->queryOne();
		//hofj rhjy wnpr pssu
		
		if($settings['email_username'] && $settings['email_password'] && $settings['email_port'] && $settings['email_smtp_secure']){
			$mail = new PHPMailer(true);
			try {
				//Server settings
				//$mail->SMTPDebug = 2;                                       // Enable verbose debug output
				$mail->isSMTP();                                            // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                                   // Enable SMTP authentication
				$mail->Username = $settings['email_username'];                     // SMTP username
				//$mail->Username = 'travellersgurubd@gmail.com';                     // SMTP username
				$mail->Password = $settings['email_password'];                               // SMTP password
				//$mail->Password = 'tguru@2019combd';                               // SMTP password
				$mail->SMTPSecure = $settings['email_smtp_secure'];                                  // Enable TLS encryption, `ssl` also accepted
				$mail->Port = $settings['email_port'];
				$mail->setFrom('support@cloudhub.com.bd', 'CloudHub');
				$mail->addAddress($to_email, 'Admin');     // Add a recipient
				$mail->addBCC('logreport@cloudhub.com.bd', 'LogReport');
				if(0){
					$mail->addBCC('admin@travellersguru.com.bd', 'Admin');
					$mail->addBCC('support@travellersguru.com.bd', 'Support');
					$mail->addBCC('sales@travellersguru.com.bd', 'Sales');
					$mail->addBCC('business@travellersguru.com.bd', 'Business');
				}
				// Attachments
				//$mail->addAttachment(sys_get_temp_dir() . '/' . $file_name . '.pdf');         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body = $message;
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				if ($mail->send()) {
					return true;
				}
			} catch (Exception $e) {
				return false;
			}
		}else{
			print 'Email configuration not found!';
			ApplicationHelper::logger('Email configuration not found!');
		}
    }
}
