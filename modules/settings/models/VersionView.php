<?php

namespace app\modules\settings\models;

use app\modules\models\User;

use Yii;

/**
 * This is the model class for table "version_view".
 *
 * @property integer $user_id
 * @property integer $version_id
 * @property string $when
 *
 * @property User $user
 * @property VersionHistory $version
 */
class VersionView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'version_id', 'when'], 'required'],
            [['user_id', 'version_id'], 'integer'],
            [['when'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['version_id'], 'exist', 'skipOnError' => true, 'targetClass' => VersionHistory::className(), 'targetAttribute' => ['version_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'version_id' => 'Version ID',
            'when' => 'When',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(VersionHistory::className(), ['id' => 'version_id']);
    }
}
