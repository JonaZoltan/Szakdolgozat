<?php

namespace app\modules\tasks\models;

use app\modules\apps\models\Apps;
use app\modules\project\models\ProjectMembership;
use app\modules\users\models\User;
use DateTime;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $worktype_id
 * @property integer $workplace_id
 * @property integer $user_id
 * @property string $text
 * @property string $working_datetime_start
 * @property string $working_datetime_end
 * @property integer $recommended_hours
 * @property integer $recognized
 * @property-read mixed $workingHours
 * @property string $comment [varchar(255)]
 * @property bool $planned [tinyint(1)]
 * @property bool $verified [tinyint(1)]
 * @property string $verified_comment
 */
class Tasks extends ActiveRecord
{
	public $date;
	public $start_time;
	public $end_time;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worktype_id', 'workplace_id', 'user_id', 'date', 'start_time', 'end_time', 'text'], 'required'],
            [['project_id', 'worktype_id', 'workplace_id', 'user_id', 'recommended_hours', 'recognized', 'planned', 'verified'], 'integer'],
            [['text'], 'string', 'min' => 10],
            [['comment', 'verified_comment'], 'string', 'max' => 255],
            [['working_datetime_start', 'working_datetime_end', 'date', 'start_time', 'end_time'], 'safe'],

	        [['start_time', 'end_time'], function() {
        	    $startTime = new DateTime($this->start_time);
        	    $endTime = new DateTime($this->end_time);
        	    if($startTime > $endTime)
        	    	$this->addError('start_time', 'A munkavégzés kezdete nem lehet később mint a munkavégzés vége');
	        }],

	        [['user_id'], function() {
	            if(!User::current()->can('view_all_tasks')) {
			        $user = User::current();
			        $currentLeader = ProjectMembership::findOne(['user_id' => $user->id, 'project_id' => $this->project_id]);

			        // TODO: Usercan, vagy az admin is tegye magát project felelősnek
			        if ($user->id != $this->user_id && ($currentLeader && !$currentLeader->leader)) {
				        $this->addError('user_id', 'Nincs jogosultságod felvenni a tevékenységét');
			        }
		        }
	        }],

	        [['project_id'], function() {
				$membership = ProjectMembership::findOne(['project_id' => $this->project_id, 'user_id' => $this->user_id]);

				if(!$membership && $this->project_id != 0) {
					$this->addError('project_id', 'Nem tagja ennek a projektnek.');
				}
	        }],
        ];
    }

    public function afterFind()
    {
	    $this->date = Apps::getDate($this->working_datetime_start);
	    $this->start_time = Apps::getDate($this->working_datetime_start, 'H:i');
	    $this->end_time = Apps::getDate($this->working_datetime_end, 'H:i');
    }

    /*******Létre lettek hozva a vizsgálatok, illetve szét lettek szedve functionökbe*******/


//Ha az új feladat kezdete korábban van mint a meglévő kezdete, és az új vége korábban van mint a meglévő vége
    public function isNewDateEarlier($tasks){
    	if(!$tasks)
    		return false;
	    $currStart = $this->working_datetime_start = Apps::getDate($this->date)." ".Apps::getDate($this->start_time, 'H:i:s');
	    $currEnd = $this->working_datetime_end = Apps::getDate($this->date)." ".Apps::getDate($this->end_time, 'H:i:s');
		$return = [];
	    foreach($tasks as $taskId => $task)
	    {

		    if($taskId != $this->id) {

			    if ($currStart <= $task->working_datetime_start
				    && $currEnd < $task->working_datetime_end
				    && $currEnd > $task->working_datetime_start) {

			    	array_push($return, $task);


			    }


		    }
	    }
	    return $return;
    }

//Ha az új feladat kezdete később van mint a meglévő kezdete, viszont korábban mint a meglévő vége,
//illetve ha az új vége később van mint a meglévő vége
	public function isNewDateLater($tasks){

		if(!$tasks)
			return false;
		$currStart = $this->working_datetime_start = Apps::getDate($this->date)." ".Apps::getDate($this->start_time, 'H:i:s');
		$currEnd = $this->working_datetime_end = Apps::getDate($this->date)." ".Apps::getDate($this->end_time, 'H:i:s');

		$return = [];
		foreach($tasks as $taskId => $task) {

			if ($taskId != $this->id) {
				if ($currStart > $task->working_datetime_start
					&& $currStart < $task->working_datetime_end
					&& $currEnd >= $task->working_datetime_end)
				{

					array_push($return, $task);
				}
			}
		}

			return $return;
	}

//Ha pontosan ugyanaz a megadott dátum és időintervallum mint ami már létezik egyszer, akkor felülírja a meglévőt
	public function isNewDateSame($tasks){

		if(!$tasks)
			return false;
		$currStart = $this->working_datetime_start = Apps::getDate($this->date)." ".Apps::getDate($this->start_time, 'H:i:s');
		$currEnd = $this->working_datetime_end = Apps::getDate($this->date)." ".Apps::getDate($this->end_time, 'H:i:s');

		$return = [];
		foreach($tasks as $taskId => $task) {

			if ($taskId != $this->id) {

				if ($currStart <= $task->working_datetime_start
					&& $currEnd >= $task->working_datetime_end) {

					array_push($return, $task);
				}
			}
		}
			return $return;
	}

