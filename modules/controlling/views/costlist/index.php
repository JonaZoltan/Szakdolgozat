<?php

use app\modules\apps\models\Apps;
use app\modules\project\models\Area;
use app\modules\project\models\Project;

use app\modules\project\models\ProjectMembership;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use yii\helpers\Html;
use app\modules\users\models\User;

use kartik\grid\GridView;;

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchTasks */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $projectHours  */
/* @var $projectCost  */
/* @var $projectRecommendedHours  */

$this->title = "Költségelemzés";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectdescription-index">

	<h1><?= Html::encode($this->title) ?></h1>

    <?php $user = User::current(); ?>

	<?php Pjax::begin(['id' => 'costId']); ?>    <?= GridView::widget([
		'panel' => [
			'heading'=>'
                <span class="CostSum">
                    <span>Összes költség: <b>'.$projectCost.'</b> </span><br />
                    <span>Összes kiajánlott munkaóra: <b>'.$projectRecommendedHours.'</b></span><br />
                    <span> Összes munkaóra: <b>'.$projectHours.' </b> </span>
                </span>',
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

							],
						],
						'pluginEvents' => [
							//"editableSuccess"=>"function(event, val, form, data) { $.pjax.reload({container: '#costId'}); }",
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

		],
	]); ?>

	<?php Pjax::end(); ?>
</div>

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





