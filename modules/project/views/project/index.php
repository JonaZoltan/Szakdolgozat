<?php /** @noinspection MissedFieldInspection */

use app\modules\partners\models\Partners;
use app\modules\project\models\Area;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\modules\project\models\Project;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\project\models\SearchProject */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'project');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.Project::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>

    <?= GridView::widget([
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
            'name',
	        [
		        'attribute' => 'area_id',
		        'format' => 'raw',
		        'filter' => Area::allAreaNames(true),
		        'filterType' => GridView::FILTER_SELECT2,
		        'filterWidgetOptions' => [
			        'options' => ['prompt' => ''],
			        'pluginOptions' => ['allowClear' => true],
		        ],
		        'value' => function($model) {
			        $area = $model->area;
			        if(!$area)
				        return Yii::t('app', 'n/a');

			        if($this->context->userCan('view_worktype'))
			            return Html::a($area->title, Url::to(['/project/area/view', 'id' => $area->id]), ['target' => '_blank']);
			        else
			            return $area->title;
		        }
	        ],
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value' => function($model) {
                    return '<span class="badge" style="padding: 5px; background-color: '. $model->color. '; color: '. $model->fontColor .'">'.$model->color.'</span>';
                }
            ],
	        [
		        'attribute' => 'partner_ids',
		        'filter' => Partners::allPartnerName(true),
		        'filterType' => GridView::FILTER_SELECT2,
		        'filterWidgetOptions' => [
			        'options' => ['prompt' => ''],
			        'pluginOptions' => [
				        'allowClear' => true
			        ],
		        ],
		        'value' => function ($model){
			        return $model->partnerNames;
		        }
	        ],
            //'text:ntext',
            // 'timestamp',

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
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?></div>
