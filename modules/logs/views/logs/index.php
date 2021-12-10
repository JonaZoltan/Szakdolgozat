<?php

use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\Html;

use app\modules\logs\models\LogEvent;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\logs\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'logs');
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .daterangepicker td.in-range:not(.end-date) {
        background-color: #ddd;
    }
</style>

<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
	    'panel' => [ 'footer'=>'' ],
	    'toolbar'=> [ '{export}', '' ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'event_name',
                'filter' => LogEvent::allNames(true),
                'value' => function ($model) {
                    return $model->logEvent->name;
                }
            ],
            [
                'attribute' => 'log_text',
                'value' => function ($model) {
                    $string = $model->toString();
                    $model->cached_text = $string;
                    $model->save(false);
                    return $string;
                },
            ],
            [
                'attribute' => 'created_at_range',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_range',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'Y-m-d'
                        ],
                    ],

                ]),
                'value' => function ($model) {
                    return $model->created_at;
                }
            ],

	        ['class' => 'kartik\grid\ActionColumn', 'template' => '{delete}',
		        'visibleButtons'=>[
			        'delete'=> function() {
				        return $this->context->is_admin;
			        },
		        ]
	        ],
        ],
    ]); ?>
</div>
