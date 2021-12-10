<?php

namespace app\modules\users\models;


use app\modules\apps\models\Apps;
use app\modules\project\models\ProjectMembership;
use Yii;

use app\modules\groups\models\Membership;
use app\modules\groups\models\Group;

use app\modules\productions\models\WorkOperation;
use app\modules\productions\models\WorkOperationWorker;
use yii\helpers\Json;
use yii\web\Cookie;

/**
* A "user" tábla modellje.
 * @property int $id [int(11)]
 * @property string $name [varchar(100)]
 * @property string $email [varchar(200)]
 * @property string $created_at [datetime]
 * @property string $password_hash [varchar(200)]
 * @property int $permission_set_id [int(11)]
 * @property bool $is_admin [tinyint(1)]
 * @property bool $suspended [tinyint(1)]
 * @property int $created_by [int(11)]
 * @property string $rfid [varchar(30)]
 * @property string $homepage_mode [varchar(50)]
 * @property string $quickmenu
 * @property string $last_login [datetime]
 */
class User extends \yii\db\ActiveRecord
{
    /**
    Az akutálisan bejelentkezett felhasználó. (cache céljából tárolva)
    */
    private static $logged_in_user = null;


    private $liveGroupMemberships;
	private $allGroups;
	private $capabilities;

	public function __construct($config = [])
	{
		parent::__construct($config);
		//$this->capabilities = (new Controller())->capabilities;
	}


	/**
     Tábla neve.
     */
    public static function tableName()
    {
        return 'user';
    }

	/** Feltöltött fotó (űrlapnál használva) */
	public $imageFile;

    /**
     Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['created_at', 'quickmenu', 'last_login','imageFile'], 'safe'],
            [['email'], 'email'],
            [["email"], "unique"],
            [['permission_set_id', 'is_admin', 'suspended', 'created_by'], 'integer'],
            [['email', 'password_hash'], 'string', 'max' => 200],
            [['rfid'], 'string', 'max' => 20],
        ];
    }

    /**
     Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'), //'Név',
            'email' => Yii::t('app', 'email'), //'E-mail',
            'created_at' => Yii::t('app', 'created_at'), //'Létrehozva',
            'password_hash' => Yii::t('app', 'password_hash'), //'Jelszó hash',
            'permission_set_id' => Yii::t('app', 'permission_set_id'), //'Jogosultsági kör',
            'permission_set_search' => Yii::t('app', 'permission_set_id'), //'Jogosultsági kör',
            'is_admin' => Yii::t('app', 'is_admin'), //'Admin-e',
            'suspended' => Yii::t('app', 'suspended'), //'Kitiltva',
            'created_by' => Yii::t('app', 'created_by'), //'Létrehozta',
            'created_by_search' => Yii::t('app', 'created_by'), //'Létrehozta',
            'rfid' => Yii::t('app', 'rfid'), //'RFID',
            'quickmenu' => Yii::t('app', 'quickmenu'), //'Gyorsmenü',
	        'imageFile' => Yii::t('app','imageFile'), //Kép feltöltés,
        ];
    }

    /**
    Visszaadja a felhasználót, aki létrehozta őt.
    */
    public function getCreatorUser() {
        return User::findOne($this->created_by);
    }

    /**
    Visszaadja az összes felhasználó nevét. (csoporttól függetlenül)
    */
    public static function allUserNames($assoc = false) {
	    $all = User::find()->where(['suspended' => 0])->all();

        $result = [];
        foreach ($all as $user) {
	        if ($assoc) {
		        $result[$user->id] = $user->name;
	        } else {
		        $result[$user->name] = $user->name;
	        }
        }
        return $result;
    }

