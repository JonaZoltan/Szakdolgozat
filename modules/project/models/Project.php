<?php

namespace app\modules\project\models;

use app\modules\apps\models\Apps;
use app\modules\partners\models\Partners;
use app\modules\tasks\models\Tasks;
use app\modules\users\models\User;
use DateTime;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property integer $area_id
 * @property string $name
 * @property string $text
 * @property string $color
 * @property string $timestamp
 * @property-read \yii\db\ActiveQuery $area
 * @property-read string $fontColor Vissza adja a választott színből, hogy a betűszín fehér vagy fekete legyen-e
 * @property bool $archived [tinyint(1)]
 * @property string $partner_ids [json]
 */
class Project extends ActiveRecord
{
	public $workTypes;
	/**
	 * @var mixed|null
	 */

	public static function tableName()
    {
        return 'project';
    }

	public function beforeSave($insert)
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}
		if ($this->partner_ids) {
			$this->partner_ids = Json::encode($this->partner_ids);
		}

		return true;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->partner_ids = Json::decode($this->partner_ids);
	}

	public function rules()
    {
        return [
            [['area_id', 'name', 'color', 'workTypes'], 'required'],
            [['area_id', 'archived'], 'integer'],
            [['text'], 'string'],
            [['timestamp', 'workTypes','partner_ids'], 'safe'],
            [['name', 'color'], 'string', 'max' => 255],
	        [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::class, 'targetAttribute' => ['area_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'area_id' => Yii::t('app', 'area_id'),
            'name' => Yii::t('app', 'name'),
            'text' => Yii::t('app', 'text'),
            'color' => Yii::t('app', 'color'),
            'timestamp' => Yii::t('app', 'timestamp'),
	        'members' => Yii::t('app', 'project_members'),
	        'workTypes' => Yii::t('app', 'workTypes'),
	        'archived' => Yii::t('app', 'archived'),
	        'partner_ids' => Yii::t('app', 'partners')
        ];
    }

    public function getProjectHasWorktypes()
    {
		    return array_keys(ProjectWorktypes::find()->where(['project_id' => $this->id])->asArray()->indexBy('worktype_id')->all());
    }

	public static function allProjectNames($assoc = false) {
		$currUser = User::current();

		if($currUser->can('view_all_tasks'))
			$all = Project::find()->where(['archived' => 0])->all();
		else {
			$members = ProjectMembership::find()->where(['user_id' => $currUser->id])->asArray()->indexBy('project_id')->all();
			$all = Project::find()->where(['in', 'id', array_keys($members)])->andWhere(['archived' => 0])->all();
		}

		$result[0] = 'Általános';

		foreach ($all as $project) {
			if ($assoc) {
				$result[$project->id] = $project->name;
			} else {
				$result[$project->name] = $project->name;
			}
		}

		return $result;
	}

	public static function allProjectLeaderNames($assoc = false) {
		$currUser = User::current();

		if($currUser->can('view_all_tasks'))
			$all = Project::find()->all();
		else {
			$members = ProjectMembership::find()->where(['user_id' => $currUser->id, 'leader' => true])->asArray()->indexBy('project_id')->all();
			$all = Project::find()->where(['in', 'id', array_keys($members)])->all();
		}

		$result[0] = 'Általános';

		foreach ($all as $project) {
			if ($assoc) {
				$result[$project->id] = $project->name;
			} else {
				$result[$project->name] = $project->name;
			}
		}

		return $result;
	}

		/**
	 * @return ActiveQuery
	 */
	public function getArea()
	{
		return $this->hasOne(Area::class, ['id' => 'area_id']);
	}

	public function getPartnerNames() : string{
		if(!$this->partner_ids)
			return '';
		$partners = Partners::find()
			->where(['in', 'id', $this->partner_ids])
			->all();
		$names = ArrayHelper::map($partners, 'name', 'name');

		return implode(', ',$names);
	}

	/**
	 * Vissza adja a választott színből, hogy a betűszín fehér vagy fekete legyen-e
	 */
	public function getFontColor()
	{
		if(strlen($this->color) === 7) {
			$split = str_split(str_replace('#', '', $this->color), 2);

			$r = hexdec($split[0]);
			$g = hexdec($split[1]);
			$b = hexdec($split[2]);

			$brightness = round((($r * 299) + ($g * 587) + ($b * 114)) / 1000);

			return $brightness > 125 ? 'black' : 'white';
		}

		return 'black';
	}

	/** Számolás */

	public static function setProjectHours ($from, $to) {
		$date1 = new DateTime($from);
		$date2 = new DateTime($to);
		$diff = $date2->diff($date1);

		return round(($diff -> i + $diff->h*60 + $diff->d*24) / 60, 2);


	}

	public static function calcProjectHours ($query) {
		$all = $query->all();
		$minutes =  0;
		foreach ($all as $elem) {
			$minutes += self::setProjectHours($elem->working_datetime_start, $elem->working_datetime_end);
		}

		return $minutes;
	}

	public static function ProjectTypeHours ($query){
		$all = $query->all();
		$workTypeArray = [];
		foreach ($all as $data) {
			$time = self::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

			if(isset($workTypeArray[ $data->worktype_id ])) {
				$workTypeArray[ $data->worktype_id ] += $time;
			} else {
				$workTypeArray[ $data->worktype_id ] = $time;
			}
		}
		return $workTypeArray;
	}

	public static function ProjecLaborHours ($query){
		$all = $query->all();
		$userTimeArray = [];
		foreach ($all as $data) {
			$time = self::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

			if(isset($userTimeArray[ $data->user_id ])) {
				$userTimeArray[ $data->user_id ] += $time;
			} else {
				$userTimeArray[ $data->user_id ] = $time;
			}
		}
		return $userTimeArray;
	}

	public static function ProjectMonthHours ($query){
		$all = $query->all();
		$monthTimeArray = [];
		foreach ($all as $data) {
			$time = self::setProjectHours($data->working_datetime_start, $data->working_datetime_end);


			$month = Yii::t('app', Apps::getDate($data->working_datetime_start, "F"));
			if (isset($monthTimeArray[ $month ])) {

				$monthTimeArray[ $month ] += $time;
			} else {
				$monthTimeArray[ $month ] = $time;
			}
		}
		return $monthTimeArray;
	}

	// Költség
	public static function calcProjectCost ($query) {
		$all = $query->all();
		$minutes =  0;
		foreach ($all as $elem) {
			$minutes += Project::setProjectHours($elem->working_datetime_start, $elem->working_datetime_end);
		}

		$userTimeArray = [];
		$all = $query->all();

		foreach ($all as $data) {
			$time = self::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

			if($data->project_id !== 0) {
				$member = ProjectMembership::findOne(["project_id" => $data->project_id, "user_id" => $data->user_id]);

				if($member) {
					if (isset($userTimeArray[$data->user_id])) {
						$userTimeArray[$data->user_id] += $time * $member->financing;
					} else {
						$userTimeArray[$data->user_id] = $time * $member->financing;
					}
				} else {
					var_dump("Nem található a projektben:");
					var_dump($data); die();
				}
			}
		}
		$cost=0;
		foreach($userTimeArray as $sum)
		{
			$cost += $sum;
		}

		return number_format($cost,0,'.',' ');

	}

	public static function ProjectRecommendedHours ($query)
	{
		$all = $query->all();
		$hours = 0;
		foreach ($all as $data) {
			$recommended = Tasks::findOne(["id"=>$data->id,"user_id"=>$data->user_id]);
			$hours += $recommended->recommended_hours;
		}
		return $hours;
	}

}
