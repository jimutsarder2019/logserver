<?php
namespace app\commands;

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use yii\elasticsearch\Query;
use app\components\ApplicationHelper;
use app\components\CustomController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LogCheckController extends Controller
{	

	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}	
	
	public function actionProcess()
    {
		$license_data = CustomController::getLicenseData();
		
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
				
				$date_filter[] = [
							"range"=>[
								"@timestamp"=>[
								       "time_zone"=> "+06:00",
									   "gte" => "now-30s",
									   "lt" =>  "now"
								]
							]
				];
				$match  =	 [
					"bool"=> [
					  "must"=> $date_filter,
					  "should"=> $router_filter
					]
				];
				
				$query = new Query;
				$query->from('cloud-log-nat');
				$query->query = $match;
				$command = $query->createCommand();
				$response = $command->search();
				
				$message = "<p>Company Name: ".$license_data['registration_name']." </p> <p>License Number: ".$license_data['license_number']." </p> 
				<p>Any log data didn't find in this router (".$router_ip.")</p>";
				$subject = 'Log not found Alert';
				
				if(!empty($response)){
					if(isset($response['hits']['hits']) && empty($response['hits']['hits'])){
						self::send_mail($subject, $message, $to_email);
					}
				}
			}
		}
		ApplicationHelper::logger('End Checking router log...');
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