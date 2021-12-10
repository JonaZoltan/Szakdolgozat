<?php

namespace app\modules\worktimes\models;

use Yii;

/**
 * This is the model class for table "worktimes".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $stepin
 * @property string $stepout
 * @property string $comment
 *
 * @property User $user
 */
class Worktimes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'worktimes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'stepin'], 'required'],
            [['user_id'], 'integer'],
            [['stepin', 'stepout'], 'safe'],
            [['comment'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'stepin' => 'stepin',
            'stepout' => 'stepout',
            'comment' => 'comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
