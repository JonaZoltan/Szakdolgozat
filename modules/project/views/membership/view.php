<?php

use app\modules\project\models\Project;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\project\models\ProjectMembership;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\ProjectMembership */

$this->title = User::findOne($model->user_id)->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'project_membership'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-membership-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.ProjectMembership::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.ProjectMembership::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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
				                return Yii::t('app', 'n/a');

			                return Html::a($project->name, Url::toRoute(['/project/project/view', 'id' => $project->id]), ['target' => '_blank']);
		                },
	                ],
	                [
		                'attribute' => 'financing',
		                'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
		                'value' => function($model) {
			                $leader = ProjectMembership::getDb()->cache(function() use ($model) {
				                return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => User::current()->id, 'leader' => true]);
			                }, 30);

			                if(($leader && $this->context->userCan('leader_recognized')) || $this->context->userCanOne(['view_finance', 'is_recognized'])) {
				                return $model->financing;
			                } else {
				                return '-';
			                }
		                }
	                ],
                    'member_since',
                ],
            ]) ?>

        </div>
    </div>

</div>
