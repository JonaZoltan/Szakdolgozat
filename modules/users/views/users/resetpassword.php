<?php
use app\modules\users\models\User;

use lajax\languagepicker\widgets\LanguagePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="login-box">
        <div class="logo">
            <img src="/img/logo.png" alt="Szitár" />
        </div>

        <div class="card">
            <div class="card-body login-card-body">

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <form class="form-signin" style="padding: 30px">
                    <h4 class="form-signin-heading text-success" style="margin: 0"><i class="fas fa-check-circle"></i> Sikeresen beállította a jelszavát!</h4>
                    <div style="text-align: center; margin-top: 25px; font-size: 18px">
                        <a href="/"><?= Yii::t('app', 'login') ?></a>
                    </div>
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
                    <h2 class="form-signin-heading"><?= Yii::t('app', 'reset_password') ?></h2>

                    <?=$form->field($model, 'token')->hiddenInput(["value"=>$token])->label(false)?>

                    <?=$form->field($model, 'password')->passwordInput(["placeholder" => Yii::t('app', 'password')])->label(false)?>
                    <br />
                    <?=$form->field($model, 'password_repeat')->passwordInput(["placeholder" => Yii::t('app', 'password_again')])->label(false)?>

                    <?php if (isset($_GET['gdpr']) && $_GET['gdpr']): ?>
                    <div class="form-group">
                        <label>
                            <small>
                            <input type="checkbox" required />
                                <?= Yii::t('app', 'terms_service') ?>
                            </small>
                        </label>
                    </div>
                    <?php endif; ?>

                    <br />

                    <button class="btn btn-lg btn-primary btn-preload btn-block" type="submit"><?= Yii::t('app', 'save') ?></button>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

