<?php /** @noinspection MissedFieldInspection */

use app\modules\plants\models\Plants;
use app\modules\users\models\PermissionSet;
use app\modules\users\models\User;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
	    'panel' => [ 'footer'=>false, 'heading' => false ],
	    'toolbar'=>[ '{export}', '{toggleData}' ],

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'rowOptions' => function ($model, $key, $index, $column) {
		    if($model->is_admin) {
			    return ['class' => 'background-is_admin'];
		    }
	    },
        'columns' => [

            [
                'attribute'=>'name',
	            'format'=>'raw',
	            'filter' => User::allUserNames(),
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
		            'options' => ['prompt' => ''],
		            'pluginOptions' => [
			            'allowClear' => true,
			            'width'=>'200px',
		            ],
	            ],
                'value' => function ($model) {
                    if(!$model->password_hash && $this->context->is_admin)
                        return '<a style="color:red;" href="'.Url::to(['/users/users/view','id'=>$model->id]).'">'.$model->name.'</a>';

	                return '<a href="'.Url::to(['/users/users/view','id'=>$model->id]).'">'.$model->name.'</a>';
                }
            ],
            'email:email',
            [
                'attribute' => 'permission_set_id',
                'format' => 'raw',
                'filter' => PermissionSet::allPermissionSetNames(true),
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
		            'options' => ['prompt' => ''],
		            'pluginOptions' => [
			            'allowClear' => true,
			            'width'=>'200px',
		            ],
	            ],
                'value' => function ($model) {
                    $set = $model->permissionSet;
                    if ($set) {
                        return '<a href="'.Url::to(['/users/permissionsets/update','id'=>$set->id]).'">'.$set->toHtml().'</a>';
                    }
                    return '<span class="badge">'.Yii::t('app', 'not_defined').'</span>';
                },
            ],
            'rfid',
            [
                'attribute' => 'created_by_search',
                'format' => 'raw',
                'value' => function ($model) {
                    return ($model->creatorUser && $model->creatorUser->id <= 2) ? "Szitár-Net Kft." : ($model->creatorUser
                        ? '<a href="'.Url::to(['/users/users/view','id'=>$model->creatorUser->id]).'">'.$model->creatorUser->name.'</a>'
                        : '<span class="badge">'.Yii::t('app', 'unknown').'</span>');
                }
            ],
	        'created_at',

	        ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
		        'visibleButtons'=>[
			        'view'=> function()  {
				        return $this->context->userCan('users');
			        },
			        'update' => function ()  {
				        return $this->context->userCan('users');
			        },
			        'delete' => function ()  {
				        return $this->context->userCan('users');
			        },
		        ]
	        ],
        ],
    ]); ?>
</div>
