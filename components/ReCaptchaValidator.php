<?php
namespace app\components;

use Yii;
use yii\validators\Validator;

class ReCaptchaValidator extends Validator
{
	public static $secret = "6Le9xD0UAAAAACPawtjgMxejjyUpJG8HjPeS4jlO";
	
	public static function validate_captcha($captcha_response) {
		$api_url = "https://www.google.com/recaptcha/api/siteverify";
		$response = file_get_contents($api_url . "?" . http_build_query([
			'secret' => self::$secret,
			'response' => $captcha_response,
			'remoteip' => Yii::$app->getRequest()->getUserIP()
		]));
		$data = json_decode($response, true);
		return $data['success'];
	}
	
	public function validateAttribute($model, $attribute)
	{
		$email = $model->$attribute;
		if (!self::validate_captcha($email)) {
			$this->addError($model, $attribute, "Hibás ellenőrző kód.");
		}
	}
}
?>
