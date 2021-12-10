<?php

use app\modules\partners\models\Partners;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\project\models\Project;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.Project::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.Project::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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
	                'name',
	                [
		                'attribute' => 'area_id',
		                'format' => 'raw',
		                'value' => function($model) {
			                $area = $model->area;
			                if(!$area)
				                return Yii::t('app', 'n/a');

			                if($this->context->userCan('view_worktype'))
				                return Html::a($area->title, Url::to(['/project/area/view', 'id' => $area->id]), ['target' => '_blank']);
			                else
				                return $area->title;
		                }
	                ],
	                [
		                'attribute' => 'color',
		                'format' => 'raw',
		                'value' => function($model) {
			                return '<span class="badge" style="padding: 5px; background-color: '. $model->color. '; color: '. $model->fontColor .'">'.$model->color.'</span>';
		                }
	                ],
	                [
		                'attribute' => 'partner_ids',
		                'filter' => Partners::allPartnerName(true),
		                'filterType' => GridView::FILTER_SELECT2,
		                'filterWidgetOptions' => [
			                'options' => ['prompt' => ''],
			                'pluginOptions' => [
				                'allowClear' => true
			                ],
		                ],
		                'value' => function ($model){
			                return $model->partnerNames;
		                }
	                ],
                ]
            ]) ?>

        </div>
    </div>

    <div class="card card-outline card-secondary">
        <div class="card-body">

			<?= $model->text ?>

        </div>
    </div>

</div>
