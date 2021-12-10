<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.01.29., 17:54:46
 * The used disentanglement, and any part of the code
 * Excell.php own by the author, Bencsik Matyas.
 */


namespace app\modules\apps\models;


use app\modules\events\models\Events;
use app\modules\itemdescriptions\models\ItemDescriptions;
use app\modules\lines\models\Lines;
use app\modules\mpd\models\Mpd;
use app\modules\shiftitem\models\ShiftItem;use app\modules\users\models\User;
use http\Url;
use PHPExcel_Style_Alignment;
use Yii;
use yii\db\ActiveRecord;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PHPExcel;
use PHPExcel_IOFactory;
use yii\db\Query;

class Excel extends ActiveRecord
{
	static public $directory = "excel/";

	private static function getLetter($c) {
		$c = intval($c);
		if ($c <= 0) return '';
		$letter = '';

		while($c != 0){
			$p = ($c - 1) % 26;
			$c = intval(($c - $p) / 26);
			$letter = chr(65 + $p) . $letter;
		}

		return $letter;
	}

}