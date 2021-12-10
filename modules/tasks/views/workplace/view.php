<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\users\models\User;
use app\modules\tasks\models\Workplace;

/* @var $this yii\web\View */
/* @var $model app\modules\tasks\models\Workplace */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'workplace'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplace-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $this->context->userCan('update_'.Workplace::tableName().'')?Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):"" ?>
        <?= $this->context->userCan('delete_'.Workplace::tableName().'')?Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
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
                  'title',
                ],
            ]) ?>

        </div>
    </div>

</div>
