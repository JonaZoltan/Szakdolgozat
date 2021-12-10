<?php

use app\modules\apps\models\Apps;
use app\modules\project\models\Area;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use app\modules\users\models\User;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchTasks */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $projectHours  */
/* @var $projectTypeHours  */
/* @var $projectLaborHours  */
/* @var $projectMonthHours  */

$this->title = Yii::t('app', 'project');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="projectdescription-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_search', ['model' => $searchModel]); ?>


    <?php $user = User::current(); ?>


	<?php Pjax::begin(); ?>

	<?= ExportMenu::widget([
		'dataProvider' => $dataProvider,
		'columns' => [

			[
				'attribute' => 'user_id',
				'format' => 'raw',
				'value' => function ($model) {
					$user = User::getDb()->cache(function () use ($model) {
						return User::findOne($model->user_id);
					}, 30);

					return $user->name;
				},
			],
			[
				'attribute' => 'project_id',
				'value' => function ($model) {
					$project = Project::getDb()->cache(function () use($model) {
						return Project::findOne($model->project_id);
					}, 30);
					if(!$project)
						return 'Általános';

					return $project->name;
				},
			],
			[
				'attribute' => 'workplace_id',
				'value' => function ($model) {
					$place = Workplace::getDb()->cache(function () use ($model) {
						return Workplace::findOne($model->workplace_id);
					}, 30);

					if(!$place)
						return Yii::t('app', 'n/a');

					return $place->title;
				},
			],
			[
				'attribute' => 'worktype_id',
				'value' => function ($model) {
					$type = Worktype::getDb()->cache(function () use ($model) {
						return Worktype::findOne($model->worktype_id);
					}, 30);

					if(!$type)
						return Yii::t('app', 'n/a');

					return $type->title;
				},
			],
			[
				'label' => 'Munkanap',
				'value' => function ($model) {
					return Apps::getDate($model->working_datetime_start);
				}
			],
			[
				'label' => 'Kezdete',
				'value' => function($model) {
					return Apps::getDate($model->working_datetime_start, 'H:i');
				}
			],
			[
				'label' => 'Vége',
				'value' => function($model) {
					return Apps::getDate($model->working_datetime_end, 'H:i');
				}
			],
			[
				'label' => Yii::t('app', 'working_hours'),
				'value' => function($model) {
					return $model->workingHours;
				}
			],

			[
				'attribute' => 'text',
				'value' => function($model) {
					return strip_tags($model->text);
				}
			],
		],
		'selectedColumns' => [0, 1, 3, 4, 7, 8],
	]) ?>

    <?= GridView::widget([
		'panel' => [
			'heading'=>'Összesen <b>'.$projectHours.'</b> munkaóra',
			'footer' => ''
		],
		'toolbar'=>['{toggleData}'],
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [

			[
				'class' => 'kartik\grid\ExpandRowColumn',
				'expandIcon' => '<i style="color:#337ab7" class="fas fa-plus-square"></i>',
				'collapseIcon' => '<i style="color:#337ab7" class="fas fa-minus-square"></i>',
				'expandOneOnly' => true,
				'value' => function ($model, $key, $index, $column) {
					return GridView::ROW_COLLAPSED;
				},
				'detail' => function ($model, $key, $index, $column){
					return Yii::$app->controller->renderpartial('@app/modules/tasks/views/tasks/_tasks_other', [
						'model' => $model,
					]);
				}
			],
			[
				'attribute' => 'user_id',
				'format' => 'raw',
				'filter' => User::allUserLeaderNames(true),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => '',],
					'pluginOptions' => [
						'allowClear' => true,
						'width' => '100%',
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
					],
				],
				'value' => function ($model) {
					$user = User::getDb()->cache(function () use ($model) {
						return User::findOne($model->user_id);
					}, 30);

					return Html::a($user->name, Url::toRoute(['/users/users/view', 'id' => $user->id]), ['target' => '_blank']);
				},
			],
			[
				'attribute' => 'project_id',
				'format' => 'raw',
				'filter' => Project::allProjectNames(true),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => ''],
					'pluginOptions' => [
						'allowClear' => true,
						'width' => '100%',
					],
				],
				'value' => function ($model) {
					$project = Project::getDb()->cache(function () use($model) {
						return Project::findOne($model->project_id);
					}, 30);
					if(!$project)
					    return 'Általános';

					return Html::a($project->name, Url::toRoute(['/project/project/view', 'id' => $project->id]), ['target' => '_blank']);
				},
			],
			[
				'attribute' => 'workplace_id',
				'format' => 'raw',
				'filter' => Workplace::allWorkplaceNames(true),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => ''],
					'pluginOptions' => [
						'allowClear' => true,
						'width' => '100%',
					],
				],
				'value' => function ($model) {
					$place = Workplace::getDb()->cache(function () use ($model) {
						return Workplace::findOne($model->workplace_id);
					}, 30);

					if(!$place)
						return Yii::t('app', 'n/a');

					return Html::a($place->title, Url::toRoute(['/tasks/workplace/view', 'id' => $place->id]), ['target' => '_blank']);
				},
			],
			[
				'attribute' => 'worktype_id',
				'format' => 'raw',
				'filter' => Worktype::allWorkTypeNames(true),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => ''],
					'pluginOptions' => [
						'allowClear' => true,
						'width' => '100%',
					],
				],
				'value' => function ($model) {
					$type = Worktype::getDb()->cache(function () use ($model) {
						return Worktype::findOne($model->worktype_id);
					}, 30);

					if(!$type)
						return Yii::t('app', 'n/a');

					return Html::a($type->title, Url::toRoute(['/tasks/worktype/view', 'id' => $type->id]), ['target' => '_blank']);
				},
			],
			[
				'attribute' => 'working_datetime_start',
				'label' => Yii::t('app', 'working_time'),
                'filterType' => GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions' => ([
					'presetDropdown' => true,
					'convertFormat' => false,
					'pluginOptions' => [
						'width' => '200px',
						'separator' => ' - ',
						'format' => 'YYYY-MM-DD',
						'locale' => [
							'format' => 'YYYY-MM-DD'
						],

					],
				]),
				'format' => 'raw',
				'value' => function($model) {
					return Apps::getDate($model->working_datetime_start, 'Y-m-d').'<br />'.Apps::getDate($model->working_datetime_start, 'H:i').' - '.Apps::getDate($model->working_datetime_end, 'H:i');
				}
			],
			[
				'label' => Yii::t('app', 'working_hours'),
				'value' => function($model) {
					return $model->workingHours;
				}
			],
			[
				'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'recommended_hours',
				'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
				'pageSummary' => true,
				'readonly' => false,
				'value' => function($model) use($user) {
					$leader = ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $user->id, 'leader' => true]);

					if($this->context->userCanOne(['view_finance', 'is_recognized']) || ($leader && $leader->leader && $this->context->userCan('leader_recognized'))) {
						return $model->recommended_hours;
					} else {
						return '-';
					}
				},
				'editableOptions' => function ($model, $key, $index) {
					return [
						'inputType' => kartik\editable\Editable::INPUT_TEXT,
						'formOptions' => [
							'action' => ['/tasks/tasks/change-hours'],
						],
						'options' => [
							'pluginOptions' => [

							]
						]
					];
				},
			],
			[
				'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'comment',
				'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
				'format' => 'raw',
				'pageSummary' => true,
				'readonly' => false,
				'value' => function($model) use($user) {
					$leader = ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $user->id, 'leader' => true]);

					if($model->comment && ($this->context->userCanOne(['view_finance', 'is_recognized']) || ($leader && $leader->leader && $this->context->userCan('leader_recognized')))) {
						return '<span data-toggle="tooltip" data-html="true" data-title="'.$model->comment.'">'.substr($model->comment, 0, 10).((strlen($model->comment)>10)?'...':'').'</span>';
					}
				},
				'editableOptions' => function ($model, $key, $index) {
					return [
						'inputType' => kartik\editable\Editable::INPUT_TEXTAREA,
						'formOptions' => [
							'action' => ['/tasks/tasks/change-comment'],
						],

						'size' => 'md',
						'options' => [
							'rows' => 5,
							'pluginOptions' => [

							],
						],
					];
				},
			],
			[
				'attribute' => 'recognized',
				'format' => 'raw',
				'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
				'filter' => [
					1=>Yii::t('app','yes'),
					0=>Yii::t('app','no')
				],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => ''],
					'pluginOptions' => [
						'allowClear' => true,
						'width'=>'100px',
					],
				],
				'value' => function($model) use($user) {
					$leader = ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $user->id]);
					if($this->context->userCanOne(['view_finance', 'is_recognized']) || ($leader && $leader->leader && $this->context->userCan('leader_recognized'))) {
						if($model->recognized) {
							return '
                            <span id="recognized_icon_'.$model->id.'" ondblclick="recognized('.$model->id.')" style="color:green">
                                <i class="fas fa-check"></i>
                            </span>';
						} else {
							return '
                            <span id="recognized_icon_'.$model->id.'" ondblclick="recognized('.$model->id.')" style="color:red">
                                <i class="fas fa-times"></i>
                            </span>';
						}
					} else { // Ha nincs joga recognizedezni
						if($model->recognized) {
							return '
                            <span style="color:green">
                                <i class="fas fa-check"></i>
                            </span>';
						} else {
							return '
                            <span style="color:red">
                                <i class="fas fa-times"></i>
                            </span>';
						}
					}

				}
			],

			[
				'attribute' => 'planned',
				'format' => 'raw',
				'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
				'filter' => [
					1=>Yii::t('app','yes'),
					0=>Yii::t('app','no')
				],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
					'options' => ['prompt' => ''],
					'pluginOptions' => [
						'allowClear' => true,
						'width'=>'100px',
					],
				],
				'value' => function($model) use($user) {
					$leader = ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $user->id]);
					if($this->context->userCanOne(['view_finance', 'is_recognized']) || ($leader && $leader->leader && $this->context->userCan('leader_recognized'))) {
						if($model->planned) {
							return '
                            <span id="planned_icon_'.$model->id.'" ondblclick="planned('.$model->id.')" style="color:green">
                                <i class="fas fa-check"></i>
                            </span>';
						} else {
							return '
                            <span id="planned_icon_'.$model->id.'" ondblclick="planned('.$model->id.')" style="color:red">
                                <i class="fas fa-times"></i>
                            </span>';
						}
					} else { // Ha nincs joga recognizedezni
						if($model->planned) {
							return '
                            <span style="color:green">
                                <i class="fas fa-check"></i>
                            </span>';
						} else {
							return '
                            <span style="color:red">
                                <i class="fas fa-times"></i>
                            </span>';
						}
					}

				}
			],


        [
	        'class' => 'kartik\grid\EditableColumn',
	        'attribute' => 'verified',
	        'format' => 'raw',
	        'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
	        'filter' => [
		        2=>Yii::t('app','accepted'),
		        1=>Yii::t('app','rejected'),
		        0=>Yii::t('app', 'not_set')
	        ],
	        'filterType' => GridView::FILTER_SELECT2,
	        'filterWidgetOptions' => [
		        'options' => ['prompt' => ''],
		        'pluginOptions' => [
			        'allowClear' => true,
			        'width'=>'100px',
		        ],
	        ],
	        'editableOptions' => function ($model, $key, $index, $widget) {

		        return [

			        'inputType' => kartik\editable\Editable::INPUT_DROPDOWN_LIST,
			        'asPopover' => true,
			        'formOptions' => [
				        'action' => ['/tasks/tasks/change-verified'],
			        ],
			        'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
			        'size' => 'md',
			        'data' => [0 => Yii::t('app', 'not_set'),1 => Yii::t('app', 'rejected'),2 => Yii::t('app', 'accepted')],
			        'options' => [
				        'pluginOptions' => [

				        ],
			        ],

			        'displayValueConfig' => [
				        0 => Yii::t('app', 'not_set'),
				        1 => Yii::t('app', 'rejected'),
				        2 => Yii::t('app', 'accepted')
			        ],
		        ];
	        },
        ],



		[

			'attribute' => 'verified',
			'format' => 'raw',
			'visible' => !$this->context->is_admin,
			'filter' => [
				2=>Yii::t('app','accepted'),
				1=>Yii::t('app','rejected'),
                0=>Yii::t('app', 'not_set')
			],
			'filterType' => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
				'options' => ['prompt' => ''],
				'pluginOptions' => [
					'allowClear' => true,
					'width'=>'100px',
				],
			],

            'value' => function ($model) {
	            if(!$this->context->is_admin){
                    if ($model->verified === 2) {
                        return '
                                <span data-toggle="tooltip" data-html="true" title="'.$model->verified_comment.'" style="color:green">
                                    <i class="fas fa-check"></i>
                                </span>';
                    } else if ($model->verified === 1) {
                        return '
                                <span data-toggle="tooltip" data-html="true" title="'.$model->verified_comment.'" style="color:red">
                                    <i class="fas fa-times"></i>
                                </span>';
                    } else
	                    return '
                                <span data-toggle="tooltip" data-html="true" title="Elbírálásra vár..." style="color:darkslategray">
                                    <i class="fas fa-question"></i>
                                </span>';
	            }
            },


		],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'verified_comment',
            'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
            'format' => 'raw',
            'pageSummary' => true,
            'readonly' => false,
            'value' => function($model) use($user) {
                $leader = ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => $user->id, 'leader' => true]);

                if($model->comment && ($this->context->userCanOne(['view_finance', 'is_recognized']) || ($leader && $leader->leader && $this->context->userCan('leader_recognized')))) {
                    return '<span data-toggle="tooltip" data-html="true" data-title="'.$model->verified_comment.'">'.substr($model->verified_comment, 0, 10).((strlen($model->verified_comment)>10)?'...':'').'</span>';
                }
            },


            'editableOptions' => function ($model, $key, $index) {
                return [
                    'inputType' => kartik\editable\Editable::INPUT_TEXTAREA,
                    'formOptions' => [
                        'action' => ['/tasks/tasks/change-verified-comment'],
                    ],
	                'placement' => \kartik\popover\PopoverX::ALIGN_LEFT,
                    'size' => 'md',
                    'options' => [
                        'rows' => 3,
                        'pluginOptions' => [

                        ],
                    ],
                ];
            },
        ],

    ],


]); ?>

    <div class="row">
        <div class="col-sm-12">
            <h3>Össz órák hónapokra bontva</h3>
        </div>
        <div class="col-sm-12">

            <canvas id="curveDraw" width="400" height="100"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <h3>Munkatárs szerinti elosztás:</h3>
        </div>
        <div class="col-sm-6">
            <h3>Típus szerinti elosztás:</h3>
        </div>
        <div class="col-sm-6">

            <canvas id="pieDraw" width="400" height="200"></canvas>
        </div>
        <div class="col-sm-6">

            <canvas id="pieSecondDraw" width="400" height="200"></canvas>
        </div>

    </div>



    <?php

    $userLabel = array_keys($projectLaborHours);
    foreach ($userLabel as $key => $userID) {
	    $userLabel[$key] = User::findOne($userID)->name;
    }

	$workTypeLabel = array_keys($projectTypeHours);
	foreach ($workTypeLabel as $key => $typeID) {
		$workTypeLabel[$key] = Worktype::findOne($typeID)->title??"n/a";
	}

    $this->registerJs("
    var ctx = document.getElementById('curveDraw').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['".implode("','",array_keys($projectMonthHours))."'],
            datasets: [{
                label: 'Össz óra: ',
                data: [".implode(",",array_values($projectMonthHours))."],
                backgroundColor: 'rgba(20,96,130, 1)',
                borderWidth: 0
            }],
        },
        options: {
            legend: {
                display: false,
                position: 'bottom',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });");

	$this->registerJs("
    var ctx = document.getElementById('pieSecondDraw').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['".implode("','",$workTypeLabel)."'],
            datasets: [{
                label: 'Össz óra: ',
                data: [".implode(",",array_values($projectTypeHours))."],
                backgroundColor:['#ac725e', '#d06b64', '#f83a22', '#fa573c', '#ff7537', '#ffad46', '#42d692', '#16a765', '#7bd148', '#b3dc6c', '#fbe983', '#fad165', '#92e1c0', '#9fe1e7', '#9fc6e7', '#4986e7', '#9a9cff', '#c2c2c2', '#cabdbf', '#cca6ac', '#f691b2', '#cd74e6', '#a47ae2'],
                borderWidth: 2
            }],
        },
        options: {
            legend: {
                display: true,
                position: 'bottom',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });");

    $this->registerJs("
    var ctx = document.getElementById('pieDraw').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['".implode("','",$userLabel)."'],
            datasets: [{
                label: 'Össz óra: ',
                data: [".implode(",",array_values($projectLaborHours))."],
                backgroundColor:['#a47ae2','#cd74e6','#f691b2','#cca6ac','#cabdbf','#c2c2c2','#9a9cff','#4986e7','#9fc6e7','#9fe1e7','#92e1c0','#fad165','#fbe983','#b3dc6c','#7bd148','#16a765','#42d692','#ffad46','#ff7537','#fa573c','#f83a22','#d06b64','#ac725e'],
                borderWidth: 2
            }],
        },
        options: {
            legend: {
                display: true,
                position: 'bottom',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });");
	?>

	<?php Pjax::end(); ?></div>

