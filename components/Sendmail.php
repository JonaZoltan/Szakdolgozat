<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "sendmail".
 *
 * @property integer $id
 * @property integer $priority
 * @property string $sender
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $status
 * @property string $completed_time
 * @property string $response
 * @property string $timestamp
 * @property string $attachment
 */
class Sendmail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sendmail';
    }

	/**
	 * @param bool $insert
	 * @return bool
	 */
    public function beforeSave($insert)
    {
	    if (!parent::beforeSave($insert)) {
		    return false;
	    }

	    $this->to = Json::encode(array_unique((array)$this->to));

	    return true;
    }

    public function afterFind()
    {
	    parent::afterFind();

	    $this->to = Json::decode($this->to);
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priority'], 'integer'],
            [['to', 'body'], 'required'],
            [['body'], 'string'],
            [['completed_time', 'timestamp', 'to', 'response', 'attachment'], 'safe'],
            [['sender', 'subject', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'priority' => 'Prioritás',
            'sender' => 'Küldő',
            'to' => 'Ki/Kiknek',
            'subject' => 'Tárgy',
            'body' => 'Törzs',
            'status' => 'Státusz',
            'completed_time' => 'Teljesítés ideje',
            'attachment' => 'Csatolmány',
            'response' => 'Válasz',
            'timestamp' => 'Kiküldés ideje',
        ];
    }

	/**
	 * @param $to
	 * @param $subject
	 * @param $body
	 * @param $priority
	 * @param null $attachment
	 */
    public static function add($to, $subject, $body, $priority, $attachment = null) {
    	$sendmail = new Sendmail();
    	$sendmail->to = $to;
    	$sendmail->subject = $subject;
    	$sendmail->body = $body;
    	$sendmail->attachment = $attachment;
    	$sendmail->priority = $priority;
    	$sendmail->save();
    }
}
