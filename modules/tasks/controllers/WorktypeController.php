<?php

namespace app\modules\tasks\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\tasks\models\Worktype;
use app\modules\tasks\models\SearchWorktype;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * WorktypeController implements the CRUD actions for Worktype model.
 */
class WorktypeController extends BaseController
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

	public function actionCreateWorktype() {
		$model = new Worktype();
		$model->title = $_POST["name"];
		$model->save(false);

		Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			"id" => $model->getPrimaryKey(),
		];
	}

    /**
     * Lists all Worktype models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!$this->userCan('view_'.Worktype::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchWorktype();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/tasks/views/worktype/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Worktype model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.Worktype::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/tasks/views/worktype/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Worktype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Worktype::tableName().''))
            return $this->redirect("/");

        $model = new Worktype();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".Worktype::tableName().".create", Log::getAddParameters($model, Worktype::tableName()));

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/tasks/views/worktype/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Worktype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.Worktype::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".Worktype::tableName().".update", Log::getParameters($model, Worktype::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/tasks/views/worktype/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Worktype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.Worktype::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Worktype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Worktype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Worktype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
