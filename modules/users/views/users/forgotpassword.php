<?php
use app\modules\users\models\User;

use lajax\languagepicker\widgets\LanguagePicker;
use yii\captcha\Captcha;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="login-box">
    <div class="login-logo">
        <img src="/img/logo.png" alt="Szita'r" />
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <div class="back">
                <a href="/users/users/login" class="text-primary"><i class="fas fa-arrow-left"></i> <?= Yii::t('app', 'back') ?></a>
            </div>

            <?php if (Yii::$app->session->hasFlash('sent')): ?>
                <form class="form-signin" style="padding: 30px">
                    <h4 class="form-signin-heading text-success" style="margin: 0"><i class="fas fa-check-circle"></i> Jelszóbeállító e-mail elküldve</h4>
                </form>

            <?php else: ?>
                <?php
	            $form = ActiveForm::begin([
		            'id' => 'resetpassword-form',
		            'options' => [
			            "class" => 'form-signin',
		            ],
		            'fieldConfig' => [
			            'options' => [
				            //   'tag' => false,
			            ],
		            ],
	            ]) ?>
                <div class="lang-menu">
                    <?= LanguagePicker::widget([
                        'skin' => LanguagePicker::SKIN_BUTTON,
                        'size' => LanguagePicker::SIZE_SMALL
                    ]); ?>
                </div>
                <h2 class="form-signin-heading"><?= Yii::t('app', 'forgot_password') ?></h2>

                <p><?= Yii::t('app', 'forgot_password_text') ?></p>

                <?=$form->field($model, 'email')->textInput(["placeholder" => Yii::t('app', 'email')])->label(false)?>

                <br />

                <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
                'captchaAction' => '/users/users/captcha',
                'options' => ['class'=>'form-control','placeholder' => Yii::t('app', 'captcha')],
                'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-2">{image}</div></div>',
            ])->label(false) ?>

                <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fas fa-envelope"></i> <?= Yii::t('app', 'send') ?></button>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

