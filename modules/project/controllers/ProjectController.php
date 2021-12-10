<?php

namespace app\modules\project\controllers;

use app\modules\logs\models\Log;
use app\modules\partners\models\Partners;
use app\modules\project\models\ProjectMembership;
use app\modules\project\models\ProjectWorktypes;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\User;
use Yii;
use app\modules\project\models\Project;
use app\modules\project\models\SearchProject;
use app\controllers\BaseController;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends BaseController
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
    /*
     * Worktype mentése a projectekhez
     */
    private static function handleWorktype($model){
    	ProjectWorktypes::deleteAll(['project_id' => $model->id]);

	    foreach ($model->workTypes as $worktypeId){
		    $projectWorktype = new ProjectWorktypes();
		    $projectWorktype -> worktype_id = $worktypeId;
		    $projectWorktype -> project_id = $model->id;
		    $projectWorktype ->save(false);

	    }
    }


    /**
     * Lists all Project models.
     * @return mixed
     */

    public function actionIndex()
    {
        if(!$this->userCan('view_'.Project::tableName().''))
            return $this->redirect("/");

        $searchModel = new SearchProject();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/project/views/project/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.Project::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/project/views/project/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Project::tableName().''))
            return $this->redirect("/");

        $model = new Project();


        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                Log::add("".Project::tableName().".create", Log::getAddParameters($model, Project::tableName()));

	            $member = new ProjectMembership();

	            $member -> project_id = $model->id;
	            $member -> user_id = User::current()->id;
	            $member -> leader = true;

	            $member -> save(false);
	            self::handleWorktype($model);


                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/project/views/project/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('update_'.Project::tableName().''))
            return $this->redirect("/");
        $model = $this->findModel($id);
		$model->workTypes = $model->projectHasWorktypes;



        if ($model->load(Yii::$app->request->post())) {
            //Log::add("".Project::tableName().".update", Log::getParameters($model, Project::tableName(), "Milyenadatotmodositott")); // TODO: Log update modositas neve(line name, labors name etc...)

            if($model->save()) {
            	self::handleWorktype($model);


                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('@app/modules/project/views/project/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->userCan('delete_'.Project::tableName().''))
            return $this->redirect("/");

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionLoadProjectUser($projectId, $userId) {
		$out = [];
		
		$leader = ProjectMembership::findOne(['project_id' => $projectId, 'user_id' => $userId, 'leader' => true]);

		if($leader) {
			$users = ProjectMembership::find()->where(['project_id' => $projectId])->all();

			foreach ($users as $user) {

				$modelUser = User::findOne($user->user_id);
				$out[] = [$modelUser->id => $modelUser->name];
			}
		}

		return Json::encode($out);
	}

}
