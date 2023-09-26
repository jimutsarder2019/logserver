<?php
//TODO:: CODE OPTIMIZE
namespace app\controllers;

ini_set('max_execution_time', '300');

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\web\Controller;
use yii\elasticsearch\Query;
use app\components\ApplicationHelper;

class ElasticController extends Controller
{	

	public function beforeAction($action) 
	{ 
		$this->enableCsrfValidation = false; 
		return parent::beforeAction($action); 
	}
	
	
	public function actionGet()
    {
		$from_date = Yii::$app->request->post('from_date');
		$to_date = Yii::$app->request->post('to_date');
		
		$from_hours = Yii::$app->request->post('from_hours');
		$from_mins = Yii::$app->request->post('from_mins');
		$to_hours = Yii::$app->request->post('to_hours');
		$to_mins = Yii::$app->request->post('to_mins');
		
		$user = Yii::$app->request->post('user');
		$mac = Yii::$app->request->post('mac');
		$src_ip = Yii::$app->request->post('src_ip');
		$dst_ip = Yii::$app->request->post('dst_ip');
		$nat_ip = Yii::$app->request->post('nat_ip');
		$limit = Yii::$app->request->post('limit', 50);
		$date_limit = Yii::$app->request->post('limit_date');
		$search = Yii::$app->request->post('search');
		$router = Yii::$app->request->post('router');
		$offset = Yii::$app->request->post('offset', 0);
		$page_name = Yii::$app->request->post('page_name', 'log');

		$message_filter = [];
		$mac_filter = [];
		$user_filter = [];
		$src_filter = [];
		$dst_filter = [];
		$nat_filter = [];
		$router_filter = [];
		
		if($mac){
			$mac_filter[] = self::filter_match_phrase_prefix($mac);
		}
		if($user){
			$user_filter[] = self::filter_match_phrase_prefix($user);
		}
		if($src_ip){
			$src_filter[] = self::filter_match_phrase_prefix($src_ip);
		}
		if($dst_ip){
			$dst_filter[] = self::filter_match_phrase_prefix($dst_ip);
		}
		if($nat_ip){
			$nat_filter[] = self::filter_match_phrase_prefix($nat_ip);
		}
		
		$message_filter = array_merge($mac_filter, $user_filter, $src_filter, $dst_filter, $nat_filter);	
		$message_filter_ppp = array_merge($mac_filter, $user_filter, $src_filter);	
		
		$router_filter = [];
		$router_list = ApplicationHelper::getRouters();
		
		if(!empty($router_list)){
			
			foreach($router_list  as $router_ip){
				if($router == 'all'){
					$router_filter[] = [
						"match"=> [
							"HOST"=> '.*'.$router_ip.'.*'
						]
					];
				}else{
					if($router == $router_ip){
						$router_filter[] = [
							  "match"=> [
								"HOST"=> '.*'.$router_ip.'.*'
							  ]
						];
					}
				}
			}
			
			if($from_date && $to_date && $from_hours && $from_mins && $to_hours && $to_mins && !empty($message_filter)){
				
				$date_filter[] = [
					"range"=>[
						"@timestamp"=>[
						                "time_zone"=> "+06:00", 
										"gte"=>"".$from_date."T".$from_hours.":".$from_mins.":00",
										"lte"=>"".$to_date."T".$to_hours.":".$to_mins.":59",
						]
					]
				];
				$date_filter_ppp[] = [
						"range"=>[
									"@timestamp"=>[
									    "time_zone"=> "+06:00", 
										"gte"=>"".$from_date."T00:00:00",
										"lte"=>"".$to_date."T23:59:59",
									]
						]
				];
				
				$message_filter_ppp = array_merge($message_filter_ppp, $date_filter_ppp);
				$message_filter = array_merge($message_filter, $date_filter);
				
				if(count($router_filter) > 1){
					$match  =	 [
						"bool"=> [
						  "should"=> $router_filter,
						  "must"=> $message_filter
						]
					];
				}else{
					$match  =	 [
						"bool"=> [
						  "must"=> array_merge($router_filter,$message_filter)	
						]
					];
				}
			}else if($from_date && $to_date && $from_hours && $from_mins && $to_hours && $to_mins){
				
				$date_filter[] = [
					"range"=>[
						"@timestamp"=>[
						                "time_zone"=> "+06:00", 
										"gte"=>"".$from_date."T".$from_hours.":".$from_mins.":00",
										"lte"=>"".$to_date."T".$to_hours.":".$to_mins.":59",
						]
					]
				];
				$date_filter_ppp[] = [
						"range"=>[
									"@timestamp"=>[
									    "time_zone"=> "+06:00", 
										"gte"=>"".$from_date."T00:00:00",
										"lte"=>"".$to_date."T23:59:59",
									]
						]
				];
				
				$message_filter_ppp = $date_filter_ppp;
				
				if(count($router_filter) > 1){
					$match  =	 [
						"bool"=> [
						  "should"=> $router_filter,
						  "must"=> $date_filter
						]
					];
				}else{
					$match  =	 [
						"bool"=> [
						  "must"=> array_merge($router_filter,$date_filter)	
						]
					];
				}
			}else if($date_limit){		
				$match = [
							"range"=>[
								 "@timestamp"=>[
									"gte"=>$date_limit
								 ]
						]
				];
			}else if($search){
				  if(count($router_filter) > 1){
					  $match  =	 [
						"bool"=> [
						  "must"=> self::filter_match_phrase_prefix($search),
						  "should"=> $router_filter
						]
					  ];
				  }else{  
					$match_prefix[] = self::filter_match_phrase_prefix($search);
					$match  =	 [
						"bool"=> [
						  "must"=> array_merge($router_filter,$match_prefix)
						]
					  ];
				  }
			}else{
				if($page_name == 'log'){
					$date_filter[] = [
							"range"=>[
								"@timestamp"=>[
								       "time_zone"=> "+06:00",
									   "gte" => "now-24h",
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
				}else{
					$match  =	 [
						"bool"=> [
						  "should"=> $router_filter
						]
					];
				}
			}

			$all_data = self::getQueryData($match, 'cloud-log-nat', $limit, $offset);

			$all_message = [];
			$all_syslog_data = [];
			

			if(!empty($all_data)){
				$all_syslog_data = self::dataProcess($all_data, true, $from_date, $to_date);	
			}else{
				if($search){
					$_filter[] = [
					  "match_phrase_prefix"=> [
						"MESSAGE"=> '.* '.$search.'. *'
					  ]
					];
					
					$match  =	 [
						"bool"=> [
						  "must"=> $_filter			
						  ]
					];
				}else{
					$match  =	 [
						"bool"=> [
						  "must"=> $message_filter_ppp		
						  ]
					];
				}
				
				$all_data = self::getQueryData($match, 'cloud-log-ppp', 1, 0);
				if(!empty($all_data)){
					$missing_user_data = $all_data[0]['_source']['MESSAGE'];
					$main_src_ip = $all_data[0]['_source']['HOST'];
					$message_array = explode(" ",$missing_user_data);
					if(isset($message_array[0],  $message_array[1])){
						$user_name = $message_array[0];
						$mac_ip = $message_array[1];
						$src_ip_string = $message_array[2];
						
						if($user_name == 'PPPLOG'){
						   $user_name = $message_array[1];
						   $mac_ip = $message_array[2];
						   $src_ip_string = $message_array[3];
						}
						
						$src_ip_array = explode("?",$src_ip_string);
						$src_ip = @$src_ip_array[0];

						$src_filter_nat[] = self::filter_match_phrase_prefix($src_ip);
						if($dst_ip){
							$dst_filter[] = self::filter_match_phrase_prefix($dst_ip);
						}
						if($nat_ip){
							$nat_filter[] = self::filter_match_phrase_prefix($nat_ip);
						}
						
						$src_filter_nat = array_merge($src_filter_nat, $dst_filter, $nat_filter);
						$date_filter_nat = [];
						
						if($from_date && $to_date && $from_hours && $from_mins && $to_hours && $to_mins){
							$date_filter_nat[] = [
								"range"=>[
									"@timestamp"=>[
													"time_zone"=> "+06:00", 
													"gte"=>"".$from_date."T".$from_hours.":".$from_mins.":00",
													"lte"=>"".$to_date."T".$to_hours.":".$to_mins.":59",
									]
								]
							];
						}
						$filter = array_merge($src_filter_nat, $date_filter_nat);
						$match  =	 [
								"bool"=> [
								  "must"=> $filter
								]
						];
						$all_data = self::getQueryData($match, 'cloud-log-nat', $limit, 0);
						
						if(!empty($all_data)){
							$all_syslog_data = self::dataProcess($all_data, false, $from_date, $to_date, $user_name, $mac_ip, $main_src_ip);
						}
					}
				}
			}
			
			$limit_date = '';
			if(!empty($all_syslog_data)){
				$limit_date = $all_syslog_data[count($all_syslog_data) - 1]['datetime'];
			}
			die(json_encode(['status'=>'success', 'data'=>$all_syslog_data, 'limit_date'=>$limit_date]));
		}else{
			die(json_encode(['status'=>'fail', 'data'=>[], 'limit_date'=>'']));
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
												"gte"=>"".$date_start."T00:00:00",
												"lte"=>"".$date_end."T23:59:59",
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

	private function dataProcess($all_data, $missing_find=true, $date_start = false, $date_end = false, $user_name = false, $mac_ip = false,  $main_src_ip = false)
	{
		$all_syslog_data = [];
		foreach($all_data as $key=>$data){
			$message_array = explode(", ",$data['_source']['MESSAGE']);
	
			$all_syslog_data[$key]['datetime_real'] = @$data['_source']['@timestamp'];
			$datetime2 = new \DateTime(@$data['_source']['@timestamp']);
			$all_syslog_data[$key]['datetime'] = $datetime2->format('d-m-Y H:i a');
			$all_syslog_data[$key]['host'] = @$data['_source']['HOST'];
			$all_syslog_data[$key]['user'] = 'N/A';
			$all_syslog_data[$key]['nat_ip'] = 'N/A';
			$all_syslog_data[$key]['nat_port'] = 'N/A';
			
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
				}
				
				if($k === 3){
					
					if (str_contains($message, '->[')) {
						$ipv6_data = explode("->", @$message);
						$ipv6_data_1 = explode("]:", @$ipv6_data[0]);
						$src_ip = str_replace('[','',@$ipv6_data_1[0]);
						$all_syslog_data[$key]['src_ip'] = $src_ip;
						
						$src_port = str_replace('[','',@$ipv6_data_1[1]);
						$all_syslog_data[$key]['src_port'] = $src_port;
						
						$ipv6_data_2 = explode("]:", @$ipv6_data[1]);
						$dest_ip = str_replace('[','',@$ipv6_data_2[0]);
						$all_syslog_data[$key]['destination_ip'] = $dest_ip;
						
						$dest_port = str_replace('[','',@$ipv6_data_2[1]);
						$all_syslog_data[$key]['destination_port'] = $dest_port;
					}else{
						$ip_data = explode("->", $message);
						$all_syslog_data[$key]['src_ip'] = @explode(":", @$ip_data[0])[0];
						$all_syslog_data[$key]['src_port'] = @explode(":", @$ip_data[0])[1];
						$all_syslog_data[$key]['destination_ip'] = @explode(":", @$ip_data[1])[0];
						$all_syslog_data[$key]['destination_port'] = @explode(":", @$ip_data[1])[1];
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
		}
						
		return $all_syslog_data;
	}
	
	private function getQueryData($match, $index = 'cloud-log-nat', $limit = 50, $offset = 0)
	{
		$query = new Query;
		$query->from($index);
		$query->query = $match;
		$query->orderBy(['@timestamp' => SORT_DESC]);
		$query->offset = $offset;
		$query->limit = $limit;
		$command = $query->createCommand();
		$response = $command->search();
		$all_data = [];
		if(!empty($response)){
			if(isset($response['hits']['hits']) && !empty($response['hits']['hits'])){
				$all_data = $response['hits']['hits'];
			}
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
}