//Ha az új kezdete később van mint a meglévő kezdete, viszont korábban mint a meglévő vége,
//illetve ha az új vége korábban van mint a meglévő vége
	public function isNewDateInBetween($tasks){

		if(!$tasks)
			return false;
		$currStart = $this->working_datetime_start = Apps::getDate($this->date)." ".Apps::getDate($this->start_time, 'H:i:s');
		$currEnd = $this->working_datetime_end = Apps::getDate($this->date)." ".Apps::getDate($this->end_time, 'H:i:s');

		$return = [];
		foreach($tasks as $taskId => $task) {

			if ($taskId != $this->id) {

				if ($currStart > $task->working_datetime_start
					&& $currStart < $task->working_datetime_end
					&& $currEnd > $task->working_datetime_start
					&& $currEnd < $task->working_datetime_end) {

					array_push($return, $task);

				}
			}
		}
			return $return;

	}

/******************************************************************/


	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		$currUser = User::current();
		if($this->user_id == $currUser->id) {
			$allTasks = Tasks::find()
				->where(['user_id' => $currUser->id])
				->andWhere(['date(working_datetime_start)' => $this->date])
				->indexBy('id')
				->all();

			if ($isNewDateEarlier = $this->isNewDateEarlier($allTasks)) {
				foreach ($isNewDateEarlier as $task) {
					$task->start_time = Apps::getDate($this->working_datetime_end, 'H:i');
					$task->save(true);
				}
			}

			if ($isNewDateLater = $this->isNewDateLater($allTasks)) {
				foreach ($isNewDateLater as $task) {

					$task->end_time = Apps::getDate($this->working_datetime_start, 'H:i');
					$task->save(true);
				}
			}

			if ($isNewDateSame = $this->isNewDateSame($allTasks)) {
				foreach ($isNewDateSame as $task) {
					$task->delete();
				}
			}

			if ($isNewDateInBetween = $this->isNewDateInBetween($allTasks)) {
				$taskEnd = $isNewDateInBetween[0]->end_time;
				$isNewDateInBetween[0]->date = Apps::getDate($this->working_datetime_start);
				$isNewDateInBetween[0]->end_time = Apps::getDate($this->working_datetime_start, 'H:i');
				$isNewDateInBetween[0]->save(true);

				$newTask = new Tasks;
				$newTask->setAttributes($isNewDateInBetween[0]->attributes);
				$newTask->date = Apps::getDate($this->working_datetime_end);
				$newTask->end_time = Apps::getDate($taskEnd, 'H:i');
				$newTask->start_time = Apps::getDate($this->working_datetime_end, 'H:i');
				$newTask->save(true);
			}
		}

		$this->recommended_hours = $this->recommended_hours?:0;
		$this->working_datetime_start = Apps::getDate($this->date) . " " . Apps::getDate($this->start_time, 'H:i:s');
		$this->working_datetime_end = Apps::getDate($this->date) . " " . Apps::getDate($this->end_time, 'H:i:s');

		return true;
	}

	public function getWorkingHours() {
		$date1 = new DateTime($this->working_datetime_start);
		$date2 = new DateTime($this->working_datetime_end);
		$diff = $date2->diff($date1);

		return round(($diff -> i + $diff->h*60 + $diff->d*24) / 60, 2);
	}

	/**
	 * Vissza adja az adott felhasználóhoz és naphoz tartozó Task objektumokat
	 * (update-day)
	 * @param $model
	 * @return array
	 */
	public static function getTasksByDateAndUser($model): array
	{
		return Tasks::find()
			->where(['user_id' => $model->user_id, 'date(working_datetime_start)' => Apps::getDate($model->working_datetime_start)])
			->orderBy('working_datetime_start ASC')
			->all();
	}

	/**
	 * Unseteli az üres Tasks objektumot
	 * @param $tasks
	 * @return mixed
	 */
	public static function unsetEmptyTasks($tasks)
	{
		foreach($tasks as $index => $task){
			if(!$task->date && !$task->start_time && !$task->end_time)
				unset($tasks[$index]);
		}
		return $tasks;
	}

	/**
	 * Elmenti a paraméterként jövő adatokból a Task-et,
	 * a Task objektum már tartalmazza a user_id-t
	 * @param $newTask
	 * @param $task
	 * @return boolean
	 */
	public static function saveNewTasks($newTask, $task): bool
	{
		$newTask -> project_id = $task -> project_id;
		$newTask -> worktype_id = $task -> worktype_id;
		$newTask -> workplace_id = $task -> workplace_id;
		$newTask -> date = $task -> date;
		$newTask -> start_time = $task -> start_time;
		$newTask -> end_time = $task -> end_time;
		$newTask -> text = $task -> text;

		return $newTask->save();
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'project_id' => Yii::t('app', 'project_id'),
            'worktype_id' => Yii::t('app', 'worktype_id'),
            'workplace_id' => Yii::t('app', 'workplace_id'),
            'user_id' => Yii::t('app', 'user_id'),
            'text' => Yii::t('app', 'task_text'),
            'working_datetime_start' => Yii::t('app', 'working_datetime_start'),
            'working_datetime_end' => Yii::t('app', 'working_datetime_end'),
            'recommended_hours' => Yii::t('app', 'recommended_hours'),
            'recognized' => Yii::t('app', 'recognized'),
	        'comment' => Yii::t('app', 'comment'),
			'planned' => Yii::t('app', 'planned'),
	        'verified' => Yii::t('app', 'verified'),
			'verified_comment' => Yii::t('app', 'verified_comment'),
	        'date' => Yii::t('app', 'date'),
	        'start_time' => Yii::t('app', 'start_time'),
	        'end_time' => Yii::t('app', 'end_time'),
        ];
    }
}
