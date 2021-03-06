<?php

namespace app\modules\users\controllers;

use Yii;
use app\modules\users\models\Permission;
use app\modules\users\models\PermissionSearch;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PermissionsController implements the CRUD actions for Permission model.
 */
class PermissionsController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param integer $permission_set_id
     * @param integer $capability_id
     * @return mixed
     */
    public function actionView($permission_set_id, $capability_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($permission_set_id, $capability_id),
        ]);
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Permission();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $permission_set_id
     * @param integer $capability_id
     * @return mixed
     */
    public function actionUpdate($permission_set_id, $capability_id)
    {
        $model = $this->findModel($permission_set_id, $capability_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $permission_set_id
     * @param integer $capability_id
     * @return mixed
     */
    public function actionDelete($permission_set_id, $capability_id)
    {
        $this->findModel($permission_set_id, $capability_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $permission_set_id
     * @param integer $capability_id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permission_set_id, $capability_id)
    {
        if (($model = Permission::findOne(['permission_set_id' => $permission_set_id, 'capability_id' => $capability_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
