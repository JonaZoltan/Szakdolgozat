<?php

namespace app\modules\tasks\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\tasks\models\HolidayExtra;
use app\modules\tasks\models\SearchHolidayExtra;
use app\controllers\BaseController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HolidayExtraController implements the CRUD actions for HolidayExtra model.
 */
class HolidayExtraController extends BaseController
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
     * Lists all HolidayExtra models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new SearchHolidayExtra();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/tasks/views/holidayextra/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HolidayExtra model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@app/modules/tasks/views/holidayextra/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HolidayExtra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HolidayExtra();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	$model->disabled_user = Json::encode($model->disabled_user);

            if($model->save()) {
                Log::add("".HolidayExtra::tableName().".create", Log::getAddParameters($model, HolidayExtra::tableName()));

                return $this->redirect(['index']);
            }
        } else {
            return $this->render('@app/modules/tasks/views/holidayextra/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing HolidayExtra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	    $model->disabled_user = Json::decode($model->disabled_user);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
	        $model->disabled_user = Json::encode($model->disabled_user);
            Log::add("".HolidayExtra::tableName().".update", Log::getParameters($model, HolidayExtra::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
	            return $this->redirect(['index']);
            }
        } else {
            return $this->render('@app/modules/tasks/views/holidayextra/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing HolidayExtra model.
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
     * Finds the HolidayExtra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HolidayExtra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HolidayExtra::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
