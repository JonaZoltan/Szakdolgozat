<?php

namespace app\modules\logs\models;

use app\modules\users\models\User;

use Yii;
use yii\db\Exception;

/**
 * A "log" tábla modell osztálya.
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * Tábla neve.
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['log_event_id', 'user_id'], 'integer'],
            [['parameters'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log_event_id' => 'Típus',
	        'event_name' => Yii::t('app', 'event'),
	        'log_text' => Yii::t('app', 'log_text'),
	        'created_at_range' => Yii::t('app', 'date'),
            'parameters' => 'Parameters',
            'user_id' => 'User ID',
            'created_at' => 'Időpont',
        ];
    }
    
    /**
    A naplóbejegyzéshez tartozó naplóeseménnyel tér vissza.
    */
    public function getLogEvent() {
        return LogEvent::findOne($this->log_event_id);
    }


	/**
	 * Return with full needed Array for Logging.
	 * @param bool $model
	 * @param int $id
	 * @param string $table
	 * @param string $modify_name
	 * @return array keys ['nev']: user name; ['user_id']: user id; ['modosult']: modified parameters (from model).
	 * @throws Exception
	 */
	public static function getParameters($model = false, $table = "undefined", $modify_name = "undefined") {
		if($model) {
			$real = get_class($model)::findOne($model->id);

			$modified = "[ $modify_name ] - ";
			foreach ($model as $key => $value) {
				if (strcmp($model->$key, $real->$key) !== 0)
					$modified .= "[$key] $real[$key] => $model[$key] ";
			}

			return [
				"user_id" => User::current()['id'],
				"nev" => User::current()['name'],
				"table" => $table,
				"modosult" => $modified
			];
		} else {
			throw new Exception('Need model');
		}
    }

    public static function getAddParameters($model = false, $table = "undefined") {
		if($model) {
			$datas = "";
			foreach ($model as $key => $value) {
				$datas .= "[$key] $model[$key] ";
			}

			return [
				"user_id" => User::current()['id'],
				"nev" => User::current()['name'],
				"table" => $table,
				"nev_created" => $datas
			];

		} else {
			throw new Exception('Need model');
		}
    }

    /**
    Új naplóbejegyzés készítése. Az esemény nevét és a paramétereket kell átadni.
    */
    public static function add($event_name, $params = []) {
        $user = User::current();
        $log_event = LogEvent::findOne(["name" => $event_name]);
        if ($user && $log_event) {
            $log = new Log;
            $log->log_event_id = $log_event->id;
            $log->user_id = $user ? $user->id : NULL;
            $log->parameters = json_encode($params);
            $log->created_at = date("Y-m-d H:i:s");
            $log->cached_text = $log->toString();
            $log->save(false);
        }
    }
    
    /**
    Stringgé alakítja a naplóbejegyzést az esemény szövegezése és a paraméterek beilleszte által.
    */
    public function toString() {
        $event = $this->logEvent;
        $text = $event->description;
        $params = json_decode($this->parameters);
	        foreach ($params as $key => $value) {
	        	$text = is_object($value) ? str_replace("{" . $key . "}", serialize($value), $text) : str_replace("{" . $key . "}", $value, $text) ;
        }
        return $text;
    }

}
