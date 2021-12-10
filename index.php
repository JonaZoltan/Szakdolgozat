<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

defined('WEB_ROOT') or define('WEB_ROOT', dirname(__FILE__));

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');

if(YII_DEBUG) {
	try {
		(new yii\web\Application($config))->run();
	} catch (Exception $ex) {
		var_dump($ex); die();
	}
} else {
	(new yii\web\Application($config))->run();
}