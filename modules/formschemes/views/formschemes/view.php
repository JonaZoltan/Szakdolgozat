<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\formschemes\models\FormSchemes;

/* @var $this yii\web\View */
/* @var $model app\modules\formschemes\models\FormSchemes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'form_schemes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-schemes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.FormSchemes::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.FormSchemes::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'delete_confirm'),
                'method' => 'post',
            ],
        ]):"" ?>
    </p>

    <div class="card card-outline card-primary">
        <div class="card-body">


            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                  'id',
                  'name',
                  'text:ntext',
                ],
            ]) ?>

        </div>
    </div>

</div>
