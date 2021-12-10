<?php

use app\modules\users\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\HolidayExtra */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'holiday_extra'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => User::findOne($model->user_id)->name." ".$model->year." ".Yii::t('app', 'year'), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
?>
<div class="holiday-extra-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