	/**
	Visszaadja az összes felhasználó nevét. (csoporttól függetlenül)
	 */
	public static function allUserLeaderNames($assoc = false) {
		$currUser = User::current();

		if($currUser->can('view_all_tasks'))
			$all = User::find()->all();
		else {
			$members = ProjectMembership::find()->where(['user_id' => $currUser->id, 'leader' => true])->asArray()->indexBy('project_id')->all();

			if(!empty($members)) {
				$allUser = ProjectMembership::find()->where(['in', 'project_id', array_keys($members)])->asArray()->indexBy('user_id')->all();

				$all = User::find()->where(['in', 'id', array_keys($allUser)])->all();
			} else {
				$all = User::find()->where(['id' => $currUser->id])->all();
			}
		}

		$result = [];
		foreach ($all as $user) {
			if ($assoc) {
				$result[$user->id] = $user->name;
			} else {
				$result[$user->name] = $user->name;
			}
		}
		return $result;
	}


    /**
    *   Visszaadja az aktuális bejelentkezett felhasználót. Ha nincs ilyen, vagy lejárt
    *   a bejelentkezési token, NULL értékkel tér vissza.
    */
    public static function current() {
        if (self::$logged_in_user) {
			return	User::findOne(self::$logged_in_user->getPrimaryKey());
        }

        $login_record = Login::currentLogin();
        if (!$login_record) {
            return null;
        }

        $user = $login_record->user; // mindenképp visszaad egy user-t az idegen kulcsok miatt, mert ha nem létezne a user, akkor nem létezne a login rekord
	    //var_dump($user);
	    if ($user->suspended) {
            return null;
        }
//        if (count($user->liveMemberships) === 0 && !$user->is_admin) {
//            return null; // a felhasználó egyik csoportjának sincs aktív előfizetése
//        }
		return $login_record->user; // Minden valid. A függvény visszatér a felhasználóval.
	}

	/**
	 *   Belépteti a felhasználót. A megfelelő sütit is elhelyezi.
	 * @param bool $stay_login maradjon-e bejelentkezve
	 * @throws \Exception
	 */
    public function login($stay_login = false) {
    	$user = User::findOne($this->getPrimaryKey());
	    if($user) {
	    	$new_login = date('Y-m-d H:i:s');
	    	/*if(Apps::getDate($new_login) !== Apps::getDate($user -> last_login)) {
				$user->search_date = Apps::getDate($new_login);
				$this->search_date = Apps::getDate($new_login);
		    }*/

			$user -> last_login = $new_login;
			$user -> save(false);
	    }

        $token = bin2hex(random_bytes(20));
        $login_record = new Login;
        $login_record->user_id = $this->getPrimaryKey();
        $login_record->start_date = date("Y-m-d H:i:s");
        $lifetime = ($stay_login
	        ? (2592000) // 30 days
            : (86400) // 1 day
        );
        $login_record->end_date = date("Y-m-d H:i:s", time() + $lifetime);
        $login_record->token = $token;
        $login_record->ip_address = Yii::$app->request->getUserIP();
        $login_record->user_agent = Yii::$app->request->getUserAgent();
        $login_record->save();
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'login',
            'value' => strval($this->id) . ":" . $token,
            'expire' => $stay_login ? time() + $lifetime : 0,
        ]));

	    // Ha itt + dolog kerül hozzá akkor a UsersController changedate functionben is hozzá kell rakni
	    Yii::$app->response->cookies->add(new Cookie([
		    'name'=>'user_data',
		    'value'=> [
			    'id'=>$this->id,
			    'permission_set_id'=>$this->permission_set_id,
			    'name' => $this->name,
			    'is_admin'=>$this->is_admin,
		    ],
		    'expire' => $stay_login ? time() + $lifetime : 0,
	    ]));

        self::$logged_in_user = $this;
    }

	public function getCapabilities($persmissionsetId) {
		if($persmissionsetId) {
			$return = [];
			$capabilities = Yii::$app->db->createCommand("SELECT * FROM capability")->queryAll();
			$capabilytyIdArray = [];
			foreach (Yii::$app->db->createCommand("SELECT capability_id FROM permission WHERE permission_set_id = $persmissionsetId")->queryAll() as $capElem) {
				array_push($capabilytyIdArray,$capElem['capability_id']);
			}
			foreach ($capabilities as $capability) {
				if (in_array($capability['id'], $capabilytyIdArray)) {
					$return[$capability['id']] = $capability['name'];
				}
			}
			return $return;
		}
		return false;
	}

    /**
    Aktuális felhasználó jijelentkeztetése. (a sütit is törli)
    */
    public function logout() {
        $cookies = Yii::$app->request->cookies;
        $login_cookie = $cookies->getValue('login', '');
        if (!$login_cookie) {
            return; // nem található a cookie
        }
        $parts = explode(":", $login_cookie);
        if (count($parts) !== 2) {
            return; // nem `valami:valami` a cookie tartalma
        }
        $login_record = Login::findOne(["user_id" => intval($parts[0]), "token" => $parts[1]]);
        if ($login_record) {
            $login_record->delete();
        }
        Yii::$app->response->cookies->remove("login");
        Yii::$app->response->cookies->remove("user_data");

        self::$logged_in_user = null;
    }


    /**
    A felhasználóhoz tartozó jogosultsági kör.
    */
    public function getPermissionSet() {
        return PermissionSet::findOne($this->permission_set_id);
    }

    /**
    Mentés előtt fut le.
    */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $user = User::current();
            $this->created_at = date("Y-m-d H:i:s");
            $this->created_by = $user->id;
        }
        return true;
    }

    /**
    Van-e beállítva jelszava a felhasználónak.
    */
    public function hasPassword() {
        return !!$this->password_hash;
	    return true;
    }



    /**
    Ellenőrzi, hogy egy adott képesség birtokában van-e a felhasználó.
     * permisson_set id  ahol user->permissionset_id -
    */
