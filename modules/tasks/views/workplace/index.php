<?php /** @noinspection MissedFieldInspection */

use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\tasks\models\Workplace;
use kartik\grid\GridView;;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchWorkplace */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'workplace');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workplace-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Workplace::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'title',
            [
                'attribute' => 'default',
                'format' => 'raw',
	            'filter' => [
		            1=>Yii::t('app','yes'),
		            0=>Yii::t('app','no')
	            ],
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
		            'options' => ['prompt' => ''],
		            'pluginOptions' => [
			            'allowClear' => true,
		            ],
	            ],
	            'value' => function($model) {
		            if($model->default) {
			            return '<span style="color: green;"<i class="fas fa-check"></i></span>';
		            } else {
			            return '<span style="color: red;"><i class="fas fa-times"></i></span>';
		            }
	            }
            ],

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.Workplace::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.Workplace::tableName().'');
                    },
                    'delete' => function () {
                        return $this->context->userCan('delete_'.Workplace::tableName().'');
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
