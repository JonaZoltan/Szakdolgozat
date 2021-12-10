<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2019
 * 2019.10.13., 16:39:46
 * The used disentanglement, and any part of the code
 * quick_menu.php own by the author, Bencsik Matyas.
 */

use app\modules\apps\models\Apps;
use yii\helpers\Url;

?>

<div class="quick-menu">
    <div class="quick-menu-inner">
        <?php if($user): ?>
            <?php if($user->getQuickMenu() != null): ?>
                <?php foreach ($items = Apps::getAllQuickMenu() as $key => $item): ?>
                        <?php if( (isset($item['capability']) ? $this->context->userCan($item['capability']) : $this->context->userCan("view_".$key)) && in_array($key, $user->getQuickMenu())): ?>
                        <a href="<?= Url::to([ (strpos($item['url'], "/") === false) ? "/".$item['url']."/default/index" : "/".$item['url'] ])?>" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('app', $item['translate']) ?>">
                            <i class="fas <?= $item['fa-icon'] ?>"></i>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

	    <?php if($this->context->is_admin): ?>
            <b>|</b>

            <a href="/clearassets.php" data-toggle="tooltip" data-placement="bottom" title="Ideiglenes filok törlése!">
                <i class="fas fa-bug"></i>
            </a>
            <a href="/apps/migrate/up" data-toggle="tooltip" data-placement="bottom" title="Migrate UP">
                <i class="fas fa-database"></i>
            </a>

	    <?php endif;  ?>

    </div>

</div>
