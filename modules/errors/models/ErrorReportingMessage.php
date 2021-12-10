<?php

namespace app\modules\errors\models;

use app\modules\users\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "error_reporting_message".
 *
 * @property integer $id
 * @property integer $error_reporting_id
 * @property integer $user_id
 * @property string $text
 * @property-read User|null $user
 * @property string $timestamp
 */
class ErrorReportingMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'error_reporting_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['error_reporting_id', 'user_id'], 'required'],
            [['error_reporting_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'error_reporting_id' => Yii::t('app', 'error_reporting_id'),
            'user_id' => Yii::t('app', 'user_id'),
            'text' => Yii::t('app', 'text'),
            'timestamp' => Yii::t('app', 'timestamp'),
        ];
    }

	/**
	 * Üzenet küldőjét adja vissza.
	 * @return User|null
	 */
    public function getUser() {
    	return User::findOne($this->user_id);
    }
}
