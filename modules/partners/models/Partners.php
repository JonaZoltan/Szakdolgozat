<?php

namespace app\modules\partners\models;

use app\modules\apps\models\Apps;
use app\modules\users\models\User;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "partners".
 *
 * @property integer $id
 * @property string $name
 * @property-read array|Contact[]|ActiveRecord[] $contact
 * @property string $note
 * @property int $alert_day [int(11)]
 * @property-read mixed $contactNum
 * @property-read string $responsiblesNames
 * @property-read string|mixed $lastContactEventDate
 * @property-read ActiveQuery $contactEvent
 * @property-read ActiveRecord|null|array|ContactEvent $lastContactEvent
 * @property string $user_ids [json]  Felelősök
 * @property bool $email_sent [tinyint(1)]
 */
class Partners extends ActiveRecord
{


	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

	/**
	 * Felelősöket meghatározó id-k Json formátumba alakítása
	 * @param bool $insert
	 * @return bool
	 */
    public function beforeSave($insert): bool
    {
	    if (!parent::beforeSave($insert))
		    return false;

	    if ($this->user_ids)
	    	$this->user_ids = Json::encode($this->user_ids);

	    if (!$this->alert_day)
	    	$this->alert_day = 30;

	    return true;
    }

	/**
	 * Felelősöket meghatározó id-k dekódolása Json formátumból
	 */
	public function afterFind() : void
    {
	    parent::afterFind();
	    $this->user_ids = Json::decode($this->user_ids);

    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 250],
	        [['alert_day'], 'integer'],
	        [['user_ids'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'),
            'note' => Yii::t('app', 'comment'),
            'alert_day' => Yii::t('app', 'alert_day'),
            'user_ids' => Yii::t('app', 'responsibles'),
            'contactEvent' => Yii::t('app', 'last_contact_event'),
        ];
    }


	/**
	 * A keresést lehetővé tévő join
	 * @return ActiveQuery
	 */
	public function getContactEvent() : ActiveQuery
	{
		return $this->hasOne(ContactEvent::className(), ['partner_id' => 'id']);
	}

	/**
	 * aktuális partner contaktok
	 * @return Contact[]|array|ActiveRecord[]
	 */
    public function getContact(): array
    {
    	return Contact::find()
		    ->where(['partner_id' => $this->id])
		    ->orderBy('name')
		    ->all();
    }

	/**
	 * Kapcsolatok száma
	 * @return int
	 */
	public function getContactNum() : int
	{
		return Contact::find()
			->where(['partner_id' => $this->id])
			->count();
	}

	/**
	 * Partnernevek meghatározása
	 * @param false $assoc
	 * @return array
	 */
	public static function allPartnerName($assoc = false) : array
	{
		$partners = Partners::find()
					->all();
		$result = [];
		foreach ($partners as $partner) {
			if ($assoc) {
				$result[$partner->id] = $partner->name;
			} else {
				$result[$partner->name] = $partner->name;
			}
		}
		return $result;
	}

	/**
	 * Felelősök neveit adja vissza
	 * @return string
	 */
	public function getResponsiblesNames() : string
	{
    	if(!$this->user_ids)
    		return '';
    	$users = User::find()
		    ->where(['in', 'id', $this->user_ids])
		    ->all();
    	$names = ArrayHelper::map($users, 'name', 'name');

    	return implode(', ',$names);
	}

	/**
	 * Legutolsó kapcsolattartás lekérdezése
	 * @return ContactEvent|array|ActiveRecord|null
	 */
	public function getLastContactEvent() : ?ContactEvent
	{
		return ContactEvent::find()
			->where(['partner_id' => $this->id])
			->orderBy(['when' => SORT_DESC])
			->one();
	}

	/**
	 * Legutolsó kapcsolattartás ideje
	 * @return mixed|string
	 * @throws Exception
	 */
	public function getLastContactEventDate() : string
	{
		/** @var ContactEvent $lastContactEvent */
		$lastContactEvent = $this->lastContactEvent;
		if (!$lastContactEvent)
			return '';
		else {
			$date = Apps::getDate($lastContactEvent->when);
			$diff = $this->getDaysSince($date);
			if ($diff != 0) {
				$color = $this->getColorForAlertDay($diff);
				$htmlDate = "$date <span class='badge badge-$color '>$diff napja</span>";
			}
			else
				$htmlDate = "$date <span class='badge badge-success '>Mai napon</span>";

			return Html::a($htmlDate, Url::toRoute(['/partners/contact-event/view', 'id' => $lastContactEvent->id]), ['target' => '_blank']);
		}
	}

	/**
	 * Kiszámolja és vissza adja a legutolsó kapcsolattartás óta eltelt időt (napokban)
	 * @throws Exception
	 * @param $date
	 * @return int
	 */
	public function getDaysSince($date) : int
	{
		$date = Apps::getDate($date);
		$firstDate = new \DateTime(date('Y-m-d'));
		$lastDate = new \DateTime($date);
		return $firstDate->diff($lastDate)->format('%a');
	}

	/**
	 * Az értesítés idejét figyelembe véve, a megfelelő színt adja vissza stringként
	 * @param $diff
	 * @return string
	 */
	public function getColorForAlertDay ($diff) : string
	{
		$dayPercent = $diff / $this->alert_day;
		$dayPercent = $dayPercent * 100;
		if ($dayPercent <= 74)
			return 'success';
		else if ($dayPercent >= 75 && $dayPercent <= 84)
			return 'warning';
		else
			return 'danger';
	}
}
