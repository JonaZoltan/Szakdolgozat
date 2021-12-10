<?php

return [
	"users" => [
			"class" => 'app\\modules\\users\\UsersModule',
		],

	'apps'=>[
		'class'=>'app\\modules\\apps\\AppsModule',
	],

	"logs" => [
			"class" => 'app\\modules\\logs\\LogsModule',
		],

	"errors" => [
			"class" => 'app\\modules\\errors\\ErrorsModule',
		],

	"importexport" => [
			"class" => 'app\\modules\\importexport\\ImportexportModule',
		],

	"settings" => [
			"class" => 'app\\modules\\settings\\SettingsModule',
		],

	'ajax' => [
		'class' => 'app\\modules\\ajax\\AjaxModule',
		],

	##END OF FILE
]
?>