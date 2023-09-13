<?php
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
		$user = Yii::$app->request->post('user');
		$mac = Yii::$app->request->post('mac');
		$src_ip = Yii::$app->request->post('src_ip');
		$dst_ip = Yii::$app->request->post('dst_ip');
		$nat_ip = Yii::$app->request->post('nat_ip');
		$mikrotik = Yii::$app->request->post('mikrotik');
		$limit = Yii::$app->request->post('limit', 100);
		$date_limit = Yii::$app->request->post('limit_date');
		$search = Yii::$app->request->post('search');
		$router = Yii::$app->request->post('router');
		
		$query = new Query;
		$query->from('syslog-ng');
		
		$message_filter = [];
		$mac_filter = [];
		$user_filter = [];
		$src_filter = [];
		$dst_filter = [];
		$nat_filter = [];
		$router_filter = [];
		
		if($mac){
			$mac_filter[] = [
					  "match_phrase_prefix"=> [
						"MESSAGE"=> '.*'.$mac.'.*'
					  ]
					];
		}
		if($user){
			$user_filter[] = [
					  "match"=> [
						"MESSAGE"=> '.*'.$user.'.*'
					  ]
					];
		}
		if($src_ip){
			$src_filter[] = [
					  "match"=> [
						"MESSAGE"=> '.*'.$src_ip.'.*'
					  ]
					];
		}
		if($dst_ip){
			$dst_filter[] = [
					  "match"=> [
						"MESSAGE"=> '.*'.$dst_ip.'.*'
					  ]
					];
		}
		if($nat_ip){
			$nat_filter[] = [
					  "match"=> [
						"MESSAGE"=> '.*'.$nat_ip.'.*'
					  ]
					];
		}
		
		$message_filter = array_merge($mac_filter, $user_filter, $src_filter, $dst_filter, $nat_filter);	
		
		$router_filter = [];
		$router_list = ApplicationHelper::getRouters();
		
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
		
		if($from_date && $to_date && !empty($message_filter)){
			
			$date_filter[] = [
					"range"=>[
								"@timestamp"=>[
												"gte"=>"".$from_date."T00:00:00+06:00",
												"lte"=>"".$to_date."T23:59:59+06:00",
								]
					]
			];
			
			
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
			$query->query = $match;
		}
		else if($from_date && $to_date){
			$date_filter[] = [
					"range"=>[
								"@timestamp"=>[
												"gte"=>"".$from_date."T00:00:00+06:00",
												"lte"=>"".$to_date."T23:59:59+06:00",
								]
					]
			];
			
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
			$query->query = $match;
		}else if(!empty($message_filter)){
              if(count($router_filter) > 1){
				  $match  =	 [
					"bool"=> [
					  "must"=> $message_filter,
					  "should"=> $router_filter
					]
				  ];
			  }else{
				  $match  =	 [
					"bool"=> [
					  "must"=> array_merge($router_filter,$message_filter)					
					  ]
				  ];
			  }
			 
			  $query->query = $match;
		}else if($date_limit){		
			$match = [
						"range"=>[
							 "@timestamp"=>[
								"gte"=>$date_limit
							 ]
				    ]
			];
			
			$query->query = $match;
		}else if($search){
			  if(count($router_filter) > 1){
				  $match  =	 [
					"bool"=> [
					  "must"=> [
						  "match_phrase_prefix"=> [
							"MESSAGE"=> '.*'.$search.'.*'
						  ]
					  ],
					  "should"=> $router_filter
					]
				  ];
			  }else{  
				$match_prefix[] = [
						  "match_phrase_prefix"=> [
							"MESSAGE"=> '.*'.$search.'.*'
						  ]
						];
				$match  =	 [
					"bool"=> [
					  "must"=> array_merge($router_filter,$match_prefix)
					]
				  ];
			  }
			  $query->query = $match;
		}else{
			$match  =	 [
				"bool"=> [
				  "should"=> $router_filter
				]
			];
			$query->query = $match;
		}
		
		$query->orderBy(['@timestamp' => SORT_DESC]);
        $query->offset = 0;
		if($limit){
            $query->limit = $limit;
		}
		
		$command = $query->createCommand();
		$response = $command->search();
		
		$all_data = [];
		$all_message = [];
		$all_syslog_data = [];
		if(!empty($response)){
			if(isset($response['hits']['hits']) && !empty($response['hits']['hits'])){
				$all_data = $response['hits']['hits'];
				
				foreach($all_data as $key=>$data){
					$MESSAGE = $data['_source']['MESSAGE'];
					$message_array = explode(", ",$MESSAGE);
					
							//print '<pre>';
		//print_r($message_array);
		//print '</pre>';
		
		//die;
					$all_message[] = $message_array;
					
					$all_syslog_data[$key]['datetime'] = $data['_source']['@timestamp'];
					$all_syslog_data[$key]['host'] = $data['_source']['HOST'];
					$all_syslog_data[$key]['user'] = 'N/A';
					$all_syslog_data[$key]['nat_ip'] = 'N/A';
					$all_syslog_data[$key]['nat_port'] = 'N/A';
					
					foreach($message_array as $k=>$message){
						
						
						$message_all[][] = $message;
						if(strpos($message, "Internet_Log:") !== false && strpos($message, "in:<pppoe-") !== false){
							//$message = 'syslog prerouting: in:<pppoe-icr.hasan> out:(unknown 0), connection-state:established src-mac d8:32:14:a0:4d:48, proto TCP (ACK,FIN), 10.45.3.253:7768->142.250.4.128:443, len 40';
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
						
						if($all_syslog_data[$key]['user'] == 'N/A'){
							if($data['_source']['HOST'] && $data['_source']['@timestamp']){
							self::getMissingUser($data['_source']['HOST'], $data['_source']['@timestamp'],$data['_source']['@timestamp']);
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
					}
					
					//print_r($message_all);
					
					//die;
								
				}
			}
		}
		
		$limit_date = '';
		if(!empty($all_syslog_data)){
		    $limit_date = $all_syslog_data[count($all_syslog_data) - 1]['datetime'];
		}
		
		die(json_encode(['status'=>'success', 'data'=>$all_syslog_data, 'limit_date'=>$limit_date]));
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function getMissingUser($router_ip, $from_date, $to_date)
    {	
		$query = new Query;
		$query->from('syslog-ng');
		
		
		$router_filter[] = [
					"match"=> [
						"HOST"=> '.*'.$router_ip.'.*'
					]
		];

        if(0){
			
			$date_filter[] = [
					"range"=>[
								"@timestamp"=>[
												"gte"=>"".$from_date."",
												"lte"=>"".$to_date."",
								]
					]
			];
			
					
			$match  =	 [
					"bool"=> [
					  "must"=> array_merge($router_filter,$date_filter)	
					]
			];
				
				
			$query->query = $match;
		}else{
		
		    $match  =	 [
				"bool"=> [
				  "should"=> $router_filter
				]
			];
			$query->query = $match;
		}
		
		$query->orderBy(['@timestamp' => SORT_DESC]);
        $query->offset = 0;
        $query->limit = 10;
		
		$command = $query->createCommand();
		$response = $command->search();
		
		$all_data = [];
		$all_message = [];
		$all_syslog_data = [];
		if(!empty($response)){
			if(isset($response['hits']['hits']) && !empty($response['hits']['hits'])){
				$all_data = $response['hits']['hits'];
				
				foreach($all_data as $key=>$data){
					$MESSAGE = $data['_source']['MESSAGE'];
					$message_array = explode(", ",$MESSAGE);
					
							//print '<pre>';
		//print_r($message_array);
		//print '</pre>';
		
		//die;
					$all_message[] = $message_array;
					
					$all_syslog_data[$key]['datetime'] = $data['_source']['@timestamp'];
					$all_syslog_data[$key]['host'] = $data['_source']['HOST'];
					$all_syslog_data[$key]['user'] = 'N/A';
					$all_syslog_data[$key]['nat_ip'] = 'N/A';
					$all_syslog_data[$key]['nat_port'] = 'N/A';
					
					foreach($message_array as $k=>$message){
						
						
						$message_all[][] = $message;
						if(strpos($message, "Internet_Log:") !== false && strpos($message, "in:<pppoe-") !== false){
							//$message = 'syslog prerouting: in:<pppoe-icr.hasan> out:(unknown 0), connection-state:established src-mac d8:32:14:a0:4d:48, proto TCP (ACK,FIN), 10.45.3.253:7768->142.250.4.128:443, len 40';
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
						
						if($all_syslog_data[$key]['user']){
							
						}else{
							
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
					}
					
					//print_r($message_all);
					
					//die;
								
				}
			}
		}
		print_r($all_syslog_data);
					
		die;
		$limit_date = '';
		if(!empty($all_syslog_data)){
		    $limit_date = $all_syslog_data[count($all_syslog_data) - 1]['datetime'];
		}
		
		die(json_encode(['status'=>'success', 'data'=>$all_syslog_data, 'limit_date'=>$limit_date]));
    }
}
