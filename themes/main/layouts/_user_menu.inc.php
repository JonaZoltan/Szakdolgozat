<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2019
 * 2019.10.13., 16:41:46
 * The used disentanglement, and any part of the code
 * user_menu.inc.php own by the author, Bencsik Matyas.
 */


use app\modules\users\models\User;
use yii\helpers\Url;
use lajax\languagepicker\widgets\LanguagePicker;


$user=User::current();
?>

<span class="user">

            <?php if($user->hasPhoto()):?>
                <img class='user_picture'src='/uploads/users/<?= $user->id.".jpg"?>'>
            <?php else:?>
                <span class="icon"><i class="fas fa-user-alt"></i></span>
            <?php endif ?>
            <span class="text"><?= $context->userData['name'] ?> <?= $context->userData['is_admin'] ? '<span class="is-admin">admin</span>' : '' ?></span>
            <div class="user-menu">
                <div class="lang-menu">
                    <span><?= Yii::t('app', 'language') ?></span>
                    <?= LanguagePicker::widget([
	                    'skin' => LanguagePicker::SKIN_BUTTON,
	                    'size' => LanguagePicker::SIZE_SMALL
                    ]); ?>
                </div>

                <a href="/users/users/settings" class="user-menu-item">
                    <span class="icon">
                        <i class="fas fa-cog"></i>
                    </span><span class="text"><?= Yii::t('app', 'settings') ?></span>
                </a>

                <a href="/users/users/quick-menu" class="user-menu-item">
                    <span class="icon">
                        <i class="fas fa-bars"></i>
                    </span><span class="text"><?= Yii::t('app', 'quickmenu') ?></span>
                </a>

                <a href="<?= Url::to(["/errors/errors/create"]) ?>" class="user-menu-item">
                    <span class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                    <span class="text"><?= Yii::t('app', 'error_report') ?></span>
                </a>

                <a href="/users/users/logout-page" class="user-menu-item">
                    <span class="icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    <span class="text"><?= Yii::t('app', 'exit') ?></span>
                </a>
            </div
</span>

