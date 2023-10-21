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
		self::send_mail();
		
		die;
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
				
				if(!empty($response)){
					if(isset($response['hits']['hits']) && empty($response['hits']['hits'])){
						self::sendMail($router_ip, $to_email);
					}
				}
			}
		}
		self::send_mail();
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
	
	
	//Business partner registration using this mail function:
    private function send_mail()
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
             $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = 'travellersgurubd@gmail.com';                     // SMTP username
                $mail->Password = 'tguru@2019combd';                               // SMTP password
                $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; 
            $mail->setFrom('jimutsarder@gmail.com', 'Jimut sarder');
            $mail->addAddress('engrahuldeb@gmail.com', 'Rahul deb');     // Add a recipient
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
            $mail->Subject = 'Travellers Guru Registration';
            $mail_body = 'Dear Sir/Madam,<br>';
            $mail_body = $mail_body . 'Your registration has been completed successfully. Thanks for choosing Travellers Guru. ';
            $mail->Body = $mail_body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            if ($mail->send()) {
				die("okokoko");
                return true;
            }else{
				die("no");
			}
        } catch (Exception $e) {
            return false;
        }

    }
}