<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use kartik\select2\Select2;

use app\modules\users\models\PermissionSet;
use app\modules\users\models\Capability;

use app\modules\users\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'permissions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= \kartik\grid\GridView::widget([ 'panel' => [ 'footer'=>'' ],
        
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'permission_set_search',
                'format'=>'raw',
                'value' => function ($model) {
                    return '<a href="'.Url::to(['/users/permissionsets/update','id'=>$model->permissionSet->id]).'">'.$model->permissionSet->name.'</a>';
                },
            ],
            [
                'attribute' => 'capability_id',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'capability_id',
                    'data' => Capability::allCapabilityNames(true),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app', 'switch')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ]),
                'format'=>'raw',
                'value' => function ($model) {
                    return '<a href="'.Url::to(['/users/capabilities/update','id'=>$model->capability->id]).'">' . $model->capability->description . '</a>';
                }
            ],

            ['class' => 'yii\grid\ActionColumn', "template" => "{delete}"],
        ],
    ]); ?>
</div>
