<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'system_settings');
?>

<div class="settings-default-index">
    <h1><?= Yii::t('app', 'system_settings') ?></h1>
    <div class="settings">

        <?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

          <div class="panel panel-default">
           <div class="panel-heading"><i class="fas fa-envelope-open-text"></i> <?= Yii::t('app', 'email_settings') ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'smtp_name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'smtp_address')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <?= $form->field($model, 'smtp_host')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-1">
                        <?= $form->field($model, 'smtp_port')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'smtp_security')->dropdownList([
                            'none' => Yii::t('app', 'no_have'),
                            'ssl' => 'SSL',
                            'tls' => 'TLS'
                        ]) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'smtp_username')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'smtp_password')->passwordInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
          </div>
        
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>

    </div><!-- settings -->

</div>
