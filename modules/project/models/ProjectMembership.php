<?php

namespace app\modules\project\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project_membership".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property integer $financing
 * @property string $member_since
 * @property bool $leader [tinyint(1)]
 */
class ProjectMembership extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['user_id', 'project_id', 'financing', 'leader'], 'integer'],
            [['member_since'], 'safe'],

	        [['user_id', 'project_id'], 'unique',
		        'targetAttribute' => ['user_id', 'project_id']],
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
            'project_id' => Yii::t('app', 'project_id'),
            'financing' => Yii::t('app', 'financing'),
            'member_since' => Yii::t('app', 'member_since'),
	        'leader' => Yii::t('app', 'project_leader'),
        ];
    }

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}
		if ($this->isNewRecord) {
			$this->financing = $this->financing?:0;
			$this->member_since = date('Y-m-d H:i:s');
		}
		return true;
	}
}
