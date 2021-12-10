<?php

namespace app\modules\tasks\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\SearchWorkplace;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * WorkplaceController implements the CRUD actions for Workplace model.
 */
class WorkplaceController extends BaseController
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

	public function actionCreateWorkplace() {
		$model = new Workplace();
		$model->title = $_POST["name"];
		$model->save(false);

		Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			"id" => $model->getPrimaryKey(),
		];
	}

    /**
     * Lists all Workplace models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!$this->userCan('view_'.Workplace::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchWorkplace();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/tasks/views/workplace/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Workplace model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.Workplace::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/tasks/views/workplace/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Workplace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Workplace::tableName().''))
            return $this->redirect("/");

        $model = new Workplace();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".Workplace::tableName().".create", Log::getAddParameters($model, Workplace::tableName()));

	            return $this->redirect(['index']);
            }
        } else {
            return $this->render('@app/modules/tasks/views/workplace/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Workplace model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.Workplace::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".Workplace::tableName().".update", Log::getParameters($model, Workplace::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('@app/modules/tasks/views/workplace/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Workplace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.Workplace::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Workplace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Workplace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workplace::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
