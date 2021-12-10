<?php

namespace app\modules\users\models;

use Yii;

/**
 * A "capability" táblához tartozó modell.
 */
class Capability extends \yii\db\ActiveRecord
{
    /**
     Táblanév.
     */
    public static function tableName()
    {
        return 'capability';
    }

    /**
     Validálási szabályok.
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 80],
            ['name', 'unique'],
            [['description'], 'string', 'max' => 1024],
            [['name'], 'unique'],
            [['module'], 'safe'],
        ];
    }

    /**
     Oszlopnevek.
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'name' => Yii::t('app', 'name'),
            'description' => Yii::t('app', 'description'),
            'module' => Yii::t('app', 'module'),
        ];
    }

    public static $disabledCapability = [
    	"import", "export", "backup",
	    "permissions",
	    "groupsettings",
	    // Part_rate
	    "create_part_rate", "update_part_rate", "view_part_rate", "delete_part_rate",
	    // Security, Security_tandard
	    "create_security", "update_security", "view_security", "delete_security",
	    "create_security_standard", "update_security_standard", "view_security_standard", "delete_security_standard",
	    // OEE TP
	    "create_oee_tp", "update_oee_tp", "view_oee_tp", "delete_oee_tp",
    ];

	/**
	 * Visszaadja az összes rendszerben lévő képességet.
	 * @param bool $assoc
	 * @return array
	 */
    public static function allCapabilityNames($assoc = false) {
        $all = Capability::find()->all();
        $results = [];
        foreach ($all as $item) {
            if ($assoc) {
            	if(!in_array($item->name, self::$disabledCapability)) {
					$itm = explode(":", $item->description);

					if(count($itm) > 1) {
						$result = $itm[0].": ".Yii::t('app', str_replace(' ', '', $itm[1]));
					} else {
						$result = $itm[0];
					}

		            $results[$item->id] = $result;
	            }
            } else {
	            if(!in_array($item->name, self::$disabledCapability)) {
		            $itm = explode(":", $item->description);

		            if(count($itm) > 1) {
			            $result = $itm[0].": ".Yii::t('app', str_replace(' ', '', $itm[1]));
		            } else {
			            $result = $itm[0];
		            }

		            $results[] = $result;
	            }
            }
        }
        return $results;
    }
    
    /**
    A modulok elnevezése.
    */
    public static $module_names = [
        "base" => "Alap modul",
        "plant" => "Plant modul",
        "oee" => "OEE modul",
        "spoi" => "SPOI modul",
        "qa" => "QA modul",
        "s5" => "5S modul",
	    "administration" => "Adminisztráció modul",
	    "otif" => "OTIF modul",
	    "monitoring" => "Monitoring modul",
	    "security" => "Security modul",
    ];
    
    /**
    A modul, amelyhez a képesség tartozik.
    */
    public function moduleName() {
        return self::$module_names[$this->module];
    }
}
