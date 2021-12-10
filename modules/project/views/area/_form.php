<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\project\models\Area */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="area-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">

           <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
