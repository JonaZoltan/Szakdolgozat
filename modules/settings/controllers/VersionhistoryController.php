<?php

namespace app\modules\settings\controllers;

use Yii;
use app\modules\settings\models\VersionHistory;
use app\modules\settings\models\VersionHistorySearch;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\users\models\User;
use app\modules\settings\models\VersionView;

use yii\jui\DatePicker;

/**
 * VersionhistoryController implements the CRUD actions for VersionHistory model.
 */
class VersionhistoryController extends BaseController
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
                    'seen' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VersionHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VersionHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VersionHistory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VersionHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VersionHistory();
        $load = $model->load(Yii::$app->request->post());
        
        if ($load && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VersionHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $load = $model->load(Yii::$app->request->post());
        
        if ($load && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VersionHistory model.
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
     * Finds the VersionHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VersionHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VersionHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSeen() {
        $user = User::current();
        $version_id = Yii::$app->request->post('version_id', '');
        
        if ($user && $version_id) {
            $record = new VersionView;
            $record->user_id = $user->id;
            $record->version_id = $version_id;
            $record->when = date("Y-m-d H:i:s");
            $record->save(false);
        }
    }
}
