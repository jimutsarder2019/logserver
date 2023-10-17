<?php
namespace app\commands;

ini_set('max_execution_time', '900');
ini_set('memory_limit', '16384M');

require_once __DIR__ . '/../api/vendor/autoload.php';

use kartik\mpdf\Pdf;
use Yii;
use yii\web\Controller;
use yii\elasticsearch\Query;
use app\components\ApplicationHelper;
use app\models\ReportBackup;

class ReportGenerateController extends Controller
{	

	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}	
	
	public function actionProcess()
    {
		$report_backup_list = $this->getReportBackupList();
		ApplicationHelper::logger('Report generate process start...');
		if(!empty($report_backup_list)){
		
			$company_name = ApplicationHelper::getCompanyName();
			$license_number = ApplicationHelper::getCompanyName('license_number');
			$company_address = ApplicationHelper::getCompanyName('company_address');
			$company_phone = ApplicationHelper::getCompanyName('company_phone');
			
			$licenseInfo = [
				'company'=>$company_name,
				'license'=>$license_number,
				'address'=>$company_address,
				'phone'=>$company_phone,
			];
			
			foreach($report_backup_list as $report_backup){
				
				$model = ReportBackup::findOne(['id' => @$report_backup['id']]);
				$model->status = 1;
				$model->save();
				
				$from_date = $report_backup['from_date'];
				$to_date = $report_backup['to_date'];
				$match_type = $report_backup['match_type'];
				$report_type = $report_backup['report_type'];
				$report_file_name = $report_type.'/'.$report_backup['file_name'];
				
				if($match_type == 'nat'){
					$offset = 0;
					$limit = 100;
					$match = json_decode($report_backup['match1'], 1);
					$all_data = self::getQueryData($match, 'cloud-log-nat', $limit, $offset);
					ApplicationHelper::logger('get log data from DB');
					if(!empty($all_data)){
						self::dataProcess($all_data, true, @$report_backup['id'], $report_type, $report_file_name, $from_date, $to_date, $licenseInfo);	
					}else{
						ApplicationHelper::logger('Log data not found!');
					}
				}else{
					$match_pp = json_decode($report_backup['match1'], 1);
					$match_nat = json_decode($report_backup['match2'], 1);
					$limit = 100;
					$all_data = self::getQueryData($match_pp, 'cloud-log-ppp', 1, 0);
					ApplicationHelper::logger('get log data from DB');
					if(!empty($all_data)){
						$missing_user_data = $all_data[0]['_source']['MESSAGE'];
						$main_src_ip = $all_data[0]['_source']['HOST'];
						$message_array = explode(" ",$missing_user_data);
						if(isset($message_array[0],  $message_array[1])){
							$user_name = $message_array[0];
							$mac_ip = $message_array[1];						
							if($user_name == 'PPPLOG'){
							   $user_name = $message_array[1];
							   $mac_ip = $message_array[2];
							}

							$all_data = self::getQueryData($match_nat, 'cloud-log-nat', $limit, 0);
							
							if(!empty($all_data)){
								self::dataProcess($all_data, false, @$report_backup['id'], $report_type, $report_file_name, $from_date, $to_date, $licenseInfo);
							}
						}
					}else{
						ApplicationHelper::logger('Log data not found!');
					}
				}
			}
			
			
			ApplicationHelper::logger('All the report generated successfully!');
			print "All the report generated successfully!";
	    }else{
			ApplicationHelper::logger('No report generate request found!');
			print "No report generate request found!";
		}
    }
	

	
	private function getMissingUser($src_ip, $date_start = false, $date_end = false)
    {
		$src_filter[] = self::filter_match_phrase_prefix($src_ip);
		$date_filter = [];
		if($date_start && $date_end){
			$date_filter[] = [
					"range"=>[
								"@timestamp"=>[												
												"time_zone"=> "+06:00", 
												"gte"=>$date_start,
												"lte"=>"".$date_end,
								]
					]
			];
		}
		
		$filter = array_merge($src_filter, $date_filter);
		$match  =	 [
					"bool"=> [
					  "must"=> $filter
					]
		];
			
		$all_data = self::getQueryData($match, 'cloud-log-ppp', 1, 0);
		
		$all_syslog_data = [];
		if(!empty($all_data)){
			$missing_user_data = $all_data[0]['_source']['MESSAGE'];
			$src_ip = $all_data[0]['_source']['HOST'];
			$message_array = explode(" ",$missing_user_data);
			if(isset($message_array[1],  $message_array[2])){
				$user_name = $message_array[1];
				$mac_ip = $message_array[2];
				return ['user'=>$user_name, 'mac'=>$mac_ip, 'router_ip'=>$src_ip];
			}
		}
    }

	private function dataProcess($all_data, $missing_find=true, $report_id, $report_type,$report_file_name, $date_start, $date_end, $licenseInfo, $user_name = false, $mac_ip = false,  $main_src_ip = false)
	{
		$all_syslog_data = [];
        
		if($report_type == 'pdf'){
			ApplicationHelper::logger('pdf file create done');
			$pdf = new Pdf([
                    // set to use core fonts only
                    'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
                    //'destination' => Pdf::DEST_FILE,
                    //'filename' =>'sss.pdf',
                    // your html content input
                    //'content' => $content,

                    'cssInline' => '.kv-heading-1{font-size:18px}',
                    // set mPDF properties on the fly
                    'options' => ['title' => 'LOG'],
                    // call mPDF methods on the fly
                    'methods' => [
                        'SetHeader' => [''],
                        'SetFooter' => ['{PAGENO}'],
                    ]
                ]);
		    $mpdf = $pdf->api;
	
	        $top_area_content = '<body>
			                        <h1>'.$licenseInfo['company'].' Log Report</h1></br>
			                        <p>License Number: '.$licenseInfo['license'].'</p>
			                        <p>Address: '.$licenseInfo['address'].'</p>
			                        <p>Phone Number: '.$licenseInfo['phone'].'</p>
			                        <p>Log Report: '.$date_start.' to '.$date_end.'</p>';
			$table_header = '<thead>
									<tr>
										<th style="border:1px solid #000000;" scope="col">DateTime</th>
										<th style="border:1px solid #000000;" scope="col">Router IP</th>
										<th style="border:1px solid #000000;"  scope="col">User</th>
										<th style="border:1px solid #000000;"  scope="col">Protocol</th>
										<th style="border:1px solid #000000;"  scope="col">MAC</th>
										<th style="border:1px solid #000000;"  scope="col">Src IP</th>
										<th style="border:1px solid #000000;"  scope="col">Port</th>
										<th style="border:1px solid #000000;"  scope="col">Dst IP</th>
										<th style="border:1px solid #000000;"  scope="col">Port</th>
										<th style="border:1px solid #000000;"  scope="col">NAT IP</th>
										<th style="border:1px solid #000000;"  scope="col">Port</th>
									</tr>
								</thead>';
			$mpdf->WriteHTML($top_area_content.'<table style="border:1px solid #000000; border-collapse: collapse;
">'.$table_header.'<tbody class="data-render">');
		}else{
			ApplicationHelper::logger($report_type.' file create done');
		    $fh = @fopen(__DIR__ . '/../web/uploads/report/'.$report_file_name, 'wb');
			$csvValueArray = [];
	        $csvValueArray[] = $licenseInfo['company'].' Log Report';
			fputcsv($fh, $csvValueArray);
	        $csvValueArray2[] = 'License Number: '.$licenseInfo['license'];
	        fputcsv($fh, $csvValueArray2);
			$csvValueArray3[] = 'Address: '.$licenseInfo['address'];
	        fputcsv($fh, $csvValueArray3);
			$csvValueArray4[] = 'Phone Number: '.$licenseInfo['phone'];
	        fputcsv($fh, $csvValueArray4);
			$csvValueArray5[] = 'Log Report: '.$date_start.' to '.$date_end;
	        fputcsv($fh, $csvValueArray5);
			
		    $header_data = [
			               0=>
			                  [
							  'datetime'=>'DateTime',
							  'host'=>'Router IP',
							  'user'=>'User',
							  'protocol'=>'Protocol',
							  'mac'=>'Mac',
							  'src_ip'=>'Src IP',
							  'src_port'=>'Port',
							  'destination_ip'=>'Destination IP',
							  'destination_port'=>'Port',
							  'nat_ip'=>'NAT IP',
							  'nat_port'=>'Port',
							  ]
					    ];
						
			self::csvXlsxGenerate($fh, $header_data, 0);		
		}
        $tr = '';
		foreach($all_data as $key=>$data){
			$message_array = explode(", ",$data['_source']['MESSAGE']);
	
			$all_syslog_data[$key]['datetime_real'] = @$data['_source']['@timestamp'];
			$datetime2 = new \DateTime(@$data['_source']['@timestamp']);
			$all_syslog_data[$key]['datetime'] = $datetime2->format('d-m-Y H:i a');
			$all_syslog_data[$key]['host'] = @$data['_source']['HOST'];
			$all_syslog_data[$key]['user'] = 'N/A';
			$all_syslog_data[$key]['nat_ip'] = 'N/A';
			$all_syslog_data[$key]['nat_port'] = 'N/A';
			$all_syslog_data[$key]['mac'] = 'N/A';
			
			foreach($message_array as $k=>$message){
				if(strpos($message, "Internet_Log:") !== false && strpos($message, "in:<pppoe-") !== false){
					$user_data = @explode("in:<pppoe-",$message);
					$last_user_data = @explode("out:", @$user_data[1]);
					if(!empty($last_user_data)){
						$user = str_replace('>','',@$last_user_data[0]);
						if($user != ''){
							$all_syslog_data[$key]['user'] = $user;
						}
					}
				}else if(strpos($message, "prerouting:") !== false && strpos($message, "in:<pppoe-") !== false){
					$user_data = @explode("in:<pppoe-",$message);
					$last_user_data = @explode("out:", @$user_data[1]);
					if(!empty($last_user_data)){
						$user = str_replace('>','',@$last_user_data[0]);
						if($user != ''){
							$all_syslog_data[$key]['user'] = $user;
						}
					}
				}else if(strpos($message, "PPPLOG") !== false){
					$user_data = @explode("PPPLOG",$message);
					$last_user_data = @explode(" ", @$user_data[1]);
					if(isset($last_user_data[0])){
						$user = $last_user_data[0];
						$all_syslog_data[$key]['user'] = $user;
					}
				}

				
				if(strpos($message, "src-mac") !== false){
					$mac1 = str_replace('src-mac ','',str_replace('connection-state:established','',$message));
					$mac1 = str_replace('connection-mark:speed','',$mac1);
					$mac1 = str_replace('connection-mark:cdn_ggc','',$mac1);
					$mac1 = str_replace('connection-mark:cdn_fna','',$mac1);
					$mac1 = str_replace('connection-state:new','',$mac1);
					$mac1 = str_replace(',snat','',$mac1);
					$all_syslog_data[$key]['mac'] = $mac1;
				}
				
				if(strpos($message, "proto") !== false){
					$all_syslog_data[$key]['protocol'] = @explode(" ", $message)[1];
					if($all_syslog_data[$key]['protocol'] == 'proto'){
						$all_syslog_data[$key]['protocol'] = @explode(" ", $message)[2];
					}
				}
				
				if($k === 3){
					
					if (str_contains($message, '->[')) {
						$ipv6_data = explode("->", @$message);
						$ipv6_data_1 = explode("]:", @$ipv6_data[0]);
						$src_ip = str_replace('[','',@$ipv6_data_1[0]);
						$src_ip = str_replace('NAT','',$src_ip);
						$src_ip = str_replace('(','',$src_ip);
						$all_syslog_data[$key]['src_ip'] = $src_ip;
						
						$src_port = str_replace('[','',@$ipv6_data_1[1]);
						$all_syslog_data[$key]['src_port'] = $src_port;
						
						$ipv6_data_2 = explode("]:", @$ipv6_data[1]);
						$dest_ip = str_replace('[','',@$ipv6_data_2[0]);
						$all_syslog_data[$key]['destination_ip'] = $dest_ip;
						
						$dest_port = str_replace('[','',@$ipv6_data_2[1]);
						$dest_port = str_replace(')','',$dest_port);
						$all_syslog_data[$key]['destination_port'] = $dest_port;
					}else{
						$ip_data = explode("->", $message);
						$all_syslog_data[$key]['src_ip'] = @explode(":", @$ip_data[0])[0];
						$all_syslog_data[$key]['src_ip'] = str_replace('NAT','',$all_syslog_data[$key]['src_ip']);
						$all_syslog_data[$key]['src_ip'] = str_replace('(','',$all_syslog_data[$key]['src_ip']);
						$all_syslog_data[$key]['src_port'] = @explode(":", @$ip_data[0])[1];
						$all_syslog_data[$key]['destination_ip'] = @explode(":", @$ip_data[1])[0];
						$all_syslog_data[$key]['destination_port'] = @explode(":", @$ip_data[1])[1];
						$all_syslog_data[$key]['destination_port'] = str_replace(')','',$all_syslog_data[$key]['destination_port']);
					}
				}
				
				
				if(strpos($message, "NAT") !== false){
					$nat_ip = str_replace(@$ip_data[1],'',str_replace(@$ip_data[0],'',$message));
					$nat_ip_array = str_replace(')','',str_replace('(','',str_replace('->','',str_replace('NAT','',$nat_ip))));
					$all_syslog_data[$key]['nat_ip'] = @explode(":", @$nat_ip_array)[0];
					$all_syslog_data[$key]['nat_port'] = @explode(":", @$nat_ip_array)[1];
				}
				
				if(isset($all_syslog_data[$key]['src_ip'], $data['_source']['@timestamp']) && $missing_find && $all_syslog_data[$key]['src_ip'] && $all_syslog_data[$key]['user'] == 'N/A'){
					$missing_user_data = self::getMissingUser($all_syslog_data[$key]['src_ip'], $date_start, $date_end);
				
					if(isset($missing_user_data['user']) && $missing_user_data['user']){
						$all_syslog_data[$key]['user'] = $missing_user_data['user'];
						$all_syslog_data[$key]['mac'] = $missing_user_data['mac'];
						$all_syslog_data[$key]['host'] = $missing_user_data['router_ip'];
					}
				}else{
					if($user_name && $mac_ip && $main_src_ip){
						$all_syslog_data[$key]['user'] = $user_name;									
						$all_syslog_data[$key]['mac'] = $mac_ip;
						$all_syslog_data[$key]['host'] = $main_src_ip;
					}
				}
			}
			
			if($report_type == 'pdf'){
				$tr = self::pdfGenerate($all_syslog_data, $key);
                $mpdf->WriteHTML($tr);				
			}else{
				self::csvXlsxGenerate($fh, $all_syslog_data, $key);		
			}	
		}
		
		if($report_type == 'pdf'){
			$mpdf->WriteHTML('</tbody></table></body>');
			ApplicationHelper::logger('log data write into pdf file done');
			$model2 = ReportBackup::findOne(['id' => $report_id]);
			$model2->status = 2;
			$model2->save();
			$mpdf->Output(__DIR__ . '/../web/uploads/report/'.$report_file_name);
		}else{
			$model2 = ReportBackup::findOne(['id' => $report_id]);
			$model2->status = 2;
			$model2->save();
			ApplicationHelper::logger('log data write into '.$report_type.' file done');
			fclose($fh);
		}
	}
	
	private function getQueryData($match, $index = 'cloud-log-nat', $limit = 50, $offset = 0)
	{
		$all_data = [];
		$query = (new Query)->from($index);
		$query->query = $match;
		$query->orderBy(['@timestamp' => SORT_ASC]);
		$query->limit = 500;
		
		foreach ($query->batch() as $key=>$rows) {
		    $all_data = array_merge($all_data,$rows);
		}
		return $all_data;
	}
	
	private function filter_match_phrase_prefix($search_string){
		return [
		  "match_phrase_prefix"=> [
			"MESSAGE"=> '.*'.$search_string.'.*'
		  ]
		];
	}
	
	
	
	private function getReportBackupList()
	{
		$report_backup_list = Yii::$app->db->createCommand( 'SELECT * FROM report_backup where status=0' )->queryAll();
		return $report_backup_list;
	}
	
	
	private function csvXlsxGenerate($fh, $raw_data, $key)
    {
		$csvValueArray = [];
		$csvValueArray[] = $raw_data[$key]['datetime'];
		$csvValueArray[] = $raw_data[$key]['host'];
		$csvValueArray[] = $raw_data[$key]['user'];
		$csvValueArray[] = $raw_data[$key]['protocol'];
		$csvValueArray[] = $raw_data[$key]['mac'];
		$csvValueArray[] = $raw_data[$key]['src_ip'];
		$csvValueArray[] = $raw_data[$key]['src_port'];
		$csvValueArray[] = $raw_data[$key]['destination_ip'];
		$csvValueArray[] = $raw_data[$key]['destination_port'];
		$csvValueArray[] = $raw_data[$key]['nat_ip'];
		$csvValueArray[] = $raw_data[$key]['nat_port'];
	 
		// Put the data into the stream
		fputcsv($fh, $csvValueArray);
    }
	
	private function pdfGenerate($raw_data, $key)
    {
		$tr = '<tr>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['datetime'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['host'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['user'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['protocol'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['mac'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['src_ip'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['src_port'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['destination_ip'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['destination_port'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['nat_ip'].'</td>
		       <td style="border:1px solid #000000;">'.$raw_data[$key]['nat_port'].'</td>
		      </tr>';
			  
		return $tr;
    }
}