<script>
    function recognized(taskId) {
        console.log(taskId);

        $.get({
            url: `/tasks/tasks/recognized`,
            data: {
                taskId: taskId
            },
            success: function(success) {
                console.log(success);
                if (success) {
                    $(`#recognized_icon_${taskId}`).replaceWith(
                        `<span id="recognized_icon_${taskId}" ondblclick="recognized(${taskId})" style="color:green">\n` +
                        `<i class="fas fa-check"></i>\n` +
                        `</span>`
                    );
                } else if(!success) {
                    $(`#recognized_icon_${taskId}`).replaceWith(
                        `<span id="recognized_icon_${taskId}" ondblclick="recognized(${taskId})" style="color:red">\n` +
                        `<i class="fas fa-times"></i>\n` +
                        `</span>`
                    );
                }
            }
        });
    }

    function planned(taskId) {
        console.log(taskId);

        $.get({
            url: `/tasks/tasks/planned`,
            data: {
                taskId: taskId
            },
            success: function(success) {
                console.log(success);
                if (success) {
                    $(`#planned_icon_${taskId}`).replaceWith(
                        `<span id="planned_icon_${taskId}" ondblclick="planned(${taskId})" style="color:green">\n` +
                        `<i class="fas fa-check"></i>\n` +
                        `</span>`
                    );
                } else if(!success) {
                    $(`#planned_icon_${taskId}`).replaceWith(
                        `<span id="planned_icon_${taskId}" ondblclick="planned(${taskId})" style="color:red">\n` +
                        `<i class="fas fa-times"></i>\n` +
                        `</span>`
                    );
                }
            }
        });
    }
</script>
