<?php /** @noinspection MissedFieldInspection */

use kartik\grid\GridView;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\partners\models\Partners;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\partners\models\SearchPartners */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'partners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Partners::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
	        [
		        'attribute' => 'user_ids',
		        'filter' => User::allUserNames(true),
		        'filterType' => GridView::FILTER_SELECT2,
		        'filterWidgetOptions' => [
			        'options' => ['prompt' => ''],
			        'pluginOptions' => [
				        'allowClear' => true
			        ],
		        ],
		        'value' => function ($model){
			        return $model->responsiblesNames;
		        }
	        ],
	        [
		        'attribute' => 'alert_day',
		        'value' => function ($model){
			        return $model->alert_day.' nap';
		        }
	        ],
            [
	                'attribute' => 'contactEvent',
                    'format' => 'raw',
                    'value' => function ($model){
                        return $model->lastContactEventDate;
                    }
            ],
            [
                    'label' => Yii::t('app', 'contactNum'),
                    'value' => function ($model){
                        return $model->contactNum;
                    }
            ],
            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.Partners::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.Partners::tableName().'');
                    },
                    'delete' => function () {
                        return $this->context->userCan('delete_'.Partners::tableName().'');
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
