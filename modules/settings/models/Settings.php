<?php

namespace app\modules\settings\models;

use Yii;

/**
 * A "settings" táblához tartozó modell.
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * Tábla neve.
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 2000],
        ];
    }

    /**
     * Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'),
            'value' => Yii::t('app', 'value'),
        ];
    }
    
    /**
    Visszaad egy adott beállítási értéket annak ID-ja alapján.
    */
    public static function get($id) {
        $record = Settings::findOne($id);
        if (!$record) {
            return '';
        }
        return $record->value;
    }
    
    /**
    Visszaad egy adott beállítási értéket annak neve alapján.
    */
    public static function getByName($name) {
        $record = Settings::findOne(["name" => $name ]);
        if (!$record) {
            return '';
        }
        return $record->value;
    }
    
    /**
    Megváltoztatja a beállítás értékét. Paraméterként a beállítás neve és az új értéket kell megadnunk.
    */
    public static function setByName($name, $val) {
        $record = Settings::findOne(["name" => $name ]);
        if ($record) {
            $record->value = $val;
            $record->save(false);
        } else {
            $record = new Settings;
            $record->name = $name;
            $record->value = $val;
            $record->save(false);
        }
    }
}
