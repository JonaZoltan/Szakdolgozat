<?php

namespace app\modules\users\controllers;

use app\modules\apps\models\Apps;
use app\modules\formschemes\models\FormSchemes;
use app\modules\project\models\Project;
use app\modules\tasks\models\Holiday;
use app\modules\tasks\models\Tasks;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\forms\QuickMenuForm;
use DateTime;
use kartik\mpdf\Pdf;
use phpDocumentor\Reflection\Types\Context;
use Yii;
use app\modules\users\models\User;
use app\modules\groups\models\Membership;
use app\modules\users\models\UserSearch;
use app\modules\users\models\Login;
use app\controllers\BaseController;
use yii\caching\Cache;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\users\models\forms\LoginForm;
use app\modules\users\models\forms\ForgotPasswordForm;
use app\modules\users\models\forms\SettingsForm;
use app\modules\users\models\forms\ResetPasswordForm;

use app\modules\logs\models\Log;

use app\components\Helpers;

use app\modules\groups\models\Group;
use yii\web\UploadedFile;

/**
 * UsersController implements the CRUD actions for User model.
 */



class MonthlyStatsController extends BaseController
{
	public function actionIndex($baseData = null){

		if (!$baseData) {
			return $this->redirect('?baseData='.base64_encode('{"searchDate":"'.(date('Y-m')).'"}'));
		} else {
			$getData = json_decode(base64_decode($baseData));
		}


		if(User::current()->is_admin){
			$users = User::find()->all();
		} else {
			$users = User::find()->where(['id' => User::current()->id])->all();
		}

		$sumHours = [];

		foreach ($users as $user){
		$tasks = Tasks::find()
			->where(['tasks.user_id' => $user->id])
			->andWhere(['year(tasks.working_datetime_start)' => Apps::getDate($getData->searchDate, 'Y')])
			->andWhere(['month(tasks.working_datetime_start)' => Apps::getDate($getData->searchDate, 'm')])
			->all();

		$sum = 0;
		foreach ($tasks as $task){

			$startDate = new DateTime($task->working_datetime_start);
			$endDate = new DateTime($task->working_datetime_end);
			$diff = $endDate->diff($startDate);
			$diff = $diff->i+$diff->h*60;
			$sum += $diff;

		}

		$sumHours[$user->id] = $sum;

		}

/****************Egyelőre amíg nincs dropdown csinálva az elérhető sémákhoz, addig az egyes id az alapértelmezett******************/

		$model = FormSchemes::find()->where(['id' => 1])->one();

		return $this->render('stats', [
			'baseData' => $baseData,
			'users' => $users,
			'sumHours' => $sumHours,
			'model' => $model,

		]);
	}



	public function actionPdf($id, $baseData, $text){
		$user = User::findOne($id);
		$getData = json_decode(base64_decode($baseData));
		$projects = Project::find()->asArray()->indexBy('id')->all();
		$workTypes = Worktype::find()->asArray()->indexBy('id')->all();

		$data = [];

		/**@var User $user*/
		/**@var Tasks $tasks*/
		/**@var Project $project*/




			$tasks = Tasks::find()
				->where(['tasks.user_id' => $user->id])
				->andWhere(['year(tasks.working_datetime_start)' => Apps::getDate($getData->searchDate, 'Y')])
				->andWhere(['month(tasks.working_datetime_start)' => Apps::getDate($getData->searchDate, 'm')])
				->all();
			$taskData = [];
			/** @var Tasks $task */
			foreach ($tasks as $task){
				$startDate = new DateTime($task->working_datetime_start);
				$endDate = new DateTime($task->working_datetime_end);
				$diff = $endDate->diff($startDate);
				$diff = $diff->i+$diff->h*60;
				$isVerified = $task->verified == 2;

				if(isset($taskData[$task->project_id])){
					if(isset($taskData[$task->project_id][$task->worktype_id])){
						$taskData[$task->project_id][$task->worktype_id]['Workhour'] += $diff;
						$taskData[$task->project_id][$task->worktype_id]['Verified'] += $isVerified?$diff:0;

					}
					else{
						$taskData[$task->project_id][$task->worktype_id] = [
							"Workhour" =>$diff,
							"Verified" => $isVerified?$diff:0,
						];
					}
				}
				else{
					$taskData[$task->project_id] = [
						$task->worktype_id => [
							"Workhour" =>$diff,
							"Verified" => $isVerified?$diff:0,
							]

					];

				}

			}

		$model = FormSchemes::find()->where(['name' => 'Belső'])->one();

			$holidays = array_keys(Holiday::find()
				->where(['user_id' => $user->id])
				->andWhere(['accepted' => true])
				->andWhere(['year(date)' => Apps::getDate($getData->searchDate, 'Y')])
				->andWhere(['month(date)' => Apps::getDate($getData->searchDate, 'm')])
				->indexBy('date')
				->orderBy('date ASC')
				->all());
				$data = [
				'username' => $user->name,

				'tasks' => $taskData,

				'holidays' => $holidays,

				'model' => $model,

				'text' => $text,
			];

//		var_dump($data); die();

		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		$pdf = new Pdf([
			'mode' => Pdf::MODE_UTF8,
			'destination' => Pdf::DEST_BROWSER,
			'content' => $this->renderPartial('@app/modules/users/views/monthly-stats/month_sum_pdf.php', [
				'getData' => $getData,
				'data' => $data,
				'projects' => $projects,
				'workTypes' => $workTypes,
			]),
			'cssInline' => '.fonts-size{font-size: 12px} 
				table{table-layout: fixed, width: 100%} 
				th,td{width: 33%, overflow: hidden} ',
			'options' => [

			],
		]);
		return $pdf->render();


	}

}
