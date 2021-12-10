<?php
namespace app\modules\users\models\forms;

use app\components\Helpers;

use app\modules\users\models\User;

class ResetPasswordForm extends \yii\base\Model {
    public $password;
    public $password_repeat;
    public $token;
    
	public function rules() {
        return [
            [["password", "password_repeat", "token"], "required"],
            [["password", "password_repeat"], "string", "min" => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['token', 'string'],
            ['token', function ($attribute, $params, $validator) {
                $json = Helpers::decrypt($this->token);

                if (!is_array($json) || !isset($json["type"]) || $json["type"] !== "reset_password") {
                    $this->addError("token", "Hibás token.");
                } else if (time() > $json["expiration"]) {
                    $this->addError("token", "Lejárt a link érvényességi ideje. A jelszó nem változtható meg.");
                } else if (!User::findOne($json["user_id"])) {
                    $this->addError("token", "A felhasználót törölték.");
                }
            }],
        ];
    }
    
    public function attributeLabels() {
        return [
            'password' => "Jelszó",
            'password_repeat' => "Jelszó ismét",
        ];
    }
}