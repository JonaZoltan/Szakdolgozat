<?php

namespace app\modules\apps\controllers;


use Yii;
use app\controllers\BaseController;


/**
 * DefaultController implements the CRUD actions for CostPlace model.
 */
class MigrateController extends BaseController
{


    /**
     * Not in use
     * @return mixed
     */

    public function actionIndex()
    {
	    //return $this->redirect(Url::to(["/"]));
    }

    /**
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUp()
    {

    	echo "<style>body {font-family: Arial, Helvetica, sans-serif; padding: 35px;}</style>";
    	echo "
		<h2>Response</h2>
			";
	    $oldApp = \Yii::$app;

	    $config = require \Yii::getAlias('@app'). '/config/console.php';
	    new \yii\console\Application($config);
	    echo "<div id='response'>";
	    $result = \Yii::$app->runAction('migrate/up', ['migrationPath' => '@app/migrations/', 'interactive' => false]);
	    echo "</div>";
	    \Yii::$app = $oldApp;
	    echo "
	    <script>
	    document.body.onload = function(){
	    	var str = document.getElementById('response').innerHTML; 
  	 		var res = chunk(str);
   			document.getElementById('response').innerHTML = res;
	    };
	    
	    function chunk(str) {
		    var textArr = str.split('');
		    if (textArr.length == 0 ) {
		        textArr.push('None');
		    }
			for (var i = 0; i < textArr.length; i++) {
    			if (textArr[i] == 's' && textArr[i+1] == ')') 
    			    textArr[i+2] = '<br/>';
    			if (textArr[i] == '#')  
    			    textArr[i-1] = '<br/>';
			}	
			return textArr.join('')
		};
		</script>
	    ";
    }

	public function actionDown()
	{
		$oldApp = \Yii::$app;
		$config = require \Yii::getAlias('@app'). '/config/console.php';
		new \yii\console\Application($config);
		$result = \Yii::$app->runAction('migrate/down', ['migrationPath' => '@app/migrations/', 'interactive' => false]);
		if(!$result) {
			echo "Nothing to Do!";
		}
		\Yii::$app = $oldApp;
	}

	public function actionTo($ver)
	{
		$oldApp = \Yii::$app;
		$config = require \Yii::getAlias('@app'). '/config/console.php';
		new \yii\console\Application($config);
		$result = \Yii::$app->runAction('migrate/to "'.$ver.'"', ['migrationPath' => '@app/migrations/', 'interactive' => false]);
		if(!$result) {
			echo "Nothing to Do!";
		}
		\Yii::$app = $oldApp;
	}

    public function actionGet()
    {
	    echo "<style>body {font-family: Arial, Helvetica, sans-serif; padding: 35px;}</style>";
	    echo "
		<h2>Migration History</h2>
			";
        foreach((new \yii\db\Query())
		            ->select("*")
		            ->from('migration')
		            ->all() as $migration) {
		   echo "<b>". date("Y-m-d H:m:s", $migration['apply_time']). '</b> --- ['.$migration['version'] . "]<br />";
	    }
    }
}
