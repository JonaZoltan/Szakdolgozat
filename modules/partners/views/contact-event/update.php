<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\ContactEvent */
/* @var $task app\modules\tasks\models\Tasks */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'contact_event'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
?>
<div class="contact-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'task' => $task
    ]) ?>

</div>
