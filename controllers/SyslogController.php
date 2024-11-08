<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
//use yii\web\Response;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\init\CustomController;
use app\components\ApplicationHelper;

class SyslogController extends CustomController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
			'as beforeRequest' => [  //if guest user access site so, redirect to login page.
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow' => true,
					],
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$this->layout = 'frontend';
		$routers = ApplicationHelper::getRouterList();
		$search = Yii::$app->getRequest()->getQueryParam('search');
        return $this->render('index', array('search'=>$search, 'routers'=>$routers));
    }
	
	public function actionSearch()
    {
		$routers = ApplicationHelper::getRouterList();
		$this->layout = 'frontend';
        return $this->render('search', array('routers'=>$routers));
    }
	
	public function actionReport()
    {
		$routers = ApplicationHelper::getRouterList();
		$this->layout = 'frontend';
        return $this->render('report', array('routers'=>$routers));
    }
	
	public function actionData()
    {
		$page = Yii::$app->getRequest()->getQueryParam('page');
		$final_data = [];
		$params = require __DIR__ . '/../config/configuration.php';
		$start_number = 0;
		$end_number = 10;
		if($page > 1){
			$end_number = $end_number * $page;
			$start_number = $end_number - 10;
		}
		for($i=$start_number; $i<$end_number; $i++){
			$date = date('Y-m-d', strtotime('-'.$i.' days'));
			$data =  @file_get_contents('http://'.@$params['elasticSearchHttpAddress'].'/nat-'.$date.'/_stats');
			if($data){
				$file_data = json_decode($data, 1);
				if(isset($file_data['_all']['primaries']['docs'])){
				    $final_data[$date]['count'] = $file_data['_all']['primaries']['docs']['count'];
				}else{
					$final_data[$date]['count'] = 0;
				}
			    if(isset($file_data['_all']['primaries']['store'])){
				    $final_data[$date]['size'] = number_format($file_data['_all']['primaries']['store']['size_in_bytes']/1000000, 2).' MB';
				}else{
					$final_data[$date]['size'] = '';
				}
				$final_data[$date]['stats_url'] = 'http://'.@$params['elasticSearchHttpAddress'].'/nat-'.$date.'/_stats';
				$final_data[$date]['search_url'] = 'http://'.@$params['elasticSearchHttpAddress'].'/nat-'.$date.'/_search';
			}
		}
		if(!$page){
			$page = 1;
		}
		$this->layout = 'frontend';
        return $this->render('data', array('index_data'=>$final_data, 'page'=>($page+1)));
    }
	
	
	public function actionDownloadRequest(){
		$date = Yii::$app->getRequest()->getQueryParam('date');
		$count = Yii::$app->getRequest()->getQueryParam('count');
		//$all_data_count = self::getQueryCount($match, $date_list);
		$from_date = $date;
		$to_date = $date;
		$from_hours = '00';
		$from_mins = '01';
		$to_hours = '23';
		$to_mins = '59';
		$router_filter = [];
		$router_list = ApplicationHelper::getRouters();
		if(!empty($router_list)){
			
			foreach($router_list  as $router_ip){
				$router_filter[] = [
					"match"=> [
						"HOST"=> '.*'.$router_ip.'.*'
					]
				];
			}
		}
		
		$date_list = ApplicationHelper::getDatesFromRange($from_date, $to_date);
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
								"lte"=>"".$to_date."T23:59:59",
							]
				]
		];
		
		$message_filter_ppp = $date_filter_ppp;
		
	    $match  =	 [
			"bool"=> [
			  "should"=> $router_filter,
			  "must"=> $date_filter
			]
		];
		$report_type = 'csv';
		$match_type = 'nat';
		$report_match1 = json_encode($match);
		$report_match2 = json_encode($match);
		$params = ['from_date_to_date'=>$from_date.$from_hours.$from_mins.'_'.$to_date.$to_hours.$to_mins, 'from_date'=>$from_date."T".$from_hours.":".$from_mins.":00", 'to_date'=>$to_date."T".$to_hours.":".$to_mins.":59", 'report_type'=>$report_type, 'match1'=>$report_match1, 'match2'=>$report_match2, 'match_type'=>$match_type];
		//$params = ['from_date_to_date'=>$date.'00:01_'.$date.'23:59', 'from_date'=>$date."T00:01:00", 'to_date'=>$date."T23:59:59", 'report_type'=>'csv', 'match1'=>$report_match1, 'match2'=>$report_match2, 'match_type'=>$match_type];
		ApplicationHelper::storeReportGenerateRecord($params, $count);
		return $this->redirect(['data', 'msg' => true]);
	}
}
