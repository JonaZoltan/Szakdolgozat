<?php

namespace app\modules\tasks\models;

use Yii;

/**
 * This is the model class for table "workplace".
 *
 * @property integer $id
 * @property string $title
 * @property bool $default
 */
class Workplace extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'workplace';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'string', 'max' => 50],
			[['default', 'archived'], 'integer'],
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
			'default' => Yii::t('app', 'default'),
			'archived' => Yii::t('app', 'archived'),
		];
	}

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($this->default) {
			Workplace::updateAll(['default' => false], ['default' => true]);
		}

		return true;
	}

	/**
	 * @param bool $assoc
	 * @return array
	 */
	public static function allWorkplaceNames($assoc = false) {
		$all = Workplace::find()->where(['archived' => 0])->all();
		$result = [];

		foreach ($all as $workplace) {
			if ($assoc) {
				$result[$workplace->id] = $workplace->title;
			} else {
				$result[$workplace->title] = $workplace->title;
			}
		}

		return $result;
	}
}
