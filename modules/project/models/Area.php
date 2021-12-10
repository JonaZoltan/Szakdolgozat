<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property integer $id
 * @property string $title
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'area';
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
	 * A area adatait adja vissza. ( tömb )
	 * @param bool $assoc
	 * @return array
	 */
	public static function allAreaNames($assoc = false) {
		$all = Area::find()->all();
		$result = [];

		foreach ($all as $area) {
			if ($assoc) {
				$result[$area->id] = $area->title;
			} else {
				$result[$area->title] = $area->title;
			}
		}

		return $result;
	}

}
