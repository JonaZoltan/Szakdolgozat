<?php

use app\modules\apps\models\Apps;
use app\modules\users\models\User;
use kartik\select2\Select2;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'quickmenu')." ".Yii::t('app', 'settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="quick-menu-form">

	<?php $form = ActiveForm::begin([
	    'encodeErrorSummary' => false,
    ]); ?>

	<?php if (Yii::$app->session->hasFlash('json_saved')): ?>
		<div class="alert alert-success" role="alert">
			<i class="fas fa-check-circle"></i> <?= Yii::t('app', 'success_quickmenu_changed') ?>
		</div>
	<?php endif; ?>

    <div class="form-group">
	    <?= $form->field($model, 'quickmenu')->widget(Select2::classname(), [
		    'data' => Apps::getAllQuickMenuArray(),
		    'language' => 'hu',
		    'showToggleAll' => false,
		    'options' => [
		        'placeholder' => Yii::t('app', 'switch'),
                'value' => User::current()->getQuickMenu(),
            ],
		    'pluginOptions' => [
			    'allowClear' => true,
			    'multiple' => true,
			    'maximumSelectionLength' => 10,
		    ],
	    ]);
	    ?>
    </div>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
