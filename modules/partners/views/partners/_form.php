<?php

use app\modules\partners\models\Contact;
use app\modules\users\models\User;
use kartik\select2\Select2;
use kidzen\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\Partners */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelContact Contact */
?>

<div class="partners-form">

    <?php $form = ActiveForm::begin([
        'encodeErrorSummary' => false,
    ]); ?>

	<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'user_ids')->widget(Select2::class, [
                        'data' => User::allUserNames(true),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app', 'switch')],
                        'pluginOptions' => [
                            'multiple' => true,
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 mb-3">
	                    <?= $form->field($model, 'alert_day', [
	                            'options' => ['class' => 'm-0']
                        ])->textInput()?>
                    <small>(nap)</small>
                </div>
            </div>

           <?= $form->field($model, 'note')->textarea(['data-summernote' => true, 'rows' => 6])  ?>


        </div>
    </div>


	<?php DynamicFormWidget::begin([
		'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
		'widgetBody' => '.container-items', // required: css class selector
		'widgetItem' => '.item', // required: css class
		'limit' => 100, // the maximum times, an element can be cloned (default 999)
		'min' => 0, // 0 or 1 (default 1)
		'insertButton' => '.add-item', // css class
		'deleteButton' => '.remove-item', // css class
		'model' => $modelContact[0],
		'formId' => 'w0',
		'formFields' => [

			'name',
			'email',
			'tel',
			'position',
			'note'
		],
	]); ?>
    <div class="card card-outline card-secondary">
        <div class="card-header">
			<?= Yii::t('app', 'contacts') ?>
            <button type="button" class="float-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Új hozzáadása</button>
            <div class="clearfix"></div>
        </div>
        <div class="card-body container-items">
            <?php foreach($modelContact as $index => $contact): ?>
                <div class="card item card-outline card-gray">
                    <div class="card-header">
                        <button type="button" class="float-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>

                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
	                            <?= $form->field($contact, "[$index]name")->textInput() ?>
                            </div>
                            <div class="col-sm-8">
	                            <?= $form->field($contact, "[$index]position")->textInput() ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
			                    <?= $form->field($contact, "[$index]email")->textInput() ?>
                            </div>
                            <div class="col-sm-6">
			                    <?= $form->field($contact, "[$index]tel")->textInput() ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
			                    <?= $form->field($contact, "[$index]note")->textarea() ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

	<?php DynamicFormWidget::end(); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') : Yii::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
