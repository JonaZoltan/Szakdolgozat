<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\modules\users\models\Capability;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\CapabilitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'capabilitys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capability-index">

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
	        [
	            'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    if(in_array($model->name, Capability::$disabledCapability))
                        return $model->name." <span class='label label-danger'>D</span>";
                    else
                        return $model->name;
                }
            ],
	        [
	            'attribute' => 'description',
                'format' => 'raw',
                'value' => function ($model) {
		            $capa = explode(":", $model->description);
		            return (count($capa) > 1 ? $capa[0].": ".Yii::t('app', str_replace(' ', '', $capa[1])) : $capa[0]);
                }
            ],
            [
                'attribute' => 'module',
                'filter' => Capability::$module_names,
                'value' => function ($model) {
                    return $model->moduleName();
                }
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
        ],
    ]); ?>
</div>
