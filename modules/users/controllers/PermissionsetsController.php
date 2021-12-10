<?php

namespace app\modules\users\controllers;

use Yii;
use app\modules\users\models\Permission;
use app\modules\users\models\PermissionSet;
use app\modules\users\models\PermissionSetSearch;
use app\modules\users\models\Capability;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\users\models\User;

/**
 * PermissionsetsController implements the CRUD actions for PermissionSet model.
 */
class PermissionsetsController extends BaseController
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
     * Lists all PermissionSet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionSetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermissionSet model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public static function handleCapabilities($model) {
        $capabilities = Yii::$app->request->post("capabilities", []);
        Permission::deleteAll('permission_set_id = ' . strval($model->id));
        foreach ($capabilities as $cap_id) {
            $cat_link_record = new Permission;
            $cat_link_record->permission_set_id = $model->id;
            $cat_link_record->capability_id = $cap_id;
            $cat_link_record->save(false);
        }
    }


    /**
     * Creates a new PermissionSet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PermissionSet();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            self::handleCapabilities($model);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PermissionSet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            self::handleCapabilities($model);
            return $this->redirect(['index']);
        } else {
            
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PermissionSet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermissionSet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermissionSet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermissionSet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
