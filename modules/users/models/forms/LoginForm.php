<?php
namespace app\modules\users\models\forms;

use app\modules\users\models\User;

class LoginForm extends \yii\base\Model {
    public $email;
    public $password;
    public $remember;
    
    private $user; // A felhasználó, hogy csak egyszer kelljen lekérdezni

	public function rules() {
        return [
            [["remember"], "integer", "min" => 0, "max" => 1],
            [["email", "password"], "string"],
            [["email"], "email"],
            [["email", "password"], "required", "message" => "Nem lehet üres."],
			["email", function ($attribute, $params, $validator) {
                $user = User::findOne([ "email" => $this->email ]);
                if ($user === NULL) {
                    $this->addError("email", "Az e-mail cím nem található.");
                } else if ($user->suspended) {
                    $this->addError("email", "Ön ki lett tiltva a rendszerből.");
                } else if (!$user->hasPassword()) {
                    $this->addError("email", "Előbb aktiválnia kell a fiókját jelszavának beállításával.");
                } else {
                    $this->user = $user;
                }
            }],
			["password", function ($attribute, $params, $validator) {
                if ($this->user) {
                    if (!$this->user->validatePassword($this->password)) {
                        $this->addError("password", "Hibás jelszó.");
                    }
                }
            }]
        ];
    }
    
    public function login($remember = false) {
        if ($this->user) {
            $this->user->login($remember);
            return $this->user;
        }
    }
    
    public function attributeLabels() {
        return [
            'email' => "E-mail",
            'password' => "Jelszó",
        ];
    }
}

?>