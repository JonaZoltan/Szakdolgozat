<?php /** @noinspection MissedFieldInspection */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\components\sendmail\models\SearchSendmail */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Automatikus emailek';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sendmail-index">

    <?php if(Yii::$app->session->getFlash('success_resend')): ?>
        <div class="alert alert-success">
            Email újraküldve!
        </div>
    <?php endif; ?>

	<h1><?= Html::encode($this->title) ?></h1>

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
					return Yii::$app->controller->renderpartial('_extra', [
						'model' => $model,
					]);
				}
			],
			'sender',
			[
			    'attribute' => 'to',
                'format' => 'raw',
                'value' => function($model) {
	                return implode(', ', $model->to);
                }
            ],
			'subject',
            [
                'attribute' => 'status',
                'format' => 'raw',
	            'filter' => [
	                '-1' => 'Folyamatban',
	                'completed' => 'Sikeres',
                    'error' => 'Hiba',
                ],
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
		            'options' => ['prompt' => '',],
		            'pluginOptions' => [
			            'allowClear' => true,
			            'width' => '100%',
			            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		            ],
	            ],
                'value' => function($model) {
	                if(!$model->status)
	                    return "Folyamatban";

	                return $model->status === 'completed'?'Sikeres':'Hiba';
                }
            ],
            'completed_time',
            'timestamp',
			//'to:ntext',
			// 'body:ntext',
			// 'status',
			// 'completed_time',
			// 'attachment:ntext',
			// 'response:ntext',
			// 'timestamp',

			['class' => 'kartik\grid\ActionColumn', 'template' => '{resend}',
				'buttons' => [
					'resend' => function ($url, $model, $key) {
						return Html::a('<span aria-hidden="true"><i class="fas fa-paper-plane"></i></span>',
                            Url::to(['/sendmail/sendmail/resend', 'id' => $model->id]),
                            ['class' => 'grid-btn']);
					},
				],
                'visibleButtons' => [
	                'resend'=> function($model) {
		                return $model->status === 'error';
	                },
                ],
            ],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>
