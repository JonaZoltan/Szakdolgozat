<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2019
 * 2019.10.13., 16:45:46
 * The used disentanglement, and any part of the code
 * footer.inc.php own by the author, Bencsik Matyas.
 */

use app\modules\apps\models\Apps;
use \app\modules\settings\models\VersionHistory;

$version = VersionHistory::currentVersion();
?>

<div id="version">
	<?= Yii::t('app', 'szitar_system') ?>
	<?php

	?>
	<?php if ($version['number'] !== '0.0') : ?>
        <a id="current-version" href="javascript:void(0)"><b>v<?= $version['number'] ?></b></a>
	<?php else: ?>
        <b><?= $version['number'] ?></b>
	<?php endif; ?>


    <small>/ <?= Yii::t('app', 'activated') ?>: <b><?= Apps::getDate($version['activated'], "Y. m. d.") ?></small></b>

</div>
