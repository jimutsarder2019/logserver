<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Router;
use app\components\CustomController;


class DashboardController extends CustomController
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
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
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
		$routers = Yii::$app->db->createCommand( 'SELECT * FROM router ORDER BY id desc' )->queryAll();
		$users = Yii::$app->db->createCommand( 'SELECT * FROM user WHERE role > 1 ORDER BY id desc' )->queryAll();
		$active_users = Yii::$app->db->createCommand( 'SELECT COUNT(*) FROM user WHERE status = 1 and role > 1' )->queryScalar();
		
		
		
		// Geeting free CPU info
		$cpuUsage = shell_exec("top -bn 2 -d 0.01 | grep '^%Cpu' | tail -n 1");
		preg_match_all('/[0-9.]+/', $cpuUsage, $cpuUsageArray);
		$cpuUtilization = @$cpuUsageArray[0][0];


		// Geeting free RAM info

		$totalMemory = shell_exec('cat /proc/meminfo | grep "MemTotal" | awk \'{print $2}\'');
		$freeMemory = shell_exec('cat /proc/meminfo | grep "MemFree" | awk \'{print $2}\'');
		
		
		//print $totalMemory.'-'.$freeMemory;
		
		//die;
		$usedMemory = intval($totalMemory) - intval($freeMemory);
		$ramUtilization = (intval($usedMemory) / intval($totalMemory)) * 100;



		// Geeting free Space info

		$rootTotalSpace = disk_total_space("/");
		$rootFreeSpace = disk_free_space("/");
		$rootUtilization = ($rootTotalSpace - $rootFreeSpace) / $rootTotalSpace * 100;
		
		$diskFree = 100 - round($rootUtilization, 2);
		
		// uptime
		$uptime = shell_exec('uptime');
		preg_match('/up\s+(.*?),\s+(.*?)\s+/', $uptime, $matches);
		$days = str_replace(',', '', $matches[1]);
		$days = intval($days);
		
		
		/*$cpuUtilization = 0;
		$ramUtilization = 0;
		$rootUtilization = 0;
		$diskFree = 0;
		$days = 0;*/
		
		
		
        return $this->render('index', ['router_data'=>$routers, 
		'user_data'=>$users, 
		'users_count'=>$active_users, 
		'router_count'=>count($routers),
		'cpu'=>$cpuUtilization,
		'ram'=>round($ramUtilization, 2),
		'disk_use'=>round($rootUtilization, 2),
		'disk_free'=>round($diskFree, 2),
		'uptime'=>$days
		]);
    }
}
