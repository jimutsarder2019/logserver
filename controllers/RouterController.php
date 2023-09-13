<?php

namespace app\controllers;

ini_set('max_execution_time', '300');

require_once __DIR__ . '/../api/vendor/autoload.php';

use app\models\Router;
use app\models\RouterSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ApplicationHelper;
use app\components\CustomController;

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

/**
 * RouterController implements the CRUD actions for Router model.
 */
class RouterController extends CustomController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        //'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Router models.
     *
     * @return string
     */
    public function actionIndex()
    {
		$this->layout = 'frontend';
        $searchModel = new RouterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Router model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$this->layout = 'frontend';

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Router model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
		if(ApplicationHelper::isAdmin()){
			$this->layout = 'frontend';
			
			$model = new Router();

			if ($this->request->isPost) {
				if ($model->load($this->request->post())) {
					$model->identity = 'identity';
					$model->date = date('Y-m-d');
					if($model->validate()){
						
						try {
							$client = new Client([
								'host' => $model->ip,
								'user' => $model->api_username,
								'pass' => $model->api_password
							]);
							
							$query = new Query('/ppp/active/print');
							$query->where('service', 'pppoe');
							$secrets = $client->query($query)->read();
							
							print '<pre>';
							print_r($client);
							print '</pre>';
							die;
							if(1){
								if ($model->save()) {
									//return $this->redirect(['view', 'id' => $model->id]);
									return $this->redirect(['index']);
								}
							}
						} catch (\Throwable $th) {
							return $this->render('create', [
								'model' => $model,
								'error'=>'yes'
							]);
							
						}					
					}else{
						print_r($model->getErrors());die;
					}
				}
			} else {
				$model->loadDefaultValues();
			}

			return $this->render('create', [
				'model' => $model,
				'error'=>'no'
			]);
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
    }

    /**
     * Updates an existing Router model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		if(ApplicationHelper::isAdmin()){
			$this->layout = 'frontend';

			$model = $this->findModel($id);

			if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
				return $this->redirect(['index']);
			}

			return $this->render('update', [
				'model' => $model,
			]);
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
    }

    /**
     * Deletes an existing Router model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		if(ApplicationHelper::isAdmin()){
			$this->findModel($id)->delete();

			return $this->redirect(['index']);
	    }else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
    }

    /**
     * Finds the Router model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Router the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Router::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
