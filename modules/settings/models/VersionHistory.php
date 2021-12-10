<?php

namespace app\modules\settings\models;

use app\modules\groups\models\Group;

use Yii;

/**
 * This is the model class for table "version_history".
 *
 * @property integer $id
 * @property string $number
 * @property string $description
 * @property string $release_date
 */
class VersionHistory extends \yii\db\ActiveRecord
{

	/**
	 * The default version of the framework. View in footer.
	 * Begin numbering the version system from here, if not any
	 * database record for version.
	 * @var string
	 */
	static private $defaultVersion = "0.0";

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'description', 'release_date'], 'required'],
            [['description'], 'string'],
            [['release_date'], 'safe'],
            [['number'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'number' => Yii::t('app', 'number_version'),
            'description' => Yii::t('app', 'description'),
            'release_date' => Yii::t('app', 'release_date'),
        ];
    }
    
    public static function currentVersion() {
     $history = VersionHistory::find();
	   $versionHistory = VersionHistory::getDb()->cache(function ()  {
	       return VersionHistory::find()->orderBy('release_date DESC')->limit(1)->asArray()->all();
	    });

        if (count($versionHistory) === 0) {
	        /* default version number */
			$return['number'] = self::$defaultVersion;
			$return['description'] = '';
			$return['release_date'] = date('Y-m-d');
			$return['activated'] = date('Y-m-d');
        	return $return;
        }
	    $activated = VersionHistory::getDb()->cache(function ()  {
		    return VersionHistory::find()->orderBy('release_date ASC')->limit(1)->asArray()->all();
	    });
	    $versionHistory[0]['activated'] = $activated[0]['release_date'];
        return $versionHistory[0];
    }
    

    public function  seenBy($userId, $versionId) {
        return !!VersionView::findOne([
	        "user_id" => $userId,
	        "version_id" =>$versionId,
        ]);
    }
}
