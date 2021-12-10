<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use app\modules\errors\models\ErrorReportingSubject;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\errors\models\ErrorReportingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'error_report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-reporting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <?= \kartik\grid\GridView::widget([ 'panel' => [ 'footer'=>'' ],
        
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'subject',
                'filter' => ErrorReportingSubject::allSubjects(true),
                'value' => function ($model) {
                    return $model->subjectModel->name;
                },
            ],
            [
                'label' => 'Üzenetek',
                'value' => function($model) {
                    return $model->numberOfMessages;
                }
            ],
            [
                'attribute' => 'user_search',
                'format'=>'raw',
                'value' => function ($model) {
                    $user = $model->user;
                    if (!$user) {
                        return null;
                    }
                    return '<a href="'.Url::to(['/users/users/view','id'=>$user->id]).'">'.$user->name.'</a>';
                }
            ],
            'created_at',


            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
