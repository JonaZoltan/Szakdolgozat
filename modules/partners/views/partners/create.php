<?php

use app\modules\partners\models\Contact;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\partners\models\Partners */
/* @var $modelContact Contact */

$this->title = Yii::t('app', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'modelContact' => $modelContact
    ]) ?>

</div>
