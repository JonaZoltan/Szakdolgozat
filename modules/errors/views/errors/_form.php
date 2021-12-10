<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\errors\models\ErrorReportingSubject;

/* @var $this yii\web\View */
/* @var $model app\modules\errors\models\ErrorReporting */
/* @var $form yii\widgets\ActiveForm */

if (isset($_GET['code']) && $_GET['code']) {
    $model->message = Yii::t('app', 'id').": " . $_GET['code'];
}

?>

<div class="error-reporting-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>
    
    <?= $form->field($model, 'subject')->dropdownList(ErrorReportingSubject::allSubjects(true)) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
