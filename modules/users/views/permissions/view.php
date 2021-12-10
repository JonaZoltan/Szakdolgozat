<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Permission */

$this->title = $model->permission_set_id;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'permission_set_id' => $model->permission_set_id, 'capability_id' => $model->capability_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'permission_set_id' => $model->permission_set_id, 'capability_id' => $model->capability_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'permission_set_id',
            'capability_id',
            'created_at',
        ],
    ]) ?>

</div>
