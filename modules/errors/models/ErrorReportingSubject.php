<?php

namespace app\modules\errors\models;

use Yii;

/**
 * A "error_reporting_subject" nevű tábla modell osztálya.
 */
class ErrorReportingSubject extends \yii\db\ActiveRecord
{
    /**
     * Táblanév.
     */
    public static function tableName()
    {
        return 'error_reporting_subject';
    }

    /**
     * Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'subject'),
        ];
    }

    /**
     * Visszaadja az összes hibabejelentést, amelynek ez a tárgya.
     */
    public function getErrorReportings()
    {
        return $this->hasMany(ErrorReporting::className(), ['subject' => 'id']);
    }
    
    /**
    Visszaadja az összes rendszerben rögzített hibabejelentési tárgy mezőt.
    */
    public static function allSubjects($assoc = false) {
        $all = ErrorReportingSubject::find()->all();
        $result = [];
        foreach ($all as $item) {
            if ($assoc) {
                $result[$item->id] = $item->name;
            } else {
                $result[] = $item->name;
            }
        }
        return $result;
    }
}
