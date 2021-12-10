<?php

use app\modules\partners\models\ContactEvent;
use app\modules\project\models\Project;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\tasks\models\Tasks;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Tasks */

$this->title = User::findOne($model->user_id)->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.Tasks::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.Tasks::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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

	                [
		                'attribute' => 'user_id',
		                'format' => 'raw',
		                'value' => function ($model) {
			                $user = User::findOne($model->user_id);
			                return Html::a($user->name, Url::toRoute(['/users/users/view', 'id' => $user->id]), ['target' => '_blank']);
		                },
	                ],
	                [
		                'attribute' => 'project_id',
		                'format' => 'raw',
		                'value' => function ($model) {
			                $project = Project::findOne($model->project_id);
			                if(!$project)
				                return 'Általános';

			                return Html::a($project->name, Url::toRoute(['/project/project/view', 'id' => $project->id]), ['target' => '_blank']);
		                },
	                ],
	                [
		                'attribute' => 'workplace_id',
		                'format' => 'raw',
		                'value' => function ($model) {
			                $place = Workplace::findOne($model->workplace_id);
			                if(!$place)
				                return Yii::t('app', 'n/a');

			                return Html::a($place->title, Url::toRoute(['/tasks/workplace/view', 'id' => $place->id]), ['target' => '_blank']);
		                },
	                ],
	                [
		                'attribute' => 'worktype_id',
		                'format' => 'raw',
		                'value' => function ($model) {
			                $type = Worktype::findOne($model->worktype_id);
			                if(!$type)
				                return Yii::t('app', 'n/a');

			                return Html::a($type->title, Url::toRoute(['/tasks/worktype/view', 'id' => $type->id]),
                                    ['target' => '_blank']).' '.ContactEvent::getContactEventLinkByTaskId($model->id);
		                },
	                ],
	                'working_datetime_start',
	                'working_datetime_end',
                    [
                        'attribute' => 'verified',
                        'visible' => $this->context->userCan('view_finance'),
                        'value' => function ($model) {
                            if($model->verified === 2)
                                return Yii::t('app', 'accepted');
                            else if($model->verified === 1)
                                return Yii::t('app', 'rejected');
                            else
                                return Yii::t('app', 'not_set');

                        },

                    ],
	                [
		                'attribute' => 'recommended_hours',
		                'visible' => $this->context->userCan('view_finance'),
	                ],
	                [
		                'attribute' => 'recognized',
		                'visible' => $this->context->userCan('view_finance'),
                        'value' => function ($model) {
                            $recognized = Tasks::findOne($model->recognized);
                                if($recognized)
                                    return Yii::t('app', 'yes');
                                else
	                                return Yii::t('app', 'no');
                        }
	                ],
	                [
		                'attribute' => 'planned',
		                'visible' => $this->context->userCan('view_finance'),
                        'value' => function($model) {
                            $planned = Tasks::findOne($model->planned);
                                if($planned)
	                                return Yii::t('app', 'yes');
                                else
	                                return Yii::t('app', 'no');
                        }
	                ],

                ],
            ]) ?>

        </div>
    </div>

    <?= Yii::$app->view->render('@app/modules/tasks/views/tasks/_tasks_other', [
        'model' => $model,
    ]); ?>

</div>
