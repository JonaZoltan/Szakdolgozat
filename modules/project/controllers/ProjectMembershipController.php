<?php

namespace app\modules\project\controllers;

use app\modules\logs\models\Log;
use Yii;
use app\modules\project\models\ProjectMembership;
use app\modules\project\models\SearchProjectMembership;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectMembershipController implements the CRUD actions for ProjectMembership model.
 */
class ProjectMembershipController extends BaseController
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
     * Lists all ProjectMembership models.
     * @return mixed
     */

    public function actionIndex()
    {
        if(!$this->userCan('view_'.ProjectMembership::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchProjectMembership();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/project/views/membership/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectMembership model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.ProjectMembership::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/project/views/membership/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProjectMembership model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.ProjectMembership::tableName().''))
            return $this->redirect(['index']);

        $model = new ProjectMembership();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        	// Úgy is csak azok jelennek meg hozzáadásnál amelyekben leader
        	/*if(!$this->userCan('create_'.ProjectMembership::tableName().'')) {
		        $leader = ProjectMembership::getDb()->cache(function () use ($model) {
			        return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $this->userData['id'], 'leader' => true]);
		        }, 30);

		        if (!$leader)
			        return $this->redirect(['index']);
	        }*/

            if($model->save()) {
                Log::add("".ProjectMembership::tableName().".create", Log::getAddParameters($model, ProjectMembership::tableName()));

                return $this->redirect(['create']);
            }
        } else {
            return $this->render('@app/modules/project/views/membership/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProjectMembership model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
	    $model = $this->findModel($id);

	    $leader = ProjectMembership::getDb()->cache(function() use ($model) {
		    return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $this->userData['id'], 'leader' => true]);
	    }, 30);

	    if(!$this->userCan('update_'.ProjectMembership::tableName().'') && !$leader)
		    return $this->redirect("/");

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Log::add("".ProjectMembership::tableName().".update", Log::getParameters($model, ProjectMembership::tableName(), $model->user_id));

            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/project/views/membership/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProjectMembership model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

	    $leader = ProjectMembership::getDb()->cache(function() use ($model) {
		    return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $this->userData['id'], 'leader' => true]);
	    }, 30);

	    if(!$this->userCan('delete_'.ProjectMembership::tableName().'') && !$leader)
		    return $this->redirect("/");

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProjectMembership model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectMembership the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectMembership::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
