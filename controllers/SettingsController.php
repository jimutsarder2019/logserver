<?php

namespace app\controllers;


use yii\filters\AccessControl;
use app\models\Settings;
use app\models\SettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\components\CustomController;


/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends CustomController
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Settings models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SettingsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Settings model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Settings();
		
		if ($model->load($this->request->post())) {
			$model->login_logo = UploadedFile::getInstance($model, 'login_logo');
			$model->user_logo = UploadedFile::getInstance($model, 'user_logo');
			$model->login_logo->saveAs('uploads/login_logo/' . $model->login_logo->baseName . '.' . $model->login_logo->extension);
			$model->user_logo->saveAs('uploads/user_logo/' . $model->user_logo->baseName . '.' . $model->user_logo->extension);

            $model->login_logo = 'uploads/login_logo/' . $model->login_logo->baseName . '.' . $model->login_logo->extension;
            $model->user_logo = 'uploads/user_logo/' . $model->user_logo->baseName . '.' . $model->user_logo->extension;
			if ($model->save()) {
			   return $this->redirect(['view', 'id' => $model->id]);
			}
		}

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id=1)
    {
		$this->layout = 'frontend';
		
        $model = $this->findModel($id);

        if ($model->load($this->request->post())) {
			
			$model->file1 = UploadedFile::getInstance($model, 'file1');
			$model->file2 = UploadedFile::getInstance($model, 'file2');
			$model->file3 = UploadedFile::getInstance($model, 'file3');
			
			if($model->file1){
			    $model->file1->saveAs('uploads/login_logo/' . $model->file1->baseName . '.' . $model->file1->extension);
			    $model->login_logo = 'uploads/login_logo/' . $model->file1->baseName . '.' . $model->file1->extension;
			}
			
			if($model->file2){
			    $model->file2->saveAs('uploads/user_logo/' . $model->file2->baseName . '.' . $model->file2->extension);
			    $model->user_logo = 'uploads/user_logo/' . $model->file2->baseName . '.' . $model->file2->extension;
			}
			
		    if($model->file3){
			    $model->file3->saveAs('uploads/favicon/' . $model->file3->baseName . '.' . $model->file3->extension);
			    $model->favicon = 'uploads/favicon/' . $model->file3->baseName . '.' . $model->file3->extension;
			}

			if ($model->save()) {
			    return $this->redirect(['update']);
			}
           
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Settings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
