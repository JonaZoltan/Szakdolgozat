<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.07.30., 10:15:46
 * The used disentanglement, and any part of the code
 * TimelineController.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'matrix' project. 


namespace app\modules\controlling\controllers;


use app\controllers\BaseController;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Tasks;
use app\modules\users\models\User;
use yii\filters\VerbFilter;

class TimelineController extends BaseController
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

	public function actionIndex($baseData = NULL)
	{
		if (!$baseData) {
			return $this->redirect('?baseData='.base64_encode('{"searchDate":"'.(date('Y-m-d')).'"}'));
		} else {
			$getData = json_decode(base64_decode($baseData));
		}

		$users = User::find()->where(['id' => $this->userData['id']])->all();
		$projects = Project::find()->indexBy('id')->all();
		$tasks = Tasks::find()->where(['date(working_datetime_start)'=>$getData->searchDate])->all();

		if($this->userCan('view_all_task')) {
			$users = User::find()->all();

			$tasks = Tasks::find()
				->where(['date(working_datetime_start)'=>$getData->searchDate])
				->all();
		} else {
			$projectLeader = ProjectMembership::find()
				->where(['user_id' => $this->userData['id']])
				->andWhere(['leader' => true])
				->indexBy('project_id')
				->all();

			$projectLeaderIds = array_keys($projectLeader);
			if($projectLeaderIds) {
				$members = ProjectMembership::find()
					->where(['in', 'project_id', $projectLeaderIds])
					->indexBy('user_id')
					->all();

				$users = User::find()->where(['in', 'id', array_keys($members)])->all();

				$tasks = Tasks::find()
					->where(['in', 'user_id', array_keys($members)])
					->andWhere(['and',
						['=', 'date(working_datetime_start)', $getData->searchDate],
						['in', 'project_id', $projectLeaderIds]
					])
					->orWhere(['and',
						['=', 'user_id', $this->userData['id']],
						['=', 'date(working_datetime_start)', $getData->searchDate]
					])
					->all();
			}
		}

		return $this->render('@app/modules/controlling/views/timeline/index', [
			'getData'=>$getData, 'users'=>$users, 'tasks'=>$tasks, "projects"=>$projects
		]);
	}

}