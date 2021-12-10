<?php
namespace app\modules\users\models\forms;

use app\components\Helpers;

use app\modules\users\models\User;
use Yii;

class SettingsForm extends \yii\base\Model {
    public $name;
    public $current_password;
    public $password;
    public $password_repeat;
    
	public function rules() {
        return [
            [['current_password'], 'safe'],
            [["name"], "required"],
            [["password", "password_repeat"], "string", "min" => 8],
            ['password','required', 'when' => function ($model) {
                return !!$model->current_password;
            }, 'enableClientValidation' => false ],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
			["current_password", function ($attribute, $params, $validator) {
                $user = User::current();
                if (!$user->validatePassword($this->current_password)) {
                    $this->addError("current_password", Yii::t('app', 'incorrect_password'));
                }
            }],
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'name'),
            'current_password' => Yii::t('app', 'current_password'),
            'password' => Yii::t('app', 'new_password'),
            'password_repeat' => Yii::t('app', 'new_password_again'),
        ];
    }
}