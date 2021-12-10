<?php

use app\modules\plants\models\Plants;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\users\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'delete_confirm'),
                'method' => 'post',
            ],
        ]) ?>
        
        <?= Html::a('<i class="fas fa-envelope"></i> '.Yii::t('app', 'password_send'), 'javascript:void(0)', [
            'class' => 'btn btn-default',
            'style' => 'float: right',
            'data-send-password-reset' => true,
            'data-user-id' => $model->id,
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'name',
            'email:email',
            'rfid',
	        [
		        'attribute' => 'permission_set_id',
		        'format' => 'raw',
		        'value' => function ($model) {
			        $set = $model->permissionSet;
			        if ($set) {
				        return '<a href="'.Url::to(['/users/permissionsets/update','id'=>$set->id]).'">'.$set->toHtml().'</a>';
			        }
			        return '<span class="badge">'.Yii::t('app', 'not_defined').'</span>';
		        },
	        ],
            [
                'attribute' => 'is_admin',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->is_admin ? '<span class="badge">'.Yii::t('app', 'yes').'</span>' : '<span class="badge">'.Yii::t('app', 'no').'</span>';
                }
            ],
            [
                'attribute' => 'suspended',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->suspended ? '<span class="badge">'.Yii::t('app', 'yes').'</span>' : '<span class="badge">'.Yii::t('app', 'no').'</span>';
                }
            ],
            [
                'attribute' =>'created_by',
                'format'=>'raw',
                'value' => function ($model) {
                    if($model->creatorUser && $model->creatorUser->id <= 2) {
	                    return "Szitár-Net Kft., ". $model->created_at;
                    }
                    else if ($model->creatorUser) {
                        return '<a href="'.Url::to(['/users/users/view','id'=>$model->creatorUser->id]).'">'.$model->creatorUser->name . "</a>, " . $model->created_at;
                    }
                    return $model->created_at;
                }
            ]
        ],
    ]) ?>

</div>
