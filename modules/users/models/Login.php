<?php

namespace app\modules\users\models;

use Yii;

use app\modules\groups\models\Group;
use yii\db\ActiveRecord;

/**
 * A "login" táblához tartozó modell.
 */
class Login extends ActiveRecord
{
    /**
     Adatbázis tábla.
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
    Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['user_id', 'start_date', 'end_date', 'token'], 'required'],
            [['user_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['token'], 'string', 'max' => 40],
            [['ip_address'], 'string', 'max' => 50],
            [['user_agent'], 'string', 'max' => 1000],
        ];
    }

    /**
     Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('app', 'user'),
            'user_search' => Yii::t('app', 'user'),
            'start_date' => Yii::t('app', 'start_date'),
            'end_date' => Yii::t('app', 'end_date'),
            'token' => Yii::t('app', 'token'),
            'ip_address' => Yii::t('app', 'ip_address'),
            'user_agent' => Yii::t('app', 'user_agent'),
            'group_search' => Yii::t('app', 'group'),
        ];
    }
    
    /**
    Visszaadja a kapcsolódó felhasználót.
    */
    public function getUser() {
		$return = User::getDb()->cache(function () {
			 return User::findOne($this->user_id);
		    });
		return $return;
    }
    

    
    /**
    Az aktuális "login" (munkamenet) modellt adja vissza. Sütiből olvassa ki az értéket.
    */
    public static function currentLogin() {
        $cookies = Yii::$app->request->cookies;
        $login_cookie = $cookies->getValue('login', '');
        if (!$login_cookie) {
	        Yii::$app->response->cookies->remove("user_data");
            return null; // nem található a cookie
        }

	    // Ha nincs user_data, de login "valahogy meg maradt".
        $user_data_cookie = $cookies->getValue('user_data', '');
        if (!$user_data_cookie) {
	        Yii::$app->response->cookies->remove("login");
        	return null;
        }

        $parts = explode(":", $login_cookie);
        if (count($parts) !== 2) {
            return null; // nem `valami:valami` a cookie tartalma
        }
        $user_id = intval($parts[0]);
        $token = $parts[1];
        if (strlen($token) !== 40) {
            return null; // a token nem 40 karakteres, tehát módosított cookie-t
        }
       // $user = User::findOne($user_id);
  	    $user = User::getDb()->cache(function () use($user_id) {
		    return User::findOne($user_id);
	    });

        if (!$user) {
            return null; // nem található a felhasználó ID-ja
        }

	    $login_record = Login::getDb()->cache(function () use($user, $token) {
		    return Login::findOne(["user_id" => $user->id, "token" => $token]);
	    });

        if (!$login_record) {
            return null; // nem található a bejelentkezés az adatbázisban
        }
        $now = time();
        $login_expiration = strtotime($login_record->end_date);
        if ($login_expiration < $now) {
            return null; // lejárt a biztonsági időkorlát
        }
        return $login_record;
    }
}
