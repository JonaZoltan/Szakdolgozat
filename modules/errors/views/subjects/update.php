<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\errors\models\ErrorReportingSubject */

$this->title = Yii::t('app', 'update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'error_reporting_subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-reporting-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
