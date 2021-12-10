<?php

namespace app\controllers;

use app\components\Helpers;
use app\components\Sendmail;
use app\modules\apps\models\Apps;
use app\modules\partners\models\Partners;
use app\modules\users\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

class CheckController extends Controller
{

	/**
	 * @throws \Exception
	 */
	public function actionPartners() {
		/**
		 * HA van utolsó kapcsolattartás
		 *  ÉS uk-eltelt nap > értesítés ideje
		 *  ÉS ha még nem volt email küldve
		 *
		 * akkor Helpers::email($to, $subject, $body, int $priority = self::PRIORITY_LOW, $attachment = null)
		 */
		$partners = Partners::find()
			->all();
		foreach ($partners as $partner) {
			$lastContactEvent = $partner->lastContactEvent;
			$daysSince = $partner->getDaysSince($lastContactEvent->when);
			$date = Apps::getDate($lastContactEvent->when);
			if ($lastContactEvent
				and $daysSince >= $partner->alert_day
				and $partner->email_sent == false
				and $partner->user_ids) {
				$to = User::find()
					->where(['in', 'id', $partner->user_ids])
					->all();
				$to = ArrayHelper::map($to, 'id', 'email');
				$subject = "Kapcsolattartás: $partner->name";
				$partnerLink = Html::a($partner->name, Url::to(['/partners/partners/view', 'id' => $partner->id], true));

				$body = "Kedves Munkatársunk!<br>
						A <b>$partnerLink</b> ügyfelünkkel utoljára $date ($daysSince napja) napon volt kapcsolattartás.";

				Helpers::email(array_values($to), $subject, $body);

				$partner->email_sent = true;
				$partner->save();
			}
		}
	}
}
