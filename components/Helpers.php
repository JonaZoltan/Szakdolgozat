<?php

namespace app\components;

use app\modules\settings\models\Settings;

use Exception;
use Yii;

class Helpers {
	const PRIORITY_HIGH = 1;
	const PRIORITY_MEDIUM = 2;
	const PRIORITY_LOW = 3;

	private static $encryption_key = "SlRkZAODNzCsNy2ohevUSfdsf45SfkOhhfdj48ODNzCsNy2";
	public static function encrypt($plaintext) {
		$json = json_encode($plaintext);
		$ciphertext = Yii::$app->getSecurity()->encryptByPassword($json, self::$encryption_key);
		return bin2hex($ciphertext);
	}
	public static function decrypt($ciphertext) {
		$ciphertext = hex2bin($ciphertext);
		if (!$ciphertext) {
			return NULL;
		}
		$plaintext = Yii::$app->getSecurity()->decryptByPassword($ciphertext, self::$encryption_key);
		if (!$plaintext) {
			return NULL;
		}
		$decoded = json_decode($plaintext, true);
		if (!$decoded) {
			return NULL;
		}
		return $decoded;
	}

	/**
	 * Emailek végleges kiküldése
	 * @param $to
	 * @param $subject
	 * @param $body
	 * @param null $attachment
	 * @return bool|Exception
	 */
	public static function send($to, $subject, $body, $attachment = null) {

		/** Email üzenet felépítése */
		$template = file_get_contents("email-layout/index.html");
		$template = str_replace("{targy}", $subject, $template);
		$template = str_replace("{uzenet}", $body, $template);


		/** Elsőnek megpróbáljuk a configban beállított adatokkal küldeni. */
		try {
			$messages = [];
			foreach($to as $mailAddress) {
				$mail = Yii::$app->mailer->compose()
					->setTo($mailAddress)
					->setSubject($subject)
					->setTextBody($template)
					->setHtmlBody($template);

				if ($attachment) {
					$mail = $mail->attach($attachment);
				}

				$messages[] = $mail;
			}

			if($messages)
				Yii::$app->mailer->sendMultiple($messages);
		} catch (Exception $e) {
			try {
				Yii::$app->mailer->setTransport([
					'class' => 'Swift_SmtpTransport',
					'host' => Settings::getByName('smtp_host'),
					'username' => Settings::getByName('smtp_username'),
					'password' => Settings::getByName('smtp_password'),
					'port' => Settings::getByName('smtp_port'),
					'encryption' => Settings::getByName('smtp_security') === "none" ? null : Settings::getByName('smtp_security'),
					'streamOptions' => [
						'ssl' => [
							'allow_self_signed' => true, //o: true
							'verify_peer' => false,
							'verify_peer_name' => false,
						],
					],
				]);

				$messages = [];
				foreach($to as $mailAddress) {
					$mail = Yii::$app->mailer->compose()
						->setTo($mailAddress)
						->setSubject($subject)
						->setHtmlBody($template);

					if ($attachment) {
						$mail = $mail->attach($attachment);
					}

					$messages[] = $mail;
				}

				if($messages)
					Yii::$app->mailer->sendMultiple($messages);
			} catch (Exception $e2) {
				return $e2;
			}

		}

		return true;
	}

	/**
	 * Email küldés hozzáadása
	 * @param $to
	 * @param $subject
	 * @param $body
	 * @param int $priority
	 * @param null $attachment
	 */
	public static function email($to, $subject, $body, int $priority = self::PRIORITY_LOW, $attachment = null)
	{
		if (!is_array($to))
			$arrayTo[] = $to;
		else
			$arrayTo = $to;

		Sendmail::add($arrayTo, $subject, $body, $priority, $attachment);
	}
}

?>