<?php

use app\modules\apps\models\Apps;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\groups\models\Group;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\VersionHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-history-form">

    <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'data-summernote' => true]) ?>

	<?= $form->field($model, 'release_date')->widget(DatePicker::className(), [
		'name' => 'working_date',
		'options' => ['placeholder' => Yii::t('app', 'date_switch')],
		'readonly' => true,
		'pluginOptions' => [
			'format' => 'yyyy-mm-dd',
			'startDate' => Apps::START_DATE,
			'todayHighlight' => true,
		],
	]);
	?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
