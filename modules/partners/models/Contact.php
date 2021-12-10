<?php

namespace app\modules\partners\models;

use app\modules\project\models\Project;
use app\modules\tasks\controllers\TasksController;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $name
 * @property string $email
 * @property string $tel
 * @property string $position
 * @property-read Partners $partner
 * @property string $note
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['partner_id'], 'required'],
            [['partner_id'], 'integer'],
            [['note'], 'string'],
            [['name', 'email', 'tel', 'position'], 'string', 'max' => 250],
	        [['email'], 'email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'partner_id' => Yii::t('app', 'partner_id'),
            'name' => Yii::t('app', 'name'),
            'email' => Yii::t('app', 'email'),
            'tel' => Yii::t('app', 'tel'),
            'position' => Yii::t('app', 'position'),
            'note' => Yii::t('app', 'comment'),
        ];
    }

	/**
	 * A Partner kapcsolatok nevét adja vissza
	 * @param false $assoc
	 * @return array
	 */
	public static function allContactName($assoc = false): array
	{
		$contacts = Contact::find()
			->all();

		$result = [];
		foreach ($contacts as $contact) {
			if ($assoc) {
				$result[$contact->id] = $contact->name;
			} else {
				$result[$contact->name] = $contact->name;
			}
		}
		return $result;
	}

	/**
	 * @param bool $assoc
	 * @param null $projectId
	 * @return array
	 */
	public static function allContactNameWithPartnerName($assoc = false, $projectId = null): array
	{
		$contacts = self::getContactsByProjectId($projectId);
		$partners = Partners::find()->indexBy('id')->all();

		$result = [];
		/** @var Contact $contact */
		foreach ($contacts as $contact) {
			$partner = $partners[$contact->partner_id];

			if ($assoc) {
				$result[$contact->id] = "<b>$contact->name</b> <br><small>$partner->name</small>";
			} else {
				$result[$contact->name] = "<b>$contact->name</b> <br><small>$partner->name</small>";
			}
		}
		return $result;
	}

	/**
	 * @param null $projectId
	 * @return array
	 */
	public static function getContactsByProjectId($projectId = null): array
	{
		if ($projectId)
			$partnerIds = Project::findOne($projectId)->partner_ids;
		if (isset($partnerIds) && $partnerIds) {
			$contacts = Contact::find()
				->select(['id', 'name','partner_id'])
				->where(['partner_id' => $partnerIds])
				->all();
			if (!$contacts){
				$contacts = Contact::find()
					->select(['id', 'name', 'partner_id'])
					->all();
			}
		}
		else{
			$contacts = Contact::find()
				->select(['id', 'name', 'partner_id'])
				->all();
		}
		return $contacts;
	}

	/**
	 * @return Partners
	 */
	public function getPartner(): Partners
	{
		return Partners::findOne($this->partner_id);
	}
}
