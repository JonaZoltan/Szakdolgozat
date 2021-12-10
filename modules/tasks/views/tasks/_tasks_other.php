<?php

/** @var Tasks $model */

use app\modules\tasks\models\Tasks;

?>

<div class="card card-outline card-secondary">
    <div class="card-header">
	    <b><?= Yii::t('app', 'task_text') ?></b>
    </div>
    <div class="card-body">
        <?= $model->text ?>
    </div>
</div>

<?php if($model->comment): ?>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <b><?= Yii::t('app', 'comment') ?></b>
        </div>
        <div class="card-body">
            <?= $model->comment ?>
        </div>
    </div>
<?php endif; ?>