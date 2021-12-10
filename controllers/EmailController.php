<?php

namespace app\controllers;

use app\components\Helpers;
use app\components\Sendmail;
use Yii;
use yii\web\Controller;

class EmailController extends Controller
{

	public function actionCheck($priority = null) {
		$emails = Sendmail::find()
			->where(['completed_time' => null]);

		if($priority)
			$emails->andWhere(['priority' => $priority]);

		$emails = $emails->all();

		foreach ($emails as $email) {
			$return = Yii::$app->mail->send($email->to, $email->subject, $email->body, $email->attachment);

			if($return && is_bool($return)) { /** Sikeres email küldés */
				$email->completed_time = date('Y-m-d H:i:s');
				$email->status = 'completed';
				$email->save();
			} elseif ($return && is_object($return)) {
				$email->completed_time = date('Y-m-d H:i:s');
				$email->status = 'error';
				$email->response = $return->getMessage();
				$email->save();
			}
		}
	}
}
