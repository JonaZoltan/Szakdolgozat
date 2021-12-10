<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\partners\models\Partners;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\Partners */
/* @var $modelContact app\modules\partners\models\Contact */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.Partners::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.Partners::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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
                  'id',
                  'name',
	               [
		                'attribute' => 'user_ids',
		                'value' => function ($model){
			                return $model->responsiblesNames;
		                }
	                ],
	                [
		                'attribute' => 'alert_day',
		                'value' => function ($model){
			                return $model->alert_day.' nap';
		                }
	                ],
	                [
		                'attribute' => 'contactEvent',
		                'format' => 'raw',
		                'value' => function ($model){
			                return $model->lastContactEventDate;
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

	<h3><?= Yii::t('app', 'contacts') ?></h3>
    <?php if($model->contact): ?>
    <div class="row">
        <?php foreach($modelContact as $index => $contact):?>
            <div class="col-sm-4">
                <div class="card card-outline card-gray">
                    <div class="card-header text-bold">
                        <?= $contact->name ?>
                    </div>
                    <div class="card-body">
                        <p><b><?= $contact->getAttributeLabel('email') ?></b>: <a href="mailto:<?= $contact->email ?>"><?= $contact->email ?></a></p>
                        <p><b><?= $contact->getAttributeLabel('tel') ?></b>: <a href="tel:<?= $contact->tel ?>"><?= $contact->tel ?></a></p>
                        <p><b><?= $contact->getAttributeLabel('position') ?></b>: <?= $contact->position ?></p>
                    </div>
                    <div class="card-footer">
                        <?= $contact->note ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else : ?>
        Nem található kapcsolat az adatbázisban
    <?php endif; ?>
</div>
