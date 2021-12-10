<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logs\models\LogEvent */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
