<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use app\components\ApplicationHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\init\CustomController;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends CustomController
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
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
		$this->layout = 'frontend';
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
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
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
		if(ApplicationHelper::isAdmin()){
			$this->layout = 'frontend';
			$model = new Users();

			if ($this->request->isPost) {
				if ($model->load($this->request->post())) {
					$model->date = date('Y-m-d H:i:s');
					if ($model->save()) {
						//return $this->redirect(['view', 'id' => $model->id]);
						return $this->redirect(['index']);
				}
				}
			} else {
				$model->loadDefaultValues();
			}

			return $this->render('create', [
				'model' => $model,
			]);
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id=null)
    {
		$this->layout = 'frontend';
		if(!$id){
			$id = Yii::$app->user->id;
		}
		
		if(!ApplicationHelper::isAdmin()){
			if($id != Yii::$app->user->id){
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		
		if($id){
			$model = $this->findModel($id);

			if ($this->request->isPost && $model->load($this->request->post())) {
				
				$model->file = UploadedFile::getInstance($model, 'file');

				if($model->file){
					$model->file->saveAs('uploads/user_image/' . $model->file->baseName . '.' . $model->file->extension);
					$model->authKey = 'uploads/user_image/' . $model->file->baseName . '.' . $model->file->extension;
				}
				
				if ($model->save()) {
					return $this->redirect(['update', 'id' => $id]);
				}
			}

			return $this->render('update', [
				'model' => $model,
			]);
		}
    }
	//104 - kalinagar 131 - bugardana 132 -120        \    784 - 354

    /**
     * Deletes an existing Users model.
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
