<?php

use app\modules\partners\models\Contact;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\Partners */
/* @var $modelContact Contact */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'update');
?>
<div class="partners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelContact' => $modelContact
    ]) ?>

</div>
