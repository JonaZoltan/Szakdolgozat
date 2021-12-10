<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\formschemes\models\FormSchemes */

$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'form_schemes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-schemes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
