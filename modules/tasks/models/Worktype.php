<?php

namespace app\modules\tasks\models;

use app\modules\project\models\ProjectWorktypes;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "worktype".
 *
 * @property integer $id
 * @property string $title
 */
class Worktype extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'worktype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'title' => Yii::t('app', 'title'),
        ];
    }

	/**
	 * @param bool $assoc
	 * @param null $projectId
	 * @return array
	 */
	public static function allWorkTypeNames(bool $assoc = false, $projectId = null): array
	{
		$result = [];
		if ($projectId !== null) {
			$workTypeIds = array_keys(ProjectWorktypes::find()->where(['project_id' => $projectId])->asArray()->indexBy('worktype_id')->all());

			if ($workTypeIds)
				$all = Worktype::find()->where(['id' => $workTypeIds])->orderBy('title')->all();
			else
				$all = Worktype::find()->orderBy('title')->all();
		}
		else
			$all = Worktype::find()->orderBy('title')->all();


		foreach ($all as $workType) {
			if ($assoc) {
				$result[$workType->id] = $workType->title;
			} else {
				$result[$workType->title] = $workType->title;
			}
		}

		return $result;
	}
}
