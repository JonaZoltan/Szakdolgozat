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

class CostListController extends BaseController
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
	public function actionCostList()
	{
		if(!$this->userCan('view_finance'))
			return $this->redirect("/");

		$searchModel = new SearchTasks();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$projectCost = Project::calcProjectCost($dataProvider->query);
		$projectHours = Project::calcProjectHours($dataProvider->query);
		$projectRecommendedHours = Project::ProjectRecommendedHours($dataProvider->query);

		return $this->render('@app/modules/controlling/views/costlist/index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'projectCost' => $projectCost,
			'projectHours' => $projectHours,
			'projectRecommendedHours' => $projectRecommendedHours,
		]);
	}


}