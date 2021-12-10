<?php

	return [
		'sendmail' => [
			'class' => 'app\\components\\sendmail\\SendmailModule',
		],
		'project' => [
			'class' => 'app\modules\project\ProjectModule',
		],
		'tasks' => [
			'class' => 'app\modules\tasks\TasksModule',
		],
		'controlling' => [
			'class' => 'app\modules\controlling\ControllingModule',
		],
		'partners' => [
			'class' => 'app\modules\partners\Partners',
		],
		'formschemes' => [
			'class' => 'app\\modules\\formschemes\\FormSchemesModule',
		],

	];

?>