<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.07.29., 09:53:46
 * The used disentanglement, and any part of the code
 * SumListController.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'matrix' project. 


namespace app\modules\controlling\controllers;


use app\controllers\BaseController;
use app\modules\project\models\Project;
use app\modules\projectdescription\models\Projectdescription;
use app\modules\projectdescription\models\SearchProjectdescription;
use app\modules\tasks\models\SearchTasks;
use app\modules\tasks\models\Tasks;
use Yii;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

class SumListController extends BaseController
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
	public function actionSumList()
	{
		if(!$this->userCan('view_'.Tasks::tableName().''))
			return $this->redirect("/");

		$searchModel = new SearchTasks();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$projectHours = Project::calcProjectHours($dataProvider->query);
		$projectTypeHours = Project::ProjectTypeHours($dataProvider->query);
		$projectLaborHours = Project::ProjecLaborHours($dataProvider->query);
		$projectMonthHours = Project::ProjectMonthHours($dataProvider->query);

		return $this->render('@app/modules/controlling/views/sumlist/index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'projectHours' => $projectHours,
			'projectTypeHours' => $projectTypeHours,
			'projectLaborHours' => $projectLaborHours,
			'projectMonthHours' => $projectMonthHours,

		]);
	}

}