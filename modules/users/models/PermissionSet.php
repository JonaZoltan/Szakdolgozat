<?php

namespace app\modules\users\models;

use Yii;

//use app\modules\groups\models\Group;

/**
A "permission_set" táblázhoz tartozó csoport.
 */
class PermissionSet extends \yii\db\ActiveRecord
{
    /**
     Tábla neve.
     */
    public static function tableName()
    {
        return 'permission_set';
    }

    /**
    Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'),
	        'capability_search' => Yii::t('app', 'capabilitys'),
            'user_id' => 'Felhasználóra korlátozás',
        ];
    }
    
    /**
     Kapcsolódó felhasználó.
     */
    public function getUser() {
        return User::findOne($this->user_id);
    }
    
    /**
     Felhasználóhoz köthető, egyedi jogosultsági csoport-e. (nincs használva)
     */
    public function isPersonal() {
        return !!$this->user_id;
    }
    
    /**
     HTML nézete a csoportnak. Kiíratásnál használjuk.
     */
    public function toHtml() {
        $personal = $this->isPersonal();
        if ($personal) {
            return '<span class="badge">'.Yii::t('app', 'individual').'</span>';
        }
//        $group = $this->group;
//        if ($group) {
//            return $this->name . " (" . $group->name . ")";
//        }
        return $this->name;
    }
    
    /**
    Visszaadja a rendszer összes jogosultsági körét (csoporttól függetlenül mindet!).
    */
    public static function allPermissionSetNames($assoc = false) {
        $all = PermissionSet::find()->all();
        $results = [];
        foreach ($all as $item) {
            if ($assoc) {
                $results[$item->id] = $item->name;
            } else {
                $results[] = $item->name;
            }
        }
        return $results;
    }
    
//    /**
//    Visszaadja egy adott csoport összes jogosultsági körének nevét.
//    */
    public static function allGroupPermissionSetNames($assoc = false) {
        $all = PermissionSet::find()->all();
        $results = [];
        foreach ($all as $item) {
            if ($assoc) {
                $results[$item->id] = $item->name;
            } else {
                $results[] = $item->name;
            }
        }
        return $results;
    }
    
    /**
    Visszaadja az összes jogosultságot, amely a jogosultsági körhöz tartozik.
    */
    public function getPermissions() {
        return Permission::find()->where(["permission_set_id" => $this->id])->all();
    }
    
    /**
    Visszaadja az össszes képességet, amely a jogosultsági körhöz tartozik.
    */
    public function getCapabilities() {
        $permissions = $this->permissions;
        $all = [];
        foreach ($permissions as $permission) {
            $all[] = $permission->capability;
        }
        return $all;
    }
    
    /**
    Visszaadja az összes kapcsolódó képesség ID-ját.
    */
    public function getCapabilityIds() {
        $ids = [];
        foreach ($this->capabilities as $cap) {
            $ids[] = $cap->id;
        }
        return $ids;
    }
}
