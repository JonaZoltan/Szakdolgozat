<?php

namespace app\modules\controlling\controllers;

use app\controllers\BaseController;
use app\modules\project\controllers\ProjectController;
use app\modules\project\models\Project;
use app\modules\project\models\SearchProject;
use app\modules\projectdescription\models\Projectdescription;
use app\modules\projectdescription\models\SearchProjectdescription;
use app\modules\tasks\models\SearchTasks;
use Yii;
use yii\filters\VerbFilter;

/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.07.29., 09:27:46
 * The used disentanglement, and any part of the code
 * ProjectStatController.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'matrix' project. 


class ProjectStatController extends BaseController
{

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

	public function actionIndex()
	{
		if(!$this->userCan('view_'.Project::tableName().''))
			return $this->redirect("/");

		$searchModel = new SearchProject();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('@app/modules/controlling/views/projectstat/index', [
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

		$searchModel = new SearchTasks(['project_id'=>$id]);
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$projectHours = Project::calcProjectHours($dataProvider->query);
		$projectTypeHours = Project::ProjectTypeHours($dataProvider->query);
		$projectLaborHours = Project::ProjecLaborHours($dataProvider->query);
		$projectMonthHours = Project::ProjectMonthHours($dataProvider->query);

		return $this->render('@app/modules/controlling/views/projectstat/view', [
			//'model' => $this->findModel($id),
			'model' => Project::findOne($id),
			'searchModel' => $searchModel,
			'projectHours' => $projectHours,
			'dataProvider' => $dataProvider,
			'projectTypeHours' => $projectTypeHours,
			'projectLaborHours' => $projectLaborHours,
			'projectMonthHours' => $projectMonthHours,

		]);
	}


}