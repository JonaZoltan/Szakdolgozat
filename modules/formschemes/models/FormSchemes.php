<?php

namespace app\modules\formschemes\models;

use Yii;

/**
 * This is the model class for table "form_schemes".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 */
class FormSchemes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_schemes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'),
            'text' => Yii::t('app', 'text'),
        ];
    }
}
