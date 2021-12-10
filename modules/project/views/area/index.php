<?php

use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\project\models\Area;
use kartik\grid\GridView;;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\project\models\SearchArea */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'area');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Area::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
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
                        return $this->context->userCan('view_'.Area::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.Area::tableName().'');
                    },
                    'delete' => function () {
                        return $this->context->userCan('delete_'.Area::tableName().'');
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
