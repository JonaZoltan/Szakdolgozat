<?php

namespace app\modules\tasks\controllers;

use app\base\Model;
use app\components\Helpers;
use app\modules\apps\models\Apps;
use app\modules\logs\models\Log;
use app\modules\partners\models\Contact;
use app\modules\partners\models\ContactEvent;
use app\modules\partners\models\Partners;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectWorktypes;
use app\modules\tasks\models\Holiday;
use app\modules\tasks\models\HolidayExtra;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\User;
use DateTime;
use Yii;
use app\modules\tasks\models\Tasks;
use app\modules\tasks\models\SearchTasks;
use app\controllers\BaseController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


/**
 * TasksController implements the CRUD actions for Tasks model.
 */
class TasksController extends BaseController
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

    public function actionRecognized($taskId) {
    	$task = Tasks::findOne($taskId);

    	if($task) {
    		$task -> recognized = !$task->recognized;
    		if($task->save(false)) {
    			return $task->recognized;
		    }
	    }

    	return -1;
    }

	public function actionPlanned($taskId) {
		$task = Tasks::findOne($taskId);

		if($task) {
			$task -> planned = !$task->planned;
			if($task->save(false)) {
				return $task->planned;
			}
		}

		return -1;
	}

    public function actionChangeHours() {
	    if(isset($_POST['hasEditable'])) {
		    Yii::$app->response->format = Response::FORMAT_JSON;

		    $model = $this->findModel(Yii::$app->request->post('editableKey'));

		    $posted = current($_POST[$model->formName()]);
		    $post[$model->formName()] = $posted;

		    $model->recommended_hours = $posted['recommended_hours'];
		    if($model->save(false))
		    	return true;
	    }

	    return false;
    }

    public function actionChangeVerified() {

    	if(isset($_POST['hasEditable'])) {
		    //echo 'console.log("asdfasd")';
    		Yii::$app->response->format = Response::FORMAT_JSON;

    		$model = $this->findModel(Yii::$app->request->post('editableKey'));

    		$posted = current($_POST[$model->formName()]);
    		$post[$model->formName()] = $posted;

    		$model->verified = $posted['verified'];

    		if($model->save(false)){

    			return true;
		    }

	    }
    	return false;
    }

    public function actionChangeVerifiedComment(){
	    if(isset($_POST['hasEditable'])) {
		    Yii::$app->response->format = Response::FORMAT_JSON;

		    $model = $this->findModel(Yii::$app->request->post('editableKey'));

		    $posted = current($_POST[$model->formName()]);
		    $post[$model->formName()] = $posted;

		    $model->verified_comment = $posted['verified_comment'];
		    if($model->save(false))
			    return ['output'=>substr($model->verified_comment, 0, 10).((strlen($model->verified_comment)>10)?'...':''), 'message'=>''];
	    }

	    return false;
    }


	public function actionChangeComment() {
		if(isset($_POST['hasEditable'])) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$model = $this->findModel(Yii::$app->request->post('editableKey'));

			$posted = current($_POST[$model->formName()]);
			$post[$model->formName()] = $posted;

			$model->comment = $posted['comment'];
			if($model->save(false))
				return ['output'=>substr($model->comment, 0, 10).((strlen($model->comment)>10)?'...':''), 'message'=>''];
		}

		return false;
	}



    /**
     * Lists all Tasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!$this->userCan('view_'.Tasks::tableName().''))
            return $this->redirect("/");

	    Yii::$app->session->setFlash('xy', null);

        $searchModel = new SearchTasks();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/modules/tasks/views/tasks/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionCalendar() {
		return $this->render('@app/modules/tasks/views/tasks/calendar');
	}

    /**
     * Displays a single Tasks model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->userCan('view_'.Tasks::tableName().''))
            return $this->redirect("/");

        return $this->render('@app/modules/tasks/views/tasks/view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Tasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!$this->userCan('create_'.Tasks::tableName().''))
            return $this->redirect("/");
        $model = new Tasks();
        $modelContactEvent = new ContactEvent();

        if ($model->load(Yii::$app->request->post())) {
	        $modelContactEvent->load(Yii::$app->request->post());

	        if(Yii::$app->session->getFlash('xy') === null) {
		        $currUser = User::current();
		        if($model->user_id == $currUser->id) {
			        $allTasks = Tasks::find()
				        ->where(['user_id' => $currUser->id])
				        ->andWhere(['date(working_datetime_start)' => $model->date])
				        ->indexBy('id')
				        ->all();
			        if($render = $this->checkNewDate($model, $allTasks, $modelContactEvent))
			        	return $render;
		        }
	        }
			if ($model->validate() && $model->save()) {
                Log::add("".Tasks::tableName().".create", Log::getAddParameters($model, Tasks::tableName()));

				if (Yii::$app->request->getBodyParam('isContactEvent')){
					if (isset($modelContactEvent->contact_id))
						ContactEvent::saveModelContactEvent($modelContactEvent, $model);
				}
				Yii::$app->session->setFlash('xy', null);
                return $this->redirect(['create']);
            }
			else
				return $this->renderCreateView($model, $modelContactEvent);

        }
	    return $this->renderCreateView($model, $modelContactEvent);
    }

	/**
	 * Create day
	 * @return mixed
	 */
	public function actionCreateDay()
	{
		if(!$this->userCan('create_'.Tasks::tableName().''))
			return $this->redirect("/");

		$model = new Tasks();
		$modelAll = [new Tasks()];
		$modelContactEventAll = [new ContactEvent()];
		$attr_models = Model::createMultiple(Tasks::class);
		$request = Yii::$app->request;

		if ($model->load($request->post()) && Model::loadMultiple($attr_models, $request->post())) {
			$postedContactEvents = $request->getBodyParam('ContactEvent');
			$attr_models = Tasks::unsetEmptyTasks($attr_models);
			$postedContactEvents = ContactEvent::makeObjContactEvents(count($attr_models), $postedContactEvents);


			foreach ($attr_models as $m) {
				$m->user_id = $model->user_id;
				if(!$m->validate())
					return $this->renderCreateView($model, $postedContactEvents, $attr_models);
			}

			foreach ($attr_models as $taskId => $task) {
				$newTask = new Tasks();
				$newTask->user_id = $task->user_id;

				if(Tasks::saveNewTasks($newTask, $task)) {
					Log::add("".Tasks::tableName().".create", Log::getAddParameters($newTask, Tasks::tableName()));
					if (isset($postedContactEvents[$taskId]->contact_id))
						ContactEvent::saveModelContactEvent($postedContactEvents[$taskId], $newTask);
				} else
					return $this->renderCreateView($model, $postedContactEvents, $attr_models);
			}
			return $this->redirect(['create-day']);
		} else
			return $this->renderCreateView($model, $modelContactEventAll, $modelAll);
	}

    public function actionUpdateDay($id) {
    	if(isset($id) && $id)
		    $model = $this->findModel($id);
    	else
    		$model = Tasks::find()->where(['user_id' => $this->userData['id'], 'date(working_datetime_start)' => date('Y-m-d')])->one();
    	if(!$model)
    		return $this->redirect(['/tasks/tasks/create-day']);

	    $modelAll = Tasks::getTasksByDateAndUser($model);
	    $modelContactEventAll = ContactEvent::getContactEventsToTasks($modelAll);
	    $deleteIds = self::getIds($modelAll);
	    $deleteContactEventIds = self::getIds($modelContactEventAll);

	    $attr_models = Model::createMultiple(Tasks::class);
	    if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($attr_models, Yii::$app->request->post())) {
		    $postedContactEvents = Yii::$app->request->getBodyParam('ContactEvent');
		    $attr_models = Tasks::unsetEmptyTasks($attr_models);
	    	$postedContactEvents = ContactEvent::makeObjContactEvents(count($attr_models), $postedContactEvents);

		    foreach ($attr_models as $m) {
		    	$m->user_id = $model->user_id;
			    if(!$m->validate())
				    return $this->renderCreateView($model, $postedContactEvents, $attr_models);
			}

		    foreach ($attr_models as $taskId => $task) {
			    $newTask = new Tasks();
			    $newTask -> user_id = $model -> user_id;
			    if(Tasks::saveNewTasks($newTask, $task)) {
				    Tasks::deleteAll(['in', 'id', array_values($deleteIds)]);
				    ContactEvent::deleteAll(['in', 'id', array_values($deleteContactEventIds)]);
				    Log::add("".Tasks::tableName().".create", Log::getAddParameters($newTask, Tasks::tableName()));
				    if (isset($postedContactEvents[$taskId]->contact_id))
					    ContactEvent::saveModelContactEvent($postedContactEvents[$taskId], $newTask);
			    }
			    else
				    return $this->renderCreateView($model, $postedContactEvents, $attr_models);
		    }
		    return $this->redirect(['index']);
	    }
	    else
		    return $this->renderCreateView($model, $modelContactEventAll, $modelAll);
    }

	/**
	 * @param $projectIds
	 * @param $worktypeIds
	 * @param $workplaceIds
	 * @param $dateStart
	 * @param $dateEnd
	 * @param $text
	 */
    public function actionLoadView($projectIds, $worktypeIds, $workplaceIds, $date, $dateStart, $dateEnd, $text) {
	    return Yii::$app->controller->renderAjax('@app/modules/tasks/views/tasks/_load_view', [
		    'projectIds' => $projectIds,
		    'worktypeIds' => $worktypeIds,
		    'workplaceIds' => $workplaceIds,
		    'date' => $date,
		    'dateStart' => $dateStart,
		    'dateEnd' => $dateEnd,
		    'text' => $text,
	    ]);

    }



    /**
     * Updates an existing Tasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!$this->userCan('create_'.Tasks::tableName().''))
            return $this->redirect("/");

        $model = $this->findModel($id);
	    $modelContactEvent = ContactEvent::findOne(['task_id' => $id]);
	    if(!$modelContactEvent)
	    	$modelContactEvent = new ContactEvent();

        if ($model->load(Yii::$app->request->post())) {
            Log::add("".Tasks::tableName().".update", Log::getParameters($model, Tasks::tableName(), $model->user_id)); // TODO: Log update modositas neve(line name, labors name etc...)
	        $postedContactEvent = new ContactEvent();
	        $postedContactEvent->load(Yii::$app->request->post());

            if($model->validate() && $model->save()) {
	            if (Yii::$app->request->getBodyParam('isContactEvent')) {
		            ContactEvent::saveModelContactEvent($postedContactEvent, $model);
		            if (!$modelContactEvent->isNewRecord)
			            $modelContactEvent->delete();
	            }
	            else if (!$modelContactEvent->isNewRecord)
		            $modelContactEvent->delete();

                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
	            return $this->render('@app/modules/tasks/views/tasks/update', [
		            'model' => $model,
		            'modelContactEvent' => $postedContactEvent,
	            ]);
            }
        } else {
            return $this->render('@app/modules/tasks/views/tasks/update', [
                'model' => $model,
                'modelContactEvent' => $modelContactEvent,
            ]);
        }
    }

    /**
     * Deletes an existing Tasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	/**
	 * Calendar
	 * @param $userId
	 * @param $date
	 */
	public function actionCreateHoliday($userId, $date) {
		$date = str_replace('. ', '-', $date);
		$date = str_replace('.', '', $date);
		$now = date('Y-m-d');

		$user = User::findOne($userId);
		$holidayExtra = HolidayExtra::findOne(['user_id' => $userId, 'year' => Apps::getDate($date, 'Y')]);

		$hasConflict = false;
		if($holidayExtra && Json::decode($holidayExtra->disabled_user)) {
			$hasConflict = Holiday::find()
				->where(['date' => $date])
				->andWhere(['in', 'user_id', array_values(Json::decode($holidayExtra->disabled_user))])
				->all()?true:false;
		}

		if(!($now > $date)) {
			$hasHoliday = Holiday::findOne(['user_id' => $userId, 'date' => $date]);

			if (!$hasHoliday) {
				$freeDays = HolidayExtra::findOne(['user_id' => $userId, 'year' => Apps::getDate($date, 'Y')]);
				if(!$freeDays) {
					return Json::encode([
						'success' => false,
						'id' => null,
						'error' => 'Erre az évre nincs megadva hány nap szabadságod van.',
						'conflict' => $hasConflict,
					]);
				}

				$freeDays = $freeDays->freeDay;

				if($freeDays) {
					$holiday = new Holiday();
					$holiday->user_id = $userId;
					$holiday->date = $date;
					if ($holiday->save()) {
						// TODO: e-mail küldés
						/*$message = "<b>{$user->name}</b> szabadság kérelmet nyújtott be az adott napra: <i>{$date}</i>";
						Helpers::email('mgabor411@gmail.com', "Szabadság kérelem", $message, null);*/

						return Json::encode([
							'success' => true,
							'id' => $holiday->getPrimaryKey(),
							'error' => null,
							'conflict' => $hasConflict,
						]);
					}
				} else {
					return Json::encode([
						'success' => false,
						'id' => null,
						'error' => 'Nincs több szabadságod.',
						'conflict' => $hasConflict,
					]);
				}
			} else {
				return Json::encode([
					'success' => false,
					'id' => null,
					'error' => 'Erre a napra már kértél szabadságot.',
					'conflict' => $hasConflict,
				]);
			}
		}

		return Json::encode([
			'success' => false,
			'id' => null,
			'error' => 'A múltban nem lehetséges a kérelem.',
			'conflict' => $hasConflict,
		]);
	}

	public function actionModifyCalendar($eventId) {
		$split = explode('_', $eventId);
		if($split[0] == 'mn') {
			return $this->redirect(['calendar']);
		}

		$event = Holiday::findOne($eventId);
		$user = User::findOne($event->user_id);

		return $this->render('_calendar_holiday_modify', [
			'user' => $user,
			'event' => $event,
		]);
	}

	public function actionDeleteHoliday($eventId) {
		$model = Holiday::findOne($eventId);

		if($model->delete()) {
			return true;
		} else {
			return false;
		}
	}

	public function actionDeclineHoliday($eventId) {
		$model = Holiday::findOne($eventId);
		$model->accepted = 2;
		$model->save();

		return Json::encode([
			'id' => $model->id,
			'name' => User::findOne($model->user_id)->name,
			'date' => Apps::getDate($model->date, 'm/d/y'),
			'type' => "holiday_".$model->accepted,
			'color' => !$model->accepted ? "gray" : ( $model->accepted == 2 ? "orange" : "green" ),
		]);
	}

	public function actionAcceptHoliday($eventId) {
		$model = Holiday::findOne($eventId);
		$freeDays = HolidayExtra::findOne(['user_id' => $model->user_id, 'year' => Apps::getDate($model->date, 'Y')]);
		$freeDays = $freeDays->freeDay;

		$save = false;
		if($freeDays) {
			$model->accepted = 1;
			$model->save();
			$save = true;
		}

		return Json::encode([
			'id' => $model->id,
			'name' => User::findOne($model->user_id)->name,
			'date' => Apps::getDate($model->date, 'm/d/y'),
			'type' => "holiday_".$model->accepted,
			'color' => !$model->accepted ? "gray" : ( $model->accepted == 2 ? "orange" : "green" ),
			'success' => $save,
		]);
	}

	/**
	 * A meghatározott projekthez tartozó Munkatípusokat adja vissza
	 * @param $project
	 * @return string
	 */
	public function actionWorktypesToProject($project): string
	{
		$worktypes = [];
		if ($project)
			$list = Project::findOne($project)->projectHasWorktypes;
		if(isset($list) && $list) {

			$all = Worktype::find()->where(['in', 'id', $list])->orderBy('title')->all();

			if($project != null && count($all) > 0){
				foreach ($all as $item){
					$worktypes[] = [$item->id => $item->title];
				}
			}
		}
		else {
			$all = Worktype::find()->all();
			foreach ($all as $item){
				$worktypes[] = [$item->id => $item->title];
			}
		}
		return Json::encode($worktypes);
	}

	/**
	 * Vissza adja a projekthez tartozó partnerek és az azokhoz tartozó contaktok neveit
	 * @param $project
	 * @return string
	 */
	public function actionContactsToProject($project): string
	{
		$partners = Partners::find()->indexBy('id')->all();
		$contactsWithPartners = [];

		$contacts = Contact::getContactsByProjectId($project);
		/** @var Contact $contact */
		foreach ($contacts as $contact){
			$partner = $partners[$contact->partner_id];
			$contactsWithPartners[] = [strval($contact->id), $contact->name, $partner->name];
		}
		return Json::encode($contactsWithPartners);
	}

	/**
	 * Ellenőrzi, hogy az új dátum nem ütközik-e korábbi modellek dátumaival,
	 * ha ütközik akkor visszairányít a creat oldalra
	 * @param $model
	 * @param $allTasks
	 * @param $modelContactEvent
	 * @return string
	 */
	private function checkNewDate($model, $allTasks, $modelContactEvent): string
	{
		$task = '';
		if ($isNewDateEarlier = $model->isNewDateEarlier($allTasks)) {
			$task = $isNewDateEarlier;
		}
		else if ($isNewDateLater = $model->isNewDateLater($allTasks)) {
			$task = $isNewDateLater;
		}
		else if ($isNewDateSame = $model->isNewDateSame($allTasks)) {
			$task = $isNewDateSame;
		}
		else if ($isNewDateInBetween = $model->isNewDateInBetween($allTasks)) {
			$task = $isNewDateInBetween;
		}

		if($task){
			return $this->render('@app/modules/tasks/views/tasks/create', [
				'model' => $model,
				'modelContactEvent' => $modelContactEvent,
				'task' => $task,
			]);
		}
		return $task;
	}

	/**
	 * A create view generálását végzi el, a bejövő adatoknak megfelelően
	 * @param $model
	 * @param $modelAll
	 * @param $modelContactEventAll
	 * @return string
	 */
	private function renderCreateView($model, $modelContactEventAll, $modelAll = null): string
	{
		if ($modelAll) {
			return $this->render('@app/modules/tasks/views/tasks/create', [
				'model' => $model,
				'modelAll' => $modelAll,
				'modelContactEventAll' => $modelContactEventAll,
				'day' => true,
			]);
		}
		else{
			return $this->render('@app/modules/tasks/views/tasks/create', [
				'model' => $model,
				'modelContactEvent' => $modelContactEventAll,
			]);
		}
	}

	/**
	 * Kigyűjti a tömbben lévő összes objektumnak az id-ját
	 * @param $modelAll
	 * @return array
	 */
	private static function getIds($modelAll): array
	{
		$ids = [];
		foreach ($modelAll as $model) {
			$ids[] = $model->id;
		}
		return $ids;
	}

}
