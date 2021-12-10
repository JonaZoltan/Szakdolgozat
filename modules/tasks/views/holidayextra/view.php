<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\tasks\models\HolidayExtra;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\HolidayExtra */

$this->title = User::findOne($model->user_id)->name." ".$model->year." ".Yii::t('app', 'year');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'holiday_extra'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-extra-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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

	                [
		                'attribute' => 'user_id',
		                'format' => 'raw',
		                'value' => function ($model) {
			                $user = User::findOne($model->user_id);
			                return Html::a($user->name, Url::toRoute(['/users/users/view', 'id' => $user->id]), ['target' => '_blank']);
		                },
	                ],
	                'year',
	                [
		                'attribute' => 'disabled_user',
		                'format' => 'raw',
		                'value' => function($model) {
			                $disabledUsers = Json::decode($model->disabled_user);
			                if(!$disabledUsers)
				                return "-";

			                foreach ($disabledUsers as $key => $disabledUser) {
				                $disabledUsers[$key] = User::findOne($disabledUser)->name;
			                }

			                return implode("<br />", array_values($disabledUsers));
		                }
	                ],
	                'holiday_day',
	                [
		                'label' => 'Egyéb',
		                'format' => 'raw',
		                'value' => function($model) {
			                $text = "<b>Fennmaradó napok:</b> {$model->freeDay}<br />";
			                $text .= "<b>Kivett napok:</b> {$model->acceptedDay}<br />";
			                $text .= "<b>Elutasított napok:</b> {$model->declinedDay}<br />";
			                $text .= "<b>Elbírálás alatt:</b> {$model->reviewDay}<br />";

			                return $text;
		                }
	                ],

                ],
            ]) ?>

        </div>
    </div>

</div>
