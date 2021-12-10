<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\SettingsForm */
/* @var $form ActiveForm */
?>
<div class="settings">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

        <?= $form->field($model, 'smtp_name') ?>
        <?= $form->field($model, 'smtp_address') ?>
        <?= $form->field($model, 'smtp_host') ?>
        <?= $form->field($model, 'smtp_port') ?>
        <?= $form->field($model, 'smtp_username') ?>
        <?= $form->field($model, 'smtp_password') ?>
        <?= $form->field($model, 'smtp_security') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- settings -->
