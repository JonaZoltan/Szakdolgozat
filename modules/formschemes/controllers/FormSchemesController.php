<?php

namespace app\modules\formschemes\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\formschemes\models\FormSchemes;
use app\modules\formschemes\models\SearchFormSchemes;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormSchemesController implements the CRUD actions for FormSchemes model.
 */
class FormSchemesController extends BaseController
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
     * Lists all FormSchemes models.
     * @return mixed
     */

    public function actionIndex()
    {
        if(!$this->userCan('view_'.FormSchemes::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchFormSchemes();

        /** Session search */
        $session = Yii::$app->session;
        $params = Yii::$app->request->queryParams;
        if (!$params) $params = $session[ $searchModel::tableName() ]; else $session[ $searchModel::tableName() ] = $params;
        $dataProvider = $searchModel->search($params);
        /** Session search */

        return $this->render('@app/modules/formschemes/views/formschemes/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FormSchemes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.FormSchemes::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/formschemes/views/formschemes/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FormSchemes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.FormSchemes::tableName().''))
            return $this->redirect("/");

        $model = new FormSchemes();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".FormSchemes::tableName().".create", Log::getAddParameters($model, FormSchemes::tableName()));

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/formschemes/views/formschemes/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FormSchemes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.FormSchemes::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".FormSchemes::tableName().".update", Log::getParameters($model, FormSchemes::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/formschemes/views/formschemes/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FormSchemes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.FormSchemes::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FormSchemes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FormSchemes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FormSchemes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
