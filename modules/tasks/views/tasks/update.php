<?php

use app\modules\partners\models\ContactEvent;
use app\modules\users\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Tasks */
/* @var $modelContactEvent ContactEvent */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => User::findOne($model->user_id)->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
?>
<div class="tasks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'modelContactEvent' => $modelContactEvent
    ]) ?>

</div>
