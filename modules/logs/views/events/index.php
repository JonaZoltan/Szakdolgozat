<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\logs\models\LogEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-event-index">

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
            'description',

            ['class' => 'yii\grid\ActionColumn', 'template' => "{update} {delete}"],
        ],
    ]); ?>
</div>
