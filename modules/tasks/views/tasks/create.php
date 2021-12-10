<?php

use app\modules\apps\models\Apps;
use app\modules\project\models\Project;
use app\modules\tasks\models\Tasks;
use app\modules\tasks\models\Workplace;
use app\modules\tasks\models\Worktype;
use app\modules\users\models\User;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\partners\models\ContactEvent;
/* @var $modelContactEventAll ContactEvent|array*/
/* @var $modelContactEvent ContactEvent*/
/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Tasks */
/* @var $modelAll app\modules\tasks\models\Tasks */

$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(isset($day) && $day): ?>
	    <?= $this->render('_form_day', [
		    'model' => $model,
            'modelAll' => $modelAll,
		    'modelContactEventAll' => $modelContactEventAll,
	    ]) ?>
    <?php else: ?>
        <?= $this->render('_form', [
            'model' => $model,
		    'modelContactEvent' => $modelContactEvent,
            'task' => $task??null
        ]) ?>
    <?php endif; ?>

    <?php
        $dataProvider = new ActiveDataProvider([
            'query' => $lastRecords = Tasks::find()->where(['user_id' => User::current()->id])->orderBy('working_datetime_start DESC')->limit(10),
        ]);
    ?>

    <div class="card card-outline card-secondary">
        <div class="card-body">
	        <?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'layout' => '{items}',
		        'options' => [ 'style' => 'width:100%;' ],
		        'columns' => [
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
			        [
				        'attribute' => 'working_datetime_start',
				        'value' => function($model) {
					        return Apps::getDate($model->working_datetime_start, 'm. d. H:i');
				        }
			        ],
			        [
				        'attribute' => 'working_datetime_end',
				        'value' => function($model) {
					        return Apps::getDate($model->working_datetime_end, 'm. d. H:i');
				        }
			        ],

			        ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
				        'visibleButtons'=>[
					        'view'=> function() {
						        return $this->context->userCan('view_'.Tasks::tableName().'');
					        },
					        'update' => function ($model) {
						        return $this->context->userCan('update_'.Tasks::tableName().'') || $this->context->userData['id'] == $model->user_id;
					        },
					        'delete' => function ($model) {
						        return $this->context->userCan('delete_'.Tasks::tableName().'') || $this->context->userData['id'] == $model->user_id;
					        },
				        ]
			        ],
		        ],
	        ]); ?>
        </div>
    </div>

</div>
