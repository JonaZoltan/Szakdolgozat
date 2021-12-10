<?php

namespace app\modules\partners\models;

use app\modules\apps\models\Apps;
use app\modules\tasks\models\Tasks;
use app\modules\users\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "contact_event".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $partner_id
 * @property integer $contact_id
 * @property integer $type
 * @property string $when
 * @property-read string $userName
 * @property-read string $typeName
 * @property-read string $contactName
 * @property-read string $partnerName
 * @property string $note
 * @property int $task_id [int(11)]
 */
class ContactEvent extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_event';
    }

	/**
	 * Felhasználó hozzárendelése automatikusan, az első alkalommal
	 * Automatikusan hozzárendeli a partner_id-t a contact_id alapján
	 * @param bool $insert
	 * @return bool
	 */
	public function beforeSave($insert): bool
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}
		if($this->isNewRecord){
			$this->user_id = User::current()->id;
		}
		$contact = Contact::findOne($this->contact_id);
		$this->partner_id = $contact->partner_id;

		return true;
	}

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		$partner = Partners::findOne($this->partner_id);
		if ($partner->getDaysSince($this->when) < $partner->alert_day) {
			$partner->email_sent = false;
			$partner->save();
		}
	}

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'partner_id', 'contact_id', 'type'], 'integer'],
            [['contact_id', 'type', 'when'], 'required'],
            [['when'], 'safe'],
            [['note'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'user_id' => Yii::t('app', 'user'),
            'partner_id' => Yii::t('app', 'partner'),
            'contact_id' => Yii::t('app', 'contact'),
            'type' => Yii::t('app', 'contact_event_type'),
            'when' => Yii::t('app', 'when'),
            'note' => Yii::t('app', 'comment'),
        ];
    }

	/**
	 * A lehetséges kapcsolattartási típúsokat adja vissza tömb formátumban
	 * @return string[]
	 */
    public static function allTypeNames(): array
    {
    	return [
    		1 => 'Személyes',
		    2 => 'Telefon',
		    3 => 'Email'
	    ];
    }

	/**
	 * A kapcsolattartó felhasználó nevét adja vissza
	 * @return string
	 */
    public function getUserName(): string
    {
    	if (!$this->user_id){
    		return Yii::t('app', 'unknown');
	    }
    	$user = User::findOne($this->user_id);
    	return Html::a($user->name, Url::toRoute(['/users/users/view', 'id' => $user->id]), ['target' => '_blank']);
    }
	/**
	 * A partner nevét adja vissza
	 * @return string
	 */
	public function getPartnerName(): string
	{
		if (!$this->partner_id){
			return Yii::t('app', 'unknown');
		}
		$partner = Partners::findOne($this->partner_id);
		return Html::a($partner->name, Url::toRoute(['/partners/partners/view', 'id' => $partner->id]), ['target' => '_blank']);

	}

	/**
	 * A partner kapcsolat nevét adja vissza
	 * @return string
	 */
	public function getContactName(): string
	{
		if (!$this->contact_id){
			return Yii::t('app', 'unknown');
		}
		$contact = Contact::findOne($this->contact_id);
		if ($contact)
			return Html::a($contact->name, Url::toRoute(['/partners/partners/view', 'id' => $contact->partner_id]), ['target' => '_blank']);
		else
			return Yii::t('app', 'unknown');
	}

	/**
	 * A kapcsolattartás típusát adja vissza
	 * @return string
	 */
	public function getTypeName(): string
	{
		$types = self::allTypeNames();

		return $types[$this->type];
	}

	/**
	 * Azokat a ContactEvent-eket kéri le melyeknél a task_id nem null
	 * és indexeli task_id alapján
	 * @return ContactEvent[]|array|ActiveRecord[]
	 */
	public static function getContactEventsIndexedByTaskId(): ?array
	{
		return ContactEvent::find()
			->indexBy('task_id')
			->where(['not', ['task_id' => null]])
			->all();
	}

	/**
	 * A Taskokhoz megfelelően indexeli $modelContactEventAll tömböt.
	 * Amelyik Taskhoz társítható contactEvent oda azt tölti be,
	 * ahol nem található oda new ContactEvent kerül
	 * @param $modelAll
	 * @return array
	 */
	public static function getContactEventsToTasks($modelAll): array
	{
		$contactEvents = ContactEvent::getContactEventsIndexedByTaskId();
		$modelContactEventAll = [];
		foreach ($modelAll as $index => $item){

			$contactEvent = $contactEvents[$item->id] ?? null;

			if ($contactEvent){
				$modelContactEventAll[$index] = $contactEvent;
			}
			else{
				$modelContactEventAll[$index] = new ContactEvent();
			}
		}
		return $modelContactEventAll;
	}

	/**
	 * Vissza adja a Task-hoz tartozó ContactEvent linkjét,
	 * (Kapcsolattartás <szem ikon>) formában
	 * @param $modelId
	 * @return string|null
	 */
	public static function getContactEventLinkByTaskId($modelId): ?string
	{
		$contactEvent = ContactEvent::findOne(['task_id' => $modelId]);
		$contactEventEye = null;
		if(isset($contactEvent)) {
			$contactEventEye = Html::a('<br><small>(Kapcsolattartás <i class="fas fa-comments"></i>)</small>',
				Url::toRoute(['/partners/contact-event/view', 'id' => $contactEvent->id]), ['target' => '_blank']);
		}
		return $contactEventEye;
	}

	/**
	 * Ha van akkor vissza adja a ContactEvent-hez tartozó Task linkjét,
	 * (Feladat <szem ikon>) formában.
	 * Ha nincs Task akkor csak a dátumot adja vissza
	 * @return string|null
	 */
	public function getTaskLinkByTaskId(): ?string
	{
		$task = Tasks::findOne(['id' => $this->task_id]);
		$contactEventEye = null;
		if($task) {
			$contactEventEye = Html::a('<br><small>(Feladat <i class="fas fa-exclamation-circle"></i>)</small>',
				Url::toRoute(['/tasks/tasks/view', 'id' => $task->id]), ['target' => '_blank']);
		}
		return $contactEventEye;
	}

	/**
	 * ContactEvent objektumokat készít az asszociációs tömbből
	 * a Task modellek számának megfelelően, indexelve
	 * @param $attr_modelsCount
	 * @param $postedContactEvents
	 * @return mixed
	 */
	public static function makeObjContactEvents($attr_modelsCount, $postedContactEvents) : array
	{
		for ($i = 0; $i < $attr_modelsCount; $i++){
			$newContactEvent = new ContactEvent();
			if (isset($postedContactEvents[$i])) {
				$newContactEvent->contact_id = $postedContactEvents[$i]['contact_id'];
				$newContactEvent->type = $postedContactEvents[$i]['type'];
				$postedContactEvents[$i] = $newContactEvent;
			}
			else
				$postedContactEvents[$i] = $newContactEvent;
		}
		return $postedContactEvents;
	}

	/**
	 * Elmenti a paraméterként jövő adatokból a ContactEvent-et
	 * a newContactEvent már tartalmazza a contact_id-t és a type-ot
	 * @param $newContactEvent
	 * @param $model
	 * @return boolean
	 */
	public static function saveModelContactEvent($newContactEvent, $model): bool
	{
		$newContactEvent->partner_id = Contact::findOne($newContactEvent->contact_id)->partner_id;
		$newContactEvent->user_id = $model->user_id;
		$newContactEvent->when = Apps::getDate($model->date)." ".Apps::getDate($model->start_time, 'H:i:s');
		$newContactEvent->note = $model->text;
		$newContactEvent->task_id = $model->id;

		return $newContactEvent->save();
	}
}
