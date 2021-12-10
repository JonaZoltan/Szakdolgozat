<?php

namespace app\modules\errors\models;

use app\modules\users\models\User;

use Yii;
use yii\db\ActiveRecord;

/**
 * Az "error_reporting" tábla modell osztálya.
 * @property int $id [int(11)]
 * @property int $user_id [int(11)]
 * @property string $message
 * @property string $user_agent [varchar(2048)]
 * @property string $created_at [datetime]
 * @property-read mixed $user
 * @property-read mixed $subjectModel
 * @property-read mixed $messages
 * @property-read int $numberOfMessages
 * @property int $subject [int(11)]
 */
class ErrorReporting extends ActiveRecord
{
    /**
     * Táblanév.
     */
    public static function tableName()
    {
        return 'error_reporting';
    }

    /**
     * Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['user_id', 'subject'], 'integer'],
            [['message'], 'string'],
            [['subject', "message"], 'required'],
            [['created_at'], 'safe'],
            [['user_agent'], 'string', 'max' => 2048],
            [['subject'], 'exist', 'skipOnError' => true, 'targetClass' => ErrorReportingSubject::className(), 'targetAttribute' => ['subject' => 'id']],
        ];
    }

    /**
     * Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'user_id' => Yii::t('app', 'user'),
            'user_search' => Yii::t('app', 'user'),
            'message' => Yii::t('app', 'message'),
            'user_agent' => Yii::t('app', 'user_agent'),
            'created_at' => Yii::t('app', 'created_at'),
            'subject' => Yii::t('app', 'subject'),
        ];
    }
    
    /**
    A modell mentése előtt fut le.
    */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $user = User::current();
            $this->user_id = $user ? $user->id : null;
            $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $this->created_at = date("Y-m-d H:i:s");
        }
        return true;
    }
    
    /**
    Visszaadja a kapcsolódó felhasználót.
    */
    public function getUser() {
        return User::findOne($this->user_id);
    }

    /**
     * A hibabejelntés tárgyát adja vissza.
     */
    public function getSubjectModel()
    {
        return $this->hasOne(ErrorReportingSubject::className(), ['id' => 'subject']);
    }

	/**
	 * Adott hibajelentés üzeneteit adja vissza.
	 * @return array|ActiveRecord[]
	 */
    public function getMessages() {
    	return ErrorReportingMessage::find()->where(['error_reporting_id' => $this->id])->orderBy('timestamp DESC')->all();
    }

	/**
	 * Adott hibajelentés üzeneteinek számát adja vissza
	 * @return int
	 */
    public function getNumberOfMessages() {
    	return count($this->messages);
    }
}
