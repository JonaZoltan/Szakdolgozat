<?php
namespace app\modules\settings\models;

use app\modules\users\models\User;
use Yii;

class SettingsForm extends \yii\base\Model {
    public $smtp_name;
    public $smtp_address;
    public $smtp_host;
    public $smtp_port;
    public $smtp_security;
    public $smtp_username;
    public $smtp_password;
    public $version;

    public function rules() {
        return [
            [['smtp_name'], 'required', 'when' => function ($model) {
                    return $this->smtp_address || $this->smtp_host || $this->smtp_port
                        || $this->smtp_username || $this->smtp_password;
             }, 'enableClientValidation' => false, 'message' => 'Kötelező megadni.'],
            
            [['smtp_address', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password'], 'required', 'when' => function ($model) {
                    return $this->smtp_name;
             }, 'enableClientValidation' => false, 'message' => 'Kötelező megadni.'],
            
            [['smtp_port'], 'integer', 'min' => 1, 'max' => 65535],
            [['version'], 'string', 'max' => 8],
            [['smtp_security'], 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'smtp_name' => Yii::t('app', 'smtp_name'),
            'smtp_address' => Yii::t('app', 'smtp_address'),
            'smtp_host' => Yii::t('app', 'smtp_address'),
            'smtp_port' => Yii::t('app', 'smtp_port'),
            'smtp_security' => Yii::t('app', 'smtp_security'),
            'smtp_username' => Yii::t('app', 'smtp_username'),
            'smtp_password' => Yii::t('app', 'smtp_password'),
            'version' => Yii::t('app', 'version'),
        ];
    }
}

?>