<?php

namespace app\modules\tasks\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "holiday_extra".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $holiday_day
 * @property-read int $freeDay
 * @property-read mixed $declinedDay
 * @property-read mixed $acceptedDay
 * @property-read mixed $reviewDay
 * @property int $year [int(11)]
 * @property string $disabled_user
 */
class HolidayExtra extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holiday_extra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'holiday_day', 'year'], 'required'],
            [['user_id', 'holiday_day', 'year'], 'integer'],
	        [['disabled_user'], 'safe'],
	        [['user_id', 'year'], 'unique',
		        'targetAttribute' => ['user_id', 'year']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'user_id' => Yii::t('app', 'user_id'),
            'holiday_day' => Yii::t('app', 'holiday_day'),
            'disabled_user' => Yii::t('app', 'disabled_user'),
            'year' => Yii::t('app', 'year'),
        ];
    }

	/**
	 * Fennmaradó napok lekérése
	 * @return bool|int|string|null
	 */
    public function getFreeDay() {
    	$holidays = Holiday::find()
		    ->where(['user_id' => $this->user_id, 'accepted' => 1, 'year(date)' => $this->year])
		    ->count();

		return $this->holiday_day - $holidays;
    }

	/**
	 * Elutasított napok lekérése
	 * @return bool|int|string|null
	 */
    public function getDeclinedDay() {
	    return Holiday::find()
		    ->where(['user_id' => $this->user_id, 'accepted' => 2, 'year(date)' => $this->year])
		    ->count();
    }

	/**
	 * Elfogadott/szabadnapok lekérése
	 * @return bool|int|string|null
	 */
    public function getAcceptedDay() {
	    return Holiday::find()
		    ->where(['user_id' => $this->user_id, 'accepted' => 1, 'year(date)' => $this->year])
		    ->count();
    }

	/**
	 * Szabadságok kérésének lekérése
	 * @return bool|int|string|null
	 */
    public function getReviewDay() {
	    return Holiday::find()
		    ->where(['user_id' => $this->user_id, 'accepted' => 0, 'year(date)' => $this->year])
		    ->count();
    }
}
