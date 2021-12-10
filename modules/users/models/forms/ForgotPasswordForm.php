<?php
namespace app\modules\users\models\forms;

use app\components\Helpers;

use app\components\ReCaptchaValidator;

use app\modules\users\models\User;

class ForgotPasswordForm extends \yii\base\Model {
    public $email;
    public $captcha;
    
	public function rules() {
        return [
            [["email", "captcha"], "required"],
            [["email"], "email"],
            [["email"], function ($attribute, $params, $validator) {
                if (!User::findOne(["email" => $this->email])) {
                    $this->addError("email", "A felhasználó nem található.");
                }
            }],
            [['captcha'], 'captcha','captchaAction'=>'users/users/captcha'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'email' => "E-mail",
            'captcha' => 'Ellenőrző kód',
        ];
    }
}