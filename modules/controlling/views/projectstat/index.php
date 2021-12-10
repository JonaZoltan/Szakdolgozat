<?php

use app\modules\project\models\Area;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\project\models\Project;
use kartik\grid\GridView;;

use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\project\models\SearchProject */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Projektek beállítások');
$this->title = "Project statisztikák";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Project::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
	        [
		        'class' => 'kartik\grid\ExpandRowColumn',
		        'expandIcon' => '<i style="color:#337ab7" class="fas fa-plus-square"></i>',
		        'collapseIcon' => '<i style="color:#337ab7" class="fas fa-minus-square"></i>',
		        'expandOneOnly' => true,
		        'value' => function ($model, $key, $index, $column) {
			        return GridView::ROW_COLLAPSED;
		        },
		        'detail' => function ($model, $key, $index, $column){
			        return Yii::$app->controller->renderpartial('@app/modules/project/views/project/_project_other', [
				        'model' => $model,
			        ]);
		        }
	        ],
            //'id',
            [
		            'attribute' => 'name',
		            'format' => 'raw',
		            'value' => function ($model) {
			            $project_id = Project::findOne($model->id);
			            return Html::a($project_id->name, Url::toRoute(['/controlling/project-stat/view', 'id' => $project_id->id]), ['target' => '_blank']);
		            },
            ],
	        [
		        'attribute' => 'area_id',
		        'format' => 'raw',
		        'value' => function ($model) {
			        $area_id = Area::findOne($model->area_id);
			        $project_id = Project::findOne($model->id);
			        return Html::a($area_id->title, Url::toRoute(['/controlling/project-stat/view', 'id' => $project_id->id]), ['target' => '_blank']);
		        },
	        ],
	        [
		        'attribute' => 'timestamp',
		        'format' => 'raw',
		        'value' => function ($model) {
			        $project_id = Project::findOne($model->id);
			        return Html::a($project_id->timestamp, Url::toRoute(['/controlling/project-stat/view', 'id' => $project_id->id]), ['target' => '_blank']);
		        },
	        ],
            /*
            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.Project::tableName().'');
                    },
                    'update' => function () {
                        return $this->context->userCan('update_'.Project::tableName().'');
                    },
                    'delete' => function () {
                        return $this->context->userCan('delete_'.Project::tableName().'');
                    },
                ]
            ],*/
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
