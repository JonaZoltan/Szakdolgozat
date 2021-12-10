<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\SearchTasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-search">

    <div class="card card-outline card-primary">
        <div class="card-header text-bold">
            Keresés
        </div>
        <div class="card-body">
	        <?php $form = ActiveForm::begin([
		        'action' => ['sum-list'],
		        'method' => 'get',
	        ]); ?>

	        <?= $form->field($model, 'text') ?>

            <div class="form-group">
		        <?= Html::submitButton('Keresés', ['class' => 'btn btn-primary']) ?>
            </div>

	        <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