//    }
    /**
    Ellenőrzi, hogy a module legalább egy képességének birtokában van-e a felhasználó.
    */
    public function canModule($module_name) {
        if ($this->is_admin) {
            return true; // az adminnak bármihez van jogosultsága
        }
        $permission_set = $this->permissionSet;
        if (!$permission_set) {
            return false; // Nincs semmihez joga az illetőnek.
        }
        $capabilities = $permission_set->capabilities;
        foreach ($capabilities as $capability) {
            if ($capability->module === $module_name) {
                return true; // található
            }
        }
        return false; // nem található
    }

    /**
    Ellenőrzi a megadott jelszó helyességét. (hash alapján)
    */
    public function validatePassword($password) {
      if (!$this->password_hash) {
            return false;
        }
        return password_verify($password, $this->password_hash);
    }

	/**
	 * Visszaadja a Quick menu json-t
	 */
	public function getQuickMenu() {
		if(!self::current())
			return null;

		return Json::decode($this->quickmenu);
	}

	public function hasPhoto() {
		return $this->photoUrl() != "/uploads/users/user.png";
	}
	public function photoUrl() {
		$path = "uploads/users/" . $this->id . ".jpg";
		if (file_exists($path)) {
			return "/" . $path . "?v=" . sha1_file($path);
		}

		return "/uploads/users/user.png";
	}

	/** Capability */
	/**
	 * Ellenőrzi, hogy egy adott képesség birtokában van-e a felhasználó.
	 * @param $capability_name
	 * @return bool
	 */
	public function can($capability_name) {
		if ($this->is_admin) {
			return true; // az adminnak bármihez van  jogosultsága
		}
		$userData = Yii::$app->getRequest()->getCookies()->getValue('user_data');
		if (!$userData) {
			return false; // Nem található. Ilyenkor biztonsági okokból ne legyen jogosultsága hozzá az illetőnek.
		}
		if (!$userData['permission_set_id']) {
			return false; // Nincs semmihez joga az illetőnek.
		}
		$capabilities = $this->getCapabilities($userData['permission_set_id']);
		foreach ($capabilities as $capability) {
			if ($capability === $capability_name) {
				return true; // található
			}
		}
		return false; // nem található
	}
	/** Capability end */

}
