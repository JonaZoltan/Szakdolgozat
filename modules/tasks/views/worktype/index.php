<?php

use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\tasks\models\Worktype;
use kartik\grid\GridView;;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchWorktype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'worktype');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worktype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Worktype::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'title',

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.Worktype::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.Worktype::tableName().'');
                    },
                    'delete' => function () {
                        return $this->context->userCan('delete_'.Worktype::tableName().'');
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
