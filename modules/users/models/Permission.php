<?php

namespace app\modules\users\models;

use Yii;

/**
 * A "permission" táblához tartozó modell.
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     Tábla neve.
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['permission_set_id', 'capability_id'], 'required'],
            [['capability_id', 'permission_set_id'], 'unique', 'targetAttribute' => ['capability_id', 'permission_set_id'], "message" => "Már hozzá lett adva."],
            [['permission_set_id', 'capability_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'permission_set_id' => Yii::t('app', 'permission_set'),
            'permission_set_search' => Yii::t('app', 'permission_set'),
            'capability_id' => Yii::t('app', 'capability'),
            'created_at' => Yii::t('app', 'created_at'),
        ];
    }
    
    /**
    Visszaadja a kapcsolódó jogosultsági csoportot.
    */
    
    public function getPermissionSet() {
        return PermissionSet::findOne($this->permission_set_id);
    }
    
    /**
    Visszaadja a jogosultsághoz tartozó képességet.
    */
    
    public function getCapability() {
        return Capability::findOne($this->capability_id);
    }
    
    /**
    A modell mentése előtt fut le.
    */
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s");
        }
        return true;
    }
}
