<?php /** @noinspection MissedFieldInspection */

use app\modules\partners\models\Contact;
use app\modules\partners\models\Partners;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\partners\models\ContactEvent;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\partners\models\SearchContactEvent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'contact_event');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.ContactEvent::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
