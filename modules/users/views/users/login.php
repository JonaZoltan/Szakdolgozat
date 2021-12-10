<?php

use lajax\languagepicker\widgets\LanguagePicker;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'login');
?>

<div class="login-box">
    <div class="login-logo">
        <img src="/img/logo.png" alt="Szita'r" />
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">

            <div class="lang-menu">
		        <?= LanguagePicker::widget([
			        'skin' => LanguagePicker::SKIN_BUTTON,
			        'size' => LanguagePicker::SIZE_SMALL
		        ]); ?>
            </div>

            <div style="text-align: center;">
                <h3><?= Yii::t('app', 'login') ?></h3>
            </div>

	        <?php
            $form = ActiveForm::begin([
		        'id' => 'login-form',
		        'options' => [
			        "class" => 'form-signin',
		        ],
		        'fieldConfig' => [
		            //'template' => "{input}{error}", // Ez miatt nincs error
			        'options' => [
				        'tag' => false, // Ez miatt nincs error
			        ],
		        ],
	        ]) ?>
                <div class="input-group mb-3">
                    <!--<input type="email" class="form-control" placeholder="Email">-->
	                <?= $form->field($model, 'email', [
	                    'template' => '{input}
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
	                    {error}',
                    ])->textInput(["placeholder" => Yii::t('app', 'e-mail'), "class" => "form-control"])->label(false) ?>
                </div>
                <div class="input-group mb-3">
                    <!--<input type="password" class="form-control" placeholder="Password">-->
	                <?=$form->field($model, 'password', [
		                'template' => '{input}
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
	                    {error}',
	                ])->passwordInput(["placeholder" => Yii::t('app', 'password'), "class" => "form-control"])->label(false)?>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
	                        <?=Html::activeCheckbox($model, 'remember', ["label"=>false]) ?> <?= Yii::t('app', 'signed-in'); ?>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block btn-preload"><?= Yii::t('app', 'login'); ?></button>
                    </div>
                </div>
	        <?php ActiveForm::end(); ?>

            <br />

            <div class="row" style="text-align: center;">
                <div class="col-12">
                    <a href="/users/users/forgot-password" class="text-primary"><i class="fas fa-question-circle"></i> <?= Yii::t('app', 'forgot-password'); ?></a>
                </div>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
    <div style="text-align: center; margin-top: 10px;">
		<?= Yii::t('app', 'welcome'); ?>
    </div>
</div>
<!-- /.login-box -->