<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\partners\models\ContactEvent;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\ContactEvent */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'contact_event'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.ContactEvent::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.ContactEvent::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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
		                'value' => function ($model){
			                return $model->userName;
		                }
	                ],
	                [
		                'attribute' => 'partner_id',
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->partnerName;
		                }
	                ],
	                [
		                'attribute' => 'contact_id',
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->contactName;
		                }
	                ],
	                [
		                'attribute' => 'type',
		                'value' => function ($model){
			                return $model->typeName;
		                }
	                ],
	                [
		                'attribute' => 'when',
		                'format' => 'raw',
		                'value' => function ($model){
			                if ($model->task_id)
				                return $model->when.$model->taskLinkByTaskId;
			                return $model->when;
		                }
	                ],
                ],
            ]) ?>
        </div>
        <div class="text-bold ml-4">
            <?=(Yii::t('app', 'comment'))?>
        </div>
        <div class="card-footer">
		    <?= $model->note ?>
        </div>
    </div>

</div>
