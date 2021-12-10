<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\LoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'sessions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \kartik\grid\GridView::widget([ 'panel' => [ 'footer'=>'' ],
        
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         
            [
                'attribute' => 'user_search',
                'format'=>'raw',
                'label' => Yii::t('app', 'user'),
                'value' => function ($model) {
                    return '<a href="'.Url::to(['/users/users/view','id'=>$model->user->id]).'">'.$model->user->name.'</a>';
                },
            ],
            
            'start_date',
            'end_date',

            
            'ip_address',
            'user_agent',
            


            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>
</div>
