<?php

use app\modules\apps\models\Apps;
use app\modules\project\models\Area;
use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Tasks;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\project\models\Project;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\Project */
/* @var $searchModel app\modules\tasks\models\SearchTasks */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $projectHours  */
/* @var $projectTypeHours  */
/* @var $projectLaborHours  */
/* @var $projectMonthHours  */


Pjax::begin();
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= $this->context->userCan('create_'.Tasks::tableName().'')?Html::a(Yii::t('app', 'create'), ['/tasks/tasks/create'], ['class' => 'btn btn-success']):"" ?>

		<?= $this->context->is_admin?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
		<?= $this->context->is_admin?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => Yii::t('app', 'delete_confirm'),
				'method' => 'post',
			],
		]):"" ?>

    </p>

    <div class="card card-outline card-primary">
        <div class="card-body">

	        <?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					//'id',
					'name',
					[
						'attribute' => 'area_id',
						'format' => 'raw',
						'value' => function ($model) {
							$area_id = Area::findOne($model->area_id);
							return Html::a($area_id->title, Url::toRoute(['/tasks/worktype/view', 'id' => $area_id->id]), ['target' => '#popup-window']);
						},
					],
					'text:ntext',
					'timestamp',
				],
			]) ?>


        </div>
    </div>

    <?php $user = User::current(); ?>

	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'panel' => [
			'heading'=>'Összesen <b>'.$projectHours.'</b> munkaóra',
			'footer' => ''
		],
		'toolbar'=>['{export}', '{toggleData}'],
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
			/*[
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

					return Html::a($project->name, Url::toRoute(['/project/project/view', 'id' => $project->id]), ['target' => '_blank']);
				},
			],*/
			[
				'attribute' => 'workplace_id',
				'visible' => false,
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
			['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
				'urlCreator' => function($action,$model,$key,$index){
					if($action=='view'){
						return Url::to(['/tasks/tasks/view','id'=>$model->id]);
					}
					if($action=='update'){
						return Url::to(['/tasks/tasks/update','id'=>$model->id]);
					}
					if($action=='delete'){
						return Url::to(['/tasks/tasks/delete','id'=>$model->id]);
					}

					return Url::to(['/tasks/tasks/view','id'=>$model->id]);
				},
				'visibleButtons'=>[
					'view'=> function() {
						return $this->context->userCan('view_'.Tasks::tableName().'');
					},
					'update' => function ($model) use ($user) {
						$memberLeader = ProjectMembership::getDb()->cache(function () use ($model, $user) {
							return ProjectMembership::findOne(['user_id' => $user->id, 'project_id' => $model->project_id, 'leader' => true]);
						}, 30);

						return $this->context->userCan('update_'.Tasks::tableName().'') || $this->context->userData['id'] == $model->user_id || $memberLeader;
					},
					'delete' => function ($model) use ($user) {
						$memberLeader = ProjectMembership::getDb()->cache(function () use ($model, $user) {
							return ProjectMembership::findOne(['user_id' => $user->id, 'project_id' => $model->project_id, 'leader' => true]);
						}, 30);

						return $this->context->userCan('delete_'.Tasks::tableName().'') || $this->context->userData['id'] == $model->user_id || $memberLeader;
					},
				]

			],

		],
	]); ?>



	<?php
	$projectWorkTypes = Tasks::find()->where(['project_id' => $model->id])->all();

	$workTypeArray = [];
	foreach ($projectWorkTypes as $data) {
		$time = Project::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

		if(isset($workTypeArray[ $data->worktype_id ])) {
			$workTypeArray[ $data->worktype_id ] += $time;
		} else {
			$workTypeArray[ $data->worktype_id ] = $time;
		}
	}
	/** Munka típusa array */
	//var_dump($workTypeArray);

	$projectUserTime = Tasks::find()->where(['project_id' => $model->id])->all();

	$userTimeArray = [];
	foreach ($projectUserTime as $data) {
		$time = Project::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

		if(isset($userTimeArray[ $data->user_id ])) {
			$userTimeArray[ $data->user_id ] += $time;
		} else {
			$userTimeArray[ $data->user_id ] = $time;
		}
	}
	/** Munkatárs szerinti array */
	//var_dump($userTimeArray);die();

	$projectMonthTime = Tasks::find()->where(['project_id' => $model->id])->all();

	$monthTimeArray = [];
	foreach ($projectMonthTime as $data) {
		$time = Project::setProjectHours($data->working_datetime_start, $data->working_datetime_end);

		$month = Yii::t('app', Apps::getDate($data->working_datetime_start, "F"));
		if(isset($monthTimeArray[ $month ])) {
			$monthTimeArray[ $month ] += $time;
		} else {
			$monthTimeArray[ $month ] = $time;
		}
	}
	/** Hónapokra bontva array */
	//var_dump($monthTimeArray);

	?>

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
	//var_dump($projectTypeHours);

	$userLabel = array_keys($projectLaborHours);
	foreach ($userLabel as $key => $userID) {
		$userLabel[$key] = User::findOne($userID)->name;
	}

	$workTypeLabel = array_keys($projectTypeHours);
	foreach ($workTypeLabel as $key => $typeID) {
		$workTypeLabel[$key] = Worktype::findOne($typeID)->title;
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

	<?php Pjax::end(); ?>


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
    </script>