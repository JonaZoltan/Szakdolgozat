<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "project_worktypes".
 *
 * @property int|null $project_id
 * @property int|null $worktype_id
 * @property int $id [int(11)]
 */
class ProjectWorktypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_worktypes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'worktype_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('app', 'project_id'),
            'worktype_id' => Yii::t('app', 'worktype_id'),
        ];
    }
}
