<?php

use app\modules\partners\models\Contact;
use app\modules\partners\models\ContactEvent;
use app\modules\partners\models\Partners;
use app\modules\users\models\User;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\ContactEvent */

$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'contact_event'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

	<?php
	$dataProvider = new ActiveDataProvider([
		'query' => $lastRecords = ContactEvent::find()->where(['user_id' => User::current()->id])->orderBy('when DESC')->limit(10),
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
		                'filter' => User::allUserNames(true),
		                'filterType' => GridView::FILTER_SELECT2,
		                'filterWidgetOptions' => [
			                'options' => ['prompt' => ''],
			                'pluginOptions' => [
				                'allowClear' => true
			                ],
		                ],
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->userName;
		                }
	                ],
	                [
		                'attribute' => 'partner_id',
		                'filter' => Partners::allPartnerName(true),
		                'filterType' => GridView::FILTER_SELECT2,
		                'filterWidgetOptions' => [
			                'options' => ['prompt' => ''],
			                'pluginOptions' => [
				                'allowClear' => true
			                ],
		                ],
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->partnerName;
		                }
	                ],
	                [
		                'attribute' => 'contact_id',
		                'filter' => Contact::allContactName(true),
		                'filterType' => GridView::FILTER_SELECT2,
		                'filterWidgetOptions' => [
			                'options' => ['prompt' => ''],
			                'pluginOptions' => [
				                'allowClear' => true
			                ],
		                ],
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->contactName;
		                }
	                ],
	                [
		                'attribute' => 'type',
		                'filter' => ContactEvent::allTypeNames(true),
		                'filterType' => GridView::FILTER_SELECT2,
		                'filterWidgetOptions' => [
			                'options' => ['prompt' => ''],
			                'pluginOptions' => [
				                'allowClear' => true
			                ],
		                ],
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
	                // 'note:ntext',

	                ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
		                'visibleButtons'=>[
			                'view'=> function() {
				                return $this->context->userCan('view_'.ContactEvent::tableName().'');
			                },
			                'update' => function () {
				                return $this->context->userCan('update_'.ContactEvent::tableName().'');
			                },
			                'delete' => function () {
				                return $this->context->userCan('delete_'.ContactEvent::tableName().'');
			                },
		                ]
	                ],
                ],
        ]); ?>
        </div>
    </div>
</div>
