<?php

use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\formschemes\models\FormSchemes;
use kartik\grid\GridView;;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\formschemes\models\SearchFormSchemes */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'form_schemes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-schemes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.FormSchemes::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success disabled']):"" ?>
    </p>
    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            'text:ntext',

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} ',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.FormSchemes::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.FormSchemes::tableName().'');
                    },
//                    'delete' => function () {
//                        return $this->context->userCan('delete_'.FormSchemes::tableName().'');
//                  },
                ]
            ],
        ],
    ]); ?>
</div>
