<?php

namespace app\controllers;

ini_set('max_execution_time', '300');

require_once __DIR__ . '/../api/vendor/autoload.php';

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Router;
use app\init\CustomController;
use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

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
		
		
		$license_data = CustomController::getLicenseData();
		
		// Execute the "df -BG" command to get disk usage information
		//exec("df -BG /", $output);
		exec("df -BG/log_disk", $output);	 

		// Parse the output to get the usage information
		
		$used_percentage_formatted = 0;
		$remaining_percentage_formatted = 0;

		if (count($output) > 1) {

			$data = explode(" ", preg_replace('/\s+/', ' ', $output[1]));

			$total = $data[1];

			$used = $data[2];

			$remaining = $data[3];

		 

			// Remove the 'G' character and convert to numeric values

			$used = rtrim($used, 'G');

			$total = rtrim($total, 'G');

		 

			// Calculate the percentage

			$used_percentage = ($used / $total) * 100;

			$remaining_percentage = 100 - $used_percentage;

		 

			// Format the percentages

			$used_percentage_formatted = number_format($used_percentage, 2);

			$remaining_percentage_formatted = number_format($remaining_percentage, 2);

		}
		
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

		if($usedMemory && $totalMemory){
		    $ramUtilization = (intval($usedMemory) / intval($totalMemory)) * 100;
		}

		// Geeting free Space info

		$rootTotalSpace = disk_total_space("/");
		$rootFreeSpace = disk_free_space("/");
		$rootUtilization = ($rootTotalSpace - $rootFreeSpace) / $rootTotalSpace * 100;
		
		$diskFree = 100 - round($rootUtilization, 2);
		
		// uptime
		$uptime = shell_exec('uptime');
		preg_match('/up\s+(.*?),\s+(.*?)\s+/', $uptime, $matches);
		$days = str_replace(',', '', @$matches[1]);
		$days = intval($days);
		
		//INDEX STATS
		
		$final_data = [];		
		for($i=0; $i<7; $i++){
			$date = date('Y-m-d', strtotime('-'.$i.' days'));
			$data =  @file_get_contents('http://103.102.216.134:9200/nat-'.$date.'/_stats');
			if($data){
				$file_data = json_decode($data, 1);
				$file_data['_all']['primaries']['docs']['count'];
				$file_data['_all']['primaries']['store']['size_in_bytes'];
				$final_data[$date]['count'] = $file_data['_all']['primaries']['docs']['count'];
				$final_data[$date]['size'] = number_format($file_data['_all']['primaries']['store']['size_in_bytes']/1000000, 2).' MB';
			}
		}
        return $this->render('index', ['router_data'=>$routers, 
		'user_data'=>@$users,
		'users_count'=>0, 
		'router_count'=>count(@$routers),
		'cpu'=>@$cpuUtilization,
		'ram'=>round(@$ramUtilization, 2),
		'disk_use'=>round(@$used_percentage_formatted, 2),
		'disk_free'=>round(@$remaining_percentage_formatted, 2),
		'uptime'=>@$days,
		'max_user_count_limit'=>@$license_data['maximum_number_of_user_allow'],
		'license_expire'=>@$license_data['license_expire'],
		'index_data'=>@$final_data
		]);
    }
}
