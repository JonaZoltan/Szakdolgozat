<?php

namespace app\modules\tasks\models;

use Yii;

/**
 * This is the model class for table "holiday".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property integer $accepted
 * @property string $description
 * @property integer $email_sent
 * @property string $timestamp
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date'], 'required'],
            [['user_id', 'accepted', 'email_sent'], 'integer'],
            [['date', 'timestamp'], 'safe'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'user_id' => Yii::t('app', 'user_id'),
            'date' => Yii::t('app', 'date'),
            'accepted' => Yii::t('app', 'accepted'),
            'description' => Yii::t('app', 'description'),
	        'email_sent' => Yii::t('app', 'email_sent'),
            'timestamp' => Yii::t('app', 'timestamp'),
        ];
    }
}
