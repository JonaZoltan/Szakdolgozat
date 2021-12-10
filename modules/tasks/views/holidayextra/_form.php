<?php

use app\modules\users\models\User;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\HolidayExtra */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="holiday-extra-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-3">
	                <?= $form->field($model, 'user_id')->widget(Select2::class, [
		                'data' => User::allUserNames(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
		                ],
	                ]);
	                ?>
                </div>

                <div class="col-sm-6">
	                <?= $form->field($model, 'disabled_user')->widget(Select2::class, [
		                'data' => User::allUserNames(true),
		                'language' => 'hu',
		                'options' => ['placeholder' => Yii::t('app', 'switch')],
		                'pluginOptions' => [
			                'allowClear' => true,
                            'multiple' => true,
		                ],
	                ]);
	                ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <?php $dates = [];
                        for($i = 2020; $i <= date('Y')+1; $i++) {
                            $dates[$i] = $i;
                        }
                    ?>

                    <?= $form->field($model, 'year')->widget(Select2::class, [
	                    'data' => $dates,
	                    'language' => 'hu',
	                    'options' => [
		                    'value' => $model->year??date('Y'),
	                        'placeholder' => Yii::t('app', 'switch')
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-sm-3">
		            <?= $form->field($model, 'holiday_day')->textInput() ?>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
