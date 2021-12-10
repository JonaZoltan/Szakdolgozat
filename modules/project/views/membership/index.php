<?php /** @noinspection MissedFieldInspection */

use app\modules\project\models\Project;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\project\models\ProjectMembership;
use kartik\grid\GridView;;

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\project\models\SearchProjectMembership */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'project_membership');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-membership-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.ProjectMembership::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'panel' => [ 'footer'=>'' ],
        'toolbar'=>['{export}', '{toggleData}'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

	        [

		        'attribute' => 'user_id',
		        'format' => 'raw',
		        'filter' => User::allUserLeaderNames(true),
		        'filterType' => GridView::FILTER_SELECT2,
		        'filterWidgetOptions' => [
			        'options' => ['prompt' => ''],
			        'pluginOptions' => [
				        'allowClear' => true,
				        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
			        ],
		        ],
		        'value' => function ($model) {
			        $user = User::findOne($model->user_id);
			        return Html::a($user->name, Url::toRoute(['/users/users/view', 'id' => $user->id]), ['target' => '_blank']);
		        },
	        ],
	        [

		        'attribute' => 'project_id',
		        'format' => 'raw',
		        'filter' => Project::allProjectNames(true),
		        'filterType' => GridView::FILTER_SELECT2,
		        'filterWidgetOptions' => [
			        'options' => ['prompt' => ''],
			        'pluginOptions' => ['allowClear' => true, 'width'=>'140px'],
		        ],
		        'value' => function ($model) {
			        $project = Project::findOne($model->project_id);
			        if(!$project)
			            return Yii::t('app', 'n/a');

			        return Html::a($project->name, Url::toRoute(['/project/project/view', 'id' => $project->id]), ['target' => '_blank']);
		        },
	        ],
	        [
		        'attribute' => 'leader',
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
			        if($model->leader) {
				        return '<span style="color: green;"<i class="fas fa-check"></i></span>';
			        } else {
				        return '<span style="color: red;"><i class="fas fa-times"></i></span>';
			        }
		        }
	        ],
            [
                'attribute' => 'financing',
                'visible' => $this->context->userCanOne(['view_finance', 'is_recognized', 'leader_recognized']),
                'value' => function($model) {
	                $leader = ProjectMembership::getDb()->cache(function() use ($model) {
		                return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => User::current()->id, 'leader' => true]);
	                }, 30);

	                if(($leader && $this->context->userCan('leader_recognized')) || $this->context->userCanOne(['view_finance', 'is_recognized'])) {
	                    return $model->financing;
                    } else {
	                    return '-';
                    }
                }
            ],
            'member_since',

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'view'=> function() {
                        return $this->context->userCan('view_'.ProjectMembership::tableName().'');
                    },
                    'update' => function ($model) {
	                    $leader = ProjectMembership::getDb()->cache(function() use ($model) {
		                    return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => User::current()->id, 'leader' => true]);
	                    }, 30);

                        return $this->context->userCan('update_'.ProjectMembership::tableName().'') || $leader;
                    },
                    'delete' => function ($model) {
                        $leader = ProjectMembership::getDb()->cache(function() use ($model) {
                            return ProjectMembership::findOne(['project_id' => $model->project_id, 'user_id' => User::current()->id, 'leader' => true]);
                        }, 30);

                        return $this->context->userCan('delete_'.ProjectMembership::tableName().'') || $leader;
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
