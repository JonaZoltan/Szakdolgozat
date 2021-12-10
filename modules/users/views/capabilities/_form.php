<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\users\models\Capability;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Capability */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capability-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, "disabled" => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'module')->dropdownList(Capability::$module_names) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
