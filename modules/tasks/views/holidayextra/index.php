<?php /** @noinspection MissedFieldInspection */

use kartik\grid\GridView;
use yii\helpers\Html;
use app\modules\users\models\User;
use app\modules\tasks\models\HolidayExtra;


use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\SearchHolidayExtra */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'holiday_extra');
$this->params['breadcrumbs'][] = $this->title;

$dates = [];
for ($i = 2020; $i <= date('Y') + 1; $i++) {
	$dates[$i] = $i;
}


?>
<div class="holiday-extra-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $this->context->userCan('create_'.HolidayExtra::tableName().'')?Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
	            'attribute' => 'year',
	            'format' => 'raw',
	            'filter' => $dates,
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
		            'options' => ['prompt' => ''],
		            'pluginOptions' => [
			            'allowClear' => true,
                        'width' => '100px',
			            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		            ],
	            ],
            ],
            [
                'attribute' => 'disabled_user',
                'format' => 'raw',
                'value' => function($model) {
                    $disabledUsers = Json::decode($model->disabled_user);
                    if(!$disabledUsers)
                        return "-";

	                foreach ($disabledUsers as $key => $disabledUser) {
                        $disabledUsers[$key] = User::findOne($disabledUser)->name;
                    }

                    return implode("<br />", array_values($disabledUsers));
                }
            ],
            [
                'label' => 'Egyéb',
                'format' => 'raw',
                'value' => function($model) {
                    $text = "<b>Fennmaradó napok:</b> {$model->freeDay}<br />";
                    $text .= "<b>Kivett napok:</b> {$model->acceptedDay}<br />";
                    $text .= "<b>Elutasított napok:</b> {$model->declinedDay}<br />";
                    $text .= "<b>Elbírálás alatt:</b> {$model->reviewDay}<br />";

                    return $text;
                }
            ],

            ['class' => 'kartik\grid\ActionColumn', 'template' => '{view} {update} {delete}',
                'visibleButtons'=>[
                    'update' => function () {
                        return $this->context->is_admin;
                    },
                    'delete' => function () {
                        return $this->context->is_admin;
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
