<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
//use yii\web\Response;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\components\CustomController;

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
     * {@inheritdoc}
     */
	 /*
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
	*/

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		$this->layout = 'frontend';
		$search = Yii::$app->getRequest()->getQueryParam('search');
        return $this->render('index', array('search'=>$search));
    }
	
	public function actionSearch()
    {
		$this->layout = 'frontend';
        return $this->render('search');
    }
	
	public function actionReport()
    {
		$this->layout = 'frontend';
        return $this->render('report');
    }
}
