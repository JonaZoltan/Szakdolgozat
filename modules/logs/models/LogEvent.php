<?php

namespace app\modules\logs\models;

use Yii;

/**
 * A "log_event" táblához tartozó modell.
 */
class LogEvent extends \yii\db\ActiveRecord
{
    /**
     * Tábla neve.
     */
    public static function tableName()
    {
        return 'log_event';
    }

    /**
     * Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 1024],
            [['name'], 'unique'],
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
            'description' => Yii::t('app', 'description'),
        ];
    }
    
    /**
    Az összes naplóesemény nevével tér vissza.
    */
    public static function allNames($assoc = false) {
        $all_log_events = LogEvent::find()->orderBy("name ASC")->all();
        $result = [];
        foreach ($all_log_events as $event) {
            if ($assoc) {
                $result[$event->name]= $event->name;
            } else {
                $result[] = $event->name;
            }
        }
        return $result;
    }
}
