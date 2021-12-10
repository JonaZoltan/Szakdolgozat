<?php

namespace app\modules\project\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\project\models\Area;
use app\modules\project\models\SearchArea;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AreaController implements the CRUD actions for Area model.
 */
class AreaController extends BaseController
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

	public function actionCreateArea() {
		$model = new Area();
		$model->title = $_POST["name"];
		$model->save(false);

		Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			"id" => $model->getPrimaryKey(),
		];
	}

    /**
     * Lists all Area models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!$this->userCan('view_'.Area::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchArea();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/project/views/area/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Area model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.Area::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/project/views/area/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Area model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Area::tableName().''))
            return $this->redirect("/");

        $model = new Area();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".Area::tableName().".create", Log::getAddParameters($model, Area::tableName()));

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/project/views/area/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Area model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.Area::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".Area::tableName().".update", Log::getParameters($model, Area::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/project/views/area/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Area model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.Area::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Area::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
