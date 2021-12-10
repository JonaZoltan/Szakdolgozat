<?php

use yii\web\View;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
	'timeZone' => 'Europe/Budapest',
    'bootstrap' => ['log', 'languagepicker'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
	    'languagepicker' => [
		    'class' => 'lajax\languagepicker\Component',
		    'languages' => ['hu', 'en'], // Without name
		    //'languages' => ['hu-HU' => Yii::t('yii', 'hungary'), 'en-US' => Yii::t('yii', 'english')], // With name
	    ],
	    'i18n' => [
		    'translations' => [
			    'app*' => [
				    'class' => 'yii\i18n\PhpMessageSource',
				    'basePath' => '@app/themes/messages',
				    //'sourceLanguage' => 'hu',
				    'fileMap' => [
					    'app' => 'app.php',
					    'app/error' => 'error.php',
				    ],
			    ],
		    ],
	    ],
	    'assetManager' => [
		    'bundles' => [
			    'yii\web\JqueryAsset' => [
				    'jsOptions' => [ 'position' => View::POS_HEAD ],
				    'basePath' => '@webroot',
				    'baseUrl' => '@web',
				    'js' => [
					    'dist/js/jquery-3.5.1.min.js',
				    ]
			    ],
		    ],
	    ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-XxCQ97JvdD6EXIdDUTXOMv96nKMnEQ4',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

	    'urlManager' => [
		    'class' => 'yii\web\UrlManager',
		    'enablePrettyUrl' => true,
		    'showScriptName' => false,
		    'rules' => [
			    '' => 'site/index',
			    'ticket/<id:[0-9]+>/<token:[0-9a-f]{20}>' => 'conversations/conversations/sharable-link',
		    ],
	    ],
	    'view' => [
		    'class' => 'yii\web\View',
		    'theme' => [
			    'basePath' => '@app/themes/main',
			    'baseUrl' => '@web/themes/main',
			    'pathMap' => [
				    '@app/views' => '@app/themes/main',
				    '@app/modules' => '@app/themes/main/modules'
			    ],
		    ],
	    ],
	    'mailer' => [
		    'class' => 'yii\swiftmailer\Mailer',
		    'useFileTransport' => false,
		    'messageConfig' => [
			    'charset' => 'UTF-8',
			    'from' => ['support@szitar.hu' => 'Szitár Support'],
		    ],
		    'transport' => [
			    'class' => 'Swift_SmtpTransport',
			    'encryption' => 'TLS',
			    'host' => 'smtp.office365.com',
			    'port' => '587',
			    'username' => 'support@szitar.hu',
			    'password' => '123Szitaronline',
			    'streamOptions' => [
				    'ssl' => [
					    'allow_self_signed' => true,
					    'verify_peer' => false,
					    'verify_peer_name' => false,
				    ],
			    ],
		    ],
	    ], /* Mailer end */
	    'mail' => [
		    'class' => 'app\components\Helpers',
	    ], // Yii::$app->mail->send();
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
	'modules' => [

		/*'dynagrid'=>[
			'class'=>'\kartik\dynagrid\Module',
		],*/
		'gridview' => [
			'class' => '\kartik\grid\Module',
		],
		/*'datecontrol' => [
			'class' => '\kartik\datecontrol\Module',
		],*/

		# Modules of System END
	],

    'params' => $params,
];

#   Szitar-Net modules
$config['modules'] += require(__DIR__ . '/modules_base.php');

#   Project modules
$config['modules'] += require(__DIR__ . '/modules_project.php');

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
	    'class' => 'yii\debug\Module',
	    'allowedIPs' => ['*']
    ];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		'allowedIPs' => ['127.0.0.1', '::1', '192.168.1.*','*.*.*.*'],
		'generators' => [
			'crud' => [
				'class' => 'yii\gii\generators\crud\Generator',
				'templates' => [
					'myCrud' => '@app/myGiiTemplates/crud/default',
				],
			],
			'model' => [
				'class' => 'yii\gii\generators\model\Generator',
				'templates' => [
					'myModel' => '@app/myGiiTemplates/model/default',
				],
			],
		],
	];
}

return $config;
