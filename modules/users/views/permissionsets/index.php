<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\PermissionSetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\select2\Select2;

use app\modules\users\models\PermissionSet;
use app\modules\users\models\Capability;

use app\modules\users\models\User;

$this->title = Yii::t('app', 'permission_sets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-set-index">

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

            'name',

            [
                'attribute' => 'capability_search',
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'capability_search',
                    'data' => Capability::allCapabilityNames(true),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app', 'switch')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                    ],
                ]),
                'value' => function ($model) {
                    $capabilities = $model->capabilities;
                    $html = '';

                    foreach ($capabilities as $cap) {
	                    $capa = explode(":", $cap->description);
                        $html .= '<span class="badge">' . (count($capa) > 1 ? $capa[0].": ".Yii::t('app', str_replace(' ', '', $capa[1])) : $capa[0]) . '</span> ';
                    }
                    return $html ?: '('.Yii::t('app', 'not_set').')';
                },
            ],

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
