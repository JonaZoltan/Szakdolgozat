<?php /** @noinspection MissedFieldInspection */

use app\modules\apps\models\Apps;
use app\modules\partners\models\ContactEvent;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use kartik\bs4dropdown\ButtonDropdown;
use kartik\editable\Editable;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\tasks\models\Tasks;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchTasks */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'tasks');
$this->params['breadcrumbs'][] = $this->title;

$user = User::current();
?>
<div class="tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Tasks::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>

        <?php $hasTask = Tasks::find()->where(['user_id' => $user->id, 'date(working_datetime_start)' => date('Y-m-d')])->one() ?>
        <?php if(!$hasTask): ?>
            <?= Html::a(Yii::t('app', 'create_day_tasks'), ['create-day'], ['class' => 'btn btn-primary']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app', 'today_tasks'), Url::to(['/tasks/tasks/update-day', 'id' => 0]), ['class' => 'btn btn-secondary']) ?>
        <?php endif; ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
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
				        'width' => '105px',
			        ],
		        ],
		        'value' => function ($model) {
			        $type = Worktype::getDb()->cache(function () use ($model) {
				        return Worktype::findOne($model->worktype_id);
			        }, 30);

			        if(!$type)
			            return Yii::t('app', 'n/a');

			        return Html::a($type->title, Url::toRoute(['/tasks/worktype/view', 'id' => $type->id]),
                            ['target' => '_blank']).' '.ContactEvent::getContactEventLinkByTaskId($model->id);
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
	                $date1 = new DateTime($model->working_datetime_start);
	                $date2 = new DateTime($model->working_datetime_end);
	                $diff = $date2->diff($date1);

	                return round(($diff -> i + $diff->h*60 + $diff->d*24) / 60, 2);
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

                            ],
                        ],
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
                            <span id="recognized_icon_'.$model->id.'" style="color:green">
                                <i class="fas fa-check"></i>
                            </span>';
				        } else {
					        return '
                            <span id="recognized_icon_'.$model->id.'" style="color:red">
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
		        'attribute' => 'verified',
		        'format' => 'raw',
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
                'value' => function($model) use($user) {

                    if ($model->verified === 2) {
                        return '
                                <span style="color:green">
                                    <i class="fas fa-check"></i>
                                </span>';
                    } else if ($model->verified === 1) {
                        return '
                                <span style="color:red">
                                    <i class="fas fa-times"></i>
                                </span>';
                    } else
                        return '<span data-toggle="tooltip" data-html="true" title="Elbírálásra vár..." style="color:darkslategray">
                                    <i class="fas fa-question"></i>
                                </span>';
	            }
	        ],

            [
                'attribute' => 'text',
                'hidden' => true,
                'value' => function($model) {
                    return strip_tags($model->text);
                }
            ],

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{day} {view} {update} {delete}',
	            'buttons' => [
		            'day' => function ($url, $model, $key) {
			            return Html::a('<span aria-hidden="true" title="'.Yii::t('app', 'update_day_tasks').'"><i class="fas fa-layer-group"></i></span>', Url::to(['/tasks/tasks/update-day', 'id' => $model->id]), ['class' => 'grid-btn']);
		            },
	            ],
	            'visibleButtons'=>[
		            'day' => function($model) {
			            return $this->context->userData['id'] == $model->user_id;
		            },
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
</script>