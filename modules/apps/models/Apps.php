<?php

namespace app\modules\apps\models;

use app\modules\users\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;


class Apps extends ActiveRecord
{

	/**
	 *
	 */
	const START_DATE = '2020-01-01 00:00';

	const HOLIDAY = [
		'2020' => [
			'01/01/2020',
			'03/15/2020',
			'04/10/2020',
			'04/13/2020',
			'05/01/2020',
			'06/01/2020',
			'08/20/2020',
			'08/21/2020',
			'10/23/2020',
			'11/01/2020',
			'12/24/2020',
			'12/25/2020',
			'12/26/2020',
		],
		'2021' => [
			'01/01/2021',
			'03/15/2021',
			'04/02/2021',
			'04/05/2021',
			'05/01/2021',
			'05/24/2021',
			'08/20/2021',
			'10/23/2021',
			'11/01/2021',
			'12/24/2021',
			'12/25/2021',
			'12/26/2021',
		],
	];

	/**
	 * @param $string
	 * @return array|mixed
	 */
	public static function jsonDecode($string) {
		$return = json_decode($string);
		if (!is_array($return) && !empty($return)) {
			$return = json_decode($return);
		}
		$return = is_object($return) ? (array)$return : $return;
		return $return;
	}

	/**
	 * @param $minutes
	 * @param string $format
	 * @return string|void
	 */
	public static function convertMinsToHoursMins($minutes, $format = '%2dóra %2dperc') {
		if ($minutes < 1) {
			return;
		}
		$hours = floor($minutes / 60);
		$min = ($minutes % 60);
		return sprintf($format, $hours, $min);
	}

	/**
	 * @param $fromDate
	 * @param $toDate
	 * @return false|float Numbers of day
	 */
	public static function dayDiff($fromDate, $toDate) {
		$from = strtotime($fromDate); // or your date as well
		$to = strtotime($toDate);
		$datediff = ($from>$to) ? $from - $to : $to - $from;
		return round($datediff / (60 * 60 * 24));
	}

	/**
	 * @param $date
	 * @param string $format
	 * @return bool|string
	 */
	public static function dateBeautifier($date, $format = '') {
		if (Yii::$app->language == "hu") {
			switch ($format) {
				case '':
					return date("Y. ", strtotime($date)) . Yii::t('app', 'month_'.date("m", strtotime($date))).date(" d.", strtotime($date));
					break;
				case 'fulltime':
					return date("Y. ", strtotime($date)) . Yii::t('app', 'month_'.date("m", strtotime($date))).date(" d.", strtotime($date)). ' ' . date("H:i", strtotime($date));
					break;
				case  'month':
					return date("Y. ", strtotime($date)) . Yii::t('app', 'month_'.date("m", strtotime($date)));
					break;
				default:
					throw new \Exception('Unsupported time format');
			}

		}

		if (Yii::$app->language == "en") {
			switch ($format) {
				case '':
					return date("d ", strtotime($date)) . Yii::t('app', 'month_' . date("m", strtotime($date))) . date(" Y", strtotime($date));
					break;
				case 'fulltime':
					return date("Y. ", strtotime($date)) . Yii::t('app', 'month_'.date("m", strtotime($date))).date(" d.", strtotime($date)). ' ' . date("H:i", strtotime($date));
					break;
				case  'month':
					return Yii::t('app', 'month_'.date("m", strtotime($date))). date(" Y", strtotime($date));
					break;
				default:
					throw new \Exception('Unsupported time format');
			}


		}

		return false;
	}

	/**
	 * @param $data
	 * @return |null
	 */
	public static function vardump($data) {
		if (User::current()->is_admin)
			self::vardump($data);
		return NULL;
	}


	/**
	 * @return array
	 */
	public static function getAllQuickMenu() {
		return [
			"pelda1" => [
				"translate" => Yii::t('app', 'pelda1'),
				"url" => "pelda1url",
				"fa-icon" => "fa-user-tag",
			],
		];
	}

	/**
	 * @return array
	 */
	public static function getAllQuickMenuArray() {
		$array = [];
		foreach ($items = Apps::getAllQuickMenu() as $key => $item) {
			$array[$key] = $item['translate'];
		}

		return $array;
	}

	/**
	 * @param $date
	 * @param string $param
	 * @return false|string
	 */
	public static function getDate($date, $param = "Y-m-d") {
		return date($param, strtotime($date));
	}

	/**
	 * @param $date
	 * @return false|string
	 */
	public static function getFirstDay($date) {
		return date("Y-m-01", strtotime($date));
	}

	/**
	 * @param $date
	 * @return false|string
	 */
	public static function getLastDay($date) {
		return date("Y-m-t", strtotime($date));
	}

	/**
	 * Vissza adja az aktuális dátumból a hét első napját
	 * @param $date
	 * @param string $param
	 * @return false|string
	 */
	public static function getFirstDayThisWeek($date, $param = "Y-m-d") {
		return date($param, strtotime('monday this week', strtotime($date)));
	}

	/**
	 * Vissza adja az aktuális dátumból a hét utolsó napját
	 * @param $date
	 * @param string $param
	 * @return false|string
	 */
	public static function getLastDayThisWeek($date, $param = "Y-m-d") {
		return date($param, strtotime('sunday this week', strtotime($date)));
	}

}