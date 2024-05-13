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
		$final_data = [];
		$params = require __DIR__ . '/../config/configuration.php';
		for($i=0; $i<7; $i++){
			$date = date('Y-m-d', strtotime('-'.$i.' days'));
			$data =  @file_get_contents('http://'.@$params['elasticSearchHttpAddress'].'/nat-'.$date.'/_stats');
			if($data){
				$file_data = json_decode($data, 1);
				$file_data['_all']['primaries']['docs']['count'];
				$file_data['_all']['primaries']['store']['size_in_bytes'];
				$final_data[$date]['count'] = $file_data['_all']['primaries']['docs']['count'];
				$final_data[$date]['size'] = number_format($file_data['_all']['primaries']['store']['size_in_bytes']/1000000, 2).' MB';
			}
		}
		$this->layout = 'frontend';
        return $this->render('data', array('index_data'=>$final_data));
    }
}
