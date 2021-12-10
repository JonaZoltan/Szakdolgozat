<?php
namespace app\modules\users\models\forms;

use app\components\Helpers;

use app\modules\users\models\User;
use Yii;

class QuickMenuForm extends \yii\base\Model {
	public $quickmenu;

	public function rules() {
		return [
			[['quickmenu'], 'safe'],
		];
	}

	public function attributeLabels() {
		return [
			'quickmenu' => Yii::t('app', 'quickmenu'),
		];
	}
}