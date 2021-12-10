<?php

namespace app\modules\worktimes\models;

use Yii;

/**
 * This is the model class for table "rfid_log".
 *
 * @property integer $id
 * @property string $from
 * @property string $rfid
 * @property string $timestamp
 */
class RfidLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rfid_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['from', 'rfid'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'from' => 'from',
            'rfid' => 'rfid',
            'timestamp' => 'timestamp',
        ];
    }
}
