<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\errors\models\ErrorReporting */

$this->title = Yii::t('app', 'thanks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'error_report'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-reporting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'report_recorded') ?> (#<?= $id ?>)
    </p>


</div>
